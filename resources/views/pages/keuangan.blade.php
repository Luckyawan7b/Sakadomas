@extends('layouts.app')

@section('content')
    <div x-data="{
        modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
        modalFilter: false
    }">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 3000)"
                 class="mb-4 flex items-center justify-between rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800 border border-green-200 dark:bg-green-500/10 dark:text-green-400 dark:border-green-800">
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
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 5000)"
                 class="mb-4 flex items-center justify-between rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800 border border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-800">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ session('error') }}
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        {{-- Header Section --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-title-md2 font-bold text-black dark:text-white">
                    Manajemen Keuangan
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau pemasukan penjualan dan kelola pengeluaran operasional.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                @php
                    $isFiltered = request()->filled('tgl_awal') || request()->filled('tgl_akhir') || (request()->filled('jenis_keuangan') && request('jenis_keuangan') !== 'semua');
                @endphp

                {{-- Quick Search Form --}}
                <form action="{{ route('keuangan.index') }}" method="GET" class="relative">
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Cari Keterangan..."
                        class="dark:bg-gray-900 h-11 w-full sm:w-56 rounded-lg border border-gray-300 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-800 transition focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white dark:focus:border-brand-800">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    @if(request('tgl_awal')) <input type="hidden" name="tgl_awal" value="{{ request('tgl_awal') }}"> @endif
                    @if(request('tgl_akhir')) <input type="hidden" name="tgl_akhir" value="{{ request('tgl_akhir') }}"> @endif
                    @if(request('jenis_keuangan')) <input type="hidden" name="jenis_keuangan" value="{{ request('jenis_keuangan') }}"> @endif
                </form>

                {{-- Filter Button --}}
                <button @click="modalFilter = true" type="button" class="relative inline-flex items-center justify-center font-medium gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 h-11 text-sm text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter Laporan
                    @if($isFiltered)
                        <span class="absolute top-2 right-2 flex h-2.5 w-2.5 items-center justify-center rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
                    @endif
                </button>

                {{-- Export PDF Button --}}
                <a href="{{ route('keuangan.pdf', request()->all()) }}" target="_blank"
                   class="inline-flex items-center justify-center font-medium gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 h-11 text-sm text-red-700 hover:bg-red-100 dark:border-red-900/30 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Unduh PDF
                </a>

                {{-- Add Record Button --}}
                <button @click="modalTambah = true" type="button"
                    class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2.5 h-11 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Tambah Transaksi
                </button>
            </div>
        </div>

        {{-- Statistik Keuangan Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
            {{-- Pemasukan Card --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900 flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Total Pemasukan</span>
                    <h3 class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">
                        Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>

            {{-- Pengeluaran Card --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900 flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Total Pengeluaran</span>
                    <h3 class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
            </div>

            {{-- Saldo Bersih Card --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900 flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Saldo Bersih</span>
                    <h3 class="text-2xl font-bold {{ $saldo >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-rose-600 dark:text-rose-400' }} mt-1">
                        Rp {{ number_format($saldo, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $saldo >= 0 ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400' : 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Tabel Data Keuangan --}}
        <div class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">
            <div class="max-w-full overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 text-left dark:bg-gray-800 border-b border-gray-200 dark:border-gray-800">
                            <th class="py-4 px-4 font-medium text-black dark:text-white">Tanggal</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white">Keterangan</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white">Sumber</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white">Pemasukan</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white">Pengeluaran</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_keuangan as $keuangan)
                            <tr x-data="{ modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_keuangan_edit') == $keuangan->id_keuangan ? 'true' : 'false' }} }"
                                class="border-b border-gray-200 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                
                                {{-- Tanggal --}}
                                <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($keuangan->tanggal)->translatedFormat('d M Y') }}
                                </td>

                                {{-- Keterangan --}}
                                <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                    <span class="font-medium text-black dark:text-white">{{ $keuangan->ket }}</span>
                                    @if($keuangan->id_transaksi)
                                        <br>
                                        <a href="{{ route('transaksi.invoice', $keuangan->id_transaksi) }}" target="_blank"
                                           class="text-[11px] font-semibold text-brand-500 hover:underline">
                                            Lihat Transaksi #TRX-{{ $keuangan->id_transaksi }}
                                        </a>
                                    @endif
                                </td>

                                {{-- Sumber --}}
                                <td class="py-4 px-4">
                                    @if($keuangan->id_transaksi)
                                        <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
                                            Sistem (Otomatis)
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                            Admin (Manual)
                                        </span>
                                    @endif
                                </td>

                                {{-- Pemasukan --}}
                                <td class="py-4 px-4">
                                    @if($keuangan->jenis_keuangan === 'pemasukan')
                                        <span class="font-bold text-green-600 dark:text-green-400">
                                            +Rp {{ number_format($keuangan->nominal, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 dark:text-gray-700">—</span>
                                    @endif
                                </td>

                                {{-- Pengeluaran --}}
                                <td class="py-4 px-4">
                                    @if($keuangan->jenis_keuangan === 'pengeluaran')
                                        <span class="font-bold text-red-600 dark:text-red-400">
                                            -Rp {{ number_format($keuangan->nominal, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 dark:text-gray-700">—</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="py-4 px-4 text-center">
                                    @if($keuangan->id_transaksi === null)
                                        <button @click="modalEdit = true" type="button"
                                                class="inline-flex items-center justify-center rounded-lg bg-amber-100 px-3.5 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20 transition">
                                            Edit
                                        </button>

                                        {{-- MODAL EDIT DATA MANUAL --}}
                                        <template x-teleport="body">
                                            <div x-show="modalEdit" style="display: none;"
                                                 class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                                                 @click.self="modalEdit = false">
                                                <div class="relative w-full max-w-[500px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10 text-left"
                                                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                                                     x-transition:enter-end="opacity-100 scale-100">
                                                    
                                                    <div class="mb-5 flex justify-between items-center">
                                                        <h4 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Transaksi Keuangan</h4>
                                                        <button @click="modalEdit = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </div>

                                                    @if ($errors->any() && old('_method') === 'PUT' && old('id_keuangan_edit') == $keuangan->id_keuangan)
                                                        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-800">
                                                            {{ $errors->first() }}
                                                        </div>
                                                    @endif

                                                    <form method="POST" action="{{ route('keuangan.update', $keuangan->id_keuangan) }}"
                                                          x-data="{
                                                              jenis: '{{ old('jenis_keuangan', $keuangan->jenis_keuangan) }}',
                                                              presetKet: '{{ in_array($keuangan->ket, [
                                                                  'Pemasukan Lainnya', 'Penjualan Pupuk/Kompos', 'Investasi/Modal Masuk', 'Hibah/Bantuan',
                                                                  'Pakan Ternak', 'Obat-obatan & Vaksin', 'Gaji Pegawai', 'Pemeliharaan Kandang', 'Pembelian Peralatan', 'Listrik & Air', 'Biaya Transportasi', 'Operasional Lainnya'
                                                              ]) ? $keuangan->ket : 'custom' }}',
                                                              customKet: '{{ !in_array($keuangan->ket, [
                                                                  'Pemasukan Lainnya', 'Penjualan Pupuk/Kompos', 'Investasi/Modal Masuk', 'Hibah/Bantuan',
                                                                  'Pakan Ternak', 'Obat-obatan & Vaksin', 'Gaji Pegawai', 'Pemeliharaan Kandang', 'Pembelian Peralatan', 'Listrik & Air', 'Biaya Transportasi', 'Operasional Lainnya'
                                                              ]) ? $keuangan->ket : '' }}',
                                                              nominalRaw: '{{ old('nominal', $keuangan->nominal) }}',
                                                              get nominalFormatted() {
                                                                  if (!this.nominalRaw) return '';
                                                                  return 'Rp ' + Number(this.nominalRaw).toLocaleString('id-ID');
                                                              },
                                                              formatInput(val) {
                                                                  this.nominalRaw = val.replace(/\D/g, '');
                                                              }
                                                          }"
                                                          class="flex flex-col gap-4">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="id_keuangan_edit" value="{{ $keuangan->id_keuangan }}">

                                                        {{-- Jenis Transaksi --}}
                                                        <div>
                                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Transaksi</label>
                                                            <select name="jenis_keuangan" x-model="jenis" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                                <option value="pemasukan">Pemasukan</option>
                                                                <option value="pengeluaran">Pengeluaran</option>
                                                            </select>
                                                        </div>

                                                        {{-- Keterangan (Preset & Custom) --}}
                                                        <div>
                                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Keterangan / Kategori</label>
                                                            <select x-model="presetKet" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                                <template x-if="jenis === 'pemasukan'">
                                                                    <optgroup label="Preset Pemasukan">
                                                                        <option value="Pemasukan Lainnya">Pemasukan Lainnya</option>
                                                                        <option value="Penjualan Pupuk/Kompos">Penjualan Pupuk/Kompos</option>
                                                                        <option value="Investasi/Modal Masuk">Investasi/Modal Masuk</option>
                                                                        <option value="Hibah/Bantuan">Hibah/Bantuan</option>
                                                                    </optgroup>
                                                                </template>
                                                                <template x-if="jenis === 'pengeluaran'">
                                                                    <optgroup label="Preset Pengeluaran">
                                                                        <option value="Pakan Ternak">Pakan Ternak</option>
                                                                        <option value="Obat-obatan & Vaksin">Obat-obatan & Vaksin</option>
                                                                        <option value="Gaji Pegawai">Gaji Pegawai</option>
                                                                        <option value="Pemeliharaan Kandang">Pemeliharaan Kandang</option>
                                                                        <option value="Pembelian Peralatan">Pembelian Peralatan</option>
                                                                        <option value="Listrik & Air">Listrik & Air</option>
                                                                        <option value="Biaya Transportasi">Biaya Transportasi</option>
                                                                        <option value="Operasional Lainnya">Operasional Lainnya</option>
                                                                    </optgroup>
                                                                </template>
                                                                <option value="custom">Lainnya (Ketik Manual)</option>
                                                            </select>
                                                        </div>

                                                        {{-- Custom Input Keterangan --}}
                                                        <div x-show="presetKet === 'custom'" x-transition>
                                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Keterangan Tambahan</label>
                                                            <input type="text" x-model="customKet" placeholder="Ketik keterangan..."
                                                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                        </div>

                                                        {{-- Bounded Value for Keterangan --}}
                                                        <input type="hidden" name="ket" :value="presetKet === 'custom' ? customKet : presetKet">

                                                        {{-- Nominal Input (Formatted) --}}
                                                        <div>
                                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nominal (Rupiah)</label>
                                                            <input type="text" :value="nominalFormatted" @input="formatInput($event.target.value)" required placeholder="Contoh: Rp 100.000"
                                                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                            <input type="hidden" name="nominal" :value="nominalRaw">
                                                        </div>

                                                        {{-- Tanggal (Maksimal hari ini) --}}
                                                        <div>
                                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Transaksi</label>
                                                            <div class="relative">
                                                                <input type="text" name="tanggal" value="{{ old('tanggal', $keuangan->tanggal) }}" required
                                                                       x-init="flatpickr($el, { dateFormat: 'Y-m-d', maxDate: 'today', locale: 'id' })"
                                                                       class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-3 mt-4 justify-end">
                                                            <button @click="modalEdit = false" type="button"
                                                                    class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 sm:w-auto">Batal</button>
                                                            <button type="submit"
                                                                    class="rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-amber-600 sm:w-auto">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </template>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Terkunci</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-3">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400">Tidak ada data keuangan yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginasi --}}
            @if($data_keuangan->hasPages())
                <div class="border-t border-gray-200 dark:border-gray-800 p-4">
                    {{ $data_keuangan->withQueryString()->links() }}
                </div>
            @endif
        </div>

        {{-- MODAL FILTER ADVANCED --}}
        <template x-teleport="body">
            <div x-show="modalFilter" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm" @click.self="modalFilter = false">
                <div class="relative w-full max-w-[500px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <div class="mb-5 flex justify-between items-center">
                        <h4 class="text-2xl font-bold text-gray-800 dark:text-white">Filter Rekap Keuangan</h4>
                        <button @click="modalFilter = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form method="GET" action="{{ route('keuangan.index') }}" class="flex flex-col gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Awal</label>
                                <div class="relative">
                                    <input type="text" name="tgl_awal" value="{{ request('tgl_awal') }}"
                                        x-init="flatpickr($el, { dateFormat: 'Y-m-d', locale: 'id' })"
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 dark:border-gray-700 dark:text-white"
                                        placeholder="Pilih Tanggal">
                                </div>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Akhir</label>
                                <div class="relative">
                                    <input type="text" name="tgl_akhir" value="{{ request('tgl_akhir') }}"
                                        x-init="flatpickr($el, { dateFormat: 'Y-m-d', locale: 'id' })"
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 dark:border-gray-700 dark:text-white"
                                        placeholder="Pilih Tanggal">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Transaksi</label>
                            <select name="jenis_keuangan" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="semua" {{ request('jenis_keuangan') === 'semua' ? 'selected' : '' }}>Semua Jenis</option>
                                <option value="pemasukan" {{ request('jenis_keuangan') === 'pemasukan' ? 'selected' : '' }}>Pemasukan saja</option>
                                <option value="pengeluaran" {{ request('jenis_keuangan') === 'pengeluaran' ? 'selected' : '' }}>Pengeluaran saja</option>
                            </select>
                        </div>

                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        <div class="flex items-center gap-3 mt-4 justify-end">
                            <a href="{{ route('keuangan.index') }}" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 sm:w-auto">
                                Reset Filter
                            </a>
                            <button type="submit" class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                                Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- MODAL TAMBAH TRANSAKSI MANUAL --}}
        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;"
                 class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                 @click.self="modalTambah = false">
                <div class="relative w-full max-w-[500px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10 text-left"
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                    
                    <div class="mb-5 flex justify-between items-center">
                        <h4 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Catatan Keuangan</h4>
                        <button @click="modalTambah = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    @if ($errors->any() && !old('_method'))
                        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-800">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('keuangan.store') }}"
                          x-data="{
                              jenis: 'pengeluaran',
                              presetKet: 'Pakan Ternak',
                              customKet: '',
                              nominalRaw: '',
                              get nominalFormatted() {
                                  if (!this.nominalRaw) return '';
                                  return 'Rp ' + Number(this.nominalRaw).toLocaleString('id-ID');
                              },
                              formatInput(val) {
                                  this.nominalRaw = val.replace(/\D/g, '');
                              }
                          }"
                          class="flex flex-col gap-4">
                        @csrf

                        {{-- Jenis Transaksi --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Transaksi</label>
                            <select name="jenis_keuangan" x-model="jenis" @change="presetKet = (jenis === 'pemasukan' ? 'Pemasukan Lainnya' : 'Pakan Ternak')" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="pengeluaran">Pengeluaran</option>
                                <option value="pemasukan">Pemasukan</option>
                            </select>
                        </div>

                        {{-- Keterangan (Preset & Custom) --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Keterangan / Kategori</label>
                            <select x-model="presetKet" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <template x-if="jenis === 'pemasukan'">
                                    <optgroup label="Preset Pemasukan">
                                        <option value="Pemasukan Lainnya">Pemasukan Lainnya</option>
                                        <option value="Penjualan Pupuk/Kompos">Penjualan Pupuk/Kompos</option>
                                        <option value="Investasi/Modal Masuk">Investasi/Modal Masuk</option>
                                        <option value="Hibah/Bantuan">Hibah/Bantuan</option>
                                    </optgroup>
                                </template>
                                <template x-if="jenis === 'pengeluaran'">
                                    <optgroup label="Preset Pengeluaran">
                                        <option value="Pakan Ternak">Pakan Ternak</option>
                                        <option value="Obat-obatan & Vaksin">Obat-obatan & Vaksin</option>
                                        <option value="Gaji Pegawai">Gaji Pegawai</option>
                                        <option value="Pemeliharaan Kandang">Pemeliharaan Kandang</option>
                                        <option value="Pembelian Peralatan">Pembelian Peralatan</option>
                                        <option value="Listrik & Air">Listrik & Air</option>
                                        <option value="Biaya Transportasi">Biaya Transportasi</option>
                                        <option value="Operasional Lainnya">Operasional Lainnya</option>
                                    </optgroup>
                                </template>
                                <option value="custom">Lainnya (Ketik Manual)</option>
                            </select>
                        </div>

                        {{-- Custom Input Keterangan --}}
                        <div x-show="presetKet === 'custom'" x-transition>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Keterangan Tambahan</label>
                            <input type="text" x-model="customKet" placeholder="Ketik keterangan..."
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        </div>

                        {{-- Bounded Value for Keterangan --}}
                        <input type="hidden" name="ket" :value="presetKet === 'custom' ? customKet : presetKet">

                        {{-- Nominal Input (Formatted) --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nominal (Rupiah)</label>
                            <input type="text" :value="nominalFormatted" @input="formatInput($event.target.value)" required placeholder="Contoh: Rp 100.000"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <input type="hidden" name="nominal" :value="nominalRaw">
                        </div>

                        {{-- Tanggal (Maksimal hari ini) --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Transaksi</label>
                            <div class="relative">
                                <input type="text" name="tanggal" required
                                       x-init="flatpickr($el, { dateFormat: 'Y-m-d', maxDate: 'today', defaultDate: 'today', locale: 'id' })"
                                       class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mt-4 justify-end">
                            <button @click="modalTambah = false" type="button"
                                    class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 sm:w-auto">Batal</button>
                            <button type="submit"
                                    class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
@endsection
