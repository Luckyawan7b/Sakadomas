<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\akunController;
use App\Http\Controllers\kandangController;
use App\Http\Controllers\kamarController;
use App\Http\Controllers\surveiController;
use App\Http\Controllers\ternakController;
use App\Http\Controllers\monitorController;
use App\Http\Controllers\transaksiController;
use App\Http\Controllers\LandingController;

/*
|--------------------------------------------------------------------------
| Guest Routes — Hanya bisa diakses jika belum login
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    // Auth Pages
    Route::get('/login', [akunController::class, 'showLogin'])->name('login');
    Route::post('/login', [akunController::class, 'login'])->name('login.post');

    Route::get('/register', [akunController::class, 'showRegister'])->name('register');
    Route::post('/register', [akunController::class, 'register'])->name('register.post');

    // Lupa Password
    Route::get('/lupa-password', [akunController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/lupa-password', [akunController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', [akunController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [akunController::class, 'submitResetPassword'])->name('password.update');

    Route::get('/api/desa/{id_kecamatan}', [akunController::class, 'getDesaByKecamatan']);

});

/*
|--------------------------------------------------------------------------
| 2. AUTHENTICATED ROUTES (Hanya bisa diakses jika SUDAH login)
| Berlaku untuk semua role (Admin & User)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Dashboard & Global
    Route::get('/dashboard', function () {
        return view('pages.dashboard.ecommerce', ['title' => 'SMART-SAKA | SAKADOMAS']);
    })->name('dashboard');

    // Route::get('/', [LandingController::class, 'index'])->name('home');


    Route::post('/logout', [akunController::class, 'logout'])->name('logout');

    Route::get('/profile', [akunController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [akunController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [akunController::class, 'updatePassword'])->name('profile.password');



    Route::get('/transaksi/create', [transaksiController::class, 'createPesananUser'])->name('transaksi.create');
    Route::post('/transaksi/create/store', [transaksiController::class, 'storePesananUser'])->name('transaksi.create.store');
    Route::get('/transaksi/riwayat-saya', [transaksiController::class, 'riwayatUser'])->name('transaksi.riwayat');



    // User: Batalkan pesanan
    Route::post('/transaksi/{id}/cancel', [transaksiController::class, 'cancelPesananUser'])->name('transaksi.cancel');

    // User: Selesaikan pesanan (konfirmasi terima)
    Route::post('/transaksi/{id}/selesai', [transaksiController::class, 'selesaiPesananUser'])->name('transaksi.selesai');

    // User: Upload bukti pembayaran setelah survei selesai
    Route::post('/transaksi/{id}/upload-bukti', [transaksiController::class, 'uploadBuktiUser'])->name('transaksi.upload-bukti');

    // User: Ajukan ulang survei yang dibatalkan
    Route::post('/transaksi/{id}/ajukan-survei', [surveiController::class, 'ajukanUlang'])->name('transaksi.ajukan-survei');

    // Admin: Assign & remove ternak dari detail_transaksi
    Route::post('/transaksi/{id}/assign', [transaksiController::class, 'assignTernakAdmin'])->name('transaksi.assign');
    Route::delete('/transaksi/detail/{id}', [transaksiController::class, 'removeDetailTernakAdmin'])->name('transaksi.detail.remove');


    // API Publik yang butuh login
    Route::get('/api/jadwal/cek', [surveiController::class, 'cekJadwal'])->name('jadwal.cek');
});

Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    // Kunjungan Mandiri Pelanggan
    Route::get('/kunjungan', [surveiController::class, 'indexUser'])->name('kunjungan.index');
    Route::post('/kunjungan/store', [surveiController::class, 'storeUser'])->name('kunjungan.store');
    Route::put('/kunjungan/{id}', [surveiController::class, 'updateUser'])->name('kunjungan.update');
    Route::delete('/kunjungan/{id}', [surveiController::class, 'deleteUser'])->name('kunjungan.delete');
});

/*
|--------------------------------------------------------------------------
| Admin Routes — Hanya bisa diakses jika login sebagai admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Manajemen Kunjungan (Survei) — Admin
    Route::get('/survei', [surveiController::class, 'indexAdmin'])->name('survei.index');
    Route::post('/survei', [surveiController::class, 'storeAdmin'])->name('survei.store');
    Route::put('/survei/{id}', [surveiController::class, 'updateAdmin'])->name('survei.update');
    Route::delete('/survei/{id}', [surveiController::class, 'deleteAdmin'])->name('survei.delete');
    Route::get('/data-akun', [akunController::class, 'index'])->name('akun.index');
    Route::get('/data-akun/{id}', [akunController::class, 'show'])->name('akun.show');
    Route::post('/data-akun', [akunController::class, 'store'])->name('akun.store');
    Route::get('/data-akun/{id}/edit', [akunController::class, 'edit'])->name('akun.edit');
    Route::put('/data-akun/{id}', [akunController::class, 'update'])->name('akun.update');
    Route::put('/data-akun/{id}/reset-password', [akunController::class, 'resetPassword'])->name('akun.reset-password');

    // Manajemen Kandang & Kamar
    Route::get('/kandang', [kandangController::class, 'index'])->name('kandang.index');
    Route::post('/kandang', [kandangController::class, 'store'])->name('kandang.store');
    Route::put('/kandang/{id}', [kandangController::class, 'update'])->name('kandang.update');
    Route::delete('/kandang/{id}', [kandangController::class, 'delete'])->name('kandang.delete');

    Route::get('/kandang/{id}/kamar', [kamarController::class, 'showKamar'])->name('kandang.kamar');
    Route::get('/kamar', [kamarController::class, 'index'])->name('kamar.index');
    Route::post('/kamar', [kamarController::class, 'store'])->name('kamar.store');
    Route::put('/kamar/{id}', [kamarController::class, 'update'])->name('kamar.update');
    Route::delete('/kamar/{id}', [kamarController::class, 'delete'])->name('kamar.delete');
    Route::get('/kandang/{id_kandang}/kamar/{id_kamar}/ternak', [kamarController::class, 'showTernak'])->name('kamar.ternak');



    // Manajemen Ternak
    Route::get('/ternak', [ternakController::class, 'index'])->name('ternak.index');
    Route::post('/ternak', [ternakController::class, 'store'])->name('ternak.store');
    Route::put('/ternak/{id}', [ternakController::class, 'update'])->name('ternak.update');
    Route::delete('/ternak/{id}', [ternakController::class, 'delete'])->name('ternak.delete');
    Route::get('/ternak/{id}/detail', [ternakController::class, 'detail'])->name('ternak.detail');

    // Monitoring
    Route::get('/monitoring', [monitorController::class, 'index'])->name('monitoring.index');
    Route::post('/monitoring', [monitorController::class, 'store'])->name('monitoring.store');
    Route::put('/monitoring/{id}', [monitorController::class, 'update'])->name('monitoring.update');
    Route::delete('/monitoring/{id}', [monitorController::class, 'delete'])->name('monitoring.delete');

    // Transaksi
    Route::get('/transaksi', [transaksiController::class, 'indexAdmin'])->name('transaksi.index');
    Route::post('/transaksi/tambah', [transaksiController::class, 'storeAdmin'])->name('transaksi.store');
    Route::put('/transaksi/update/{id}', [transaksiController::class, 'updateAdmin'])->name('transaksi.update');
    Route::delete('/transaksi/hapus/{id}', [transaksiController::class, 'deleteAdmin'])->name('transaksi.delete');
    Route::get('/transaksi/rekap', [transaksiController::class, 'rekapAdmin'])->name('transaksi.rekap');


});










