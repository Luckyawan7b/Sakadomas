{{--
|--------------------------------------------------------------------------
| View: resources/views/auth/register.blade.php
|--------------------------------------------------------------------------
| Halaman registrasi akun baru Smart-Saka.
| Route Laravel: GET /register  → nama: register
| Controller   : App\Http\Controllers\Auth\RegisteredUserController@create
--}}

@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="min-h-screen flex flex-col md:flex-row bg-surface">

    {{-- ================================================================
         MOBILE HEADER (hanya tampil di layar kecil)
    ================================================================ --}}
    <header class="fixed top-0 left-0 w-full z-50 bg-surface/90 backdrop-blur-md shadow-sm md:hidden">
        <div class="flex justify-between items-center w-full px-6 py-4">
            <span class="font-noto-serif text-2xl italic font-bold text-primary">Smart-Saka</span>
            <a
                href="{{ route('login') }}"
                class="font-manrope text-xs tracking-widest uppercase text-primary hover:bg-surface-variant transition-colors px-4 py-2 rounded-xl"
            >
                Masuk
            </a>
        </div>
    </header>

    {{-- ================================================================
         KIRI: Ilustrasi / Branding Panel
    ================================================================ --}}
    <section
        class="hidden md:flex md:w-1/2 lg:w-2/5 relative overflow-hidden bg-surface-dim h-screen sticky top-0"
        aria-hidden="true"
    >
        <img
            src="{{ asset('images/auth/register-hero.jpg') }}"
            alt=""
            class="absolute inset-0 w-full h-full object-cover"
            loading="lazy"
        >
        <div class="absolute inset-0 bg-gradient-to-tr from-primary/80 to-transparent"></div>

        {{-- Back to login button (panel kiri) --}}
        <div class="absolute top-8 left-8 z-10">
            <a
                href="{{ route('login') }}"
                class="flex items-center text-white/80 hover:text-white transition-colors bg-black/20 hover:bg-black/40 px-4 py-2 rounded-full backdrop-blur-sm"
                aria-label="Kembali ke halaman login"
            >
                <span class="material-symbols-outlined mr-2 text-sm" aria-hidden="true">arrow_back</span>
                <span class="font-manrope text-sm font-medium">Kembali ke Login</span>
            </a>
        </div>

        <div class="relative z-10 p-16 flex flex-col justify-end h-full max-w-2xl page-enter">
            <h2 class="font-noto-serif text-5xl font-bold text-white mb-6 leading-tight tracking-tight">Smart-Saka</h2>
            <p class="font-noto-serif italic text-xl text-primary-fixed leading-relaxed opacity-90">
                "Pilihan Cerdas Penikmat Domba"
            </p>
        </div>
    </section>

    {{-- ================================================================
         KANAN: Form Registrasi
    ================================================================ --}}
    <section class="w-full md:w-1/2 lg:w-3/5 flex justify-center bg-surface p-6 pt-24 md:p-12 lg:p-20 overflow-y-auto">
        <div class="w-full max-w-xl">

            {{-- Heading --}}
            <div class="mb-10 text-center md:text-left">
                <h1 class="font-noto-serif text-3xl md:text-4xl font-bold text-on-surface mb-2">Daftar Akun Baru</h1>
                <p class="text-on-surface-variant font-medium opacity-80 font-manrope">
                    Lengkapi data diri Anda untuk bergabung dalam ekosistem.
                </p>
            </div>

            {{-- Register Form --}}
            <form method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
                @csrf

                {{-- Nama Lengkap --}}
                <div class="space-y-1.5">
                    <label for="name" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">
                        Nama Lengkap <span class="text-error" aria-hidden="true">*</span>
                    </label>
                    <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200">
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap sesuai KTP"
                            autocomplete="name"
                            required
                            aria-required="true"
                            @if ($errors->has('name')) aria-describedby="name-error" aria-invalid="true" @endif
                            class="w-full bg-transparent border-none focus:ring-0 px-4 py-3.5 text-on-surface placeholder:text-outline-variant {{ $errors->has('name') ? 'ring-2 ring-error/50 rounded-xl' : '' }}"
                        >
                    </div>
                    @error('name')
                        <p id="name-error" class="flex items-center gap-1 text-xs text-error mt-1 ml-1" role="alert">
                            <span class="material-symbols-outlined text-sm" aria-hidden="true">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Username & No Handphone --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label for="username" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">
                            Username <span class="text-error" aria-hidden="true">*</span>
                        </label>
                        <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200">
                            <input
                                id="username"
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                placeholder="contoh: budi_farm"
                                autocomplete="username"
                                required
                                aria-required="true"
                                @if ($errors->has('username')) aria-describedby="username-error" aria-invalid="true" @endif
                                class="w-full bg-transparent border-none focus:ring-0 px-4 py-3.5 text-on-surface placeholder:text-outline-variant"
                            >
                        </div>
                        @error('username')
                            <p id="username-error" class="flex items-center gap-1 text-xs text-error mt-1 ml-1" role="alert">
                                <span class="material-symbols-outlined text-sm" aria-hidden="true">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="phone" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">
                            No Handphone / WA
                        </label>
                        <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200">
                            <input
                                id="phone"
                                type="tel"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="0812..."
                                autocomplete="tel"
                                @if ($errors->has('phone')) aria-describedby="phone-error" aria-invalid="true" @endif
                                class="w-full bg-transparent border-none focus:ring-0 px-4 py-3.5 text-on-surface placeholder:text-outline-variant"
                            >
                        </div>
                        @error('phone')
                            <p id="phone-error" class="flex items-center gap-1 text-xs text-error mt-1 ml-1" role="alert">
                                <span class="material-symbols-outlined text-sm" aria-hidden="true">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Email & Password --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">
                            Email <span class="text-error" aria-hidden="true">*</span>
                        </label>
                        <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                autocomplete="email"
                                required
                                aria-required="true"
                                @if ($errors->has('email')) aria-describedby="reg-email-error" aria-invalid="true" @endif
                                class="w-full bg-transparent border-none focus:ring-0 px-4 py-3.5 text-on-surface placeholder:text-outline-variant"
                            >
                        </div>
                        @error('email')
                            <p id="reg-email-error" class="flex items-center gap-1 text-xs text-error mt-1 ml-1" role="alert">
                                <span class="material-symbols-outlined text-sm" aria-hidden="true">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">
                            Password <span class="text-error" aria-hidden="true">*</span>
                        </label>
                        <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200 flex items-center pr-4 relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                autocomplete="new-password"
                                required
                                aria-required="true"
                                @if ($errors->has('password')) aria-describedby="reg-password-error" aria-invalid="true" @endif
                                class="pw-input w-full bg-transparent border-none focus:ring-0 px-4 py-3.5 text-on-surface placeholder:text-outline-variant"
                            >
                            <button
                                type="button"
                                class="pw-toggle absolute right-4 text-on-surface-variant hover:text-primary transition-colors"
                                aria-label="Tampilkan atau sembunyikan kata sandi"
                                aria-pressed="false"
                            >
                                <span class="material-symbols-outlined" aria-hidden="true">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <p id="reg-password-error" class="flex items-center gap-1 text-xs text-error mt-1 ml-1" role="alert">
                                <span class="material-symbols-outlined text-sm" aria-hidden="true">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Kecamatan & Desa --}}
                <div class="grid grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label for="kecamatan" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">
                            Kecamatan
                        </label>
                        <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200 relative">
                            <select
                                id="kecamatan"
                                name="kecamatan"
                                class="w-full bg-transparent border-none focus:ring-0 pl-4 pr-10 py-3.5 text-on-surface appearance-none cursor-pointer"
                                aria-label="Pilih kecamatan"
                            >
                                <option value="" disabled {{ old('kecamatan') ? '' : 'selected' }}>Pilih Kecamatan</option>
                                {{-- TODO: Populate via backend: foreach ($kecamatanList as $kec) --}}
                                <option value="sukabumi" {{ old('kecamatan') == 'sukabumi' ? 'selected' : '' }}>Sukabumi</option>
                                <option value="cisaat"   {{ old('kecamatan') == 'cisaat'   ? 'selected' : '' }}>Cisaat</option>
                                <option value="kaduhejo" {{ old('kecamatan') == 'kaduhejo' ? 'selected' : '' }}>Kaduhejo</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-outline" aria-hidden="true">expand_more</span>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="desa" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">
                            Desa
                        </label>
                        <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200 relative">
                            <select
                                id="desa"
                                name="desa"
                                class="w-full bg-transparent border-none focus:ring-0 pl-4 pr-10 py-3.5 text-on-surface appearance-none cursor-pointer"
                                aria-label="Pilih desa"
                            >
                                <option value="" disabled {{ old('desa') ? '' : 'selected' }}>Pilih Desa</option>
                                {{-- TODO: Populate dynamically via AJAX based on selected kecamatan --}}
                                <option value="sukamaju" {{ old('desa') == 'sukamaju' ? 'selected' : '' }}>Sukamaju</option>
                                <option value="cibodas"  {{ old('desa') == 'cibodas'  ? 'selected' : '' }}>Cibodas</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-outline" aria-hidden="true">expand_more</span>
                        </div>
                    </div>
                </div>

                {{-- Alamat Lengkap --}}
                <div class="space-y-1.5">
                    <label for="address" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">
                        Alamat Lengkap
                    </label>
                    <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200">
                        <textarea
                            id="address"
                            name="address"
                            rows="3"
                            placeholder="Detail jalan, RT/RW, nomor rumah..."
                            autocomplete="street-address"
                            class="w-full bg-transparent border-none focus:ring-0 px-4 py-3.5 text-on-surface placeholder:text-outline-variant resize-none"
                        >{{ old('address') }}</textarea>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="pt-4">
                    <button
                        type="submit"
                        class="w-full bg-primary text-on-primary font-bold py-4 rounded-xl shadow-lg shadow-primary/20 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                    >
                        Daftar Sekarang
                    </button>
                </div>
            </form>

            {{-- Login Link --}}
            <div class="mt-8 pt-8 border-t border-surface-container-highest text-center">
                <p class="text-on-surface-variant text-sm font-medium font-manrope">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-primary font-bold hover:underline underline-offset-4 ml-1">
                        Masuk di sini
                    </a>
                </p>
            </div>

            <footer class="mt-12 text-center pb-8">
                <small class="font-manrope text-xs tracking-widest uppercase text-outline">
                    &copy; {{ date('Y') }} Smart-Saka. All rights reserved.
                </small>
            </footer>

        </div>
    </section>
</div>
@endsection
