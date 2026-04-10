<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\ReportController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories (Admin & Petugas only)
    Route::middleware('role:admin,petugas')->group(function () {
        Route::resource('categories', CategoryController::class);
    });

    // Equipment (Admin & Petugas only)
    Route::middleware('role:admin,petugas')->group(function () {
        Route::resource('equipment', EquipmentController::class);
    });

    // Users (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Borrowings
    Route::resource('borrowings', BorrowingController::class);

    // Borrowing Actions (Admin & Petugas only)
    Route::middleware('role:admin,petugas')->group(function () {
        Route::post('/borrowings/{borrowing}/approve', [BorrowingController::class, 'approve'])->name('borrowings.approve');
        Route::post('/borrowings/{borrowing}/reject', [BorrowingController::class, 'reject'])->name('borrowings.reject');
        Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnEquipment'])->name('borrowings.return');
    });

    // Reports (Admin & Petugas only)
    Route::middleware('role:admin,petugas')->group(function () {
        Route::resource('pengembalian', PengembalianController::class);
        Route::get('/reports/borrowings', [ReportController::class, 'printBorrowings'])->name('reports.borrowings');
        Route::get('/reports/returns', [ReportController::class, 'printReturns'])->name('reports.returns');
        Route::get('/reports/equipment', [ReportController::class, 'printEquipment'])->name('reports.equipment');
    });
});

// Home route redirects to dashboard if authenticated, login if not
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

