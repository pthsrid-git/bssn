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
     * Get daftar anggota tim (bawahan dengan parent_id = user login id)
     */
    public function getTeamMembers(Request $request)
    {
        try {
            $user = auth()->user();

            // Get semua user yang parent_id-nya sama dengan id user yang login
            $teamMembers = User::where('parent_id', $user->id)
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

            return response()->json([
                'success' => true,
                'data' => $teamMembers
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
     * Get logbook anggota tim berdasarkan member ID
     */
    public function getMemberLogs(Request $request, $memberId)
    {
        try {
            $user = auth()->user();

            // Validasi bahwa member adalah bawahan dari user yang login
            $member = User::where('id', $memberId)
                ->where('parent_id', $user->id)
                ->first();

            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Member not found or not authorized'
                ], 404);
            }

            // Query logbook
            $query = Logbook::where('user_id', $memberId);

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
     * Get detail logbook
     */
    public function getLogDetail($logId)
    {
        try {
            $user = auth()->user();

            // Get logbook dengan validasi bahwa pemilik logbook adalah bawahan user
            $logbook = Logbook::with('user')
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('parent_id', $user->id);
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
     */
    public function approveLog(Request $request, $logId)
    {
        try {
            $user = auth()->user();

            // Get logbook dengan validasi bahwa pemilik adalah bawahan
            $logbook = Logbook::whereHas('user', function ($query) use ($user) {
                $query->where('parent_id', $user->id);
            })
                ->where('status', 'Disubmit')
                ->findOrFail($logId);

            // Update hanya status dan catatan_katim
            $logbook->update([
                'status' => 'Disetujui',
                'catatan_katim' => $request->catatan ?? null
            ]);

            \Log::info('Logbook approved by Katim', [
                'logbook_id' => $logId,
                'katim_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Logbook berhasil disetujui',
                'data' => $logbook
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logbook tidak ditemukan atau tidak dapat disetujui'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error approving logbook', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui logbook: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject logbook - update status dan catatan_katim (wajib isi)
     */
    public function rejectLog(Request $request, $logId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'catatan' => 'required|string|min:10'
            ], [
                'catatan.required' => 'Alasan penolakan wajib diisi',
                'catatan.min' => 'Alasan penolakan minimal 10 karakter'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = auth()->user();

            // Get logbook dengan validasi bahwa pemilik adalah bawahan
            $logbook = Logbook::whereHas('user', function ($query) use ($user) {
                $query->where('parent_id', $user->id);
            })
                ->where('status', 'Disubmit')
                ->findOrFail($logId);

            // Update hanya status dan catatan_katim
            $logbook->update([
                'status' => 'Ditolak',
                'catatan_katim' => $request->catatan
            ]);

            \Log::info('Logbook rejected by Katim', [
                'logbook_id' => $logId,
                'katim_id' => $user->id,
                'reason' => $request->catatan
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
     * Get summary/statistik logbook tim
     */
    public function getTeamSummary(Request $request)
    {
        try {
            $user = auth()->user();
            $month = $request->month ?? now()->month;
            $year = $request->year ?? now()->year;

            // Get team member IDs
            $teamMemberIds = User::where('parent_id', $user->id)->pluck('id');

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
                    'total_members' => $teamMemberIds->count(),
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
