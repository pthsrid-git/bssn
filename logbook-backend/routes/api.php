<?php

use App\Http\Controllers\Api\LogbookController;
use App\Http\Controllers\Api\LogbookKatimController;
use App\Http\Controllers\Api\LogbookAtasanController;
use App\Http\Controllers\Api\LogbookAdminController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::get('{id}', [UserController::class, 'show']);
});

// Logbook routes (untuk pegawai/PKO)
Route::apiResource('logbook', LogbookController::class);
Route::post('logbook/{id}/submit', [LogbookController::class, 'submit']);

// Logbook Katim routes (untuk ketua tim)
Route::prefix('katim')->group(function () {
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
Route::prefix('kaunit')->group(function () {
    Route::get('pegawai', [LogbookAtasanController::class, 'getPegawaiList']);
    Route::get('pegawai/{pegawaiId}/logs', [LogbookAtasanController::class, 'getPegawaiLogs']);
    Route::get('logs/{logId}', [LogbookAtasanController::class, 'getLogDetail']);

    Route::post('logs/{logId}/approve', [LogbookAtasanController::class, 'approveLog']);
    Route::post('logs/{logId}/reject', [LogbookAtasanController::class, 'rejectLog']);

    Route::put('logs/{logId}/catatan', [LogbookAtasanController::class, 'updateCatatanAtasan']);
    Route::get('summary', [LogbookAtasanController::class, 'getUnitSummary']);
});

// Logbook Admin routes
Route::prefix('logbook-admin')->group(function () {
    Route::get('units', [LogbookAdminController::class, 'getUnitsList']);
    Route::get('pegawai', [LogbookAdminController::class, 'getAllPegawai']);
    Route::get('units/{unitCode}/pegawai', [LogbookAdminController::class, 'getUnitPegawai']);
    Route::get('pegawai/{pegawaiId}/logs', [LogbookAdminController::class, 'getPegawaiLogs']);
    Route::get('logs/{logId}', [LogbookAdminController::class, 'getLogDetail']);
    Route::get('summary', [LogbookAdminController::class, 'getOverallSummary']);
});
