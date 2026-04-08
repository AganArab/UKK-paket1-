<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| DASHBOARD (PER ROLE)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->get('/admin/dashboard', function () {
    return view('dashboard.admin');
});

Route::middleware(['auth', 'role:petugas'])->get('/petugas/dashboard', function () {
    return view('dashboard.petugas');
});

Route::middleware(['auth', 'role:owner'])->get('/owner/dashboard', function () {
    return view('dashboard.owner');
});

/*
|--------------------------------------------------------------------------
| DEFAULT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('kendaraan', KendaraanController::class);
    Route::resource('tarif', TarifController::class);
    Route::resource('area', AreaController::class);
});

/*
|--------------------------------------------------------------------------
| SEMUA ROLE (ADMIN, PETUGAS, OWNER)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,petugas,owner'])->group(function () {
    // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/masuk', [TransaksiController::class, 'createMasuk'])->name('transaksi.masuk');
    Route::post('/transaksi/masuk', [TransaksiController::class, 'storeMasuk'])->name('transaksi.store.masuk');
    Route::get('/transaksi/keluar', [TransaksiController::class, 'createKeluar'])->name('transaksi.keluar');
    Route::get('/transaksi/{id}/keluar', [TransaksiController::class, 'detailKeluar'])->name('transaksi.detail.keluar');
    Route::post('/transaksi/keluar', [TransaksiController::class, 'storeKeluar'])->name('transaksi.store.keluar');
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});