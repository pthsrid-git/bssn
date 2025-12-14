<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PohonKinerjaController;
use App\Http\Controllers\Api\PetugasController;
use App\Http\Controllers\Api\RekapitulasiController;
use App\Http\Controllers\Api\PeriodeController;

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Periode Routes - ADD THIS BLOCK
    Route::prefix('periode')->group(function () {
        Route::get('/', [PeriodeController::class, 'index']);
        Route::get('/active', [PeriodeController::class, 'getActive']);
        Route::get('/{id}', [PeriodeController::class, 'show']);
        Route::post('/', [PeriodeController::class, 'store']);
        Route::put('/{id}', [PeriodeController::class, 'update']);
        Route::delete('/{id}', [PeriodeController::class, 'destroy']);
    });

    // Pohon Kinerja Routes
    Route::prefix('pohon-kinerja')->group(function () {
        // Final Outcome
        Route::get('/final-outcome', [PohonKinerjaController::class, 'getFinalOutcomes']);
        Route::get('/final-outcome/{id}', [PohonKinerjaController::class, 'getFinalOutcome']);
        Route::post('/final-outcome', [PohonKinerjaController::class, 'createFinalOutcome']);
        Route::put('/final-outcome/{id}', [PohonKinerjaController::class, 'updateFinalOutcome']);
        Route::delete('/final-outcome/{id}', [PohonKinerjaController::class, 'deleteFinalOutcome']);
        Route::post('/final-outcome/upload', [PohonKinerjaController::class, 'uploadFinalOutcome']);

        // Intermediate Outcome LV1
        Route::get('/intermediate-lv1', [PohonKinerjaController::class, 'getIntermediateLv1']);
        Route::get('/intermediate-lv1/{id}', [PohonKinerjaController::class, 'getIntermediateLv1Detail']);
        Route::post('/intermediate-lv1', [PohonKinerjaController::class, 'createIntermediateLv1']);
        Route::post('/intermediate-lv1/upload', [PohonKinerjaController::class, 'uploadIntermediateLv1']);

        // Intermediate Outcome LV2
        Route::get('/intermediate-lv2', [PohonKinerjaController::class, 'getIntermediateLv2']);
        Route::get('/intermediate-lv2/{id}', [PohonKinerjaController::class, 'getIntermediateLv2Detail']);
        Route::post('/intermediate-lv2', [PohonKinerjaController::class, 'createIntermediateLv2']);
        Route::post('/intermediate-lv2/upload', [PohonKinerjaController::class, 'uploadIntermediateLv2']);

        // Immediate Outcome LV3
        Route::get('/immediate-lv3', [PohonKinerjaController::class, 'getImmediateLv3']);
        Route::get('/immediate-lv3/{id}', [PohonKinerjaController::class, 'getImmediateLv3Detail']);
        Route::post('/immediate-lv3', [PohonKinerjaController::class, 'createImmediateLv3']);
        Route::post('/immediate-lv3/upload', [PohonKinerjaController::class, 'uploadImmediateLv3']);

        // Output
        Route::get('/output', [PohonKinerjaController::class, 'getOutputs']);
        Route::get('/output/{id}', [PohonKinerjaController::class, 'getOutput']);
        Route::post('/output', [PohonKinerjaController::class, 'createOutput']);
        Route::post('/output/upload', [PohonKinerjaController::class, 'uploadOutput']);

        // Penerjemahan
        Route::post('/penerjemahan-lv2/upload', [PohonKinerjaController::class, 'uploadPenerjemahanLv2']);
        Route::post('/penerjemahan-lv3/upload', [PohonKinerjaController::class, 'uploadPenerjemahanLv3']);

        // Download Template
        Route::get('/download-template/{type}', [PohonKinerjaController::class, 'downloadTemplate']);
    });

    // Petugas Routes
    Route::prefix('petugas')->group(function () {
        // Admin ePerforma
        Route::get('/admin-eperforma', [PetugasController::class, 'getAdminEperforma']);
        Route::post('/admin-eperforma', [PetugasController::class, 'createAdminEperforma']);
        Route::delete('/admin-eperforma/{id}', [PetugasController::class, 'deleteAdminEperforma']);

        // PKO
        Route::get('/pko', [PetugasController::class, 'getPKO']);
        Route::post('/pko', [PetugasController::class, 'createPKO']);
        Route::delete('/pko/{id}', [PetugasController::class, 'deletePKO']);

        // PMK
        Route::get('/pmk', [PetugasController::class, 'getPMK']);
        Route::post('/pmk', [PetugasController::class, 'createPMK']);
        Route::delete('/pmk/{id}', [PetugasController::class, 'deletePMK']);
    });

    // Rekapitulasi Routes
    Route::prefix('rekapitulasi')->group(function () {
        // Realisasi Intermediate LV2
        Route::get('/realisasi-lv2/tahunan', [RekapitulasiController::class, 'getRealisasiLv2Tahunan']);
        Route::get('/realisasi-lv2/triwulan', [RekapitulasiController::class, 'getRealisasiLv2Triwulan']);
        Route::post('/realisasi-lv2', [RekapitulasiController::class, 'createRealisasiLv2']);
        Route::put('/realisasi-lv2/{id}', [RekapitulasiController::class, 'updateRealisasiLv2']);

        // Realisasi Immediate LV3
        Route::get('/realisasi-lv3/tahunan', [RekapitulasiController::class, 'getRealisasiLv3Tahunan']);
        Route::get('/realisasi-lv3/triwulan', [RekapitulasiController::class, 'getRealisasiLv3Triwulan']);
        Route::post('/realisasi-lv3', [RekapitulasiController::class, 'createRealisasiLv3']);
        Route::put('/realisasi-lv3/{id}', [RekapitulasiController::class, 'updateRealisasiLv3']);

        // Perkin NKO
        Route::get('/perkin-nko', [RekapitulasiController::class, 'getPerkinNKO']);
        Route::post('/perkin-nko/upload', [RekapitulasiController::class, 'uploadPerkinNKO']);
        Route::get('/perkin-nko/{id}/download', [RekapitulasiController::class, 'downloadPerkinNKO']);
    });

    // Health check
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now(),
            'service' => 'ePerforma BSSN API'
        ]);
    });
});
