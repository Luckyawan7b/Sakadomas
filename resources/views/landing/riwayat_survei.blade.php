@extends('layouts.landing')

@section('title', 'Semua Riwayat Survei | Smart-Saka Premium Sheep Farm')

@push('head')
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    "primary": "#3d6700", "on-primary": "#ffffff", "primary-container": "#4f8207",
                    "on-primary-container": "#f9ffea", "secondary": "#496800", "on-secondary": "#ffffff",
                    "secondary-container": "#c8f17a", "on-secondary-container": "#4e6e00",
                    "tertiary": "#535f56", "tertiary-container": "#6b786e", "surface": "#fafaf5",
                    "on-surface": "#1a1c19", "surface-container": "#eeeee9",
                    "surface-container-low": "#f4f4ef", "surface-container-lowest": "#ffffff",
                    "surface-container-high": "#e8e8e3", "surface-variant": "#e3e3de",
                    "on-surface-variant": "#444939", "outline": "#747967",
                    "outline-variant": "#c4c9b4", "error": "#ba1a1a", "on-error": "#ffffff",
                    "error-container": "#ffdad6", "primary-fixed": "#b9f474",
                    "secondary-fixed": "#c8f17a", "tertiary-fixed": "#d9e6da",
                    "status-success": "#059669", "status-success-bg": "#d1fae5",
                    "status-cancel": "#dc2626", "status-cancel-bg": "#fee2e2"
                },
                fontFamily: { "headline": ["Plus Jakarta Sans"], "body": ["Manrope"], "label": ["Manrope"] }
            }
        }
    }
