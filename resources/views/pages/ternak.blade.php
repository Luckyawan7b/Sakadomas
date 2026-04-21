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

        <div class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900 mt-6">
            <div class="py-6 px-4 md:px-6 xl:px-7.5">
                <h4 class="text-xl font-semibold text-black dark:text-white">Daftar Ternak: <span
                        class="text-brand-500">{{ $data_ternak->total() }} Ekor Total</span></h4>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 text-left dark:bg-gray-800 border-b border-gray-200 dark:border-gray-800">
                            <th class="py-4 px-4 font-medium text-black dark:text-white">ID Ternak</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white">Lokasi</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white">Jenis & Profil</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white">Berat & Harga</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white text-center">Status</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_ternak as $index => $ternak)
                            <tr x-data="{
                                modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_ternak_edit') == $ternak->id_ternak ? 'true' : 'false' }},
                                modalHapus: false
                            }"
                                class="border-b border-gray-200 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50">

                                <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                    <span class="font-medium ">ID-{{ $ternak->id_ternak }}</span><br>
                                    </span>
                                </td>

                                <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                    <span class="font-bold text-brand-500">Kandang
                                        {{ $ternak->kamar->kandang->nomor_kandang ?? 'kosong' }}</span><br>
                                    <span class="text-xs text-gray-500">
                                        Kamar {{ $ternak->kamar->nomor_kamar ?? '-' }} <br>
                                    </span>
                                </td>

                                <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                    <span
                                        class="font-medium capitalize">{{ $ternak->jenis_ternak->jenis_ternak ?? 'ID: ' . $ternak->id_jenis_ternak }}</span><br>
                                    <span class="text-sm capitalize">{{ $ternak->jenis_kelamin }}</span> • <span
                                        class="text-sm">{{ $ternak->usia }} Bln</span>
                                </td>

                                <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                    <span class="font-medium">{{ $ternak->berat }} Kg</span><br>
                                    <span class="text-sm text-green-600 dark:text-green-400">Rp
                                        {{ number_format($ternak->harga, 0, ',', '.') }}</span>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if (strtolower($ternak->status_ternak) == 'sehat')
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-500/10">Sehat</span>
                                    @elseif(strtolower($ternak->status_ternak) == 'sakit')
                                        <span
                                            class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-500/10">Sakit</span>
                                    @elseif(strtolower($ternak->status_ternak) == 'hamil')
                                        <span
                                            class="inline-flex rounded-full bg-purple-100 px-2 py-1 text-xs font-medium text-purple-700 dark:bg-purple-500/10">Hamil</span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-gray-800 px-2 py-1 text-xs font-medium text-white dark:bg-gray-700">Mati</span>
                                    @endif
                                    <br>
                                    <span class="mt-1 inline-block text-xs text-gray-500 uppercase font-semibold">
                                        {{ $ternak->status_jual }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center space-x-3.5">
                                        <a href="{{ route('ternak.detail', $ternak->id_ternak) }}"
                                            class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            Detail
                                        </a>

                                        <button @click="modalEdit = true" type="button"
                                            class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-yellow-500 text-white shadow-theme-xs hover:bg-yellow-600">
                                            Edit
                                        </button>

                                        {{-- <button @click="modalHapus = true" type="button"
                                            class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-red-500 text-white shadow-theme-xs hover:bg-red-600">
                                            Hapus
                                        </button> --}}
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
