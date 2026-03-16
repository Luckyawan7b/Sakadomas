@extends('layouts.app')

@section('content')
    {{-- 1. Tambahkan modalTambah ke dalam state Alpine.js --}}
    <div x-data="{
        filterStatus: 'semua',
        modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }}
    }">

        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                {{-- Tombol Kembali --}}
                <a href="{{ route('kandang.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
                <h2 class="text-title-md2 font-bold text-black dark:text-white">
                    Detail Kandang: {{ $kandang->nomor_kandang }}
                </h2>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Dropdown Filter --}}
                <div class="relative">
                    <select x-model="filterStatus"
                        class="appearance-none rounded-lg border border-gray-300 bg-white px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        <option value="semua">Semua Status</option>
                        <option value="kosong">Kosong</option>
                        <option value="terisi">Terisi</option>
                        <option value="penuh">Penuh</option>
                        <option value="karantina">Karantina</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                {{-- 2. Tombol Tambah Kamar --}}
                <button @click="modalTambah = true" type="button"
                    class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-green-500 text-white shadow-theme-xs hover:bg-green-600">
                    <svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4.5v15m7.5-7.5h-15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Tambah Kamar
                </button>
            </div>
        </div>

        {{-- 3. MODAL TAMBAH KAMAR --}}
        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="modalTambah = false">
                <div class="no-scrollbar relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-6">
                        <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Tambah Kamar Baru</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tambahkan kamar baru untuk Kandang {{ $kandang->nomor_kandang }}.</p>
                    </div>

                    @if ($errors->any() && !old('_method'))
                        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('kamar.store') }}" class="flex flex-col gap-5">
                        @csrf

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Kandang</label>
                            <select name="id_kandang" required
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                @foreach($data_kandang as $k)
                                    {{-- Cerdas: Otomatis memilih kandang yang sedang dilihat saat ini --}}
                                    <option value="{{ $k->id_kandang }}" {{ (old('id_kandang') ?? $kandang->id_kandang) == $k->id_kandang ? 'selected' : '' }}>
                                        Kandang {{ $k->nomor_kandang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Kamar</label>
                                <input type="number" name="nomor_kamar" value="{{ old('nomor_kamar') }}" required min="1" placeholder="Contoh: 1"
                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kapasitas Maks.</label>
                                <input type="number" name="kapasitas" value="{{ old('kapasitas') }}" required min="1" placeholder="Contoh: 5"
                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mt-4 justify-end">
                            <button @click="modalTambah = false" type="button"
                                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:w-auto">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex w-full justify-center rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600 sm:w-auto">
                                Simpan Kamar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        @php
            // Hitung statistik untuk pesan kosong Alpine.js
            $counts = ['kosong' => 0, 'terisi' => 0, 'penuh' => 0, 'karantina' => 0];
            foreach($kamars as $k) {
                $jmlTernak = $k->ternak_count ?? 0;
                $dbStatus = strtolower(trim($k->status));

                if ($dbStatus == 'karantina') {
                    $counts['karantina']++;
                } elseif ($jmlTernak == 0) {
                    $counts['kosong']++;
                } elseif ($jmlTernak >= $k->kapasitas) {
                    $counts['penuh']++;
                } else {
                    $counts['terisi']++;
                }
            }
        @endphp

        <div class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900 mb-8">
            <div class="py-4 px-4 md:px-6 xl:px-7.5 bg-gray-50 dark:bg-gray-800/50 flex justify-between items-center border-b border-gray-200 dark:border-gray-800">
                <h4 class="text-lg font-semibold text-black dark:text-white">
                    Daftar Kamar
                </h4>
                <span class="text-sm font-medium bg-brand-100 text-brand-500 px-3 py-1 rounded-full dark:bg-brand-500/10 dark:text-brand-400">
                    Total Kamar: {{ $kamars->count() }} / {{ $kandang->kapasitas }} Kapasitas
                </span>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="text-left dark:bg-gray-800">
                            <th class="py-3 px-4 font-medium text-black dark:text-white xl:px-6 w-16">No</th>
                            <th class="py-3 px-4 font-medium text-black dark:text-white xl:px-6">Kamar</th>
                            <th class="py-3 px-4 font-medium text-black dark:text-white xl:px-6 text-center">Isi / Kapasitas</th>
                            <th class="py-3 px-4 font-medium text-black dark:text-white xl:px-6 text-center">Status</th>
                            <th class="py-3 px-4 font-medium text-black dark:text-white xl:px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kamars as $index => $kamar)
                            @php
                                $jmlTernak = $kamar->ternak_count ?? 0;
                                $kapasitas = $kamar->kapasitas;
                                $dbStatus = strtolower(trim($kamar->status));

                                if ($dbStatus == 'karantina') {
                                    $realStatus = 'karantina';
                                } elseif ($jmlTernak == 0) {
                                    $realStatus = 'kosong';
                                } elseif ($jmlTernak >= $kapasitas) {
                                    $realStatus = 'penuh';
                                } else {
                                    $realStatus = 'terisi';
                                }
                            @endphp

                            <tr x-data="{
                                modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_kamar_edit') == $kamar->id_kamar ? 'true' : 'false' }},
                                modalHapus: false,
                                statusKamar: '{{ $realStatus }}'
                            }"
                            x-show="filterStatus === 'semua' || filterStatus === statusKamar"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="border-b border-gray-200 dark:border-gray-800 last:border-0">

                                <td class="py-4 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-4 px-4 xl:px-6 text-gray-800 dark:text-gray-300 font-medium">
                                    Kamar {{ $kamar->nomor_kamar }}
                                </td>
                                <td class="py-4 px-4 xl:px-6 text-center text-gray-800 dark:text-gray-300">
                                    <span class="font-bold {{ $jmlTernak >= $kapasitas ? 'text-red-500' : 'text-brand-500' }}">{{ $jmlTernak }}</span> / {{ $kapasitas }} Ekor
                                </td>
                                <td class="py-4 px-4 xl:px-6 text-center">
                                    @if($realStatus == 'kosong')
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">Kosong</span>
                                    @elseif($realStatus == 'terisi')
                                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">Terisi</span>
                                    @elseif($realStatus == 'penuh')
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-700 dark:bg-red-500/10 dark:text-red-400">Penuh</span>
                                    @elseif($realStatus == 'karantina')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400">Karantina</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 xl:px-6 text-center">
                                    <div class="flex items-center justify-center space-x-3.5">
                                        <a href="{{ route('kamar.ternak', ['id_kandang' => $kandang->id_kandang, 'id_kamar' => $kamar->id_kamar]) }}" class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600">
                                            Lihat
                                        </a>
                                        <button @click="modalEdit = true" type="button" class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-yellow-500 text-white shadow-theme-xs hover:bg-yellow-600">
                                            Edit
                                        </button>
                                        <button @click="modalHapus = true" type="button" class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-red-500 text-white shadow-theme-xs hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </div>
                                </td>

                                {{-- MODAL EDIT KAMAR --}}
                                <template x-teleport="body">
                                    <div x-show="modalEdit" style="display: none;"
                                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                                        @click.self="modalEdit = false">
                                        <div class="no-scrollbar relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100">

                                            <div class="mb-6">
                                                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Edit Kamar</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui detail untuk <strong>Kamar {{ $kamar->nomor_kamar }}</strong>.</p>
                                            </div>

                                            <form method="POST" action="{{ route('kamar.update', $kamar->id_kamar) }}" class="flex flex-col gap-5">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="id_kamar_edit" value="{{ $kamar->id_kamar }}">

                                                @if ($errors->any() && old('_method') === 'PUT' && old('id_kamar_edit') == $kamar->id_kamar)
                                                    <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                                                        {{ $errors->first() }}
                                                    </div>
                                                @endif

                                                <div>
                                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pindah Kandang</label>
                                                    <select name="id_kandang" required class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                        @foreach($data_kandang as $k)
                                                            <option value="{{ $k->id_kandang }}" {{ $k->id_kandang == $kamar->id_kandang ? 'selected' : '' }}>
                                                                Kandang {{ $k->nomor_kandang }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Kamar</label>
                                                        <input type="number" name="nomor_kamar" value="{{ $kamar->nomor_kamar }}" required min="1"
                                                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                    </div>
                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kapasitas Maksimal</label>
                                                        <input type="number" name="kapasitas" value="{{ $kamar->kapasitas }}" required min="1"
                                                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pembaruan Status</label>
                                                    <select name="status" required class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                        <option value="kosong" {{ $dbStatus == 'kosong' ? 'selected' : '' }}>Sistem Otomatis (Kosong)</option>
                                                        <option value="terisi" {{ $dbStatus == 'terisi' ? 'selected' : '' }}>Sistem Otomatis (Terisi)</option>
                                                        <option value="penuh" {{ $dbStatus == 'penuh' ? 'selected' : '' }}>Sistem Otomatis (Penuh)</option>
                                                        <option value="karantina" {{ $dbStatus == 'karantina' ? 'selected' : '' }}>Karantina</option>
                                                    </select>
                                                    <p class="mt-1 text-xs text-gray-500">Pilih Karantina jika kamar tidak boleh diisi. Jika tidak, biarkan sistem menghitung otomatis.</p>
                                                </div>

                                                <div class="flex items-center gap-3 mt-4 justify-end">
                                                    <button @click="modalEdit = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:w-auto">Batal</button>
                                                    <button type="submit" class="flex w-full justify-center rounded-lg bg-yellow-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-yellow-600 sm:w-auto">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </template>

                                {{-- MODAL HAPUS KAMAR --}}
                                <template x-teleport="body">
                                    <div x-show="modalHapus" style="display: none;"
                                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                                        @click.self="modalHapus = false">
                                        <div class="relative w-full max-w-[400px] rounded-3xl bg-white p-6 dark:bg-gray-900 text-center"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100">
                                            <h4 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">Hapus Kamar?</h4>
                                            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus <strong>Kamar {{ $kamar->nomor_kamar }}</strong>?</p>

                                            <form method="POST" action="{{ route('kamar.delete', $kamar->id_kamar) }}" class="flex justify-center gap-3">
                                                @csrf
                                                @method('DELETE')
                                                <button @click="modalHapus = false" type="button" class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Batal</button>
                                                <button type="submit" class="rounded-lg bg-red-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-red-600">Ya, Hapus!</button>
                                            </form>
                                        </div>
                                    </div>
                                </template>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 px-4 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada kamar di kandang ini.
                                </td>
                            </tr>
                        @endforelse

                        {{-- PESAN JIKA HASIL FILTER KOSONG --}}
                        @if($kamars->isNotEmpty())
                            <tr x-show="filterStatus === 'kosong' && {{ $counts['kosong'] }} === 0" style="display: none;"><td colspan="5" class="py-6 px-4 text-center text-gray-500 italic">Tidak ada kamar kosong di kandang ini.</td></tr>
                            <tr x-show="filterStatus === 'terisi' && {{ $counts['terisi'] }} === 0" style="display: none;"><td colspan="5" class="py-6 px-4 text-center text-gray-500 italic">Tidak ada kamar terisi di kandang ini.</td></tr>
                            <tr x-show="filterStatus === 'penuh' && {{ $counts['penuh'] }} === 0" style="display: none;"><td colspan="5" class="py-6 px-4 text-center text-gray-500 italic">Tidak ada kamar penuh di kandang ini.</td></tr>
                            <tr x-show="filterStatus === 'karantina' && {{ $counts['karantina'] }} === 0" style="display: none;"><td colspan="5" class="py-6 px-4 text-center text-gray-500 italic">Tidak ada kamar karantina di kandang ini.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
