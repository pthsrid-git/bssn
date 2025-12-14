<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LogbookAtasanController extends Controller
{
    /**
     * GET /logbook-atasan/pegawai
     */
    public function getPegawaiList()
    {
        try {
            $user = auth()->user();

            $pegawai = User::where('kode_unit_organisasi', $user->kode_unit_organisasi)
                ->where('id', '!=', $user->id)
                ->orderBy('fullname')
                ->get()
                ->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->fullname ?? $p->name,
                        'nip' => $p->nip,
                        'pangkat' => $p->pangkat,
                        'golongan' => $p->pangkat,
                        'jabatan' => $p->jabatan,
                        'email' => $p->email,
                        'unit_kerja' => $p->kode_unit_organisasi
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $pegawai
            ]);
        } catch (\Exception $e) {
            \Log::error('getPegawaiList error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar pegawai'
            ], 500);
        }
    }

    /**
     * GET /logbook-atasan/pegawai/{pegawaiId}/logs
     */
    public function getPegawaiLogs(Request $request, $pegawaiId)
    {
        try {
            $user = auth()->user();

            $pegawai = User::where('id', $pegawaiId)
                ->where('kode_unit_organisasi', $user->kode_unit_organisasi)
                ->firstOrFail();

            $query = Logbook::where('user_id', $pegawaiId);

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->month) {
                $query->whereMonth('tanggal', $request->month);
            }

            if ($request->year) {
                $query->whereYear('tanggal', $request->year);
            }

            $logs = $query->orderBy('tanggal', 'desc')
                ->orderBy('jam_mulai', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $logs,
                'pegawai' => [
                    'id' => $pegawai->id,
                    'nama' => $pegawai->fullname ?? $pegawai->name,
                    'nip' => $pegawai->nip
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('getPegawaiLogs error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil logbook pegawai'
            ], 500);
        }
    }

    /**
     * GET /logbook-atasan/logs/{logId}
     */
    public function getLogDetail($logId)
    {
        try {
            $user = auth()->user();

            $logbook = Logbook::with('user:id,fullname,name,nip')
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('kode_unit_organisasi', $user->kode_unit_organisasi);
                })
                ->findOrFail($logId);

            return response()->json([
                'success' => true,
                'data' => [
                    ...$logbook->toArray(),
                    'user' => [
                        'id' => $logbook->user->id,
                        'nama' => $logbook->user->fullname ?? $logbook->user->name,
                        'nip' => $logbook->user->nip
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('getLogDetail error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Logbook tidak ditemukan'
            ], 404);
        }
    }

    /**
     * POST /logbook-atasan/logs/{logId}/approve
     */
    public function approveLog(Request $request, $logId)
    {
        $validated = $request->validate([
            'catatan_atasan' => 'nullable|string|max:1000'
        ]);

        $user = auth()->user();

        $logbook = Logbook::whereHas('user', function ($q) use ($user) {
            $q->where('kode_unit_organisasi', $user->kode_unit_organisasi);
        })
            ->whereIn('status', ['Disubmit', 'Disetujui Katim'])
            ->findOrFail($logId);

        if ($logbook->user_id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menyetujui logbook sendiri'
            ], 403);
        }

        $logbook->update([
            'status' => 'Disetujui',
            'catatan_atasan' => $validated['catatan_atasan']
        ]);

        return response()->json([
            'success' => true,
            'data' => $logbook
        ]);
    }

    /**
     * POST /logbook-atasan/logs/{logId}/reject
     */
    public function rejectLog(Request $request, $logId)
    {
        $validated = $request->validate([
            'catatan_atasan' => 'required|string|min:10'
        ]);

        $user = auth()->user();

        $logbook = Logbook::whereHas('user', function ($q) use ($user) {
            $q->where('kode_unit_organisasi', $user->kode_unit_organisasi);
        })
            ->whereIn('status', ['Disubmit', 'Disetujui Katim'])
            ->findOrFail($logId);

        $logbook->update([
            'status' => 'Ditolak',
            'catatan_atasan' => $validated['catatan_atasan']
        ]);

        return response()->json([
            'success' => true,
            'data' => $logbook
        ]);
    }

    /**
     * PUT /logbook-atasan/logs/{logId}/catatan
     */
    public function updateCatatanAtasan(Request $request, $logId)
    {
        $validated = $request->validate([
            'catatan_atasan' => 'nullable|string|max:1000'
        ]);

        $user = auth()->user();

        $logbook = Logbook::whereHas('user', function ($q) use ($user) {
            $q->where('kode_unit_organisasi', $user->kode_unit_organisasi);
        })->findOrFail($logId);

        $logbook->update([
            'catatan_atasan' => $validated['catatan_atasan']
        ]);

        return response()->json([
            'success' => true,
            'data' => $logbook
        ]);
    }

    /**
     * GET /logbook-atasan/summary
     */
    public function getUnitSummary(Request $request)
    {
        $user = auth()->user();
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $pegawaiIds = User::where('kode_unit_organisasi', $user->kode_unit_organisasi)
            ->where('id', '!=', $user->id)
            ->pluck('id');

        return response()->json([
            'success' => true,
            'data' => [
                'total_logs' => Logbook::whereIn('user_id', $pegawaiIds)->count(),
                'pending' => Logbook::whereIn('user_id', $pegawaiIds)->where('status', 'Disubmit')->count(),
                'approved' => Logbook::whereIn('user_id', $pegawaiIds)->where('status', 'Disetujui')->count(),
                'rejected' => Logbook::whereIn('user_id', $pegawaiIds)->where('status', 'Ditolak')->count()
            ]
        ]);
    }
}
