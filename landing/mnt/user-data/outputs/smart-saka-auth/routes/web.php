<?php

/**
 * =============================================================================
 * routes/web.php — Smart-Saka
 * Route lengkap: Landing Page + Auth + Static pages
 * =============================================================================
 *
 * Semua named route di sini direferensikan oleh file Blade.
 * Jangan ubah nama route tanpa mengupdate view terkait.
 *
 * Jika menggunakan Laravel Breeze/Jetstream:
 * - Route auth standar sudah ada di routes/auth.php
 * - Cukup tambahkan route landing, blog, dan static pages di sini
 * - Hapus duplikat route auth dari file ini
 * =============================================================================
 */

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;


/* =============================================================================
   PUBLIC — Landing Page
   ============================================================================= */

Route::get('/', [LandingController::class, 'index'])->name('home');


/* =============================================================================
   GUEST ONLY — Auth routes
   Middleware 'guest' mencegah user yang sudah login mengakses halaman ini.
   ============================================================================= */

Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login',  [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    // Register
    Route::get('/register',  [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // Forgot Password
    Route::get('/forgot-password',  [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // Reset Password
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password',        [NewPasswordController::class, 'store'])->name('password.store');

    // Google OAuth — uncomment setelah composer require laravel/socialite
    //
    // Route::get('/auth/google',          [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
    // Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');

});


/* =============================================================================
   AUTHENTICATED — Dashboard dan halaman dalam aplikasi
   ============================================================================= */

Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard — ganti dengan controller yang sesuai saat fitur dashboard sudah ada
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

});


/* =============================================================================
   STATIC / INFORMATIONAL PAGES
   Direferensikan di footer landing page dan halaman auth.
   Ganti view() dengan controller jika halaman perlu data dinamis.
   ============================================================================= */

Route::get('/privacy', fn () => view('legal.privacy'))->name('privacy');
Route::get('/terms',   fn () => view('legal.terms'))->name('terms');
Route::get('/help',    fn () => view('help.index'))->name('help');


/* =============================================================================
   BLOG — Placeholder route
   Uncomment dan sesuaikan setelah BlogController dibuat.
   ============================================================================= */

// Route::get('/blog',       [BlogController::class, 'index'])->name('blog.index');
// Route::get('/blog/{slug}',[BlogController::class, 'show'])->name('blog.show');

// Sementara, diarahkan ke halaman landing agar tidak 404
Route::get('/blog', fn () => redirect()->route('home'))->name('blog.index');


/* =============================================================================
   NEWSLETTER — API endpoint untuk form subscribe
   Uncomment dan buat NewsletterController saat siap.
   ============================================================================= */

// Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
//     ->name('newsletter.subscribe');
