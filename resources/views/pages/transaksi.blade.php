@extends('layouts.app')

@section('content')
    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 3000)"
             class="mb-4 flex items-center justify-between rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800 border border-green-200">
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
             class="mb-4 flex items-center justify-between rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800 border border-red-200">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ session('error') }}
            </div>
            <button @click="show = false" class="text-red-500 hover:text-red-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Operasional Transaksi
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau pesanan yang berstatus Pending hingga
                Dikirim.</p>
        </div>

        {{-- Tombol Tambah Data --}}
        <a href="{{ route('transaksi.create.admin') }}"
            class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600">
            <svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 4.5v15m7.5-7.5h-15" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            Tambah Transaksi
        </a>
    </div>

    {{-- TABEL DATA --}}
    <div class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900">
        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 text-left dark:bg-gray-800 border-b border-gray-200 dark:border-gray-800">
                        <th class="py-4 px-4 font-medium text-black dark:text-white whitespace-nowrap">ID Transaksi</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white whitespace-nowrap">Pembeli</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white whitespace-nowrap">Total Harga & Kurir</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white text-center whitespace-nowrap">Pembayaran</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white text-center whitespace-nowrap">Status</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data_transaksi as $transaksi)
                        <tr x-data="{ modalEdit: {{ (session('open_modal_trx') == $transaksi->id_transaksi || ($errors->any() && old('_method') === 'PUT' && old('id_transaksi_edit') == $transaksi->id_transaksi)) ? 'true' : 'false' }} }"
                            class="border-b border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">

                            <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                <span class="font-bold text-brand-500">#TRX-{{ $transaksi->id_transaksi }}</span><br>
                                <span
                                    class="text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->translatedFormat('d M Y, H:i') }}</span>
                            </td>

                            <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                <span
                                    class="font-semibold text-black dark:text-white">{{ $transaksi->akun->nama ?? 'Admin/Guest' }}</span><br>
                                <span class="text-xs text-gray-500">{{ $transaksi->akun->no_hp ?? '-' }}</span>
                            </td>


                            <td class="py-4 px-4 text-gray-800 dark:text-gray-300">
                                <span class="font-bold text-green-600 dark:text-green-400">Rp
                                    {{ number_format($transaksi->total_harga + $transaksi->ongkir, 0, ',', '.') }}</span><br>
                                @if($transaksi->ongkir > 0)
                                    <span class="text-xs text-gray-500">(Ongkir: Rp {{ number_format($transaksi->ongkir, 0, ',', '.') }})</span><br>
                                @endif
                                @if($transaksi->metode_pengiriman === 'ambil_sendiri')
                                    <span class="inline-flex items-center gap-1 mt-1 rounded-full bg-green-100 dark:bg-green-500/10 px-2 py-0.5 text-[10px] font-medium text-green-700 dark:text-green-400">Ambil Sendiri</span>
                                @else
                                    <span class="text-xs text-gray-500"><i class="fas fa-truck text-[10px]"></i>
                                        {{ $transaksi->kurir ?? '-' }} ({{ $transaksi->no_kurir ?? '-' }})</span>
                                @endif
                            </td>

                            {{-- Pembayaran --}}
                            <td class="py-4 px-4 text-center">
                                @if($transaksi->metode_pembayaran === 'transfer')
                                    @if($transaksi->bukti_pembayaran)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 dark:bg-green-500/10 px-3 py-1.5 text-xs font-semibold text-green-700 dark:text-green-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Bukti Ada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-100 dark:bg-red-500/10 px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Belum Upload
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 dark:bg-amber-500/10 px-3 py-1.5 text-xs font-medium text-amber-700 dark:text-amber-400">
                                        Cash On Delivery
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-4 text-center">
                                @php $st = strtolower($transaksi->status); @endphp
                                @if ($st == 'dikirim')
                                    <span
                                        class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700 dark:bg-purple-500/10">Dikirim</span>
                                @elseif($st == 'diproses')
                                    <span
                                        class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/10">Diproses</span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-500/10">Pending</span>
                                @endif
                            </td>

                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center">
                                    <button @click="modalEdit = true" type="button"
                                        class="inline-flex items-center justify-center font-semibold gap-2 rounded-xl transition px-4 py-2 text-sm bg-brand-500 text-white hover:bg-brand-600 cursor-pointer shadow-sm">Detail / Edit</button>
                                </div>
                            </td>

                            {{-- MODAL EDIT TRANSAKSI --}}
                            <template x-teleport="body">
                                <div x-show="modalEdit" style="display: none;"
                                    class="fixed inset-0 z- flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                                    @click.self="modalEdit = false">
                                    <div class="no-scrollbar relative w-full max-w-[600px] max-h-[90vh] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-8"
                                        x-data="{
                                             selectedTernak: [],
                                             maxSelect: {{ $transaksi->total_jumlah - $transaksi->detailTransaksi->count() }},
                                             toggleTernak(id) {
                                                 if (this.selectedTernak.includes(id)) {
                                                     this.selectedTernak = this.selectedTernak.filter(x => x !== id);
                                                 } else {
                                                     if (this.selectedTernak.length < this.maxSelect) {
                                                         this.selectedTernak.push(id);
                                                     }
                                                 }
                                             }
                                        }"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100">
                                        <div class="mb-6">
                                            <h4 class="mb-1 text-xl font-semibold text-gray-800 dark:text-white/90">Update Transaksi #TRX-{{ $transaksi->id_transaksi }}</h4>
                                            <p class="text-sm text-gray-500">Pesanan: {{ $transaksi->total_jumlah }} ekor &bull; Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
                                        </div>

                                        @if ($errors->any() && (old('id_transaksi_edit') == $transaksi->id_transaksi || session('open_modal_trx') == $transaksi->id_transaksi))
                                            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                                                <ul class="list-disc list-inside">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        {{-- SECTION: ASSIGN TERNAK --}}
                                        @php
                                            $hargaPerEkor = $transaksi->total_jumlah > 0 ? $transaksi->total_harga / $transaksi->total_jumlah : 0;
                                            $jkPesanan = $transaksi->jenis_kelamin_pesanan ?? null;
                                            $idJenisTrx = $transaksi->id_jenis_ternak ?? null;

                                            // Filter ternak yang cocok dengan kriteria pesanan
                                            $ternakCocok = ($idJenisTrx && $jkPesanan)
                                                ? $data_ternak->filter(function($t) use ($idJenisTrx, $jkPesanan, $hargaPerEkor) {
                                                    return $t->id_jenis_ternak == $idJenisTrx
                                                        && $t->jenis_kelamin == $jkPesanan
                                                        && $t->harga == $hargaPerEkor;
                                                })
                                                : $data_ternak;
                                        @endphp

                                        <div class="mb-5 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                            {{-- Info Kriteria Pesanan --}}
                                            @if($idJenisTrx)
                                                <div class="mb-3 rounded-md bg-brand-50 dark:bg-brand-900/10 border border-brand-100 dark:border-brand-800/30 p-3">
                                                    <p class="text-xs font-semibold text-brand-700 dark:text-brand-400 mb-1.5">Kriteria Pesanan User</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        <span class="capitalize inline-flex items-center rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-2.5 py-1 text-xs font-medium text-gray-700 dark:text-gray-300">
                                                            {{ $transaksi->jenisTernak->jenis_ternak ?? '-' }}
                                                        </span>
                                                        <span class="inline-flex items-center rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-2.5 py-1 text-xs font-medium text-gray-700 dark:text-gray-300">
                                                            {{ ucfirst($jkPesanan) }}
                                                        </span>
                                                        <span class="inline-flex items-center rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-2.5 py-1 text-xs font-medium text-green-700 dark:text-green-400">
                                                             Rp {{ number_format($hargaPerEkor, 0, ',', '.') }}/ekor
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="flex items-center justify-between mb-3">
                                                <h5 class="text-sm font-semibold text-gray-800 dark:text-white">Ternak Di-assign</h5>
                                                <span class="text-xs font-bold px-2 py-1 rounded-full {{ $transaksi->detailTransaksi->count() >= $transaksi->total_jumlah ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                    {{ $transaksi->detailTransaksi->count() }} / {{ $transaksi->total_jumlah }}
                                                </span>
                                            </div>

                                            @if($transaksi->detailTransaksi->count() > 0)
                                                <div class="space-y-2 mb-3">
                                                    @foreach($transaksi->detailTransaksi as $detail)
                                                        <div class="flex items-center justify-between rounded-md bg-gray-50 dark:bg-gray-800 px-3 py-2">
                                                            <div>
                                                                <span class="text-sm font-medium text-brand-500">#{{ $detail->id_ternak }}</span>
                                                                <span class="text-xs text-gray-500 ml-1">{{ $detail->ternak->jenis_ternak->jenis_ternak ?? '-' }} &bull; {{ $detail->ternak->berat ?? '-' }}kg &bull; {{ ucfirst($detail->ternak->jenis_kelamin ?? '-') }} &bull; Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</span>
                                                            </div>
                                                            <form action="{{ route('transaksi.detail.remove', $detail->id_detail) }}" method="POST" onsubmit="return confirm('Hapus ternak ini?')">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                                                            </form>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-xs text-gray-400 italic mb-3">Belum ada ternak di-assign.</p>
                                            @endif

                                            @if($transaksi->detailTransaksi->count() < $transaksi->total_jumlah)
                                                @if($ternakCocok->count() > 0)
                                                    <form action="{{ route('transaksi.assign', $transaksi->id_transaksi) }}" method="POST" class="space-y-3">
                                                        @csrf
                                                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Pilih Ternak yang Ingin Di-assign (Maks <span x-text="maxSelect"></span>):</label>
                                                        <div class="space-y-1.5 max-h-40 overflow-y-auto pr-1 border border-gray-200 dark:border-gray-700 rounded-lg p-2 bg-gray-50 dark:bg-gray-800/40">
                                                            @foreach($ternakCocok as $t)
                                                                <label class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer text-xs transition duration-150 border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                                                                    <input type="checkbox" name="id_ternak[]" value="{{ $t->id_ternak }}"
                                                                           :disabled="selectedTernak.length >= maxSelect && !selectedTernak.includes('{{ $t->id_ternak }}')"
                                                                           @change="toggleTernak('{{ $t->id_ternak }}')"
                                                                           class="rounded text-brand-500 focus:ring-brand-500 w-4 h-4 dark:bg-gray-900 border-gray-300 dark:border-gray-700">
                                                                    <div class="flex-1">
                                                                        <span class="font-bold text-gray-750 dark:text-white">#{{ $t->id_ternak }}</span>
                                                                        <span class="text-gray-500 dark:text-gray-400">
                                                                            &bull; {{ $t->berat }}kg &bull; Usia {{ $t->usia }} bln
                                                                            @if($t->kamar)
                                                                                &bull; Kamar {{ $t->kamar->nomor_kamar }} (Kandang {{ $t->kamar->kandang->nomor_kandang ?? '-' }})
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        <div class="flex items-center justify-between mt-2 text-xs">
                                                            <span class="text-gray-500" x-text="`Terpilih: ${selectedTernak.length} dari ${maxSelect} ekor`"></span>
                                                            <button type="submit" :disabled="selectedTernak.length === 0"
                                                                    class="rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                                                Assign Terpilih
                                                            </button>
                                                        </div>
                                                    </form>
                                                @else
                                                    <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-2.5 text-xs text-red-600 dark:text-red-400 border border-red-100 dark:border-red-800/30">
                                                        ⚠️ Tidak ada ternak yang sesuai kriteria pesanan ini. Pastikan stok tersedia.
                                                    </div>
                                                @endif
                                            @endif
                                        </div>

                                        {{-- BUKTI TRANSFER BUTTON (in edit modal) --}}
                                        @if($transaksi->bukti_pembayaran)
                                            <div class="mb-5 rounded-lg border border-blue-200 dark:border-blue-800/30 p-4 bg-blue-50 dark:bg-blue-900/10">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        <span class="text-sm font-semibold text-blue-700 dark:text-blue-400">Bukti Transfer</span>
                                                        <span class="text-xs font-bold text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/30 px-2 py-0.5 rounded-full">Total: Rp {{ number_format($transaksi->total_harga + $transaksi->ongkir, 0, ',', '.') }}</span>
                                                    </div>
                                                    <a href="{{ $transaksi->bukti_pembayaran }}" target="_blank" rel="noopener"
                                                        class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 text-xs font-medium transition-colors cursor-pointer">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                        Lihat Foto Bukti
                                                    </a>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- FORM UPDATE STATUS --}}
                                        <form method="POST" action="{{ route('transaksi.update', $transaksi->id_transaksi) }}" class="flex flex-col gap-4">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="id_transaksi_edit" value="{{ $transaksi->id_transaksi }}">

                                            {{-- Info Pengiriman --}}
                                            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-3 bg-gray-50 dark:bg-gray-800/50">
                                                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Metode Pengiriman</p>
                                                @if($transaksi->metode_pengiriman === 'ambil_sendiri')
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-100 dark:bg-green-500/10 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:text-green-400">🏠 Ambil Sendiri</span>
                                                    <p class="text-xs text-gray-500 mt-1">Ongkir: Gratis</p>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-brand-100 dark:bg-brand-500/10 px-2.5 py-0.5 text-xs font-medium text-brand-700 dark:text-brand-400">🚚 Dikirim</span>
                                                    <p class="text-xs text-gray-500 mt-1">Ongkir: Rp {{ number_format($transaksi->ongkir, 0, ',', '.') }}</p>
                                                @endif
                                            </div>

                                            <div>
                                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Update Status</label>
                                                <select name="status" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                    <option value="pending" {{ $st == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="diproses" {{ $st == 'diproses' ? 'selected' : '' }}>Diproses (Dikemas)</option>
                                                    
                                                    @if ($transaksi->detailTransaksi->count() >= $transaksi->total_jumlah)
                                                        <option value="dikirim" {{ $st == 'dikirim' ? 'selected' : '' }}>Dalam Pengiriman</option>
                                                        <option value="selesai" {{ $st == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                    @endif
                                                    
                                                    <option value="batal" {{ $st == 'batal' ? 'selected' : '' }}>Batal</option>
                                                </select>
                                                @if ($transaksi->detailTransaksi->count() < $transaksi->total_jumlah)
                                                    <p class="mt-1 text-xs text-amber-600 font-medium">⚠️ Pilihan 'Dalam Pengiriman' & 'Selesai' disembunyikan karena ternak di-assign belum lengkap ({{ $transaksi->detailTransaksi->count() }}/{{ $transaksi->total_jumlah }}).</p>
                                                @else
                                                    <p class="mt-1 text-xs text-gray-500">Peringatan: 'Selesai' memindahkan ke Rekap, 'Batal' mengembalikan stok ternak.</p>
                                                @endif
                                            </div>

                                            @if($transaksi->metode_pengiriman === 'dikirim')
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kurir Pengiriman</label>
                                                    <input type="text" name="kurir" value="{{ $transaksi->kurir }}" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                </div>
                                                <div>
                                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">No HP Kurir</label>
                                                    <input type="text" name="no_kurir" value="{{ $transaksi->no_kurir }}" class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                </div>
                                            </div>
                                            @endif

                                            <div class="flex items-center gap-3 mt-4 justify-end">
                                                <button @click="modalEdit = false" type="button" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Batal</button>
                                                <button type="submit" class="rounded-lg bg-yellow-500 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-600">Update Data</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </template>


                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 px-4 text-center text-gray-500 dark:text-gray-400">Tidak ada
                                transaksi yang memerlukan tindakan saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($data_transaksi->hasPages())
            <div class="border-t border-gray-200 dark:border-gray-800 p-4">
                {{ $data_transaksi->withQueryString()->links() }}
            </div>
        @endif
    </div>


@endsection
