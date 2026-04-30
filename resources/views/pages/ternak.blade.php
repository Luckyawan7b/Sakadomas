@extends('layouts.app')

@section('content')
    <div x-data="{
        modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
        modalFilter: false
    }">
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

                <form method="GET" action="{{ route('ternak.index') }}" class="relative w-full sm:w-auto">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari ID Ternak..."
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
                <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">{{ $stat_total }} <span class="text-sm font-medium text-gray-500">Ekor</span></h4>
            </div>
            <div class="rounded-xl border border-green-200 bg-green-50 p-5 shadow-sm dark:border-green-900/30 dark:bg-green-500/10">
                <p class="text-sm font-medium text-green-600 dark:text-green-400">Siap Jual</p>
                <h4 class="mt-2 text-2xl font-bold text-green-700 dark:text-green-300">{{ $stat_siap_jual }} <span class="text-sm font-medium opacity-70">Ekor</span></h4>
            </div>
            <div class="rounded-xl border border-red-200 bg-red-50 p-5 shadow-sm dark:border-red-900/30 dark:bg-red-500/10">
                <p class="text-sm font-medium text-red-600 dark:text-red-400">Sakit</p>
                <h4 class="mt-2 text-2xl font-bold text-red-700 dark:text-red-300">{{ $stat_sakit }} <span class="text-sm font-medium opacity-70">Ekor</span></h4>
            </div>
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-5 shadow-sm dark:border-blue-900/30 dark:bg-blue-500/10">
                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Telah Terjual</p>
                <h4 class="mt-2 text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $stat_terjual }} <span class="text-sm font-medium opacity-70">Ekor</span></h4>
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

                    <form method="GET" action="{{ route('ternak.index') }}" class="flex flex-col gap-5">

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
                            {{-- Tombol Reset: Merupakan link yang membuang semua parameter di URL --}}
                            <a href="{{ route('ternak.index') }}"
                                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">
                                Reset Filter
                            </a>
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
                                <select name="jenis_kelamin" required
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="jantan" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ old('jenis_kelamin') == 'jantan' ? 'selected' : '' }}>Jantan</option>
                                    <option value="betina" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ old('jenis_kelamin') == 'betina' ? 'selected' : '' }}>Betina</option>
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
                                <select name="status_ternak" required
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="sehat" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ old('status_ternak') == 'sehat' ? 'selected' : '' }}>Sehat</option>
                                    <option value="sakit" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ old('status_ternak') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="hamil" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ old('status_ternak') == 'hamil' ? 'selected' : '' }}>Hamil</option>
                                    <option value="mati" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                        {{ old('status_ternak') == 'mati' ? 'selected' : '' }}>Mati</option>
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

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 mt-6 overflow-hidden">
            <div class="py-5 px-5 md:px-6 flex justify-between items-center border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Ternak
                    @if($data_ternak->total() > 0)
                        <span class="ml-2 inline-flex items-center rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-600 ring-1 ring-inset ring-brand-500/10 dark:bg-brand-500/10 dark:text-brand-400 dark:ring-brand-500/20">{{ $data_ternak->total() }} Data Ditemukan</span>
                    @endif
                </h4>
            </div>

            <div class="max-w-full overflow-x-auto">
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
                        @forelse ($data_ternak as $index => $ternak)
                            <tr x-data="{
                                modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_ternak_edit') == $ternak->id_ternak ? 'true' : 'false' }},
                                modalHapus: false
                            }"
                                class="border-b border-gray-100 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">

                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-50 dark:bg-brand-500/10">
                                            <svg class="h-5 w-5 text-brand-500 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-800 dark:text-white block">ID-{{ $ternak->id_ternak }}</span>
                                            <span class="text-sm font-medium text-gray-500 capitalize">{{ $ternak->jenis_ternak->jenis_ternak ?? 'Tipe ID: ' . $ternak->id_jenis_ternak }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-5">
                                    <span class="font-semibold text-brand-600 dark:text-brand-400 block">Kandang {{ $ternak->kamar->kandang->nomor_kandang ?? 'Kosong' }}</span>
                                    <span class="text-sm text-gray-500 flex items-center gap-1 mt-0.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        Kamar {{ $ternak->kamar->nomor_kamar ?? '-' }}
                                    </span>
                                </td>

                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1 text-sm font-medium text-gray-700 dark:text-gray-300" title="Jenis Kelamin">
                                            @if($ternak->jenis_kelamin === 'jantan')
                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m0-8h-8m8 0L8 16"></path></svg>
                                            @else
                                                <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11a4 4 0 100-8 4 4 0 000 8zm0 0v9m-3-3h6"></path></svg>
                                            @endif
                                            {{ ucfirst($ternak->jenis_kelamin) }}
                                        </div>
                                        <div class="h-4 w-px bg-gray-300 dark:bg-gray-700"></div>
                                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $ternak->usia }} Bln
                                        </div>
                                        <div class="h-4 w-px bg-gray-300 dark:bg-gray-700"></div>
                                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $ternak->berat }} Kg
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                                        Rp {{ number_format($ternak->harga, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="py-4 px-5 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        @if (strtolower($ternak->status_ternak) == 'sehat')
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-500/20 dark:text-green-300">
                                                <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-green-500"></span>Sehat
                                            </span>
                                        @elseif(strtolower($ternak->status_ternak) == 'sakit')
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-800 dark:bg-red-500/20 dark:text-red-300">
                                                <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-red-500"></span>Sakit
                                            </span>
                                        @elseif(strtolower($ternak->status_ternak) == 'hamil')
                                            <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-1 text-xs font-medium text-purple-800 dark:bg-purple-500/20 dark:text-purple-300">
                                                <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-purple-500"></span>Hamil
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-200 px-2.5 py-1 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-gray-500"></span>Mati
                                            </span>
                                        @endif

                                        <span class="text-[11px] font-bold tracking-wider text-gray-500 uppercase">
                                            {{ $ternak->status_jual }}
                                        </span>
                                    </div>
                                </td>

                                <td class="py-4 px-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('ternak.detail', $ternak->id_ternak) }}" title="Analisis Ternak"
                                            class="inline-flex items-center justify-center rounded-lg bg-brand-50 px-3 py-2 text-sm font-medium text-brand-600 transition hover:bg-brand-100 dark:bg-brand-500/10 dark:text-brand-400 dark:hover:bg-brand-500/20">
                                            Detail
                                        </a>
                                        <button @click="modalEdit = true" type="button" title="Edit Ternak"
                                            class="inline-flex items-center justify-center rounded-lg bg-amber-50 px-3 py-2 text-sm font-medium text-amber-600 transition hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20">
                                            Edit
                                        </button>
                                    </div>
                                </td>

                                {{-- MODAL EDIT TERNAK --}}
                                <template x-teleport="body">
                                    <div x-show="modalEdit" style="display: none;"
                                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                                        @click.self="modalEdit = false">
                                        <div class="no-scrollbar relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-8"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100">

                                            <div class="mb-6 flex justify-between items-start">
                                                <div>
                                                    <h4
                                                        class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                                                        Edit Ternak #ID-{{ $ternak->id_ternak }}</h4>
                                                    <p class="text-xs text-gray-500">Terakhir diupdate:
                                                        {{ \Carbon\Carbon::parse($ternak->last_update)->translatedFormat('d M Y, H:i') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <form method="POST"
                                                action="{{ route('ternak.update', $ternak->id_ternak) }}"
                                                class="flex flex-col gap-4">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="id_ternak_edit"
                                                    value="{{ $ternak->id_ternak }}">

                                                @if ($errors->any() && old('_method') === 'PUT' && old('id_ternak_edit') == $ternak->id_ternak)
                                                    <div
                                                        class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                                                        {{ $errors->first() }}</div>
                                                @endif

                                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2"
                                                    x-data='{
                                                        selectedKandang: "{{ old('id_kandang', $ternak->kamar->id_kandang ?? 'kosong') }}",
                                                        selectedKamar: "{{ old('id_kamar', $ternak->id_kamar ?? 'kosong') }}",
                                                        semuaKamar: @json($data_kamar),
                                                        get kamarTersedia() {
                                                            if (this.selectedKandang === "kosong") return [];
                                                            return this.semuaKamar.filter(k => k.id_kandang == this.selectedKandang);
                                                    }
                                                }'>
                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pindah
                                                            Kandang</label>
                                                        <select name="id_kandang" x-model="selectedKandang"
                                                            @change="selectedKamar = (selectedKandang === 'kosong' ? 'kosong' : '')"
                                                            required
                                                            class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                            <option value="" disabled class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Kandang</option>
                                                            @foreach($data_kandang as $kd)
                                                                <option value="{{ $kd->id_kandang }}" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Kandang {{ $kd->nomor_kandang }}</option>
                                                            @endforeach
                                                            <option value="kosong" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white ">Kosong (Keluar Kandang)</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pindah
                                                            Kamar</label>
                                                        <select name="id_kamar" x-model="selectedKamar" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                            <option value="" disabled x-show="selectedKandang !== 'kosong'" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Kamar</option>
                                                            <option value="kosong" x-show="selectedKandang === 'kosong'" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Kosong</option>

                                                            <template x-for="kamar in kamarTersedia" :key="kamar.id_kamar">
                                                                <option class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" :value="kamar.id_kamar" x-text="'Kamar ' + kamar.nomor_kamar"></option>
                                                            </template>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis
                                                            Ternak</label>
                                                        <select name="id_jenis_ternak" required
                                                            class="capitalize dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                            @foreach ($data_jenis as $jenis)
                                                                <option value="{{ $jenis->id_jenis_ternak }}"
                                                                    class="capitalize bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                                                    {{ $ternak->id_jenis_ternak == $jenis->id_jenis_ternak ? 'selected' : '' }}>
                                                                    {{ $jenis->jenis_ternak }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kelamin</label>
                                                        <select name="jenis_kelamin" required
                                                            class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                            <option value="jantan"
                                                                class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                                                {{ $ternak->jenis_kelamin == 'jantan' ? 'selected' : '' }}>
                                                                Jantan</option>
                                                            <option value="betina"
                                                                class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                                                {{ $ternak->jenis_kelamin == 'betina' ? 'selected' : '' }}>
                                                                Betina</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Usia
                                                            (Bulan)
                                                        </label>
                                                        <input type="number" name="usia" value="{{ $ternak->usia }}"
                                                            required min="0"
                                                            class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Berat
                                                            (Kg)</label>
                                                        <input type="number" name="berat"
                                                            value="{{ $ternak->berat }}" required min="0"
                                                            class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga
                                                            Jual (Rp)</label>
                                                        <input type="number" name="harga"
                                                            value="{{ $ternak->harga }}" required min="0"
                                                            class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kesehatan</label>
                                                        <select name="status_ternak" required
                                                            class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                            <option value="sehat"
                                                                class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                                                {{ $ternak->status_ternak == 'sehat' ? 'selected' : '' }}>
                                                                Sehat</option>
                                                            <option value="sakit"
                                                                class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                                                {{ $ternak->status_ternak == 'sakit' ? 'selected' : '' }}>
                                                                Sakit</option>
                                                            <option value="hamil"
                                                                class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                                                {{ $ternak->status_ternak == 'hamil' ? 'selected' : '' }}>
                                                                Hamil</option>
                                                            <option value="mati"
                                                                class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                                                {{ $ternak->status_ternak == 'mati' ? 'selected' : '' }}>
                                                                Mati</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Penjualan</label>
                                                        <select name="status_jual" required
                                                            class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                            <option value="tidak dijual"
                                                                class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                                                {{ $ternak->status_jual == 'tidak dijual' ? 'selected' : '' }}>
                                                                Tidak Dijual</option>
                                                            <option value="siap jual"
                                                                class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                                                {{ $ternak->status_jual == 'siap jual' ? 'selected' : '' }}>
                                                                Siap Jual</option>
                                                            <option value="booking"
                                                                class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                                                {{ $ternak->status_jual == 'booking' ? 'selected' : '' }}>
                                                                Booking</option>
                                                            <option value="terjual"
                                                                class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"
                                                                {{ $ternak->status_jual == 'terjual' ? 'selected' : '' }}>
                                                                Terjual</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-3 mt-4 justify-end">
                                                    <button @click="modalEdit = false" type="button"
                                                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">Batal</button>
                                                    <button type="submit"
                                                        class="flex w-full justify-center rounded-lg bg-yellow-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-yellow-600 sm:w-auto">Simpan
                                                        Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </template>

                                {{-- MODAL HAPUS TERNAK (TETAP) --}}
                                {{-- <template x-teleport="body">
                                    <div x-show="modalHapus" style="display: none;"
                                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                                        @click.self="modalHapus = false">
                                        <div class="relative w-full max-w-[400px] rounded-3xl bg-white p-6 dark:bg-gray-900 text-center"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100">
                                            <h4 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">Hapus
                                                Data Ternak?</h4>
                                            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Yakin ingin menghapus
                                                ternak <strong>#ID-{{ $ternak->id_ternak }}</strong>?</p>

                                            <form method="POST"
                                                action="{{ route('ternak.delete', $ternak->id_ternak) }}"
                                                class="flex justify-center gap-3">
                                                @csrf
                                                @method('DELETE')
                                                <button @click="modalHapus = false" type="button"
                                                    class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800  dark:text-gray-300 dark:hover:bg-white/[0.03]">Batal</button>
                                                <button type="submit"
                                                    class="rounded-lg bg-red-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-red-600">Ya,
                                                    Hapus!</button>
                                            </form>
                                        </div>
                                    </div>
                                </template> --}}

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">Tidak ada data ternak yang sesuai
                                            dengan filter.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($data_ternak->hasPages())
                <div class="border-t border-gray-200 dark:border-gray-800 p-4">
                    {{ $data_ternak->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection
