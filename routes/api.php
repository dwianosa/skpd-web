<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SuratController;
use App\Http\Controllers\Api\JenisSuratController;
use App\Http\Controllers\Api\TrackingController;
use App\Http\Controllers\Api\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API Routes
Route::prefix('v1')->group(function () {
    
    // Auth Routes
    Route::post('/login', [AuthController::class, 'login']);
    
    // Jenis Surat API
    Route::get('/jenis-surat', [JenisSuratController::class, 'index']);
    Route::get('/jenis-surat/{id}', [JenisSuratController::class, 'show']);
    
    // Tracking API
    Route::get('/tracking/{nomor_surat}', [TrackingController::class, 'track']);
    
    // Surat API (Public untuk pengajuan)
    Route::post('/surat', [SuratController::class, 'store']);
    Route::get('/surat/{nomor_surat}', [SuratController::class, 'show']);
    
    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
    });
    
    // Admin API Routes (Protected)
    Route::middleware(['auth:sanctum', 'admin.api'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [SuratController::class, 'dashboard']);
        Route::get('/surat', [SuratController::class, 'index']);
        Route::get('/surat/{id}', [SuratController::class, 'show']);
        Route::put('/surat/{id}', [SuratController::class, 'update']);
        Route::delete('/surat/{id}', [SuratController::class, 'destroy']);
        
        // Admin Jenis Surat
        Route::apiResource('jenis-surat', JenisSuratController::class);
    });
});
