{{--
|--------------------------------------------------------------------------
| View: resources/views/landing/form-pesanan.blade.php
|--------------------------------------------------------------------------
| Halaman Form Pemesanan — 3-Step Wizard (Landing UI, Material 3)
| Route: GET /pesan  → TransaksiController@createPesananUser
|        POST /pesan → TransaksiController@storePesananUser
|
| Data dari Controller:
|   $jenis_ternak : Collection produk tersedia (dari value.json + DB Ternak)
|   $ongkirInfo   : Array { jarak_km, ongkir, dalam_jangkauan } | null
--}}

@extends('layouts.landing')

@section('title', 'Form Pemesanan | Smart-Saka Premium Sheep Farm')

@push('head')
<style>
    /* Step transition */
    .step-section { display: none; }
    .step-section.active {
        display: block;
        animation: fadeUp 0.4s cubic-bezier(.4,0,.2,1);
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Underline input style (M3 inspired) */
    .input-line {
        border: none;
        border-bottom: 2px solid var(--color-m3-outline-variant);
        border-radius: 0;
        background: transparent;
        padding: 8px 0;
        width: 100%;
        font-size: 0.95rem;
        color: var(--color-m3-on-surface);
        transition: border-color 0.25s;
        outline: none;
    }
    .input-line:focus { border-color: var(--color-m3-primary); }
    .input-line option { background: white; color: #1a1c19; }

    /* Calendar */
    .cal-day { border-radius: 9999px; transition: background 0.15s, color 0.15s; }
    .cal-day:not(.past):hover { background: var(--color-m3-primary-fixed); color: var(--color-m3-on-surface); }
    .cal-day.selected { background: var(--color-m3-primary); color: #fff; font-weight: 700; }
    .cal-day.past { opacity: 0.28; cursor: not-allowed; }
    .cal-day.booked { background: var(--color-m3-error-container); color: var(--color-m3-on-error-container); cursor: not-allowed; }

    /* Method card active */
    .method-card { cursor: pointer; transition: border-color 0.2s, box-shadow 0.2s, background 0.2s; }
    .method-card.active {
        border-color: var(--color-m3-primary) !important;
        background: rgba(61,103,0,0.05);
        box-shadow: 0 0 0 2px var(--color-m3-primary);
    }

    /* Step indicator line fill */
    .step-line { background: var(--color-m3-outline-variant); height: 2px; transition: background 0.3s; }
    .step-line.done { background: var(--color-m3-primary); }

    /* Time slot button */
    .time-slot { transition: background 0.2s, color 0.2s, border-color 0.2s; }
    .time-slot.selected { background: var(--color-m3-primary); color: #fff; border-color: var(--color-m3-primary); }
    .time-slot.booked  { background: var(--color-m3-error-container); color: var(--color-m3-on-error-container); border-color: transparent; cursor: not-allowed; opacity: 0.65; }

    
</style>
@endpush

@section('content')

<x-landing.navbar />

{{-- ── HERO BANNER ── --}}
<header class="bg-m3-surface-container-low pt-36 pb-28 px-8 overflow-hidden relative">
    <div class="max-w-7xl mx-auto relative z-10">
        {{-- Breadcrumb --}}
        <nav class="flex items-center flex-wrap gap-x-2 gap-y-1 text-sm font-label text-m3-on-surface-variant mb-6 tracking-wide">
            <a href="{{ route('home') }}" class="hover:text-m3-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="{{ route('katalog') }}" class="hover:text-m3-primary transition-colors">Katalog Domba</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-m3-primary font-semibold">Pemesanan</span>
        </nav>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-8">
            <div>
                <h1 class="text-5xl md:text-7xl font-bold font-headline text-m3-primary mb-4 tracking-tight leading-tight">
                    Form Pemesanan
                </h1>
                <p class="text-m3-on-surface-variant max-w-xl text-lg leading-relaxed">
                    Lengkapi detail pemesanan di bawah ini untuk mengamankan unit domba pilihan Anda.
                    Tim kami akan memverifikasi dalam 24 jam.
                </p>
            </div>
            <div class="flex flex-wrap gap-3 shrink-0">
                <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm text-sm font-semibold text-m3-primary">
                    <span class="material-symbols-outlined text-sm">security</span> Data Aman
                </div>
                <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm text-sm font-semibold text-m3-primary">
                    <span class="material-symbols-outlined text-sm">chat_bubble</span> Konfirmasi WhatsApp
                </div>
                <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm text-sm font-semibold text-m3-primary">
                    <span class="material-symbols-outlined text-sm">verified</span> Stok Terjamin
                </div>
            </div>
        </div>
    </div>
    {{-- decorative blob --}}
    <span class="material-symbols-outlined absolute -right-8 -bottom-6 text-[18rem] text-m3-primary/5 select-none pointer-events-none"
        style="font-variation-settings:'FILL' 0,'wght' 200;">pets</span>
</header>

{{-- ── ERROR BANNER ── --}}
@if ($errors->any())
<div class="max-w-7xl mx-auto px-8 mt-6">
    <div class="rounded-2xl bg-m3-error-container border border-m3-error/20 p-4 text-sm text-m3-on-error-container flex gap-3 items-start">
        <span class="material-symbols-outlined text-m3-error mt-0.5 shrink-0">error</span>
        <ul class="space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

{{-- ── MAIN CONTENT ── --}}
<div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-10 mb-28 relative z-10"
    x-data='{
        /* ── Raw product data from controller ── */
        rawData: @json($jenis_ternak),
        ongkirInfo: @json($ongkirInfo),

        /* ── Step state ── */
        currentStep: 1,

        /* ── Step 1: Product selection ── */
        selectedKategori: "",
        selectedKelas: "",
        selectedKey: "",
        jumlah: 1,
        layanan: "langsung",

        /* ── Step 2a: Survey ── */
        calMonth: new Date().getMonth(),
        calYear: new Date().getFullYear(),
        selectedDate: null,
        selectedDateStr: "",
        selectedTime: "",
        bookedTimes: [],
        loadingJadwal: false,
        ketSurvei: "",

        /* ── Step 2b: Langsung ── */
        metodePengiriman: "ambil_sendiri",
        metodePembayaran: "transfer",
        fileName: "",

        /* ── Step validation ── */
        errors: {},

        /* ── Init: auto-fill from URL params ── */
        init() {
            const params = new URLSearchParams(window.location.search);
            const jenisParam  = params.get("jenis");
            const kelaminParam = params.get("kelamin");
            const hargaParam  = params.get("harga");

            if (jenisParam && kelaminParam && hargaParam) {
                const match = this.rawData.find(item =>
                    String(item.id_jenis) === jenisParam &&
                    (item.jenis_kelamin || "").toLowerCase() === kelaminParam.toLowerCase() &&
                    String(item.harga) === hargaParam
                );
                if (match) {
                    this.selectedKategori = match.nama_produk;
                    this.$nextTick(() => {
                        this.selectedKelas = match.kelas_berat;
                        this.$nextTick(() => {
                            this.selectedKey = this.makeKey(match);
                        });
                    });
                }
            }

            /* respect ongkir default to dikirim if in range */
            if (this.ongkirInfo && this.ongkirInfo.dalam_jangkauan) {
                this.metodePengiriman = "dikirim";
            }

            this.renderCalendar();
        },

        /* ── Helpers ── */
        makeKey(item) {
            return item.nama_produk + "_" + item.kelas_berat + "_" + item.jenis_kelamin + "_" + item.harga;
        },

        /* ── Computed ── */
        get kategoriOptions() {
            return [...new Set(this.rawData.map(i => i.nama_produk))];
        },
        get kelasOptions() {
            if (!this.selectedKategori) return [];
            return [...new Set(this.rawData
                .filter(i => i.nama_produk === this.selectedKategori)
                .map(i => i.kelas_berat))];
        },
        get genderOptions() {
            if (!this.selectedKelas) return [];
            return this.rawData.filter(i =>
                i.nama_produk === this.selectedKategori &&
                i.kelas_berat === this.selectedKelas
            );
        },
        get selectedItem() {
            if (!this.selectedKey) return null;
            return this.rawData.find(i => this.makeKey(i) === this.selectedKey) || null;
        },
        get totalHarga() {
            return this.selectedItem ? this.selectedItem.harga * this.jumlah : 0;
        },
        get ongkirNominal() {
            if (this.metodePengiriman === "ambil_sendiri") return 0;
            return this.ongkirInfo ? this.ongkirInfo.ongkir : 0;
        },
        get grandTotal() { return this.totalHarga + this.ongkirNominal; },
        get dalamJangkauan() {
            return this.ongkirInfo ? this.ongkirInfo.dalam_jangkauan : false;
        },
        get isSurvei() { return this.layanan === "survei"; },

        formatRupiah(n) {
            return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", minimumFractionDigits: 0 }).format(n || 0);
        },

        /* ── Step navigation ── */
        validateStep1() {
            this.errors = {};
            if (!this.selectedKategori) this.errors.kategori = "Kategori wajib dipilih";
            if (!this.selectedKelas)    this.errors.kelas    = "Kelas berat wajib dipilih";
            if (!this.selectedKey)      this.errors.kelamin  = "Jenis kelamin wajib dipilih";
            if (!this.jumlah || this.jumlah < 1) this.errors.jumlah = "Jumlah minimal 1";
            return Object.keys(this.errors).length === 0;
        },
        validateStep2() {
            this.errors = {};
            if (this.isSurvei) {
                if (!this.selectedDateStr) this.errors.tanggal = "Pilih tanggal survei";
                if (!this.selectedTime)    this.errors.waktu   = "Pilih sesi waktu";
            } else {
                if (!this.metodePengiriman) this.errors.pengiriman = "Pilih metode pengiriman";
                if (!this.metodePembayaran) this.errors.pembayaran = "Pilih metode pembayaran";
            }
            return Object.keys(this.errors).length === 0;
        },
        goStep(n) {
            if (n === 2 && this.currentStep === 1 && !this.validateStep1()) return;
            if (n === 3 && this.currentStep === 2 && !this.validateStep2()) return;
            this.currentStep = n;
            window.scrollTo({ top: 0, behavior: "smooth" });
        },

        /* ── Calendar ── */
        monthNames: ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],
        calDays: [],
        renderCalendar() {
            const today = new Date(); today.setHours(0,0,0,0);
            const maxDate = new Date(today); maxDate.setDate(today.getDate() + 7);
            const first = new Date(this.calYear, this.calMonth, 1).getDay();
            const total = new Date(this.calYear, this.calMonth + 1, 0).getDate();
            const days = [];
            for (let i = 0; i < first; i++) days.push(null);
            for (let d = 1; d <= total; d++) {
                const date = new Date(this.calYear, this.calMonth, d);
                days.push({
                    d,
                    past: date < today || date > maxDate,
                    selected: this.selectedDate && date.getTime() === this.selectedDate.getTime(),
                    dateObj: date
                });
            }
            this.calDays = days;
        },
        changeMonth(dir) {
            this.calMonth += dir;
            if (this.calMonth > 11) { this.calMonth = 0; this.calYear++; }
            if (this.calMonth < 0)  { this.calMonth = 11; this.calYear--; }
            this.renderCalendar();
        },
        pickDate(day) {
            if (day.past) return;
            this.selectedDate    = day.dateObj;
            this.selectedDateStr = this.calYear + "-" + String(this.calMonth + 1).padStart(2,"0") + "-" + String(day.d).padStart(2,"0");
            this.selectedTime    = "";
            this.renderCalendar();
            this.fetchBookedTimes();
        },
        async fetchBookedTimes() {
            if (!this.selectedDateStr) return;
            this.loadingJadwal = true;
            try {
                const res  = await fetch("/api/jadwal/cek?tanggal=" + this.selectedDateStr);
                const data = await res.json();
                this.bookedTimes = Array.isArray(data) ? data : [];
            } catch(e) {
                this.bookedTimes = [];
            } finally {
                this.loadingJadwal = false;
            }
        },
        isTimeBooked(t) { return this.bookedTimes.includes(t); },
        selectTime(t) {
            if (this.isTimeBooked(t)) return;
            this.selectedTime = t;
        },
        get timeSlots() {
            return ["09:00","11:00","13:00","15:00"];
        },

        /* ── Summary helpers ── */
        sumKategori()    { return this.selectedItem ? this.selectedItem.nama_produk : "-"; },
        sumKelas()       { return this.selectedItem ? this.selectedItem.kelas_berat : "-"; },
        sumKelamin()     { return this.selectedItem ? this.selectedItem.jenis_kelamin : "-"; },
        sumJumlah()      { return this.jumlah + " Ekor"; },
        sumTanggal()     { return this.selectedDateStr || "-"; },
        sumWaktu()       { return this.selectedTime || "-"; },
        sumPengiriman()  { return this.metodePengiriman === "dikirim" ? "Kirim ke Alamat" : "Ambil Langsung"; },
        sumPembayaran()  { return this.metodePembayaran === "transfer" ? "Transfer Bank" : "COD (Bayar di Tempat)"; },
    }'
    x-init="init()"
>

    {{-- ── STEP INDICATOR ── --}}
    <div class="bg-m3-surface-container-lowest rounded-3xl shadow-[0_20px_50px_rgba(61,103,0,0.07)] p-6 mb-10 overflow-x-auto">
        <div class="flex justify-between items-center min-w-[540px] px-4">

            <template x-for="(label, idx) in ['Detail Pesanan Ternak','Pengambilan & Pembayaran','Ringkasan']" :key="idx">
                <template x-if="true">
                    <div class="contents">
                        {{-- Step dot --}}
                        <div class="flex flex-col items-center gap-2"
                            :style="idx + 1 < currentStep ? 'opacity:1' : (idx + 1 > currentStep ? 'opacity:0.4' : 'opacity:1')">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all"
                                :class="idx + 1 <= currentStep ? 'bg-m3-primary text-white' : 'bg-m3-surface-container-high text-m3-on-surface'">
                                <template x-if="idx + 1 < currentStep">
                                    <span class="material-symbols-outlined text-sm">check</span>
                                </template>
                                <template x-if="idx + 1 >= currentStep">
                                    <span x-text="idx + 1"></span>
                                </template>
                            </div>
                            <span class="text-xs font-bold whitespace-nowrap"
                                :class="idx + 1 <= currentStep ? 'text-m3-primary' : 'text-m3-on-surface-variant'"
                                x-text="label"></span>
                        </div>
                        {{-- Connector line (not after last) --}}
                        <template x-if="idx < 2">
                            <div class="h-0.5 flex-1 mx-4 step-line transition-all"
                                :class="idx + 1 < currentStep ? 'done' : ''"></div>
                        </template>
                    </div>
                </template>
            </template>

        </div>
    </div>

    {{-- ── FORM WRAPPER ── --}}
    <form method="POST" action="{{ route('transaksi.create.store') }}" enctype="multipart/form-data"
        id="order-form"
        @submit.prevent="$el.submit()">
        @csrf

        {{-- Hidden fields synced to Alpine state --}}
        <input type="hidden" name="id_jenis_ternak"       :value="selectedItem?.id_jenis || ''">
        <input type="hidden" name="jenis_kelamin_pesanan" :value="selectedItem?.jenis_kelamin || ''">
        <input type="hidden" name="total_harga"           :value="totalHarga">
        <input type="hidden" name="total_jumlah"          :value="jumlah">
        <input type="hidden" name="metode_pengiriman"     :value="isSurvei ? 'ambil_sendiri' : metodePengiriman">
        <input type="hidden" name="is_survei"             :value="isSurvei ? 1 : 0">
        <input type="hidden" name="tanggal_survei"        :value="selectedDateStr">
        <input type="hidden" name="waktu_survei"          :value="selectedTime">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">

            {{-- ═══════════════════ LEFT COLUMN: STEPS ═══════════════════ --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- ═════════ STEP 1: Detail Pesanan Ternak ═════════ --}}
                <section :class="currentStep === 1 ? 'step-section active' : 'step-section'"
                    class="bg-m3-surface-container-lowest p-8 md:p-10 rounded-3xl shadow-sm">

                    <div class="flex items-center gap-4 mb-8">
                        <span class="material-symbols-outlined text-m3-primary text-3xl" style="font-variation-settings:'FILL' 1">pets</span>
                        <h2 class="text-2xl font-bold font-headline text-m3-primary">Detail Pesanan Ternak</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-8">

                        {{-- Kategori Produk --}}
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-[10px] uppercase tracking-widest font-bold text-m3-on-surface-variant">
                                Pilih Kategori Produk <span class="text-m3-error">*</span>
                            </label>
                            <select x-model="selectedKategori" @change="selectedKelas = ''; selectedKey = ''; jumlah = 1"
                                class="input-line">
                                <option value="">-- Pilih Kategori (Breed + Usia) --</option>
                                <template x-for="kat in kategoriOptions" :key="kat">
                                    <option :value="kat" x-text="kat"></option>
                                </template>
                            </select>
                            <p x-show="errors.kategori" x-text="errors.kategori"
                                class="text-[11px] text-m3-error mt-1"></p>
                        </div>

                        {{-- Kelas Berat --}}
                        <div class="space-y-1.5">
                            <label class="text-[10px] uppercase tracking-widest font-bold text-m3-on-surface-variant">
                                Kelas Berat <span class="text-m3-error">*</span>
                            </label>
                            <select x-model="selectedKelas" @change="selectedKey = ''; jumlah = 1"
                                :disabled="!selectedKategori" class="input-line disabled:opacity-40">
                                <option value="">-- Pilih Kelas --</option>
                                <template x-for="kls in kelasOptions" :key="kls">
                                    <option :value="kls" x-text="kls"></option>
                                </template>
                            </select>
                            <p x-show="errors.kelas" x-text="errors.kelas" class="text-[11px] text-m3-error mt-1"></p>
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="space-y-1.5">
                            <label class="text-[10px] uppercase tracking-widest font-bold text-m3-on-surface-variant">
                                Jenis Kelamin <span class="text-m3-error">*</span>
                            </label>
                            <select x-model="selectedKey" :disabled="!selectedKelas" class="input-line disabled:opacity-40">
                                <option value="">-- Pilih Kelamin --</option>
                                <template x-for="opt in genderOptions" :key="makeKey(opt)">
                                    <option :value="makeKey(opt)"
                                        x-text="opt.jenis_kelamin.toUpperCase() + ' — ' + formatRupiah(opt.harga) + ' (Stok: ' + opt.stok + ')'">
                                    </option>
                                </template>
                            </select>
                            <p x-show="errors.kelamin" x-text="errors.kelamin" class="text-[11px] text-m3-error mt-1"></p>
                        </div>

                        {{-- Jumlah --}}
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-[10px] uppercase tracking-widest font-bold text-m3-on-surface-variant">
                                Jumlah Pesanan <span class="text-m3-error">*</span>
                            </label>
                            <input type="number" x-model.number="jumlah" min="1"
                                :max="selectedItem?.stok || 99"
                                :disabled="!selectedKey"
                                @input="if(selectedItem && jumlah > selectedItem.stok) jumlah = selectedItem.stok"
                                class="input-line disabled:opacity-40"
                                placeholder="Masukkan jumlah pesanan">
                            <p x-show="errors.jumlah" x-text="errors.jumlah" class="text-[11px] text-m3-error mt-1"></p>
                            <p x-show="selectedItem" class="text-xs text-m3-outline mt-1"
                                x-text="'Stok tersedia: ' + (selectedItem?.stok || 0) + ' ekor'"></p>
                        </div>

                        {{-- Opsi Layanan --}}
                        <div class="md:col-span-2 pt-6 border-t border-m3-surface-container-high">
                            <p class="text-[10px] uppercase tracking-widest font-bold text-m3-on-surface-variant mb-4">
                                Opsi Layanan <span class="text-m3-error">*</span>
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- Survei --}}
                                <label @click="layanan = 'survei'"
                                    :class="layanan === 'survei' ? 'border-m3-primary bg-m3-primary/5 ring-1 ring-m3-primary' : 'border-m3-outline-variant'"
                                    class="flex items-start gap-4 p-5 rounded-2xl border-2 cursor-pointer transition-all">
                                    <input type="radio" name="_layanan_display" value="survei" x-model="layanan" class="sr-only">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                                        :class="layanan === 'survei' ? 'bg-m3-primary text-white' : 'bg-m3-surface-container text-m3-outline'">
                                        <span class="material-symbols-outlined text-xl">calendar_month</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-m3-on-surface">Survei Lokasi</p>
                                        <p class="text-xs text-m3-on-surface-variant mt-0.5">Jadwalkan kunjungan ke peternakan</p>
                                    </div>
                                </label>

                                {{-- Langsung --}}
                                <label @click="layanan = 'langsung'"
                                    :class="layanan === 'langsung' ? 'border-m3-primary bg-m3-primary/5 ring-1 ring-m3-primary' : 'border-m3-outline-variant'"
                                    class="flex items-start gap-4 p-5 rounded-2xl border-2 cursor-pointer transition-all">
                                    <input type="radio" name="_layanan_display" value="langsung" x-model="layanan" class="sr-only">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                                        :class="layanan === 'langsung' ? 'bg-m3-primary text-white' : 'bg-m3-surface-container text-m3-outline'">
                                        <span class="material-symbols-outlined text-xl">shopping_cart</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-m3-on-surface">Langsung Beli</p>
                                        <p class="text-xs text-m3-on-surface-variant mt-0.5">Pilih pengiriman & pembayaran</p>
                                    </div>
                                </label>

                            </div>
                        </div>
                    </div>

                    {{-- Step 1 Navigation --}}
                    <div class="flex justify-between items-center pt-10">
                        <a href="{{ route('katalog') }}"
                            class="text-m3-primary font-bold flex items-center gap-2 hover:-translate-x-1 transition-transform text-sm">
                            <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali ke Katalog
                        </a>
                        <button type="button" @click="goStep(2)"
                            class="bg-m3-primary text-m3-on-primary px-10 py-4 rounded-full font-bold text-base shadow-lg hover:bg-m3-primary-container active:scale-95 transition-all flex items-center gap-2">
                            Lanjutkan <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </div>
                </section>


                {{-- ═════════ STEP 2: Pengambilan / Survei ═════════ --}}
                <section :class="currentStep === 2 ? 'step-section active' : 'step-section'"
                    class="bg-m3-surface-container-lowest p-8 md:p-10 rounded-3xl shadow-sm">

                    {{-- Dynamic title --}}
                    <div class="flex items-center gap-4 mb-8">
                        <span class="material-symbols-outlined text-m3-primary text-3xl"
                            x-text="isSurvei ? 'calendar_month' : 'local_shipping'"
                            style="font-variation-settings:'FILL' 1"></span>
                        <h2 class="text-2xl font-bold font-headline text-m3-primary"
                            x-text="isSurvei ? 'Jadwal Survei Lokasi' : 'Pengambilan & Pembayaran'"></h2>
                    </div>

                    {{-- ─── FLOW SURVEI ─── --}}
                    <div x-show="isSurvei" class="space-y-8">

                        {{-- Calendar --}}
                        <div>
                            <p class="text-[10px] uppercase tracking-widest font-bold text-m3-on-surface-variant mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-m3-primary text-sm">calendar_month</span>
                                Pilih Tanggal Survei <span class="text-m3-error">*</span>
                                <span class="normal-case font-normal text-m3-outline">(maks. 7 hari ke depan)</span>
                            </p>
                            <div class="bg-m3-surface-container-low rounded-2xl p-6">
                                {{-- Month nav --}}
                                <div class="flex justify-between items-center mb-5">
                                    <button type="button" @click="changeMonth(-1)"
                                        class="w-9 h-9 rounded-full hover:bg-m3-surface-variant flex items-center justify-center transition-colors">
                                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                                    </button>
                                    <span class="font-bold text-m3-primary text-base"
                                        x-text="monthNames[calMonth] + ' ' + calYear"></span>
                                    <button type="button" @click="changeMonth(1)"
                                        class="w-9 h-9 rounded-full hover:bg-m3-surface-variant flex items-center justify-center transition-colors">
                                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                                    </button>
                                </div>

                                {{-- Day headers --}}
                                <div class="grid grid-cols-7 gap-1 text-center text-[11px] font-bold text-m3-on-surface-variant mb-2">
                                    <template x-for="h in ['Min','Sen','Sel','Rab','Kam','Jum','Sab']">
                                        <div class="py-1.5" x-text="h"></div>
                                    </template>
                                </div>

                                {{-- Days --}}
                                <div class="grid grid-cols-7 gap-1 text-center text-sm">
                                    <template x-for="(day, i) in calDays" :key="i">
                                        <div>
                                            <template x-if="day === null">
                                                <div></div>
                                            </template>
                                            <template x-if="day !== null">
                                                <button type="button"
                                                    @click="pickDate(day)"
                                                    :disabled="day.past"
                                                    :class="{
                                                        'cal-day selected': day.selected,
                                                        'cal-day past': day.past,
                                                        'cal-day': !day.past && !day.selected
                                                    }"
                                                    class="w-8 h-8 mx-auto flex items-center justify-center text-sm transition-all"
                                                    x-text="day.d">
                                                </button>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Selected date display --}}
                            <div x-show="selectedDateStr" class="mt-3 flex items-center gap-2 text-sm font-bold text-m3-primary">
                                <span class="material-symbols-outlined text-sm">event</span>
                                Tanggal dipilih:
                                <span x-text="selectedDate ? selectedDate.getDate() + ' ' + monthNames[selectedDate.getMonth()] + ' ' + selectedDate.getFullYear() : ''"></span>
                            </div>
                            <p x-show="errors.tanggal" x-text="errors.tanggal" class="text-[11px] text-m3-error mt-1"></p>
                        </div>

                        {{-- Time slots --}}
                        <div>
                            <p class="text-[10px] uppercase tracking-widest font-bold text-m3-on-surface-variant mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-m3-primary text-sm">schedule</span>
                                Sesi Waktu Kunjungan <span class="text-m3-error">*</span>
                            </p>
                            <div x-show="loadingJadwal" class="text-xs text-m3-primary animate-pulse mb-3 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">hourglass_empty</span>
                                Memeriksa ketersediaan jadwal...
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3"
                                :class="loadingJadwal ? 'opacity-50 pointer-events-none' : ''">
                                <template x-for="t in timeSlots" :key="t">
                                    <button type="button" @click="selectTime(t)"
                                        :disabled="isTimeBooked(t) || !selectedDateStr"
                                        :class="{
                                            'time-slot selected': selectedTime === t,
                                            'time-slot booked':   isTimeBooked(t),
                                            'time-slot border-m3-outline-variant text-m3-on-surface hover:border-m3-primary': selectedTime !== t && !isTimeBooked(t)
                                        }"
                                        class="px-3 py-3 rounded-2xl border-2 text-xs font-bold transition-all disabled:cursor-not-allowed">
                                        <span class="material-symbols-outlined text-[16px] block mb-1"
                                            x-text="isTimeBooked(t) ? 'block' : 'schedule'"></span>
                                        <span x-text="t"></span>
                                        <span x-show="isTimeBooked(t)" class="block text-[9px] mt-0.5 opacity-70">Terisi</span>
                                    </button>
                                </template>
                            </div>
                            <p x-show="!selectedDateStr" class="text-[11px] text-m3-outline mt-2">Pilih tanggal terlebih dahulu</p>
                            <p x-show="errors.waktu" x-text="errors.waktu" class="text-[11px] text-m3-error mt-1"></p>
                        </div>

                        {{-- Keterangan --}}
                        <div class="space-y-1.5">
                            <label class="text-[10px] uppercase tracking-widest font-bold text-m3-on-surface-variant">
                                Keterangan (Opsional)
                            </label>
                            <textarea name="ket_survei" x-model="ketSurvei" rows="2"
                                class="input-line resize-none"
                                placeholder="Contoh: Ingin melihat langsung kualitas bulu dan berat badan..."></textarea>
                        </div>

                        {{-- Survei info --}}
                        <div class="p-4 bg-m3-primary-fixed/20 rounded-2xl flex gap-3 items-start">
                            <span class="material-symbols-outlined text-m3-primary text-xl shrink-0">info</span>
                            <p class="text-sm text-m3-on-surface-variant">
                                Jika memilih survei, <strong class="text-m3-primary">metode pembayaran</strong>
                                akan ditentukan <strong class="text-m3-primary">setelah survei selesai</strong>.
                                Anda memiliki <strong>24 jam</strong> setelah survei untuk menyelesaikan pembayaran.
                            </p>
                        </div>
                    </div>

                    {{-- ─── FLOW LANGSUNG ─── --}}
                    <div x-show="!isSurvei" class="space-y-8">

                        {{-- Metode Pengiriman --}}
                        <div>
                            <p class="text-[10px] uppercase tracking-widest font-bold text-m3-on-surface-variant mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-m3-primary text-sm">local_shipping</span>
                                Metode Pengambilan <span class="text-m3-error">*</span>
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- Ambil Sendiri --}}
                                <div @click="metodePengiriman = 'ambil_sendiri'"
                                    :class="metodePengiriman === 'ambil_sendiri' ? 'active' : 'border-m3-outline-variant'"
                                    class="method-card flex flex-col items-start gap-3 p-6 rounded-2xl border-2 transition-all">
                                    <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                                        :class="metodePengiriman === 'ambil_sendiri' ? 'bg-m3-primary text-white' : 'bg-m3-surface-container text-m3-outline'">
                                        <span class="material-symbols-outlined text-xl">store</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-m3-on-surface">Ambil Langsung</p>
                                        <p class="text-xs text-m3-on-surface-variant mt-0.5">Ke peternakan Sakadomas, Jember</p>
                                        <p class="text-xs font-bold text-m3-secondary mt-2">Gratis Ongkir</p>
                                    </div>
                                </div>

                                {{-- Kirim --}}
                                <div @click="dalamJangkauan ? metodePengiriman = 'dikirim' : null"
                                    :class="{
                                        'active': metodePengiriman === 'dikirim',
                                        'border-m3-outline-variant': metodePengiriman !== 'dikirim',
                                        'opacity-50 cursor-not-allowed': !dalamJangkauan
                                    }"
                                    class="method-card flex flex-col items-start gap-3 p-6 rounded-2xl border-2 transition-all">
                                    <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                                        :class="metodePengiriman === 'dikirim' ? 'bg-m3-primary text-white' : 'bg-m3-surface-container text-m3-outline'">
                                        <span class="material-symbols-outlined text-xl">local_shipping</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-m3-on-surface">Kirim ke Alamat</p>
                                        <p class="text-xs text-m3-on-surface-variant mt-0.5">Biaya menyesuaikan jarak</p>
                                        <template x-if="dalamJangkauan && ongkirInfo">
                                            <p class="text-xs font-bold text-m3-primary mt-2"
                                                x-text="formatRupiah(ongkirInfo.ongkir) + ' (' + ongkirInfo.jarak_km + ' km)'"></p>
                                        </template>
                                        <template x-if="!dalamJangkauan">
                                            <p class="text-xs text-m3-error mt-2">⚠ Di luar jangkauan pengiriman</p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div class="pt-6 border-t border-m3-surface-container-high">
                            <p class="text-[10px] uppercase tracking-widest font-bold text-m3-on-surface-variant mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-m3-primary text-sm">payments</span>
                                Metode Pembayaran <span class="text-m3-error">*</span>
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- Transfer --}}
                                <div @click="metodePembayaran = 'transfer'"
                                    :class="metodePembayaran === 'transfer' ? 'active' : 'border-m3-outline-variant'"
                                    class="method-card flex flex-col items-start gap-3 p-6 rounded-2xl border-2 transition-all">
                                    <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                                        :class="metodePembayaran === 'transfer' ? 'bg-m3-primary text-white' : 'bg-m3-surface-container text-m3-outline'">
                                        <span class="material-symbols-outlined text-xl">account_balance</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-m3-on-surface">Transfer Bank</p>
                                        <p class="text-xs text-m3-on-surface-variant mt-0.5">Pembayaran lunas via ATM/M-Banking</p>
                                    </div>
                                </div>

                                {{-- COD --}}
                                <div @click="metodePembayaran = 'cash'"
                                    :class="metodePembayaran === 'cash' ? 'active' : 'border-m3-outline-variant'"
                                    class="method-card flex flex-col items-start gap-3 p-6 rounded-2xl border-2 transition-all">
                                    <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                                        :class="metodePembayaran === 'cash' ? 'bg-m3-primary text-white' : 'bg-m3-surface-container text-m3-outline'">
                                        <span class="material-symbols-outlined text-xl">payments</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-m3-on-surface">Cash on Delivery</p>
                                        <p class="text-xs text-m3-on-surface-variant mt-0.5">Bayar di tempat setelah domba sampai</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Hidden metode_pembayaran input (only relevant if not survei) --}}
                            <input type="hidden" name="metode_pembayaran" :value="isSurvei ? '' : metodePembayaran">

                            {{-- Upload Bukti Transfer --}}
                            <div x-show="metodePembayaran === 'transfer'" x-transition
                                class="mt-6 p-6 bg-m3-surface-container-low rounded-2xl space-y-4">
                                <p class="text-sm font-bold text-m3-primary flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base">receipt_long</span>
                                    Upload Bukti Transfer <span class="text-m3-error">*</span>
                                </p>

                                {{-- Bank info --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="flex items-center gap-3 bg-white rounded-xl p-3 border border-m3-outline-variant/40">
                                        <div class="w-10 h-10 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center shrink-0">BRI</div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-m3-on-surface tracking-wider">0123 4567 8901</p>
                                            <p class="text-xs text-m3-outline">a.n. Peternakan Sakadomas</p>
                                        </div>
                                        <button type="button" @click="navigator.clipboard.writeText('01234567890123'); $el.textContent='✓'"
                                            class="text-xs text-m3-primary font-bold shrink-0">Salin</button>
                                    </div>
                                    <div class="flex items-center gap-3 bg-white rounded-xl p-3 border border-m3-outline-variant/40">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-600 text-white font-bold text-xs flex items-center justify-center shrink-0">BSI</div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-m3-on-surface tracking-wider">7654 3210 9876</p>
                                            <p class="text-xs text-m3-outline">a.n. Peternakan Sakadomas</p>
                                        </div>
                                        <button type="button" @click="navigator.clipboard.writeText('765432109876'); $el.textContent='✓'"
                                            class="text-xs text-m3-primary font-bold shrink-0">Salin</button>
                                    </div>
                                </div>

                                {{-- File upload --}}
                                <div class="flex items-center gap-3">
                                    <label for="uploadBukti"
                                        class="cursor-pointer inline-flex items-center gap-2 bg-m3-primary text-m3-on-primary px-5 py-2.5 rounded-full text-sm font-bold hover:bg-m3-primary-container transition-colors">
                                        <span class="material-symbols-outlined text-sm">upload</span> Pilih File
                                    </label>
                                    <input type="file" id="uploadBukti" name="bukti_pembayaran" accept="image/*"
                                        :required="!isSurvei && metodePembayaran === 'transfer'"
                                        class="hidden"
                                        @change="fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''">
                                    <span class="text-sm text-m3-outline italic truncate max-w-[200px]"
                                        x-text="fileName || 'Belum ada file'"></span>
                                </div>
                                <p class="text-[11px] text-m3-error font-medium">
                                    * Pesanan dengan bukti transfer yang sudah dikirim tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2 Navigation --}}
                    <div class="flex justify-between items-center pt-10">
                        <button type="button" @click="goStep(1)"
                            class="text-m3-primary font-bold flex items-center gap-2 hover:-translate-x-1 transition-transform text-sm">
                            <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
                        </button>
                        <button type="button" @click="goStep(3)"
                            class="bg-m3-primary text-m3-on-primary px-10 py-4 rounded-full font-bold text-base shadow-lg hover:bg-m3-primary-container active:scale-95 transition-all flex items-center gap-2">
                            Lihat Ringkasan <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </div>
                </section>


                {{-- ═════════ STEP 3: Ringkasan ═════════ --}}
                <section :class="currentStep === 3 ? 'step-section active' : 'step-section'"
                    class="bg-m3-surface-container-lowest p-8 md:p-10 rounded-3xl shadow-sm">

                    <div class="flex items-center gap-4 mb-8">
                        <span class="material-symbols-outlined text-m3-primary text-3xl" style="font-variation-settings:'FILL' 1">receipt_long</span>
                        <h2 class="text-2xl font-bold font-headline text-m3-primary">Ringkasan Pemesanan</h2>
                    </div>

                    <div class="space-y-5">

                        {{-- Detail Ternak --}}
                        <div class="bg-m3-surface-container-low rounded-2xl p-6 space-y-4">
                            <p class="text-[10px] uppercase tracking-widest font-bold text-m3-primary">Detail Pesanan Ternak</p>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-m3-on-surface-variant">Kategori:</span><br>
                                    <span class="font-bold text-m3-on-surface" x-text="sumKategori()"></span>
                                </div>
                                <div>
                                    <span class="text-m3-on-surface-variant">Kelas Berat:</span><br>
                                    <span class="font-bold text-m3-on-surface" x-text="sumKelas()"></span>
                                </div>
                                <div>
                                    <span class="text-m3-on-surface-variant">Jenis Kelamin:</span><br>
                                    <span class="font-bold text-m3-on-surface" x-text="sumKelamin()"></span>
                                </div>
                                <div>
                                    <span class="text-m3-on-surface-variant">Jumlah:</span><br>
                                    <span class="font-bold text-m3-on-surface" x-text="sumJumlah()"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Survei summary --}}
                        <div x-show="isSurvei" class="bg-m3-surface-container-low rounded-2xl p-6 space-y-4">
                            <p class="text-[10px] uppercase tracking-widest font-bold text-m3-primary">Jadwal Survei Lokasi</p>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-m3-on-surface-variant">Tanggal:</span><br>
                                    <span class="font-bold text-m3-on-surface" x-text="sumTanggal()"></span>
                                </div>
                                <div>
                                    <span class="text-m3-on-surface-variant">Sesi Waktu:</span><br>
                                    <span class="font-bold text-m3-on-surface" x-text="sumWaktu()"></span>
                                </div>
                            </div>
                            <div class="p-3 bg-amber-50 rounded-xl border border-amber-100">
                                <p class="text-xs text-amber-800 font-medium">
                                    ℹ️ Pembayaran akan dilakukan setelah survei selesai dan disetujui admin.
                                </p>
                            </div>
                        </div>

                        {{-- Non-survei summary --}}
                        <div x-show="!isSurvei" class="space-y-4">
                            <div class="bg-m3-surface-container-low rounded-2xl p-6 space-y-4">
                                <p class="text-[10px] uppercase tracking-widest font-bold text-m3-primary">Detail Pengambilan & Pembayaran</p>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-m3-on-surface-variant">Metode Kirim:</span><br>
                                        <span class="font-bold text-m3-on-surface" x-text="sumPengiriman()"></span>
                                    </div>
                                    <div>
                                        <span class="text-m3-on-surface-variant">Metode Bayar:</span><br>
                                        <span class="font-bold text-m3-on-surface" x-text="sumPembayaran()"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Total --}}
                            <div class="bg-m3-surface-container-low rounded-2xl p-6 flex justify-between items-end">
                                <div>
                                    <p class="text-xs text-m3-on-surface-variant uppercase tracking-widest font-bold mb-1">Total Pembayaran</p>
                                    <p class="text-3xl font-bold text-m3-primary" x-text="formatRupiah(grandTotal)"></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] uppercase tracking-widest text-m3-on-surface-variant font-bold">Ongkir</p>
                                    <p class="font-bold text-m3-primary"
                                        x-text="ongkirNominal === 0 ? 'Gratis' : formatRupiah(ongkirNominal)"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Disclaimer --}}
                        <div class="p-4 bg-m3-primary-fixed/20 rounded-2xl flex gap-3 items-start">
                            <span class="material-symbols-outlined text-m3-primary text-xl shrink-0">info</span>
                            <p class="text-sm text-m3-on-surface-variant">
                                Dengan melanjutkan, Anda menyetujui syarat dan ketentuan pembelian domba di Smart-Saka.
                                Verifikasi akan dilakukan dalam 24 jam via WhatsApp.
                            </p>
                        </div>
                    </div>

                    {{-- Step 3 Navigation + Submit --}}
                    <div class="flex justify-between items-center pt-10">
                        <button type="button" @click="goStep(2)"
                            class="text-m3-primary font-bold flex items-center gap-2 hover:-translate-x-1 transition-transform text-sm">
                            <span class="material-symbols-outlined text-lg">arrow_back</span> Ubah Data
                        </button>
                        <button type="submit"
                            :disabled="metodePengiriman === 'dikirim' && !dalamJangkauan && !isSurvei"
                            class="bg-m3-primary text-m3-on-primary px-10 py-4 rounded-full font-bold text-base shadow-lg hover:bg-m3-primary-container active:scale-95 transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-text="isSurvei ? 'Konfirmasi Survei' : 'Konfirmasi Pesanan'"></span>
                            <span class="material-symbols-outlined">check_circle</span>
                        </button>
                    </div>
                </section>

            </div>{{-- end left col --}}


            {{-- ═══════════════════ RIGHT COLUMN: STICKY SUMMARY ═══════════════════ --}}
            <aside class="lg:sticky lg:top-28 space-y-5">

                {{-- Product card --}}
                <div class="bg-m3-surface-container-lowest rounded-3xl shadow-xl overflow-hidden">

                    {{-- Product image placeholder --}}
                    <div class="relative h-52 bg-m3-surface-container-low flex items-center justify-center overflow-hidden">
                        <span class="material-symbols-outlined text-[7rem] text-m3-outline-variant/20"
                            style="font-variation-settings:'FILL' 0,'wght' 200;">pets</span>
                        <div x-show="selectedItem"
                            class="absolute top-4 right-4 bg-m3-primary/90 text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                            Siap Jual
                        </div>
                    </div>

                    <div class="p-6 space-y-5">
                        {{-- Title --}}
                        <div x-show="selectedItem">
                            <h3 class="text-xl font-bold font-headline text-m3-primary mb-1 capitalize"
                                x-text="selectedItem?.nama_produk + ' — ' + (selectedItem?.jenis_kelamin || '')"></h3>
                            <p class="text-xs text-m3-on-surface-variant font-medium">
                                Kelas: <span x-text="selectedItem?.kelas_berat"></span>
                            </p>
                        </div>
                        <div x-show="!selectedItem" class="text-sm text-m3-outline italic text-center py-4">
                            Pilih produk untuk melihat detail
                        </div>

                        {{-- Specs --}}
                        <div x-show="selectedItem" class="grid grid-cols-2 gap-3 py-4 border-y border-m3-surface-container-high text-xs">
                            <div class="text-center">
                                <span class="block text-[10px] text-m3-on-surface-variant uppercase font-bold tracking-tight mb-1">Stok</span>
                                <span class="font-bold text-m3-primary" x-text="(selectedItem?.stok || 0) + ' Ekor'"></span>
                            </div>
                            <div class="text-center border-l border-m3-surface-container-high">
                                <span class="block text-[10px] text-m3-on-surface-variant uppercase font-bold tracking-tight mb-1">Kelamin</span>
                                <span class="font-bold text-m3-primary capitalize" x-text="selectedItem?.jenis_kelamin || '-'"></span>
                            </div>
                        </div>

                        {{-- Price breakdown --}}
                        <div x-show="selectedItem" class="space-y-2.5">
                            <div class="flex justify-between text-sm">
                                <span class="text-m3-on-surface-variant"
                                    x-text="jumlah + ' Ekor × ' + formatRupiah(selectedItem?.harga)"></span>
                                <span class="font-bold text-m3-on-surface" x-text="formatRupiah(totalHarga)"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-m3-on-surface-variant">Ongkos Kirim</span>
                                <span class="font-bold"
                                    :class="ongkirNominal === 0 ? 'text-m3-secondary' : 'text-m3-on-surface'"
                                    x-text="isSurvei ? 'Dikonfirmasi' : (ongkirNominal === 0 ? 'Gratis' : formatRupiah(ongkirNominal))"></span>
                            </div>
                            <div class="pt-3 border-t border-dashed border-m3-outline-variant flex justify-between items-end">
                                <span class="text-xs font-bold text-m3-primary uppercase tracking-widest">Total Estimasi</span>
                                <span class="text-2xl font-bold text-m3-primary" x-text="formatRupiah(isSurvei ? totalHarga : grandTotal)"></span>
                            </div>
                        </div>

                        {{-- Protection --}}
                        <div class="p-4 bg-m3-surface-container-low rounded-2xl space-y-2">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="material-symbols-outlined text-m3-primary text-sm">shield</span>
                                <span class="text-xs font-bold text-m3-primary">Proteksi Transaksi</span>
                            </div>
                            <ul class="space-y-1.5">
                                @foreach(['Verifikasi Kesehatan Hewan','Garansi Pengiriman Aman','Pendampingan Pelihara (1 Bulan)'] as $badge)
                                <li class="flex items-center gap-2 text-[11px] text-m3-on-surface-variant">
                                    <span class="material-symbols-outlined text-[14px] text-m3-primary">check_circle</span>
                                    {{ $badge }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- WhatsApp help --}}
                <div class="bg-m3-surface-container-lowest p-5 rounded-2xl border border-m3-outline-variant/30 flex gap-4">
                    <span class="material-symbols-outlined text-m3-primary text-2xl shrink-0">support_agent</span>
                    <div>
                        <p class="text-sm font-bold text-m3-primary">Butuh Bantuan?</p>
                        <p class="text-xs text-m3-on-surface-variant mt-1">Tim Sakadomas siap menjawab pertanyaan seputar spek domba & logistik.</p>
                        <a href="https://wa.me/{{ config('smartsaka.wa_number') }}"
                            target="_blank" rel="noopener"
                            class="text-xs font-bold text-m3-primary mt-2 inline-flex items-center gap-1 hover:underline">
                            <span class="material-symbols-outlined text-sm">open_in_new</span>
                            Hubungi Admin via WhatsApp
                        </a>
                    </div>
                </div>

            </aside>{{-- end right col --}}

        </div>{{-- end grid --}}
    </form>

