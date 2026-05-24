@extends('layouts.app')

@section('content')
    <div x-data="ajaxTable('{{ route('transaksi.rekap') }}')">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-title-md2 font-bold text-black dark:text-white">
                    Rekap Transaksi
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau semua riwayat transaksi penjualan ternak.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Form Pencarian Cepat --}}
                <form id="search-form" @submit.prevent="fetchData" class="relative">
                    <input type="text" name="q" x-model="searchQuery" @input.debounce.500ms="fetchData"
                        placeholder="Cari Transaksi..."
                        class="dark:bg-gray-900 h-11 w-full sm:w-56 rounded-lg border border-gray-300 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-800 transition focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white dark:focus:border-brand-800">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                </form>

                {{-- Tombol Buka Modal Filter --}}
                <button @click="modalFilter = true" type="button" class="relative inline-flex items-center justify-center font-medium gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 h-11 text-sm text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter Laporan
                    <template x-if="isFiltered">
                        <span class="absolute top-2 right-2 flex h-2.5 w-2.5 items-center justify-center rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
                    </template>
                </button>
            </div>
        </div>

        {{-- MODAL FILTER ADVANCED --}}
        <template x-teleport="body">
            <div x-show="modalFilter" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm" @click.self="modalFilter = false">
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

                    <form id="filter-form" @submit.prevent="applyFilter" class="flex flex-col gap-5">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Awal</label>
                                <div class="relative">
                                    <input type="text" name="tgl_awal" x-model="filterTglAwal"
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
                                    <input type="text" name="tgl_akhir" x-model="filterTglAkhir"
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
                                <select name="status" x-model="filterStatus" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="semua">Semua Status</option>
                                    <option value="pending">Pending (Menunggu Pembayaran)</option>
                                    <option value="diproses">Diproses (Lunas)</option>
                                    <option value="dikirim">Dalam Pengiriman</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="batal">Dibatalkan</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Metode Pembayaran</label>
                                <select name="metode_pembayaran" x-model="filterMetode" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="semua">Semua Metode</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="cod">Cash on Delivery (COD)</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mt-4 justify-end">
                            <button type="button" @click="resetFilter" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">
                                Reset Filter
                            </button>
                            <button type="submit" class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                                Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- TABEL DATA TRANSAKSI --}}
        <div class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900 mt-6 relative">
            <div class="py-6 px-4 md:px-6 xl:px-7.5 border-b border-gray-200 dark:border-gray-800">
                <h4 class="text-xl font-semibold text-black dark:text-white">
                    Daftar Transaksi <span class="text-sm font-normal text-gray-500" x-text="`(${totalData} Data)`"></span>
                </h4>
            </div>

            {{-- Loading Overlay Spinner --}}
            <div x-show="isFetching" class="py-8 flex justify-center items-center" style="display: none;">
                <svg class="animate-spin h-8 w-8 text-brand-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <div class="max-w-full overflow-x-auto" x-show="!isFetching" x-transition.opacity.duration.200ms>
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
                        <template x-for="transaksi in rows" :key="transaksi.id_transaksi">
                            <tr class="border-b border-gray-200 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50">

                                <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                    <span class="font-bold text-brand-500" x-text="'#TRX-' + transaksi.id_transaksi"></span><br>
                                    <span class="text-xs font-medium text-gray-500" x-text="formatDate(transaksi.tgl_transaksi)"></span>
                                </td>

                                <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                    <span class="font-medium text-black dark:text-white" x-text="transaksi.akun ? transaksi.akun.nama : 'Akun Terhapus'"></span><br>
                                    <span class="text-xs text-gray-500" x-text="transaksi.akun ? transaksi.akun.no_hp : '-'"></span>
                                </td>

                                <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                    <span class="font-medium" x-text="transaksi.jenis_ternak ? transaksi.jenis_ternak.jenis_ternak : '-'"></span><br>
                                    <span class="text-xs text-gray-500" x-text="`${transaksi.jenis_kelamin_pesanan ? transaksi.jenis_kelamin_pesanan.charAt(0).toUpperCase() + transaksi.jenis_kelamin_pesanan.slice(1) : '-'} &bull; ${transaksi.total_jumlah} Ekor`"></span>
                                </td>

                                <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                    <span class="font-bold text-green-600 dark:text-green-400" x-text="`Rp ${formatRupiah(transaksi.total_harga + transaksi.ongkir)}`"></span><br>
                                    <span class="text-xs text-gray-500 capitalize" x-text="transaksi.metode_pembayaran || '-'"></span>
                                    
                                    <template x-if="transaksi.ongkir > 0">
                                        <span><br><span class="text-xs text-brand-500" x-text="`(+Ongkir Rp ${formatRupiah(transaksi.ongkir)})`"></span></span>
                                    </template>
                                    
                                    <template x-if="transaksi.metode_pengiriman === 'ambil_sendiri'">
                                        <span><br><span class="inline-flex items-center gap-0.5 mt-0.5 text-[10px] font-medium text-green-600 dark:text-green-400">🏠 Ambil Sendiri</span></span>
                                    </template>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <template x-if="transaksi.status === 'selesai'">
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-500/10">Selesai</span>
                                    </template>
                                    <template x-if="transaksi.status === 'diproses' || transaksi.status === 'dibayar'">
                                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/10">Diproses</span>
                                    </template>
                                    <template x-if="transaksi.status === 'dikirim'">
                                        <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700 dark:bg-purple-500/10">Dikirim</span>
                                    </template>
                                    <template x-if="transaksi.status === 'batal'">
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700 dark:bg-red-500/10">Batal</span>
                                    </template>
                                    <template x-if="transaksi.status === 'pending'">
                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-500/10">Pending</span>
                                    </template>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <a :href="`/transaksi/invoice/${transaksi.id_transaksi}`" target="_blank" class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                            Cetak Invoice
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        </template>

                        {{-- Tampilan saat data kosong --}}
                        <tr x-show="rows.length === 0" style="display: none;">
                            <td colspan="6" class="py-10 px-4 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-3">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400">Tidak ada data transaksi yang ditemukan.</p>
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
    </div>
