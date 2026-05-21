@php
    $kecamatan = \App\Models\Kecamatan::all();
    $desa = \App\Models\Desa::all();
@endphp

@extends('layouts.landing')

@section('title', 'Profil Saya | Smart-Saka Premium Sheep Farm')

@section('content')
    <x-landing.navbar />
    <main class="pt-24 flex-1 bg-surface text-on-surface"
        x-data='{
        modalEditProfile: {{ $errors->any() && !$errors->has('password_lama') && !$errors->has('password_baru') ? 'true' : 'false' }},
        modalGantiPassword: {{ $errors->has('password_lama') || $errors->has('password_baru') ? 'true' : 'false' }}
    }'>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="fixed top-28 left-1/2 -translate-x-1/2 z-[100] bg-status-success text-white px-6 py-3 rounded-full shadow-lg flex items-center gap-2"
                x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="fixed top-28 left-1/2 -translate-x-1/2 z-[100] bg-error text-white px-6 py-3 rounded-full shadow-lg flex items-center gap-2"
                x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
                <span class="material-symbols-outlined text-[20px]">error</span>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <header class="relative bg-surface-container-low pt-12 pb-16 px-8 overflow-hidden">
            <div class="absolute inset-0 opacity-10 pointer-events-none"
                style="background-image: url('/images/katalog.jpg'); background-size: cover; background-position: center;">
            </div>
            <div class="max-w-7xl mx-auto relative z-10">
                <h1 class="text-4xl md:text-5xl font-bold font-headline text-primary mb-4 tracking-tight leading-tight">
                    Pengaturan Akun</h1>
                <p class="text-on-surface-variant max-w-xl text-lg">Kelola informasi personal dan preferensi akun Anda di
                    sini.</p>
            </div>
        </header>

        <section class="max-w-7xl mx-auto px-8 -mt-8 mb-24 relative z-10 flex flex-col lg:flex-row gap-8">

            <aside class="w-full lg:w-80 shrink-0">
                <div
                    class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(61,103,0,0.04)] border border-surface-container-high p-4 flex flex-col gap-2 sticky top-32">

                    <a href="{{ route('profile') }}"
                        class="flex items-center gap-4 px-6 py-4 rounded-2xl bg-primary/10 text-primary font-bold transition-all">
                        <span class="material-symbols-outlined">person</span> Profil Saya
                    </a>

                    <a href="{{ route('transaksi.riwayat') }}"
                        class="flex items-center gap-4 px-6 py-4 rounded-2xl text-on-surface-variant hover:bg-surface-container-low hover:text-primary font-bold transition-all">
                        <span class="material-symbols-outlined">receipt_long</span> Riwayat Pemesanan
                    </a>

                    <a href="{{ route('kunjungan.riwayat') }}"
                        class="flex items-center gap-4 px-6 py-4 rounded-2xl text-on-surface-variant hover:bg-surface-container-low hover:text-primary font-bold transition-all">
                        <span class="material-symbols-outlined">history</span> Riwayat Survei
                    </a>

                    {{-- Card Pengaturan Notifikasi --}}
                    <div class="px-6 py-4 rounded-2xl bg-surface-container-low border border-surface-container-high flex flex-col gap-3 mt-2"
                        x-data="{
                            hasPermission: ('Notification' in window) && Notification.permission === 'granted',
                            isLoading: false,
                            async toggleNotification() {
                                this.isLoading = true;
                                if (this.hasPermission) {
                                    const success = await window.removeToken();
                                    if (success) {
                                        this.hasPermission = false;
                                        if (window.showToast) window.showToast('Notifikasi berhasil dinonaktifkan.');
                                    }
                                } else {
                                    const success = await window.requestPermissionAndToken();
                                    if (success) {
                                        this.hasPermission = true;
                                        if (window.showToast) window.showToast('Notifikasi berhasil diaktifkan!');
                                    } else {
                                        alert('Gagal mengaktifkan notifikasi. Pastikan Anda mengizinkan notifikasi pada pengaturan browser.');
                                    }
                                }
                                this.isLoading = false;
                            }
                        }">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px]"
                                    :class="hasPermission ? 'text-primary' : 'text-outline'">
                                    notifications
                                </span>
                                Notifikasi
                            </span>

                            <!-- Toggle Switch -->
                            <button type="button" @click="toggleNotification()" :disabled="isLoading"
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none disabled:opacity-50"
                                :class="hasPermission ? 'bg-primary' : 'bg-outline-variant'">
                                <span
                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                    :class="hasPermission ? 'translate-x-5' : 'translate-x-0'"></span>
                            </button>
                        </div>
                        <p class="text-[11px] text-on-surface-variant leading-relaxed font-medium">
                            Dapatkan pemberitahuan real-time untuk status pesanan, kunjungan, dan update lainnya.
                        </p>
                    </div>

                    <hr class="border-surface-container-high my-2 mx-4">

                    <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                        @csrf
                        <button type="submit"
                            class="cursor-pointer w-full flex items-center gap-4 px-6 py-4 rounded-2xl text-error hover:bg-error-container hover:text-error font-bold transition-all group">
                            <span
                                class="material-symbols-outlined group-hover:-translate-x-1 transition-transform">logout</span>
                            Keluar
                        </button>
                    </form>

                </div>
            </aside>

            <div class="flex-1 space-y-8">

                <div
                    class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(61,103,0,0.04)] border border-surface-container-high p-8 flex flex-col sm:flex-row items-center sm:items-start gap-8">
                    <div class="relative group">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-surface shadow-lg">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama) }}&background=3d6700&color=fff&size=200"
                                alt="Profile Picture" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="flex flex-col items-center sm:items-start flex-1 text-center sm:text-left mt-2">
                        <h2 class="text-2xl font-extrabold font-headline text-on-surface mb-2">{{ Auth::user()->nama }}</h2>
                        <p class="text-on-surface-variant font-medium mb-4">{{ '@' . Auth::user()->username }}</p>
                        <div
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold tracking-wide uppercase bg-primary/10 text-primary border border-primary/20">
                            <span class="material-symbols-outlined text-[14px]">verified</span> Pelanggan Aktif
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(61,103,0,0.04)] border border-surface-container-high overflow-hidden">
                    <div
                        class="px-8 py-6 border-b border-surface-container-high flex justify-between items-center bg-surface-container-lowest">
                        <h3 class="text-xl font-extrabold font-headline text-on-surface flex items-center gap-2"><span
                                class="material-symbols-outlined text-primary">badge</span> Informasi Personal</h3>
                        <button @click="modalEditProfile = true"
                            class="text-primary font-bold text-sm hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">edit_note</span> Edit
                        </button>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-xs font-bold text-outline uppercase tracking-widest mb-1.5 font-label">Nama
                                Lengkap</p>
                            <p class="text-base font-bold text-on-surface">{{ Auth::user()->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-outline uppercase tracking-widest mb-1.5 font-label">Username
                            </p>
                            <p class="text-base font-bold text-on-surface">{{ '@' . Auth::user()->username }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-outline uppercase tracking-widest mb-1.5 font-label">Alamat
                                Email</p>
                            <p class="text-base font-bold text-on-surface flex items-center gap-2">
                                {{ Auth::user()->email ?? '-' }} @if (Auth::user()->email)
                                    <span class="material-symbols-outlined text-status-success text-[16px]"
                                        title="Email Terdaftar">check_circle</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-outline uppercase tracking-widest mb-1.5 font-label">Nomor
                                Handphone</p>
                            <p class="text-base font-bold text-on-surface">{{ Auth::user()->no_hp ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs font-bold text-outline uppercase tracking-widest mb-1.5 font-label">Alamat
                                Lengkap</p>
                            <p class="text-base font-bold text-on-surface leading-relaxed max-w-2xl">
                                {{ Auth::user()->alamat ?? '-' }}</p>
                            <p class="text-sm font-medium text-on-surface-variant mt-1">
                                @if (Auth::user()?->desa)
                                    Desa {{ Auth::user()?->desa?->nama_desa }}, Kecamatan
                                    {{ Auth::user()?->desa?->kecamatan?->nama_kecamatan }}
                                @else
                                    Wilayah belum diatur
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(61,103,0,0.04)] border border-surface-container-high overflow-hidden">
                    <div class="px-8 py-6 border-b border-surface-container-high bg-surface-container-lowest">
                        <h3 class="text-xl font-extrabold font-headline text-on-surface flex items-center gap-2"><span
                                class="material-symbols-outlined text-primary">security</span> Keamanan Akun</h3>
                    </div>
                    <div class="p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <p class="text-base font-bold text-on-surface mb-1">Password</p>
                            <p class="text-sm text-on-surface-variant font-medium">Pastikan untuk memperbarui password Anda
                                secara berkala agar akun tetap aman.</p>
                        </div>
                        <button @click="modalGantiPassword = true"
                            class="bg-surface-container-low border border-surface-container-high text-primary font-bold px-6 py-3 rounded-xl hover:bg-surface-variant transition-all whitespace-nowrap active:scale-95">
                            Ganti Password
                        </button>
                    </div>
                </div>

            </div>
        </section>

        <template x-teleport="body">
            <div x-show="modalEditProfile" style="display: none;"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-on-surface/40 backdrop-blur-sm p-4"
                @click.self="modalEditProfile = false">
                <div x-show="modalEditProfile" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">
                    <div
                        class="px-8 py-6 border-b border-surface-container-high flex justify-between items-center bg-surface-container-lowest shrink-0">
                        <h3 class="text-xl font-extrabold font-headline text-on-surface">Edit Profil</h3>
                        <button type="button" @click="modalEditProfile = false"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-container-low text-on-surface-variant hover:bg-surface-variant transition-all">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" class="flex flex-col overflow-hidden">
                        @csrf
                        @method('PUT')

                        <div class="p-8 overflow-y-auto no-scrollbar space-y-6">
                            @if ($errors->any() && !$errors->has('password_lama') && !$errors->has('password_baru'))
                                <div
                                    class="rounded-xl bg-error-container p-4 text-sm text-error font-medium border border-error/20">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="locationPickerProfile(window.locationDataProfile)">
                                <div>
                                    <label class="block text-sm font-bold text-on-surface mb-2 font-label">Nama
                                        Lengkap</label>
                                    <input type="text" name="nama" value="{{ old('nama', Auth::user()->nama) }}"
                                        required
                                        class="w-full px-5 py-3.5 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 font-bold text-on-surface outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-on-surface mb-2 font-label">Username</label>
                                    <input type="text" name="username"
                                        value="{{ old('username', Auth::user()->username) }}" required
                                        class="w-full px-5 py-3.5 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 font-bold text-on-surface outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-on-surface mb-2 font-label">Alamat
                                        Email</label>
                                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                        class="w-full px-5 py-3.5 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 font-bold text-on-surface outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-on-surface mb-2 font-label">Nomor
                                        Handphone</label>
                                    <input type="tel" name="no_hp" value="{{ old('no_hp', Auth::user()->no_hp) }}"
                                        required
                                        class="w-full px-5 py-3.5 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 font-bold text-on-surface outline-none">
                                </div>

                                {{-- Kecamatan Autocomplete --}}
                                <div class="relative">
                                    <label
                                        class="block text-sm font-bold text-on-surface mb-2 font-label">Kecamatan</label>
                                    <div class="relative" @click.away="openKecamatan = false">
                                        <input type="hidden" name="id_kecamatan" x-model="selectedKecamatanId">
                                        <input type="text" x-model="searchKecamatan" @focus="openKecamatan = true"
                                            @input="openKecamatan = true; selectedKecamatanId = ''; selectedDesaId = ''; searchDesa = '';"
                                            placeholder="Cari Kecamatan..." autocomplete="off" required
                                            class="w-full px-5 py-3.5 pr-10 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 font-bold text-on-surface outline-none">
                                        <span
                                            class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline">search</span>

                                        <ul x-show="openKecamatan" x-transition
                                            class="absolute z-50 w-full bg-white border border-surface-container-high rounded-xl mt-1 shadow-lg max-h-60 overflow-y-auto"
                                            style="display: none;">
                                            <template x-for="kec in filteredKecamatan" :key="kec.id_kecamatan">
                                                <li @click="selectKecamatan(kec)"
                                                    class="px-4 py-3 hover:bg-surface-container-low cursor-pointer text-on-surface text-sm transition-colors font-medium"
                                                    x-text="kec.nama_kecamatan"></li>
                                            </template>
                                            <li x-show="filteredKecamatan.length === 0"
                                                class="px-4 py-3 text-on-surface-variant text-sm text-center">Kecamatan
                                                tidak ditemukan</li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Desa Autocomplete --}}
                                <div class="relative">
                                    <label class="block text-sm font-bold text-on-surface mb-2 font-label">Desa</label>
                                    <div class="relative" @click.away="openDesa = false">
                                        <input type="hidden" name="id_desa" x-model="selectedDesaId">
                                        <input type="text" x-model="searchDesa" @focus="openDesa = true"
                                            @input="openDesa = true; selectedDesaId = '';" placeholder="Cari Desa..."
                                            autocomplete="off" :disabled="!selectedKecamatanId" required
                                            class="w-full px-5 py-3.5 pr-10 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 font-bold text-on-surface outline-none disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span
                                            class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline">search</span>

                                        <ul x-show="openDesa && selectedKecamatanId" x-transition
                                            class="absolute z-50 w-full bg-white border border-surface-container-high rounded-xl mt-1 shadow-lg max-h-60 overflow-y-auto"
                                            style="display: none;">
                                            <template x-for="d in filteredDesa" :key="d.id_desa">
                                                <li @click="selectDesa(d)"
                                                    class="px-4 py-3 hover:bg-surface-container-low cursor-pointer text-on-surface text-sm transition-colors font-medium"
                                                    x-text="d.nama_desa"></li>
                                            </template>
                                            <li x-show="filteredDesa.length === 0"
                                                class="px-4 py-3 text-on-surface-variant text-sm text-center">Desa tidak
                                                ditemukan</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-on-surface mb-2 font-label">Alamat
                                        Lengkap</label>
                                    <textarea name="alamat" required
                                        class="w-full px-5 py-3.5 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 font-bold text-on-surface outline-none resize-none"
                                        rows="3">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div
                            class="px-8 py-6 border-t border-surface-container-high bg-surface-container-lowest flex justify-end gap-4 shrink-0">
                            <button type="button" @click="modalEditProfile = false"
                                class="px-6 py-3.5 rounded-xl font-bold text-on-surface-variant hover:bg-surface-container-low transition-all">Batal</button>
                            <button type="submit"
                                class="px-8 py-3.5 rounded-xl font-bold bg-primary text-white hover:bg-primary-container shadow-md shadow-primary/20 transition-all active:scale-95">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <template x-teleport="body">
            <div x-show="modalGantiPassword" style="display: none;"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-on-surface/40 backdrop-blur-sm p-4"
                @click.self="modalGantiPassword = false">
                <div x-show="modalGantiPassword" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
                    <div
                        class="px-8 py-6 border-b border-surface-container-high flex justify-between items-center bg-surface-container-lowest">
                        <h3 class="text-xl font-extrabold font-headline text-on-surface">Ganti Password</h3>
                        <button type="button" @click="modalGantiPassword = false"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-container-low text-on-surface-variant hover:bg-surface-variant transition-all">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('profile.password') }}" class="flex flex-col">
                        @csrf
                        @method('PUT')
                        <div class="p-8 space-y-6">
                            @error('password_lama')
                                <div
                                    class="rounded-xl bg-error-container p-4 text-sm text-error font-medium border border-error/20">
                                    {{ $message }}
                                </div>
                            @enderror
                            @error('password_baru')
                                <div
                                    class="rounded-xl bg-error-container p-4 text-sm text-error font-medium border border-error/20">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div>
                                <label class="block text-sm font-bold text-on-surface mb-2 font-label">Password Saat
                                    Ini</label>
                                <input type="password" name="password_lama" required
                                    class="w-full px-5 py-3.5 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 font-bold text-on-surface outline-none"
                                    placeholder="Masukkan password lama">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-on-surface mb-2 font-label">Password
                                    Baru</label>
                                <input type="password" name="password_baru" required minlength="6"
                                    class="w-full px-5 py-3.5 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 font-bold text-on-surface outline-none"
                                    placeholder="Buat password baru">
                                <p class="text-[11px] text-on-surface-variant font-medium mt-2">Minimal 6 karakter,
                                    mencakup huruf besar, huruf kecil, dan angka.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-on-surface mb-2 font-label">Konfirmasi Password
                                    Baru</label>
                                <input type="password" name="password_baru_confirmation" required minlength="6"
                                    class="w-full px-5 py-3.5 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 font-bold text-on-surface outline-none"
                                    placeholder="Ketik ulang password baru">
                            </div>
                        </div>

                        <div class="px-8 py-6 border-t border-surface-container-high bg-surface-container-lowest">
                            <button type="submit"
                                class="w-full px-8 py-4 rounded-xl font-bold bg-primary text-white hover:bg-primary-container shadow-md shadow-primary/20 transition-all active:scale-95">Perbarui
                                Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

    </main>

    {{-- FOOTER --}}
    <x-landing.footer wa-number="{{ config('smartsaka.wa_number') }}" email="{{ config('smartsaka.email') }}"
        :address="config('smartsaka.address')" map-src="{{ config('smartsaka.maps_embed_src') }}" />

    {{-- SCRIPT JAVASCRIPT UNTUK ALPINE.JS COMPONENT LOKASI PROFIL --}}
    <script>
        // Menyimpan data ke dalam variabel agar dapat diakses oleh komponen profil
        window.locationDataProfile = {
            kecamatanList: @json($kecamatan),
            desaList: @json($desa),
            oldIdKecamatan: '{{ old('id_kecamatan', Auth::user()?->desa?->id_kecamatan) }}',
            oldIdDesa: '{{ old('id_desa', Auth::user()->id_desa) }}'
        };

        document.addEventListener('alpine:init', () => {
            Alpine.data('locationPickerProfile', (data) => ({
                kecamatanList: data.kecamatanList,
                desaList: data.desaList,

                searchKecamatan: '',
                searchDesa: '',

                selectedKecamatanId: data.oldIdKecamatan || '',
                selectedDesaId: data.oldIdDesa || '',

                openKecamatan: false,
                openDesa: false,

                init() {
                    // Saat inisialisasi, set input pencarian kecamatan sesuai dengan ID tersimpan
                    if (this.selectedKecamatanId) {
                        const kec = this.kecamatanList.find(k => k.id_kecamatan == this
                            .selectedKecamatanId);
                        if (kec) this.searchKecamatan = kec.nama_kecamatan;
                    }

                    // Set input pencarian desa sesuai dengan ID tersimpan
                    if (this.selectedDesaId) {
                        const d = this.desaList.find(d => d.id_desa == this.selectedDesaId);
                        if (d) this.searchDesa = d.nama_desa;
                    }
                },

                get filteredKecamatan() {
                    if (this.searchKecamatan === '') return this.kecamatanList;
                    const selected = this.kecamatanList.find(k => k.id_kecamatan == this
                        .selectedKecamatanId);
                    if (selected && selected.nama_kecamatan === this.searchKecamatan) return this
                        .kecamatanList;

                    return this.kecamatanList.filter(kec =>
                        kec.nama_kecamatan.toLowerCase().includes(this.searchKecamatan
                            .toLowerCase())
                    );
                },

                get filteredDesa() {
                    const availableDesa = this.desaList.filter(d => d.id_kecamatan == this
                        .selectedKecamatanId);
                    if (this.searchDesa === '') return availableDesa;

                    const selected = availableDesa.find(d => d.id_desa == this.selectedDesaId);
                    if (selected && selected.nama_desa === this.searchDesa) return availableDesa;

                    return availableDesa.filter(d =>
                        d.nama_desa.toLowerCase().includes(this.searchDesa.toLowerCase())
                    );
                },

                selectKecamatan(kec) {
                    this.selectedKecamatanId = kec.id_kecamatan;
                    this.searchKecamatan = kec.nama_kecamatan;
                    this.openKecamatan = false;

                    // Reset desa ketika kecamatan diganti
                    this.selectedDesaId = '';
                    this.searchDesa = '';
                },

                selectDesa(d) {
                    this.selectedDesaId = d.id_desa;
                    this.searchDesa = d.nama_desa;
                    this.openDesa = false;
                }
            }));
        });
    </script>

@endsection