</div>{{-- end main container --}}


{{-- ── TRUST STRIP ── --}}
<section class="bg-m3-primary text-m3-on-primary py-16 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-8 relative z-10">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-12">
            @foreach ([
                ['icon' => 'verified_user',  'title' => 'Terdaftar Resmi',    'desc' => 'Terdaftar di Dinas Peternakan'],
                ['icon' => 'medical_services','title' => 'Cek Medis Rutin',    'desc' => 'Pemeriksaan berkala oleh dokter hewan'],
                ['icon' => 'location_on',     'title' => 'Lokal Jember',       'desc' => 'Peternakan asli Jawa Timur'],
                ['icon' => 'payments',        'title' => 'Transparan',         'desc' => 'Harga jelas tanpa biaya tersembunyi'],
            ] as $trust)
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-3xl bg-white/10 flex items-center justify-center mb-5 backdrop-blur">
                    <span class="material-symbols-outlined text-3xl" style="font-variation-settings:'FILL' 1;">{{ $trust['icon'] }}</span>
                </div>
                <h4 class="font-bold text-base mb-1">{{ $trust['title'] }}</h4>
                <p class="text-m3-on-primary/70 text-xs">{{ $trust['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<x-landing.footer
    wa-number="{{ config('smartsaka.wa_number') }}"
    email="{{ config('smartsaka.email') }}"
    :address="config('smartsaka.address')"
    map-src="{{ config('smartsaka.maps_embed_src') }}"
/>

@endsection
