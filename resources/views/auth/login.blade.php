{{--
|--------------------------------------------------------------------------
| View: resources/views/auth/login.blade.php
|--------------------------------------------------------------------------
| Halaman login Smart-Saka.
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
    >
        <img
            {{-- src="{{ asset('images/auth/login-hero.jpg') }}" --}}
            src="https://media.istockphoto.com/id/1393090476/id/foto/shepherd-menempatkan-domba-yang-baru-lahir-di-trailer-sussex-uk.jpg?s=612x612&w=0&k=20&c=Y-yZnPqbatt_1Vm1zpjRfklBXZgGzJGkvbJOKay9lW0="
            alt=""
            class="absolute inset-0 w-full h-full object-cover opacity-80"
            loading="eager"
        >
        <div class="absolute inset-0 bg-gradient-to-tr from-primary/80 via-primary/40 to-transparent mix-blend-multiply"></div>

        <div class="relative z-30 w-full max-w-2xl flex flex-col items-center text-center text-white page-enter pointer-events-auto">
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

        <header class="w-full absolute top-0 left-0 z-50 flex justify-between items-center px-8 py-6 md:hidden">
            <span class="font-noto-serif text-2xl font-bold text-primary tracking-tighter">Smart-Saka</span>
        </header>

        <div class="max-w-md w-full mx-auto py-20">

            <div class="md:hidden mb-12">
                <span class="font-noto-serif text-2xl font-bold text-primary tracking-tighter">Smart-Saka</span>
            </div>

            <div class="mb-12">
                <h1 class="font-noto-serif text-4xl font-bold text-on-surface mb-3 tracking-tight">Selamat Datang</h1>
                <p class="font-manrope text-on-surface-variant/70 text-sm tracking-wide">Masukkan Username/Email dan Password Anda</p>
            </div>

            {{-- ALERT SUCCESS (Misal: dari registrasi/reset password) --}}
            @if (session('success') || session('status'))
                <div class="mb-6 flex items-center gap-2 p-4 rounded-xl bg-green-100 text-green-700 text-sm font-medium border border-green-400" role="status">
                    <span class="material-symbols-outlined text-base" aria-hidden="true">check_circle</span>
                    {{ session('success') ?? session('status') }}
                </div>
            @endif

            {{-- ALERT ERROR (Diambil dari integrasi sistem lama) --}}
            @if ($errors->any())
                <div class="mb-6 flex items-start gap-2 p-4 rounded-xl bg-red-100 border border-red-400 text-red-700 text-sm font-medium" role="alert">
                    <span class="material-symbols-outlined text-base mt-0.5" aria-hidden="true">error</span>
                    <div>
                        <strong class="font-bold">Login Gagal!</strong>
                        <span class="block">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login.post') }}" class="space-y-6" novalidate>
                @csrf

                {{-- Input Gabungan Username atau Email --}}
                <div>
                    <label
                        for="login"
                        class="block font-manrope text-[11px] uppercase tracking-widest text-on-surface-variant mb-2 ml-1"
                    >
                        Username / Email
                    </label>
                    <input
                        id="login"
                        type="text"
                        name="login"
                        value="{{ old('login') }}"
                        placeholder="Masukkan username atau email"
                        autocomplete="username"
                        required
                        aria-required="true"
                        @if ($errors->has('login')) aria-describedby="login-error" aria-invalid="true" @endif
                        class="w-full px-5 py-4 bg-surface-container-highest border-none rounded-xl text-on-surface placeholder:text-outline/50 focus:ring-2 focus:ring-primary/30 focus:bg-surface-bright transition-all {{ $errors->has('login') ? 'ring-2 ring-error/50' : '' }}"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label
                        for="password"
                        class="block font-manrope text-[11px] uppercase tracking-widest text-on-surface-variant mb-2 ml-1"
                    >
                        Kata Sandi
                    </label>
                    <div class="relative" x-data="{ showPassword: false }">
                        <input
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            name="password"
                            {{-- placeholder="••••••••" --}}
                            placeholder="Masukkan kata sandi"
                            autocomplete="current-password"
                            required
                            aria-required="true"
                            @if ($errors->has('password')) aria-describedby="password-error" aria-invalid="true" @endif
                            class="pw-input w-full px-5 py-4 pr-14 bg-surface-container-highest border-none rounded-xl text-on-surface placeholder:text-outline/50 focus:ring-2 focus:ring-primary/30 focus:bg-surface-bright transition-all {{ $errors->has('password') ? 'ring-2 ring-error/50' : '' }}"
                        >
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="pw-toggle absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant/50 hover:text-primary transition-colors"
                            aria-label="Tampilkan atau sembunyikan kata sandi"
                        >
                            <span x-show="!showPassword" class="material-symbols-outlined">visibility</span>
                            <span x-show="showPassword" class="material-symbols-outlined" style="display: none;">visibility_off</span>
                        </button>
                    </div>
                </div>

                {{-- Lupa Password --}}
                <div class="flex items-center justify-end">
                    <a href="/lupa-password" class="text-sm font-manrope text-primary font-semibold hover:underline underline-offset-4 decoration-primary/30">
                        Lupa Password?
                    </a>
                </div>

                {{-- Submit --}}
                <div class="pt-4 space-y-4">
                    <button
                        type="submit"
                        class="w-full py-4 bg-primary text-on-primary font-bold rounded-xl shadow-lg shadow-primary/10 hover:shadow-primary/20 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                    >
                        Masuk
                    </button>
                </div>
            </form>

            {{-- Register Link --}}
            <p class="mt-12 text-center font-manrope text-sm text-on-surface-variant">
                Belum Punya Akun Smart-Saka?
                <a href="/register" class="text-primary font-bold hover:underline underline-offset-4 decoration-primary/30 ml-1">
                    Registrasi
                </a>
            </p>
        </div>
    </section>
</div>
@endsection
