<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminEperforma;
use App\Models\PengelolaKinerjaOrganisasi;
use App\Models\PenanggungJawabMengisiKinerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PetugasController extends Controller
{
    // ========== ADMIN EPERFORMA ==========

    public function getAdminEperforma()
    {
        $admins = AdminEperforma::with('user')->get();

        return response()->json([
            'success' => true,
            'data' => $admins
        ]);
    }

    public function storeAdminEperforma(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|unique:admin_eperforma,user_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $admin = AdminEperforma::create([
                'user_id' => $request->user_id
            ]);

            User::find($request->user_id)->update(['role' => 'admin_eperforma']);

            return response()->json([
                'success' => true,
                'message' => 'Admin ePerforma berhasil ditambahkan',
                'data' => $admin->load('user')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan Admin ePerforma',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteAdminEperforma($id)
    {
        $admin = AdminEperforma::find($id);

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin ePerforma tidak ditemukan'
            ], 404);
        }

        try {
            $userId = $admin->user_id;
            $admin->delete();

            User::find($userId)->update(['role' => 'pmk']);

            return response()->json([
                'success' => true,
                'message' => 'Admin ePerforma berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Admin ePerforma',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ========== PKO ==========

    public function getPKO()
    {
        $pkos = PengelolaKinerjaOrganisasi::with(['user', 'unitKerja'])->get();

        return response()->json([
            'success' => true,
            'data' => $pkos
        ]);
    }

    public function storePKO(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $pko = PengelolaKinerjaOrganisasi::create([
                'user_id' => $request->user_id,
                'unit_kerja_id' => $request->unit_kerja_id,
            ]);

            User::find($request->user_id)->update(['role' => 'pko']);

            return response()->json([
                'success' => true,
                'message' => 'PKO berhasil ditambahkan',
                'data' => $pko->load(['user', 'unitKerja'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan PKO',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deletePKO($id)
    {
        $pko = PengelolaKinerjaOrganisasi::find($id);

        if (!$pko) {
            return response()->json([
                'success' => false,
                'message' => 'PKO tidak ditemukan'
            ], 404);
        }

        try {
            $userId = $pko->user_id;
            $pko->delete();

            User::find($userId)->update(['role' => 'pmk']);

            return response()->json([
                'success' => true,
                'message' => 'PKO berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus PKO',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ========== PMK ==========

    public function getPMK()
    {
        $pmks = PenanggungJawabMengisiKinerja::with([
            'user',
            'unitKerja',
            'pengelolaKinerjaOrganisasi.user'
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $pmks
        ]);
    }

    public function storePMK(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'pko_id' => 'required|exists:pengelola_kinerja_organisasi,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $pmk = PenanggungJawabMengisiKinerja::create([
                'user_id' => $request->user_id,
                'unit_kerja_id' => $request->unit_kerja_id,
                'pko_id' => $request->pko_id,
            ]);

            User::find($request->user_id)->update(['role' => 'pmk']);

            return response()->json([
                'success' => true,
                'message' => 'PMK berhasil ditambahkan',
                'data' => $pmk->load(['user', 'unitKerja', 'pengelolaKinerjaOrganisasi'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan PMK',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deletePMK($id)
    {
        $pmk = PenanggungJawabMengisiKinerja::find($id);

        if (!$pmk) {
            return response()->json([
                'success' => false,
                'message' => 'PMK tidak ditemukan'
            ], 404);
        }

        try {
            $pmk->delete();

            return response()->json([
                'success' => true,
                'message' => 'PMK berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus PMK',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
