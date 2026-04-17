{{--
|--------------------------------------------------------------------------
| View: resources/views/auth/reset-password.blade.php
|--------------------------------------------------------------------------
| Halaman atur ulang kata sandi Smart-Saka.
| Route Laravel: GET  /reset-password/{token} → nama: password.reset
| Controller   : App\Http\Controllers\Auth\NewPasswordController@create
--}}

@extends('layouts.auth')

@section('title', 'Atur Ulang Kata Sandi')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row bg-surface">

    {{-- ================================================================
         KIRI: Ilustrasi Editorial
    ================================================================ --}}
    <section
        class="hidden lg:flex lg:w-1/2 relative flex-col justify-end p-20 bg-primary-container overflow-hidden h-screen sticky top-0"
        aria-hidden="true"
    >
        <div class="absolute inset-0 z-0 bg-neutral-900">
            <img
                src="{{ asset('images/auth/reset-hero.jpg') }}"
                alt=""
                class="w-full h-full object-cover opacity-60 mix-blend-overlay"
                loading="lazy"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent"></div>
        </div>

        <div class="relative z-10 space-y-6 page-enter">
            <blockquote class="font-noto-serif text-5xl leading-tight text-white tracking-tight max-w-lg drop-shadow-md">
                "Keamanan adalah ketenangan di padang rumput."
            </blockquote>
            <div class="flex flex-col space-y-2">
                <cite class="not-italic text-primary-fixed font-manrope text-[11px] tracking-[0.2em] uppercase font-bold">
                    ~ Smart-Saka Security
                </cite>
                <div class="w-12 h-[2px] bg-primary-fixed rounded-full"></div>
            </div>
        </div>
    </section>

    {{-- ================================================================
         KANAN: Form Reset Password
    ================================================================ --}}
    <section class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-16 lg:p-24 bg-surface overflow-y-auto">
        <div class="w-full max-w-md flex flex-col">

            {{-- Hidden token & email fields (wajib untuk Laravel reset) --}}
            {{-- Token dikirim via URL, email via query string atau session --}}

            {{-- Header --}}
            <x-auth-header
                icon="lock_reset"
                :icon-filled="true"
                title="Atur Ulang Sandi"
                description="Silakan masukkan kata sandi baru Anda untuk mengamankan kembali akun Smart-Saka."
            />

            {{-- Reset Password Form --}}
            <form method="POST" action="{{ route('password.store') }}" class="space-y-6" novalidate>
                @csrf

                {{-- Hidden: token & email (dikirim dari link email) --}}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

                {{-- Kata Sandi Baru --}}
                <div class="space-y-2">
                    <label
                        for="password"
                        class="block text-[11px] font-bold uppercase tracking-widest text-on-surface-variant ml-1"
                    >
                        Kata Sandi Baru <span class="text-error" aria-hidden="true">*</span>
                    </label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors z-10 pointer-events-none" aria-hidden="true">lock</span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Min. 8 karakter"
                            autocomplete="new-password"
                            required
                            aria-required="true"
                            @if ($errors->has('password')) aria-describedby="new-pw-error" aria-invalid="true" @endif
                            class="pw-input w-full pl-12 pr-14 py-4 bg-surface-container-highest border-none rounded-xl focus:ring-2 focus:ring-primary/30 transition-all text-on-surface placeholder:text-outline/60 {{ $errors->has('password') ? 'ring-2 ring-error/50' : '' }}"
                        >
                        <button
                            type="button"
                            class="pw-toggle absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors z-10"
                            aria-label="Tampilkan atau sembunyikan kata sandi baru"
                            aria-pressed="false"
                        >
                            <span class="material-symbols-outlined" aria-hidden="true">visibility</span>
                        </button>
                    </div>

                    @error('password')
                        <p id="new-pw-error" class="flex items-center gap-1 text-xs text-error mt-1 ml-1" role="alert">
                            <span class="material-symbols-outlined text-sm" aria-hidden="true">error</span>
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- Password Strength Meter (driven by JS) --}}
                    <div class="pt-2 px-1" aria-live="polite" aria-atomic="true">
                        <div
                            id="strength-bars"
                            class="flex gap-1 h-1.5 w-full overflow-hidden"
                            role="progressbar"
                            aria-label="Kekuatan kata sandi"
                            aria-valuenow="0"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        >
                            <div class="strength-bar h-full w-1/3 bg-surface-container-highest transition-all duration-500 rounded-full"></div>
                            <div class="strength-bar h-full w-1/3 bg-surface-container-highest transition-all duration-500 rounded-full"></div>
                            <div class="strength-bar h-full w-1/3 bg-surface-container-highest transition-all duration-500 rounded-full"></div>
                        </div>
                        <p id="strength-label" class="strength-label text-[10px] uppercase tracking-widest mt-2 font-bold text-outline" aria-live="polite"></p>
                    </div>
                </div>

                {{-- Konfirmasi Kata Sandi --}}
                <div class="space-y-2">
                    <label
                        for="password_confirmation"
                        class="block text-[11px] font-bold uppercase tracking-widest text-on-surface-variant ml-1"
                    >
                        Konfirmasi Kata Sandi <span class="text-error" aria-hidden="true">*</span>
                    </label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors z-10 pointer-events-none" aria-hidden="true">shield</span>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi kata sandi baru"
                            autocomplete="new-password"
                            required
                            aria-required="true"
                            class="pw-input w-full pl-12 pr-14 py-4 bg-surface-container-highest border-none rounded-xl focus:ring-2 focus:ring-primary/30 transition-all text-on-surface placeholder:text-outline/60"
                        >
                        <button
                            type="button"
                            class="pw-toggle absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors z-10"
                            aria-label="Tampilkan atau sembunyikan konfirmasi kata sandi"
                            aria-pressed="false"
                        >
                            <span class="material-symbols-outlined" aria-hidden="true">visibility</span>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="pt-6">
                    <button
                        type="submit"
                        class="w-full bg-primary text-white py-4 px-6 rounded-xl font-bold hover:bg-primary-container hover:-translate-y-0.5 active:translate-y-0 transition-all shadow-lg shadow-primary/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            {{-- Footer Navigation --}}
            <footer class="mt-12 flex items-center justify-between border-t border-surface-container-highest pt-8">
                <a
                    href="{{ route('login') }}"
                    class="flex items-center text-sm font-bold text-on-surface-variant hover:text-primary transition-colors group"
                >
                    <span class="material-symbols-outlined text-lg mr-2 group-hover:-translate-x-1 transition-transform" aria-hidden="true">arrow_back</span>
                    Kembali Login
                </a>
                <a href="{{ route('help') }}" class="flex items-center text-sm font-bold text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-lg mr-1" aria-hidden="true">help_outline</span>
                    Bantuan
                </a>
            </footer>

        </div>
    </section>
</div>
@endsection
