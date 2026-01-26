<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\User;
use App\Exports\AbkReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LogbookAdminController extends Controller
{
    /**
     * GET /logbook-admin/units
     * Get list of all units with logbook statistics
     */
    public function getUnitsList(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $perPage = $request->get('per_page', 10);
            $keyword = $request->get('keyword', '');
            $month = $request->get('month');
            $year = $request->get('year');

            // Get all units with user count and logbook statistics
            $query = User::select(
                'kode_unit_organisasi',
                DB::raw('COUNT(DISTINCT id) as total_pegawai')
            )
                ->whereNotNull('kode_unit_organisasi')
                ->where('kode_unit_organisasi', '!=', '')
                ->groupBy('kode_unit_organisasi');

            if ($keyword) {
                $query->having('kode_unit_organisasi', 'like', "%{$keyword}%");
            }

            $paginated = $query->orderBy('kode_unit_organisasi')
                ->paginate($perPage);

            $items = $paginated->getCollection()->map(function ($unit) use ($month, $year) {
                $userIds = User::where('kode_unit_organisasi', $unit->kode_unit_organisasi)
                    ->pluck('id');

                $logbookQuery = Logbook::whereIn('user_id', $userIds);

                if ($month) {
                    $logbookQuery->whereMonth('tanggal', $month);
                }
                if ($year) {
                    $logbookQuery->whereYear('tanggal', $year);
                }

                $totalLogs = (clone $logbookQuery)->count();
                $pending = (clone $logbookQuery)->where('status', 'Disubmit')->count();
                $approved = (clone $logbookQuery)->where('status', 'Disetujui')->count();
                $rejected = (clone $logbookQuery)->where('status', 'Ditolak')->count();

                return [
                    'kode_unit' => $unit->kode_unit_organisasi,
                    'nama_unit' => $unit->kode_unit_organisasi, // TODO: add proper unit name mapping
                    'total_pegawai' => $unit->total_pegawai,
                    'total_logbook' => $totalLogs,
                    'pending' => $pending,
                    'disetujui' => $approved,
                    'ditolak' => $rejected
                ];
            });

            return response()->json([
                'data' => $items->values()->toArray(),
                'meta' => [
                    'current_page' => $paginated->currentPage(),
                    'from' => $paginated->firstItem(),
                    'last_page' => $paginated->lastPage(),
                    'per_page' => $paginated->perPage(),
                    'to' => $paginated->lastItem(),
                    'total' => $paginated->total()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('getUnitsList error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar unit'
            ], 500);
        }
    }

    /**
     * GET /logbook-admin/pegawai
     * Get list of all pegawai
     */
    public function getAllPegawai(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $perPage = $request->get('per_page', 10);
            $keyword = $request->get('keyword', '');

            $query = User::query()
                ->where('role', '!=', 'admin'); // Exclude admin users

            if ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('fullname', 'like', "%{$keyword}%")
                        ->orWhere('name', 'like', "%{$keyword}%")
                        ->orWhere('nip', 'like', "%{$keyword}%");
                });
            }

            $paginated = $query->orderBy('fullname')
                ->paginate($perPage);

            $items = $paginated->getCollection()->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nama' => $p->fullname ?? $p->name,
                    'nip' => $p->nip,
                    'pangkat' => $p->pangkat,
                    'golongan' => $p->pangkat,
                    'jabatan' => $p->jabatan,
                    'email' => $p->email,
                    'unit_kerja' => $p->kode_unit_organisasi,
                    'role' => $p->role,
                    'tim' => $p->tim ?? $p->team ?? null
                ];
            });

            return response()->json([
                'data' => $items->values()->toArray(),
                'meta' => [
                    'current_page' => $paginated->currentPage(),
                    'from' => $paginated->firstItem(),
                    'last_page' => $paginated->lastPage(),
                    'per_page' => $paginated->perPage(),
                    'to' => $paginated->lastItem(),
                    'total' => $paginated->total()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('getAllPegawai error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar pegawai'
            ], 500);
        }
    }

    /**
     * GET /logbook-admin/units/{unitCode}/pegawai
     * Get list of pegawai in specific unit
     */
    public function getUnitPegawai(Request $request, $unitCode)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $perPage = $request->get('per_page', 10);
            $keyword = $request->get('keyword', '');

            $query = User::where('kode_unit_organisasi', $unitCode);

            if ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('fullname', 'like', "%{$keyword}%")
                        ->orWhere('name', 'like', "%{$keyword}%")
                        ->orWhere('nip', 'like', "%{$keyword}%");
                });
            }

            $paginated = $query->orderBy('fullname')
                ->paginate($perPage);

            $items = $paginated->getCollection()->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nama' => $p->fullname ?? $p->name,
                    'nip' => $p->nip,
                    'pangkat' => $p->pangkat,
                    'golongan' => $p->pangkat,
                    'jabatan' => $p->jabatan,
                    'email' => $p->email,
                    'unit_kerja' => $p->kode_unit_organisasi,
                    'role' => $p->role,
                    'tim' => $p->tim ?? $p->team ?? null
                ];
            });

            return response()->json([
                'data' => $items->values()->toArray(),
                'meta' => [
                    'current_page' => $paginated->currentPage(),
                    'from' => $paginated->firstItem(),
                    'last_page' => $paginated->lastPage(),
                    'per_page' => $paginated->perPage(),
                    'to' => $paginated->lastItem(),
                    'total' => $paginated->total()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('getUnitPegawai error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar pegawai'
            ], 500);
        }
    }

    /**
     * GET /logbook-admin/pegawai/{pegawaiId}/logs
     * Get logbook entries for specific pegawai
     */
    public function getPegawaiLogs(Request $request, $pegawaiId)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $pegawai = User::findOrFail($pegawaiId);

            $query = Logbook::where('user_id', $pegawaiId);

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->start_date && $request->end_date) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            } elseif ($request->month) {
                $query->whereMonth('tanggal', $request->month);
            }

            if ($request->year) {
                $query->whereYear('tanggal', $request->year);
            }

            $logs = $query->orderBy('tanggal', 'desc')
                ->orderBy('jam_mulai', 'desc')
                ->get()
                ->toArray();

            return response()->json(['data' => array_values($logs)]);
        } catch (\Exception $e) {
            \Log::error('getPegawaiLogs error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil logbook pegawai'
            ], 500);
        }
    }

    /**
     * GET /logbook-admin/logs/{logId}
     * Get detail of specific logbook entry
     */
    public function getLogDetail(Request $request, $logId)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $logbook = Logbook::with('user:id,fullname,name,nip')
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
     * GET /logbook-admin/summary
     * Get overall statistics across all units
     */
    public function getOverallSummary(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $month = $request->get('month', now()->month);
            $year = $request->get('year', now()->year);

            $query = Logbook::query();

            if ($month) {
                $query->whereMonth('tanggal', $month);
            }
            if ($year) {
                $query->whereYear('tanggal', $year);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_units' => User::whereNotNull('kode_unit_organisasi')
                        ->where('kode_unit_organisasi', '!=', '')
                        ->distinct('kode_unit_organisasi')
                        ->count('kode_unit_organisasi'),
                    'total_pegawai' => User::count(),
                    'total_logbook' => (clone $query)->count(),
                    'pending' => (clone $query)->where('status', 'Disubmit')->count(),
                    'disetujui' => (clone $query)->where('status', 'Disetujui')->count(),
                    'ditolak' => (clone $query)->where('status', 'Ditolak')->count()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('getOverallSummary error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil ringkasan'
            ], 500);
        }
    }

    /**
     * GET /logbook-admin/abk/download
     * Download ABK Report in Excel format
     */
    public function downloadAbkReport(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $year = $request->get('year', now()->year);

            // Log untuk debugging
            $logbookCount = Logbook::whereYear('tanggal', $year)
                ->where('status', 'Disetujui')
                ->count();
            \Log::info('ABK Download Request', [
                'year' => $year,
                'user_id' => $user->id,
                'logbook_count' => $logbookCount
            ]);

            $fileName = 'Laporan_ABK_' . $year . '.xlsx';

            return Excel::download(new AbkReportExport($year), $fileName);
        } catch (\Exception $e) {
            \Log::error('downloadAbkReport error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunduh laporan ABK: ' . $e->getMessage()
            ], 500);
        }
    }
}
