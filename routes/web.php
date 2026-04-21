<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EoqController;
use App\Http\Controllers\KoreksiStokController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PermintaanBahanController;
use App\Http\Controllers\ProfileController;
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

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::middleware('role:admin,pemilik')->group(function () {

        Route::resource('bahan-baku', BahanBakuController::class);
        Route::resource('pemasok', PemasokController::class);

        Route::get('/eoq/hasil', [EoqController::class, 'hasil'])->name('eoq.hasil');

        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/stok-akhir', [LaporanController::class, 'stokAkhir'])->name('stok-akhir');
            Route::get('/bahan-masuk', [LaporanController::class, 'bahanMasuk'])->name('bahan-masuk');
            Route::get('/bahan-keluar', [LaporanController::class, 'bahanKeluar'])->name('bahan-keluar');
            Route::get('/reorder', [LaporanController::class, 'reorder'])->name('reorder');
            Route::get('/export/{type}', [LaporanController::class, 'export'])->name('export');
        });
    });

    Route::middleware('role:admin')->group(function () {

        // Manajemen Pengguna
        Route::resource('users', UserController::class);
        Route::resource('pembelian', PembelianController::class);
        Route::resource('pemakaian', PemakaianController::class);

        Route::resource('koreksi-stok', KoreksiStokController::class)
            ->parameters(['koreksi-stok' => 'koreksiStok']);

        Route::get('/eoq/setting', [EoqController::class, 'setting'])->name('eoq.setting');
        Route::post('/eoq/setting', [EoqController::class, 'storeSetting'])->name('eoq.storeSetting');
        Route::post('/eoq/hitung/{bahanBaku}', [EoqController::class, 'hitung'])->name('eoq.hitung');
        Route::post('/eoq/hitung-semua', [EoqController::class, 'hitungSemua'])->name('eoq.hitungSemua');


        Route::put('/permintaan-bahan/{permintaan}/approve', [PermintaanBahanController::class, 'approve'])->name('permintaan-bahan.approve');
        Route::put('/permintaan-bahan/{permintaan}/tolak', [PermintaanBahanController::class, 'tolak'])->name('permintaan-bahan.tolak');

    });

    Route::middleware('role:admin,produksi')->group(function () {
        Route::get('/permintaan-bahan', [PermintaanBahanController::class, 'index'])->name('permintaan-bahan.index');
        Route::get('/permintaan-bahan/create', [PermintaanBahanController::class, 'create'])->name('permintaan-bahan.create');
        Route::post('/permintaan-bahan', [PermintaanBahanController::class, 'store'])->name('permintaan-bahan.store');
        Route::get('/permintaan-bahan/{permintaan}', [PermintaanBahanController::class, 'show'])->name('permintaan-bahan.show');


        Route::get('/stok-bahan', [BahanBakuController::class, 'stokProduksi'])->name('stok-bahan');
    });
});