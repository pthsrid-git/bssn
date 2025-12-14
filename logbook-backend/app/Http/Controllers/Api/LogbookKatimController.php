<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LogbookKatimController extends Controller
{
    /**
     * Get daftar anggota tim (bawahan + katim yang login)
     */
    public function getTeamMembers(Request $request)
    {
        try {
            $user = auth()->user();

            // Ambil hanya staff (parent_id = id user login)
            $staffMembers = User::where('parent_id', $user->id)
                ->select([
                    'id',
                    'name',
                    'fullname',
                    'nip',
                    'pangkat',
                    'jabatan',
                    'email',
                    'kode_unit_organisasi',
                    'role'
                ])
                ->get()
                ->map(function ($member) {
                    return [
                        'id' => $member->id,
                        'nama' => $member->fullname ?? $member->name,
                        'nip' => $member->nip,
                        'pangkat' => $member->pangkat ?? '-',
                        'golongan' => $this->extractGolongan($member->pangkat),
                        'jabatan' => $member->jabatan ?? '-',
                        'email' => $member->email,
                        'unit_kerja' => $member->kode_unit_organisasi,
                        'role' => $member->role
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $staffMembers
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching team members', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch team members'
            ], 500);
        }
    }


    /**
     * Get logbook anggota tim berdasarkan member ID (termasuk katim sendiri)
     */
    public function getMemberLogs(Request $request, $memberId)
    {
        try {
            $user = auth()->user();

            // Validasi bahwa member adalah bawahan dari user yang login ATAU user sendiri
            $member = User::where(function ($query) use ($user, $memberId) {
                $query->where('id', $memberId)
                    ->where(function ($q) use ($user) {
                        $q->where('parent_id', $user->id) // Staff
                            ->orWhere('id', $user->id);      // Katim sendiri
                    });
            })->first();

            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Member not found or not authorized'
                ], 404);
            }

            // Query logbook
            $query = Logbook::where('user_id', $memberId);

            // Filter by date range
            if ($request->has('start_date') && $request->start_date) {
                $query->whereDate('tanggal', '>=', $request->start_date);
            }

            if ($request->has('end_date') && $request->end_date) {
                $query->whereDate('tanggal', '<=', $request->end_date);
            }

            // Filter by month
            if ($request->has('month') && $request->month) {
                $query->whereMonth('tanggal', $request->month);
            }

            // Filter by year
            if ($request->has('year') && $request->year) {
                $query->whereYear('tanggal', $request->year);
            }

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            $logbooks = $query->orderBy('tanggal', 'desc')
                ->orderBy('jam_mulai', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $logbooks,
                'member' => [
                    'id' => $member->id,
                    'nama' => $member->fullname ?? $member->name,
                    'nip' => $member->nip,
                    'pangkat' => $member->pangkat,
                    'jabatan' => $member->jabatan,
                    'email' => $member->email
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching member logs', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch member logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detail logbook (termasuk logbook katim sendiri)
     */
    public function getLogDetail($logId)
    {
        try {
            $user = auth()->user();

            // Get logbook dengan validasi bahwa pemilik logbook adalah bawahan user ATAU user sendiri
            $logbook = Logbook::with(['user' => function ($query) {
                $query->select('id', 'name', 'fullname', 'nip', 'pangkat', 'jabatan', 'email');
            }])
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('parent_id', $user->id)
                        ->orWhere('id', $user->id);
                })
                ->findOrFail($logId);

            // Format user data
            if ($logbook->user) {
                $logbook->user->nama = $logbook->user->fullname ?? $logbook->user->name;
            }

            return response()->json([
                'success' => true,
                'data' => $logbook
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching log detail', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Logbook not found or not authorized'
            ], 404);
        }
    }

    /**
     * Approve logbook - update status dan catatan_katim
     * Note: Katim tidak bisa approve logbook sendiri, hanya staff
     */
    public function approveLog(Request $request, $logId)
    {
        try {
            $user = auth()->user();

            // Validasi input catatan_katim (opsional)
            $validated = $request->validate([
                'catatan_katim' => 'nullable|string|max:1000'
            ]);

            // Get logbook dengan validasi bahwa pemilik adalah bawahan (BUKAN logbook sendiri)
            $logbook = Logbook::whereHas('user', function ($query) use ($user) {
                $query->where('parent_id', $user->id); // Hanya staff, bukan katim sendiri
            })
                ->where('status', 'Disubmit')
                ->findOrFail($logId);

            // Cek jika ini logbook katim sendiri (double check)
            if ($logbook->user_id === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menyetujui logbook sendiri'
                ], 403);
            }

            // Update status dan catatan_katim
            $logbook->update([
                'status' => 'Disetujui',
                'catatan_katim' => $validated['catatan_katim'] ?? $logbook->catatan_katim
            ]);

            // Log untuk debugging
            \Log::info('Logbook approved by Katim', [
                'logbook_id' => $logId,
                'katim_id' => $user->id,
                'katim_name' => $user->fullname ?? $user->name,
                'staff_id' => $logbook->user_id,
                'catatan_katim' => $validated['catatan_katim'] ?? null
            ]);

            // Refresh data dari database
            $logbook->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Logbook berhasil disetujui',
                'data' => $logbook
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logbook tidak ditemukan atau tidak dapat diupdate'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error approving logbook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui logbook: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject logbook - update status dan catatan_katim (wajib isi)
     * Note: Katim tidak bisa reject logbook sendiri, hanya staff
     */
    public function rejectLog(Request $request, $logId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'catatan_katim' => 'required|string|min:10'
            ], [
                'catatan_katim.required' => 'Alasan penolakan wajib diisi',
                'catatan_katim.min' => 'Alasan penolakan minimal 10 karakter'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = auth()->user();

            // Get logbook dengan validasi bahwa pemilik adalah bawahan (BUKAN logbook sendiri)
            $logbook = Logbook::whereHas('user', function ($query) use ($user) {
                $query->where('parent_id', $user->id); // Hanya staff, bukan katim sendiri
            })
                ->where('status', 'Disubmit')
                ->findOrFail($logId);

            // Cek jika ini logbook katim sendiri (double check)
            if ($logbook->user_id === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menolak logbook sendiri'
                ], 403);
            }

            // Update status dan catatan_katim
            $logbook->update([
                'status' => 'Ditolak',
                'catatan_katim' => $request->catatan_katim
            ]);

            \Log::info('Logbook rejected by Katim', [
                'logbook_id' => $logId,
                'katim_id' => $user->id,
                'katim_name' => $user->fullname ?? $user->name,
                'staff_id' => $logbook->user_id,
                'reason' => $request->catatan_katim
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Logbook berhasil ditolak',
                'data' => $logbook
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logbook tidak ditemukan atau tidak dapat ditolak'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error rejecting logbook', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak logbook: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update catatan katim saja (tanpa mengubah status)
     */
    public function updateCatatanKatim(Request $request, $logId)
    {
        try {
            $validated = $request->validate([
                'catatan_katim' => 'required|string|max:1000'
            ]);

            $user = auth()->user();

            // Get logbook dengan validasi bahwa pemilik adalah bawahan
            $logbook = Logbook::whereHas('user', function ($query) use ($user) {
                $query->where('parent_id', $user->id);
            })->findOrFail($logId);

            // Update catatan_katim saja
            $logbook->update([
                'catatan_katim' => $validated['catatan_katim']
            ]);

            \Log::info('Catatan Katim updated', [
                'logbook_id' => $logId,
                'katim_id' => $user->id,
                'catatan' => $validated['catatan_katim']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil diperbarui',
                'data' => $logbook
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating catatan katim', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui catatan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk approve multiple logs
     */
    public function bulkApproveLog(Request $request)
    {
        try {
            $validated = $request->validate([
                'log_ids' => 'required|array|min:1',
                'log_ids.*' => 'required|integer|exists:logbooks,id',
                'catatan_katim' => 'nullable|string|max:1000'
            ]);

            $user = auth()->user();
            $approved = [];
            $failed = [];

            foreach ($validated['log_ids'] as $logId) {
                try {
                    $logbook = Logbook::whereHas('user', function ($query) use ($user) {
                        $query->where('parent_id', $user->id);
                    })
                        ->where('id', $logId)
                        ->where('status', 'Disubmit')
                        ->where('user_id', '!=', $user->id)
                        ->firstOrFail();

                    $logbook->update([
                        'status' => 'Disetujui',
                        'catatan_katim' => $validated['catatan_katim'] ?? $logbook->catatan_katim
                    ]);

                    $approved[] = $logId;
                } catch (\Exception $e) {
                    $failed[] = $logId;
                    \Log::error("Failed to approve logbook {$logId}", ['error' => $e->getMessage()]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Bulk approval completed',
                'data' => [
                    'approved' => $approved,
                    'failed' => $failed,
                    'total_approved' => count($approved),
                    'total_failed' => count($failed)
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error bulk approving logs', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal bulk approve: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk reject multiple logs
     */
    public function bulkRejectLog(Request $request)
    {
        try {
            $validated = $request->validate([
                'log_ids' => 'required|array|min:1',
                'log_ids.*' => 'required|integer|exists:logbooks,id',
                'catatan_katim' => 'required|string|min:10'
            ]);

            $user = auth()->user();
            $rejected = [];
            $failed = [];

            foreach ($validated['log_ids'] as $logId) {
                try {
                    $logbook = Logbook::whereHas('user', function ($query) use ($user) {
                        $query->where('parent_id', $user->id);
                    })
                        ->where('id', $logId)
                        ->where('status', 'Disubmit')
                        ->where('user_id', '!=', $user->id)
                        ->firstOrFail();

                    $logbook->update([
                        'status' => 'Ditolak',
                        'catatan_katim' => $validated['catatan_katim']
                    ]);

                    $rejected[] = $logId;
                } catch (\Exception $e) {
                    $failed[] = $logId;
                    \Log::error("Failed to reject logbook {$logId}", ['error' => $e->getMessage()]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Bulk rejection completed',
                'data' => [
                    'rejected' => $rejected,
                    'failed' => $failed,
                    'total_rejected' => count($rejected),
                    'total_failed' => count($failed)
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error bulk rejecting logs', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal bulk reject: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary/statistik logbook tim (termasuk katim sendiri)
     */
    public function getTeamSummary(Request $request)
    {
        try {
            $user = auth()->user();
            $month = $request->month ?? now()->month;
            $year = $request->year ?? now()->year;

            // Get team member IDs (staff + katim sendiri)
            $staffIds = User::where('parent_id', $user->id)->pluck('id');
            $teamMemberIds = $staffIds->push($user->id); // Tambahkan katim sendiri

            // Get statistics
            $totalLogbooks = Logbook::whereIn('user_id', $teamMemberIds)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->count();

            $pendingLogbooks = Logbook::whereIn('user_id', $teamMemberIds)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->where('status', 'Disubmit')
                ->count();

            $approvedLogbooks = Logbook::whereIn('user_id', $teamMemberIds)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->where('status', 'Disetujui')
                ->count();

            $rejectedLogbooks = Logbook::whereIn('user_id', $teamMemberIds)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->where('status', 'Ditolak')
                ->count();

            // Get per-member statistics
            $byMember = User::whereIn('id', $teamMemberIds)
                ->select('id', 'fullname', 'name', 'nip')
                ->get()
                ->map(function ($member) use ($month, $year) {
                    $total = Logbook::where('user_id', $member->id)
                        ->whereMonth('tanggal', $month)
                        ->whereYear('tanggal', $year)
                        ->count();

                    $pending = Logbook::where('user_id', $member->id)
                        ->whereMonth('tanggal', $month)
                        ->whereYear('tanggal', $year)
                        ->where('status', 'Disubmit')
                        ->count();

                    $approved = Logbook::where('user_id', $member->id)
                        ->whereMonth('tanggal', $month)
                        ->whereYear('tanggal', $year)
                        ->where('status', 'Disetujui')
                        ->count();

                    $rejected = Logbook::where('user_id', $member->id)
                        ->whereMonth('tanggal', $month)
                        ->whereYear('tanggal', $year)
                        ->where('status', 'Ditolak')
                        ->count();

                    return [
                        'member_id' => $member->id,
                        'member_name' => $member->fullname ?? $member->name,
                        'member_nip' => $member->nip,
                        'total' => $total,
                        'pending' => $pending,
                        'approved' => $approved,
                        'rejected' => $rejected
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'total_members' => $teamMemberIds->count(),
                    'total_logbooks' => $totalLogbooks,
                    'pending' => $pendingLogbooks,
                    'approved' => $approvedLogbooks,
                    'rejected' => $rejectedLogbooks,
                    'by_member' => $byMember,
                    'month' => $month,
                    'year' => $year
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching team summary', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch team summary: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending logs count
     */
    public function getPendingLogsCount()
    {
        try {
            $user = auth()->user();

            // Get staff IDs (tidak termasuk katim sendiri)
            $staffIds = User::where('parent_id', $user->id)->pluck('id');

            $count = Logbook::whereIn('user_id', $staffIds)
                ->where('status', 'Disubmit')
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'pending_count' => $count
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting pending count', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to get pending count: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper function to extract golongan from pangkat string
     * Example: "Penata Muda (III/a)" -> "III/a"
     */
    private function extractGolongan($pangkat)
    {
        if (!$pangkat) {
            return '-';
        }

        // Try to extract golongan from format "Pangkat (Golongan)"
        if (preg_match('/\((.*?)\)/', $pangkat, $matches)) {
            return $matches[1];
        }

        // If no golongan found, return dash
        return '-';
    }
}
