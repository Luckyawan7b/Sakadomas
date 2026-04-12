<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\akunController;
use App\Http\Controllers\kandangController;
use App\Http\Controllers\kamarController;
use App\Http\Controllers\surveiController;
use App\Http\Controllers\ternakController;
use App\Http\Controllers\monitorController;


// dashboard pages
Route::get('/', function () {
    return view('pages.dashboard.ecommerce', ['title' => 'SMART-SAKA | SAKADOMAS']);
})->name('dashboard');

// Route::get('/test', function () {
//     return view('pages.dashboard.testingDashboard', ['title' => 'SMART-SAKA | SAKADOMAS']);
// })->name('dashboard2');

Route::get('/test', function () {
    return view('pages.tesLogin', ['title' => 'SMART-SAKA | SAKADOMAS']);
})->name('dashboard2');

// calender pages
// Route::get('/calendar', function () {
//     return view('pages.calender', ['title' => 'Calendar']);
// })->name('calendar');

// profile pages
// Route::get('/profile', function () {
//     return view('pages.profile', ['title' => 'Profile']);
// })->name('profile');

// form pages
// Route::get('/form-elements', function () {
//     return view('pages.form.form-elements', ['title' => 'Form Elements']);
// })->name('form-elements');

// // tables pages
// Route::get('/basic-tables', function () {
//     return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
// })->name('basic-tables');

// // pages

// Route::get('/blank', function () {
//     return view('pages.blank', ['title' => 'Blank']);
// })->name('blank');

// // error pages
// Route::get('/error-404', function () {
//     return view('pages.errors.error-404', ['title' => 'Error 404']);
// })->name('error-404');

// // chart pages
// Route::get('/line-chart', function () {
//     return view('pages.chart.line-chart', ['title' => 'Line Chart']);
// })->name('line-chart');

// Route::get('/bar-chart', function () {
//     return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
// })->name('bar-chart');


// authentication pages
// Route::get('/signin', function () {
//     return view('pages.auth.signin', ['title' => 'Sign In']);
// })->name('signin');

// Route::get('/signup', function () {
//     return view('pages.auth.signup', ['title' => 'Sign Up']);
// })->name('signup');


// Menampilkan halaman login
Route::get('/login', [akunController::class, 'showLogin'])->name('login');

// Memproses data login
Route::post('/login', [akunController::class, 'login'])->name('login.post');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware('auth');

Route::get('/register', [akunController::class, 'showRegister'
])->name('register');
Route::post('/register', [akunController::class, 'register'
])->name('register.post');
Route::post('/logout', [akunController::class, 'logout'])->name('logout');

// Lupa Password saya lupa
Route::get('/lupa-password', [akunController::class, 'showForgotPassword'])->name('password.request');
Route::post('/lupa-password', [akunController::class, 'sendResetLink'])->name('password.email');

// Reset Password link email
Route::get('/reset-password/{token}', [akunController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [akunController::class, 'submitResetPassword'])->name('password.update');

// Route API untuk Dropdown Desa dinamis
Route::get('/api/desa/{id_kecamatan}', [akunController::class, 'getDesaByKecamatan']);

// ui elements pages
// Route::get('/alerts', function () {
//     return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
// })->name('alerts');

// Route::get('/avatars', function () {
//     return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
// })->name('avatars');

// Route::get('/badge', function () {
//     return view('pages.ui-elements.badges', ['title' => 'Badges']);
// })->name('badges');

// Route::get('/buttons', function () {
//     return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
// })->name('buttons');

// Route::get('/image', function () {
//     return view('pages.ui-elements.images', ['title' => 'Images']);
// })->name('images');

// Route::get('/videos', function () {
//     return view('pages.ui-elements.videos', ['title' => 'Videos']);
// })->name('videos');

//Manajemen Data Akun
Route::get('/data-akun', [akunController::class, 'index'])->name('akun.index');
Route::get('/data-akun/{id}', [akunController::class, 'show'])->name('akun.show');
Route::post('/data-akun', [akunController::class, 'store'])->name('akun.store');
Route::get('/data-akun/{id}/edit', [akunController::class, 'edit'])->name('akun.edit');
Route::put('/data-akun/{id}', [akunController::class, 'update'])->name('akun.update');

Route::get('/profile', [akunController::class, 'profile'])
    ->middleware('auth')
    ->name('profile');

Route::put('/profile/update', [akunController::class, 'updateProfile'])->name('profile.update');
Route::put('/profile/password', [akunController::class, 'updatePassword'])->name('profile.password');
Route::put('/data-akun/{id}/reset-password', [akunController::class, 'resetPassword'])->name('akun.reset-password');

Route::get('/kandang', [kandangController::class, 'index'])->name('kandang.index');
Route::post('/kandang', [kandangController::class, 'store'])->name('kandang.store');
Route::put('/kandang/{id}', [kandangController::class, 'update'])->name('kandang.update');
Route::delete('/kandang/{id}', [kandangController::class, 'delete'])->name('kandang.delete');

Route::get('/kandang/{id}/kamar', [kamarController::class, 'showKamar'])->name('kandang.kamar');

Route::get('/kamar', [kamarController::class, 'index'])->name('kamar.index');
Route::post('/kamar', [kamarController::class, 'store'])->name('kamar.store');
Route::put('/kamar/{id}', [kamarController::class, 'update'])->name('kamar.update');
Route::delete('/kamar/{id}', [kamarController::class, 'delete'])->name('kamar.delete');

Route::get('/survei', [surveiController::class, 'index'])->name('survei.index');
Route::post('/survei', [surveiController::class, 'store'])->name('survei.store');
Route::put('/survei/{id}', [surveiController::class, 'update'])->name('survei.update');
Route::delete('/survei/{id}', [surveiController::class, 'delete'])->name('survei.delete');

Route::get('/ternak', [ternakController::class, 'index'])->name('ternak.index');
Route::post('/ternak', [ternakController::class, 'store'])->name('ternak.store');
Route::put('/ternak/{id}', [ternakController::class, 'update'])->name('ternak.update');
Route::delete('/ternak/{id}', [ternakController::class, 'delete'])->name('ternak.delete');
Route::get('/ternak/{id}/detail', [ternakController::class, 'detail'])->name('ternak.detail');


Route::get('/kandang/{id_kandang}/kamar/{id_kamar}/ternak', [kamarController::class, 'showTernak'])->name('kamar.ternak');

Route::get('/monitoring', [monitorController::class, 'index'])->name('monitoring.index');
Route::post('/monitoring', [monitorController::class, 'store'])->name('monitoring.store');
Route::put('/monitoring/{id}', [monitorController::class, 'update'])->name('monitoring.update');
Route::delete('/monitoring/{id}', [monitorController::class, 'delete'])->name('monitoring.delete');












