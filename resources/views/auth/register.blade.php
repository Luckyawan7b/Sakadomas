{{--
|--------------------------------------------------------------------------
| View: resources/views/auth/register.blade.php
|--------------------------------------------------------------------------
--}}

@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="min-h-screen flex flex-col md:flex-row bg-surface">

    {{-- ================================================================
         MOBILE HEADER
    ================================================================ --}}
    <header class="fixed top-0 left-0 w-full z-50 bg-surface/90 backdrop-blur-md shadow-sm md:hidden">
        <div class="flex justify-between items-center w-full px-6 py-4">
            <span class="font-noto-serif text-2xl italic font-bold text-primary">Smart-Saka</span>
            <a href="{{ route('login') }}" class="font-manrope text-xs tracking-widest uppercase text-primary hover:bg-surface-variant transition-colors px-4 py-2 rounded-xl">
                Masuk
            </a>
        </div>
    </header>

    {{-- ================================================================
         KIRI: Ilustrasi / Branding Panel
    ================================================================ --}}
    <section class="hidden md:flex md:w-1/2 lg:w-2/5 relative overflow-hidden bg-surface-dim h-screen sticky top-0">
        <img src="https://media.istockphoto.com/id/2210264157/id/foto/close-up-domba-betina-dan-domba-dombanya-di-ladang-pada-jam-emas.jpg?s=612x612&w=0&k=20&c=7_HbLYcMJY5yd5i3CtZM5O-NihWv5s9feYU9PiTDcC8=" alt="" class="absolute inset-0 w-full h-full object-cover" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-tr from-primary/80 to-transparent"></div>

        <div class="absolute top-8 left-8 z-30">
            <a href="{{ route('login') }}" class="flex items-center text-white/80 hover:text-white transition-colors bg-black/20 hover:bg-black/40 px-4 py-2 rounded-full backdrop-blur-sm pointer-events-auto">
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

            <div class="mb-10 text-center md:text-left">
                <h1 class="font-noto-serif text-3xl md:text-4xl font-bold text-on-surface mb-2">Daftar Akun Baru</h1>
                <p class="text-on-surface-variant font-medium opacity-80 font-manrope">Lengkapi data diri Anda untuk bergabung dalam ekosistem.</p>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
                    <strong class="font-bold">Registrasi Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <div class="mt-2">
                        <a href="{{ route('login') }}" class="text-green-800 font-bold underline">Ke Halaman Login</a>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
                @csrf

                {{-- Nama Lengkap --}}
                <div class="space-y-1.5">
                    <label for="nama" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Nama Lengkap <span class="text-error">*</span></label>
                    <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200">
                        <input id="nama" type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required class="w-full bg-transparent border-none focus:ring-0 px-4 py-3.5 text-on-surface placeholder:text-outline-variant {{ $errors->has('nama') ? 'ring-2 ring-error/50 rounded-xl' : '' }}">
                    </div>
                    @error('nama') <p class="text-xs text-error mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- Username & No HP --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label for="username" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Username <span class="text-error">*</span></label>
                        <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200">
                            <input id="username" type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username" required class="w-full bg-transparent border-none focus:ring-0 px-4 py-3.5 text-on-surface placeholder:text-outline-variant">
                        </div>
                        @error('username') <p class="text-xs text-error mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="no_hp" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">No Handphone / WA <span class="text-error">*</span></label>
                        <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200">
                            <input id="no_hp" type="tel" name="no_hp" value="{{ old('no_hp') }}" placeholder="0812xxxxxxxx" required class="w-full bg-transparent border-none focus:ring-0 px-4 py-3.5 text-on-surface placeholder:text-outline-variant">
                        </div>
                        @error('no_hp') <p class="text-xs text-error mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Email & Password (Menggunakan Alpine.js) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Email <span class="text-error">*</span></label>
                        <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200">
                            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required class="w-full bg-transparent border-none focus:ring-0 px-4 py-3.5 text-on-surface placeholder:text-outline-variant">
                        </div>
                        @error('email') <p class="text-xs text-error mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Password <span class="text-error">*</span></label>
                        {{-- DI SINI ALPINE JS BEKERJA MENGATUR PASSWORD SHOW/HIDE --}}
                        <div x-data="{ showPassword: false }" class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200 flex items-center pr-4 relative">
                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" placeholder="Masukkan password" required class="w-full bg-transparent border-none focus:ring-0 px-4 py-3.5 text-on-surface placeholder:text-outline-variant">
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-4 text-on-surface-variant hover:text-primary transition-colors cursor-pointer z-10">
                                <span x-show="!showPassword" class="material-symbols-outlined">visibility</span>
                                <span x-show="showPassword" class="material-symbols-outlined" style="display: none;">visibility_off</span>
                            </button>
                        </div>
                        @error('password') <p class="text-xs text-error mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Kecamatan & Desa --}}
                <div class="grid grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label for="kecamatan" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Kecamatan <span class="text-error">*</span></label>
                        <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200 relative">
                            <select id="kecamatan" name="id_kecamatan" required class="w-full bg-transparent border-none focus:ring-0 pl-4 pr-10 py-3.5 text-on-surface appearance-none cursor-pointer">
                                <option value="" disabled {{ old('id_kecamatan') ? '' : 'selected' }} hidden>Pilih Kecamatan</option>
                                @foreach ($kecamatan as $kec)
                                    <option value="{{ $kec->id_kecamatan }}" {{ old('id_kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-outline">expand_more</span>
                        </div>
                        @error('id_kecamatan') <p class="text-xs text-error mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="desa" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Desa <span class="text-error">*</span></label>
                        <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200 relative">
                            <select id="desa" name="id_desa" required disabled class="w-full bg-transparent border-none focus:ring-0 pl-4 pr-10 py-3.5 text-on-surface appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                <option value="" disabled selected hidden>Pilih Desa</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-outline">expand_more</span>
                        </div>
                        @error('id_desa') <p class="text-xs text-error mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Alamat Lengkap --}}
                <div class="space-y-1.5">
                    <label for="alamat" class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant ml-1">Alamat Lengkap <span class="text-error">*</span></label>
                    <div class="input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200">
                        <textarea id="alamat" name="alamat" rows="3" required placeholder="Detail jalan, RT/RW, nomor rumah" class="w-full bg-transparent border-none focus:ring-0 px-4 py-3.5 text-on-surface placeholder:text-outline-variant resize-none">{{ old('alamat') }}</textarea>
                    </div>
                    @error('alamat') <p class="text-xs text-error mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary text-on-primary font-bold py-4 rounded-xl shadow-lg shadow-primary/20 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300">
                        Daftar Sekarang
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-8 border-t border-surface-container-highest text-center">
                <p class="text-on-surface-variant text-sm font-medium font-manrope">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-primary font-bold hover:underline underline-offset-4 ml-1">Masuk di sini</a>
                </p>
            </div>
            <footer class="mt-12 text-center pb-8">
                <small class="font-manrope text-xs tracking-widest uppercase text-outline">&copy; {{ date('Y') }} Smart-Saka. All rights reserved.</small>
            </footer>
        </div>
    </section>
</div>

{{-- SCRIPT JAVASCRIPT UNTUK MENGAMBIL DATA DESA --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const kecamatanSelect = document.getElementById("kecamatan");
        const desaSelect = document.getElementById("desa");

        if (kecamatanSelect && desaSelect) {
            kecamatanSelect.addEventListener("change", function() {
                const idKecamatan = this.value;

                if (idKecamatan) {
                    desaSelect.innerHTML = '<option value="" disabled selected hidden>Memuat desa...</option>';
                    desaSelect.setAttribute("disabled", "disabled");

                    fetch(`/api/desa/${idKecamatan}`)
                        .then(response => response.json())
                        .then(data => {
                            desaSelect.innerHTML = '<option value="" disabled selected hidden>Pilih desa</option>';

                            data.forEach(desa => {
                                const option = document.createElement('option');
                                option.value = desa.id_desa;
                                option.textContent = desa.nama_desa;
                                desaSelect.appendChild(option);
                            });

                            desaSelect.removeAttribute("disabled");
                        })
                        .catch(error => {
                            console.error('Error fetching desa:', error);
                            desaSelect.innerHTML = '<option value="" disabled selected hidden>Gagal memuat data</option>';
                        });
                }
            });
        }
    });
</script>
@endsection
