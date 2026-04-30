@php
    // Mengambil data ternak yang belum memiliki kandang (id_kamar = null) secara otomatis
    if (!isset($ternak_kosong)) {
        $ternak_kosong = \App\Models\ternakModel::with('jenis_ternak')->whereNull('id_kamar')->get();
    }
@endphp

@extends('layouts.app')

@section('content')
    <div x-data="{
        filterStatus: 'semua',
        modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }}
    }">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                class="mb-5 flex items-center justify-between gap-3 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800 dark:bg-green-900/20 dark:border-green-800/30 dark:text-green-400">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
                <button @click="show = false" class="text-green-600 hover:text-green-800 dark:text-green-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 8000)"
                class="mb-5 flex items-center justify-between gap-3 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800 dark:bg-red-900/20 dark:border-red-800/30 dark:text-red-400">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('error') }}
                </div>
                <button @click="show = false" class="text-red-600 hover:text-red-800"><svg class="h-4 w-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg></button>
            </div>
        @endif

        {{-- ===== HEADER ===== --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('kandang.kamar', $kandang->id_kandang) }}"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <div>
                    <div class="flex items-center gap-2 mb-0.5">
                        <h2 class="text-title-md2 font-bold text-black dark:text-white">
                            Kamar {{ $kamar->nomor_kamar }}
                        </h2>
                        <span
                            class="inline-flex items-center rounded-lg bg-brand-50 dark:bg-brand-500/10 px-2.5 py-1 text-xs font-semibold text-brand-600 dark:text-brand-400">
                            Kandang {{ $kandang->nomor_kandang }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Daftar ternak yang menempati kamar ini.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Filter Status Kesehatan --}}
                <div
                    class="inline-flex rounded-lg border border-gray-200 bg-gray-50 p-1 dark:border-gray-700 dark:bg-gray-800">
                    @foreach (['semua' => 'Semua', 'sehat' => 'Sehat', 'sakit' => 'Sakit', 'hamil' => 'Hamil'] as $val => $label)
                        <button @click="filterStatus = '{{ $val }}'"
                            :class="filterStatus === '{{ $val }}' ?
                                'bg-white shadow text-brand-500 dark:bg-gray-700 dark:text-brand-400' :
                                'text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                            class="px-3 py-1.5 text-xs font-medium rounded-md transition-all">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                @if (strtolower(trim($kamar->status ?? '')) === 'karantina')
                    <button type="button"
                        class="inline-flex items-center justify-center font-medium gap-2 rounded-xl border border-amber-300 bg-amber-50 px-4 py-2.5 text-sm text-amber-700 cursor-not-allowed dark:border-amber-700 dark:bg-amber-900/10 dark:text-amber-400"
                        title="Kamar sedang dikarantina">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z"
                                clip-rule="evenodd" />
                        </svg>
                        Karantina Aktif
                    </button>
                @else
                    <button @click="modalTambah = true" type="button"
                        class="inline-flex items-center justify-center font-medium gap-2 rounded-xl transition px-4 py-2.5 text-sm bg-brand-500 text-white shadow-lg shadow-brand-500/30 hover:bg-brand-600 hover:-translate-y-0.5 hover:shadow-brand-500/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Ternak
                    </button>
                @endif
            </div>
        </div>

        {{-- ===== KARANTINA BANNER ===== --}}
        @if (strtolower(trim($kamar->status ?? '')) === 'karantina')
            <div
                class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-amber-200 bg-gradient-to-r from-amber-50 to-orange-50 p-5 dark:border-amber-800/30 dark:bg-amber-900/10">
                <div class="flex items-start gap-4">
                    <div
                        class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-500/20">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-amber-800 dark:text-amber-400">Kamar Dalam Status Karantina</h4>
                        <p class="text-sm text-amber-700 dark:text-amber-500 mt-0.5">Penambahan ternak baru dikunci
                            sementara. Semua ternak di kamar ini berstatus sakit.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('kamar.update', $kamar->id_kamar) }}" class="flex-shrink-0">
                    @csrf @method('PUT')
                    <input type="hidden" name="id_kandang" value="{{ $kamar->id_kandang }}">
                    <input type="hidden" name="nomor_kamar" value="{{ $kamar->nomor_kamar }}">
                    <input type="hidden" name="kapasitas" value="{{ $kamar->kapasitas }}">
                    <input type="hidden" name="status" value="aktif">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-amber-600 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>
                        Cabut Karantina
                    </button>
                </form>
            </div>
        @endif

        {{-- ===== STATISTIK RINGKAS ===== --}}
        @php
            $countSehat = 0;
            $countSakit = 0;
            $countHamil = 0;
            foreach ($data_ternak as $t) {
                $st = strtolower(trim($t->status_ternak));
                if ($st == 'sehat') {
                    $countSehat++;
                } elseif ($st == 'sakit') {
                    $countSakit++;
                } elseif ($st == 'hamil') {
                    $countHamil++;
                }
            }
            $totalTernak = $data_ternak->count();
            $persen = $kamar->kapasitas > 0 ? round(($totalTernak / $kamar->kapasitas) * 100) : 0;
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            {{-- Kapasitas --}}
            <div
                class="col-span-2 sm:col-span-1 rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-100 dark:bg-brand-500/10">
                        <svg class="w-4.5 h-4.5 text-brand-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Kapasitas</p>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTernak }}<span
                        class="text-base font-medium text-gray-400 dark:text-gray-500">/{{ $kamar->kapasitas }}</span></p>
                <div class="mt-2 h-1.5 w-full rounded-full bg-gray-100 dark:bg-gray-800 overflow-hidden">
                    <div class="{{ $persen >= 100 ? 'bg-red-500' : 'bg-brand-500' }} h-1.5 rounded-full transition-all"
                        style="width: {{ min($persen, 100) }}%"></div>
                </div>
            </div>

            {{-- Sehat --}}
            <div class="rounded-2xl border border-green-100 bg-green-50 p-4 dark:border-green-800/30 dark:bg-green-900/10">
                <div class="flex items-center gap-2 mb-2">
                    <span class="h-2 w-2 rounded-full bg-green-500"></span>
                    <p class="text-xs font-medium text-green-700 dark:text-green-400">Sehat</p>
                </div>
                <p class="text-3xl font-bold text-green-700 dark:text-green-400">{{ $countSehat }}</p>
                <p class="text-xs text-green-600/70 dark:text-green-500/70 mt-1">ekor</p>
            </div>

            {{-- Sakit --}}
            <div class="rounded-2xl border border-red-100 bg-red-50 p-4 dark:border-red-800/30 dark:bg-red-900/10">
                <div class="flex items-center gap-2 mb-2">
                    <span class="h-2 w-2 rounded-full bg-red-500 {{ $countSakit > 0 ? 'animate-pulse' : '' }}"></span>
                    <p class="text-xs font-medium text-red-700 dark:text-red-400">Sakit</p>
                </div>
                <p class="text-3xl font-bold text-red-700 dark:text-red-400">{{ $countSakit }}</p>
                <p class="text-xs text-red-600/70 dark:text-red-500/70 mt-1">ekor</p>
            </div>

            {{-- Hamil --}}
            <div
                class="rounded-2xl border border-purple-100 bg-purple-50 p-4 dark:border-purple-800/30 dark:bg-purple-900/10">
                <div class="flex items-center gap-2 mb-2">
                    <span class="h-2 w-2 rounded-full bg-purple-500"></span>
                    <p class="text-xs font-medium text-purple-700 dark:text-purple-400">Hamil</p>
                </div>
                <p class="text-3xl font-bold text-purple-700 dark:text-purple-400">{{ $countHamil }}</p>
                <p class="text-xs text-purple-600/70 dark:text-purple-500/70 mt-1">ekor</p>
            </div>
        </div>

        {{-- ===== MODAL TAMBAH TERNAK ===== --}}
        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/60 px-4 py-5 backdrop-blur-sm"
                @click.self="modalTambah = false">
                <div class="no-scrollbar relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-2">
                        <h4 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Tambah Ternak</h4>
                        <p class="text-sm text-gray-500 mt-1">ke <strong>Kandang {{ $kandang->nomor_kandang }}</strong>,
                            <strong>Kamar {{ $kamar->nomor_kamar }}</strong></p>
                    </div>

                    @if ($errors->any() && !old('_method'))
                        <div class="mt-4 mb-2 rounded-xl bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                            {{ $errors->first() }}</div>
                    @endif

                    <div x-data="{
                        tab: 'baru',
                        selectedTernakId: '',
                        ternakKosong: {{ $ternak_kosong->toJson() }},
                        selectedTernakData: {},
                        updateTernakData() {
                            this.selectedTernakData = this.ternakKosong.find(t => t.id_ternak == this.selectedTernakId) || {};
                        }
                    }">
                        {{-- Tab Navigation --}}
                        <div class="flex border-b border-gray-200 dark:border-gray-700 mt-5 mb-6">
                            <button type="button" @click="tab = 'baru'"
                                :class="tab === 'baru' ? 'border-brand-500 text-brand-500' :
                                    'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                                class="flex-1 py-2.5 text-sm font-medium border-b-2 transition-colors text-center">
                                🐑 Registrasi Baru
                            </button>
                            <button type="button" @click="tab = 'ada'"
                                :class="tab === 'ada' ? 'border-brand-500 text-brand-500' :
                                    'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                                class="flex-1 py-2.5 text-sm font-medium border-b-2 transition-colors text-center">
                                📦 Dari Stok Ada
                            </button>
                        </div>

                        {{-- TAB 1: FORM TERNAK BARU --}}
                        <form x-show="tab === 'baru'" method="POST" action="{{ route('ternak.store') }}"
                            class="flex flex-col gap-4">
                            @csrf
                            <input type="hidden" name="id_kandang" value="{{ $kandang->id_kandang }}">
                            <input type="hidden" name="id_kamar" value="{{ $kamar->id_kamar }}">

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis
                                        Ternak</label>
                                    <select name="id_jenis_ternak" required
                                        class="dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white capitalize">
                                        <option value="" disabled selected>Pilih Jenis</option>
                                        @foreach ($data_jenis as $jenis)
                                            <option value="{{ $jenis->id_jenis_ternak }}"
                                                {{ old('id_jenis_ternak') == $jenis->id_jenis_ternak ? 'selected' : '' }}>
                                                {{ $jenis->jenis_ternak }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis
                                        Kelamin</label>
                                    <select name="jenis_kelamin" required
                                        class="dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white">
                                        <option value="jantan" {{ old('jenis_kelamin') == 'jantan' ? 'selected' : '' }}>♂
                                            Jantan</option>
                                        <option value="betina" {{ old('jenis_kelamin') == 'betina' ? 'selected' : '' }}>♀
                                            Betina</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Usia
                                        (Bulan)</label>
                                    <input type="number" name="usia" value="{{ old('usia') }}" required
                                        min="0" placeholder="0"
                                        class="dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white">
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Berat
                                        (Kg)</label>
                                    <input type="number" name="berat" value="{{ old('berat') }}" required
                                        min="0" placeholder="0"
                                        class="dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white">
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status
                                        Kesehatan</label>
                                    <select name="status_ternak" required
                                        class="dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white">
                                        <option value="sehat" {{ old('status_ternak') == 'sehat' ? 'selected' : '' }}>✅
                                            Sehat</option>
                                        <option value="sakit" {{ old('status_ternak') == 'sakit' ? 'selected' : '' }}>🔴
                                            Sakit</option>
                                        <option value="hamil" {{ old('status_ternak') == 'hamil' ? 'selected' : '' }}>🟣
                                            Hamil</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status
                                        Penjualan</label>
                                    <select name="status_jual" required
                                        class="dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white">
                                        <option value="tidak dijual"
                                            {{ old('status_jual') == 'tidak dijual' ? 'selected' : '' }}>Tidak Dijual
                                        </option>
                                        <option value="siap jual"
                                            {{ old('status_jual') == 'siap jual' ? 'selected' : '' }}>Siap Jual</option>
                                        <option value="booking" {{ old('status_jual') == 'booking' ? 'selected' : '' }}>
                                            Booking</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 mt-2 justify-end">
                                <button @click="modalTambah = false" type="button"
                                    class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Batal</button>
                                <button type="submit"
                                    class="rounded-xl bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Simpan
                                    Ternak</button>
                            </div>
                        </form>

                        {{-- TAB 2: PILIH DARI STOK ADA --}}
                        <form x-show="tab === 'ada'" method="POST" :action="'/ternak/' + selectedTernakId"
                            style="display: none;" class="flex flex-col gap-4">
                            @csrf @method('PUT')
                            <input type="hidden" name="id_kandang" value="{{ $kandang->id_kandang }}">
                            <input type="hidden" name="id_kamar" value="{{ $kamar->id_kamar }}">
                            <input type="hidden" name="id_ternak_edit" :value="selectedTernakId">
                            <input type="hidden" name="id_jenis_ternak" :value="selectedTernakData.id_jenis_ternak">
                            <input type="hidden" name="jenis_kelamin" :value="selectedTernakData.jenis_kelamin">
                            <input type="hidden" name="usia" :value="selectedTernakData.usia">
                            <input type="hidden" name="berat" :value="selectedTernakData.berat">
                            <input type="hidden" name="harga" :value="selectedTernakData.harga">
                            <input type="hidden" name="status_ternak" :value="selectedTernakData.status_ternak">
                            <input type="hidden" name="status_jual" :value="selectedTernakData.status_jual">

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih
                                    Ternak Tanpa Kandang</label>
                                @if ($ternak_kosong->count() > 0)
                                    <select x-model="selectedTernakId" @change="updateTernakData" required
                                        class="capitalize dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white">
                                        <option value="" disabled>Pilih Ternak...</option>
                                        @foreach ($ternak_kosong as $tk)
                                            <option value="{{ $tk->id_ternak }}">#ID-{{ $tk->id_ternak }} —
                                                {{ $tk->jenis_ternak->jenis_ternak ?? 'Ternak' }} · {{ $tk->berat }}Kg
                                                · {{ ucfirst($tk->jenis_kelamin) }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <div
                                        class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Tidak ada ternak kosong tersedia.
                                    </div>
                                @endif
                            </div>

                            <div x-show="selectedTernakId !== ''"
                                class="rounded-xl bg-brand-50 dark:bg-brand-500/10 border border-brand-100 dark:border-brand-500/20 p-4 text-sm">
                                <div class="grid grid-cols-3 gap-3 text-center">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Jenis Kelamin</p>
                                        <p class="font-semibold text-gray-800 dark:text-white capitalize"
                                            x-text="selectedTernakData.jenis_kelamin"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Usia</p>
                                        <p class="font-semibold text-gray-800 dark:text-white"><span
                                                x-text="selectedTernakData.usia"></span> Bln</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Berat</p>
                                        <p class="font-semibold text-gray-800 dark:text-white"><span
                                                x-text="selectedTernakData.berat"></span> Kg</p>
                                    </div>
                                </div>
                                <p class="text-center text-xs text-brand-600 dark:text-brand-400 mt-3 font-medium">
                                    Ternak ini akan dimasukkan ke Kamar {{ $kamar->nomor_kamar }}
                                </p>
                            </div>

                            <div class="flex items-center gap-3 mt-2 justify-end">
                                <button @click="modalTambah = false" type="button"
                                    class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Batal</button>
                                <button type="submit" :disabled="selectedTernakId === ''"
                                    :class="selectedTernakId === '' ? 'opacity-50 cursor-not-allowed' :
                                        'hover:bg-brand-600'"
                                    class="rounded-xl bg-brand-500 px-5 py-2.5 text-sm font-medium text-white">
                                    Masukkan ke Kamar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        {{-- ===== DAFTAR TERNAK ===== --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            {{-- Table Header --}}
            <div
                class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <h4 class="font-semibold text-gray-800 dark:text-white text-sm">
                    Daftar Ternak
                    <span
                        class="ml-2 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                        {{ $totalTernak >= $kamar->kapasitas ? 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400' : 'bg-brand-100 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400' }}">
                        {{ $totalTernak }} / {{ $kamar->kapasitas }} Ekor
                    </span>
                </h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 hidden sm:block">Klik "Analisis" untuk melihat grafik
                    pertumbuhan</p>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800 text-left">
                            <th
                                class="py-3.5 px-5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Ternak</th>
                            <th
                                class="py-3.5 px-5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Profil Fisik</th>
                            <th
                                class="py-3.5 px-5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Harga</th>
                            <th
                                class="py-3.5 px-5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">
                                Status</th>
                            <th
                                class="py-3.5 px-5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($data_ternak as $index => $ternak)
                            @php
                                $st = strtolower(trim($ternak->status_ternak));
                                $statusConfig = match ($st) {
                                    'sehat' => [
                                        'bg' => 'bg-green-100 dark:bg-green-500/10',
                                        'text' => 'text-green-700 dark:text-green-400',
                                        'dot' => 'bg-green-500',
                                        'label' => 'Sehat',
                                    ],
                                    'sakit' => [
                                        'bg' => 'bg-red-100 dark:bg-red-500/10',
                                        'text' => 'text-red-700 dark:text-red-400',
                                        'dot' => 'bg-red-500 animate-pulse',
                                        'label' => 'Sakit',
                                    ],
                                    'hamil' => [
                                        'bg' => 'bg-purple-100 dark:bg-purple-500/10',
                                        'text' => 'text-purple-700 dark:text-purple-400',
                                        'dot' => 'bg-purple-500',
                                        'label' => 'Hamil',
                                    ],
                                    default => [
                                        'bg' => 'bg-gray-100',
                                        'text' => 'text-gray-600',
                                        'dot' => 'bg-gray-400',
                                        'label' => ucfirst($st),
                                    ],
                                };
                                $jualConfig = match ($ternak->status_jual) {
                                    'siap jual' => [
                                        'bg' => 'bg-emerald-50 dark:bg-emerald-500/10',
                                        'text' => 'text-emerald-700 dark:text-emerald-400',
                                    ],
                                    'booking' => [
                                        'bg' => 'bg-amber-50 dark:bg-amber-500/10',
                                        'text' => 'text-amber-700 dark:text-amber-400',
                                    ],
                                    'terjual' => [
                                        'bg' => 'bg-blue-50 dark:bg-blue-500/10',
                                        'text' => 'text-blue-700 dark:text-blue-400',
                                    ],
                                    default => [
                                        'bg' => 'bg-gray-50 dark:bg-gray-800',
                                        'text' => 'text-gray-600 dark:text-gray-400',
                                    ],
                                };
                            @endphp

                            <tr x-data="{
                                modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_ternak_edit') == $ternak->id_ternak ? 'true' : 'false' }},
                                modalHapus: false
                            }"
                                x-show="filterStatus === 'semua' || filterStatus === '{{ $st }}'" x-transition
                                class="hover:bg-gray-50/80 dark:hover:bg-gray-800/40 transition-colors group">

                                {{-- Ternak Info --}}
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        {{-- Avatar --}}
                                        <div
                                            class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-xl
                                            {{ $st === 'sehat' ? 'bg-green-100 dark:bg-green-500/10' : ($st === 'sakit' ? 'bg-red-100 dark:bg-red-500/10' : 'bg-purple-100 dark:bg-purple-500/10') }}">
                                            <span class="text-lg">🐑</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-brand-500 text-sm">#ID-{{ $ternak->id_ternak }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">
                                                {{ $ternak->jenis_ternak->jenis_ternak ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Profil Fisik --}}
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3 text-sm">
                                        <div class="text-center">
                                            <p class="font-semibold text-gray-800 dark:text-white">{{ $ternak->usia }}</p>
                                            <p class="text-[10px] text-gray-400">bulan</p>
                                        </div>
                                        <div class="h-6 w-px bg-gray-200 dark:bg-gray-700"></div>
                                        <div class="text-center">
                                            <p class="font-semibold text-gray-800 dark:text-white">{{ $ternak->berat }}
                                            </p>
                                            <p class="text-[10px] text-gray-400">kg</p>
                                        </div>
                                        <div class="h-6 w-px bg-gray-200 dark:bg-gray-700"></div>
                                        <div>
                                            <span
                                                class="inline-flex items-center gap-1 text-xs font-medium text-gray-600 dark:text-gray-300 capitalize">
                                                {{ $ternak->jenis_kelamin == 'jantan' ? '♂' : '♀' }}
                                                {{ ucfirst($ternak->jenis_kelamin) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Harga --}}
                                <td class="py-4 px-5">
                                    <p class="font-semibold text-green-600 dark:text-green-400 text-sm">Rp
                                        {{ number_format($ternak->harga, 0, ',', '.') }}</p>
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-medium {{ $jualConfig['bg'] }} {{ $jualConfig['text'] }} mt-1 capitalize">
                                        {{ $ternak->status_jual }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="py-4 px-5 text-center">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="py-4 px-5">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- ✅ TOMBOL DETAIL GRAFIK BARU --}}
                                        <a href="{{ route('ternak.detail', $ternak->id_ternak) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-2 text-xs font-semibold text-white hover:bg-brand-600 transition shadow-sm shadow-brand-500/30"
                                            title="Lihat grafik pertumbuhan ternak ini">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                            Detail
                                        </a>

                                        {{-- Tombol Edit --}}
                                        <button @click="modalEdit = true" type="button"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-amber-100 dark:bg-amber-500/10 px-3 py-2 text-xs font-semibold text-amber-700 dark:text-amber-400 hover:bg-amber-200 dark:hover:bg-amber-500/20 transition"
                                            title="Edit data ternak">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>

                                        {{-- Tombol Keluar Kandang --}}
                                        <button @click="modalHapus = true" type="button"
                                            class="inline-flex items-center rounded-lg bg-gray-100 dark:bg-gray-800 px-2.5 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:bg-red-100 hover:text-red-700 dark:hover:bg-red-500/10 dark:hover:text-red-400 transition"
                                            title="Keluarkan dari kandang">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>

                                {{-- MODAL EDIT TERNAK --}}
                                <template x-teleport="body">
                                    <div x-show="modalEdit" style="display: none;"
                                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/60 px-4 py-5 backdrop-blur-sm"
                                        @click.self="modalEdit = false">
                                        <div class="no-scrollbar relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100">

                                            <div class="flex items-center gap-3 mb-6">
                                                <div
                                                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-500/10">
                                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">Edit
                                                        Ternak <span
                                                            class="text-brand-500">#ID-{{ $ternak->id_ternak }}</span>
                                                    </h4>
                                                    <p class="text-xs text-gray-400">Update terakhir:
                                                        {{ \Carbon\Carbon::parse($ternak->last_update)->translatedFormat('d M Y') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <form method="POST"
                                                action="{{ route('ternak.update', $ternak->id_ternak) }}"
                                                class="flex flex-col gap-4">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="id_ternak_edit"
                                                    value="{{ $ternak->id_ternak }}">

                                                @if ($errors->any() && old('_method') === 'PUT' && old('id_ternak_edit') == $ternak->id_ternak)
                                                    <div
                                                        class="mb-2 rounded-xl bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                                                        {{ $errors->first() }}</div>
                                                @endif

                                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2"
                                                    x-data="{
                                                        selectedKandang: '{{ old('id_kandang', $ternak->id_kamar ? $ternak->kamar->id_kandang : 'kosong') }}',
                                                        selectedKamar: '{{ old('id_kamar', $ternak->id_kamar ?? 'kosong') }}'
                                                    }">
                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pindah
                                                            Kandang</label>
                                                        <select name="id_kandang" x-model="selectedKandang"
                                                            @change="selectedKamar = (selectedKandang === 'kosong' ? 'kosong' : '')"
                                                            required
                                                            class="dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm dark:border-gray-700 dark:text-white">
                                                            @foreach ($data_kandang as $kd)
                                                                <option value="{{ $kd->id_kandang }}">Kandang
                                                                    {{ $kd->nomor_kandang }}</option>
                                                            @endforeach
                                                            <option value="kosong">Kosong (Keluar Kandang)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pindah
                                                            Kamar</label>
                                                        <select name="id_kamar" x-model="selectedKamar" required
                                                            class="dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm dark:border-gray-700 dark:text-white">
                                                            <option value="" disabled
                                                                x-show="selectedKandang !== 'kosong'">Pilih Kamar</option>
                                                            <option value="kosong" x-show="selectedKandang === 'kosong'">
                                                                Kosong</option>
                                                            @foreach ($data_kamar as $kamarList)
                                                                <option value="{{ $kamarList->id_kamar }}"
                                                                    x-show="selectedKandang == '{{ $kamarList->id_kandang }}'"
                                                                    :hidden="selectedKandang != '{{ $kamarList->id_kandang }}'">
                                                                    Kamar {{ $kamarList->nomor_kamar }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis
                                                            Ternak</label>
                                                        <select name="id_jenis_ternak" required
                                                            class="capitalize dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm dark:border-gray-700 dark:text-white">
                                                            @foreach ($data_jenis as $jenis)
                                                                <option value="{{ $jenis->id_jenis_ternak }}"
                                                                    {{ $ternak->id_jenis_ternak == $jenis->id_jenis_ternak ? 'selected' : '' }}>
                                                                    {{ $jenis->jenis_ternak }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kelamin</label>
                                                        <select name="jenis_kelamin" required
                                                            class="dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm dark:border-gray-700 dark:text-white">
                                                            <option value="jantan"
                                                                {{ $ternak->jenis_kelamin == 'jantan' ? 'selected' : '' }}>
                                                                ♂ Jantan</option>
                                                            <option value="betina"
                                                                {{ $ternak->jenis_kelamin == 'betina' ? 'selected' : '' }}>
                                                                ♀ Betina</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Usia
                                                            (Bulan)</label>
                                                        <input type="number" name="usia" value="{{ $ternak->usia }}"
                                                            required min="0"
                                                            class="dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm dark:border-gray-700 dark:text-white">
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Berat
                                                            (Kg)</label>
                                                        <input type="number" name="berat"
                                                            value="{{ $ternak->berat }}" required min="0"
                                                            class="dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm dark:border-gray-700 dark:text-white">
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga
                                                            Jual (Rp)</label>
                                                        <input type="number" name="harga"
                                                            value="{{ $ternak->harga }}" required min="0"
                                                            class="dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm dark:border-gray-700 dark:text-white">
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kesehatan</label>
                                                        <select name="status_ternak" required
                                                            class="dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm dark:border-gray-700 dark:text-white">
                                                            <option value="sehat"
                                                                {{ $ternak->status_ternak == 'sehat' ? 'selected' : '' }}>✅
                                                                Sehat</option>
                                                            <option value="sakit"
                                                                {{ $ternak->status_ternak == 'sakit' ? 'selected' : '' }}>
                                                                🔴 Sakit</option>
                                                            <option value="hamil"
                                                                {{ $ternak->status_ternak == 'hamil' ? 'selected' : '' }}>
                                                                🟣 Hamil</option>
                                                            <option value="mati"
                                                                {{ $ternak->status_ternak == 'mati' ? 'selected' : '' }}>⚫
                                                                Mati</option>
                                                        </select>
                                                    </div>

                                                    <div class="sm:col-span-2">
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Penjualan</label>
                                                        <select name="status_jual" required
                                                            class="dark:bg-gray-900 h-11 w-full rounded-xl border border-gray-300 bg-transparent px-4 py-2 text-sm dark:border-gray-700 dark:text-white">
                                                            <option value="tidak dijual"
                                                                {{ $ternak->status_jual == 'tidak dijual' ? 'selected' : '' }}>
                                                                Tidak Dijual</option>
                                                            <option value="siap jual"
                                                                {{ $ternak->status_jual == 'siap jual' ? 'selected' : '' }}>
                                                                Siap Jual</option>
                                                            <option value="booking"
                                                                {{ $ternak->status_jual == 'booking' ? 'selected' : '' }}>
                                                                Booking</option>
                                                            <option value="terjual"
                                                                {{ $ternak->status_jual == 'terjual' ? 'selected' : '' }}>
                                                                Terjual</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-3 mt-2 justify-end">
                                                    <button @click="modalEdit = false" type="button"
                                                        class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Batal</button>
                                                    <button type="submit"
                                                        class="rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-amber-600">Simpan
                                                        Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </template>

                                {{-- MODAL KELUAR KANDANG --}}
                                <template x-teleport="body">
                                    <div x-show="modalHapus" style="display: none;"
                                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/60 px-4 py-5 backdrop-blur-sm"
                                        @click.self="modalHapus = false">
                                        <div class="relative w-full max-w-[400px] rounded-3xl bg-white p-6 dark:bg-gray-900 text-center"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100">

                                            <div
                                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-100 dark:bg-orange-500/10">
                                                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                            </div>

                                            <h4 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">
                                                Keluarkan dari Kandang?</h4>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                Ternak <strong
                                                    class="text-brand-500">#ID-{{ $ternak->id_ternak }}</strong> akan
                                                dikeluarkan dari kamar ini.
                                            </p>
                                            <p class="mb-6 text-xs text-gray-400 dark:text-gray-500">Data ternak tetap
                                                tersimpan dan bisa dipindah ke kamar lain kapan saja.</p>

                                            <form method="POST"
                                                action="{{ route('ternak.delete', $ternak->id_ternak) }}"
                                                class="flex justify-center gap-3">
                                                @csrf @method('DELETE')
                                                <button @click="modalHapus = false" type="button"
                                                    class="rounded-xl border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Batal</button>
                                                <button type="submit"
                                                    class="rounded-xl bg-red-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-red-600">Ya,
                                                    Keluarkan!</button>
                                            </form>
                                        </div>
                                    </div>
                                </template>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800 mb-4 text-3xl">
                                            🐑</div>
                                        <p class="font-medium text-gray-600 dark:text-gray-400 mb-1">Kamar ini masih kosong
                                        </p>
                                        <p class="text-sm text-gray-400 dark:text-gray-500 mb-4">Tambahkan ternak ke kamar
                                            ini</p>
                                        <button @click="modalTambah = true"
                                            class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                            Tambah Ternak Sekarang
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        {{-- Pesan jika filter kosong --}}
                        @if ($data_ternak->isNotEmpty())
                            @foreach (['sehat' => $countSehat, 'sakit' => $countSakit, 'hamil' => $countHamil] as $fStatus => $fCount)
                                <tr x-show="filterStatus === '{{ $fStatus }}' && {{ $fCount }} === 0"
                                    style="display: none;">
                                    <td colspan="5"
                                        class="py-10 text-center text-sm text-gray-500 dark:text-gray-400 italic">
                                        Tidak ada ternak dengan status "{{ ucfirst($fStatus) }}" di kamar ini.
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
