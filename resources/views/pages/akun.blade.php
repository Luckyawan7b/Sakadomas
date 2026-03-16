@extends('layouts.app')

@section('content')

    {{-- x-data khusus untuk Modal Tambah Akun --}}
    <div x-data='{
        modalTambah: false,
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
                <form method="GET" action="{{ route('akun.index') }}" class="relative w-full sm:w-auto">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, hp..."
                        class="dark:bg-gray-900 h-11 w-full sm:w-64 rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">

                    <button type="submit" class="absolute right-0 top-0 flex h-11 w-11 items-center justify-center text-gray-500 hover:text-brand-500 dark:text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    {{-- Tombol reset pencarian kecil di dalam input (Muncul jika sedang mencari) --}}
                    @if(request('q'))
                        <a href="{{ route('akun.index') }}" class="absolute right-10 top-0 flex h-11 w-8 items-center justify-center text-red-400 hover:text-red-600" title="Reset Pencarian">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
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
                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">Masukkan detail untuk mendaftarkan
                            pengguna baru.</p>
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
                            <button @click="modalTambah = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">Batal</button>
                            <button type="submit" class="flex w-full justify-center rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600 sm:w-auto">Tambah Akun</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <div class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">
        <div class="py-6 px-4 md:px-6 xl:px-7.5">
            <h4 class="text-xl font-semibold text-black dark:text-white">
                Daftar Pengguna <span class="text-sm font-normal text-gray-500">({{ $data_akun->total() }} Data)</span>
            </h4>
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
                    @forelse ($data_akun as $index => $akun)
                        <tr x-data="{
                            modalLihat: false,
                            modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_akun_edit') == $akun->id_akun && !old('password_baru') ? 'true' : 'false' }},
                            modalSandi: {{ $errors->any() && old('_method') === 'PUT' && old('id_akun_sandi') == $akun->id_akun ? 'true' : 'false' }},
                            selectedKecamatan: {{ $akun->desa ? $akun->desa->id_kecamatan : 'null' }},
                            selectedDesa: {{ $akun->id_desa ?? 'null' }},
                            semuaDesa: {{ $desa->toJson() }},
                            get filteredDesa() {
                                return this.semuaDesa.filter(d => d.id_kecamatan == this.selectedKecamatan);
                            }
                        }" class="border-b border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                {{-- Menggunakan firstItem() dari pagination Laravel agar nomor urut tidak me-reset di halaman 2 --}}
                                {{ $data_akun->firstItem() + $index }}
                            </td>
                            <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                <span class="font-medium text-brand-500">{{ $akun->nama }}</span>
                            </td>
                            <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                {{ $akun->username }}
                            </td>
                            <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                {{ $akun->email ?? '-' }} <br>
                                <span class="text-sm text-gray-500">{{ $akun->no_hp }}</span>
                            </td>
                            <td class="py-5 px-4 xl:px-6 text-center">
                                <div class="flex items-center justify-center space-x-3.5">
                                    {{-- Modal Lihat --}}
                                    <button @click="modalLihat = true" type="button"
                                        class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 disabled:bg-brand-300">
                                        Lihat
                                    </button>

                                    {{-- Modal Edit --}}
                                    <button @click="modalEdit = true"
                                        class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-yellow-500 text-white shadow-theme-xs hover:bg-yellow-600 disabled:bg-brand-300"
                                        type="button">
                                        Edit
                                    </button>

                                    {{-- Modal Reset Sandi --}}
                                    <button @click="modalSandi = true" type="button"
                                        class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-gray-500 text-white shadow-theme-xs hover:bg-gray-600 disabled:bg-gray-300">
                                        Sandi
                                    </button>
                                </div>
                            </td>

                            <template x-teleport="body">
                                <div x-show="modalLihat" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm" @click.self="modalLihat = false">
                                    <div class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                                        <div class="px-2 pr-14">
                                            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Detail Akun</h4>
                                        </div>

                                        <div class="flex flex-col">
                                            <div class="custom-scrollbar overflow-y-auto p-2">
                                                <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Lengkap</label>
                                                        <input type="text" value="{{ $akun->nama }}" readonly class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90">
                                                    </div>
                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Username</label>
                                                        <input type="text" value="{{ $akun->username }}" readonly class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90">
                                                    </div>
                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                                                        <input type="text" value="{{ $akun->email }}" readonly class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90">
                                                    </div>
                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">No Handphone</label>
                                                        <input type="text" value="{{ $akun->no_hp }}" readonly class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90">
                                                    </div>
                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kecamatan</label>
                                                        <input type="text" value="{{ $akun->desa->kecamatan->nama_kecamatan ?? '-' }}" readonly class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90">
                                                    </div>
                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Desa</label>
                                                        <input type="text" value="{{ $akun->desa->nama_desa ?? '-' }}" readonly class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90">
                                                    </div>
                                                    <div class="col-span-1 lg:col-span-2">
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat</label>
                                                        <input type="text" value="{{ $akun->alamat }}" readonly class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                                                <button @click="modalLihat = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:w-auto">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template x-teleport="body">
                                <div x-show="modalEdit" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm" @click.self="modalEdit = false">
                                    <div class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                                        <div class="px-2 pr-14">
                                            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Edit Data Akun</h4>
                                        </div>

                                        <form method="POST" action="{{ route('akun.update', $akun->id_akun) }}" class="flex flex-col">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id_akun_edit" value="{{ $akun->id_akun }}">

                                            @if ($errors->any() && old('_method') === 'PUT' && old('id_akun_edit') == $akun->id_akun)
                                                <div class="mb-4 mx-2 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">{{ $errors->first() }}</div>
                                            @endif

                                            <div class="custom-scrollbar overflow-y-auto p-2">
                                                <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Lengkap</label>
                                                        <input type="text" name="nama" value="{{ $akun->nama }}" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                                                    </div>
                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Username</label>
                                                        <input type="text" name="username" value="{{ $akun->username }}" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                                                    </div>
                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                                                        <input type="email" name="email" value="{{ $akun->email }}" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                                                    </div>
                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">No Handphone</label>
                                                        <input type="text" name="no_hp" value="{{ $akun->no_hp }}" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                                                    </div>

                                                    {{-- Dropdown Edit Kandang & Desa --}}
                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kecamatan</label>
                                                        <select x-model="selectedKecamatan" @change="selectedDesa = ''" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                                                            <option value="" disabled class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Kecamatan</option>
                                                            @foreach ($kecamatan as $kec)
                                                                <option value="{{ $kec->id_kecamatan }}" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">{{ $kec->nama_kecamatan }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Desa</label>
                                                        <select name="id_desa" x-model="selectedDesa" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                                                            <option value="" disabled class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Desa</option>
                                                            <template x-for="d in filteredDesa" :key="d.id_desa">
                                                                <option :value="d.id_desa" x-text="d.nama_desa" :selected="d.id_desa == selectedDesa" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white"></option>
                                                            </template>
                                                        </select>
                                                    </div>

                                                    <div class="col-span-1 lg:col-span-2">
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat</label>
                                                        <input type="text" name="alamat" value="{{ $akun->alamat }}" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                                                <button @click="modalEdit = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 sm:w-auto">Batal</button>
                                                <button type="submit" class="flex w-full justify-center rounded-lg bg-yellow-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-yellow-600 sm:w-auto">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </template>

                            <template x-teleport="body">
                                <div x-show="modalSandi" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm" @click.self="modalSandi = false">
                                    <div class="no-scrollbar relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                                        <div class="px-2 pr-14 mb-6">
                                            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Ganti Password</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Atur ulang password untuk akun <strong>{{ $akun->nama }}</strong>.</p>
                                        </div>

                                        <form method="POST" action="{{ route('akun.reset-password', $akun->id_akun) }}" class="flex flex-col gap-5 px-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id_akun_sandi" value="{{ $akun->id_akun }}">

                                            @if ($errors->any() && old('_method') === 'PUT' && old('id_akun_sandi') == $akun->id_akun)
                                                <div class="mb-1 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">{{ $errors->first() }}</div>
                                            @endif

                                            <div>
                                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password Baru</label>
                                                <input type="password" name="password_baru" required minlength="6" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                                            </div>

                                            <div>
                                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Konfirmasi Password</label>
                                                <input type="password" name="password_baru_confirmation" required minlength="6" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 dark:border-gray-700 dark:text-white/90">
                                            </div>

                                            <div class="flex items-center gap-3 mt-4 lg:justify-end">
                                                <button @click="modalSandi = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 sm:w-auto">Batal</button>
                                                <button type="submit" class="flex w-full justify-center rounded-lg bg-gray-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-gray-700 sm:w-auto">Simpan Password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </template>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 px-4 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    <p class="text-gray-500 dark:text-gray-400">Tidak ada data akun yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Fungsi Pagination Bawaan Laravel --}}
        @if($data_akun->hasPages())
            <div class="border-t border-gray-200 dark:border-gray-800 p-4">
                {{ $data_akun->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
