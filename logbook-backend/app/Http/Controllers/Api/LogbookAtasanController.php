<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LogbookAtasanController extends Controller
{
    /**
     * Get daftar pegawai dalam unit kerja atasan
     * Logic:
     * - Tampilkan hanya bawahan (child) dari Ka-unit, tidak tampilkan Ka-unit
     * - Jumlah pegawai tetap hitung dengan Ka-unit (untuk statistik)
     */
    public function getPegawaiList(Request $request)
    {
        try {
            $user = auth()->user();

            // Get semua pegawai dalam unit kerja yang sama (untuk count total)
            $allPegawaiCount = User::where('kode_unit_organisasi', $user->kode_unit_organisasi)
                ->count();

            // Get Ka-unit ID (user yang sedang login)
            $kaUnitId = $user->id;

            // Get semua child (langsung) dari Ka-unit
            $directChildren = User::where('kode_unit_organisasi', $user->kode_unit_organisasi)
                ->where('parent_id', $kaUnitId)
                ->get();

            $directChildrenIds = $directChildren->pluck('id')->toArray();

            // Get semua grandchildren (child dari child)
            $grandChildren = [];
            if (!empty($directChildrenIds)) {
                $grandChildren = User::where('kode_unit_organisasi', $user->kode_unit_organisasi)
                    ->whereIn('parent_id', $directChildrenIds)
                    ->get();
            }

            // Gabungkan direct children dan grandchildren ke dalam satu collection
            $pegawaiList = $directChildren->merge($grandChildren);

            // Sort by nama_pegawai
            $pegawaiList = $pegawaiList->sortBy('nama_pegawai')->values();

            // Tambahkan info tim (nama Katim) dan level
            $pegawaiList = $pegawaiList->map(function ($pegawai) use ($directChildrenIds) {
                if ($pegawai->parent_id) {
                    $katim = User::find($pegawai->parent_id);
                    $pegawai->tim = $katim ? $katim->nama_pegawai : '-';

                    // Tambahkan info level
                    $pegawai->level = in_array($pegawai->id, $directChildrenIds) ? 'Katim' : 'Staff';
                } else {
                    $pegawai->tim = '-';
                    $pegawai->level = 'Ka-unit';
                }

                // Select only needed fields
                return [
                    'id' => $pegawai->id,
                    'name' => $pegawai->name,
                    'nama_pegawai' => $pegawai->nama_pegawai,
                    'nip_nrp' => $pegawai->nip_nrp,
                    'pangkat_golongan' => $pegawai->pangkat_golongan,
                    'nama_jabatan' => $pegawai->nama_jabatan,
                    'email' => $pegawai->email,
                    'kode_unit_organisasi' => $pegawai->kode_unit_organisasi,
                    'nama_unit_organisasi' => $pegawai->nama_unit_organisasi,
                    'parent_id' => $pegawai->parent_id,
                    'tim' => $pegawai->tim,
                    'level' => $pegawai->level
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $pegawaiList,
                'total_pegawai' => $allPegawaiCount,
                'total_displayed' => $pegawaiList->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching pegawai list', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch pegawai list: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get logbook pegawai berdasarkan pegawai ID
     */
    public function getPegawaiLogs(Request $request, $pegawaiId)
    {
        try {
            $user = auth()->user();

            // Validasi bahwa pegawai ada di unit kerja yang sama
            $pegawai = User::where('id', $pegawaiId)
                ->where('kode_unit_organisasi', $user->kode_unit_organisasi)
                ->first();

            if (!$pegawai) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pegawai not found or not authorized'
                ], 404);
            }

            // Query logbook
            $query = Logbook::where('user_id', $pegawaiId);

            // Filter by month (bulan)
            if ($request->has('bulan') && $request->bulan) {
                $query->whereMonth('tanggal', $request->bulan);
            }

            // Filter by year (tahun)
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
                'pegawai' => $pegawai
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching pegawai logs', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch pegawai logs: ' . $e->getMessage()
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

            // Get logbook dengan validasi bahwa pemilik logbook ada di unit yang sama
            $logbook = Logbook::with('user')
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('kode_unit_organisasi', $user->kode_unit_organisasi);
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
     * Approve logbook - update status menjadi "Disetujui" dan catatan_atasan
     */
    public function approveLog(Request $request, $logId)
    {
        try {
            $user = auth()->user();

            // Validasi input catatan_atasan (opsional)
            $validated = $request->validate([
                'catatan_atasan' => 'nullable|string|max:1000'
            ]);

            // Get logbook dengan validasi unit kerja yang sama
            $logbook = Logbook::whereHas('user', function ($query) use ($user) {
                $query->where('kode_unit_organisasi', $user->kode_unit_organisasi);
            })
                ->whereIn('status', ['Disubmit', 'Disetujui Katim']) // Hanya yang sudah disubmit atau disetujui katim
                ->findOrFail($logId);

            // Tidak boleh approve logbook sendiri
            if ($logbook->user_id === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menyetujui logbook sendiri'
                ], 403);
            }

            // Update status dan catatan_atasan
            $logbook->update([
                'status' => 'Disetujui',
                'catatan_atasan' => $validated['catatan_atasan'] ?? $logbook->catatan_atasan
            ]);

            \Log::info('Logbook approved by Atasan', [
                'logbook_id' => $logId,
                'atasan_id' => $user->id,
                'staff_id' => $logbook->user_id,
                'catatan_atasan' => $validated['catatan_atasan'] ?? null
            ]);

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
                'message' => 'Logbook tidak ditemukan atau tidak dapat disetujui'
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
     * Reject logbook - update status menjadi "Ditolak" dan catatan_atasan (wajib)
     */
    public function rejectLog(Request $request, $logId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'catatan_atasan' => 'required|string|min:10'
            ], [
                'catatan_atasan.required' => 'Alasan penolakan wajib diisi',
                'catatan_atasan.min' => 'Alasan penolakan minimal 10 karakter'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = auth()->user();

            // Get logbook dengan validasi unit kerja yang sama
            $logbook = Logbook::whereHas('user', function ($query) use ($user) {
                $query->where('kode_unit_organisasi', $user->kode_unit_organisasi);
            })
                ->whereIn('status', ['Disubmit', 'Disetujui Katim'])
                ->findOrFail($logId);

            // Tidak boleh reject logbook sendiri
            if ($logbook->user_id === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menolak logbook sendiri'
                ], 403);
            }

            // Update status dan catatan_atasan
            $logbook->update([
                'status' => 'Ditolak',
                'catatan_atasan' => $request->catatan_atasan
            ]);

            \Log::info('Logbook rejected by Atasan', [
                'logbook_id' => $logId,
                'atasan_id' => $user->id,
                'staff_id' => $logbook->user_id,
                'reason' => $request->catatan_atasan
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
     * Update catatan atasan saja (tanpa mengubah status)
     */
    public function updateCatatanAtasan(Request $request, $logId)
    {
        try {
            $user = auth()->user();

            $validated = $request->validate([
                'catatan_atasan' => 'nullable|string|max:1000'
            ]);

            $logbook = Logbook::whereHas('user', function ($query) use ($user) {
                $query->where('kode_unit_organisasi', $user->kode_unit_organisasi);
            })->findOrFail($logId);

            $logbook->update([
                'catatan_atasan' => $validated['catatan_atasan']
            ]);

            $logbook->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Catatan atasan berhasil disimpan',
                'data' => $logbook
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating catatan atasan', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan catatan atasan'
            ], 500);
        }
    }

    /**
     * Get summary/statistik logbook unit
     */
    public function getUnitSummary(Request $request)
    {
        try {
            $user = auth()->user();
            $month = $request->month ?? now()->month;
            $year = $request->year ?? now()->year;

            // Get pegawai IDs dalam unit yang sama
            $pegawaiIds = User::where('kode_unit_organisasi', $user->kode_unit_organisasi)
                ->where('id', '!=', $user->id)
                ->pluck('id');

            // Get statistics
            $totalLogbooks = Logbook::whereIn('user_id', $pegawaiIds)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->count();

            $pendingLogbooks = Logbook::whereIn('user_id', $pegawaiIds)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->where('status', 'Disubmit')
                ->count();

            $approvedLogbooks = Logbook::whereIn('user_id', $pegawaiIds)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->where('status', 'Disetujui')
                ->count();

            $rejectedLogbooks = Logbook::whereIn('user_id', $pegawaiIds)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->where('status', 'Ditolak')
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_pegawai' => $pegawaiIds->count(),
                    'total_logbooks' => $totalLogbooks,
                    'pending' => $pendingLogbooks,
                    'approved' => $approvedLogbooks,
                    'rejected' => $rejectedLogbooks,
                    'month' => $month,
                    'year' => $year
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching unit summary', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch unit summary: ' . $e->getMessage()
            ], 500);
        }
    }
}
