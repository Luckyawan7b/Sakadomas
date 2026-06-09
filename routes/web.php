<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\KandangController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\SurveiController;
use App\Http\Controllers\TernakController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\HargaKatalogController;

/*
|--------------------------------------------------------------------------
| Guest Routes — Hanya bisa diakses jika belum login
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/katalog', [LandingController::class, 'katalog'])->name('katalog');
Route::get('/produk/{slug}', [LandingController::class, 'detailProduk'])->name('produk.detail');

// Firebase Service Worker (Dynamic to hide secrets)
Route::get('/firebase-messaging-sw.js', function () {
    return response()->view('service-worker', [
        'config' => config('services.firebase')
    ])->header('Content-Type', 'application/ja  vascript');
});

Route::middleware('guest')->group(function () {
    // Auth Pages
    Route::get('/login', [AkunController::class, 'showLogin'])->name('login');
    Route::post('/login', [AkunController::class, 'login'])->name('login.post');

    Route::get('/register', [AkunController::class, 'showRegister'])->name('register');
    Route::post('/register', [AkunController::class, 'register'])->name('register.post');

    // Lupa Password
    Route::get('/lupa-password', [AkunController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/lupa-password', [AkunController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', [AkunController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AkunController::class, 'submitResetPassword'])->name('password.update');

    Route::get('/api/desa/{id_kecamatan}', [AkunController::class, 'getDesaByKecamatan']);

});

/*
|--------------------------------------------------------------------------
| 2. AUTHENTICATED ROUTES (Hanya bisa diakses jika SUDAH login)
| Berlaku untuk semua role (Admin & User)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AkunController::class, 'logout'])->name('logout');

    Route::get('/profile', [AkunController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [AkunController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AkunController::class, 'updatePassword'])->name('profile.password');

    // Halaman checkout - check role di controller untuk redirect admin
    Route::get('/transaksi/create', [TransaksiController::class, 'createPesananUser'])->name('transaksi.create');

    // API Publik yang butuh login
    Route::get('/api/jadwal/cek', [SurveiController::class, 'cekJadwal'])->name('jadwal.cek');

    // FCM Push Notification Token
    Route::post('/api/fcm/register', [App\Http\Controllers\Api\FcmTokenController::class, 'register'])->name('fcm.register');
    Route::post('/api/fcm/remove', [App\Http\Controllers\Api\FcmTokenController::class, 'remove'])->name('fcm.remove');
});

Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    // Kunjungan & Survei Pelanggan
    Route::get('/kunjungan', [SurveiController::class, 'indexUser'])->name('kunjungan.index');
    Route::get('/kunjungan/riwayat', [SurveiController::class, 'riwayatUser'])->name('kunjungan.riwayat');
    Route::post('/kunjungan/store', [SurveiController::class, 'storeUser'])->name('kunjungan.store');
    Route::put('/kunjungan/{id}', [SurveiController::class, 'updateUser'])->name('kunjungan.update');
    Route::delete('/kunjungan/{id}', [SurveiController::class, 'deleteUser'])->name('kunjungan.delete');

    // Transaksi Pelanggan
    Route::post('/transaksi/create/store', [TransaksiController::class, 'storePesananUser'])->name('transaksi.create.store');
    Route::get('/transaksi/pembayaran/{id}', [TransaksiController::class, 'halamanPembayaran'])->name('transaksi.pembayaran');
    Route::get('/transaksi/pengajuan-survei/{id}', [SurveiController::class, 'successPage'])->name('survei.success');
    Route::get('/transaksi/riwayat-saya', [TransaksiController::class, 'riwayatUser'])->name('transaksi.riwayat');
    Route::post('/transaksi/{id}/cancel', [TransaksiController::class, 'cancelPesananUser'])->name('transaksi.cancel');
    Route::post('/transaksi/{id}/selesai', [TransaksiController::class, 'selesaiPesananUser'])->name('transaksi.selesai');
    Route::post('/transaksi/{id}/upload-bukti', [TransaksiController::class, 'uploadBuktiUser'])->name('transaksi.upload-bukti');
    Route::post('/transaksi/{id}/ajukan-survei', [SurveiController::class, 'ajukanUlang'])->name('transaksi.ajukan-survei');
});

/*
|--------------------------------------------------------------------------
| Admin Routes — Hanya bisa diakses jika login sebagai admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin: Assign & remove ternak dari detail_transaksi
    Route::post('/transaksi/{id}/assign', [TransaksiController::class, 'assignTernakAdmin'])->name('transaksi.assign');
    Route::delete('/transaksi/detail/{id}', [TransaksiController::class, 'removeDetailTernakAdmin'])->name('transaksi.detail.remove');

    // Manajemen Kunjungan (Survei) — Admin
    Route::get('/survei', [SurveiController::class, 'indexAdmin'])->name('survei.index');
    Route::post('/survei', [SurveiController::class, 'storeAdmin'])->name('survei.store');
    Route::put('/survei/{id}', [SurveiController::class, 'updateAdmin'])->name('survei.update');
    Route::delete('/survei/{id}', [SurveiController::class, 'deleteAdmin'])->name('survei.delete');
    Route::get('/data-akun', [AkunController::class, 'index'])->name('akun.index');
    Route::get('/data-akun/{id}', [AkunController::class, 'show'])->name('akun.show');
    Route::post('/data-akun', [AkunController::class, 'store'])->name('akun.store');
    Route::get('/data-akun/{id}/edit', [AkunController::class, 'edit'])->name('akun.edit');
    Route::put('/data-akun/{id}', [AkunController::class, 'update'])->name('akun.update');
    Route::put('/data-akun/{id}/reset-password', [AkunController::class, 'resetPassword'])->name('akun.reset-password');

    // Manajemen Kandang & Kamar
    Route::get('/kandang', [KandangController::class, 'index'])->name('kandang.index');
    Route::post('/kandang', [KandangController::class, 'store'])->name('kandang.store');
    Route::put('/kandang/{id}', [KandangController::class, 'update'])->name('kandang.update');
    Route::delete('/kandang/{id}', [KandangController::class, 'delete'])->name('kandang.delete');

    Route::get('/kandang/{id}/kamar', [KamarController::class, 'showKamar'])->name('kandang.kamar');
    Route::get('/kamar', [KamarController::class, 'index'])->name('kamar.index');
    Route::post('/kamar', [KamarController::class, 'store'])->name('kamar.store');
    Route::put('/kamar/{id}', [KamarController::class, 'update'])->name('kamar.update');
    Route::delete('/kamar/{id}', [KamarController::class, 'delete'])->name('kamar.delete');
    Route::get('/kandang/{id_kandang}/kamar/{id_kamar}/ternak', [KamarController::class, 'showTernak'])->name('kamar.ternak');



    // Manajemen Ternak
    Route::get('/ternak', [TernakController::class, 'index'])->name('ternak.index');
    Route::post('/ternak', [TernakController::class, 'store'])->name('ternak.store');
    Route::put('/ternak/{id}', [TernakController::class, 'update'])->name('ternak.update');
    Route::delete('/ternak/{id}', [TernakController::class, 'delete'])->name('ternak.delete');
    Route::get('/ternak/{id}/detail', [TernakController::class, 'detail'])->name('ternak.detail');

    // Harga Katalog
    Route::get('/ternak/harga', [HargaKatalogController::class, 'index'])->name('ternak.harga.index');
    Route::post('/ternak/harga/update', [HargaKatalogController::class, 'update'])->name('ternak.harga.update');
    Route::post('/ternak/harga/sync', [HargaKatalogController::class, 'sync'])->name('ternak.harga.sync');

    // Monitoring
    Route::get('/monitoring', [MonitorController::class, 'index'])->name('monitoring.index');
    Route::post('/monitoring', [MonitorController::class, 'store'])->name('monitoring.store');
    Route::put('/monitoring/{id}', [MonitorController::class, 'update'])->name('monitoring.update');

    // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'indexAdmin'])->name('transaksi.index');
    Route::get('/transaksi/buat', [TransaksiController::class, 'createAdminForm'])->name('transaksi.create.admin');
    Route::post('/transaksi/buat', [TransaksiController::class, 'storeAdminForm'])->name('transaksi.store.admin');
    Route::post('/transaksi/tambah', [TransaksiController::class, 'storeAdmin'])->name('transaksi.store');
    Route::put('/transaksi/update/{id}', [TransaksiController::class, 'updateAdmin'])->name('transaksi.update');
    Route::delete('/transaksi/hapus/{id}', [TransaksiController::class, 'deleteAdmin'])->name('transaksi.delete');
    Route::get('/transaksi/rekap', [TransaksiController::class, 'rekapAdmin'])->name('transaksi.rekap');
    Route::get('/transaksi/invoice/{id}', [TransaksiController::class, 'printInvoiceAdmin'])->name('transaksi.invoice');

    // Keuangan
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::post('/keuangan', [KeuanganController::class, 'store'])->name('keuangan.store');
    Route::put('/keuangan/{id}', [KeuanganController::class, 'update'])->name('keuangan.update');
    Route::get('/keuangan/pdf', [KeuanganController::class, 'downloadPdf'])->name('keuangan.pdf');


});

/*
|--------------------------------------------------------------------------
| Vercel Cron Job Route
|--------------------------------------------------------------------------
| Digunakan untuk menjalankan queue worker di lingkungan Vercel.
| Vercel akan memanggil route ini secara otomatis berdasarkan konfigurasi di vercel.json.
*/
Route::get('/api/queue-worker', function () {
    // Verifikasi bahwa request datang dari Vercel Cron dengan mencocokkan CRON_SECRET
    $cronSecret = env('CRON_SECRET');
    $authHeader = request()->header('Authorization');

    if ($cronSecret && $authHeader !== 'Bearer ' . $cronSecret) {
        return response()->json(['error' => 'Unauthorized. Invalid CRON_SECRET.'], 401);
    }

    // Jalankan queue:work dan berhenti ketika queue kosong (karena ini serverless)
    \Illuminate\Support\Facades\Artisan::call('queue:work', [
        '--stop-when-empty' => true,
        '--tries' => 3
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Queue processed successfully',
        'output' => \Illuminate\Support\Facades\Artisan::output()
    ]);
});










