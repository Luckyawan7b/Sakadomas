{{--
|--------------------------------------------------------------------------
| View: resources/views/auth/login.blade.php
|--------------------------------------------------------------------------
| Halaman login Smart-Saka.
| Route Laravel: GET /login  → nama: login
| Controller   : App\Http\Controllers\Auth\AuthenticatedSessionController@create
--}}

@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<div class="h-screen w-full flex overflow-hidden">

    {{-- ================================================================
         KIRI: Ilustrasi / Branding Panel
    ================================================================ --}}
    <section
        class="relative hidden md:flex md:w-1/2 lg:w-3/5 h-full items-center justify-center p-24 bg-neutral-900 overflow-hidden"
        aria-hidden="true"
    >
        {{-- Background image --}}
        <img
            src="{{ asset('images/auth/login-hero.jpg') }}"
            alt=""
            class="absolute inset-0 w-full h-full object-cover opacity-80"
            loading="eager"
        >
        <div class="absolute inset-0 bg-gradient-to-tr from-primary/80 via-primary/40 to-transparent mix-blend-multiply"></div>

        {{-- Brand content --}}
        <div class="relative z-10 w-full max-w-2xl flex flex-col items-center text-center text-white page-enter">
            <p class="font-manrope text-xs uppercase tracking-[0.4em] mb-12 opacity-80">PT Sakadomas</p>
            <h2 class="font-noto-serif text-5xl lg:text-7xl font-bold leading-tight mb-8 tracking-tight">Smart-Saka</h2>
            <div class="w-16 h-[1px] bg-white/40 mb-8" aria-hidden="true"></div>
            <p class="font-manrope text-lg font-light leading-relaxed max-w-lg opacity-90 tracking-wide">
                Pilihan Cerdas Penikmat Domba
            </p>
        </div>

        <div class="absolute bottom-12 left-12 z-10 flex items-center gap-3 opacity-60" aria-hidden="true">
            <div class="w-8 h-[1px] bg-white"></div>
            <span class="font-manrope text-[10px] uppercase tracking-widest text-white">Cultivating Precision</span>
        </div>
    </section>

    {{-- ================================================================
         KANAN: Form Login
    ================================================================ --}}
    <section class="w-full md:w-1/2 lg:w-2/5 h-full bg-surface-bright flex flex-col justify-center px-8 sm:px-16 lg:px-24 relative overflow-y-auto">

        {{-- Top navigation bar (mobile only) --}}
        <header class="w-full absolute top-0 left-0 z-50 flex justify-between items-center px-8 py-6 md:hidden">
            <span class="font-noto-serif text-2xl font-bold text-primary tracking-tighter">Smart-Saka</span>
        </header>

        <div class="max-w-md w-full mx-auto py-20">

            {{-- Mobile brand headline --}}
            <div class="md:hidden mb-12">
                <span class="font-noto-serif text-2xl font-bold text-primary tracking-tighter">Smart-Saka</span>
            </div>

            {{-- Heading --}}
            <div class="mb-12">
                <h1 class="font-noto-serif text-4xl font-bold text-on-surface mb-3 tracking-tight">Selamat Datang</h1>
                <p class="font-manrope text-on-surface-variant/70 text-sm tracking-wide">Masukkan Email dan Password Anda</p>
            </div>

            {{-- Session Status (misal: "Logout berhasil") --}}
            @if (session('status'))
                <div class="mb-6 flex items-center gap-2 p-4 rounded-xl bg-primary/10 text-primary text-sm font-medium" role="status">
                    <span class="material-symbols-outlined text-base" aria-hidden="true">check_circle</span>
                    {{ session('status') }}
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-6" novalidate>
                @csrf

                {{-- Email --}}
                <div>
                    <label
                        for="email"
                        class="block font-manrope text-[11px] uppercase tracking-widest text-on-surface-variant mb-2 ml-1"
                    >
                        Alamat Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        autocomplete="email"
                        required
                        aria-required="true"
                        @if ($errors->has('email')) aria-describedby="email-error" aria-invalid="true" @endif
                        class="w-full px-5 py-4 bg-surface-container-highest border-none rounded-xl text-on-surface placeholder:text-outline/50 focus:ring-2 focus:ring-primary/30 focus:bg-surface-bright transition-all {{ $errors->has('email') ? 'ring-2 ring-error/50' : '' }}"
                    >
                    @error('email')
                        <p id="email-error" class="flex items-center gap-1 text-xs text-error mt-1.5 ml-1" role="alert">
                            <span class="material-symbols-outlined text-sm" aria-hidden="true">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label
                        for="password"
                        class="block font-manrope text-[11px] uppercase tracking-widest text-on-surface-variant mb-2 ml-1"
                    >
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                            aria-required="true"
                            @if ($errors->has('password')) aria-describedby="password-error" aria-invalid="true" @endif
                            class="pw-input w-full px-5 py-4 pr-14 bg-surface-container-highest border-none rounded-xl text-on-surface placeholder:text-outline/50 focus:ring-2 focus:ring-primary/30 focus:bg-surface-bright transition-all {{ $errors->has('password') ? 'ring-2 ring-error/50' : '' }}"
                        >
                        <button
                            type="button"
                            class="pw-toggle absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant/50 hover:text-primary transition-colors"
                            aria-label="Tampilkan atau sembunyikan kata sandi"
                            aria-pressed="false"
                        >
                            <span class="material-symbols-outlined" aria-hidden="true">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <p id="password-error" class="flex items-center gap-1 text-xs text-error mt-1.5 ml-1" role="alert">
                            <span class="material-symbols-outlined text-sm" aria-hidden="true">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="peer appearance-none w-5 h-5 rounded-lg border-2 border-outline-variant checked:bg-primary checked:border-primary transition-all duration-200 focus-visible:ring-2 focus-visible:ring-primary/40"
                            >
                            <span
                                class="material-symbols-outlined absolute text-white text-[14px] opacity-0 peer-checked:opacity-100 pointer-events-none"
                                style="font-variation-settings: 'FILL' 1;"
                                aria-hidden="true"
                            >check</span>
                        </div>
                        <span class="text-sm font-manrope text-on-surface-variant group-hover:text-on-surface transition-colors select-none">
                            Ingat Saya
                        </span>
                    </label>

                    @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            class="text-sm font-manrope text-primary font-semibold hover:underline underline-offset-4 decoration-primary/30"
                        >
                            Lupa Password?
                        </a>
                    @endif
                </div>

                {{-- Submit & Google OAuth --}}
                <div class="pt-4 space-y-4">
                    <button
                        type="submit"
                        class="w-full py-4 bg-primary text-on-primary font-bold rounded-xl shadow-lg shadow-primary/10 hover:shadow-primary/20 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                    >
                        Masuk
                    </button>

                    <div class="relative flex items-center py-2" role="separator" aria-label="Atau">
                        <div class="flex-grow border-t border-outline-variant/30"></div>
                        <span class="flex-shrink mx-4 text-[10px] font-manrope uppercase tracking-[0.2em] text-outline/60" aria-hidden="true">Atau</span>
                        <div class="flex-grow border-t border-outline-variant/30"></div>
                    </div>

                    {{-- Google OAuth — URL disesuaikan dengan package Socialite --}}
                    <a
                        href="{{ route('auth.google') }}"
                        class="w-full py-4 bg-surface-container-low border border-outline-variant/20 text-on-surface font-semibold rounded-xl flex items-center justify-center gap-3 hover:bg-surface-container transition-colors duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-outline focus-visible:ring-offset-2"
                        aria-label="Masuk menggunakan akun Google"
                    >
                        {{-- Google SVG icon (inline, tidak perlu asset eksternal) --}}
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span>Masuk Dengan Google</span>
                    </a>
                </div>
            </form>

            {{-- Register Link --}}
            <p class="mt-12 text-center font-manrope text-sm text-on-surface-variant">
                Belum Punya Akun?
                <a href="{{ route('register') }}" class="text-primary font-bold hover:underline underline-offset-4 decoration-primary/30 ml-1">
                    Daftar
                </a>
            </p>
        </div>

        {{-- Footer --}}
        <footer class="absolute bottom-6 w-full left-0 flex justify-center gap-6 px-8 pointer-events-none">
            <small class="text-on-surface-variant/40 font-manrope text-[10px] uppercase tracking-widest hidden sm:block">
                &copy; {{ date('Y') }} Smart-Saka.
            </small>
            <nav class="flex gap-4 pointer-events-auto" aria-label="Tautan kebijakan">
                <a href="{{ route('privacy') }}" class="text-on-surface-variant/50 hover:text-primary font-manrope text-[10px] uppercase tracking-widest transition-colors">
                    Privacy Policy
                </a>
                <a href="{{ route('terms') }}" class="text-on-surface-variant/50 hover:text-primary font-manrope text-[10px] uppercase tracking-widest transition-colors">
                    Terms
                </a>
            </nav>
        </footer>

    </section>
</div>
@endsection
