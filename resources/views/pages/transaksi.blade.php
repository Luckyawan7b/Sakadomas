@extends('layouts.app')

@section('content')
    {{-- BUNGKUSAN UTAMA UNTUK MODAL TAMBAH --}}
    <div x-data="{ modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }} }">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-title-md2 font-bold text-black dark:text-white">
                    Operasional Transaksi
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau pesanan yang berstatus Pending hingga
                    Dikirim.</p>
            </div>

            {{-- Tombol Tambah Data --}}
            <button @click="modalTambah = true" type="button"
                class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600">
                <svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4.5v15m7.5-7.5h-15" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Tambah Transaksi
            </button>
        </div>

        {{-- MODAL TAMBAH TRANSAKSI --}}
        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;"
                class="fixed inset-0 z- flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="modalTambah = false">
                <div class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-8"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-6">
                        <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Transaksi Baru</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pembeli otomatis di-set ke akun Admin.</p>
                    </div>

                    @if ($errors->any() && !old('_method'))
                        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('transaksi.store') }}" enctype="multipart/form-data"
                        class="flex flex-col gap-5"
                        x-data='{
                              selectedKandang: "",
                              selectedKamar: "",
                              selectedTernak: "{{ old('id_ternak', '') }}",
                              semuaKamar: @json($data_kamar ?? []),
                              semuaTernak: @json($data_ternak ?? []),

                              get kamarTersedia() {
                                  if (this.selectedKandang === "kosong") return [{id_kamar: "kosong", nomor_kamar: "Kosong (Tanpa Kamar)"}];
                                  return this.semuaKamar.filter(k => k.id_kandang == this.selectedKandang);
                              },

                              get ternakTersedia() {
                                  let filtered = this.semuaTernak;
                                  if (this.selectedKandang === "kosong") {
                                      filtered = filtered.filter(t => t.id_kamar == null);
                                  } else if (this.selectedKamar && this.selectedKamar !== "kosong") {
                                      filtered = filtered.filter(t => t.id_kamar == this.selectedKamar);
                                  } else {
                                      return [];
                                  }
                                  return filtered;
                              },

                              autoFillHarga() {
                                  let t = this.semuaTernak.find(x => x.id_ternak == this.selectedTernak);
                                  if(t) { $refs.hargaInput.value = t.harga; }
                              }
                          }'>
                        @csrf

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih
                                    Kandang</label>
                                <select x-model="selectedKandang"
                                    @change="selectedKamar = ''; selectedTernak = ''; $refs.hargaInput.value = ''" required
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="" disabled>Pilih Kandang...</option>
                                    @foreach ($data_kandang ?? [] as $kd)
                                        <option value="{{ $kd->id_kandang }}">Kandang {{ $kd->nomor_kandang }}</option>
                                    @endforeach
                                    <option value="kosong" class="text-red-500 font-semibold">Kosong (Tanpa Kandang)
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih
                                    Kamar</label>
                                <select x-model="selectedKamar" @change="selectedTernak = ''; $refs.hargaInput.value = ''"
                                    required
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white">
                                    <option value="" disabled x-show="!selectedKandang">Pilih Kandang Dulu</option>
                                    <option value="" disabled x-show="selectedKandang">Pilih Kamar...</option>
                                    <template x-for="kamar in kamarTersedia" :key="kamar.id_kamar">
                                        <option :value="kamar.id_kamar"
                                            x-text="kamar.nomor_kamar === 'Kosong (Tanpa Kamar)' ? kamar.nomor_kamar : 'Kamar ' + kamar.nomor_kamar">
                                        </option>
                                    </template>
                                </select>
                            </div>

                            {{-- <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Ternak</label>
                                <select name="id_ternak" x-model="selectedTernak" @change="autoFillHarga" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-brand-500 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-brand-500 dark:text-white ring-1 ring-brand-500">
                                    <option value="" disabled x-show="!selectedKamar">Pilih Kamar Dulu</option>
                                    <option value="" disabled x-show="selectedKamar">Pilih Ternak...</option>
                                    <template x-for="t in ternakTersedia" :key="t.id_ternak">
                                        <option :value="t.id_ternak" x-text="'#ID-' + t.id_ternak + ' (' + (t.jenis_ternak ? t.jenis_ternak.jenis_ternak : 'Domba') + ')'"></option>
                                    </template>
                                </select>
                            </div> --}}
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-4">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih
                                        Ternak</label>
                                    {{-- <select name="id_ternak" x-model="selectedTernak" @change="autoFillHarga" required class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white ring-1 ring-brand-500 border-brand-500">
                                        <option value="" disabled>-- Pilih Ternak --</option>
                                        <template x-for="t in semuaTernak" :key="t.id_ternak">
                                            <option :value="t.id_ternak" x-text="'#ID-' + t.id_ternak + ' (' + t.jenis_ternak.jenis_ternak + ')'"></option>
                                        </template>
                                    </select> --}}
                                    <select name="id_ternak" x-model="selectedTernak" @change="autoFillHarga" required
                                        class="dark:bg-gray-900 h-11 w-full rounded-lg border border-brand-500 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-brand-500 dark:text-white ring-1 ring-brand-500">
                                        <option value="" disabled x-show="!selectedKamar">Pilih Kamar Dulu</option>
                                        <option value="" disabled x-show="selectedKamar">Pilih Ternak...</option>
                                        <template x-for="t in ternakTersedia" :key="t.id_ternak">
                                            <option :value="t.id_ternak"
                                                x-text="'#ID-' + t.id_ternak + ' (' + (t.jenis_ternak ? t.jenis_ternak.jenis_ternak : 'Domba') + ')'">
                                            </option>
                                        </template>
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah
                                            (Ekor)</label>
                                        <input type="number" name="total_jumlah" value="1" required min="1"
                                            class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <label
                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Total
                                            Harga (Rp)</label>
                                        <input type="number" name="total_harga" x-ref="hargaInput"
                                            value="{{ old('total_harga') }}" required min="0"
                                            class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Metode
                                        Pembayaran</label>
                                    <select name="metode_pembayaran" required
                                        class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="cod">Cash on Delivery (COD)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status
                                        Transaksi</label>
                                    <select name="status" required
                                        class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                        <option value="pending">Pending</option>
                                        <option value="diproses">Diproses</option>
                                        <option value="dikirim">Dikirim</option>
                                        <option value="selesai">Selesai</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Kurir
                                         <span class="text-xs text-gray-500">(Opsional)</span></label>
                                    <input type="text" name="kurir" placeholder="Nama kurir"
                                        class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">No HP Kurir <span class="text-xs text-gray-500">(Opsional)</span></label>
                                    <input type="text" name="no_kurir" placeholder="Contoh: 08123456789"
                                        class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                </div>
                            </div>
                        </div>


                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Bukti
                                Pembayaran <span class="text-xs text-gray-500">(Opsional, Maks 2MB)</span></label>
                            <input type="file" name="bukti_pembayaran" accept="image/*"
                                class="dark:bg-gray-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white file:mr-4 file:rounded-md file:border-0 file:bg-brand-500 file:py-2 file:px-4 file:text-sm file:font-semibold file:text-white hover:file:bg-brand-600">
                        </div>

                        <div class="flex items-center gap-3 mt-4 justify-end">
                            <button @click="modalTambah = false" type="button"
                                class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 sm:w-auto">Batal</button>
                            <button type="submit"
                                class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">Buat
                                Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
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
                        <tr x-data="{ modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_transaksi_edit') == $transaksi->id_transaksi ? 'true' : 'false' }} }"
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
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100">
                                        <div class="mb-6">
                                            <h4 class="mb-1 text-xl font-semibold text-gray-800 dark:text-white/90">Update Transaksi #TRX-{{ $transaksi->id_transaksi }}</h4>
                                            <p class="text-sm text-gray-500">Pesanan: {{ $transaksi->total_jumlah }} ekor &bull; Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
                                        </div>

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
                                                            {{-- 🐑  --}}
                                                            {{ $transaksi->jenisTernak->jenis_ternak ?? '-' }}
                                                        </span>
                                                        <span class="inline-flex items-center rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-2.5 py-1 text-xs font-medium text-gray-700 dark:text-gray-300">
                                                            {{-- {{ $jkPesanan == 'jantan' ? '♂' : '♀' }}  --}}
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
                                                    <form action="{{ route('transaksi.assign', $transaksi->id_transaksi) }}" method="POST" class="flex gap-2">
                                                        @csrf
                                                        <select name="id_ternak" required class="dark:bg-gray-900 flex-1 h-9 rounded-lg border border-gray-300 bg-transparent px-3 text-sm dark:border-gray-700 dark:text-white">
                                                            <option value="" disabled selected>Pilih Ternak ({{ $ternakCocok->count() }} tersedia)...</option>
                                                            @foreach($ternakCocok as $t)
                                                                <option value="{{ $t->id_ternak }}">#{{ $t->id_ternak }} &mdash; {{ $t->berat }}kg, Usia {{ $t->usia }} bln (Kamar {{ $t->kamar->nomor_kamar ?? '-' }})</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600 whitespace-nowrap">+ Assign</button>
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
                                                    <option value="dikirim" {{ $st == 'dikirim' ? 'selected' : '' }}>Dalam Pengiriman</option>
                                                    <option value="selesai" {{ $st == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                    <option value="batal" {{ $st == 'batal' ? 'selected' : '' }}>Batal</option>
                                                </select>
                                                <p class="mt-1 text-xs text-gray-500">Peringatan: 'Selesai' memindahkan ke Rekap, 'Batal' mengembalikan stok ternak.</p>
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
                                                <a href="{{ route('transaksi.invoice', $transaksi->id_transaksi) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2 text-sm font-medium text-white transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                    Cetak Invoice
                                                </a>
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
