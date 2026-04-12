<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KoreksiStokController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'active'])->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin,pemilik')->group(function () {

        Route::resource('bahan-baku', BahanBakuController::class);
        Route::resource('pemasok', PemasokController::class);
    });

    Route::middleware('role:admin')->group(function () {

        // Manajemen Pengguna
        Route::resource('users', UserController::class);
        Route::resource('pembelian', PembelianController::class);
        Route::resource('pemakaian', PemakaianController::class);

        Route::resource('koreksi-stok', KoreksiStokController::class)
            ->parameters(['koreksi-stok' => 'koreksiStok']);
    });
});