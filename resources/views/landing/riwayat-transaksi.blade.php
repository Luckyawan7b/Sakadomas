@extends('layouts.landing')

@section('title', 'Riwayat Transaksi | Smart-Saka Premium Sheep Farm')

@push('head')
    <style>
        /* Custom Scrollbar for table to make horizontal scroll elegant */
        .table-container::-webkit-scrollbar { height: 8px; }
        .table-container::-webkit-scrollbar-track { background: #f4f4ef; border-radius: 4px; }
        .table-container::-webkit-scrollbar-thumb { background: #c4c9b4; border-radius: 4px; }
        .table-container::-webkit-scrollbar-thumb:hover { background: #747967; }
    </style>
    <!-- Flatpickr for Reschedule Form -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush

@section('content')

    {{-- NAVBAR --}}
    <x-landing.navbar />

    <main class="pt-24 flex-1">
        <!-- Header Hero Section -->
        <header class="relative bg-m3-surface-container-low pt-12 pb-20 px-8 overflow-hidden">
            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: url('{{ asset('images/katalog.jpg') }}'); background-size: cover; background-position: center;"></div>
            <div class="max-w-7xl mx-auto relative z-10">
                <nav class="flex items-center space-x-2 text-sm text-m3-on-surface-variant mb-6 tracking-wide font-label">
                    <a href="{{ route('home') }}" class="hover:text-m3-primary transition-colors">Beranda</a>
                    <span class="material-symbols-outlined text-xs">chevron_right</span>
                    <span class="text-m3-primary font-semibold">Riwayat Transaksi</span>
                </nav>
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold font-headline text-m3-primary mb-4 tracking-tight leading-tight">Riwayat Transaksi</h1>
                        <p class="text-m3-on-surface-variant max-w-xl text-lg">Pantau status pesanan, jadwal survei, dan riwayat pembelian domba Anda di sini.</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Table Content -->
        <section class="max-w-7xl mx-auto px-8 -mt-10 mb-24 relative z-10" x-data="{
            searchQuery: '{{ request('q') }}',
            statusFilter: '{{ request('status', 'semua') }}'
        }">

            {{-- Flash Message Sukses --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
                    class="mb-6 rounded-2xl bg-green-50 p-4 text-sm text-green-800 border border-green-200 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-600">check_circle</span>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-600 hover:text-green-800">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(61,103,0,0.08)] overflow-hidden border border-m3-surface-container-high">

                <!-- Toolbar: Search & Filter -->
                <form action="{{ route('transaksi.riwayat') }}" method="GET" class="p-6 md:p-8 border-b border-m3-surface-container-high bg-m3-surface-container-lowest flex flex-col lg:flex-row gap-4 justify-between items-center">

                    <!-- Search Bar -->
                    <div class="relative w-full lg:w-96">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-m3-outline">search</span>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari ID Transaksi..." class="w-full pl-12 pr-4 py-3 rounded-full bg-m3-surface-container-low border-none focus:ring-2 focus:ring-m3-primary/50 text-sm placeholder:text-m3-outline transition-all text-m3-on-surface outline-none font-medium">
                    </div>

                    <!-- Filters -->
                    <div class="flex flex-wrap lg:flex-nowrap gap-3 w-full lg:w-auto">
                        <!-- Custom Alpine.js Dropdown for Status Filter -->
                        <div class="relative flex-1 lg:flex-none" x-data="{
                            open: false,
                            statusValue: '{{ request('status', 'semua') }}',
                            statusLabel: '',
                            init() {
                                const labels = {
                                    'semua': 'Semua Status',
                                    'pending': 'Pending',
                                    'diproses': 'Diproses / Survei',
                                    'dikirim': 'Dalam Pengiriman',
                                    'selesai': 'Selesai',
                                    'batal': 'Dibatalkan'
                                };
                                this.statusLabel = labels[this.statusValue] || 'Semua Status';
                            },
                            selectOption(val, label) {
                                this.statusValue = val;
                                this.statusLabel = label;
                                this.open = false;
                                this.$nextTick(() => {
                                    this.$el.closest('form').submit();
                                });
                            }
                        }" @click.away="open = false">
                            <!-- Hidden Input to submit via form -->
                            <input type="hidden" name="status" :value="statusValue">

                            <button type="button" @click="open = !open" class="w-full min-w-[210px] px-5 py-3 pr-10 rounded-full bg-m3-surface-container-low border-none focus:ring-2 focus:ring-m3-primary/50 text-sm font-bold text-m3-on-surface text-left hover:bg-m3-surface-variant transition-all outline-none flex items-center justify-between cursor-pointer">
                                <span x-text="statusLabel"></span>
                            </button>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-m3-outline text-sm transition-transform duration-200" :class="{ 'rotate-180': open }">expand_more</span>

                            <!-- Dropdown Menu Options -->
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 left-0 mt-2 rounded-2xl border border-m3-surface-container-high bg-white p-2 shadow-lg z-50 overflow-hidden"
                                 style="display: none;">
                                <template x-for="opt in [
                                    {val: 'semua', label: 'Semua Status'},
                                    {val: 'pending', label: 'Pending'},
                                    {val: 'diproses', label: 'Diproses / Survei'},
                                    {val: 'dikirim', label: 'Dalam Pengiriman'},
                                    {val: 'selesai', label: 'Selesai'},
                                    {val: 'batal', label: 'Dibatalkan'}
                                ]" :key="opt.val">
                                    <button type="button" @click="selectOption(opt.val, opt.label)"
                                            class="flex w-full items-center px-4 py-2.5 rounded-xl text-sm font-semibold text-m3-on-surface hover:bg-m3-surface-container-low transition-colors text-left cursor-pointer"
                                            :class="{ 'bg-m3-primary/10 text-m3-primary': statusValue === opt.val }">
                                        <span x-text="opt.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <a href="{{ route('transaksi.create') }}" class="shrink-0 bg-m3-primary text-m3-on-primary rounded-full px-5 py-3 font-bold text-sm flex items-center gap-2 hover:bg-m3-primary-container hover:text-m3-on-primary-container transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[20px]">add</span> Buat Pesanan
                        </a>
                    </div>
                </form>

                <!-- Data Table -->
                <div class="table-container overflow-x-auto w-full min-h-[400px]">
                    <table class="w-full min-w-[1000px] text-left border-collapse">
                        <thead>
                            <tr class="bg-m3-surface-container-low text-[10px] uppercase tracking-widest text-m3-on-surface-variant font-extrabold border-b border-m3-surface-container-high font-label">
                                <th class="py-4 px-6 pl-8">ID & Tgl Transaksi</th>
                                <th class="py-4 px-6">Pesanan Ternak</th>
                                <th class="py-4 px-6">Total Harga & Kurir</th>
                                <th class="py-4 px-6">Pembayaran</th>
                                <th class="py-4 px-6">Status</th>
                                <th class="py-4 px-6 pr-8 text-center">Aksi</th>
                            </tr>
                        </thead>
                        {{-- We use multiple tbodies so Alpine x-data can wrap both the main row and the accordion detail row --}}
                            @forelse ($data_transaksi as $trx)
                                @php
                                    $st = strtolower($trx->status);
                                    // Mapping status to colors
                                    $statusConfig = match($st) {
                                        'pending'  => ['label' => 'Pending', 'bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200', 'dot' => 'bg-yellow-500', 'step' => 1],
                                        'diproses' => ['label' => 'Diproses / Survei', 'bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'dot' => 'bg-blue-500', 'step' => 2],
                                        'dikirim'  => ['label' => 'Dalam Pengiriman', 'bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'dot' => 'bg-purple-500', 'step' => 3],
                                        'selesai'  => ['label' => 'Selesai', 'bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'dot' => 'bg-green-500', 'step' => 4],
                                        'batal'    => ['label' => 'Dibatalkan', 'bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'dot' => 'bg-red-500', 'step' => 0],
                                        default    => ['label' => ucfirst($st), 'bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'dot' => 'bg-gray-500', 'step' => 0],
                                    };
                                @endphp

                                <tbody x-data="{ openDetail: false }" class="border-b border-m3-surface-container-high text-sm">
                                <tr class="hover:bg-m3-primary/5 transition-colors group">
                                    {{-- ID & Tgl --}}
                                    <td class="py-5 px-6 pl-8 align-top">
                                        <div class="font-bold text-m3-primary text-base mb-1">#TRX-{{ $trx->id_transaksi }}</div>
                                        <div class="text-[13px] text-m3-on-surface-variant flex items-center gap-1.5 font-medium font-label">
                                            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                            {{ \Carbon\Carbon::parse($trx->tgl_transaksi)->translatedFormat('d M Y, H:i') }}
                                        </div>
                                    </td>

                                    {{-- Pesanan Ternak --}}
                                    <td class="py-5 px-6 align-top">
                                        <div class="font-bold text-m3-on-surface mb-1 capitalize">
                                            {{ $trx->total_jumlah }}x {{ $trx->jenisTernak->jenis_ternak ?? 'Domba' }} {{ $trx->jenis_kelamin_pesanan ?? '-' }}
                                        </div>
                                        @if(isset($trx->jenisTernak->kelas_berat))
                                            <div class="text-[11px] font-bold text-m3-on-surface-variant bg-m3-surface-variant inline-block px-2 py-1 rounded mt-1 uppercase tracking-wider">
                                                Kelas {{ $trx->jenisTernak->kelas_berat }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Total Harga & Kurir --}}
                                    <td class="py-5 px-6 align-top">
                                        <div class="font-bold text-m3-on-surface text-base mb-1">
                                            Rp {{ number_format($trx->total_harga + $trx->ongkir, 0, ',', '.') }}
                                        </div>
                                        <div class="text-[13px] text-m3-on-surface-variant flex items-center gap-1.5 font-medium">
                                            @if($trx->metode_pengiriman === 'ambil_sendiri')
                                                <span class="material-symbols-outlined text-[14px]">store</span> Ambil Langsung
                                            @else
                                                <span class="material-symbols-outlined text-[14px]">local_shipping</span> Kirim ke Alamat
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Pembayaran --}}
                                    <td class="py-5 px-6 align-top">
                                        <div class="font-bold text-m3-on-surface mb-1 capitalize">
                                            {{ $trx->metode_pembayaran === 'transfer' ? 'Transfer Bank' : ($trx->metode_pembayaran === 'cash' ? 'COD' : 'Belum Dipilih') }}
                                        </div>
                                        @if($trx->metode_pembayaran === 'transfer')
                                            @if($trx->bukti_pembayaran)
                                                <div class="text-[13px] text-green-600 font-bold flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Lunas
                                                </div>
                                            @else
                                                <div class="text-[13px] text-yellow-600 font-bold flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[14px]">pending_actions</span> Pending
                                                </div>
                                            @endif
                                        @elseif($trx->metode_pembayaran === 'cash')
                                            <div class="text-[13px] text-m3-on-surface-variant font-medium">Bayar di Tempat</div>
                                        @else
                                            <div class="text-[13px] text-m3-outline font-medium">-</div>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="py-5 px-6 align-top">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-extrabold tracking-wide uppercase {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} border {{ $statusConfig['border'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }} {{ $st === 'diproses' ? 'animate-pulse' : '' }}"></span>
                                            {{ $statusConfig['label'] }}
                                        </span>
                                        @if($trx->is_survei)
                                            @php
                                                $surveiAktif = $trx->survei->sortByDesc('id_survei')->first();
                                            @endphp
                                            @if($surveiAktif && strtolower($surveiAktif->status) !== 'batal')
                                                <div class="text-xs font-bold text-m3-primary mt-2 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[12px]">calendar_clock</span>
                                                    {{ \Carbon\Carbon::parse($surveiAktif->tgl_survei)->translatedFormat('d M') }}
                                                </div>
                                            @endif
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="py-5 px-6 pr-8 align-top text-center w-36">
                                        {{-- Conditional Primary Button --}}
                                        @if(in_array($st, ['pending', 'diproses']) && $trx->metode_pembayaran === 'transfer' && !$trx->bukti_pembayaran)
                                            <button @click="openDetail = true" class="bg-m3-primary text-m3-on-primary px-4 py-2.5 rounded-full text-xs font-bold hover:bg-m3-primary-container hover:text-m3-on-primary-container transition-colors shadow-sm w-full mb-2 active:scale-95">Bayar Sekarang</button>
                                        @elseif($st === 'dikirim')
                                            <div x-data="{ showModalTerima: false }">
                                                <button @click="showModalTerima = true" class="bg-green-600 text-white px-4 py-2.5 rounded-full text-xs font-bold hover:bg-green-700 transition-colors shadow-sm w-full mb-2 active:scale-95 flex items-center justify-center gap-1">
                                                    <span class="material-symbols-outlined text-[16px]">check_circle</span> Terima
                                                </button>

                                                <template x-teleport="body">
                                                    <div x-show="showModalTerima" style="display: none;" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm" @click.self="showModalTerima = false">
                                                        <div x-show="showModalTerima" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-[400px] rounded-3xl bg-white p-8 text-center shadow-2xl">
                                                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-50 border border-green-100">
                                                                <span class="material-symbols-outlined text-green-500 text-3xl">local_shipping</span>
                                                            </div>
                                                            <h4 class="mb-2 text-xl font-bold font-headline text-m3-on-surface">Pesanan Diterima?</h4>
                                                            <p class="mb-8 text-sm text-m3-on-surface-variant font-medium leading-relaxed">Apakah Anda yakin telah menerima pesanan <strong>#TRX-{{ $trx->id_transaksi }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>

                                                            <form action="{{ route('transaksi.selesai', $trx->id_transaksi) }}" method="POST" class="flex justify-center gap-3">
                                                                @csrf
                                                                <button type="button" @click="showModalTerima = false" class="rounded-full border border-m3-surface-container-high bg-m3-surface-container-low px-6 py-3 text-sm font-bold text-m3-on-surface hover:bg-m3-surface-container transition-colors w-full">Belum</button>
                                                                <button type="submit" class="rounded-full bg-green-600 px-6 py-3 text-sm font-bold text-white hover:bg-green-700 shadow-sm w-full transition-colors">Ya, Diterima</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        @endif

                                        {{-- Lihat Detail Toggle --}}
                                        <button @click="openDetail = !openDetail" class="text-m3-primary hover:text-m3-primary-container hover:underline text-xs font-bold w-full transition-colors flex items-center justify-center gap-1">
                                            <span x-text="openDetail ? 'Tutup Detail' : 'Lihat Detail'"></span>
                                            <span class="material-symbols-outlined text-[16px] transition-transform" :class="openDetail ? 'rotate-180' : ''">expand_more</span>
                                        </button>
                                    </td>
                                </tr>

                                {{-- EXPANDABLE ACCORDION DETAIL ROW --}}
                                <tr x-show="openDetail" x-collapse x-cloak class="bg-m3-surface-container-low border-b border-m3-surface-container-high">
                                    <td colspan="6" class="px-8 py-6">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                                            {{-- LEFT COLUMN: Tracking & Assigned Animals --}}
                                            <div>
                                                {{-- Progress Tracker (hanya jika bukan batal) --}}
                                                @if($st !== 'batal')
                                                    <p class="text-xs font-bold text-m3-on-surface-variant uppercase tracking-widest mb-4">Tracking Pesanan</p>
                                                    <div class="flex items-center justify-between mb-8">
                                                        @php
                                                            $steps = [
                                                                ['label' => 'Pending', 'step' => 1],
                                                                ['label' => 'Diproses', 'step' => 2],
                                                                ['label' => 'Dikirim', 'step' => 3],
                                                                ['label' => 'Selesai', 'step' => 4],
                                                            ];
                                                        @endphp
                                                        @foreach ($steps as $i => $s)
                                                            <div class="flex flex-col items-center flex-1">
                                                                <div class="flex items-center w-full">
                                                                    <!-- Left Connector -->
                                                                    <div class="h-1 flex-1 rounded {{ $i > 0 ? ($statusConfig['step'] >= $s['step'] ? 'bg-m3-primary' : 'bg-m3-surface-variant') : 'bg-transparent' }}"></div>
                                                                    
                                                                    <!-- Circle Node -->
                                                                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                                                                        {{ $statusConfig['step'] >= $s['step'] ? 'bg-m3-primary text-white shadow-md' : 'bg-m3-surface-variant text-m3-outline' }}">
                                                                        @if($statusConfig['step'] > $s['step'])
                                                                            <span class="material-symbols-outlined text-[16px]">check</span>
                                                                        @else
                                                                            {{ $s['step'] }}
                                                                        @endif
                                                                    </div>
                                                                    
                                                                    <!-- Right Connector -->
                                                                    <div class="h-1 flex-1 rounded {{ $i < count($steps) - 1 ? ($statusConfig['step'] > $s['step'] ? 'bg-m3-primary' : 'bg-m3-surface-variant') : 'bg-transparent' }}"></div>
                                                                </div>
                                                                <span class="text-[11px] mt-2 font-bold {{ $statusConfig['step'] >= $s['step'] ? 'text-m3-primary' : 'text-m3-outline' }}">{{ $s['label'] }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="rounded-2xl bg-red-50 p-4 text-sm text-red-700 border border-red-200 mb-6 flex items-center gap-2 font-medium">
                                                        <span class="material-symbols-outlined text-red-500">cancel</span>
                                                        Pesanan ini telah dibatalkan.
                                                    </div>
                                                @endif

                                                {{-- Detail Ternak yang Di-assign --}}
                                                <p class="text-xs font-bold text-m3-on-surface-variant uppercase tracking-widest mb-3 mt-4">Ternak Disiapkan ({{ $trx->detailTransaksi->count() }}/{{ $trx->total_jumlah }})</p>
                                                @if($trx->detailTransaksi->count() > 0)
                                                    <div class="space-y-2 mb-6">
                                                        @foreach($trx->detailTransaksi as $detail)
                                                            <div class="flex items-center justify-between rounded-xl bg-white px-4 py-3 text-sm border border-m3-surface-container shadow-sm">
                                                                <div>
                                                                    <span class="font-bold text-m3-primary mr-2">#{{ $detail->id_ternak }}</span>
                                                                    <span class="text-xs text-m3-on-surface-variant font-medium">
                                                                        {{ $detail->ternak->jenis_ternak->jenis_ternak ?? '-' }} &bull;
                                                                        {{ $detail->ternak->berat ?? '-' }}kg &bull;
                                                                        <span class="capitalize">{{ $detail->ternak->jenis_kelamin ?? '-' }}</span>
                                                                    </span>
                                                                </div>
                                                                <span class="text-xs font-bold text-m3-on-surface">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="rounded-2xl bg-blue-50 p-4 text-xs text-blue-800 border border-blue-100 mb-6 flex items-center gap-2">
                                                        <span class="material-symbols-outlined text-blue-500 text-lg">info</span>
                                                        Admin sedang memverifikasi dan menyiapkan ternak terbaik untuk pesanan Anda.
                                                    </div>
                                                @endif

                                                {{-- Tombol Cancel / Bukti --}}
                                                <div class="flex items-center gap-4 mt-6">
                                                    @if($trx->bukti_pembayaran)
                                                        <a href="{{ $trx->bukti_pembayaran }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-m3-primary hover:text-m3-primary-container font-bold border border-m3-primary/20 px-4 py-2 rounded-full hover:bg-m3-primary/5 transition-colors">
                                                            <span class="material-symbols-outlined text-[18px]">receipt</span> Lihat Bukti Transfer
                                                        </a>
                                                    @endif

                                                    @if(in_array($st, ['pending', 'diproses']))
                                                        @if($trx->metode_pembayaran === 'transfer' && !empty($trx->bukti_pembayaran))
                                                            <div class="text-[11px] text-red-500 font-medium italic">
                                                                *Pesanan transfer yang sudah dibayar tidak bisa dibatalkan.
                                                            </div>
                                                        @else
                                                            <form action="{{ route('transaksi.cancel', $trx->id_transaksi) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan #TRX-{{ $trx->id_transaksi }}?')">
                                                                @csrf
                                                                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-red-50 px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-100 border border-red-200 transition-colors">
                                                                    <span class="material-symbols-outlined text-[16px]">close</span> Batalkan Pesanan
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- RIGHT COLUMN: Survei & Pembayaran --}}
                                            <div>
                                                @if($trx->is_survei)
                                                    <div class="bg-white p-6 rounded-3xl border border-m3-surface-container shadow-sm">
                                                        <h4 class="text-sm font-bold text-m3-on-surface uppercase tracking-widest mb-4">Informasi Survei</h4>

                                                        @php
                                                            $surveiAktif = $trx->survei->sortByDesc('id_survei')->first();
                                                        @endphp

                                                        @if($surveiAktif)
                                                            <div class="rounded-2xl p-4 mb-4 {{ strtolower($surveiAktif->status) === 'batal' ? 'bg-red-50 border border-red-200' : 'bg-m3-surface-container-low' }}">
                                                                <div class="flex justify-between items-start mb-3">
                                                                    <div>
                                                                        <p class="text-sm font-bold text-m3-on-surface mb-1 flex items-center gap-2">
                                                                            <span class="material-symbols-outlined text-[16px] text-m3-primary">event_available</span>
                                                                            {{ \Carbon\Carbon::parse($surveiAktif->tgl_survei)->translatedFormat('d M Y, H:i') }}
                                                                        </p>
                                                                        <p class="text-xs text-m3-on-surface-variant font-medium">{{ $surveiAktif->ket ?? 'Tidak ada keterangan khusus' }}</p>
                                                                    </div>
                                                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider
                                                                        {{ strtolower($surveiAktif->status) == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                                        {{ strtolower($surveiAktif->status) == 'disetujui' ? 'bg-blue-100 text-blue-800' : '' }}
                                                                        {{ strtolower($surveiAktif->status) == 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                                                                        {{ strtolower($surveiAktif->status) == 'batal' ? 'bg-red-100 text-red-800' : '' }}
                                                                    ">
                                                                        {{ $surveiAktif->status }}
                                                                    </span>
                                                                </div>

                                                                @if(strtolower($surveiAktif->status) === 'batal' && $surveiAktif->ket_admin)
                                                                    <div class="mt-3 text-xs text-red-700 bg-red-100/50 p-2 rounded-xl border border-red-200">
                                                                        <b>Alasan Batal:</b> {{ $surveiAktif->ket_admin }}
                                                                    </div>
                                                                @endif

                                                                {{-- Tombol Ajukan Ulang --}}
                                                                @if(strtolower($surveiAktif->status) === 'batal' && $trx->batas_survei && \Carbon\Carbon::parse($trx->batas_survei)->isFuture() && $st !== 'batal')
                                                                    <div class="mt-4 pt-4 border-t border-red-200"
                                                                         x-data="{
                                                                             showFormResubmit: false,
                                                                             selectedDate: '',
                                                                             bookedTimes: [],
                                                                             fetchBookedTimes() {
                                                                                 if (!this.selectedDate) return;
                                                                                 fetch('/api/jadwal/cek?tanggal=' + this.selectedDate)
                                                                                     .then(res => res.json())
                                                                                     .then(data => {
                                                                                         this.bookedTimes = data;
                                                                                     });
                                                                             }
                                                                         }">
                                                                        <p class="text-[11px] text-red-600 font-medium mb-3">Anda dapat mengajukan ulang jadwal survei maksimal hingga {{ \Carbon\Carbon::parse($trx->batas_survei)->translatedFormat('d M Y') }}.</p>
                                                                        <button @click="showFormResubmit = !showFormResubmit" class="bg-white text-m3-primary border border-m3-primary/30 px-4 py-2 rounded-full text-xs font-bold hover:bg-m3-primary/5 transition-colors">
                                                                            Ajukan Ulang Jadwal
                                                                        </button>

                                                                        <form x-show="showFormResubmit" x-collapse class="mt-4 flex flex-wrap gap-2" action="{{ route('transaksi.ajukan-survei', $trx->id_transaksi) }}" method="POST">
                                                                            @csrf
                                                                            <div class="relative w-full sm:w-auto">
                                                                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[16px] text-m3-outline">calendar_month</span>
                                                                                <input type="text" name="tanggal_survei" required placeholder="Pilih Tanggal"
                                                                                    x-model="selectedDate"
                                                                                    class="pl-9 pr-3 py-2 rounded-xl border border-m3-surface-variant text-xs w-full focus:ring-1 focus:ring-m3-primary outline-none"
                                                                                    x-init="flatpickr($el, {
                                                                                        dateFormat: 'Y-m-d',
                                                                                        minDate: 'today',
                                                                                        maxDate: '{{ $trx->batas_survei }}',
                                                                                        onChange: function(selectedDates, dateStr) {
                                                                                            selectedDate = dateStr;
                                                                                            fetchBookedTimes();
                                                                                        }
                                                                                    })">
                                                                            </div>
                                                                            <select name="waktu_survei" required class="px-3 py-2 rounded-xl border border-m3-surface-variant text-xs focus:ring-1 focus:ring-m3-primary outline-none">
                                                                                <template x-for="time in ['09:00', '11:00', '13:00', '15:00']" :key="time">
                                                                                    <option :value="time" x-text="time" :disabled="bookedTimes.includes(time)"></option>
                                                                                </template>
                                                                            </select>
                                                                            <button type="submit" class="bg-m3-primary text-white rounded-xl px-4 py-2 text-xs font-bold hover:bg-m3-primary-container shadow-sm">Simpan</button>
                                                                        </form>
                                                                    </div>
                                                                @endif

                                                                {{-- Form Upload Pembayaran --}}
                                                                @if(strtolower($surveiAktif->status) === 'selesai' && !$trx->bukti_pembayaran && $st !== 'batal')
                                                                    <div class="mt-4 pt-4 border-t border-m3-surface-container-high">
                                                                        @php
                                                                            $sisaWaktu = \Carbon\Carbon::parse($surveiAktif->tgl_survei)->addHours(24);
                                                                        @endphp
                                                                        <div class="rounded-2xl bg-yellow-50 p-4 mb-4 border border-yellow-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3"
                                                                             x-data="{
                                                                                 deadline: new Date('{{ $sisaWaktu->toIso8601String() }}').getTime(),
                                                                                 now: new Date().getTime(),
                                                                                 timeLeft: 0,
                                                                                 formatTime(ms) {
                                                                                     if (ms <= 0) return 'Habis';
                                                                                     let h = Math.floor((ms % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                                                     let m = Math.floor((ms % (1000 * 60 * 60)) / (1000 * 60));
                                                                                     let s = Math.floor((ms % (1000 * 60)) / 1000);
                                                                                     return h + 'j ' + m + 'm ' + s + 's';
                                                                                 },
                                                                                 init() {
                                                                                     this.timeLeft = this.deadline - this.now;
                                                                                     setInterval(() => {
                                                                                         this.now = new Date().getTime();
                                                                                         this.timeLeft = this.deadline - this.now;
                                                                                     }, 1000);
                                                                                 }
                                                                             }">
                                                                            <div>
                                                                                <p class="text-xs text-yellow-800 font-bold mb-1 flex items-center gap-1">
                                                                                    <span class="material-symbols-outlined text-[16px]">warning</span> Selesaikan Pembayaran
                                                                                </p>
                                                                                <p class="text-[11px] text-yellow-700">Survei telah selesai. Lanjutkan transaksi.</p>
                                                                            </div>
                                                                            <div class="bg-yellow-100 px-3 py-1.5 rounded-lg text-center shadow-inner">
                                                                                <p class="text-[10px] text-yellow-800 font-bold uppercase tracking-widest mb-0.5">Sisa Waktu</p>
                                                                                <div class="text-sm font-bold text-yellow-900 font-mono" x-text="formatTime(timeLeft)">--j --m --s</div>
                                                                            </div>
                                                                        </div>

                                                                        <form action="{{ route('transaksi.upload-bukti', $trx->id_transaksi) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                                                                            @csrf
                                                                            <div>
                                                                                <label class="text-[11px] font-bold text-m3-on-surface-variant uppercase tracking-wider mb-1 block">Metode Pengiriman</label>
                                                                                <select name="metode_pengiriman" required class="w-full rounded-xl border border-m3-surface-container-high text-sm px-4 py-2.5 focus:ring-1 focus:ring-m3-primary outline-none bg-m3-surface-container-lowest">
                                                                                    <option value="ambil_sendiri">Ambil Sendiri (Gratis Ongkir)</option>
                                                                                    <option value="dikirim">Kirim ke Alamat (Biaya Ongkir Menyesuaikan)</option>
                                                                                </select>
                                                                            </div>
                                                                            <div>
                                                                                <label class="text-[11px] font-bold text-m3-on-surface-variant uppercase tracking-wider mb-1 block">Metode Pembayaran</label>
                                                                                <select name="metode_pembayaran" required class="w-full rounded-xl border border-m3-surface-container-high text-sm px-4 py-2.5 focus:ring-1 focus:ring-m3-primary outline-none bg-m3-surface-container-lowest" onchange="document.getElementById('upload_area_{{$trx->id_transaksi}}').style.display = this.value === 'transfer' ? 'block' : 'none'">
                                                                                    <option value="transfer">Transfer Bank</option>
                                                                                    <option value="cash">Cash on Delivery (COD)</option>
                                                                                </select>
                                                                            </div>
                                                                            <div id="upload_area_{{$trx->id_transaksi}}">
                                                                                <label class="text-[11px] font-bold text-m3-on-surface-variant uppercase tracking-wider mb-1 block">Bukti Transfer (Max 2MB)</label>
                                                                                <input type="file" name="bukti_pembayaran" accept="image/*" class="w-full text-xs text-m3-on-surface-variant file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-m3-primary/10 file:text-m3-primary hover:file:bg-m3-primary/20 transition-all cursor-pointer">
                                                                            </div>
                                                                            <button type="submit" class="bg-m3-primary text-white rounded-full px-5 py-3 text-sm font-bold shadow-sm hover:bg-m3-primary-container active:scale-95 transition-all mt-2">Simpan & Konfirmasi</button>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <div class="rounded-2xl p-4 bg-m3-surface-container-low text-xs text-m3-on-surface-variant font-medium flex items-center gap-2">
                                                                <span class="material-symbols-outlined text-m3-outline text-[18px]">event_busy</span> Data survei tidak ditemukan.
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    {{-- Jika bukan pesanan survei, tampilkan info pengiriman detail --}}
                                                    <div class="bg-white p-6 rounded-3xl border border-m3-surface-container shadow-sm">
                                                        <h4 class="text-sm font-bold text-m3-on-surface uppercase tracking-widest mb-4">Informasi Pengiriman</h4>
                                                        <div class="space-y-4">
                                                            <div>
                                                                <p class="text-[10px] text-m3-outline uppercase tracking-widest font-bold mb-1">Kurir</p>
                                                                <p class="text-sm font-bold text-m3-on-surface">{{ $trx->kurir ?? 'Belum ditentukan' }}</p>
                                                            </div>
                                                            @if($trx->no_kurir)
                                                                <div>
                                                                    <p class="text-[10px] text-m3-outline uppercase tracking-widest font-bold mb-1">No HP Kurir</p>
                                                                    <p class="text-sm font-bold text-m3-on-surface">{{ $trx->no_kurir }}</p>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <p class="text-[10px] text-m3-outline uppercase tracking-widest font-bold mb-1">Alamat Tujuan</p>
                                                                <p class="text-xs text-m3-on-surface-variant font-medium leading-relaxed">{{ Auth::user()->alamat ?? '-' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            @empty
                                <tbody class="text-sm">
                                <tr>
                                    <td colspan="6" class="py-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 rounded-full bg-m3-surface-container flex items-center justify-center mb-4">
                                                <span class="material-symbols-outlined text-3xl text-m3-outline">receipt_long</span>
                                            </div>
                                            <h3 class="text-lg font-bold text-m3-on-surface mb-1">Belum ada riwayat transaksi</h3>
                                            <p class="text-m3-on-surface-variant text-sm mb-6">Ayo mulai pesanan pertama Anda sekarang.</p>
                                            <a href="{{ route('katalog') }}" class="bg-m3-primary text-m3-on-primary px-6 py-3 rounded-full font-bold text-sm hover:bg-m3-primary-container transition-colors">Telusuri Katalog</a>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            @endforelse
                    </table>
                </div>

                <!-- Pagination -->
                @if ($data_transaksi->hasPages())
                    <div class="p-6 md:px-8 border-t border-m3-surface-container-high bg-m3-surface-container-lowest">
                        {{ $data_transaksi->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </section>

    </main>

    {{-- FOOTER --}}
    <x-landing.footer wa-number="{{ config('smartsaka.wa_number') }}"
        email="{{ config('smartsaka.email') }}" :address="config('smartsaka.address')"
        map-src="{{ config('smartsaka.maps_embed_src') }}" />

@endsection
