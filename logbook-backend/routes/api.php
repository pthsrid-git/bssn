<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LogbookController;
use App\Http\Controllers\Api\LogbookKatimController;
use App\Http\Controllers\Api\LogbookAtasanController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });

    Route::prefix('users')->group(function () {
        Route::get('{id}', [UserController::class, 'show']);
    });

    // Logbook routes (untuk pegawai/PKO)
    Route::apiResource('logbooks', LogbookController::class);
    Route::post('logbooks/{id}/submit', [LogbookController::class, 'submit']);

    // Logbook Katim routes (untuk ketua tim)
    Route::prefix('logbook-katim')->group(function () {
        // Get daftar anggota tim
        Route::get('team-members', [LogbookKatimController::class, 'getTeamMembers']);

        // Get summary/statistik
        Route::get('summary', [LogbookKatimController::class, 'getTeamSummary']);

        // Get logbook anggota
        Route::get('member/{memberId}/logs', [LogbookKatimController::class, 'getMemberLogs']);

        // Detail logbook
        Route::get('logs/{logId}', [LogbookKatimController::class, 'getLogDetail']);

        Route::put('logs/{logId}/catatan', [LogbookKatimController::class, 'updateCatatanKatim']);

        // Approve & Reject (update catatan_katim + status)
        Route::post('logs/{logId}/approve', [LogbookKatimController::class, 'approveLog']);
        Route::post('logs/{logId}/reject', [LogbookKatimController::class, 'rejectLog']);
    });

    // Logbook Atasan routes
    Route::prefix('logbook-atasan')->group(function () {
        // Get daftar pegawai dalam unit kerja
        Route::get('pegawai', [LogbookAtasanController::class, 'getPegawaiList']);

        // Get summary/statistik
        Route::get('summary', [LogbookAtasanController::class, 'getUnitSummary']);

        // Get logbook pegawai
        Route::get('pegawai/{pegawaiId}/logs', [LogbookAtasanController::class, 'getPegawaiLogs']);

        // Detail logbook
        Route::get('logs/{logId}', [LogbookAtasanController::class, 'getLogDetail']);

        // Approve & Reject
        Route::post('logs/{logId}/approve', [LogbookAtasanController::class, 'approveLog']);
        Route::post('logs/{logId}/reject', [LogbookAtasanController::class, 'rejectLog']);

        // Update catatan atasan saja
        Route::put('logs/{logId}/catatan', [LogbookAtasanController::class, 'updateCatatanAtasan']);
    });
});
