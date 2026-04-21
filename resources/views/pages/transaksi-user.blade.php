@extends('layouts.app')

@section('content')
    {{-- Data Dummy / Fallback. Nanti harus dikirim dari Controller! --}}
    @php
        $jenis_ternak = $jenis_ternak ?? [
            ['id' => 1, 'nama' => 'Domba Crosstexel Premium', 'harga' => 3500000, 'stok' => 12],
            ['id' => 2, 'nama' => 'Domba Merino Betina', 'harga' => 4200000, 'stok' => 5],
            ['id' => 3, 'nama' => 'Kambing Etawa Super', 'harga' => 2800000, 'stok' => 8],
        ];
    @endphp

    <div class="mb-6">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Buat Pesanan Baru
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pilih jenis hewan ternak yang Anda inginkan. Kami hanya
            menjual hewan yang 100% sehat dan siap jual.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Pembelian dengan Alpine.js --}}
  <form method="POST" action="{{ route('transaksi.create.store') }}" enctype="multipart/form-data"
    x-data='{
        rawData: @json($jenis_ternak),
        selectedKategori: "",
        selectedKelas: "",
        selectedKey: "",
        jumlah: 1,
        metode: "transfer",

        get kategoriOptions() {
            return [...new Set(this.rawData.map(item => item.nama_produk))];
        },
        get kelasOptions() {
            if(!this.selectedKategori) return [];
            let filtered = this.rawData.filter(item => item.nama_produk === this.selectedKategori);
            return [...new Set(filtered.map(item => item.kelas_berat))];
        },
        get genderOptions() {
            if(!this.selectedKelas) return [];
            return this.rawData.filter(item =>
                item.nama_produk === this.selectedKategori &&
                item.kelas_berat === this.selectedKelas
            );
        },
        get selectedItem() {
            if(!this.selectedKey) return null;
            return this.rawData.find(item =>
                (item.nama_produk + "_" + item.kelas_berat + "_" + item.jenis_kelamin + "_" + item.harga) === this.selectedKey
            ) || null;
        },
        get totalHarga() {
            return this.selectedItem ? this.selectedItem.harga * this.jumlah : 0;
        },
        formatRupiah(angka) {
            return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", minimumFractionDigits: 0 }).format(angka);
        }
    }'
    class="grid grid-cols-1 xl:grid-cols-3 gap-8">

    @csrf
    <div class="xl:col-span-2 flex flex-col gap-6">
        <div class="rounded-sm border border-gray-200 bg-white p-6 shadow-default dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-5 border-b border-gray-200 pb-2 font-medium text-black dark:border-gray-700 dark:text-white">
                Detail Pesanan Ternak
            </h3>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                {{-- 1. Kategori Produk --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Kategori Produk</label>
                    <select x-model="selectedKategori" @change="selectedKelas = ''; selectedKey = ''; jumlah = 1" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="" disabled>-- Pilih Kategori (Breed + Usia) --</option>
                        <template x-for="kat in kategoriOptions" :key="kat">
                            <option :value="kat" x-text="kat"></option>
                        </template>
                    </select>
                </div>

                {{-- 2. Kelas Berat --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Kelas Berat</label>
                    <select x-model="selectedKelas" @change="selectedKey = ''; jumlah = 1" required :disabled="!selectedKategori"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm focus:border-brand-500 disabled:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800">
                        <option value="" disabled>-- Pilih Kelas (Standard/Super) --</option>
                        <template x-for="kls in kelasOptions" :key="kls">
                            <option :value="kls" x-text="kls"></option>
                        </template>
                    </select>
                </div>

                {{-- 3. Jenis Kelamin --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Jenis Kelamin</label>
                    <select x-model="selectedKey" required :disabled="!selectedKelas"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm focus:border-brand-500 disabled:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800">
                        <option value="" disabled>-- Pilih Kelamin --</option>
                        <template x-for="opt in genderOptions" :key="opt.nama_produk + '_' + opt.kelas_berat + '_' + opt.jenis_kelamin + '_' + opt.harga">
                            <option :value="opt.nama_produk + '_' + opt.kelas_berat + '_' + opt.jenis_kelamin + '_' + opt.harga"
                                x-text="(opt.jenis_kelamin.toUpperCase()) + ' - ' + formatRupiah(opt.harga) + ' (Stok: ' + opt.stok + ')'">
                            </option>
                        </template>
                    </select>
                </div>

                {{-- Input Jumlah --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Pesanan</label>
                    <input type="number" name="total_jumlah" x-model.number="jumlah" required min="1" :disabled="!selectedKey"
                        @input="if(selectedItem && jumlah > selectedItem.stok) jumlah = selectedItem.stok"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm focus:border-brand-500 disabled:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800">
                </div>

                <input type="hidden" name="id_jenis_ternak" :value="selectedItem?.id_jenis || ''">
                <input type="hidden" name="total_harga" :value="totalHarga">
            </div>
        </div>

            {{-- Card Pembayaran & Bukti (Sama seperti sebelumnya) --}}
            <div
                class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900 p-6">
                <h3 class="font-medium text-black dark:text-white mb-5 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Pembayaran & Pengiriman
                </h3>

                <div class="flex flex-col gap-5">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Metode Pembayaran
                            <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-300 p-4 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
                                :class="metode === 'transfer' ?
                                    'border-brand-500 bg-brand-50/50 dark:border-brand-500 dark:bg-brand-500/10' : ''">
                                <input type="radio" name="metode_pembayaran" value="transfer" x-model="metode"
                                    class="w-4 h-4 text-brand-600 focus:ring-brand-500">
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">Transfer Bank</p>
                                    <p class="text-xs text-gray-500">BCA, Mandiri, BRI</p>
                                </div>
                            </label>

                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-300 p-4 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
                                :class="metode === 'cash' ?
                                    'border-brand-500 bg-brand-50/50 dark:border-brand-500 dark:bg-brand-500/10' : ''">
                                <input type="radio" name="metode_pembayaran" value="cash" x-model="metode"
                                    class="w-4 h-4 text-brand-600 focus:ring-brand-500">
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">Bayar Cash</p>
                                    <p class="text-xs text-gray-500">Bayar saat hewan tiba</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Upload Bukti (Hanya tampil jika Transfer) --}}
                    <div x-show="metode === 'transfer'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="rounded-lg bg-blue-50 p-4 border border-blue-100 dark:bg-blue-900/20 dark:border-blue-800/30">
                        <div class="mb-3 text-sm text-blue-800 dark:text-blue-300">
                            <p class="font-semibold mb-1">Informasi Rekening:</p>
                            <p>BCA: 1234567890 a.n Smart Saka</p>
                            <p>Mandiri: 0987654321 a.n Smart Saka</p>
                        </div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Upload Bukti Transfer
                            <span class="text-red-500">*</span></label>
                        <input type="file" name="bukti_pembayaran" accept="image/*" :required="metode === 'transfer'"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white file:mr-4 file:rounded-md file:border-0 file:bg-brand-500 file:py-2 file:px-4 file:text-sm file:font-semibold file:text-white hover:file:bg-brand-600">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Pengiriman
                            (Opsional)</label>
                        <textarea name="catatan" rows="3" placeholder="Contoh: Tolong dikirim hari jumat pagi..."
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white transition"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: Ringkasan Pesanan --}}
        <div class="xl:col-span-1">
            <div
                class="sticky top-24 rounded-sm border border-gray-200 bg-white p-6 shadow-default dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-5 border-b border-gray-200 pb-2 font-medium text-black dark:border-gray-700 dark:text-white">
                    Ringkasan Pesanan
                </h3>

                <div x-show="selectedItem" style="display: none;">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400" x-text="selectedItem?.nama_produk"></span>
                        <span class="text-sm font-medium" x-text="selectedItem?.jenis_kelamin.toUpperCase()"></span>
                    </div>
                    <div class="flex justify-between mb-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400"
                            x-text="jumlah + ' Ekor x ' + formatRupiah(selectedItem?.harga)"></span>
                        <span class="text-sm font-bold" x-text="formatRupiah(totalHarga)"></span>
                    </div>
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="font-bold">Total Tagihan</span>
                            <span class="text-xl font-bold text-brand-600" x-text="formatRupiah(totalHarga)"></span>
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full mt-6 bg-brand-500 text-white py-3 rounded-lg font-bold hover:bg-brand-600 transition">
                        Konfirmasi Pesanan
                    </button>
                </div>

                <div x-show="!selectedItem" class="text-center py-4 text-gray-500 text-sm italic">
                    Pilih produk untuk melihat ringkasan.
                </div>
            </div>
        </div>
    </form>
@endsection
