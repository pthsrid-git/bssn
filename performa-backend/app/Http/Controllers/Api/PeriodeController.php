<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeriodeController extends Controller
{
    public function index()
    {
        $periodes = Periode::orderBy('tahun_mulai', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $periodes
        ]);
    }

    public function show($id)
    {
        $periode = Periode::find($id);

        if (!$periode) {
            return response()->json([
                'success' => false,
                'message' => 'Periode tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $periode
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun_mulai' => 'required|integer|min:2000',
            'tahun_selesai' => 'required|integer|min:2000|gte:tahun_mulai',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $periode = Periode::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Periode berhasil ditambahkan',
                'data' => $periode
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan periode',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $periode = Periode::find($id);

        if (!$periode) {
            return response()->json([
                'success' => false,
                'message' => 'Periode tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tahun_mulai' => 'sometimes|required|integer|min:2000',
            'tahun_selesai' => 'sometimes|required|integer|min:2000|gte:tahun_mulai',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $periode->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Periode berhasil diupdate',
                'data' => $periode
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate periode',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $periode = Periode::find($id);

        if (!$periode) {
            return response()->json([
                'success' => false,
                'message' => 'Periode tidak ditemukan'
            ], 404);
        }

        try {
            $periode->delete();

            return response()->json([
                'success' => true,
                'message' => 'Periode berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus periode',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getActive()
    {
        $periode = Periode::active()->first();

        if (!$periode) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada periode aktif'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $periode
        ]);
    }
}
