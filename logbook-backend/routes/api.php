<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LogbookController;
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
        Route::get('profile', [AuthController::class, 'profile']);
    });

    // Logbook routes
    Route::apiResource('logbooks', LogbookController::class);
    Route::post('logbooks/{id}/submit', [LogbookController::class, 'submit']);
});
