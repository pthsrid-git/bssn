<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\AuthInternalService;
use App\Services\MasterPegawaiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetugasLogbookController extends Controller
{
    // ========================================
    // Admin ePerforma
    // ========================================

    /**
     * GET /logbook/admin-eperforma
     * Get paginated list of Admin ePerforma
     */
    public function getAdminEperforma(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $keyword = $request->get('keyword', '');

            $query = Role::with('user')
                ->where('role', 'admin-eperforma')
                ->orderBy('created_at', 'DESC');

            if ($keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('fullname', 'like', '%' . $keyword . '%')
                        ->orWhere('name', 'like', '%' . $keyword . '%');
                });
            }

            $paginated = $query->paginate($perPage);

            $items = $paginated->getCollection()->map(function ($role) {
                return [
                    'guid' => $role->user->guid ?? $role->id,
                    'name' => $role->user->fullname ?? $role->user->name ?? '',
                ];
            });

            return response()->json([
                'data' => $items->values()->toArray(),
                'meta' => [
                    'current_page' => $paginated->currentPage(),
                    'last_page' => $paginated->lastPage(),
                    'per_page' => $paginated->perPage(),
                    'total' => $paginated->total(),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('getAdminEperforma error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data Admin ePerforma'
            ], 500);
        }
    }

    /**
     * POST /logbook/admin-eperforma
     * Add new Admin ePerforma
     */
    public function storeAdminEperforma(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();

            // Get pegawai data from master service
            $pegawaiService = app(MasterPegawaiService::class);
            $personilObj = $pegawaiService->getPegawai($data['guid']);

            if ($personilObj['success'] == 0) {
                return response()->json([
                    'success' => false,
                    'message' => $personilObj['message']
                ], 400);
            }

            $dataPegawai = $personilObj['message']['data'];

            // Create or get user
            $dataCreate = [
                'name' => $dataPegawai['nama_pegawai'],
                'fullname' => $dataPegawai['nama_pegawai'],
                'guid' => $dataPegawai['guid'],
                'kode_unit_organisasi' => $dataPegawai['kode_unitOrganisasi'] ?? null,
                'status' => 'aktif',
            ];

            $user = User::firstOrCreate(
                ['guid' => $dataPegawai['guid']],
                $dataCreate
            );

            // Create role
            $role = Role::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'role' => 'admin-eperforma',
                ],
                [
                    'role' => 'admin-eperforma',
                ]
            );

            // Assign role in auth service
            $authService = app(AuthInternalService::class);
            $assignResult = $authService->assign($data['guid'], 'admin-eperforma');

            if ($assignResult['success'] == 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $assignResult['message']
                ], 400);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'guid' => $user->guid,
                    'name' => $user->fullname ?? $user->name,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($e->getCode() === '23000' && str_contains($e->getMessage(), '1062 Duplicate entry')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role Admin ePerforma sudah diberikan ke pengguna ini.'
                ], 400);
            }

            \Log::error('storeAdminEperforma error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan Admin ePerforma: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /logbook/admin-eperforma/{guid}
     * Remove Admin ePerforma
     */
    public function destroyAdminEperforma($guid)
    {
        DB::beginTransaction();

        try {
            $user = User::where('guid', $guid)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            $role = Role::where('user_id', $user->id)
                ->where('role', 'admin-eperforma')
                ->first();

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak ditemukan'
                ], 404);
            }

            // Revoke role in auth service
            $authService = app(AuthInternalService::class);
            $authService->revoke($guid, 'admin-eperforma');

            $role->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Admin ePerforma berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('destroyAdminEperforma error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Admin ePerforma'
            ], 500);
        }
    }

    // ========================================
    // Pengelola Kinerja Organisasi (PKO)
    // ========================================

    /**
     * GET /logbook/pengelola-kinerja-organisasi
     * Get paginated list of PKO
     */
    public function getPengelolaKinerjaOrganisasi(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $keyword = $request->get('keyword', '');

            $query = Role::with('user')
                ->where('role', 'pko')
                ->orderBy('created_at', 'DESC');

            if ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('fullname', 'like', '%' . $keyword . '%')
                            ->orWhere('name', 'like', '%' . $keyword . '%')
                            ->orWhere('kode_unit_organisasi', 'like', '%' . $keyword . '%');
                    });
                });
            }

            $paginated = $query->paginate($perPage);

            $items = $paginated->getCollection()->map(function ($role) {
                return [
                    'guid' => $role->user->guid ?? $role->id,
                    'name' => $role->user->fullname ?? $role->user->name ?? '',
                    'unitKerja' => $role->user->kode_unit_organisasi ?? '',
                ];
            });

            return response()->json([
                'data' => $items->values()->toArray(),
                'meta' => [
                    'current_page' => $paginated->currentPage(),
                    'last_page' => $paginated->lastPage(),
                    'per_page' => $paginated->perPage(),
                    'total' => $paginated->total(),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('getPengelolaKinerjaOrganisasi error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data Pengelola Kinerja Organisasi'
            ], 500);
        }
    }

    /**
     * POST /logbook/pengelola-kinerja-organisasi
     * Add new PKO
     */
    public function storePengelolaKinerjaOrganisasi(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();

            // Get pegawai data from master service
            $pegawaiService = app(MasterPegawaiService::class);
            $personilObj = $pegawaiService->getPegawai($data['guid']);

            if ($personilObj['success'] == 0) {
                return response()->json([
                    'success' => false,
                    'message' => $personilObj['message']
                ], 400);
            }

            $dataPegawai = $personilObj['message']['data'];

            // Create or get user
            $dataCreate = [
                'name' => $dataPegawai['nama_pegawai'],
                'fullname' => $dataPegawai['nama_pegawai'],
                'guid' => $dataPegawai['guid'],
                'kode_unit_organisasi' => $dataPegawai['kode_unitOrganisasi'] ?? null,
                'status' => 'aktif',
            ];

            $user = User::firstOrCreate(
                ['guid' => $dataPegawai['guid']],
                $dataCreate
            );

            // Create role (unit_kerja diambil dari user)
            $role = Role::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'role' => 'pko',
                ],
                [
                    'role' => 'pko',
                ]
            );

            // Assign role in auth service
            $authService = app(AuthInternalService::class);
            $assignResult = $authService->assign($data['guid'], 'pko');

            if ($assignResult['success'] == 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $assignResult['message']
                ], 400);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'guid' => $user->guid,
                    'name' => $user->fullname ?? $user->name,
                    'unitKerja' => $user->kode_unit_organisasi,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($e->getCode() === '23000' && str_contains($e->getMessage(), '1062 Duplicate entry')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role PKO sudah diberikan ke pengguna ini.'
                ], 400);
            }

            \Log::error('storePengelolaKinerjaOrganisasi error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan Pengelola Kinerja Organisasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /logbook/pengelola-kinerja-organisasi/{guid}
     * Remove PKO
     */
    public function destroyPengelolaKinerjaOrganisasi($guid)
    {
        DB::beginTransaction();

        try {
            $user = User::where('guid', $guid)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            $role = Role::where('user_id', $user->id)
                ->where('role', 'pko')
                ->first();

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak ditemukan'
                ], 404);
            }

            // Revoke role in auth service
            $authService = app(AuthInternalService::class);
            $authService->revoke($guid, 'pko');

            $role->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengelola Kinerja Organisasi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('destroyPengelolaKinerjaOrganisasi error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Pengelola Kinerja Organisasi'
            ], 500);
        }
    }

    // ========================================
    // PMK (Pengelola Manajemen Kinerja)
    // ========================================

    /**
     * GET /logbook/pmk
     * Get paginated list of PMK
     */
    public function getPmk(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $keyword = $request->get('keyword', '');

            $query = Role::with('user')
                ->where('role', 'pmk')
                ->orderBy('created_at', 'DESC');

            if ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('fullname', 'like', '%' . $keyword . '%')
                            ->orWhere('name', 'like', '%' . $keyword . '%')
                            ->orWhere('kode_unit_organisasi', 'like', '%' . $keyword . '%');
                    });
                });
            }

            $paginated = $query->paginate($perPage);

            $items = $paginated->getCollection()->map(function ($role) {
                return [
                    'guid' => $role->user->guid ?? $role->id,
                    'name' => $role->user->fullname ?? $role->user->name ?? '',
                    'unitKerjaPko' => $role->user->kode_unit_organisasi ?? '',
                ];
            });

            return response()->json([
                'data' => $items->values()->toArray(),
                'meta' => [
                    'current_page' => $paginated->currentPage(),
                    'last_page' => $paginated->lastPage(),
                    'per_page' => $paginated->perPage(),
                    'total' => $paginated->total(),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('getPmk error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data PMK'
            ], 500);
        }
    }

    /**
     * POST /logbook/pmk
     * Add new PMK
     */
    public function storePmk(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();

            // Get pegawai data from master service
            $pegawaiService = app(MasterPegawaiService::class);
            $personilObj = $pegawaiService->getPegawai($data['guid']);

            if ($personilObj['success'] == 0) {
                return response()->json([
                    'success' => false,
                    'message' => $personilObj['message']
                ], 400);
            }

            $dataPegawai = $personilObj['message']['data'];

            // Create or get user
            $dataCreate = [
                'name' => $dataPegawai['nama_pegawai'],
                'fullname' => $dataPegawai['nama_pegawai'],
                'guid' => $dataPegawai['guid'],
                'kode_unit_organisasi' => $dataPegawai['kode_unitOrganisasi'] ?? null,
                'status' => 'aktif',
            ];

            $user = User::firstOrCreate(
                ['guid' => $dataPegawai['guid']],
                $dataCreate
            );

            // Create role (unit_kerja_pko diambil dari user)
            $role = Role::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'role' => 'pmk',
                ],
                [
                    'role' => 'pmk',
                ]
            );

            // Assign role in auth service
            $authService = app(AuthInternalService::class);
            $assignResult = $authService->assign($data['guid'], 'pmk');

            if ($assignResult['success'] == 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $assignResult['message']
                ], 400);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'guid' => $user->guid,
                    'name' => $user->fullname ?? $user->name,
                    'unitKerjaPko' => $user->kode_unit_organisasi,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($e->getCode() === '23000' && str_contains($e->getMessage(), '1062 Duplicate entry')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role PMK sudah diberikan ke pengguna ini.'
                ], 400);
            }

            \Log::error('storePmk error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan PMK: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /logbook/pmk/{guid}
     * Remove PMK
     */
    public function destroyPmk($guid)
    {
        DB::beginTransaction();

        try {
            $user = User::where('guid', $guid)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            $role = Role::where('user_id', $user->id)
                ->where('role', 'pmk')
                ->first();

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak ditemukan'
                ], 404);
            }

            // Revoke role in auth service
            $authService = app(AuthInternalService::class);
            $authService->revoke($guid, 'pmk');

            $role->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'PMK berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('destroyPmk error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus PMK'
            ], 500);
        }
    }

    // ========================================
    // Get All Pegawai (for dropdown)
    // ========================================

    /**
     * GET /pegawai
     * Get all pegawai for dropdown selection
     */
    public function getAllPegawaiForDropdown(Request $request)
    {
        try {
            $pegawaiService = app(MasterPegawaiService::class);
            $result = $pegawaiService->getAll();

            if ($result['success'] == 0) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

            $data = collect($result['message']['data'] ?? [])
                ->map(function ($item) {
                    return [
                        'guid' => $item['guid'],
                        'nama' => $item['nama_pegawai'],
                        'unit_organisasi' => $item['kode_unitOrganisasi'] ?? $item['nama_unitOrganisasi'] ?? '',
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data->values()->toArray()
            ]);
        } catch (\Exception $e) {
            \Log::error('getAllPegawaiForDropdown error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pegawai'
            ], 500);
        }
    }
}