@endsection

@push('scripts')
<script>
    function ajaxTable(baseUrl) {
        return {
            isFetching: false,
            abortController: null,
            modalFilter: false,
            
            // Filters
            searchQuery: "{{ request('q') }}",
            filterTglAwal: "{{ request('tgl_awal') }}",
            filterTglAkhir: "{{ request('tgl_akhir') }}",
            filterStatus: "{{ request('status', 'semua') }}",
            filterMetode: "{{ request('metode_pembayaran', 'semua') }}",

            // Pagination & Data
            rows: @json($data_transaksi_json ?? []),
            currentPage: {{ $data_transaksi->currentPage() }},
            lastPage: {{ $data_transaksi->lastPage() }},
            totalData: {{ $data_transaksi->total() }},
            fromData: {{ $data_transaksi->firstItem() ?? 0 }},
            toData: {{ $data_transaksi->lastItem() ?? 0 }},

            get isFiltered() {
                return this.filterTglAwal !== '' || 
                       this.filterTglAkhir !== '' || 
                       this.filterStatus !== 'semua' || 
                       this.filterMetode !== 'semua';
            },

            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            },

            formatDate(dateString) {
                const options = { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            },

            async fetchData() {
                this.isFetching = true;
                
                if (this.abortController) {
                    this.abortController.abort();
                }
                this.abortController = new AbortController();

                try {
                    const params = new URLSearchParams();
                    if (this.searchQuery) params.append('q', this.searchQuery);
                    if (this.filterTglAwal) params.append('tgl_awal', this.filterTglAwal);
                    if (this.filterTglAkhir) params.append('tgl_akhir', this.filterTglAkhir);
                    if (this.filterStatus !== 'semua') params.append('status', this.filterStatus);
                    if (this.filterMetode !== 'semua') params.append('metode_pembayaran', this.filterMetode);
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

            applyFilter() {
                this.currentPage = 1;
                this.modalFilter = false;
                this.fetchData();
            },

            resetFilter() {
                this.filterTglAwal = '';
                this.filterTglAkhir = '';
                this.filterStatus = 'semua';
                this.filterMetode = 'semua';
                // Trigger form reset to reset flatpickr instances
                document.getElementById('filter-form').reset();
                this.applyFilter();
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
            }
        }
    }
</script>
@endpush
