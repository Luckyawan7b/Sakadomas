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

    // Manajemen Survei
    Route::get('/survei', [surveiController::class, 'index'])->name('survei.index');
    Route::post('/survei', [surveiController::class, 'store'])->name('survei.store');
    Route::put('/survei/{id}', [surveiController::class, 'update'])->name('survei.update');
    Route::delete('/survei/{id}', [surveiController::class, 'delete'])->name('survei.delete');

    Route::get('/transaksi/create', [transaksiController::class, 'createPesananUser'])->name('transaksi.create');
    Route::post('/transaksi/create/store', [transaksiController::class, 'storePesananUser'])->name('transaksi.create.store');

    // API Publik yang butuh login
});

/*
|--------------------------------------------------------------------------
| Pelanggan Routes — Hanya bisa diakses jika login sebagai pelanggan
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Admin Routes — Hanya bisa diakses jika login sebagai admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Manajemen Data Akun
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
    Route::get('/transaksi', [transaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/tambah', [transaksiController::class, 'store'])->name('transaksi.store');
    Route::put('/transaksi/update/{id}', [transaksiController::class, 'update'])->name('transaksi.update');
    Route::delete('/transaksi/hapus/{id}', [transaksiController::class, 'delete'])->name('transaksi.delete');
    Route::get('/transaksi/rekap', [transaksiController::class, 'rekap'])->name('transaksi.rekap');


});










