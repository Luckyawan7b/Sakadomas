@extends('layouts.app')

@section('content')
    <div x-data="ajaxTable('{{ route('ternak.index') }}')">
            <div x-data="{
        modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
        modalFilter: false
    }">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 3000)"
                class="mb-4 flex items-center justify-between rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800 border border-green-200">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 5000)"
                class="mb-4 flex items-center justify-between rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800 border border-red-200">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ session('error') }}
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Manajemen Data Ternak
            </h2>

            <div class="flex flex-wrap items-center gap-3">
                @php
                    // Cek apakah ada filter yang sedang aktif
                    $isFiltered =
                        (request()->filled('id_kandang') && request('id_kandang') !== 'semua') ||
                        (request()->filled('id_jenis_ternak') && request('id_jenis_ternak') !== 'semua') ||
                        (request()->filled('jenis_kelamin') && request('jenis_kelamin') !== 'semua') ||
                        request()->filled('usia_min') ||
                        request()->filled('usia_max') ||
                        request()->filled('berat_min') ||
                        request()->filled('berat_max') ||
                        (request()->filled('status_ternak') && request('status_ternak') !== 'semua') ||
                        (request()->filled('status_jual') && request('status_jual') !== 'semua');
                @endphp

                <form @submit.prevent="fetchData" id="search-form" method="GET" action="{{ route('ternak.index') }}" class="relative w-full sm:w-auto">
                    <input type="text" name="q" x-model="search" @input.debounce.500ms="fetchData" value="{{ request('q') }}" placeholder="Cari ID Ternak..."
                        class="dark:bg-gray-900 h-11 w-full sm:w-64 rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">

                    <button type="submit"
                        class="absolute right-0 top-0 flex h-11 w-11 items-center justify-center text-gray-500 hover:text-brand-500 dark:text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>

                {{-- Tombol Buka Modal Filter --}}
                <button @click="modalFilter = true" type="button"
                    class="relative inline-flex items-center justify-center font-medium gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    Filter Data
                    @if ($isFiltered)
                        <span
                            class="absolute top-2 right-2 flex h-2.5 w-2.5 items-center justify-center rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
                    @endif
                </button>

                {{-- Tombol Tambah Data --}}
                <button @click="modalTambah = true" type="button"
                    class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600">
                    <svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4.5v15m7.5-7.5h-15" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Tambah Ternak
                </button>
            </div>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4 mb-6">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Populasi</p>
                <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">{{ $stat_total }} <span
                        class="text-sm font-medium text-gray-500">Ekor</span></h4>
            </div>
            <div
                class="rounded-xl border border-green-200 bg-green-50 p-5 shadow-sm dark:border-green-900/30 dark:bg-green-500/10">
                <p class="text-sm font-medium text-green-600 dark:text-green-400">Siap Jual</p>
                <h4 class="mt-2 text-2xl font-bold text-green-700 dark:text-green-300">{{ $stat_siap_jual }} <span
                        class="text-sm font-medium opacity-70">Ekor</span></h4>
            </div>
            <div class="rounded-xl border border-red-200 bg-red-50 p-5 shadow-sm dark:border-red-900/30 dark:bg-red-500/10">
                <p class="text-sm font-medium text-red-600 dark:text-red-400">Sakit</p>
                <h4 class="mt-2 text-2xl font-bold text-red-700 dark:text-red-300">{{ $stat_sakit }} <span
                        class="text-sm font-medium opacity-70">Ekor</span></h4>
            </div>
            <div
                class="rounded-xl border border-blue-200 bg-blue-50 p-5 shadow-sm dark:border-blue-900/30 dark:bg-blue-500/10">
                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Telah Terjual</p>
                <h4 class="mt-2 text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $stat_terjual }} <span
                        class="text-sm font-medium opacity-70">Ekor</span></h4>
            </div>
        </div>

        {{-- MODAL FILTER ADVANCED --}}
        <template x-teleport="body">
            <div x-show="modalFilter" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="modalFilter = false">
                <div class="no-scrollbar relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-8"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-6 flex justify-between items-start">
                        <div>
                            <h4 class="mb-1 text-2xl font-semibold text-gray-800 dark:text-white/90">Filter Pencarian</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tentukan kriteria ternak yang ingin
                                ditampilkan.</p>
                        </div>
                        <button @click="modalFilter = false"
                            class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="fetchData" id="filter-form" method="GET" action="{{ route('ternak.index') }}" class="flex flex-col gap-5">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Lokasi
                                    Kandang</label>
                                <select name="id_kandang"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="semua" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ request('id_kandang') == 'semua' ? 'selected' : '' }}>Semua Kandang</option>
                                    @foreach ($data_kandang as $kd)
                                        <option value="{{ $kd->id_kandang }}"
                                            class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                            {{ request('id_kandang') == $kd->id_kandang ? 'selected' : '' }}>Kandang
                                            {{ $kd->nomor_kandang }}</option>
                                    @endforeach
                                    <option value="kosong"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white font-semibold"
                                        {{ request('id_kandang') == 'kosong' ? 'selected' : '' }}>Kosong (Tanpa Kandang)
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis
                                    Ternak</label>
                                <select name="id_jenis_ternak"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="semua"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white capitalize"
                                        {{ request('id_jenis_ternak') == 'semua' ? 'selected' : '' }}>Semua Jenis</option>
                                    @foreach ($data_jenis as $jenis)
                                        <option value="{{ $jenis->id_jenis_ternak }}"
                                            class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white capitalize"
                                            {{ request('id_jenis_ternak') == $jenis->id_jenis_ternak ? 'selected' : '' }}>
                                            {{ $jenis->jenis_ternak }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Usia
                                    Minimal (Bln)</label>
                                <input type="number" name="usia_min" value="{{ request('usia_min') }}" min='0'
                                    placeholder="0"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Usia
                                    Maksimal (Bln)</label>
                                <input type="number" name="usia_max" value="{{ request('usia_max') }}" min='0'
                                    placeholder="Tak Terbatas"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Berat
                                    Minimal (Kg)</label>
                                <input type="number" name="berat_min" value="{{ request('berat_min') }}"
                                    min="0" placeholder="0"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Berat
                                    Maksimal (Kg)</label>
                                <input type="number" name="berat_max" value="{{ request('berat_max') }}"
                                    min="0" placeholder="Tak Terbatas"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kesehatan</label>
                                <select name="status_ternak"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="semua" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ request('status_ternak') == 'semua' ? 'selected' : '' }}>Semua Kondisi</option>
                                    <option value="sehat" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ request('status_ternak') == 'sehat' ? 'selected' : '' }}>Sehat</option>
                                    <option value="sakit" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ request('status_ternak') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="hamil" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ request('status_ternak') == 'hamil' ? 'selected' : '' }}>Hamil</option>
                                    <option value="mati" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ request('status_ternak') == 'mati' ? 'selected' : '' }}>Mati</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Penjualan</label>
                                <select name="status_jual"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="semua" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ request('status_jual') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="tidak dijual"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ request('status_jual') == 'tidak dijual' ? 'selected' : '' }}>Tidak Dijual
                                    </option>
                                    <option value="siap jual"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ request('status_jual') == 'siap jual' ? 'selected' : '' }}>Siap Jual</option>
                                    <option value="booking"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ request('status_jual') == 'booking' ? 'selected' : '' }}>Booking</option>
                                    <option value="terjual"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ request('status_jual') == 'terjual' ? 'selected' : '' }}>Terjual</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis
                                    Kelamin</label>
                                <select name="jenis_kelamin"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="semua" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ request('jenis_kelamin') == 'semua' ? 'selected' : '' }}>Semua Kelamin</option>
                                    <option value="jantan" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ request('jenis_kelamin') == 'jantan' ? 'selected' : '' }}>Jantan</option>
                                    <option value="betina" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ request('jenis_kelamin') == 'betina' ? 'selected' : '' }}>Betina</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mt-4 justify-end">
                            {{-- Tombol Reset: Mereset form dan fetch ulang data tanpa reload --}}
                            <button type="button" @click="
                                let form = document.getElementById('filter-form');
                                if (form) {
                                    form.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
                                    form.querySelectorAll('input[type=number]').forEach(i => i.value = '');
                                }
                                search = '';
                                let searchInput = document.querySelector('#search-form input[name=q]');
                                if (searchInput) searchInput.value = '';
                                modalFilter = false;
                                fetchData();
                            "
                                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">
                                Reset Filter
                            </button>
                            <button type="submit"
                                class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                                Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- MODAL TAMBAH TERNAK --}}
        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="modalTambah = false">
                <div class="no-scrollbar relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-8"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-6">
                        <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Registrasi Ternak Baru
                        </h4>
                    </div>

                    @if ($errors->any() && !old('_method'))
                        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                            {{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('ternak.store') }}" class="flex flex-col gap-4">
                        @csrf

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2"
                            x-data='{
                            selectedKandang: "{{ old('id_kandang', '') }}",
                            selectedKamar: "{{ old('id_kamar', '') }}",
                            jenisKelamin: "{{ old('jenis_kelamin', 'jantan') }}",
                            statusTernak: "{{ old('status_ternak', 'sehat') }}",
                            semuaKamar: @json($data_kamar),
                            get kamarTersedia() {
                                return this.semuaKamar.filter(k => k.id_kandang == this.selectedKandang);
                            }
                        }'>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Lokasi
                                    Kandang</label>
                                <select name="id_kandang" x-model="selectedKandang"
                                    @change="selectedKamar = (selectedKandang === 'kosong' ? 'kosong' : '')" required
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="" disabled
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Kandang
                                    </option>

                                    @foreach ($data_kandang as $kd)
                                        <option value="{{ $kd->id_kandang }}"
                                            class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Kandang
                                            {{ $kd->nomor_kandang }}</option>
                                    @endforeach

                                    <option value="kosong"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Kosong</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih
                                    Kamar</label>
                                <select name="id_kamar" x-model="selectedKamar" required
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">

                                    <option value="" disabled x-show="selectedKandang !== 'kosong'"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Kamar
                                        Terlebih Dahulu</option>

                                    <option value="kosong" x-show="selectedKandang === 'kosong'"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Kosong</option>

                                    <template x-for="kamar in kamarTersedia" :key="kamar.id_kamar">
                                        <option class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                            :value="kamar.id_kamar" x-text="'Kamar ' + kamar.nomor_kamar"></option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis
                                    Ternak</label>
                                <select name="id_jenis_ternak" required
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white capitalize">
                                    <option value="" disabled selected
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white capitalize">Pilih
                                        Jenis</option>
                                    @foreach ($data_jenis as $jenis)
                                        <option value="{{ $jenis->id_jenis_ternak }}"
                                            class="capitalize bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                            {{ old('id_jenis_ternak') == $jenis->id_jenis_ternak ? 'selected' : '' }}>
                                            {{ $jenis->jenis_ternak }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis
                                    Kelamin</label>
                                <select name="jenis_kelamin" required x-model="jenisKelamin"
                                    @change="if(jenisKelamin === 'jantan' && statusTernak === 'hamil') statusTernak = 'sehat'"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="jantan"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Jantan</option>
                                    <option value="betina"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Betina</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Usia
                                    (Bulan)</label>
                                <input type="number" name="usia" value="{{ old('usia') }}" required
                                    min="0"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Berat
                                    (Kg)</label>
                                <input type="number" name="berat" value="{{ old('berat') }}" required
                                    min="0"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                            </div>
                            {{--
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Jual (Rp)</label>
                                <input type="number" name="harga" value="{{ old('harga') }}" required min="0" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                            </div> --}}

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status
                                    Kesehatan</label>
                                <select name="status_ternak" required x-model="statusTernak"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="sehat"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Sehat</option>
                                    <option value="sakit"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Sakit</option>
                                    <option value="hamil" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        x-show="jenisKelamin === 'betina'">Hamil</option>
                                    <option value="mati"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Mati</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status
                                    Penjualan</label>
                                <select name="status_jual" required
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="tidak dijual"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ old('status_jual') == 'tidak dijual' ? 'selected' : '' }}>Tidak Dijual</option>
                                    <option value="siap jual"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ old('status_jual') == 'siap jual' ? 'selected' : '' }}>Siap Jual</option>
                                    <option value="booking"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ old('status_jual') == 'booking' ? 'selected' : '' }}>Booking</option>
                                    <option value="terjual"
                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ old('status_jual') == 'terjual' ? 'selected' : '' }}>Terjual</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mt-4 justify-end">
                            <button @click="modalTambah = false" type="button"
                                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">Batal</button>
                            <button type="submit"
                                class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">Simpan
                                Ternak</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <div
            class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 mt-6 overflow-hidden">
            <div
                class="py-5 px-5 md:px-6 flex justify-between items-center border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Ternak
                    <span x-show="totalData > 0"
                        class="ml-2 inline-flex items-center rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-600 ring-1 ring-inset ring-brand-500/10 dark:bg-brand-500/10 dark:text-brand-400 dark:ring-brand-500/20"
                        x-text="totalData + ' Data Ditemukan'"></span>
                </h4>
            </div>

            {{-- Loading Overlay --}}
            <div x-show="isFetching" class="py-8 flex justify-center items-center">
                <svg class="animate-spin h-8 w-8 text-brand-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <div class="max-w-full overflow-x-auto" x-show="!isFetching" x-transition.opacity.duration.200ms>
                <table class="w-full table-auto min-w-[900px]">
                    <thead>
                        <tr class="bg-gray-50 text-left dark:bg-gray-800 border-b border-gray-200 dark:border-gray-800">
                            <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm">ID & Jenis</th>
                            <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm">Lokasi</th>
                            <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm">Profil Fisik</th>
                            <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm">Harga Jual</th>
                            <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm text-center">Status</th>
                            <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="ternak in rows" :key="ternak.id_ternak">
                            <tr class="border-b border-gray-100 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                {{-- ID & Jenis --}}
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-50 dark:bg-brand-500/10">
                                            <svg class="h-5 w-5 text-brand-500 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-800 dark:text-white block" x-text="'ID-' + ternak.id_ternak"></span>
                                            <span class="text-sm font-medium text-gray-500 capitalize" x-text="ternak.jenis_ternak"></span>
                                        </div>
                                    </div>
                                </td>
                                {{-- Lokasi --}}
                                <td class="py-4 px-5">
                                    <span class="font-semibold text-brand-600 dark:text-brand-400 block" x-text="'Kandang ' + ternak.kandang"></span>
                                    <span class="text-sm text-gray-500 flex items-center gap-1 mt-0.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        <span x-text="'Kamar ' + ternak.kamar"></span>
                                    </span>
                                </td>
                                {{-- Profil Fisik --}}
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            <template x-if="ternak.jenis_kelamin === 'jantan'">
                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 18a4 4 0 100-8 4 4 0 000 8zm3-7l6-6M19 5h-4m4 0v4"></path></svg>
                                            </template>
                                            <template x-if="ternak.jenis_kelamin !== 'jantan'">
                                                <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11a4 4 0 100-8 4 4 0 000 8zm0 0v9m-3-3h6"></path></svg>
                                            </template>
                                            <span x-text="ternak.jenis_kelamin.charAt(0).toUpperCase() + ternak.jenis_kelamin.slice(1)"></span>
                                        </div>
                                        <div class="h-4 w-px bg-gray-300 dark:bg-gray-700"></div>
                                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="ternak.usia + ' Bln'"></div>
                                        <div class="h-4 w-px bg-gray-300 dark:bg-gray-700"></div>
                                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="ternak.berat + ' Kg'"></div>
                                    </div>
                                </td>
                                {{-- Harga --}}
                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20"
                                        x-text="'Rp ' + Number(ternak.harga).toLocaleString('id-ID')"></span>
                                </td>
                                {{-- Status --}}
                                <td class="py-4 px-5 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <span :class="{
                                            'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-300': ternak.status_ternak === 'sehat',
                                            'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-300': ternak.status_ternak === 'sakit',
                                            'bg-purple-100 text-purple-800 dark:bg-purple-500/20 dark:text-purple-300': ternak.status_ternak === 'hamil',
                                            'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300': ternak.status_ternak === 'mati'
                                        }" class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium">
                                            <span :class="{
                                                'bg-green-500': ternak.status_ternak === 'sehat',
                                                'bg-red-500': ternak.status_ternak === 'sakit',
                                                'bg-purple-500': ternak.status_ternak === 'hamil',
                                                'bg-gray-500': ternak.status_ternak === 'mati'
                                            }" class="mr-1.5 h-1.5 w-1.5 rounded-full"></span>
                                            <span x-text="ternak.status_ternak.charAt(0).toUpperCase() + ternak.status_ternak.slice(1)"></span>
                                        </span>
                                        <span class="text-[11px] font-bold tracking-wider text-gray-500 uppercase" x-text="ternak.status_jual"></span>
                                    </div>
                                </td>
                                {{-- Aksi --}}
                                <td class="py-4 px-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a :href="'{{ url('/ternak') }}/' + ternak.id_ternak + '/detail'" title="Analisis Ternak"
                                            class="inline-flex items-center justify-center rounded-lg bg-brand-50 px-3 py-2 text-sm font-medium text-brand-600 transition hover:bg-brand-100 dark:bg-brand-500/10 dark:text-brand-400 dark:hover:bg-brand-500/20">
                                            Detail
                                        </a>
                                        <button @click="openEditModal(ternak)" type="button" title="Edit Ternak"
                                            class="inline-flex items-center justify-center rounded-lg bg-amber-50 px-3 py-2 text-sm font-medium text-amber-600 transition hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20">
                                            Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        {{-- Empty State --}}
                        <tr x-show="rows.length === 0 && !isFetching">
                            <td colspan="6" class="py-10 px-4 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    <p class="text-gray-500 dark:text-gray-400">Tidak ada data ternak yang sesuai dengan filter.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div x-show="lastPage > 1" class="border-t border-gray-200 dark:border-gray-800 p-4">
                <nav class="flex items-center justify-between">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Menampilkan <span class="font-medium" x-text="pageFrom"></span> - <span class="font-medium" x-text="pageTo"></span> dari <span class="font-medium" x-text="totalData"></span> data
                    </p>
                    <div class="flex items-center gap-1">
                        <button @click="goToPage(currentPage - 1)" :disabled="currentPage <= 1"
                            class="inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium transition disabled:opacity-40 disabled:cursor-not-allowed border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            &laquo; Prev
                        </button>
                        <template x-for="p in paginationPages" :key="'page-'+p">
                            <button @click="if(p !== '...') goToPage(p)"
                                :class="p === currentPage ? 'bg-brand-500 text-white border-brand-500' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'"
                                class="inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium transition border min-w-[40px]"
                                :disabled="p === '...'"
                                x-text="p"></button>
                        </template>
                        <button @click="goToPage(currentPage + 1)" :disabled="currentPage >= lastPage"
                            class="inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium transition disabled:opacity-40 disabled:cursor-not-allowed border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            Next &raquo;
                        </button>
                    </div>
                </nav>
            </div>

        </div>

        {{-- SHARED EDIT MODAL (Satu modal untuk semua baris) --}}
        <template x-teleport="body">
            <div x-show="editModal" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="editModal = false">
                <div class="no-scrollbar relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-8"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-6 flex justify-between items-start">
                        <div>
                            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90"
                                x-text="'Edit Ternak #ID-' + editData.id_ternak"></h4>
                        </div>
                    </div>

                    <form method="POST" :action="'{{ url('/ternak') }}/' + editData.id_ternak" class="flex flex-col gap-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id_ternak_edit" :value="editData.id_ternak">

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2"
                            x-data="{
                                get kamarTersedia() {
                                    if ($root.querySelector('[x-ref=editKandang]')?.value === 'kosong') return [];
                                    return window._semuaKamar ? window._semuaKamar.filter(k => k.id_kandang == editData.id_kandang) : [];
                                }
                            }">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pindah Kandang</label>
                                <select name="id_kandang" x-ref="editKandang" x-model="editData.id_kandang"
                                    @change="editData.id_kamar = (editData.id_kandang === 'kosong' ? 'kosong' : '')"
                                    required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                    <option value="" disabled class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Kandang</option>
                                    @foreach ($data_kandang as $kd)
                                        <option value="{{ $kd->id_kandang }}" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Kandang {{ $kd->nomor_kandang }}</option>
                                    @endforeach
                                    <option value="kosong" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white ">Kosong (Keluar Kandang)</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pindah Kamar</label>
                                <select name="id_kamar" x-model="editData.id_kamar" required
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                    <option value="" disabled x-show="editData.id_kandang !== 'kosong'" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Kamar</option>
                                    <option value="kosong" x-show="editData.id_kandang === 'kosong'" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Kosong</option>
                                    <template x-for="kamar in kamarTersedia" :key="kamar.id_kamar">
                                        <option class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" :value="kamar.id_kamar" x-text="'Kamar ' + kamar.nomor_kamar"></option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Ternak</label>
                                <select name="id_jenis_ternak" x-model="editData.id_jenis_ternak" required
                                    class="capitalize dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                    @foreach ($data_jenis as $jenis)
                                        <option value="{{ $jenis->id_jenis_ternak }}" class="capitalize bg-white text-gray-800 dark:bg-gray-900 dark:text-white">{{ $jenis->jenis_ternak }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kelamin</label>
                                <select name="jenis_kelamin" required x-model="editData.jenis_kelamin"
                                    @change="if(editData.jenis_kelamin === 'jantan' && editData.status_ternak === 'hamil') editData.status_ternak = 'sehat'"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                    <option value="jantan" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Jantan</option>
                                    <option value="betina" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Betina</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Usia (Bulan)</label>
                                <input type="number" name="usia" x-model="editData.usia" required min="0"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Berat (Kg)</label>
                                <input type="number" name="berat" x-model="editData.berat" required min="0"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Jual (Rp) <span class="text-xs text-brand-500 font-normal">(Otomatis)</span></label>
                                <input type="number" name="harga" :value="editData.harga" readonly
                                    class="dark:bg-gray-800 h-11 w-full rounded-lg border border-gray-200 bg-gray-100 px-4 py-2 text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400 cursor-not-allowed">
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kesehatan</label>
                                <select name="status_ternak" required x-model="editData.status_ternak"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                    <option value="sehat" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Sehat</option>
                                    <option value="sakit" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Sakit</option>
                                    <option value="hamil" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" x-show="editData.jenis_kelamin === 'betina'">Hamil</option>
                                    <option value="mati" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Mati</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Penjualan</label>
                                <select name="status_jual" required x-model="editData.status_jual"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                    <option value="tidak dijual" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Tidak Dijual</option>
                                    <option value="siap jual" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Siap Jual</option>
                                    <option value="booking" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Booking</option>
                                    <option value="terjual" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Terjual</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mt-4 justify-end">
                            <button @click="editModal = false" type="button"
                                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">Batal</button>
                            <button type="submit"
                                class="flex w-full justify-center rounded-lg bg-yellow-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-yellow-600 sm:w-auto">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

    </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function ajaxTable(baseUrl) {
        return {
            search: new URLSearchParams(window.location.search).get('q') || '',
            isFetching: false,
            abortController: null,
            rows: @json($data_ternak_json),
            currentPage: {{ $data_ternak->currentPage() }},
            lastPage: {{ $data_ternak->lastPage() }},
            totalData: {{ $data_ternak->total() }},
            pageFrom: {{ $data_ternak->firstItem() ?? 0 }},
            pageTo: {{ $data_ternak->lastItem() ?? 0 }},
            pageCache: {},

            // Shared Edit Modal
            editModal: false,
            editData: {},

            get paginationPages() {
                let pages = [];
                let c = this.currentPage;
                let l = this.lastPage;
                if (l <= 7) {
                    for (let i = 1; i <= l; i++) pages.push(i);
                } else {
                    pages.push(1);
                    if (c > 3) pages.push('...');
                    for (let i = Math.max(2, c - 1); i <= Math.min(l - 1, c + 1); i++) pages.push(i);
                    if (c < l - 2) pages.push('...');
                    pages.push(l);
                }
                return pages;
            },

            fixProtocol(url) {
                if (window.location.protocol === 'https:' && url.startsWith('http://')) {
                    return url.replace('http://', 'https://');
                }
                return url;
            },

            init() {
                window._semuaKamar = @json($data_kamar);
            },

            openEditModal(ternak) {
                this.editData = JSON.parse(JSON.stringify(ternak));
                this.editData.id_kandang = String(this.editData.id_kandang);
                this.editData.id_kamar = this.editData.id_kamar ? String(this.editData.id_kamar) : 'kosong';
                this.editData.id_jenis_ternak = String(this.editData.id_jenis_ternak);
                this.editModal = true;
            },

            goToPage(page) {
                if (page < 1 || page > this.lastPage || page === this.currentPage) return;

                // Cek cache dulu — jika halaman sudah pernah dimuat, langsung tampilkan (0ms)
                let cacheKey = this.buildCacheKey(page);
                if (this.pageCache[cacheKey]) {
                    this.applyData(this.pageCache[cacheKey]);
                    this.currentPage = page;
                    return;
                }

                this.fetchPage(page);
            },

            buildCacheKey(page) {
                let searchForm = document.getElementById('search-form');
                let filterForm = document.getElementById('filter-form');
                let params = new URLSearchParams();
                params.set('page', page);
                if (searchForm) {
                    new FormData(searchForm).forEach((v, k) => { if(v) params.set(k, v); });
                }
                if (filterForm) {
                    new FormData(filterForm).forEach((v, k) => { if(v && v !== 'semua') params.set(k, v); });
                }
                return params.toString();
            },

            fetchData() {
                this.pageCache = {};
                this.fetchPage(1);
            },

            fetchPage(page) {
                let searchForm = document.getElementById('search-form');
                let filterForm = document.getElementById('filter-form');
                let params = new URLSearchParams();
                params.set('page', page);
                if (searchForm) {
                    new FormData(searchForm).forEach((v, k) => { if(v) params.set(k, v); });
                }
                if (filterForm) {
                    new FormData(filterForm).forEach((v, k) => { if(v && v !== 'semua' && v !== 'kosong') params.set(k, v); else if (v === 'kosong') params.set(k, v); });
                }

                let url = this.fixProtocol(baseUrl + '?' + params.toString());

                if (this.abortController) {
                    this.abortController.abort();
                }
                this.abortController = new AbortController();
                this.isFetching = true;

                window.axios.get(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    signal: this.abortController.signal
                })
                .then(response => {
                    let data = response.data;
                    this.applyData(data);
                    this.currentPage = page;

                    // Cache halaman ini
                    let cacheKey = this.buildCacheKey(page);
                    this.pageCache[cacheKey] = data;
                })
                .catch(error => {
                    if (error.code === 'ERR_CANCELED' || error.name === 'CanceledError') return;
                    console.error('AJAX Error:', error);

                    if (error.response) {
                        if (error.response.status === 419) {
                            alert('Sesi Anda telah berakhir. Halaman akan dimuat ulang.');
                        } else {
                            alert('Terjadi kesalahan (' + error.response.status + '). Halaman akan dimuat ulang.');
                        }
                        window.location.reload();
                    } else {
                        alert('Koneksi terputus. Halaman akan dimuat ulang.');
                        window.location.reload();
                    }
                })
                .finally(() => {
                    this.isFetching = false;
                });
            },

            applyData(data) {
                this.rows = data.data;
                this.lastPage = data.pagination.last_page;
                this.totalData = data.pagination.total;
                this.pageFrom = data.pagination.from;
                this.pageTo = data.pagination.to;
            }
        }
    }
</script>
@endpush
