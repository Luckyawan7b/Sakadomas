@extends('layouts.app')

@section('content')
    {{-- BUNGKUSAN UTAMA UNTUK MODAL TAMBAH --}}
    <div x-data="{modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }} }">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Data Kandang
            </h2>

            {{-- Tombol Tambah Data --}}
            <button @click="modalTambah = true" type="button"
                class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-green-500 text-white shadow-theme-xs hover:bg-green-600">
                <svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4.5v15m7.5-7.5h-15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Tambah Kandang
            </button>
        </div>

        {{-- MODAL TAMBAH KANDANG --}}
        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="modalTambah = false">
                <div class="no-scrollbar relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-6">
                        <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Tambah Kandang Baru</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Masukkan detail kandang di bawah ini.</p>
                    </div>

                    {{-- Alert jika ada error validasi (khususnya nomor kandang duplicate) --}}
                    @if ($errors->any() && !old('_method'))
                        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('kandang.store') }}" class="flex flex-col gap-5">
                        @csrf

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Kandang</label>
                            <input type="number" name="nomor_kandang" required placeholder="Contoh: 1"
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kapasitas (Kamar)</label>
                            <input type="number" name="kapasitas" required min="1" placeholder="Contoh: 50"
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        </div>

                        <div class="flex items-center gap-3 mt-4 justify-end">
                            <button @click="modalTambah = false" type="button"
                                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:w-auto">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex w-full justify-center rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600 sm:w-auto">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <div class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">
        <div class="py-6 px-4 md:px-6 xl:px-7.5">
            <h4 class="text-xl font-semibold text-black dark:text-white">Daftar Kandang</h4>
        </div>

        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 text-left dark:bg-gray-800">
                        <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6">No</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6">Nomor Kandang</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6">Kapasitas (Kamar)</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data_kandang as $index => $kandang)
                        {{-- BUNGKUSAN TIAP BARIS UNTUK MODAL EDIT & HAPUS --}}
                        <tr x-data="{ modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_kandang_edit') == $kandang->id_kandang ? 'true' : 'false' }},
                             modalHapus: false }"
                             class="border-b border-gray-200 dark:border-gray-800">
                            <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300 font-medium">
                                Kandang {{ $kandang->nomor_kandang }}
                            </td>
                            <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                {{ $kandang->kapasitas }} Kamar
                            </td>
                            <td class="py-5 px-4 xl:px-6 text-center">
                                <div class="flex items-center justify-center space-x-3.5">

                                    {{-- Tombol Detail Kandang --}}
                                    <a href="{{ route('kandang.kamar', $kandang->id_kandang) }}"
                                        class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </a>

                                    {{-- Tombol Edit --}}
                                    <button @click="modalEdit = true" type="button"
                                        class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-yellow-500 text-white shadow-theme-xs hover:bg-yellow-600">
                                        Edit
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <button @click="modalHapus = true" type="button"
                                        class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-red-500 text-white shadow-theme-xs hover:bg-red-600">
                                        Hapus
                                    </button>
                                </div>
                            </td>

                            {{-- MODAL EDIT KANDANG --}}
                            <template x-teleport="body">
                                <div x-show="modalEdit" style="display: none;"
                                    class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                                    @click.self="modalEdit = false">
                                    <div class="no-scrollbar relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100">

                                        <div class="mb-6">
                                            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Edit Kandang</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui detail untuk <strong>Kandang {{ $kandang->nomor_kandang }}</strong>.</p>
                                        </div>

                                        <form method="POST" action="{{ route('kandang.update', $kandang->id_kandang) }}" class="flex flex-col gap-5">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id_kandang_edit" value="{{ $kandang->id_kandang }}">
                                            @if ($errors->any() && old('_method') === 'PUT' && old('id_kandang_edit') == $kandang->id_kandang)
                                                <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                                                    {{ $errors->first() }}
                                                </div>
                                            @endif

                                            <div>
                                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Kandang</label>
                                                <input type="number" name="nomor_kandang" value="{{ $kandang->nomor_kandang }}" required
                                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                            </div>

                                            <div>
                                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kapasitas (Kamar)</label>
                                                <input type="number" name="kapasitas" value="{{ $kandang->kapasitas }}" required min="1"
                                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                            </div>

                                            <div class="flex items-center gap-3 mt-4 justify-end">
                                                <button @click="modalEdit = false" type="button"
                                                    class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:w-auto">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                    class="flex w-full justify-center rounded-lg bg-yellow-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-yellow-600 sm:w-auto">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </template>

                            {{-- MODAL HAPUS KANDANG --}}
                            <template x-teleport="body">
                                <div x-show="modalHapus" style="display: none;"
                                    class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                                    @click.self="modalHapus = false">
                                    <div class="relative w-full max-w-[400px] rounded-3xl bg-white p-6 dark:bg-gray-900 text-center"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100">

                                        {{-- Ikon Peringatan --}}
                                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-500 dark:bg-red-500/20">
                                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                        </div>

                                        <h4 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">Hapus Data?</h4>
                                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus data <strong>Kandang {{ $kandang->nomor_kandang }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>

                                        <form method="POST" action="{{ route('kandang.delete', $kandang->id_kandang) }}" class="flex justify-center gap-3">
                                            @csrf
                                            @method('DELETE')

                                            <button @click="modalHapus = false" type="button"
                                                class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                                Batal
                                            </button>
                                            <button type="submit"
                                                class="rounded-lg bg-red-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-red-600">
                                                Ya, Hapus!
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </template>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-5 px-4 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data kandang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
