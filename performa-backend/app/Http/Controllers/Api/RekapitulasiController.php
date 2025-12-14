<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RealisasiIntermediateLv2;
use App\Models\RealisasiImmediateLv3;
use App\Models\PerkinNko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RekapitulasiController extends Controller
{
    // ========== REALISASI INTERMEDIATE LV2 ==========

    public function getRealisasiIntermediateLv2Tahunan(Request $request)
    {
        $unitKerjaId = $request->get('unit_kerja_id');
        $tahun = $request->get('tahun');
        $perPage = $request->get('per_page', 15);

        $query = RealisasiIntermediateLv2::with([
            'penerjemahan.intermediateOutcomeLv2',
            'penerjemahan.unitKerja',
            'indicator'
        ]);

        if ($unitKerjaId) {
            $query->whereHas('penerjemahan', function ($q) use ($unitKerjaId) {
                $q->where('unit_kerja_id', $unitKerjaId);
            });
        }

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $realisasi = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $realisasi
        ]);
    }

    public function getRealisasiIntermediateLv2Triwulan(Request $request)
    {
        $unitKerjaId = $request->get('unit_kerja_id');
        $tahun = $request->get('tahun');
        $triwulan = $request->get('triwulan'); // tw1, tw2, tw3, tw4
        $perPage = $request->get('per_page', 15);

        $query = RealisasiIntermediateLv2::with([
            'penerjemahan.intermediateOutcomeLv2',
            'penerjemahan.unitKerja',
            'indicator'
        ]);

        if ($unitKerjaId) {
            $query->whereHas('penerjemahan', function ($q) use ($unitKerjaId) {
                $q->where('unit_kerja_id', $unitKerjaId);
            });
        }

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $realisasi = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $realisasi,
            'triwulan' => $triwulan
        ]);
    }

    public function storeRealisasiIntermediateLv2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'penerjemahan_intermediate_lv2_id' => 'required|exists:penerjemahan_intermediate_lv2,id',
            'indicator_id' => 'required|exists:penerjemahan_intermediate_lv2_indicators,id',
            'tahun' => 'required|integer',
            'target_tahunan' => 'nullable|numeric',
            'realisasi_tahunan' => 'nullable|numeric',
            'target_tw1' => 'nullable|numeric',
            'target_tw2' => 'nullable|numeric',
            'target_tw3' => 'nullable|numeric',
            'target_tw4' => 'nullable|numeric',
            'realisasi_tw1' => 'nullable|numeric',
            'realisasi_tw2' => 'nullable|numeric',
            'realisasi_tw3' => 'nullable|numeric',
            'realisasi_tw4' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $realisasi = RealisasiIntermediateLv2::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Realisasi berhasil ditambahkan',
                'data' => $realisasi->load(['penerjemahan', 'indicator'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan realisasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateRealisasiIntermediateLv2(Request $request, $id)
    {
        $realisasi = RealisasiIntermediateLv2::find($id);

        if (!$realisasi) {
            return response()->json([
                'success' => false,
                'message' => 'Realisasi tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'target_tahunan' => 'nullable|numeric',
            'realisasi_tahunan' => 'nullable|numeric',
            'target_tw1' => 'nullable|numeric',
            'target_tw2' => 'nullable|numeric',
            'target_tw3' => 'nullable|numeric',
            'target_tw4' => 'nullable|numeric',
            'realisasi_tw1' => 'nullable|numeric',
            'realisasi_tw2' => 'nullable|numeric',
            'realisasi_tw3' => 'nullable|numeric',
            'realisasi_tw4' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $realisasi->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Realisasi berhasil diupdate',
                'data' => $realisasi->load(['penerjemahan', 'indicator'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate realisasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ========== REALISASI IMMEDIATE LV3 ==========

    public function getRealisasiImmediateLv3Tahunan(Request $request)
    {
        $unitKerjaId = $request->get('unit_kerja_id');
        $tahun = $request->get('tahun');
        $perPage = $request->get('per_page', 15);

        $query = RealisasiImmediateLv3::with([
            'penerjemahan.immediateOutcomeLv3',
            'penerjemahan.unitKerja',
            'indicator'
        ]);

        if ($unitKerjaId) {
            $query->whereHas('penerjemahan', function ($q) use ($unitKerjaId) {
                $q->where('unit_kerja_id', $unitKerjaId);
            });
        }

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $realisasi = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $realisasi
        ]);
    }

    public function getRealisasiImmediateLv3Triwulan(Request $request)
    {
        $unitKerjaId = $request->get('unit_kerja_id');
        $tahun = $request->get('tahun');
        $triwulan = $request->get('triwulan');
        $perPage = $request->get('per_page', 15);

        $query = RealisasiImmediateLv3::with([
            'penerjemahan.immediateOutcomeLv3',
            'penerjemahan.unitKerja',
            'indicator'
        ]);

        if ($unitKerjaId) {
            $query->whereHas('penerjemahan', function ($q) use ($unitKerjaId) {
                $q->where('unit_kerja_id', $unitKerjaId);
            });
        }

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $realisasi = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $realisasi,
            'triwulan' => $triwulan
        ]);
    }

    public function storeRealisasiImmediateLv3(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'penerjemahan_immediate_lv3_id' => 'required|exists:penerjemahan_immediate_lv3,id',
            'indicator_id' => 'required|exists:penerjemahan_immediate_lv3_indicators,id',
            'tahun' => 'required|integer',
            'target_tahunan' => 'nullable|numeric',
            'realisasi_tahunan' => 'nullable|numeric',
            'target_tw1' => 'nullable|numeric',
            'target_tw2' => 'nullable|numeric',
            'target_tw3' => 'nullable|numeric',
            'target_tw4' => 'nullable|numeric',
            'realisasi_tw1' => 'nullable|numeric',
            'realisasi_tw2' => 'nullable|numeric',
            'realisasi_tw3' => 'nullable|numeric',
            'realisasi_tw4' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $realisasi = RealisasiImmediateLv3::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Realisasi berhasil ditambahkan',
                'data' => $realisasi->load(['penerjemahan', 'indicator'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan realisasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateRealisasiImmediateLv3(Request $request, $id)
    {
        $realisasi = RealisasiImmediateLv3::find($id);

        if (!$realisasi) {
            return response()->json([
                'success' => false,
                'message' => 'Realisasi tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'target_tahunan' => 'nullable|numeric',
            'realisasi_tahunan' => 'nullable|numeric',
            'target_tw1' => 'nullable|numeric',
            'target_tw2' => 'nullable|numeric',
            'target_tw3' => 'nullable|numeric',
            'target_tw4' => 'nullable|numeric',
            'realisasi_tw1' => 'nullable|numeric',
            'realisasi_tw2' => 'nullable|numeric',
            'realisasi_tw3' => 'nullable|numeric',
            'realisasi_tw4' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $realisasi->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Realisasi berhasil diupdate',
                'data' => $realisasi->load(['penerjemahan', 'indicator'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate realisasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ========== PERKIN NKO ==========

    public function getPerkinNko(Request $request)
    {
        $unitKerjaId = $request->get('unit_kerja_id');
        $tahun = $request->get('tahun');

        $query = PerkinNko::with('unitKerja');

        if ($unitKerjaId) {
            $query->where('unit_kerja_id', $unitKerjaId);
        }

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $perkinNko = $query->get();

        return response()->json([
            'success' => true,
            'data' => $perkinNko
        ]);
    }

    public function uploadPerkinNko(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'tahun' => 'required|integer',
            'file_type' => 'required|in:perkin,nko_tw1,nko_tw2,nko_tw3,nko_tw4,nko_tahunan',
            'file' => 'required|file|mimes:pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $fileType = $request->file_type;
            $fileName = time() . '_' . $fileType . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('perkin-nko', $fileName, 'public');

            $perkinNko = PerkinNko::firstOrCreate(
                [
                    'unit_kerja_id' => $request->unit_kerja_id,
                    'tahun' => $request->tahun
                ],
                []
            );

            $perkinNko->update([
                $fileType . '_file' => $filePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File berhasil diupload',
                'data' => $perkinNko
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadPerkinNko($id, $fileType)
    {
        $perkinNko = PerkinNko::find($id);

        if (!$perkinNko) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $fileColumn = $fileType . '_file';
        $filePath = $perkinNko->$fileColumn;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan'
            ], 404);
        }

        return Storage::disk('public')->download($filePath);
    }
}
