@php
    // Mengambil data ternak yang belum memiliki kandang (id_kamar = null) secara otomatis
    // Sehingga Anda tidak perlu mengedit kamarController.php
    if(!isset($ternak_kosong)) {
        $ternak_kosong = \App\Models\ternakModel::with('jenis_ternak')->whereNull('id_kamar')->get();
    }
@endphp

@extends('layouts.app')

@section('content')
    <div x-data="{
        filterStatus: 'semua',
        modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }}
    }">

        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                {{-- Tombol Kembali Mundur ke Detail Kamar --}}
                <a href="{{ route('kandang.kamar', $kandang->id_kandang) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
                <div>
                    <h2 class="text-title-md2 font-bold text-black dark:text-white">
                        Isi Kamar: {{ $kamar->nomor_kamar }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kandang Nomor {{ $kandang->nomor_kandang }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Filter Real-time --}}
                <div class="relative">
                    <select x-model="filterStatus"
                        class="appearance-none rounded-lg border border-gray-300 bg-white px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        <option value="semua">Semua Kesehatan</option>
                        <option value="sehat">Sehat</option>
                        <option value="sakit">Sakit</option>
                        <option value="hamil">Hamil</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7-7-7-7"></path></svg>
                    </div>
                </div>

                <button @click="modalTambah = true" type="button"
                    class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600">
                    <svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4.5v15m7.5-7.5h-15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Tambah Ternak
                </button>
            </div>
        </div>

        {{-- MODAL TAMBAH TERNAK (2 TAB: BARU & ADA) --}}
        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;" class="fixed inset-0 z- flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm" @click.self="modalTambah = false">
                <div class="no-scrollbar relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-8" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-2">
                        <h4 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Tambah Ternak</h4>
                        <p class="text-sm text-gray-500 mt-1">ke Kandang {{ $kandang->nomor_kandang }}, Kamar {{ $kamar->nomor_kamar }}</p>
                    </div>

                    @if ($errors->any() && !old('_method'))
                        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">{{ $errors->first() }}</div>
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
                        {{-- NAVIGASI TAB --}}
                        <div class="flex border-b border-gray-200 dark:border-gray-700 mb-6 mt-4 gap-4">
                            <button type="button" @click="tab = 'baru'" :class="tab === 'baru' ? 'border-brand-500 text-brand-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200'" class="w-1/2 py-2.5 text-center text-sm font-medium border-b-2 transition-colors">Registrasi Baru</button>
                            <button type="button" @click="tab = 'ada'" :class="tab === 'ada' ? 'border-brand-500 text-brand-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200'" class="w-1/2 py-2.5 text-center text-sm font-medium border-b-2 transition-colors">Pilih Ternak Kosong</button>
                        </div>

                        {{-- TAB 1: FORM TERNAK BARU --}}
                        <form x-show="tab === 'baru'" method="POST" action="{{ route('ternak.store') }}" class="flex flex-col gap-4">
                            @csrf

                            <input type="hidden" name="id_kandang" value="{{ $kandang->id_kandang }}">
                            <input type="hidden" name="id_kamar" value="{{ $kamar->id_kamar }}">

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Ternak</label>
                                    <select name="id_jenis_ternak" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white capitalize">
                                        <option value="" disabled selected class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Jenis</option>
                                        @foreach($data_jenis as $jenis)
                                            <option value="{{ $jenis->id_jenis_ternak }}" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white capitalize" {{ old('id_jenis_ternak') == $jenis->id_jenis_ternak ? 'selected' : '' }}>{{ $jenis->jenis_ternak }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                        <option value="jantan" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ old('jenis_kelamin') == 'jantan' ? 'selected' : '' }}>Jantan</option>
                                        <option value="betina" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ old('jenis_kelamin') == 'betina' ? 'selected' : '' }}>Betina</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Usia (Bulan)</label>
                                    <input type="number" name="usia" value="{{ old('usia') }}" required min="0" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Berat (Kg)</label>
                                    <input type="number" name="berat" value="{{ old('berat') }}" required min="0" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Kesehatan</label>
                                    <select name="status_ternak" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                        <option value="sehat" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ old('status_ternak') == 'sehat' ? 'selected' : '' }}>Sehat</option>
                                        <option value="sakit" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ old('status_ternak') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                        <option value="hamil" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ old('status_ternak') == 'hamil' ? 'selected' : '' }}>Hamil</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Penjualan</label>
                                    <select name="status_jual" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                        <option value="tidak dijual" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ old('status_jual') == 'tidak dijual' ? 'selected' : '' }}>Tidak Dijual</option>
                                        <option value="siap jual" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ old('status_jual') == 'siap jual' ? 'selected' : '' }}>Siap Jual</option>
                                        <option value="booking" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ old('status_jual') == 'booking' ? 'selected' : '' }}>Booking</option>
                                        <option value="terjual" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ old('status_jual') == 'terjual' ? 'selected' : '' }}>Terjual</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 mt-4 justify-end">
                                <button @click="modalTambah = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">Batal</button>
                                <button type="submit" class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">Simpan Ternak</button>
                            </div>
                        </form>

                        {{-- TAB 2: FORM MASUKKAN TERNAK YANG SUDAH ADA (KOSONG) --}}
                        <form x-show="tab === 'ada'" method="POST" :action="'/ternak/' + selectedTernakId" style="display: none;" class="flex flex-col gap-4">
                            @csrf
                            @method('PUT')

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
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Ternak Tanpa Kandang</label>
                                <select x-model="selectedTernakId" @change="updateTernakData" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="" disabled class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Data Ternak</option>
                                    @if($ternak_kosong->count() > 0)
                                        @foreach($ternak_kosong as $tk)
                                            <option value="{{ $tk->id_ternak }}" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">
                                                ID-{{ $tk->id_ternak }} ({{ $tk->jenis_ternak->jenis_ternak ?? 'Ternak' }} - {{ $tk->berat }}Kg)
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="" disabled class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Saat ini tidak ada ternak kosong</option>
                                    @endif
                                </select>
                            </div>

                            <div x-show="selectedTernakId !== ''" class="mt-2 bg-gray-50 dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 text-sm">
                                <ul class="text-gray-700 dark:text-gray-300 space-y-1 mb-2">
                                    <li><strong>Kelamin:</strong> <span x-text="selectedTernakData.jenis_kelamin" class="capitalize"></span></li>
                                    <li><strong>Usia:</strong> <span x-text="selectedTernakData.usia"></span> Bulan</li>
                                    <li><strong>Berat:</strong> <span x-text="selectedTernakData.berat"></span> Kg</li>
                                </ul>
                                <p class="text-xs text-brand-600 dark:text-brand-400 font-medium">*Ternak ini akan dimasukkan ke Kamar {{ $kamar->nomor_kamar }}</p>
                            </div>

                            <div class="flex items-center gap-3 mt-4 justify-end">
                                <button @click="modalTambah = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">Batal</button>
                                <button type="submit" :disabled="selectedTernakId === ''" :class="selectedTernakId === '' ? 'opacity-50 cursor-not-allowed' : ''" class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">Masukkan ke Kamar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        @php
            $countSehat = 0; $countSakit = 0; $countHamil = 0;
            foreach($data_ternak as $t) {
                $stat = strtolower(trim($t->status_ternak));
                if($stat == 'sehat') $countSehat++;
                elseif($stat == 'sakit') $countSakit++;
                elseif($stat == 'hamil') $countHamil++;
            }
        @endphp

        <div class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900 mb-8 mt-6">
            <div class="py-4 px-4 md:px-6 xl:px-7.5 bg-gray-50 dark:bg-gray-800/50 flex justify-between items-center border-b border-gray-200 dark:border-gray-800">
                <h4 class="text-lg font-semibold text-black dark:text-white">
                    Daftar Ternak di Kamar Ini
                </h4>
                <span class="text-sm font-medium {{ $data_ternak->count() >= $kamar->kapasitas ? 'bg-red-100 text-red-500 dark:bg-red-500/10 dark:text-red-400' : 'bg-brand-100 text-brand-500 dark:bg-brand-500/10 dark:text-brand-400' }} px-3 py-1 rounded-full">
                    Kapasitas: {{ $data_ternak->count() }} / {{ $kamar->kapasitas }} Ekor
                </span>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="text-left dark:bg-gray-800">
                            <th class="py-3 px-4 font-medium text-black dark:text-white xl:px-6">ID Ternak</th>
                            <th class="py-3 px-4 font-medium text-black dark:text-white xl:px-6">Jenis & Profil</th>
                            <th class="py-3 px-4 font-medium text-black dark:text-white xl:px-6">Berat & Harga</th>
                            <th class="py-3 px-4 font-medium text-black dark:text-white xl:px-6 text-center">Status</th>
                            <th class="py-3 px-4 font-medium text-black dark:text-white xl:px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_ternak as $index => $ternak)
                            <tr x-data="{
                                stat: '{{ strtolower(trim($ternak->status_ternak)) }}',
                                modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_ternak_edit') == $ternak->id_ternak ? 'true' : 'false' }},
                                modalHapus: false
                            }"
                                x-show="filterStatus === 'semua' || filterStatus === stat"
                                class="border-b border-gray-200 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50">

                                <td class="py-4 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                    <span class="font-bold text-brand-500">ID-{{ $ternak->id_ternak }}</span>
                                </td>

                                <td class="py-4 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                    <span class="font-medium capitalize">{{ $ternak->jenis_ternak->jenis_ternak ?? '-' }}</span><br>
                                    <span class="text-sm capitalize">{{ $ternak->jenis_kelamin }}</span> • <span class="text-sm">{{ $ternak->usia }} Bln</span>
                                </td>

                                <td class="py-4 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                    <span class="font-medium">{{ $ternak->berat }} Kg</span><br>
                                    <span class="text-sm text-green-600 dark:text-green-400">Rp {{ number_format($ternak->harga, 0, ',', '.') }}</span>
                                </td>

                                <td class="py-4 px-4 xl:px-6 text-center">
                                    @if(strtolower($ternak->status_ternak) == 'sehat')
                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-500/10">Sehat</span>
                                    @elseif(strtolower($ternak->status_ternak) == 'sakit')
                                        <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-500/10">Sakit</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-purple-100 px-2 py-1 text-xs font-medium text-purple-700 dark:bg-purple-500/10">Hamil</span>
                                    @endif
                                    <br>
                                    <span class="mt-1 inline-block text-xs text-gray-500 uppercase font-semibold">
                                        {{ $ternak->status_jual }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 xl:px-6 text-center">
                                    <div class="flex items-center justify-center space-x-3.5">
                                        <button @click="modalEdit = true" type="button" class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-3 py-1.5 text-sm bg-yellow-500 text-white hover:bg-yellow-600">Edit</button>
                                        <button @click="modalHapus = true" type="button" class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-3 py-1.5 text-sm bg-red-500 text-white hover:bg-red-600">Hapus</button>
                                    </div>
                                </td>

                                {{-- MODAL EDIT TERNAK --}}
                                <template x-teleport="body">
                                    <div x-show="modalEdit" style="display: none;" class="fixed inset-0 z- flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm" @click.self="modalEdit = false">
                                        <div class="no-scrollbar relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-8" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                                            <div class="mb-6 flex justify-between items-start">
                                                <div>
                                                    <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Edit Ternak #ID-{{ $ternak->id_ternak }}</h4>
                                                    <p class="text-xs text-gray-500">Terakhir diupdate: {{ \Carbon\Carbon::parse($ternak->last_update)->translatedFormat('d M Y, H:i') }}</p>
                                                </div>
                                            </div>

                                            <form method="POST" action="{{ route('ternak.update', $ternak->id_ternak) }}" class="flex flex-col gap-4">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="id_ternak_edit" value="{{ $ternak->id_ternak }}">

                                                @if ($errors->any() && old('_method') === 'PUT' && old('id_ternak_edit') == $ternak->id_ternak)
                                                    <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">{{ $errors->first() }}</div>
                                                @endif

                                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2" x-data="{
                                                    selectedKandang: '{{ old('id_kandang', $ternak->id_kamar ? $ternak->kamar->id_kandang : 'kosong') }}',
                                                    selectedKamar: '{{ old('id_kamar', $ternak->id_kamar ?? 'kosong') }}'
                                                }">
                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pindah Kandang</label>
                                                        <select name="id_kandang" x-model="selectedKandang" @change="selectedKamar = (selectedKandang === 'kosong' ? 'kosong' : '')" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                            <option value="" disabled class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Kandang</option>
                                                            @foreach($data_kandang as $kd)
                                                                <option value="{{ $kd->id_kandang }}" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Kandang {{ $kd->nomor_kandang }}</option>
                                                            @endforeach
                                                            <option value="kosong" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Kosong (Keluar Kandang)</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pindah Kamar</label>
                                                        <select name="id_kamar" x-model="selectedKamar" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                            <option value="" disabled x-show="selectedKandang !== 'kosong'" :hidden="selectedKandang === 'kosong'" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Pilih Kamar</option>
                                                            <option value="kosong" x-show="selectedKandang === 'kosong'" :hidden="selectedKandang !== 'kosong'" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">Kosong</option>

                                                            @foreach($data_kamar as $kamarList)
                                                                <option value="{{ $kamarList->id_kamar }}"
                                                                        x-show="selectedKandang == '{{ $kamarList->id_kandang }}'"
                                                                        :hidden="selectedKandang != '{{ $kamarList->id_kandang }}'"
                                                                        class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white">
                                                                    Kamar {{ $kamarList->nomor_kamar }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Ternak</label>
                                                        <select name="id_jenis_ternak" required class="capitalize dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                            @foreach($data_jenis as $jenis)
                                                                <option value="{{ $jenis->id_jenis_ternak }}" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white capitalize" {{ $ternak->id_jenis_ternak == $jenis->id_jenis_ternak ? 'selected' : '' }}>{{ $jenis->jenis_ternak }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kelamin</label>
                                                        <select name="jenis_kelamin" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                            <option value="jantan" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ $ternak->jenis_kelamin == 'jantan' ? 'selected' : '' }}>Jantan</option>
                                                            <option value="betina" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ $ternak->jenis_kelamin == 'betina' ? 'selected' : '' }}>Betina</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Usia (Bulan)</label>
                                                        <input type="number" name="usia" value="{{ $ternak->usia }}" required min="0" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                    </div>

                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Berat (Kg)</label>
                                                        <input type="number" name="berat" value="{{ $ternak->berat }}" required min="0" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                    </div>

                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Harga Jual (Rp)</label>
                                                        <input type="number" name="harga" value="{{ $ternak->harga }}" required min="0" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                    </div>

                                                    <div>
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kesehatan</label>
                                                        <select name="status_ternak" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                            <option value="sehat" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ $ternak->status_ternak == 'sehat' ? 'selected' : '' }}>Sehat</option>
                                                            <option value="sakit" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ $ternak->status_ternak == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                                            <option value="hamil" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ $ternak->status_ternak == 'hamil' ? 'selected' : '' }}>Hamil</option>
                                                            <option value="mati" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ $ternak->status_ternak == 'mati' ? 'selected' : '' }}>Mati</option>
                                                        </select>
                                                    </div>

                                                    <div class="sm:col-span-2">
                                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Penjualan</label>
                                                        <select name="status_jual" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                            <option value="tidak dijual" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ $ternak->status_jual == 'tidak dijual' ? 'selected' : '' }}>Tidak Dijual</option>
                                                            <option value="siap jual" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ $ternak->status_jual == 'siap jual' ? 'selected' : '' }}>Siap Jual</option>
                                                            <option value="booking" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ $ternak->status_jual == 'booking' ? 'selected' : '' }}>Booking</option>
                                                            <option value="terjual" class="bg-white text-gray-800 dark:bg-gray-900 dark:text-white" {{ $ternak->status_jual == 'terjual' ? 'selected' : '' }}>Terjual</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-3 mt-4 justify-end">
                                                    <button @click="modalEdit = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">Batal</button>
                                                    <button type="submit" class="flex w-full justify-center rounded-lg bg-yellow-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-yellow-600 sm:w-auto">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </template>

                                {{-- MODAL HAPUS TERNAK --}}
                                <template x-teleport="body">
                                    <div x-show="modalHapus" style="display: none;" class="fixed inset-0 z- flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm" @click.self="modalHapus = false">
                                        <div class="relative w-full max-w-[400px] rounded-3xl bg-white p-6 dark:bg-gray-900 text-center" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                            <h4 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">Hapus Data Ternak?</h4>
                                            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Yakin ingin menghapus ternak <strong>#ID-{{ $ternak->id_ternak }}</strong>? Tindakan ini tidak bisa dibatalkan.</p>

                                            <form method="POST" action="{{ route('ternak.delete', $ternak->id_ternak) }}" class="flex justify-center gap-3">
                                                @csrf
                                                @method('DELETE')
                                                <button @click="modalHapus = false" type="button" class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">Batal</button>
                                                <button type="submit" class="rounded-lg bg-red-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-red-600">Ya, Hapus!</button>
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
                                        <p class="text-gray-500 dark:text-gray-400">Kamar ini masih kosong.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        {{-- PESAN JIKA FILTER KOSONG --}}
                        @if($data_ternak->isNotEmpty())
                            <tr x-show="filterStatus === 'sehat' && {{ $countSehat }} === 0" style="display: none;"><td colspan="5" class="py-6 text-center text-gray-500 italic">Tidak ada ternak Sehat di kamar ini.</td></tr>
                            <tr x-show="filterStatus === 'sakit' && {{ $countSakit }} === 0" style="display: none;"><td colspan="5" class="py-6 text-center text-gray-500 italic">Tidak ada ternak Sakit di kamar ini.</td></tr>
                            <tr x-show="filterStatus === 'hamil' && {{ $countHamil }} === 0" style="display: none;"><td colspan="5" class="py-6 text-center text-gray-500 italic">Tidak ada ternak Hamil di kamar ini.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
