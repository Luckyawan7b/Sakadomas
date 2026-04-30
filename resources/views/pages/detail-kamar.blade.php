@extends('layouts.app')

@section('content')
    <div x-data="{
        filterStatus: 'semua',
        modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
        viewMode: 'grid'
    }">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 5000)"
                 class="mb-4 flex items-center justify-between rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800 border border-green-200">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 8000)"
                 class="mb-4 flex items-center justify-between rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800 border border-red-200">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ session('error') }}
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('kandang.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
                <div>
                    <h2 class="text-title-md2 font-bold text-black dark:text-white">Kandang {{ $kandang->nomor_kandang }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola kamar dan pantau isi ternak.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                {{-- View Toggle --}}
                <div class="inline-flex rounded-lg border border-gray-200 bg-gray-50 p-1 dark:border-gray-700 dark:bg-gray-800">
                    <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white shadow dark:bg-gray-700 text-brand-500' : 'text-gray-500'" class="px-3 py-1.5 text-xs font-medium rounded-md transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </button>
                    <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white shadow dark:bg-gray-700 text-brand-500' : 'text-gray-500'" class="px-3 py-1.5 text-xs font-medium rounded-md transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </button>
                </div>

                {{-- Filter --}}
                <select x-model="filterStatus"
                    class="appearance-none rounded-lg border border-gray-300 bg-white px-3 py-2 pr-8 text-sm font-medium text-gray-700 focus:border-brand-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    <option value="semua">Semua</option>
                    <option value="kosong">Kosong</option>
                    <option value="terisi">Terisi</option>
                    <option value="penuh">Penuh</option>
                    <option value="karantina">Karantina</option>
                </select>

                <button @click="modalTambah = true" type="button"
                    class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2.5 text-sm bg-green-500 text-white hover:bg-green-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Tambah Kamar
                </button>
            </div>
        </div>

        {{-- Summary Cards --}}
        @php
            $totalIsi = 0;
            $totalKapasitasKamar = 0;
            foreach($kamars as $k) {
                $totalIsi += $k->ternak_count ?? 0;
                $totalKapasitasKamar += $k->kapasitas;
            }
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $kamars->count() }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total Kamar</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-2xl font-bold text-brand-500">{{ $totalIsi }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total Ternak</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-2xl font-bold text-green-600">{{ $totalKapasitasKamar - $totalIsi }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Slot Kosong</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $kandang->kapasitas - $kamars->count() }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Slot Kamar Tersisa</p>
            </div>
        </div>

        {{-- MODAL TAMBAH KAMAR --}}
        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="modalTambah = false">
                <div class="no-scrollbar relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-6">
                        <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Tambah Kamar Baru</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Untuk Kandang {{ $kandang->nomor_kandang }}.</p>
                    </div>

                    @if ($errors->any() && !old('_method'))
                        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('kamar.store') }}" class="flex flex-col gap-5">
                        @csrf
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kandang</label>
                            <input type="hidden" name="id_kandang" value="{{ $kandang->id_kandang }}">
                            <input type="text" value="Kandang {{ $kandang->nomor_kandang }}" disabled
                                class="h-11 w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Kamar</label>
                                <input type="number" name="nomor_kamar" value="{{ old('nomor_kamar') }}" required min="1"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kapasitas (Ekor)</label>
                                <input type="number" name="kapasitas" value="{{ old('kapasitas') }}" required min="1"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                        </div>
                        <div class="flex items-center gap-3 mt-2 justify-end">
                            <button @click="modalTambah = false" type="button"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Batal</button>
                            <button type="submit" class="rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600">Simpan Kamar</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- ===== GRID VIEW ===== --}}
        <div x-show="viewMode === 'grid'">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @forelse ($kamars as $index => $kamar)
                    @php
                        $jmlTernak = $kamar->ternak_count ?? 0;
                        $jmlSakit = $kamar->ternak_sakit_count ?? 0;
                        $kapasitas = $kamar->kapasitas;

                        if ($jmlTernak > 0 && $jmlTernak == $jmlSakit) {
                            $realStatus = 'karantina';
                            $cardBg = 'border-amber-300 bg-amber-50 dark:border-amber-700 dark:bg-amber-900/10';
                            $dotColor = 'bg-amber-500';
                            $label = 'Karantina';
                            $labelColor = 'text-amber-700 dark:text-amber-400';
                        } elseif ($jmlTernak == 0) {
                            $realStatus = 'kosong';
                            $cardBg = 'border-green-200 bg-green-50 dark:border-green-800/50 dark:bg-green-900/10';
                            $dotColor = 'bg-green-500';
                            $label = 'Kosong';
                            $labelColor = 'text-green-700 dark:text-green-400';
                        } elseif ($jmlTernak >= $kapasitas) {
                            $realStatus = 'penuh';
                            $cardBg = 'border-red-300 bg-red-50 dark:border-red-700 dark:bg-red-900/10';
                            $dotColor = 'bg-red-500 animate-pulse';
                            $label = 'Penuh';
                            $labelColor = 'text-red-700 dark:text-red-400';
                        } else {
                            $realStatus = 'terisi';
                            $cardBg = 'border-blue-200 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/10';
                            $dotColor = 'bg-blue-500';
                            $label = 'Terisi';
                            $labelColor = 'text-blue-700 dark:text-blue-400';
                        }

                        $persen = $kapasitas > 0 ? round(($jmlTernak / $kapasitas) * 100) : 0;
                    @endphp

                    <div x-data="{
                        modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_kamar_edit') == $kamar->id_kamar ? 'true' : 'false' }},
                        modalHapus: false,
                        statusKamar: '{{ $realStatus }}'
                    }"
                    x-show="filterStatus === 'semua' || filterStatus === statusKamar"
                    x-transition
                    class="rounded-xl border {{ $cardBg }} p-4 flex flex-col gap-3 transition hover:shadow-md cursor-pointer group">

                        {{-- Header --}}
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Kamar</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $kamar->nomor_kamar }}</p>
                            </div>
                            <span class="flex items-center gap-1 text-[10px] font-semibold {{ $labelColor }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $dotColor }}"></span>
                                {{ $label }}
                            </span>
                        </div>

                        {{-- Occupancy visual --}}
                        <div>
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                <span>{{ $jmlTernak }} / {{ $kapasitas }} Ekor</span>
                                <span>{{ $persen }}%</span>
                            </div>
                            <div class="h-1.5 w-full rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                <div class="{{ $realStatus === 'penuh' ? 'bg-red-500' : ($realStatus === 'karantina' ? 'bg-amber-500' : 'bg-brand-500') }} h-1.5 rounded-full transition-all"
                                    style="width: {{ min($persen, 100) }}%"></div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-1.5 pt-3 sm:pt-1 border-t border-black/5 dark:border-white/5 mt-1">
                            <a href="{{ route('kamar.ternak', ['id_kandang' => $kandang->id_kandang, 'id_kamar' => $kamar->id_kamar]) }}"
                                class="flex-1 text-center rounded-lg sm:rounded-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 py-2 sm:py-1.5 text-sm sm:text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                Lihat
                            </a>
                            <div class="flex items-center gap-2 sm:gap-1.5">
                                <button @click.stop="modalEdit = true" type="button"
                                    class="flex-1 sm:flex-none rounded-lg sm:rounded-md bg-amber-100 dark:bg-amber-500/10 py-2 sm:py-1.5 px-3 sm:px-2.5 text-sm sm:text-xs font-medium text-amber-700 dark:text-amber-400 hover:bg-amber-200 dark:hover:bg-amber-500/20 transition">
                                    Edit
                                </button>
                                <button @click.stop="modalHapus = true" type="button"
                                    class="flex-1 sm:flex-none rounded-lg sm:rounded-md bg-red-100 dark:bg-red-500/10 py-2 sm:py-1.5 px-3 sm:px-2.5 text-sm sm:text-xs font-medium text-red-700 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-500/20 transition">
                                    Hapus
                                </button>
                            </div>
                        </div>

                        {{-- MODAL EDIT --}}
                        <template x-teleport="body">
                            <div x-show="modalEdit" style="display: none;"
                                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                                @click.self="modalEdit = false">
                                <div class="relative w-full max-w-[500px] rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
                                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100">
                                    <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Edit Kamar {{ $kamar->nomor_kamar }}</h4>

                                    <form method="POST" action="{{ route('kamar.update', $kamar->id_kamar) }}" class="flex flex-col gap-5 mt-6">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="id_kamar_edit" value="{{ $kamar->id_kamar }}">

                                        @if ($errors->any() && old('_method') === 'PUT' && old('id_kamar_edit') == $kamar->id_kamar)
                                            <div class="mb-2 rounded-lg bg-red-50 p-3 text-sm text-red-800 border border-red-200">{{ $errors->first() }}</div>
                                        @endif

                                        <div>
                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kandang</label>
                                            <select name="id_kandang" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                                @foreach($data_kandang as $k)
                                                    <option value="{{ $k->id_kandang }}" {{ $k->id_kandang == $kamar->id_kandang ? 'selected' : '' }}>Kandang {{ $k->nomor_kandang }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Kamar</label>
                                                <input type="number" name="nomor_kamar" value="{{ $kamar->nomor_kamar }}" required min="1"
                                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                            </div>
                                            <div>
                                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kapasitas (Ekor)</label>
                                                <input type="number" name="kapasitas" value="{{ $kamar->kapasitas }}" required min="{{ $jmlTernak ?: 1 }}"
                                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Khusus</label>
                                            <select name="status" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                                <option value="aktif" {{ $realStatus !== 'karantina' ? 'selected' : '' }}>Aktif (Sistem otomatis)</option>
                                                <option value="karantina" {{ $realStatus === 'karantina' ? 'selected' : '' }}>Karantina (Semua ternak sakit)</option>
                                            </select>
                                        </div>
                                        <div class="flex items-center gap-3 mt-2 justify-end">
                                            <button @click="modalEdit = false" type="button"
                                                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Batal</button>
                                            <button type="submit" class="rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-amber-600">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </template>

                        {{-- MODAL HAPUS --}}
                        <template x-teleport="body">
                            <div x-show="modalHapus" style="display: none;"
                                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                                @click.self="modalHapus = false">
                                <div class="relative w-full max-w-[400px] rounded-3xl bg-white p-6 dark:bg-gray-900 text-center"
                                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100">
                                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-500">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    <h4 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">Hapus Kamar {{ $kamar->nomor_kamar }}?</h4>
                                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                                        @if($jmlTernak > 0)
                                            <span class="text-red-600 font-medium">⚠️ Masih ada {{ $jmlTernak }} ternak di dalam kamar ini!</span><br>
                                            Pindahkan ternak terlebih dahulu sebelum menghapus.
                                        @else
                                            Yakin ingin menghapus Kamar {{ $kamar->nomor_kamar }}?
                                        @endif
                                    </p>
                                    <form method="POST" action="{{ route('kamar.delete', $kamar->id_kamar) }}" class="flex justify-center gap-3">
                                        @csrf
                                        @method('DELETE')
                                        <button @click="modalHapus = false" type="button"
                                            class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Batal</button>
                                        @if($jmlTernak === 0)
                                            <button type="submit" class="rounded-lg bg-red-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-red-600">Ya, Hapus!</button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </template>
                    </div>
                @empty
                    <div class="col-span-full rounded-xl border border-dashed border-gray-300 dark:border-gray-700 py-12 text-center">
                        <p class="text-gray-500 dark:text-gray-400 mb-2">Belum ada kamar di kandang ini.</p>
                        <button @click="modalTambah = true" class="text-sm text-brand-500 hover:text-brand-600 font-medium">+ Tambah Kamar</button>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ===== LIST VIEW ===== --}}
        <div x-show="viewMode === 'list'" x-cloak>
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
                <div class="max-w-full overflow-x-auto">
                    <table class="w-full table-auto min-w-[700px]">
                        <thead>
                            <tr class="bg-gray-50 text-left dark:bg-gray-800 border-b border-gray-200 dark:border-gray-800">
                                <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm">No</th>
                                <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm">Kamar</th>
                                <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm text-center">Isi / Kapasitas</th>
                                <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm w-48">Progress</th>
                                <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm text-center">Status</th>
                                <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm text-center">Aksi</th>
                            </tr>
                        </thead>
                    <tbody>
                        @forelse ($kamars as $index => $kamar)
                            @php
                                $jmlTernak = $kamar->ternak_count ?? 0;
                                $jmlSakit = $kamar->ternak_sakit_count ?? 0;
                                $kapasitas = $kamar->kapasitas;
                                if ($jmlTernak > 0 && $jmlTernak == $jmlSakit) { $realStatus = 'karantina'; }
                                elseif ($jmlTernak == 0) { $realStatus = 'kosong'; }
                                elseif ($jmlTernak >= $kapasitas) { $realStatus = 'penuh'; }
                                else { $realStatus = 'terisi'; }
                                $persen = $kapasitas > 0 ? round(($jmlTernak / $kapasitas) * 100) : 0;
                            @endphp
                            <tr x-data="{ statusKamar: '{{ $realStatus }}' }"
                                x-show="filterStatus === 'semua' || filterStatus === statusKamar"
                                class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="py-3.5 px-5 text-gray-500">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-5 font-medium text-gray-800 dark:text-white">Kamar {{ $kamar->nomor_kamar }}</td>
                                <td class="py-3.5 px-5 text-center text-sm">
                                    <span class="font-bold {{ $jmlTernak >= $kapasitas ? 'text-red-500' : 'text-brand-500' }}">{{ $jmlTernak }}</span>
                                    <span class="text-gray-400">/ {{ $kapasitas }} Ekor</span>
                                </td>
                                <td class="py-3.5 px-5">
                                    <div class="h-2 w-full rounded-full bg-gray-100 dark:bg-gray-800">
                                        <div class="{{ $realStatus === 'penuh' ? 'bg-red-500' : 'bg-brand-500' }} h-2 rounded-full" style="width: {{ min($persen, 100) }}%"></div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-5 text-center">
                                    @if($realStatus === 'kosong')<span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">Kosong</span>
                                    @elseif($realStatus === 'terisi')<span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">Terisi</span>
                                    @elseif($realStatus === 'penuh')<span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700">Penuh</span>
                                    @else<span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">Karantina</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('kamar.ternak', ['id_kandang' => $kandang->id_kandang, 'id_kamar' => $kamar->id_kamar]) }}"
                                            class="inline-flex items-center gap-1 rounded-lg bg-brand-500 px-3 py-2 text-xs font-medium text-white hover:bg-brand-600 transition">Detail</a>
                                        <button @click="modalEdit = true" type="button"
                                            class="inline-flex items-center rounded-lg bg-amber-100 dark:bg-amber-500/10 px-3 py-2 text-xs font-medium text-amber-700 dark:text-amber-400 hover:bg-amber-200 transition">Edit</button>
                                        <button @click="modalHapus = true" type="button"
                                            class="inline-flex items-center rounded-lg bg-red-100 dark:bg-red-500/10 px-3 py-2 text-xs font-medium text-red-700 dark:text-red-400 hover:bg-red-200 transition">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-10 text-center text-gray-500">Belum ada kamar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>

    </div>
@endsection
