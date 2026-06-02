@extends('layouts.app')

@section('content')

<div x-data="ajaxTable('{{ route('akun.index') }}')">
    {{-- x-data khusus untuk Modal Tambah Akun --}}
    <div x-data='{
        modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
        selectedKecamatanTambah: "",
        selectedDesaTambah: "",
        semuaDesa: @json($desa ?? []),
        get filteredDesaTambah() {
            if (!this.selectedKecamatanTambah) return [];
            return this.semuaDesa.filter(d => d.id_kecamatan == this.selectedKecamatanTambah);
        }
    }'>
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Data Akun User
            </h2>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Form Search --}}
                <form id="filter-form" @submit.prevent="fetchData" class="relative w-full sm:w-auto">
                    <input type="text" name="q" x-model="searchQuery" @input.debounce.500ms="fetchData" placeholder="Cari nama, email, hp..."
                        class="dark:bg-gray-900 h-11 w-full sm:w-64 rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">

                    <button type="submit" class="absolute right-0 top-0 flex h-11 w-11 items-center justify-center text-gray-500 hover:text-brand-500 dark:text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    
                    {{-- Tombol reset pencarian kecil di dalam input (Muncul jika sedang mencari) --}}
                    <button type="button" x-show="searchQuery.length > 0" @click="searchQuery = ''; fetchData()" class="absolute right-10 top-0 flex h-11 w-8 items-center justify-center text-red-400 hover:text-red-600" title="Reset Pencarian">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </form>

                {{-- Tombol Tambah Akun --}}
                <button @click="modalTambah = true" type="button"
                    class="inline-flex w-full sm:w-auto items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-green-500 text-white shadow-theme-xs hover:bg-green-600">
                    <svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4.5v15m7.5-7.5h-15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Tambah Akun
                </button>
            </div>
        </div>

        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="modalTambah = false">
                <div class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="px-2 pr-14">
                        <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Tambah Akun Baru</h4>
                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">Masukkan detail untuk mendaftarkan pengguna baru.</p>
                    </div>

                    @if ($errors->any() && !old('_method'))
                        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('akun.store') }}" class="flex flex-col">
                        @csrf
                        <div class="custom-scrollbar overflow-y-auto p-2">
                            <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Lengkap</label>
                                    <input type="text" name="nama" value="{{ old('nama') }}" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Username</label>
                                    <input type="text" name="username" value="{{ old('username') }}" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password</label>
                                    <input type="password" name="password" required minlength="6" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">
                                </div>

                                <div class="col-span-1 lg:col-span-2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">No Handphone</label>
                                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kecamatan</label>
                                    <select x-model="selectedKecamatanTambah" @change="selectedDesaTambah = ''" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                        <option value="" disabled class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Kecamatan</option>
                                        @foreach ($kecamatan as $kec)
                                            <option value="{{ $kec->id_kecamatan }}" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">{{ $kec->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Desa</label>
                                    <select name="id_desa" x-model="selectedDesaTambah" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                        <option value="" disabled class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Desa</option>
                                        <template x-for="d in filteredDesaTambah" :key="d.id_desa">
                                            <option :value="d.id_desa" x-text="d.nama_desa" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="col-span-1 lg:col-span-2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat Lengkap</label>
                                    <input type="text" name="alamat" value="{{ old('alamat') }}" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                            <button @click="modalTambah = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">Batal</button>
                            <button type="submit" class="flex w-full justify-center rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600 sm:w-auto">Tambah Akun</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <div class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900 relative">
        <div class="py-6 px-4 md:px-6 xl:px-7.5">
            <h4 class="text-xl font-semibold text-black dark:text-white">
                Daftar Pengguna <span class="text-sm font-normal text-gray-500" x-text="`(${totalData} Data)`"></span>
            </h4>
        </div>

        {{-- Loading Overlay Spinner --}}
        <div x-show="isFetching" class="absolute inset-0 z-10 flex items-center justify-center bg-white/50 backdrop-blur-sm dark:bg-gray-900/50" style="display: none;">
            <svg class="h-8 w-8 animate-spin text-brand-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>

        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 text-left dark:bg-gray-800">
                        <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6">No</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6">Nama</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6">Username</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6">Email / No HP</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(akun, index) in rows" :key="akun.id_akun">
                        <tr class="border-b border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                <span x-text="fromData + index"></span>
                            </td>
                            <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                <span class="font-medium text-brand-500" x-text="akun.nama"></span>
                            </td>
                            <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                <span x-text="akun.username"></span>
                            </td>
                            <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                <span x-text="akun.email || '-'"></span> <br>
                                <span class="text-sm text-gray-500" x-text="akun.no_hp"></span>
                            </td>
                            <td class="py-5 px-4 xl:px-6 text-center">
                                <div class="flex items-center justify-center space-x-3.5">
                                    {{-- Tombol Lihat --}}
                                    <button @click="openLihatModal(akun)" type="button"
                                        class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 disabled:bg-brand-300">
                                        Lihat
                                    </button>

                                    {{-- Tombol Edit --}}
                                    <button @click="openEditModal(akun)"
                                        class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-yellow-500 text-white shadow-theme-xs hover:bg-yellow-600 disabled:bg-brand-300"
                                        type="button">
                                        Edit
                                    </button>

                                    {{-- Tombol Reset Sandi --}}
                                    <button @click="openSandiModal(akun)" type="button"
                                        class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-gray-500 text-white shadow-theme-xs hover:bg-gray-600 disabled:bg-gray-300">
                                        Sandi
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    {{-- Tampilan saat data kosong --}}
                    <tr x-show="rows.length === 0" style="display: none;">
                        <td colspan="5" class="py-10 px-4 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-gray-500 dark:text-gray-400">Tidak ada data akun yang ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- AlpineJS Pagination Control --}}
        <div x-show="lastPage > 1" style="display: none;" class="flex flex-col sm:flex-row items-center justify-between border-t border-gray-200 bg-gray-50 px-4 py-4 dark:border-gray-800 dark:bg-gray-800/50 sm:px-6">
            <div class="hidden sm:block">
                <p class="text-sm text-gray-700 dark:text-gray-400">
                    Menampilkan <span class="font-medium" x-text="fromData"></span> sampai <span class="font-medium" x-text="toData"></span> dari <span class="font-medium" x-text="totalData"></span> data
                </p>
            </div>
            <div class="flex flex-1 justify-between sm:justify-end gap-2">
                <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                    class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Previous
                </button>
                <div class="hidden sm:flex gap-1">
                    <template x-for="page in paginationPages" :key="page">
                        <button @click="if(page !== '...') goToPage(page)"
                            :disabled="page === '...'"
                            :class="{
                                'bg-brand-500 text-white border-brand-500': page === currentPage,
                                'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700': page !== currentPage && page !== '...',
                                'bg-transparent text-gray-500 border-transparent cursor-default': page === '...'
                            }"
                            class="relative inline-flex items-center rounded-md border px-4 py-2 text-sm font-medium"
                            x-text="page">
                        </button>
                    </template>
                </div>
                <button @click="goToPage(currentPage + 1)" :disabled="currentPage === lastPage"
                    class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Next
                </button>
            </div>
        </div>

    </div>

    {{-- SHARED MODALS (Ditampilkan diluar perulangan tabel untuk performa) --}}
    
    {{-- 1. Modal Lihat --}}
    <template x-teleport="body">
        <div x-show="modalLihat" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm" @click.self="modalLihat = false">
            <div class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                <div class="px-2 pr-14">
                    <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Detail Akun</h4>
                </div>

                <div class="flex flex-col" x-show="editData">
                    <div class="custom-scrollbar overflow-y-auto p-2">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Lengkap</label>
                                <input type="text" :value="editData?.nama" readonly class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Username</label>
                                <input type="text" :value="editData?.username" readonly class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                                <input type="text" :value="editData?.email || '-'" readonly class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">No Handphone</label>
                                <input type="text" :value="editData?.no_hp" readonly class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kecamatan</label>
                                <input type="text" :value="editData?.desa?.kecamatan?.nama_kecamatan || '-'" readonly class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Desa</label>
                                <input type="text" :value="editData?.desa?.nama_desa || '-'" readonly class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90">
                            </div>
                            <div class="col-span-1 lg:col-span-2">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat</label>
                                <input type="text" :value="editData?.alamat" readonly class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90">
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <button @click="modalLihat = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- 2. Modal Edit --}}
    <template x-teleport="body">
        <div x-show="modalEdit" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm" @click.self="modalEdit = false">
            <div class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                <div class="px-2 pr-14">
                    <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Edit Data Akun</h4>
                </div>

                <form method="POST" :action="`/akun/${editData?.id_akun}`" class="flex flex-col">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_akun_edit" :value="editData?.id_akun">

                    <template x-if="modalEditError">
                        <div class="mb-4 mx-2 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">{{ $errors->first() }}</div>
                    </template>

                    <div class="custom-scrollbar overflow-y-auto p-2">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Lengkap</label>
                                <input type="text" name="nama" x-model="editData.nama" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Username</label>
                                <input type="text" name="username" x-model="editData.username" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                                <input type="email" name="email" x-model="editData.email" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">No Handphone</label>
                                <input type="text" name="no_hp" x-model="editData.no_hp" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kecamatan</label>
                                <select x-model="editSelectedKecamatan" @change="editSelectedDesa = ''" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                                    <option value="" disabled class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Kecamatan</option>
                                    <template x-for="kec in semuaKecamatan" :key="kec.id_kecamatan">
                                        <option :value="kec.id_kecamatan" x-text="kec.nama_kecamatan" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"></option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Desa</label>
                                <select name="id_desa" x-model="editSelectedDesa" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                                    <option value="" disabled class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Desa</option>
                                    <template x-for="d in editFilteredDesa" :key="d.id_desa">
                                        <option :value="d.id_desa" x-text="d.nama_desa" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="col-span-1 lg:col-span-2">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat</label>
                                <input type="text" name="alamat" x-model="editData.alamat" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <button @click="modalEdit = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-white/[0.03] sm:w-auto">Batal</button>
                        <button type="submit" class="flex w-full justify-center rounded-lg bg-yellow-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-yellow-600 sm:w-auto">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    {{-- 3. Modal Reset Sandi --}}
    <template x-teleport="body">
        <div x-show="modalSandi" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm" @click.self="modalSandi = false">
            <div class="no-scrollbar relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                <div class="px-2 pr-14 mb-6">
                    <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Ganti Password</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Atur ulang password untuk akun <strong x-text="editData?.nama"></strong>.</p>
                </div>

                <form method="POST" :action="`/akun/${editData?.id_akun}/reset-password`" class="flex flex-col gap-5 px-2">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_akun_sandi" :value="editData?.id_akun">

                    <template x-if="modalSandiError">
                        <div class="mb-1 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">{{ $errors->first() }}</div>
                    </template>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password Baru</label>
                        <input type="password" name="password_baru" required minlength="6" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Konfirmasi Password</label>
                        <input type="password" name="password_baru_confirmation" required minlength="6" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                    </div>

                    <div class="flex items-center gap-3 mt-4 lg:justify-end">
                        <button @click="modalSandi = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">Batal</button>
                        <button type="submit" class="flex w-full justify-center rounded-lg bg-gray-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-gray-700 sm:w-auto">Simpan Password</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
    function ajaxTable(baseUrl) {
        return {
            isFetching: false,
            abortController: null,
            searchQuery: "{{ request('q') }}",
            rows: @json($data_akun_json ?? []),
            currentPage: {{ $data_akun->currentPage() }},
            lastPage: {{ $data_akun->lastPage() }},
            totalData: {{ $data_akun->total() }},
            fromData: {{ $data_akun->firstItem() ?? 0 }},
            toData: {{ $data_akun->lastItem() ?? 0 }},
            
            // Modal States
            modalLihat: false,
            modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_akun_edit') && !old('password_baru') ? 'true' : 'false' }},
            modalEditError: {{ $errors->any() && old('_method') === 'PUT' && old('id_akun_edit') && !old('password_baru') ? 'true' : 'false' }},
            modalSandi: {{ $errors->any() && old('_method') === 'PUT' && old('id_akun_sandi') ? 'true' : 'false' }},
            modalSandiError: {{ $errors->any() && old('_method') === 'PUT' && old('id_akun_sandi') ? 'true' : 'false' }},
            
            editData: null,
            semuaKecamatan: @json($kecamatan ?? []),
            semuaDesa: @json($desa ?? []),
            editSelectedKecamatan: '',
            editSelectedDesa: '',

            get editFilteredDesa() {
                if (!this.editSelectedKecamatan) return [];
                return this.semuaDesa.filter(d => d.id_kecamatan == this.editSelectedKecamatan);
            },

            init() {
                // Saat ada error edit/sandi, cari datanya dan masukkan ke editData
                let oldEditId = "{{ old('id_akun_edit') ?? old('id_akun_sandi') }}";
                if (oldEditId) {
                    let acc = this.rows.find(r => r.id_akun == oldEditId);
                    if (acc) {
                        this.editData = JSON.parse(JSON.stringify(acc));
                        // Load old inputs if available
                        if ("{{ old('id_akun_edit') }}") {
                            this.editData.nama = "{{ old('nama') }}" || acc.nama;
                            this.editData.username = "{{ old('username') }}" || acc.username;
                            this.editData.email = "{{ old('email') }}" || acc.email;
                            this.editData.no_hp = "{{ old('no_hp') }}" || acc.no_hp;
                            this.editData.alamat = "{{ old('alamat') }}" || acc.alamat;
                            this.editSelectedKecamatan = acc.desa?.id_kecamatan || '';
                            this.editSelectedDesa = "{{ old('id_desa') }}" || acc.id_desa || '';
                        }
                    }
                }
            },

            async fetchData() {
                this.isFetching = true;
                
                if (this.abortController) {
                    this.abortController.abort();
                }
                this.abortController = new AbortController();

                try {
                    const form = document.getElementById('filter-form');
                    const params = new URLSearchParams(new FormData(form));
                    params.append('page', this.currentPage);

                    const url = `${baseUrl}?${params.toString()}`;
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        signal: this.abortController.signal
                    });

                    if (!response.ok) throw new Error('Network response was not ok');

                    const json = await response.json();
                    
                    this.rows = json.data;
                    this.currentPage = json.pagination.current_page;
                    this.lastPage = json.pagination.last_page;
                    this.totalData = json.pagination.total;
                    this.fromData = json.pagination.from || 0;
                    this.toData = json.pagination.to || 0;

                } catch (error) {
                    if (error.name !== 'AbortError') {
                        console.error("Terjadi kesalahan:", error);
                    }
                } finally {
                    this.isFetching = false;
                }
            },

            goToPage(page) {
                if (page < 1 || page > this.lastPage) return;
                this.currentPage = page;
                this.fetchData();
            },

            get paginationPages() {
                let pages = [];
                const current = this.currentPage;
                const last = this.lastPage;

                if (last <= 5) {
                    for (let i = 1; i <= last; i++) pages.push(i);
                } else {
                    if (current <= 3) {
                        pages = [1, 2, 3, 4, '...', last];
                    } else if (current >= last - 2) {
                        pages = [1, '...', last - 3, last - 2, last - 1, last];
                    } else {
                        pages = [1, '...', current - 1, current, current + 1, '...', last];
                    }
                }
                return pages;
            },

            openLihatModal(data) {
                this.editData = JSON.parse(JSON.stringify(data));
                this.modalLihat = true;
            },

            openEditModal(data) {
                this.editData = JSON.parse(JSON.stringify(data));
                this.editSelectedKecamatan = data.desa?.id_kecamatan || '';
                this.editSelectedDesa = data.id_desa || '';
                this.modalEditError = false;
                this.modalEdit = true;
            },

            openSandiModal(data) {
                this.editData = JSON.parse(JSON.stringify(data));
                this.modalSandiError = false;
                this.modalSandi = true;
            }
        }
    }
</script>
@endpush
