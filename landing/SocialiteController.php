<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

/**
 * =============================================================================
 * SocialiteController
 * Smart-Saka — Google OAuth Handler
 * =============================================================================
 *
 * Prasyarat:
 *   composer require laravel/socialite
 *
 * Tambahkan ke config/services.php:
 *   'google' => [
 *       'client_id'     => env('GOOGLE_CLIENT_ID'),
 *       'client_secret' => env('GOOGLE_CLIENT_SECRET'),
 *       'redirect'      => env('GOOGLE_REDIRECT_URI'),
 *   ],
 *
 * Tambahkan ke .env:
 *   GOOGLE_CLIENT_ID=your-client-id
 *   GOOGLE_CLIENT_SECRET=your-client-secret
 *   GOOGLE_REDIRECT_URI=https://your-app.test/auth/google/callback
 *
 * Kolom yang dibutuhkan di tabel `users`:
 *   - google_id (string, nullable)
 *   - avatar   (string, nullable) — URL foto profil Google
 * =============================================================================
 */
class SocialiteController extends Controller
{
    /**
     * Redirect ke halaman consent Google OAuth.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google setelah user menyetujui.
     * Cari user yang ada atau buat user baru, lalu login otomatis.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Login dengan Google gagal. Silakan coba lagi.']);
        }

        // Cari user berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update google_id jika login via email sebelumnya
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
            ]);
        } else {
            // Buat user baru
            $user = User::create([
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                'password'  => bcrypt(Str::random(24)), // Password random, login via Google
                // Kolom lain (username, phone, dll) bisa diisi via onboarding flow terpisah
            ]);
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }
}
