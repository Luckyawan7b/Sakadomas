@extends('layouts.app')

@section('content')
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

    {{-- Form Pembelian --}}
    <form method="POST" action="{{ route('transaksi.create.store') }}" enctype="multipart/form-data"
        x-data='{
            rawData: @json($jenis_ternak),
            selectedKategori: "",
            selectedKelas: "",
            selectedKey: "",
            jumlah: 1,
            metode: "transfer",
            isSurvei: false,
            waktuSurvei: "",
            tanggalSurvei: "",
            fileName: "",

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

            {{-- 1. Card Detail Pesanan --}}
            <div
                class="rounded-sm border border-gray-200 bg-white p-6 shadow-default dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-5 border-b border-gray-200 pb-2 font-medium text-black dark:border-gray-700 dark:text-white">
                    Detail Pesanan Ternak
                </h3>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    {{-- Kategori Produk --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Kategori
                            Produk</label>
                        <select x-model="selectedKategori" @change="selectedKelas = ''; selectedKey = ''; jumlah = 1"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="" disabled>-- Pilih Kategori (Breed + Usia) --</option>
                            <template x-for="kat in kategoriOptions" :key="kat">
                                <option :value="kat" x-text="kat"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Kelas Berat --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Kelas
                            Berat</label>
                        <select x-model="selectedKelas" @change="selectedKey = ''; jumlah = 1" required
                            :disabled="!selectedKategori"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm focus:border-brand-500 disabled:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800">
                            <option value="" disabled>-- Pilih Kelas (Standard/Super) --</option>
                            <template x-for="kls in kelasOptions" :key="kls">
                                <option :value="kls" x-text="kls"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Jenis
                            Kelamin</label>
                        <select x-model="selectedKey" required :disabled="!selectedKelas"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm focus:border-brand-500 disabled:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800">
                            <option value="" disabled>-- Pilih Kelamin --</option>
                            <template x-for="opt in genderOptions"
                                :key="opt.nama_produk + '_' + opt.kelas_berat + '_' + opt.jenis_kelamin + '_' + opt.harga">
                                <option
                                    :value="opt.nama_produk + '_' + opt.kelas_berat + '_' + opt.jenis_kelamin + '_' + opt.harga"
                                    x-text="(opt.jenis_kelamin.toUpperCase()) + ' - ' + formatRupiah(opt.harga) + ' (Stok: ' + opt.stok + ')'">
                                </option>
                            </template>
                        </select>
                    </div>

                    {{-- Input Jumlah --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah
                            Pesanan</label>
                        <input type="number" name="total_jumlah" x-model.number="jumlah" required min="1"
                            :disabled="!selectedKey"
                            @input="if(selectedItem && jumlah > selectedItem.stok) jumlah = selectedItem.stok"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm focus:border-brand-500 disabled:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800">
                    </div>

                    <input type="hidden" name="id_jenis_ternak" :value="selectedItem?.id_jenis || ''">
                    <input type="hidden" name="jenis_kelamin_pesanan" :value="selectedItem?.jenis_kelamin || ''">
                    <input type="hidden" name="total_harga" :value="totalHarga">
                </div>
            </div>

            {{-- 2. Card Opsi Survei --}}
            <div
                class="rounded-sm border border-gray-200 bg-white p-6 shadow-default dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-5 border-b border-gray-200 pb-2 font-medium text-black dark:border-gray-700 dark:text-white">
                    Opsi Survei Kandang
                </h3>

                <div class="flex flex-col gap-5">
                    <label class="flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="is_survei" value="1" x-model="isSurvei"
                            class="w-5 h-5 text-brand-600 focus:ring-brand-500 rounded border-gray-300">
                        <span class="font-medium text-gray-800 dark:text-white">Saya ingin melakukan survei langsung ke
                            kandang sebelum hewan dikirim</span>
                    </label>

                    {{-- Form Survei --}}
                    <div x-show="isSurvei" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="flex flex-col gap-4 p-5 rounded-lg border border-brand-100 bg-brand-50/50 dark:border-brand-900 dark:bg-brand-900/10"
                        style="display: none;">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Tanggal Survei --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal
                                    Survei <span class="text-red-500">*</span></label>
                                <input type="text" name="tanggal_survei" :required="isSurvei" x-model="tanggalSurvei"
                                    x-init="typeof flatpickr !== 'undefined' ? flatpickr($el, {
                                        dateFormat: 'Y-m-d',
                                        minDate: 'today',
                                        onChange: function(selectedDates, dateStr) {
                                            tanggalSurvei = dateStr;
                                        }
                                    }) : null"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                    placeholder="Pilih Tanggal">
                            </div>

                            {{-- Waktu Survei --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Waktu
                                    Survei <span class="text-red-500">*</span></label>
                                <input type="hidden" name="waktu_survei" x-model="waktuSurvei" :required="isSurvei">
                                <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                                    <template x-for="time in ['09:00', '11:00', '13:00', '15:00']" :key="time">
                                        <button type="button" @click="waktuSurvei = time"
                                            :class="waktuSurvei === time ?
                                                'bg-brand-500 text-white border-brand-500 shadow-md transform scale-105' :
                                                'bg-white text-gray-700 border-gray-300 hover:border-brand-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-white/5'"
                                            class="flex items-center justify-center rounded-lg border px-3 py-2.5 text-sm font-semibold transition-all duration-200 "
                                            x-text="time">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Keterangan Survei --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Keterangan
                                (Opsional)</label>
                            <textarea name="ket_survei" rows="2" placeholder="Contoh: Ingin melihat langsung kualitas bulu dan berat badan..."
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"></textarea>
                        </div>

                        <div
                            class="rounded-md bg-amber-50 p-3 text-sm text-amber-800 border border-amber-200 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800/30">
                            <i class="fas fa-info-circle mr-1"></i> Transaksi Anda akan diproses dan dikirim <b>setelah</b>
                            survei kandang disetujui dan diselesaikan.
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Card Pembayaran --}}
            <div
                class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900 p-6">
                <h3 class="font-medium text-black dark:text-white mb-5 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Metode Pembayaran
                </h3>
                {{-- (Isi card pembayaran persis seperti sebelumnya) --}}
                <div class="flex flex-col gap-5">
                    <div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-300 p-4 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
                                :class="metode === 'transfer' ?
                                    'border-brand-500 bg-brand-50/50 dark:border-brand-500 dark:bg-brand-500/10' : ''">
                                <input type="radio" name="metode_pembayaran" value="transfer" x-model="metode"
                                    class="w-4 h-4 text-brand-600 focus:ring-brand-500">
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">Transfer Bank</p>
                                </div>
                            </label>

                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-300 p-4 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
                                :class="metode === 'cash' ?
                                    'border-brand-500 bg-brand-50/50 dark:border-brand-500 dark:bg-brand-500/10' : ''">
                                <input type="radio" name="metode_pembayaran" value="cash" x-model="metode"
                                    class="w-4 h-4 text-brand-600 focus:ring-brand-500">
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">Bayar Cash (COD)</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- <div x-show="metode === 'transfer'" x-transition class="rounded-lg bg-blue-50 p-4 border border-blue-100">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Upload Bukti Transfer <span class="text-red-500">*</span></label>
                        <input type="file" name="bukti_pembayaran" accept="image/*" :required="metode === 'transfer'" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm file:mr-4 file:rounded-md file:bg-brand-500 file:text-white">
                    </div> --}}

                    <div x-show="metode === 'transfer'" x-transition class="rounded-lg bg-blue-50 p-4 border border-blue-100 dark:bg-blue-900/20 dark:border-blue-800/30">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Upload Bukti Transfer <span class="text-red-500">*</span>
                        </label>

                        <div class="flex items-center gap-3">
                            {{-- Tombol Kustom (Sebenarnya adalah Label) --}}
                            <label for="uploadBukti" class="cursor-pointer inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition-all hover:bg-brand-600 hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Pilih Gambar
                            </label>

                            {{-- Input Asli (Disembunyikan) --}}
                            <input type="file" id="uploadBukti" x-ref="buktiTransfer" name="bukti_pembayaran" accept="image/*"
                                :required="metode === 'transfer'"
                                class="hidden"
                                @change="fileName = $refs.buktiTransfer.files.length > 0 ? $refs.buktiTransfer.files    .name : ''">

                            {{-- Tampilan Nama File --}}
                            <span class="text-sm text-gray-600 dark:text-gray-400 italic truncate max-w-[200px] sm:max-w-xs"
                                x-text="fileName ? fileName : 'Belum ada file dipilih'">
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan
                            Pengiriman</label>
                        <textarea name="catatan" rows="3"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Ringkasan Pesanan --}}
        <div class="xl:col-span-1">
            <div
                class="sticky top-24 rounded-sm border border-gray-200 bg-white p-6 shadow-default dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-5 border-b border-gray-200 pb-2 font-medium text-black dark:border-gray-700 dark:text-white">
                    Ringkasan Pesanan
                </h3>

                <div x-show="selectedItem" style="display: none;">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400" x-text="selectedItem?.nama_produk"></span>
                        <span class="text-sm font-medium dark:text-white" x-text="selectedItem?.jenis_kelamin.toUpperCase()"></span>
                    </div>
                    <div class="flex justify-between mb-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400"
                            x-text="jumlah + ' Ekor x ' + formatRupiah(selectedItem?.harga)"></span>
                        <span class="text-sm font-bold dark:text-white" x-text="formatRupiah(totalHarga)"></span>
                    </div>

                    <div x-show="isSurvei"
                        class="mt-4 mb-4 p-3 bg-amber-50 rounded-lg text-xs text-amber-800 dark:bg-amber-900/20 dark:text-amber-400">
                        <b>+ Opsi Survei Kandang</b><br>
                        Jadwal: <span x-text="tanggalSurvei ? tanggalSurvei : '...' "></span> Pukul <span
                            x-text="waktuSurvei ? waktuSurvei : '...' "></span>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="font-bold dark:text-white">Total Tagihan</span>
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
