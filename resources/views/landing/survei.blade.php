@extends('layouts.landing')

@section('title', 'Survei & Kunjungan | Smart-Saka Premium Sheep Farm')

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
                    "inverse-surface": "#2f312e", "inverse-on-surface": "#f1f1ec",
                    "status-pending": "#d97706", "status-pending-bg": "#fef3c7",
                    "status-success": "#059669", "status-success-bg": "#d1fae5",
                    "status-process": "#2563eb", "status-process-bg": "#dbeafe",
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
    /* Penyesuaian navbar agar selaras dengan tailwind cdn */
    nav { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
<x-landing.navbar />

<main class="pt-24 flex-1 text-on-surface" x-data="{ modalTambah: false, modalEdit: false, modalBatal: false, modalDetail: false, editData: {}, batalId: null, detailData: {} }">

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="fixed top-28 left-1/2 -translate-x-1/2 z-[100] bg-status-success text-white px-6 py-3 rounded-full shadow-lg flex items-center gap-2" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="fixed top-28 left-1/2 -translate-x-1/2 z-[100] bg-error text-white px-6 py-3 rounded-full shadow-lg flex items-center gap-2" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
            <span class="material-symbols-outlined text-[20px]">error</span>
            <span class="font-bold text-sm">{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- Header & Hero -->
    <header class="relative bg-surface-container-low pt-12 pb-20 px-8 overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: url('{{ asset('images/katalog.jpg') }}'); background-size: cover; background-position: center;"></div>
        <div class="max-w-7xl mx-auto relative z-10">
            <nav class="flex items-center space-x-2 text-sm text-on-surface-variant mb-6 tracking-wide">
                <a href="{{ route('home') }}" class="hover:text-primary">Beranda</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-primary font-semibold">Survei & Kunjungan</span>
            </nav>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-primary mb-4 tracking-tight leading-tight">Survei & Kunjungan</h1>
                    <p class="text-on-surface-variant max-w-xl text-lg">Kelola jadwal kunjungan Anda, ajukan survei mandiri, atau pantau riwayat kunjungan yang telah selesai.</p>
                </div>
            </div>
        </div>
    </header>

    <section class="max-w-7xl mx-auto px-8 pt-8 mb-24 relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- LEFT COLUMN: Daftar Jadwal Aktif -->
        <div class="lg:col-span-2 space-y-6">
            <h2 class="text-2xl font-extrabold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">calendar_clock</span> Jadwal Aktif
            </h2>

            @forelse($jadwalAktif as $survei)
                @php
                    $isTransaksi = !is_null($survei->id_transaksi);
                    $formattedDate = \Carbon\Carbon::parse($survei->tgl_survei)->translatedFormat('d M Y');
                    $formattedTime = \Carbon\Carbon::parse($survei->tgl_survei)->format('H:i');
                    $statusColor = strtolower($survei->status) == 'pending' ? 'status-pending' : 'status-process';
                    $statusText = strtolower($survei->status) == 'pending' ? 'Menunggu Jadwal' : 'Jadwal Dikonfirmasi';
                    // Parse Keterangan if it contains Tujuan and Catatan
                    $ketRaw = $survei->ket;
                    $tujuan = "Kunjungan " . ($isTransaksi ? "Terkait Transaksi" : "Mandiri");
                    if (preg_match('/Tujuan:\s*(.*?)(?=\n|\||Catatan:|$)/i', $ketRaw, $matches)) {
                        $tujuan = trim($matches[1]);
                    }
                @endphp
                <div class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(61,103,0,0.06)] border border-surface-container-high p-6 md:p-8 hover:border-primary/30 transition-all">
                    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            @if($isTransaksi)
                                <span class="bg-secondary/10 text-secondary px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">link</span> TRX-{{ $survei->id_transaksi }}
                                </span>
                            @else
                                <span class="bg-primary/10 text-primary px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">Kunjungan Mandiri</span>
                            @endif
                            <span class="text-sm font-bold text-outline">SRV-{{ str_pad($survei->id_survei, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold tracking-wide uppercase bg-{{$statusColor}}-bg text-{{$statusColor}} border border-{{$statusColor}}/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-{{$statusColor}} {{ strtolower($survei->status) == 'pending' ? 'animate-pulse' : '' }}"></span> {{ $statusText }}
                        </span>
                    </div>

                    <h3 class="text-xl font-bold text-on-surface mb-4">{{ $tujuan }}</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div class="flex items-center gap-3 text-on-surface-variant font-medium text-sm">
                            <span class="material-symbols-outlined text-outline">calendar_month</span> {{ $formattedDate }}
                        </div>
                        <div class="flex items-center gap-3 text-on-surface-variant font-medium text-sm">
                            <span class="material-symbols-outlined text-outline">schedule</span> {{ $formattedTime }} WIB
                        </div>
                        <div class="flex items-center gap-3 text-on-surface-variant font-medium text-sm sm:col-span-2">
                            <span class="material-symbols-outlined text-outline">location_on</span> Farm Smart-Saka Pusat (Lembang)
                        </div>
                    </div>

                    @if($isTransaksi)
                        <!-- Alert 1x24 Jam -->
                        <div class="bg-status-pending-bg/50 border border-status-pending/20 p-3 rounded-xl flex gap-3 items-start mb-6">
                            <span class="material-symbols-outlined text-status-pending text-sm mt-0.5">info</span>
                            <p class="text-xs text-status-pending/80 font-medium leading-relaxed">Batas waktu perubahan jadwal atau pembatalan adalah 1x24 Jam sebelum waktu pelaksanaan.</p>
                        </div>
                    @endif

                    @php
                        $isMendekatiWaktu = \Carbon\Carbon::parse($survei->tgl_survei)->diffInHours(now(), false) > -24;
                    @endphp

                    <div class="flex gap-3 pt-6 border-t border-surface-container-high">
                        @if($isMendekatiWaktu)
                            <span class="text-xs text-error font-medium w-full text-center py-2 bg-error-container/50 rounded-xl">Batas waktu ubah/batal (1x24 Jam) telah habis.</span>
                        @else
                            <button @click="editData = {
                                id: {{ $survei->id_survei }},
                                tanggal: '{{ \Carbon\Carbon::parse($survei->tgl_survei)->format('Y-m-d') }}',
                                waktu: '{{ \Carbon\Carbon::parse($survei->tgl_survei)->format('H:i') }}'
                            }; modalEdit = true" class="flex-1 bg-surface-container-low text-primary font-bold py-3 rounded-xl hover:bg-primary hover:text-white transition-all text-sm">Ubah Jadwal</button>

                            <button @click="batalId = {{ $survei->id_survei }}; modalBatal = true" class="flex-1 bg-white border border-error/30 text-error font-bold py-3 rounded-xl hover:bg-error-container transition-all text-sm">Batalkan</button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-surface-container-lowest rounded-3xl border border-surface-container-high p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-outline-variant mb-3">event_busy</span>
                    <h3 class="text-lg font-bold text-on-surface mb-1">Belum ada jadwal aktif</h3>
                    <p class="text-sm text-on-surface-variant font-medium">Anda belum memiliki jadwal kunjungan mendatang.</p>
                </div>
            @endforelse
        </div>

        <!-- RIGHT COLUMN: Riwayat Survei -->
        <div class="space-y-6">
            <button @click="modalTambah = true"
            class="w-full flex items-center justify-center gap-2 bg-primary px-8 py-4 rounded-2xl shadow-lg shadow-primary/20 text-white font-bold hover:bg-primary-container transition-all active:scale-95 text-lg">
                <span class="material-symbols-outlined">add</span> Ajukan Kunjungan Baru
            </button>
            <h2 class="text-2xl font-extrabold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-outline">history</span> Riwayat
            </h2>

            @forelse($riwayatTerbaru as $riwayat)
                @php
                    $isTransaksi = !is_null($riwayat->id_transaksi);
                    $formattedDate = \Carbon\Carbon::parse($riwayat->tgl_survei)->translatedFormat('d M Y');
                    $isSelesai = strtolower($riwayat->status) == 'selesai';

                    $ketRaw = $riwayat->ket;
                    $tujuan = "Kunjungan " . ($isTransaksi ? "Terkait Transaksi" : "Mandiri");
                    if (preg_match('/Tujuan:\s*(.*?)(?=\n|\||Catatan:|$)/i', $ketRaw, $matches)) {
                        $tujuan = trim($matches[1]);
                    }
                    $alasanBatal = '';
                    if (!$isSelesai && preg_match('/Alasan Batal:\s*(.*?)$/i', $ketRaw, $matches)) {
                        $alasanBatal = trim($matches[1]);
                    }
                @endphp
                <div class="bg-surface-container-lowest rounded-3xl border border-surface-container-high p-6 opacity-80 hover:opacity-100 transition-opacity">
                    <div class="flex justify-between items-start mb-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wide uppercase {{ $isSelesai ? 'bg-status-success-bg text-status-success' : 'bg-status-cancel-bg text-status-cancel' }}">
                            {{ $isSelesai ? 'Selesai' : 'Dibatalkan' }}
                        </span>
                        <span class="text-xs font-bold text-outline">SRV-{{ str_pad($riwayat->id_survei, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <h3 class="text-sm font-bold text-on-surface mb-2">{{ $tujuan }}</h3>
                    <p class="text-xs text-on-surface-variant font-medium flex items-center gap-1.5 mb-1">
                        <span class="material-symbols-outlined text-[14px]">calendar_month</span> {{ $formattedDate }}
                    </p>
                    @if($isTransaksi)
                        <p class="text-xs text-on-surface-variant font-medium flex items-center gap-1.5 mb-2">
                            <span class="material-symbols-outlined text-[14px]">link</span> TRX-{{ $riwayat->id_transaksi }}
                        </p>
                    @endif
                    @if(!$isSelesai && $alasanBatal)
                        <p class="text-xs text-on-surface-variant font-medium flex items-center gap-1.5 text-error mb-2">
                            <span class="material-symbols-outlined text-[14px]">info</span> {{ $alasanBatal }}
                        </p>
                    @endif
                </div>
            @empty
                 <p class="text-sm text-on-surface-variant font-medium italic text-center mt-8">Belum ada riwayat.</p>
            @endforelse

            <a href="{{ route('kunjungan.riwayat') }}" class="block text-center w-full py-4 text-sm font-bold text-primary hover:bg-surface-container-low rounded-xl transition-all border border-transparent hover:border-primary/20">
                Lihat Semua Riwayat
            </a>
        </div>

    </section>

    <!-- Modal: New Survey -->
    <template x-teleport="body">
        <div x-show="modalTambah" style="display: none;" class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm transition-opacity" @click="modalTambah = false"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
                    <div class="px-8 py-6 border-b border-surface-container-high flex justify-between items-center bg-surface-container-lowest shrink-0">
                        <h3 class="text-xl font-extrabold text-on-surface">Ajukan Kunjungan Baru</h3>
                        <button @click="modalTambah = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-container-low text-on-surface-variant hover:bg-surface-variant transition-all">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('kunjungan.store') }}" class="p-8 overflow-y-auto no-scrollbar"
                        x-data="{
                            selectedDate: '',
                            selectedTime: '',
                            bookedTimes: [],
                            loadingJadwal: false,
                            fetchBookedTimes() {
                                if (!this.selectedDate) return;
                                this.loadingJadwal = true;
                                fetch('/api/jadwal/cek?tanggal=' + this.selectedDate)
                                    .then(res => res.json())
                                    .then(data => {
                                        this.bookedTimes = data;
                                        if(this.bookedTimes.includes(this.selectedTime)) {
                                            this.selectedTime = '';
                                        }
                                    })
                                    .finally(() => { this.loadingJadwal = false; });
                            }
                        }">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-on-surface mb-2">Tujuan Kunjungan <span class="text-error">*</span></label>
                            <div class="relative">
                                <select name="tujuan" class="w-full px-5 py-3.5 pr-10 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 text-sm font-bold text-on-surface appearance-none cursor-pointer outline-none" required>
                                    <option value="" disabled selected>Pilih tujuan...</option>
                                    <option value="1">Konsultasi Pembelian / Kemitraan</option>
                                    <option value="2">Melihat Hewan Kurban</option>
                                    <option value="3">Melihat Bibit Ternak</option>
                                    <option value="4">Lainnya</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline">expand_more</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-on-surface mb-3 flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">event_note</span> Pilih Tanggal Survei <span class="text-error">*</span></label>
                            <input type="text" name="tanggal_survei" required placeholder="Pilih Tanggal..."
                                x-model="selectedDate"
                                x-init="flatpickr($el, {
                                    dateFormat: 'Y-m-d', minDate: 'today', maxDate: new Date().fp_incr(7),
                                    onChange: function(selectedDates, dateStr) { selectedDate = dateStr; fetchBookedTimes(); }
                                })"
                                class="w-full px-5 py-3.5 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 text-sm font-bold text-on-surface outline-none mb-4 cursor-pointer">

                            <label class="block text-xs font-bold text-outline uppercase tracking-widest mb-3">Sesi Waktu Kunjungan <span class="text-error">*</span></label>
                            <input type="hidden" name="waktu_survei" x-model="selectedTime" required>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-2" :class="loadingJadwal ? 'opacity-50 pointer-events-none' : ''">
                                <template x-for="time in ['09:00', '11:00', '13:00', '15:00']" :key="time">
                                    <label class="cursor-pointer">
                                        <input type="radio" class="peer sr-only"
                                            :disabled="bookedTimes.includes(time) || loadingJadwal"
                                            @click="if(!bookedTimes.includes(time)) selectedTime = time">
                                        <div :class="{
                                                'bg-primary border-primary text-white': selectedTime === time,
                                                'bg-surface-variant border-surface-variant text-outline-variant cursor-not-allowed': bookedTimes.includes(time),
                                                'border-outline-variant text-on-surface-variant hover:border-primary/50': selectedTime !== time && !bookedTimes.includes(time)
                                            }"
                                            class="px-3 py-3 rounded-full border text-center text-sm font-bold transition-all shadow-sm" x-text="time"></div>
                                    </label>
                                </template>
                            </div>
                            <p x-show="loadingJadwal" class="text-xs text-primary font-medium mt-2 animate-pulse">Memeriksa ketersediaan...</p>
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-bold text-on-surface mb-2">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan" class="w-full px-5 py-3.5 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 text-sm font-medium text-on-surface outline-none resize-none" rows="3" placeholder="Contoh: Ingin bertemu dengan Pak Budi..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold text-lg hover:bg-primary-container active:scale-95 transition-all shadow-lg shadow-primary/20">
                            Kirim Pengajuan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <!-- Modal: Reschedule -->
    <template x-teleport="body">
        <div x-show="modalEdit" style="display: none;" class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm transition-opacity" @click="modalEdit = false"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
                    <div class="px-8 py-6 border-b border-surface-container-high flex justify-between items-center bg-surface-container-lowest">
                        <h3 class="text-xl font-extrabold text-on-surface">Ubah Jadwal Survei</h3>
                        <button @click="modalEdit = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-container-low text-on-surface-variant hover:bg-surface-variant transition-all">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>
                    <form method="POST" :action="'/kunjungan/' + editData.id" class="p-8"
                        x-data="{
                            selectedDate: '',
                            selectedTime: '',
                            bookedTimes: [],
                            loadingJadwal: false,
                            fetchBookedTimes() {
                                if (!this.selectedDate) return;
                                this.loadingJadwal = true;
                                fetch('/api/jadwal/cek?tanggal=' + this.selectedDate)
                                    .then(res => res.json())
                                    .then(data => {
                                        this.bookedTimes = data;
                                        if(this.bookedTimes.includes(this.selectedTime) && this.selectedTime !== editData.waktu) {
                                            this.selectedTime = '';
                                        }
                                    })
                                    .finally(() => { this.loadingJadwal = false; });
                            }
                        }"
                        x-init="$watch('modalEdit', val => {
                            if(val) { selectedDate = editData.tanggal; selectedTime = editData.waktu; fetchBookedTimes(); }
                        })">
                        @csrf
                        @method('PUT')
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-on-surface mb-3 flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">event_note</span> Pilih Tanggal Baru <span class="text-error">*</span></label>
                            <input type="text" name="tanggal_survei" required placeholder="Pilih Tanggal..."
                                x-init="$watch('modalEdit', val => {
                                    if(val) { setTimeout(() => flatpickr($el, { dateFormat: 'Y-m-d', minDate: 'today', maxDate: new Date().fp_incr(7), defaultDate: editData.tanggal, onChange: function(selectedDates, dateStr) { selectedDate = dateStr; fetchBookedTimes(); } }), 100) }
                                })"
                                class="w-full px-5 py-3.5 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/50 text-sm font-bold text-on-surface outline-none mb-4 cursor-pointer">

                            <label class="block text-xs font-bold text-outline uppercase tracking-widest mb-3">Sesi Waktu Kunjungan <span class="text-error">*</span></label>
                            <input type="hidden" name="waktu_survei" x-model="selectedTime" required>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-2" :class="loadingJadwal ? 'opacity-50 pointer-events-none' : ''">
                                <template x-for="time in ['09:00', '11:00', '13:00', '15:00']" :key="time">
                                    <label class="cursor-pointer">
                                        <input type="radio" class="peer sr-only"
                                            :disabled="(bookedTimes.includes(time) && time !== editData.waktu) || loadingJadwal"
                                            @click="if(!(bookedTimes.includes(time) && time !== editData.waktu)) selectedTime = time">
                                        <div :class="{
                                                'bg-primary border-primary text-white': selectedTime === time,
                                                'bg-surface-variant border-surface-variant text-outline-variant cursor-not-allowed': bookedTimes.includes(time) && time !== editData.waktu,
                                                'border-outline-variant text-on-surface-variant hover:border-primary/50': selectedTime !== time && !(bookedTimes.includes(time) && time !== editData.waktu)
                                            }"
                                            class="px-3 py-3 rounded-full border text-center text-sm font-bold transition-all shadow-sm" x-text="time"></div>
                                    </label>
                                </template>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold text-lg hover:bg-primary-container active:scale-95 transition-all">
                            Simpan Jadwal Baru
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <!-- Modal: Cancel Survey -->
    <template x-teleport="body">
        <div x-show="modalBatal" style="display: none;" class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm transition-opacity" @click="modalBatal = false"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-error/10">
                    <div class="p-8 text-center border-b border-surface-container-high">
                        <div class="w-16 h-16 rounded-full bg-error-container mx-auto flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-error text-3xl">warning</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-on-surface mb-2">Batalkan Survei?</h3>
                        <p class="text-sm text-on-surface-variant font-medium">Aksi ini tidak dapat diurungkan. Anda harus membuat jadwal survei baru jika berubah pikiran.</p>
                    </div>
                    <form method="POST" :action="'/kunjungan/' + batalId" class="p-8 bg-surface-container-lowest">
                        @csrf
                        @method('DELETE')
                        <div class="mb-8">
                            <label class="block text-sm font-bold text-on-surface mb-2 text-left">Alasan Pembatalan <span class="text-error">*</span></label>
                            <div class="relative text-left">
                                <select name="alasan" class="w-full px-5 py-3.5 pr-10 rounded-xl bg-surface-container-low border-none focus:ring-2 focus:ring-error/50 text-sm font-bold text-on-surface appearance-none cursor-pointer outline-none" required>
                                    <option value="" disabled selected>Pilih alasan...</option>
                                    <option value="1">Jadwal Bentrok</option>
                                    <option value="2">Sudah Beli di Tempat Lain</option>
                                    <option value="3">Berubah Pikiran</option>
                                    <option value="4">Lokasi Terlalu Jauh</option>
                                    <option value="5">Lainnya</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline">expand_more</span>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="modalBatal = false" class="flex-1 bg-surface-container-high text-on-surface font-bold py-3.5 rounded-xl hover:bg-surface-variant active:scale-95 transition-all">
                                Kembali
                            </button>
                            <button type="submit" class="flex-1 bg-error text-white font-bold py-3.5 rounded-xl hover:bg-[#a41717] active:scale-95 transition-all flex items-center justify-center gap-2">
                                Batalkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Flatpickr ID localization setup
        flatpickr.localize(flatpickr.l10ns.id);

        // Auto-open modal logic for "?action=tambah"
        if(new URLSearchParams(window.location.search).get('action') === 'tambah') {
            const mainEl = document.querySelector('main[x-data]');
            if(mainEl && mainEl.__x) {
                mainEl.__x.$data.modalTambah = true;
                // Remove parameter from URL to prevent reopening on reload
                window.history.replaceState({}, document.title, window.location.pathname);
            } else {
                // Alpine might not be initialized yet, dispatch custom event
                setTimeout(() => {
                    if(mainEl.__x) mainEl.__x.$data.modalTambah = true;
                }, 500);
            }
        }
    });
</script>
@endpush
