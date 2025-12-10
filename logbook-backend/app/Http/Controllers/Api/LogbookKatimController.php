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

            // Get semua user yang parent_id-nya sama dengan id user yang login (staff)
            $staffMembers = User::where('parent_id', $user->id)
                ->select([
                    'id',
                    'name',
                    'nama_pegawai',
                    'nip_nrp',
                    'pangkat_golongan',
                    'nama_jabatan',
                    'email',
                    'kode_unit_organisasi',
                    'nama_unit_organisasi'
                ])
                ->get();

            // Tambahkan katim sendiri (user yang login)
            $katimData = User::where('id', $user->id)
                ->select([
                    'id',
                    'name',
                    'nama_pegawai',
                    'nip_nrp',
                    'pangkat_golongan',
                    'nama_jabatan',
                    'email',
                    'kode_unit_organisasi',
                    'nama_unit_organisasi'
                ])
                ->first();

            // Gabungkan katim + staff (katim di posisi pertama)
            $allMembers = collect([$katimData])->merge($staffMembers);

            return response()->json([
                'success' => true,
                'data' => $allMembers
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching team members', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch team members: ' . $e->getMessage()
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

            // Filter by month (pakai 'bulan' sesuai frontend)
            if ($request->has('bulan') && $request->bulan) {
                $query->whereMonth('tanggal', $request->bulan);
            }

            // Filter by year (pakai 'tahun' sesuai frontend)
            if ($request->has('tahun') && $request->tahun) {
                $query->whereYear('tanggal', $request->tahun);
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
                'member' => $member
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
            $logbook = Logbook::with('user')
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('parent_id', $user->id)
                        ->orWhere('id', $user->id);
                })
                ->findOrFail($logId);

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

            // Update catatan_katim (jika ada)
            $logbook->update([
                'catatan_katim' => $validated['catatan_katim'] ?? $logbook->catatan_katim
            ]);

            // Log untuk debugging
            \Log::info('Logbook catatan updated by Katim', [
                'logbook_id' => $logId,
                'katim_id' => $user->id,
                'staff_id' => $logbook->user_id,
                'catatan_katim' => $validated['catatan_katim'] ?? null
            ]);

            // Refresh data dari database
            $logbook->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil disimpan',
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
            \Log::error('Error updating logbook catatan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan catatan: ' . $e->getMessage()
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

            return response()->json([
                'success' => true,
                'data' => [
                    'total_members' => $teamMemberIds->count(), // Staff + Katim
                    'total_logbooks' => $totalLogbooks,
                    'pending' => $pendingLogbooks,
                    'approved' => $approvedLogbooks,
                    'rejected' => $rejectedLogbooks,
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
}
