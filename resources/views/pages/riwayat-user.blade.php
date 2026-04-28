@extends('layouts.app')

@section('content')
    {{-- Flash Message Sukses --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
            class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-800 border border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800/30 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-green-600 hover:text-green-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
    @endif

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">Pesanan Saya</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau status dan riwayat seluruh pesanan Anda.</p>
        </div>
        <a href="{{ route('transaksi.create') }}"
            class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Buat Pesanan Baru
        </a>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        @php
            $statCards = [
                ['label' => 'Total Pesanan', 'value' => $stats['total'], 'color' => 'brand', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['label' => 'Menunggu', 'value' => $stats['pending'], 'color' => 'yellow', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Diproses', 'value' => $stats['diproses'], 'color' => 'blue', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                ['label' => 'Dikirim', 'value' => $stats['dikirim'], 'color' => 'purple', 'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                ['label' => 'Selesai', 'value' => $stats['selesai'], 'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
        @endphp
        @foreach ($statCards as $card)
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-{{ $card['color'] }}-100 dark:bg-{{ $card['color'] }}-500/10">
                        <svg class="w-5 h-5 text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $card['value'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $card['label'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filter Bar --}}
    <div class="mb-4 flex flex-col sm:flex-row items-start sm:items-center gap-3">
        <form action="{{ route('transaksi.riwayat') }}" method="GET" class="relative">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari ID Transaksi..."
                class="dark:bg-gray-900 h-11 w-full sm:w-56 rounded-lg border border-gray-300 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-800 transition focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
        </form>

        <div class="flex flex-wrap gap-2">
            @php
                $currentStatus = request('status', 'semua');
                $filters = [
                    'semua' => 'Semua',
                    'pending' => 'Menunggu',
                    'diproses' => 'Diproses',
                    'dikirim' => 'Dikirim',
                    'selesai' => 'Selesai',
                    'batal' => 'Batal',
                ];
            @endphp
            @foreach ($filters as $key => $label)
                <a href="{{ route('transaksi.riwayat', array_merge(request()->except('page'), ['status' => $key])) }}"
                    class="rounded-full px-4 py-1.5 text-sm font-medium border transition
                    {{ $currentStatus === $key
                        ? 'bg-brand-500 text-white border-brand-500'
                        : 'bg-white text-gray-600 border-gray-300 hover:border-brand-400 hover:text-brand-600 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:border-brand-500' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Daftar Pesanan --}}
    <div class="flex flex-col gap-4">
        @forelse ($data_transaksi as $trx)
            @php
                $st = strtolower($trx->status);
                $statusConfig = match($st) {
                    'pending'  => ['label' => 'Menunggu Konfirmasi', 'bg' => 'bg-yellow-100 dark:bg-yellow-500/10', 'text' => 'text-yellow-700 dark:text-yellow-400', 'dot' => 'bg-yellow-500', 'step' => 1],
                    'diproses' => ['label' => 'Sedang Diproses',    'bg' => 'bg-blue-100 dark:bg-blue-500/10',     'text' => 'text-blue-700 dark:text-blue-400',     'dot' => 'bg-blue-500',   'step' => 2],
                    'dikirim'  => ['label' => 'Dalam Pengiriman',   'bg' => 'bg-purple-100 dark:bg-purple-500/10', 'text' => 'text-purple-700 dark:text-purple-400', 'dot' => 'bg-purple-500', 'step' => 3],
                    'selesai'  => ['label' => 'Pesanan Selesai',    'bg' => 'bg-green-100 dark:bg-green-500/10',   'text' => 'text-green-700 dark:text-green-400',   'dot' => 'bg-green-500',  'step' => 4],
                    'batal'    => ['label' => 'Dibatalkan',         'bg' => 'bg-red-100 dark:bg-red-500/10',       'text' => 'text-red-700 dark:text-red-400',       'dot' => 'bg-red-500',    'step' => 0],
                    default    => ['label' => ucfirst($st),         'bg' => 'bg-gray-100 dark:bg-gray-700',        'text' => 'text-gray-700 dark:text-gray-300',     'dot' => 'bg-gray-500',   'step' => 0],
                };
            @endphp

            <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden"
                x-data="{ open: false }">

                {{-- Header Card --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-800 cursor-pointer"
                    @click="open = !open">
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-brand-500 text-sm">#TRX-{{ $trx->id_transaksi }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($trx->tgl_transaksi)->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                            {{ $statusConfig['label'] }}
                        </span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Body Card --}}
                <div class="px-5 py-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Produk</p>
                            <p class="capitalize text-sm font-semibold text-gray-800 dark:text-white">{{ $trx->jenisTernak->jenis_ternak ?? 'Domba' }} &bull; {{ ucfirst($trx->jenis_kelamin_pesanan ?? '-') }}</p>
                            <p class="text-xs text-gray-500">{{ $trx->total_jumlah }} Ekor</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Harga</p>
                            <p class="text-sm font-bold text-green-600 dark:text-green-400">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ $trx->metode_pembayaran }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Pengiriman</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $trx->kurir !== '-' ? $trx->kurir : 'Belum ditentukan' }}</p>
                            @if($trx->no_kurir && $trx->no_kurir !== '-')
                                <p class="text-xs text-gray-500">No HP Kurir: {{ $trx->no_kurir }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Detail Expandable --}}
                <div x-show="open" x-collapse x-cloak class="border-t border-gray-100 dark:border-gray-800 px-5 py-4 bg-gray-50/50 dark:bg-gray-800/30">

                    {{-- Progress Tracker (hanya jika bukan batal) --}}
                    @if($st !== 'batal')
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-3">Tracking Pesanan</p>
                        <div class="flex items-center justify-between mb-5">
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
                                        @if($i > 0)
                                            <div class="h-1 flex-1 rounded {{ $statusConfig['step'] >= $s['step'] ? 'bg-brand-500' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                                        @endif
                                        <div class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                                            {{ $statusConfig['step'] >= $s['step'] ? 'bg-brand-500 text-white' : 'bg-gray-200 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                            @if($statusConfig['step'] > $s['step'])
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            @else
                                                {{ $s['step'] }}
                                            @endif
                                        </div>
                                        @if($i < count($steps) - 1)
                                            <div class="h-1 flex-1 rounded {{ $statusConfig['step'] > $s['step'] ? 'bg-brand-500' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                                        @endif
                                    </div>
                                    <span class="text-[10px] mt-1.5 text-gray-500 dark:text-gray-400">{{ $s['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-lg bg-red-50 dark:bg-red-900/20 p-3 text-sm text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/30 mb-4">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            Pesanan ini telah dibatalkan.
                        </div>
                    @endif

                    {{-- Detail Ternak yang Di-assign --}}
                    @if($trx->detailTransaksi->count() > 0)
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Ternak yang Di-assign ({{ $trx->detailTransaksi->count() }}/{{ $trx->total_jumlah }})</p>
                        <div class="space-y-1.5 mb-4">
                            @foreach($trx->detailTransaksi as $detail)
                                <div class="flex items-center justify-between rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-sm border border-gray-100 dark:border-gray-700">
                                    <div>
                                        <span class="font-medium text-brand-500">#{{ $detail->id_ternak }}</span>
                                        <span class="text-xs text-gray-500 ml-1">{{ $detail->ternak->jenis_ternak->jenis_ternak ?? '-' }} &bull; {{ $detail->ternak->berat ?? '-' }}kg &bull; {{ ucfirst($detail->ternak->jenis_kelamin ?? '-') }}</span>
                                    </div>
                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-md bg-blue-50 dark:bg-blue-900/20 p-3 text-xs text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-800/30 mb-4">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            Admin belum memilih ternak untuk pesanan Anda. Mohon tunggu proses verifikasi.
                        </div>
                    @endif

                    {{-- Info Harga --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm mb-4">
                        <div>
                            <p class="text-xs text-gray-400">Jenis Ternak</p>
                            <p class="capitalize font-medium text-gray-700 dark:text-gray-300">{{ $trx->jenisTernak->jenis_ternak ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Harga/Ekor</p>
                            <p class="font-medium text-gray-700 dark:text-gray-300">Rp {{ number_format(($trx->total_jumlah > 0 ? $trx->total_harga / $trx->total_jumlah : 0), 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Metode Bayar</p>
                            <p class="font-medium text-gray-700 dark:text-gray-300 capitalize">{{ $trx->metode_pembayaran }}</p>
                        </div>
                    </div>

                    {{-- Bukti Pembayaran + Cancel Button --}}
                    <div class="flex items-center justify-between">
                        <div>
                            @if($trx->bukti_pembayaran)
                                <a href="{{ $trx->bukti_pembayaran }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-brand-500 hover:text-brand-600 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Lihat Bukti Transfer
                                </a>
                            @endif
                        </div>

                        @if(in_array($st, ['pending', 'diproses']))
                            @if($trx->metode_pembayaran === 'transfer' && !empty($trx->bukti_pembayaran))
                                <div class="text-[10px] text-red-500 font-medium italic max-w-[200px] text-right">
                                    *Pesanan transfer yang sudah dibayar tidak bisa dibatalkan.
                                </div>
                            @else
                                <form action="{{ route('transaksi.cancel', $trx->id_transaksi) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan #TRX-{{ $trx->id_transaksi }}?')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-red-500 px-4 py-2 text-xs font-medium text-white hover:bg-red-600 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            @endif
                        @elseif($st === 'dikirim')
                            <div x-data="{ showModalTerima: false }">
                                <button type="button" @click="showModalTerima = true" class="inline-flex items-center gap-1.5 rounded-lg bg-green-500 px-4 py-2 text-xs font-medium text-white hover:bg-green-600 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Pesanan Diterima
                                </button>

                                <template x-teleport="body">
                                    <div x-show="showModalTerima" style="display: none;" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm" @click.self="showModalTerima = false">
                                        <div x-show="showModalTerima" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-[400px] rounded-3xl bg-white p-6 dark:bg-gray-900 text-center">
                                            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-green-100 dark:bg-green-500/20">
                                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                            <h4 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">Pesanan Diterima?</h4>
                                            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin telah menerima pesanan <strong>#TRX-{{ $trx->id_transaksi }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                                            
                                            <form action="{{ route('transaksi.selesai', $trx->id_transaksi) }}" method="POST" class="flex justify-center gap-3">
                                                @csrf
                                                <button type="button" @click="showModalTerima = false" class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Belum</button>
                                                <button type="submit" class="rounded-lg bg-green-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-green-600">Ya, Sudah Diterima</button>
                                            </form>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        @endif
                    </div>

                    {{-- Bagian Survei (Jika is_survei = true) --}}
                    @if($trx->is_survei)
                        <div class="mt-5 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-3">Informasi Survei</h4>

                            @php
                                $surveiAktif = $trx->survei->sortByDesc('id_survei')->first();
                            @endphp

                            @if($surveiAktif)
                                <div class="rounded-lg border p-4 {{ strtolower($surveiAktif->status) === 'batal' ? 'bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800/30' : 'bg-white border-gray-200 dark:bg-gray-800 dark:border-gray-700' }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white">Jadwal: {{ \Carbon\Carbon::parse($surveiAktif->tgl_survei)->translatedFormat('d M Y, H:i') }}</p>
                                            <p class="text-xs text-gray-500">{{ $surveiAktif->ket ?? 'Tidak ada keterangan khusus' }}</p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                            {{ strtolower($surveiAktif->status) == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-500/10 dark:text-yellow-400' : '' }}
                                            {{ strtolower($surveiAktif->status) == 'disetujui' ? 'bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400' : '' }}
                                            {{ strtolower($surveiAktif->status) == 'selesai' ? 'bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-400' : '' }}
                                            {{ strtolower($surveiAktif->status) == 'batal' ? 'bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-400' : '' }}
                                        ">
                                            {{ ucfirst($surveiAktif->status) }}
                                        </span>
                                    </div>

                                    {{-- Pesan batal dari admin --}}
                                    @if(strtolower($surveiAktif->status) === 'batal' && $surveiAktif->ket_admin)
                                        <div class="mt-3 text-sm text-red-700 dark:text-red-400 border-t border-red-200 dark:border-red-800/30 pt-2">
                                            <b>Alasan Pembatalan:</b> {{ $surveiAktif->ket_admin }}
                                        </div>
                                    @endif

                                    {{-- Tombol Ajukan Ulang Survei jika batal dan belum lewat 7 hari --}}
                                    @if(strtolower($surveiAktif->status) === 'batal' && $trx->batas_survei && \Carbon\Carbon::parse($trx->batas_survei)->isFuture() && $st !== 'batal')
                                        <div class="mt-4 pt-3 border-t border-red-200 dark:border-red-800/30" 
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
                                            <p class="text-xs text-red-600 dark:text-red-400 mb-2">Anda dapat mengajukan ulang jadwal survei maksimal hingga {{ \Carbon\Carbon::parse($trx->batas_survei)->translatedFormat('d M Y') }}. (Lewat dari itu pesanan otomatis batal)</p>
                                            <button @click="showFormResubmit = !showFormResubmit" class="text-xs font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400">
                                                Ajukan Ulang Survei &rarr;
                                            </button>

                                            <form x-show="showFormResubmit" x-collapse class="mt-3 flex gap-2" action="{{ route('transaksi.ajukan-survei', $trx->id_transaksi) }}" method="POST">
                                                @csrf
                                                <input type="text" name="tanggal_survei" required placeholder="Pilih Tanggal" 
                                                    x-model="selectedDate"
                                                    class="h-9 w-32 rounded border border-gray-300 text-xs px-2 dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                                                    x-init="flatpickr($el, { 
                                                        dateFormat: 'Y-m-d', 
                                                        minDate: 'today', 
                                                        maxDate: '{{ $trx->batas_survei }}',
                                                        onChange: function(selectedDates, dateStr) {
                                                            selectedDate = dateStr;
                                                            fetchBookedTimes();
                                                        }
                                                    })">
                                                
                                                <select name="waktu_survei" required class="h-9 w-24 rounded border border-gray-300 text-xs px-2 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                                    <template x-for="time in ['09:00', '11:00', '13:00', '15:00']" :key="time">
                                                        <option :value="time" x-text="time" :disabled="bookedTimes.includes(time)"></option>
                                                    </template>
                                                </select>
                                                <button type="submit" class="bg-brand-500 text-white rounded px-3 text-xs hover:bg-brand-600">Ajukan</button>
                                            </form>
                                        </div>
                                    @endif

                                    {{-- Form Upload Pembayaran jika Survei Selesai dan belum bayar --}}
                                    @if(strtolower($surveiAktif->status) === 'selesai' && !$trx->bukti_pembayaran && $st !== 'batal')
                                        <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                                            @php
                                                $sisaWaktu = \Carbon\Carbon::parse($surveiAktif->tgl_survei)->addHours(24);
                                            @endphp
                                            <div class="rounded-md bg-amber-50 p-3 mb-3 border border-amber-200 dark:bg-amber-900/20 dark:border-amber-800/30 flex items-center justify-between"
                                                 x-data="{ 
                                                     deadline: new Date('{{ $sisaWaktu->toIso8601String() }}').getTime(),
                                                     now: new Date().getTime(),
                                                     timeLeft: 0,
                                                     formatTime(ms) {
                                                         if (ms <= 0) return 'Waktu Habis';
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
                                                    <p class="text-xs text-amber-800 dark:text-amber-400 font-medium"><i class="fas fa-exclamation-triangle mr-1"></i> Survei telah selesai! Silakan selesaikan pembayaran.</p>
                                                    <p class="text-[10px] text-amber-700 dark:text-amber-500 mt-1">Batas waktu: {{ $sisaWaktu->translatedFormat('d M Y, H:i') }}</p>
                                                </div>
                                                <div class="text-right ml-3 flex-shrink-0">
                                                    <p class="text-[10px] text-amber-700 dark:text-amber-500 mb-0.5">Sisa Waktu</p>
                                                    <div class="bg-amber-100 dark:bg-amber-800/40 px-2 py-1 rounded text-xs font-bold text-amber-800 dark:text-amber-300 font-mono tracking-wider" x-text="formatTime(timeLeft)">
                                                        --j --m --s
                                                    </div>
                                                </div>
                                            </div>

                                            <form action="{{ route('transaksi.upload-bukti', $trx->id_transaksi) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">
                                                @csrf
                                                <div>
                                                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Metode Pembayaran</label>
                                                    <select name="metode_pembayaran" required class="mt-1 block w-full rounded border border-gray-300 text-sm p-2 dark:bg-gray-800 dark:border-gray-600 dark:text-white" onchange="document.getElementById('upload_area_{{$trx->id_transaksi}}').style.display = this.value === 'transfer' ? 'block' : 'none'">
                                                        <option value="transfer">Transfer Bank</option>
                                                        <option value="cash">Cash on Delivery (COD)</option>
                                                    </select>
                                                </div>
                                                <div id="upload_area_{{$trx->id_transaksi}}">
                                                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Bukti Transfer (Max 2MB)</label>
                                                    <input type="file" name="bukti_pembayaran" accept="image/*" class="mt-1 block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                                                </div>
                                                <button type="submit" class="self-start rounded bg-brand-500 px-4 py-2 text-xs font-medium text-white hover:bg-brand-600">Simpan & Selesai</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p class="text-xs text-gray-500 italic">Data survei tidak ditemukan.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 py-16 text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 mb-1">Belum ada pesanan</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mb-4">Ayo buat pesanan pertama Anda!</p>
                <a href="{{ route('transaksi.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Buat Pesanan
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($data_transaksi->hasPages())
        <div class="mt-6">
            {{ $data_transaksi->withQueryString()->links() }}
        </div>
    @endif
@endsection
