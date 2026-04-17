<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * =============================================================================
 * RegisteredUserController
 * Smart-Saka — Registrasi Akun Baru
 * =============================================================================
 * Menangani form register yang memiliki field tambahan:
 * username, phone, kecamatan, desa, address
 * =============================================================================
 */
class RegisteredUserController extends Controller
{
    /**
     * Tampilkan halaman form registrasi.
     */
    public function create(): View
    {
        // TODO: Kirim data kecamatan dari DB untuk populate dropdown
        // $kecamatanList = Kecamatan::orderBy('nama')->get();
        // return view('auth.register', compact('kecamatanList'));

        return view('auth.register');
    }

    /**
     * Proses data registrasi.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['required', 'string', 'max:50', 'unique:users,username', 'alpha_dash'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'password'  => ['required', 'confirmed', Password::defaults()],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'desa'      => ['nullable', 'string', 'max:100'],
            'address'   => ['nullable', 'string', 'max:500'],
        ], [
            // Pesan error kustom dalam Bahasa Indonesia
            'name.required'         => 'Nama lengkap wajib diisi.',
            'username.required'     => 'Username wajib diisi.',
            'username.unique'       => 'Username sudah digunakan, coba yang lain.',
            'username.alpha_dash'   => 'Username hanya boleh huruf, angka, tanda hubung, dan underscore.',
            'email.required'        => 'Alamat email wajib diisi.',
            'email.unique'          => 'Email sudah terdaftar. Silakan login atau gunakan email lain.',
            'password.required'     => 'Kata sandi wajib diisi.',
            'password.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'username'  => $validated['username'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'] ?? null,
            'password'  => Hash::make($validated['password']),
            'kecamatan' => $validated['kecamatan'] ?? null,
            'desa'      => $validated['desa'] ?? null,
            'address'   => $validated['address'] ?? null,
        ]);

        // Trigger event Registered (kirim email verifikasi jika diaktifkan)
        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard'));
    }
}
