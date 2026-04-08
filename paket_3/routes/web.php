<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\DashboardController;

// Public routes
Route::get('/', [AspirasiController::class, 'index']);
Route::post('/aspirasi', [AspirasiController::class, 'store']);
Route::get('/tracking', [AspirasiController::class, 'tracking']);
Route::post('/tracking', [AspirasiController::class, 'cariTracking']);

// Admin routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('admin.auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/aspirasi', [AspirasiController::class, 'list']);
    Route::post('/aspirasi/status/{id}', [AspirasiController::class, 'updateStatus']);
    Route::post('/aspirasi/progres/{id}', [AspirasiController::class, 'updateProgres']);
    Route::post('/feedback/{id}', [AspirasiController::class, 'addFeedback']);
});
