@extends('layouts.app')

@section('content')
    {{-- BUNGKUSAN UTAMA MODAL TAMBAH & FILTER --}}
    <div x-data="{
        modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
        filterStatus: 'semua'
    }">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Manajemen Kamar
            </h2>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Dropdown Filter --}}
                <div class="relative">
                    <select x-model="filterStatus"
                        class="appearance-none rounded-lg border border-gray-300 bg-white px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        <option value="semua">Semua Status</option>
                        <option value="kosong">Kosong</option>
                        <option value="terisi">Terisi</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                {{-- Tombol Tambah Data --}}
                <button @click="modalTambah = true" type="button"
                    class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-green-500 text-white shadow-theme-xs hover:bg-green-600">
                    <svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4.5v15m7.5-7.5h-15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Tambah Kamar
                </button>
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
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pilih kandang dan tentukan nomor kamar.</p>
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
                                <option value="" disabled selected>-- Pilih Kandang --</option>
                                @foreach($data_kandang as $kandang)
                                    <option value="{{ $kandang->id_kandang }}" {{ old('id_kandang') == $kandang->id_kandang ? 'selected' : '' }}>
                                        Kandang {{ $kandang->nomor_kandang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Kamar</label>
                            <input type="number" name="nomor_kamar" value="{{ old('nomor_kamar') }}" required min="1" placeholder="Contoh: 1"
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
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

        @foreach ($data_kandang as $kandang)
            @php
                $kamars = $kamar_grouped[$kandang->id_kandang] ?? collect();

                // Hitung jumlah masing-masing status menggunakan PHP (Blade)
                $kosongCount = 0;
                $terisiCount = 0;
                foreach($kamars as $k) {
                    if(strtolower(trim($k->status)) == 'kosong') {
                        $kosongCount++;
                    } else {
                        $terisiCount++;
                    }
                }
            @endphp

            <div class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900 mb-8">
                <div class="py-4 px-4 md:px-6 xl:px-7.5 bg-gray-50 dark:bg-gray-800/50 flex justify-between items-center border-b border-gray-200 dark:border-gray-800">
                    <h4 class="text-lg font-semibold text-black dark:text-white">
                        Kandang Nomor: {{ $kandang->nomor_kandang }}
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
                                <th class="py-3 px-4 font-medium text-black dark:text-white xl:px-6 text-center">Status</th>
                                <th class="py-3 px-4 font-medium text-black dark:text-white xl:px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kamars as $index => $kamar)
                                <tr x-data="{
                                    modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_kamar_edit') == $kamar->id_kamar ? 'true' : 'false' }},
                                    modalHapus: false,
                                    statusKamar: '{{ strtolower(trim($kamar->status)) }}'
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
                                    <td class="py-4 px-4 xl:px-6 text-center">
                                        @if(strtolower(trim($kamar->status)) == 'kosong')
                                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">
                                                Kosong
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-sm font-medium text-orange-700 dark:bg-orange-500/10 dark:text-orange-400">
                                                Terisi
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 xl:px-6 text-center">
                                        <div class="flex items-center justify-center space-x-3.5">
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
                                                        <select name="id_kandang" required
                                                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                            @foreach($data_kandang as $k)
                                                                <option value="{{ $k->id_kandang }}" {{ $k->id_kandang == $kamar->id_kandang ? 'selected' : '' }}>
                                                                    Kandang {{ $k->nomor_kandang }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Kamar</label>
                                                        <input type="number" name="nomor_kamar" value="{{ $kamar->nomor_kamar }}" required min="1"
                                                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                    </div>

                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status</label>
                                                        <select name="status" required
                                                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                            <option value="kosong" {{ strtolower(trim($kamar->status)) == 'kosong' ? 'selected' : '' }}>Kosong</option>
                                                            <option value="terisi" {{ strtolower(trim($kamar->status)) == 'terisi' ? 'selected' : '' }}>Terisi</option>
                                                        </select>
                                                    </div>

                                                    <div class="flex items-center gap-3 mt-4 justify-end">
                                                        <button @click="modalEdit = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:w-auto">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="flex w-full justify-center rounded-lg bg-yellow-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-yellow-600 sm:w-auto">
                                                            Simpan Perubahan
                                                        </button>
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

                                                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-500 dark:bg-red-500/20">
                                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                </div>

                                                <h4 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">Hapus Kamar?</h4>
                                                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus <strong>Kamar {{ $kamar->nomor_kamar }}</strong> pada <strong>Kandang {{ $kandang->nomor_kandang }}</strong>?</p>

                                                <form method="POST" action="{{ route('kamar.delete', $kamar->id_kamar) }}" class="flex justify-center gap-3">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button @click="modalHapus = false" type="button" class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                                        Batal
                                                    </button>
                                                    <button type="submit" class="rounded-lg bg-red-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-red-600">
                                                        Ya, Hapus!
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </template>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 px-4 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada kamar di kandang ini.
                                    </td>
                                </tr>
                            @endforelse

                            {{-- PESAN JIKA HASIL FILTER KOSONG (Dikontrol oleh Alpine.js) --}}
                            @if($kamars->isNotEmpty())
                                <tr x-show="filterStatus === 'kosong' && {{ $kosongCount }} === 0" style="display: none;">
                                    <td colspan="4" class="py-6 px-4 text-center text-gray-500 dark:text-gray-400 italic">
                                        Tidak ada kamar kosong di kandang ini.
                                    </td>
                                </tr>
                                <tr x-show="filterStatus === 'terisi' && {{ $terisiCount }} === 0" style="display: none;">
                                    <td colspan="4" class="py-6 px-4 text-center text-gray-500 dark:text-gray-400 italic">
                                        Tidak ada kamar terisi di kandang ini.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@endsection
