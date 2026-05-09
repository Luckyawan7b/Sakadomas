<!DOCTYPE html>
<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Form Pemesanan | Smart-Saka Premium Sheep Farm</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Manrope:wght@400;500;600;700&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
                    "inverse-surface": "#2f312e", "inverse-on-surface": "#f1f1ec"
                },
                borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                fontFamily: { "headline": ["Plus Jakarta Sans"], "body": ["Manrope"], "label": ["Manrope"] }
            }
        }
    }
</script>
<style>
    body { font-family: 'Manrope', sans-serif; background-color: #fafaf5; }
    h1, h2, h3 { font-family: 'Plus Jakarta Sans', sans-serif; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .form-input-line { border-bottom: 2px solid #c4c9b4; transition: border-color 0.3s; }
    .form-input-line:focus-within { border-color: #3d6700; }
    .step-section { display: none; }
    .step-section.active { display: block; animation: fadeUp 0.4s ease; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
    .cal-day:hover { background: #b9f474; color: #1a1c19; }
    .cal-day.selected { background: #3d6700; color: #fff; }
    .error-msg { color: #ba1a1a; font-size: 11px; margin-top: 4px; display: none; }
    .error-msg.show { display: block; }
</style>
</head>
<body class="text-on-surface">
<!-- Navbar -->
<nav class="fixed top-0 w-full z-50 bg-[#fafaf5]/70 backdrop-blur-2xl shadow-[0_10px_40px_rgba(61,103,0,0.06)]">
<div class="flex justify-between items-center max-w-7xl mx-auto px-8 h-24">
<a href="katalog.html" class="text-2xl font-bold tracking-tighter text-[#3d6700]">Smart-Saka</a>
<div class="hidden md:flex items-center space-x-10 font-['Plus_Jakarta_Sans'] font-medium tracking-tight">
<a class="text-stone-600 hover:text-[#3d6700] transition-all duration-300" href="katalog.html">Beranda</a>
<a class="text-[#3d6700] border-b-2 border-[#8BC34A] pb-1" href="katalog.html">Produk</a>
<a class="text-stone-600 hover:text-[#3d6700] transition-all duration-300" href="#">Tentang Kami</a>
<a class="text-stone-600 hover:text-[#3d6700] transition-all duration-300" href="#">Testimoni</a>
</div>
<div class="flex items-center space-x-6">
<button class="bg-[#3d6700] text-white px-8 py-3 rounded-full font-semibold hover:bg-[#4f8207] active:scale-95 transition-all duration-300">Jelajahi</button>
</div>
</div>
</nav>

<main class="pt-24">
<!-- Hero Banner -->
<header class="bg-surface-container-low pt-20 pb-28 px-8">
<div class="max-w-7xl mx-auto">
<nav class="flex items-center space-x-2 text-sm text-on-surface-variant mb-6 tracking-wide">
<a href="katalog.html" class="hover:text-primary">Beranda</a>
<span class="material-symbols-outlined text-xs">chevron_right</span>
<a href="katalog.html" class="hover:text-primary">Katalog Domba</a>
<span class="material-symbols-outlined text-xs">chevron_right</span>
<a href="detail_produk.html" class="hover:text-primary">Cross Texel Jantan #SKD-001</a>
<span class="material-symbols-outlined text-xs">chevron_right</span>
<span class="text-primary font-semibold">Pemesanan</span>
</nav>
<div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
<div>
<h1 class="text-5xl md:text-7xl font-bold text-primary mb-4 tracking-tight leading-tight">Form Pemesanan</h1>
<p class="text-on-surface-variant max-w-xl text-lg">Lengkapi detail pemesanan di bawah ini untuk mengamankan unit domba pilihan Anda. Tim kami akan memverifikasi dalam 24 jam.</p>
</div>
<div class="flex flex-wrap gap-3">
<div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm text-sm font-semibold text-primary">
<span class="material-symbols-outlined text-sm">security</span> Data Aman
</div>
<div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm text-sm font-semibold text-primary">
<span class="material-symbols-outlined text-sm">chat_bubble</span> Konfirmasi via WhatsApp
</div>
<div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm text-sm font-semibold text-primary">
<span class="material-symbols-outlined text-sm">verified</span> Stok Terjamin
</div>
</div>
</div>
</div>
</header>

<div class="max-w-7xl mx-auto px-8 -mt-10 mb-24">
<!-- Step Indicator -->
<div id="step-indicator" class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(61,103,0,0.08)] p-6 mb-10 overflow-x-auto">
<div class="flex justify-between items-center min-w-[600px] px-4">
<div class="step-dot flex flex-col items-center gap-2" data-step="1">
<div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold transition-all">1</div>
<span class="text-sm font-bold text-primary transition-all">Data Pemesan</span>
</div>
<div class="step-line h-[2px] flex-1 mx-4 bg-outline-variant/30 transition-all"></div>
<div class="step-dot flex flex-col items-center gap-2 opacity-40" data-step="2">
<div class="w-10 h-10 rounded-full bg-surface-container-high text-on-surface flex items-center justify-center font-bold transition-all">2</div>
<span class="text-sm font-medium transition-all">Pengambilan</span>
</div>
<div class="step-line h-[2px] flex-1 mx-4 bg-outline-variant/30 transition-all"></div>
<div class="step-dot flex flex-col items-center gap-2 opacity-40" data-step="3">
<div class="w-10 h-10 rounded-full bg-surface-container-high text-on-surface flex items-center justify-center font-bold transition-all">3</div>
<span class="text-sm font-medium transition-all">Ringkasan</span>
</div>
</div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
<!-- Left Column: Form Steps -->
<div class="lg:col-span-2 space-y-6">

<!-- Step 1: Data Pemesan -->
<section id="step-1" class="step-section active bg-white p-8 md:p-10 rounded-3xl shadow-sm">
<div class="flex items-center gap-4 mb-8">
<span class="material-symbols-outlined text-primary text-3xl">person_edit</span>
<h2 class="text-2xl font-bold text-primary">Data Pemesan</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-8">
<div class="space-y-2 form-input-line pb-2">
<label class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Nama Lengkap <span class="text-error">*</span></label>
<input id="nama" class="w-full bg-transparent border-none p-0 focus:ring-0 text-on-surface placeholder:text-outline-variant" placeholder="Contoh: Ahmad Subardjo" type="text" required/>
<p class="error-msg" id="err-nama">Nama lengkap wajib diisi</p>
</div>
<div class="space-y-2 form-input-line pb-2">
<label class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Nomor WhatsApp <span class="text-error">*</span></label>
<input id="whatsapp" class="w-full bg-transparent border-none p-0 focus:ring-0 text-on-surface placeholder:text-outline-variant" placeholder="+62 812 XXXX XXXX" type="tel" required/>
<p class="error-msg" id="err-whatsapp">Nomor WhatsApp wajib diisi</p>
</div>
<div class="space-y-2 form-input-line pb-2">
<label class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Alamat Email <span class="text-error">*</span></label>
<input id="email" class="w-full bg-transparent border-none p-0 focus:ring-0 text-on-surface placeholder:text-outline-variant" placeholder="nama@email.com" type="email" required/>
<p class="error-msg" id="err-email">Masukkan alamat email yang valid</p>
</div>
<div class="space-y-2 form-input-line pb-2">
<label class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Kota Asal <span class="text-error">*</span></label>
<input id="kota" class="w-full bg-transparent border-none p-0 focus:ring-0 text-on-surface placeholder:text-outline-variant" placeholder="Contoh: Jember" type="text" required/>
<p class="error-msg" id="err-kota">Kota asal wajib diisi</p>
</div>
<div class="space-y-2 form-input-line pb-2">
<label class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Jenis Identitas</label>
<select id="id-type" class="w-full bg-transparent border-none p-0 focus:ring-0 text-on-surface">
<option>KTP (Kartu Tanda Penduduk)</option>
<option>SIM (Surat Izin Mengemudi)</option>
<option>Paspor</option>
</select>
</div>
<div class="space-y-2 form-input-line pb-2">
<label class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Nomor Identitas</label>
<input id="id-number" class="w-full bg-transparent border-none p-0 focus:ring-0 text-on-surface placeholder:text-outline-variant" placeholder="3509XXXXXXXXXXXX" type="text"/>
</div>
<div class="md:col-span-2 space-y-2 form-input-line pb-2">
<label class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Catatan Tambahan (Opsional)</label>
<textarea id="catatan" class="w-full bg-transparent border-none p-0 focus:ring-0 text-on-surface placeholder:text-outline-variant h-20 resize-none" placeholder="Contoh: Mohon info ketersediaan vitamin tambahan"></textarea>
</div>
</div>
<div class="flex justify-between items-center pt-8">
<a href="detail_produk.html" class="text-primary font-bold flex items-center gap-2 hover:translate-x-[-4px] transition-transform">
<span class="material-symbols-outlined">arrow_back</span> Kembali
</a>
<button onclick="goToStep(2)" class="bg-primary text-on-primary px-10 py-4 rounded-full font-bold text-lg shadow-lg hover:bg-[#4f8207] active:scale-95 transition-all">
Lanjutkan <span class="material-symbols-outlined align-middle">arrow_forward</span>
</button>
</div>
</section>

<!-- Step 2: Metode Pengambilan -->
<section id="step-2" class="step-section bg-white p-8 md:p-10 rounded-3xl shadow-sm">
<div class="flex items-center gap-4 mb-8">
<span class="material-symbols-outlined text-primary text-3xl">local_shipping</span>
<h2 class="text-2xl font-bold text-primary">Metode Pengambilan</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
<button id="btn-ambil" onclick="selectMethod('ambil')" class="method-btn flex flex-col items-start p-6 rounded-2xl border-2 border-primary bg-primary/5 text-left transition-all">
<span class="material-symbols-outlined text-primary mb-3">store</span>
<span class="font-bold text-on-surface">Ambil Langsung</span>
<span class="text-xs text-on-surface-variant">Ke peternakan Sakadomas, Jember</span>
</button>
<button id="btn-kirim" onclick="selectMethod('kirim')" class="method-btn flex flex-col items-start p-6 rounded-2xl border-2 border-surface-container-high hover:border-outline-variant text-left transition-all">
<span class="material-symbols-outlined text-outline mb-3">local_shipping</span>
<span class="font-bold text-on-surface">Kirim ke Alamat</span>
<span class="text-xs text-on-surface-variant">Biaya menyesuaikan jarak</span>
</button>
</div>

<!-- Alamat Pengiriman (hidden by default) -->
<div id="alamat-section" class="hidden mb-10 space-y-4 p-6 bg-surface-container-low rounded-2xl">
<h3 class="font-bold text-on-surface flex items-center gap-2"><span class="material-symbols-outlined text-sm text-primary">home</span> Alamat Pengiriman</h3>
<div class="space-y-2 form-input-line pb-2">
<label class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Alamat Lengkap <span class="text-error">*</span></label>
<textarea id="alamat" class="w-full bg-transparent border-none p-0 focus:ring-0 text-on-surface placeholder:text-outline-variant h-16 resize-none" placeholder="Jl. Contoh No. 123, Kec. Patrang, Kab. Jember"></textarea>
</div>
<div class="p-4 bg-tertiary-fixed/30 rounded-xl flex gap-4 items-start">
<span class="material-symbols-outlined text-tertiary">info</span>
<p class="text-sm text-on-surface-variant">Biaya ongkos kirim akan dikonfirmasi manual oleh admin setelah meninjau lokasi alamat Anda.</p>
</div>
</div>

<!-- Calendar -->
<div class="space-y-6">
<h3 class="font-bold text-on-surface flex items-center gap-2">
<span class="material-symbols-outlined text-sm text-primary">calendar_month</span> Pilih Jadwal Kunjungan / Pengiriman
</h3>
<div class="bg-surface-container-low rounded-2xl p-6">
<div class="flex justify-between items-center mb-4">
<button onclick="changeMonth(-1)" class="w-8 h-8 rounded-full hover:bg-surface-variant flex items-center justify-center"><span class="material-symbols-outlined text-sm">chevron_left</span></button>
<span id="cal-month" class="font-bold text-primary text-lg"></span>
<button onclick="changeMonth(1)" class="w-8 h-8 rounded-full hover:bg-surface-variant flex items-center justify-center"><span class="material-symbols-outlined text-sm">chevron_right</span></button>
</div>
<div class="grid grid-cols-7 gap-1 text-center text-xs mb-2">
<div class="font-bold text-on-surface-variant py-2">Min</div>
<div class="font-bold text-on-surface-variant py-2">Sen</div>
<div class="font-bold text-on-surface-variant py-2">Sel</div>
<div class="font-bold text-on-surface-variant py-2">Rab</div>
<div class="font-bold text-on-surface-variant py-2">Kam</div>
<div class="font-bold text-on-surface-variant py-2">Jum</div>
<div class="font-bold text-on-surface-variant py-2">Sab</div>
</div>
<div id="cal-days" class="grid grid-cols-7 gap-1 text-center text-sm"></div>
</div>
<p id="selected-date-display" class="text-sm text-primary font-bold mt-2 hidden"><span class="material-symbols-outlined text-sm align-middle">event</span> Tanggal dipilih: <span id="selected-date-text"></span></p>
</div>

<!-- Time Session -->
<div class="space-y-3 mt-6">
<label class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Sesi Waktu</label>
<div class="flex flex-wrap gap-2" id="time-slots">
<button onclick="selectTime(this)" class="time-btn px-5 py-3 rounded-full border-2 border-primary bg-primary text-white text-xs font-bold transition-all">Pagi (08:00 - 11:00)</button>
<button onclick="selectTime(this)" class="time-btn px-5 py-3 rounded-full border-2 border-outline-variant text-on-surface text-xs font-medium hover:border-primary transition-all">Siang (13:00 - 15:00)</button>
<button onclick="selectTime(this)" class="time-btn px-5 py-3 rounded-full border-2 border-outline-variant text-on-surface text-xs font-medium hover:border-primary transition-all">Sore (15:30 - 17:00)</button>
</div>
</div>

<div class="flex justify-between items-center pt-8">
<button onclick="goToStep(1)" class="text-primary font-bold flex items-center gap-2 hover:translate-x-[-4px] transition-transform">
<span class="material-symbols-outlined">arrow_back</span> Kembali
</button>
<button onclick="goToStep(3)" class="bg-primary text-on-primary px-10 py-4 rounded-full font-bold text-lg shadow-lg hover:bg-[#4f8207] active:scale-95 transition-all">
Lihat Ringkasan <span class="material-symbols-outlined align-middle">arrow_forward</span>
</button>
</div>
</section>

<!-- Step 3: Ringkasan -->
<section id="step-3" class="step-section bg-white p-8 md:p-10 rounded-3xl shadow-sm">
<div class="flex items-center gap-4 mb-8">
<span class="material-symbols-outlined text-primary text-3xl">receipt_long</span>
<h2 class="text-2xl font-bold text-primary">Ringkasan Pemesanan</h2>
</div>
<div class="space-y-6">
<div class="bg-surface-container-low rounded-2xl p-6 space-y-4">
<h3 class="font-bold text-primary text-sm uppercase tracking-widest">Data Pemesan</h3>
<div class="grid grid-cols-2 gap-4 text-sm">
<div><span class="text-on-surface-variant">Nama:</span><br/><span id="sum-nama" class="font-bold">-</span></div>
<div><span class="text-on-surface-variant">WhatsApp:</span><br/><span id="sum-wa" class="font-bold">-</span></div>
<div><span class="text-on-surface-variant">Email:</span><br/><span id="sum-email" class="font-bold">-</span></div>
<div><span class="text-on-surface-variant">Kota:</span><br/><span id="sum-kota" class="font-bold">-</span></div>
</div>
</div>
<div class="bg-surface-container-low rounded-2xl p-6 space-y-4">
<h3 class="font-bold text-primary text-sm uppercase tracking-widest">Detail Pengambilan</h3>
<div class="grid grid-cols-2 gap-4 text-sm">
<div><span class="text-on-surface-variant">Metode:</span><br/><span id="sum-metode" class="font-bold">Ambil Langsung</span></div>
<div><span class="text-on-surface-variant">Tanggal:</span><br/><span id="sum-tanggal" class="font-bold">-</span></div>
<div><span class="text-on-surface-variant">Waktu:</span><br/><span id="sum-waktu" class="font-bold">Pagi (08:00 - 11:00)</span></div>
</div>
</div>
<div class="bg-surface-container-low rounded-2xl p-6">
<div class="flex justify-between items-center">
<div>
<p class="text-on-surface-variant text-sm">Total Pembayaran</p>
<p class="text-3xl font-bold text-primary mt-1">Rp 3.500.000</p>
</div>
<div class="text-right">
<p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Metode Bayar</p>
<p class="font-bold text-primary">Transfer Bank</p>
</div>
</div>
</div>
<div class="p-4 bg-primary-fixed/20 rounded-2xl flex gap-3 items-start">
<span class="material-symbols-outlined text-primary">info</span>
<p class="text-sm text-on-surface-variant">Dengan melanjutkan, Anda menyetujui syarat dan ketentuan pembelian domba di Smart-Saka. Verifikasi akan dilakukan dalam 24 jam via WhatsApp.</p>
</div>
</div>
<div class="flex justify-between items-center pt-8">
<button onclick="goToStep(2)" class="text-primary font-bold flex items-center gap-2 hover:translate-x-[-4px] transition-transform">
<span class="material-symbols-outlined">arrow_back</span> Ubah Data
</button>
<a href="transfer_page.html" id="btn-submit" class="bg-primary text-on-primary px-10 py-4 rounded-full font-bold text-lg shadow-lg hover:bg-[#4f8207] active:scale-95 transition-all inline-flex items-center gap-2">
Konfirmasi &amp; Bayar <span class="material-symbols-outlined">check_circle</span>
</a>
</div>
</section>

</div>

<!-- Right Column: Sticky Summary -->
<aside class="lg:sticky lg:top-28 space-y-6">
<div class="bg-white rounded-3xl shadow-xl overflow-hidden">
<div class="relative h-56 overflow-hidden">
<img alt="Cross Texel Jantan" class="w-full h-full object-cover transition-transform duration-700 hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBSruqjoiVAvl2YMCIgOT6CPVOFwplI26y5cxBEX--tcmHt0yUSP6Rv6HpsJYsisI9w8kqLjQBAolyVk0kpG4cq7E6Z6huF0aaR6yoViAv6shCVBzppFfVdd51Qo4oo4P440V5sS72ftyK5z3hobJyh6jsDZ_N5pDkMpXPGnzGryPfNST27L-gy8VGDAug70AqvbnXOQtLJq_BTm9dTCahIM2R6QWfSPHGL161TQWOLzAAa7Kq5WDV7-V3O-Z2C6600_bV7txWyIkk"/>
<div class="absolute top-4 right-4 bg-primary/90 text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">Siap Jual</div>
</div>
<div class="p-6 space-y-6">
<div>
<h3 class="text-xl font-bold text-primary mb-1">Cross Texel Jantan</h3>
<p class="text-sm text-on-surface-variant font-medium">Batch ID: #SKD-001</p>
</div>
<div class="grid grid-cols-3 gap-2 py-4 border-y border-surface-container-high">
<div class="text-center">
<span class="block text-[10px] text-on-surface-variant uppercase font-bold tracking-tighter">Berat</span>
<span class="text-sm font-bold text-primary">45 kg</span>
</div>
<div class="text-center border-x border-surface-container-high">
<span class="block text-[10px] text-on-surface-variant uppercase font-bold tracking-tighter">Umur</span>
<span class="text-sm font-bold text-primary">14 Bln</span>
</div>
<div class="text-center">
<span class="block text-[10px] text-on-surface-variant uppercase font-bold tracking-tighter">Kelamin</span>
<span class="text-sm font-bold text-primary">Jantan</span>
</div>
</div>
<div class="space-y-3">
<div class="flex justify-between text-sm">
<span class="text-on-surface-variant">Harga Unit</span>
<span class="font-bold text-on-surface">Rp 3.500.000</span>
</div>
<div class="flex justify-between text-sm">
<span class="text-on-surface-variant">Biaya Admin</span>
<span class="font-bold text-primary">Gratis</span>
</div>
<div class="flex justify-between text-sm">
<span class="text-on-surface-variant">Estimasi Ongkir</span>
<span class="font-bold text-on-surface-variant italic" id="side-ongkir">Konfirmasi Admin</span>
</div>
<div class="pt-4 border-t border-dashed border-outline-variant flex justify-between items-end">
<span class="text-sm font-bold text-primary uppercase tracking-widest">Total Estimasi</span>
<span class="text-2xl font-bold text-primary">Rp 3.500.000</span>
</div>
</div>
<div class="p-4 bg-surface-container-low rounded-2xl">
<div class="flex items-center gap-3 mb-3">
<div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
<span class="material-symbols-outlined text-primary text-sm">shield</span>
</div>
<span class="text-xs font-bold text-primary">Proteksi Transaksi</span>
</div>
<ul class="space-y-2">
<li class="flex items-center gap-2 text-[11px] text-on-surface-variant">
<span class="material-symbols-outlined text-[14px] text-primary">check_circle</span> Verifikasi Kesehatan Hewan
</li>
<li class="flex items-center gap-2 text-[11px] text-on-surface-variant">
<span class="material-symbols-outlined text-[14px] text-primary">check_circle</span> Garansi Pengiriman Aman
</li>
<li class="flex items-center gap-2 text-[11px] text-on-surface-variant">
<span class="material-symbols-outlined text-[14px] text-primary">check_circle</span> Pendampingan Pelihara (1 Bulan)
</li>
</ul>
</div>
</div>
</div>
<div class="bg-primary/5 p-6 rounded-2xl border border-primary/10 flex gap-4">
<span class="material-symbols-outlined text-primary">support_agent</span>
<div>
<span class="block text-sm font-bold text-primary">Butuh Bantuan?</span>
<p class="text-xs text-on-surface-variant mt-1">Tim Sakadomas siap menjawab pertanyaan seputar spek domba &amp; logistik.</p>
<a class="text-xs font-bold text-primary mt-2 inline-block underline" href="#">Hubungi Admin via WhatsApp</a>
</div>
</div>
</aside>
</div>
</div>

<!-- Trust Strip -->
<section class="bg-primary text-on-primary py-16 overflow-hidden relative">
<div class="max-w-7xl mx-auto px-8 relative z-10">
<div class="grid grid-cols-2 lg:grid-cols-4 gap-12">
<div class="flex flex-col items-center text-center">
<div class="w-16 h-16 rounded-3xl bg-white/10 flex items-center justify-center mb-6 backdrop-blur">
<span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">verified_user</span>
</div>
<h4 class="font-bold text-lg mb-2">Terdaftar Resmi</h4>
<p class="text-on-primary/70 text-sm">Terdaftar di Dinas Peternakan</p>
</div>
<div class="flex flex-col items-center text-center">
<div class="w-16 h-16 rounded-3xl bg-white/10 flex items-center justify-center mb-6 backdrop-blur">
<span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">medical_services</span>
</div>
<h4 class="font-bold text-lg mb-2">Cek Medis Rutin</h4>
<p class="text-on-primary/70 text-sm">Pemeriksaan berkala oleh dokter hewan</p>
</div>
<div class="flex flex-col items-center text-center">
<div class="w-16 h-16 rounded-3xl bg-white/10 flex items-center justify-center mb-6 backdrop-blur">
<span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">location_on</span>
</div>
<h4 class="font-bold text-lg mb-2">Lokal Jember</h4>
<p class="text-on-primary/70 text-sm">Peternakan asli Jawa Timur</p>
</div>
<div class="flex flex-col items-center text-center">
<div class="w-16 h-16 rounded-3xl bg-white/10 flex items-center justify-center mb-6 backdrop-blur">
<span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">payments</span>
</div>
<h4 class="font-bold text-lg mb-2">Transparan</h4>
<p class="text-on-primary/70 text-sm">Harga jelas tanpa biaya tersembunyi</p>
</div>
</div>
</div>
</section>
</main>

<!-- Footer -->
<footer class="bg-stone-100 w-full py-20">
<div class="max-w-7xl mx-auto px-12 grid grid-cols-1 md:grid-cols-3 gap-16 font-['Manrope'] text-sm leading-relaxed">
<div>
<span class="text-xl font-extrabold text-[#3d6700] mb-4 block tracking-tighter">Smart-Saka</span>
<p class="text-stone-500 mb-8 max-w-xs">Dedikasi kami untuk peternakan modern yang menjunjung tinggi kesejahteraan hewan dan kualitas produk alami.</p>
</div>
<div class="grid grid-cols-2 gap-8">
<div>
<h5 class="font-bold text-[#3d6700] mb-6">Navigasi</h5>
<ul class="space-y-4 text-stone-500">
<li class="hover:text-[#8BC34A] transition-all cursor-pointer">Hubungi Kami</li>
<li class="hover:text-[#8BC34A] transition-all cursor-pointer"><a href="katalog.html">Katalog</a></li>
<li class="hover:text-[#8BC34A] transition-all cursor-pointer">Kebijakan Privasi</li>
</ul>
</div>
<div>
<h5 class="font-bold text-[#3d6700] mb-6">Sosial</h5>
<ul class="space-y-4 text-stone-500">
<li class="hover:text-[#8BC34A] transition-all cursor-pointer">Instagram</li>
<li class="hover:text-[#8BC34A] transition-all cursor-pointer">Facebook</li>
</ul>
</div>
</div>
<div>
<h5 class="font-bold text-[#3d6700] mb-6">Newsletter</h5>
<p class="text-stone-500 mb-6">Dapatkan info bibit unggul terbaru langsung di email Anda.</p>
<div class="flex gap-2">
<input class="bg-white border-none rounded-full px-6 py-3 flex-1 text-sm focus:ring-1 focus:ring-[#3d6700]" placeholder="Email Anda" type="email"/>
<button class="bg-[#3d6700] text-white px-6 py-3 rounded-full hover:opacity-90 transition-opacity">
<span class="material-symbols-outlined text-sm">send</span>
</button>
</div>
</div>
</div>
<div class="max-w-7xl mx-auto px-12 pt-16 mt-16 border-t border-stone-200">
<p class="text-stone-500 text-center">&copy; 2024 Smart-Saka Premium Sheep Farm. Menumbuhkan Kualitas Secara Alami.</p>
</div>
</footer>

<script>
let currentStep = 1;
let selectedDate = null;
let calMonth, calYear;
const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

// Initialize calendar
const now = new Date();
calMonth = now.getMonth();
calYear = now.getFullYear();
renderCalendar();

function renderCalendar() {
    const container = document.getElementById('cal-days');
    const label = document.getElementById('cal-month');
    label.textContent = monthNames[calMonth] + ' ' + calYear;
    container.innerHTML = '';
    const firstDay = new Date(calYear, calMonth, 1).getDay();
    const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
    const today = new Date(); today.setHours(0,0,0,0);

    for (let i = 0; i < firstDay; i++) {
        container.innerHTML += '<div></div>';
    }
    for (let d = 1; d <= daysInMonth; d++) {
        const date = new Date(calYear, calMonth, d);
        const isPast = date < today;
        const isSelected = selectedDate && date.getTime() === selectedDate.getTime();
        const cls = isPast ? 'p-2 rounded-xl text-on-surface-variant/30 cursor-not-allowed' :
                    isSelected ? 'cal-day selected p-2 rounded-xl cursor-pointer font-bold' :
                    'cal-day p-2 rounded-xl cursor-pointer hover:bg-primary-fixed/50 transition-colors';
        container.innerHTML += `<div class="${cls}" ${!isPast ? `onclick="pickDate(${d})"` : ''}>${d}</div>`;
    }
}

function changeMonth(dir) {
    calMonth += dir;
    if (calMonth > 11) { calMonth = 0; calYear++; }
    if (calMonth < 0) { calMonth = 11; calYear--; }
    renderCalendar();
}

function pickDate(d) {
    selectedDate = new Date(calYear, calMonth, d);
    document.getElementById('selected-date-text').textContent = d + ' ' + monthNames[calMonth] + ' ' + calYear;
    document.getElementById('selected-date-display').classList.remove('hidden');
    renderCalendar();
}

function selectMethod(method) {
    document.querySelectorAll('.method-btn').forEach(b => {
        b.classList.remove('border-primary', 'bg-primary/5');
        b.classList.add('border-surface-container-high');
        b.querySelector('.material-symbols-outlined').classList.remove('text-primary');
        b.querySelector('.material-symbols-outlined').classList.add('text-outline');
    });
    const btn = document.getElementById('btn-' + method);
    btn.classList.add('border-primary', 'bg-primary/5');
    btn.classList.remove('border-surface-container-high');
    btn.querySelector('.material-symbols-outlined').classList.add('text-primary');
    btn.querySelector('.material-symbols-outlined').classList.remove('text-outline');

    const alamatSection = document.getElementById('alamat-section');
    if (method === 'kirim') {
        alamatSection.classList.remove('hidden');
        document.getElementById('side-ongkir').textContent = 'Konfirmasi Admin';
    } else {
        alamatSection.classList.add('hidden');
        document.getElementById('side-ongkir').textContent = 'Gratis (Ambil Sendiri)';
    }
}

function selectTime(el) {
    document.querySelectorAll('.time-btn').forEach(b => {
        b.classList.remove('bg-primary', 'text-white', 'border-primary');
        b.classList.add('border-outline-variant', 'text-on-surface');
    });
    el.classList.add('bg-primary', 'text-white', 'border-primary');
    el.classList.remove('border-outline-variant', 'text-on-surface');
}

function validateStep1() {
    let valid = true;
    const fields = [
        { id: 'nama', err: 'err-nama' },
        { id: 'whatsapp', err: 'err-whatsapp' },
        { id: 'email', err: 'err-email' },
        { id: 'kota', err: 'err-kota' }
    ];
    fields.forEach(f => {
        const el = document.getElementById(f.id);
        const errEl = document.getElementById(f.err);
        if (!el.value.trim()) {
            errEl.classList.add('show');
            el.parentElement.style.borderColor = '#ba1a1a';
            valid = false;
        } else {
            errEl.classList.remove('show');
            el.parentElement.style.borderColor = '';
        }
    });
    // Email format check
    const emailEl = document.getElementById('email');
    if (emailEl.value && !emailEl.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        document.getElementById('err-email').classList.add('show');
        emailEl.parentElement.style.borderColor = '#ba1a1a';
        valid = false;
    }
    return valid;
}

function goToStep(step) {
    if (step === 2 && currentStep === 1) {
        if (!validateStep1()) return;
    }
    if (step === 3) {
        // Populate summary
        document.getElementById('sum-nama').textContent = document.getElementById('nama').value || '-';
        document.getElementById('sum-wa').textContent = document.getElementById('whatsapp').value || '-';
        document.getElementById('sum-email').textContent = document.getElementById('email').value || '-';
        document.getElementById('sum-kota').textContent = document.getElementById('kota').value || '-';
        const isKirim = !document.getElementById('alamat-section').classList.contains('hidden');
        document.getElementById('sum-metode').textContent = isKirim ? 'Kirim ke Alamat' : 'Ambil Langsung';
        document.getElementById('sum-tanggal').textContent = selectedDate ? document.getElementById('selected-date-text').textContent : 'Belum dipilih';
        const activeTime = document.querySelector('.time-btn.bg-primary');
        document.getElementById('sum-waktu').textContent = activeTime ? activeTime.textContent : '-';
    }

    currentStep = step;
    document.querySelectorAll('.step-section').forEach(s => s.classList.remove('active'));
    document.getElementById('step-' + step).classList.add('active');

    // Update step indicator
    document.querySelectorAll('.step-dot').forEach(dot => {
        const s = parseInt(dot.dataset.step);
        const circle = dot.querySelector('div');
        const label = dot.querySelector('span');
        if (s < step) {
            dot.style.opacity = '1';
            circle.className = 'w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold transition-all';
            circle.innerHTML = '<span class="material-symbols-outlined text-sm">check</span>';
            label.className = 'text-sm font-bold text-primary transition-all';
        } else if (s === step) {
            dot.style.opacity = '1';
            circle.className = 'w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold transition-all';
            circle.textContent = s;
            label.className = 'text-sm font-bold text-primary transition-all';
        } else {
            dot.style.opacity = '0.4';
            circle.className = 'w-10 h-10 rounded-full bg-surface-container-high text-on-surface flex items-center justify-center font-bold transition-all';
            circle.textContent = s;
            label.className = 'text-sm font-medium transition-all';
        }
    });

    // Update step lines
    const lines = document.querySelectorAll('.step-line');
    lines.forEach((line, i) => {
        line.style.backgroundColor = (i + 1 < step) ? '#3d6700' : '';
    });

    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
</body></html>