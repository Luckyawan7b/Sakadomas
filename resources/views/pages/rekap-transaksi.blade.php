@extends('layouts.app')

@section('content')
    <div x-data="{
        modalFilter: false
    }">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-title-md2 font-bold text-black dark:text-white">
                    Rekap Transaksi
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau semua riwayat transaksi penjualan ternak.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                @php
                    // Cek apakah ada filter yang sedang aktif
                    $isFiltered = request()->filled('tgl_awal') ||
                                  request()->filled('tgl_akhir') ||
                                  (request()->filled('status') && request('status') !== 'semua') ||
                                  (request()->filled('metode_pembayaran') && request('metode_pembayaran') !== 'semua');
                @endphp

                {{-- Form Pencarian Cepat (Cari berdasarkan ID Transaksi / Nama Pembeli) --}}
                <form action="{{ route('transaksi.rekap') ?? '#' }}" method="GET" class="relative">
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Cari Transaksi..."
                        class="dark:bg-gray-900 h-11 w-full sm:w-56 rounded-lg border border-gray-300 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-800 transition focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white dark:focus:border-brand-800">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    {{-- Simpan sisa filter aktif agar tidak hilang saat di-search --}}
                    @if(request('tgl_awal')) <input type="hidden" name="tgl_awal" value="{{ request('tgl_awal') }}"> @endif
                    @if(request('tgl_akhir')) <input type="hidden" name="tgl_akhir" value="{{ request('tgl_akhir') }}"> @endif
                    @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                    @if(request('metode_pembayaran')) <input type="hidden" name="metode_pembayaran" value="{{ request('metode_pembayaran') }}"> @endif
                </form>

                {{-- Tombol Buka Modal Filter --}}
                <button @click="modalFilter = true" type="button" class="relative inline-flex items-center justify-center font-medium gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 h-11 text-sm text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter Laporan
                    @if($isFiltered)
                        <span class="absolute top-2 right-2 flex h-2.5 w-2.5 items-center justify-center rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
                    @endif
                </button>
            </div>
        </div>

        {{-- MODAL FILTER ADVANCED --}}
        <template x-teleport="body">
            <div x-show="modalFilter" style="display: none;" class="fixed inset-0 z- flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm" @click.self="modalFilter = false">
                <div class="no-scrollbar relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-8" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-6 flex justify-between items-start">
                        <div>
                            <h4 class="mb-1 text-2xl font-semibold text-gray-800 dark:text-white/90">Filter Laporan Transaksi</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tentukan kriteria transaksi yang ingin ditampilkan.</p>
                        </div>
                        <button @click="modalFilter = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form method="GET" action="{{ route('transaksi.rekap') ?? '#' }}" class="flex flex-col gap-5">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Awal</label>
                                <div class="relative">
                                    <input type="text" name="tgl_awal" value="{{ request('tgl_awal') }}"
                                        x-init="flatpickr($el, { dateFormat: 'Y-m-d', locale: 'id' })"
                                        class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:text-white dark:focus:border-brand-800"
                                        placeholder="Pilih Tanggal">
                                    <span class="absolute top-1/2 right-3.5 -translate-y-1/2 pointer-events-none">
                                        <svg class="fill-gray-700 dark:fill-gray-400" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.33317 0.0830078C4.74738 0.0830078 5.08317 0.418794 5.08317 0.833008V1.24967H8.9165V0.833008C8.9165 0.418794 9.25229 0.0830078 9.6665 0.0830078C10.0807 0.0830078 10.4165 0.418794 10.4165 0.833008V1.24967L11.3332 1.24967C12.2997 1.24967 13.0832 2.03318 13.0832 2.99967V4.99967V11.6663C13.0832 12.6328 12.2997 13.4163 11.3332 13.4163H2.6665C1.70001 13.4163 0.916504 12.6328 0.916504 11.6663V4.99967V2.99967C0.916504 2.03318 1.70001 1.24967 2.6665 1.24967L3.58317 1.24967V0.833008C3.58317 0.418794 3.91896 0.0830078 4.33317 0.0830078ZM4.33317 2.74967H2.6665C2.52843 2.74967 2.4165 2.8616 2.4165 2.99967V4.24967H11.5832V2.99967C11.5832 2.8616 11.4712 2.74967 11.3332 2.74967H9.6665H4.33317ZM11.5832 5.74967H2.4165V11.6663C2.4165 11.8044 2.52843 11.9163 2.6665 11.9163H11.3332C11.4712 11.9163 11.5832 11.8044 11.5832 11.6663V5.74967Z" fill=""/>
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Akhir</label>
                                <div class="relative">
                                    <input type="text" name="tgl_akhir" value="{{ request('tgl_akhir') }}"
                                        x-init="flatpickr($el, { dateFormat: 'Y-m-d', locale: 'id' })"
                                        class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:text-white dark:focus:border-brand-800"
                                        placeholder="Pilih Tanggal">
                                    <span class="absolute top-1/2 right-3.5 -translate-y-1/2 pointer-events-none">
                                        <svg class="fill-gray-700 dark:fill-gray-400" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.33317 0.0830078C4.74738 0.0830078 5.08317 0.418794 5.08317 0.833008V1.24967H8.9165V0.833008C8.9165 0.418794 9.25229 0.0830078 9.6665 0.0830078C10.0807 0.0830078 10.4165 0.418794 10.4165 0.833008V1.24967L11.3332 1.24967C12.2997 1.24967 13.0832 2.03318 13.0832 2.99967V4.99967V11.6663C13.0832 12.6328 12.2997 13.4163 11.3332 13.4163H2.6665C1.70001 13.4163 0.916504 12.6328 0.916504 11.6663V4.99967V2.99967C0.916504 2.03318 1.70001 1.24967 2.6665 1.24967L3.58317 1.24967V0.833008C3.58317 0.418794 3.91896 0.0830078 4.33317 0.0830078ZM4.33317 2.74967H2.6665C2.52843 2.74967 2.4165 2.8616 2.4165 2.99967V4.24967H11.5832V2.99967C11.5832 2.8616 11.4712 2.74967 11.3332 2.74967H9.6665H4.33317ZM11.5832 5.74967H2.4165V11.6663C2.4165 11.8044 2.52843 11.9163 2.6665 11.9163H11.3332C11.4712 11.9163 11.5832 11.8044 11.5832 11.6663V5.74967Z" fill=""/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Transaksi</label>
                                <select name="status" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu Pembayaran)</option>
                                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses (Lunas)</option>
                                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dalam Pengiriman</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Metode Pembayaran</label>
                                <select name="metode_pembayaran" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="semua" {{ request('metode_pembayaran') == 'semua' ? 'selected' : '' }}>Semua Metode</option>
                                    <option value="transfer" {{ request('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="cod" {{ request('metode_pembayaran') == 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mt-4 justify-end">
                            <a href="{{ route('transaksi.rekap') ?? '#' }}" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">
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

        {{-- TABEL DATA TRANSAKSI --}}
        <div class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900 mt-6">
            <div class="max-w-full overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 text-left dark:bg-gray-800 border-b border-gray-200 dark:border-gray-800">
                            <th class="py-4 px-4 font-medium text-black dark:text-white">ID & Tgl Transaksi</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white">Pembeli</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white">Pesanan Ternak</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white">Total Harga</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white text-center">Status</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_transaksi ?? [] as $transaksi)
                            <tr class="border-b border-gray-200 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50">

                                <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                    <span class="font-bold text-brand-500">#TRX-{{ $transaksi->id_transaksi }}</span><br>
                                    <span class="text-xs font-medium text-gray-500">
                                        {{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->translatedFormat('d M Y, H:i') }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                    <span class="font-medium text-black dark:text-white">{{ $transaksi->akun->nama ?? 'Akun Terhapus' }}</span><br>
                                    <span class="text-xs text-gray-500">{{ $transaksi->akun->no_hp ?? '-' }}</span>
                                </td>

                                <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                    <span class="font-medium">{{ $transaksi->jenisTernak->jenis_ternak ?? '-' }}</span><br>
                                    <span class="text-xs text-gray-500">{{ ucfirst($transaksi->jenis_kelamin_pesanan ?? '-') }} &bull; {{ $transaksi->total_jumlah }} Ekor</span>
                                </td>

                                <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                    <span class="font-bold text-green-600 dark:text-green-400">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span><br>
                                    <span class="text-xs text-gray-500 capitalize">{{ $transaksi->metode_pembayaran }}</span>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @php
                                        $status = strtolower($transaksi->status);
                                    @endphp

                                    @if($status == 'selesai')
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-500/10">Selesai</span>
                                    @elseif($status == 'diproses' || $status == 'dibayar')
                                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/10">Diproses</span>
                                    @elseif($status == 'dikirim')
                                        <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700 dark:bg-purple-500/10">Dikirim</span>
                                    @elseif($status == 'batal')
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700 dark:bg-red-500/10">Batal</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-500/10">Pending</span>
                                    @endif
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <a href="{{ route('ternak.detail', $transaksi->id_transaksi) ?? '#' }}" class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Detail Invoice
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-3">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400">Tidak ada data transaksi yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if(isset($data_transaksi) && $data_transaksi->hasPages())
                <div class="border-t border-gray-200 dark:border-gray-800 p-4">
                    {{ $data_transaksi->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection
