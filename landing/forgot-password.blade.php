{{--
|--------------------------------------------------------------------------
| View: resources/views/auth/forgot-password.blade.php
|--------------------------------------------------------------------------
| Halaman permintaan reset password Smart-Saka.
| Route Laravel: GET  /forgot-password → nama: password.request
| Controller   : App\Http\Controllers\Auth\PasswordResetLinkController@create
--}}

@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi')

@section('content')
<div class="min-h-screen flex flex-col md:flex-row bg-surface">

    {{-- ================================================================
         KIRI: Ilustrasi Editorial
    ================================================================ --}}
    <section
        class="hidden md:flex md:w-1/2 relative overflow-hidden bg-neutral-900 h-screen sticky top-0"
        aria-hidden="true"
    >
        <div class="absolute inset-0 z-0">
            <img
                src="{{ asset('images/auth/forgot-hero.jpg') }}"
                alt=""
                class="w-full h-full object-cover opacity-70"
                loading="lazy"
            >
            <div class="absolute inset-0 bg-gradient-to-tr from-black/80 via-black/40 to-primary-container/20"></div>
        </div>

        <div class="relative z-10 p-16 flex flex-col justify-end h-full page-enter">
            <div class="max-w-md">
                <span class="text-primary-fixed-dim font-manrope tracking-[0.2em] uppercase text-xs mb-6 block drop-shadow-sm">
                    Pemulihan Akun
                </span>
                <blockquote class="font-noto-serif text-5xl italic tracking-tight leading-tight text-white mb-8 drop-shadow-md">
                    "Menjaga Akses Peternakan Anda."
                </blockquote>
                <div class="h-1 w-24 bg-primary-container rounded-full"></div>
            </div>
        </div>
    </section>

    {{-- ================================================================
         KANAN: Form Forgot Password
    ================================================================ --}}
    <section class="w-full md:w-1/2 flex items-center justify-center p-8 md:p-16 bg-surface overflow-y-auto">
        <div class="w-full max-w-sm">

            {{-- Brand mark --}}
            <div class="flex items-center gap-3 mb-4" aria-hidden="true">
                <span class="text-3xl">🐏</span>
                <span class="text-2xl font-noto-serif italic text-primary font-bold">Smart-Saka</span>
            </div>

            {{-- Heading --}}
            <div class="mb-12">
                <h1 class="text-2xl font-bold text-on-surface mb-2 font-noto-serif">Lupa Kata Sandi?</h1>
                <p class="text-on-surface-variant font-manrope text-sm leading-relaxed">
                    Masukkan email yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
                </p>
            </div>

            {{-- Status: Link terkirim --}}
            @if (session('status'))
                <div
                    class="mb-8 flex items-start gap-3 p-4 rounded-xl bg-primary/10 border border-primary/20"
                    role="status"
                    aria-live="polite"
                >
                    <span class="material-symbols-outlined text-primary mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;" aria-hidden="true">mark_email_read</span>
                    <div>
                        <p class="text-[11px] font-bold text-primary uppercase tracking-widest mb-1">Email Terkirim!</p>
                        <p class="text-xs text-on-surface-variant leading-relaxed">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            {{-- Forgot Password Form --}}
            <form method="POST" action="{{ route('password.email') }}" class="space-y-8" novalidate>
                @csrf

                <div class="space-y-2">
                    <label
                        for="email"
                        class="block text-[11px] font-bold text-on-surface-variant tracking-widest uppercase ml-1"
                    >
                        Alamat Email <span class="text-error" aria-hidden="true">*</span>
                    </label>
                    <div class="relative group">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            autocomplete="email"
                            required
                            aria-required="true"
                            @if ($errors->has('email')) aria-describedby="forgot-email-error" aria-invalid="true" @endif
                            class="w-full bg-surface-container-highest text-on-surface border-none rounded-xl px-5 py-4 pr-14 focus:ring-2 focus:ring-primary/30 transition-all placeholder:text-outline/50 {{ $errors->has('email') ? 'ring-2 ring-error/50' : '' }}"
                        >
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline group-focus-within:text-primary transition-colors pointer-events-none" aria-hidden="true">mail</span>
                    </div>
                    @error('email')
                        <p id="forgot-email-error" class="flex items-center gap-1 text-xs text-error mt-1 ml-1" role="alert">
                            <span class="material-symbols-outlined text-sm" aria-hidden="true">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="space-y-6 pt-2">
                    <button
                        type="submit"
                        class="w-full bg-primary text-on-primary font-bold py-4 rounded-xl hover:shadow-lg hover:shadow-primary/20 transition-all hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                    >
                        <span>Kirim Tautan Pemulihan</span>
                        <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_forward</span>
                    </button>

                    <div class="flex items-center justify-between pt-4 border-t border-surface-container-highest">
                        <a
                            href="{{ route('login') }}"
                            class="text-on-surface-variant hover:text-primary text-sm font-bold flex items-center gap-1 transition-colors group"
                        >
                            <span class="material-symbols-outlined text-base group-hover:-translate-x-1 transition-transform" aria-hidden="true">arrow_back</span>
                            Kembali Masuk
                        </a>
                        <a href="{{ route('help') }}" class="text-on-surface-variant hover:text-primary text-sm font-medium transition-colors">
                            Butuh Bantuan?
                        </a>
                    </div>
                </div>
            </form>

            {{-- Security Notice --}}
            <aside class="mt-16 p-4 rounded-xl bg-primary-container/10 border border-primary-container/20 flex items-start gap-4" aria-label="Informasi keamanan">
                <span class="material-symbols-outlined text-primary mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;" aria-hidden="true">verified_user</span>
                <div>
                    <p class="text-[11px] font-bold text-primary uppercase tracking-widest mb-1">Keamanan Prioritas</p>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Tautan pemulihan hanya berlaku selama <strong>30 menit</strong>. Pastikan email Anda aktif.
                    </p>
                </div>
            </aside>

        </div>
    </section>
</div>
@endsection