</script>
<style>
    body { font-family: 'Manrope', sans-serif; background-color: #fafaf5; }
    h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    nav { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>
@endpush

@section('content')
<x-landing.navbar />

@php
    $riwayatJson = $riwayatSemua->map(function($r) {
        $isTransaksi = !is_null($r->id_transaksi);
        $tujuan = 'Kunjungan ' . ($isTransaksi ? 'Terkait Transaksi' : 'Mandiri');
        $catatan = '';
        $alasanBatal = '';

        if (preg_match('/Tujuan:\s*(.*?)(?=\n|\||Catatan:|$)/i', $r->ket, $matches)) {
            $tujuan = trim($matches[1]);
        }
        if (preg_match('/Catatan:\s*(.*?)(?=\n|\||Alasan Batal:|$)/i', $r->ket, $matches)) {
            $catatan = trim($matches[1]);
        }
        if (preg_match('/Alasan Batal:\s*(.*?)$/i', $r->ket, $matches)) {
            $alasanBatal = trim($matches[1]);
        }

        return [
            'id' => 'SRV-' . str_pad($r->id_survei, 5, '0', STR_PAD_LEFT),
            'id_transaksi' => $r->id_transaksi,
            'is_transaksi' => $isTransaksi,
            'tujuan' => $tujuan,
            'catatan' => $catatan,
            'alasan_batal' => $alasanBatal,
            'tanggal' => \Carbon\Carbon::parse($r->tgl_survei)->translatedFormat('d M Y'),
            'waktu' => \Carbon\Carbon::parse($r->tgl_survei)->format('H:i'),
            'status' => ucfirst(strtolower($r->status)), // Selesai atau Batal
            'status_class' => strtolower($r->status) == 'selesai' ? 'success' : 'cancel'
        ];
    });
@endphp

<main class="pt-24 flex-1 text-on-surface"
    x-data="{
        search: '',
        filterStatus: 'Semua',
        riwayat: {{ \Illuminate\Support\Js::from($riwayatJson) }},
        modalDetail: false,
        detailData: {},

        get filteredRiwayat() {
            return this.riwayat.filter(item => {
                const matchSearch = item.id.toLowerCase().includes(this.search.toLowerCase()) ||
                                    item.tujuan.toLowerCase().includes(this.search.toLowerCase()) ||
                                    (item.is_transaksi && String(item.id_transaksi).includes(this.search));
                const matchFilter = this.filterStatus === 'Semua' || item.status === this.filterStatus;
                return matchSearch && matchFilter;
            });
        }
    }">

    <!-- Header -->
    <header class="relative bg-surface-container-low pt-12 pb-16 px-8 overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: url('{{ asset('images/katalog.jpg') }}'); background-size: cover; background-position: center;"></div>
        <div class="max-w-7xl mx-auto relative z-10">
            <nav class="flex items-center space-x-2 text-sm text-on-surface-variant mb-6 tracking-wide">
                <a href="{{ route('kunjungan.index') }}" class="hover:text-primary">Survei & Kunjungan</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-primary font-semibold">Semua Riwayat</span>
            </nav>
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-primary mb-4 tracking-tight leading-tight">Semua Riwayat Survei</h1>
                <p class="text-on-surface-variant max-w-xl text-lg">Daftar lengkap riwayat kunjungan dan survei Anda yang telah selesai atau dibatalkan.</p>
            </div>
        </div>
    </header>

    <section class="max-w-7xl mx-auto px-8 -mt-8 mb-24 relative z-10">
        <!-- Card Container -->
        <div class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(61,103,0,0.04)] border border-surface-container-high overflow-hidden">

            <!-- Toolbar (Search & Filter) -->
            <div class="p-6 md:p-8 border-b border-surface-container-high flex flex-col md:flex-row justify-between items-center gap-4 bg-surface-container-lowest">
                <!-- Search -->
                <div class="relative w-full md:w-96 flex items-center">
                    <span class="material-symbols-outlined absolute left-4 text-outline pointer-events-none select-none">search</span>
                    <input type="text" x-model="search" placeholder="Cari ID Survei atau Tujuan..." class="w-full pl-12 pr-4 py-3.5 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 text-sm font-bold text-on-surface outline-none">
                </div>

                <!-- Filters -->
                <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto no-scrollbar pb-2 md:pb-0">
                    <button @click="filterStatus = 'Semua'" :class="filterStatus === 'Semua' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-variant'" class="px-5 py-3 rounded-xl text-sm font-bold whitespace-nowrap transition-all">Semua</button>
                    <button @click="filterStatus = 'Selesai'" :class="filterStatus === 'Selesai' ? 'bg-status-success text-white shadow-md shadow-status-success/20' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-variant'" class="px-5 py-3 rounded-xl text-sm font-bold whitespace-nowrap transition-all">Selesai</button>
                    <button @click="filterStatus = 'Batal'" :class="filterStatus === 'Batal' ? 'bg-status-cancel text-white shadow-md shadow-status-cancel/20' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-variant'" class="px-5 py-3 rounded-xl text-sm font-bold whitespace-nowrap transition-all">Dibatalkan</button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant text-xs uppercase tracking-wider font-extrabold border-b border-surface-container-high">
                            <th class="px-8 py-5">Info Survei</th>
                            <th class="px-6 py-5">Tujuan</th>
                            <th class="px-6 py-5">Jadwal</th>
                            <th class="px-6 py-5">Status</th>
                            <th class="px-8 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-container-high">
                        <template x-if="filteredRiwayat.length === 0">
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center text-on-surface-variant font-medium">
                                    <span class="material-symbols-outlined text-4xl mb-2 text-outline-variant">search_off</span>
                                    <p>Tidak ada riwayat survei yang cocok dengan pencarian/filter.</p>
                                </td>
                            </tr>
                        </template>

                        <template x-for="item in filteredRiwayat" :key="item.id">
                            <tr class="hover:bg-surface/50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="font-bold text-on-surface mb-1" x-text="item.id"></div>
                                    <template x-if="item.is_transaksi">
                                        <div class="text-[10px] font-bold bg-primary/10 text-primary px-2 py-0.5 rounded-md uppercase tracking-wider inline-block" x-text="'Transaksi: TRX-' + item.id_transaksi"></div>
                                    </template>
                                    <template x-if="!item.is_transaksi">
                                        <div class="text-[10px] font-bold bg-surface-variant text-on-surface-variant px-2 py-0.5 rounded-md uppercase tracking-wider inline-block">Kunjungan Mandiri</div>
                                    </template>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-bold text-on-surface text-sm mb-1" x-text="item.tujuan"></div>
                                    <template x-if="item.status_class === 'cancel' && item.alasan_batal">
                                        <div class="text-xs text-error font-medium flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">info</span> <span x-text="item.alasan_batal"></span></div>
                                    </template>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm font-medium text-on-surface flex items-center gap-1.5 mb-1"><span class="material-symbols-outlined text-[16px] text-outline">calendar_month</span> <span x-text="item.tanggal"></span></div>
                                    <div class="text-xs text-on-surface-variant flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px] text-outline">schedule</span> <span x-text="item.waktu + ' WIB'"></span></div>
                                </td>
                                <td class="px-6 py-5">
                                    <span :class="item.status_class === 'success' ? 'bg-status-success-bg text-status-success border-status-success/20' : 'bg-status-cancel-bg text-status-cancel border-status-cancel/20'"
                                          class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold tracking-wide uppercase border">
                                        <span :class="item.status_class === 'success' ? 'bg-status-success' : 'bg-status-cancel'" class="w-1.5 h-1.5 rounded-full"></span>
                                        <span x-text="item.status === 'Batal' ? 'Dibatalkan' : item.status"></span>
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <button @click="detailData = item; modalDetail = true" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-surface-container-low text-primary hover:bg-primary/10 transition-all" title="Lihat Detail">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-surface-container-high bg-surface-container-lowest text-center">
                 <p class="text-sm text-on-surface-variant font-medium">Menampilkan <span class="font-bold text-on-surface" x-text="filteredRiwayat.length"></span> riwayat</p>
            </div>
        </div>
    </section>

    <!-- Modal: Detail Survei -->
    <template x-teleport="body">
        <div x-show="modalDetail" style="display: none;" class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm transition-opacity" @click="modalDetail = false"></div>

            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
                    <div class="px-8 py-6 border-b border-surface-container-high flex justify-between items-center bg-surface-container-lowest shrink-0">
                        <h3 class="text-xl font-extrabold text-on-surface">Detail Kunjungan</h3>
                        <button @click="modalDetail = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-container-low text-on-surface-variant hover:bg-surface-variant transition-all">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>

                    <div class="p-8 overflow-y-auto no-scrollbar space-y-6">
                        <!-- Status & ID -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-surface-container-high gap-4">
                            <div>
                                <p class="text-xs font-bold text-outline uppercase tracking-widest mb-1">Kode Registrasi Kunjungan</p>
                                <h2 class="text-xl font-extrabold text-on-surface" x-text="detailData.id"></h2>
                                <template x-if="detailData.is_transaksi">
                                    <div class="mt-1 text-xs text-on-surface-variant font-medium flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[14px]">link</span> Terhubung Transaksi: <a :href="'{{ route('transaksi.riwayat') }}'" class="text-primary hover:underline font-bold" x-text="'TRX-' + detailData.id_transaksi"></a>
                                    </div>
                                </template>
                            </div>
                            <span :class="detailData.status_class === 'success' ? 'bg-status-success-bg text-status-success border-status-success/20' : 'bg-status-cancel-bg text-status-cancel border-status-cancel/20'"
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-extrabold tracking-wide uppercase border">
                                <span :class="detailData.status_class === 'success' ? 'bg-status-success' : 'bg-status-cancel'" class="w-1.5 h-1.5 rounded-full"></span>
                                <span x-text="detailData.status === 'Batal' ? 'Dibatalkan' : detailData.status"></span>
                            </span>
                        </div>

                        <!-- Jadwal & Lokasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-surface-container-low p-5 rounded-2xl border border-surface-container-high">
                                <div class="flex items-center gap-2 text-primary mb-2">
                                    <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                                    <h3 class="font-bold text-sm">Jadwal Pelaksanaan</h3>
                                </div>
                                <p class="text-lg font-extrabold text-on-surface mb-1" x-text="detailData.tanggal"></p>
                                <p class="text-on-surface-variant font-medium text-xs flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span> <span x-text="detailData.waktu + ' WIB'"></span>
                                </p>
                            </div>

                            <div class="bg-surface-container-low p-5 rounded-2xl border border-surface-container-high">
                                <div class="flex items-center gap-2 text-primary mb-2">
                                    <span class="material-symbols-outlined text-[20px]">location_on</span>
                                    <h3 class="font-bold text-sm">Lokasi Peternakan</h3>
                                </div>
                                <p class="text-sm font-extrabold text-on-surface mb-1">Farm Smart-Saka Pusat</p>
                                <p class="text-on-surface-variant font-medium text-xs leading-relaxed">Tanjungsari, Glundengan, Kec. Wuluhan, Kabupaten Jember, Jawa Timur</p>
                            </div>
                        </div>

                        <!-- Tujuan -->
                        <div class="bg-surface-container-low p-5 rounded-2xl border border-surface-container-high">
                            <div class="flex items-center gap-2 text-primary mb-3">
                                <span class="material-symbols-outlined text-[20px]">pest_control</span>
                                <h3 class="font-bold text-sm">Tujuan Kunjungan</h3>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3 items-center">
                                <div class="w-14 h-14 bg-surface-variant rounded-xl flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-3xl text-outline">handshake</span>
                                </div>
                                <div>
                                    <p class="text-base font-extrabold text-on-surface mb-1" x-text="detailData.tujuan"></p>
                                    <template x-if="detailData.catatan">
                                        <p class="text-on-surface-variant font-medium text-xs" x-text="'Catatan: &quot;' + detailData.catatan + '&quot;'"></p>
                                    </template>
                                    <template x-if="detailData.status_class === 'cancel' && detailData.alasan_batal">
                                        <p class="text-error font-bold text-xs mt-2" x-text="'Alasan Batal: ' + detailData.alasan_batal"></p>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Laporan -->
                        <div :class="detailData.status_class === 'success' ? 'bg-surface-container-lowest shadow-sm' : 'bg-status-cancel-bg/50 border-error/20'"
                             class="border border-surface-container-high p-4 rounded-xl flex gap-3 items-start mt-4">
                            <span :class="detailData.status_class === 'success' ? 'text-status-success' : 'text-error'" class="material-symbols-outlined text-[20px]" x-text="detailData.status_class === 'success' ? 'check_circle' : 'cancel'"></span>
                            <div>
                                <h4 class="font-bold text-on-surface text-sm mb-1" x-text="detailData.status_class === 'success' ? 'Kunjungan Berhasil Diselesaikan' : 'Kunjungan Dibatalkan'"></h4>
                                <p class="text-xs text-on-surface-variant font-medium" x-text="detailData.status_class === 'success' ? 'Tim kami telah mendampingi Anda selama proses survei. Jika Anda puas dan ingin melanjutkan proses transaksi/kemitraan, silakan kembali ke halaman Transaksi Anda.' : 'Kunjungan ini telah dibatalkan dan tidak dapat dipulihkan. Anda dapat mengajukan jadwal kunjungan baru jika diperlukan.'"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</main>
@endsection
