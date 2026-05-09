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
            ongkirInfo: @json($ongkirInfo),
            selectedKategori: "",
            selectedKelas: "",
            selectedKey: "",
            jumlah: 1,
            metode: "transfer",
            metodePengiriman: "dikirim",
            isSurvei: false,
            waktuSurvei: "",
            tanggalSurvei: "",
            fileName: "",
            bookedTimes: [],
            loadingJadwal: false,

            init() {
                // Auto-fill dari URL parameters (dari halaman Katalog / Detail Produk)
                const params = new URLSearchParams(window.location.search);
                const jenisParam = params.get("jenis");
                const kelaminParam = params.get("kelamin");
                const hargaParam = params.get("harga");

                if (jenisParam && kelaminParam && hargaParam) {
                    // Cari item yang cocok berdasarkan parameter
                    const match = this.rawData.find(item =>
                        String(item.id_jenis) === jenisParam &&
                        item.jenis_kelamin === kelaminParam &&
                        String(item.harga) === hargaParam
                    );
                    if (match) {
                        this.selectedKategori = match.nama_produk;
                        this.$nextTick(() => {
                            this.selectedKelas = match.kelas_berat;
                            this.$nextTick(() => {
                                this.selectedKey = match.nama_produk + "_" + match.kelas_berat + "_" + match.jenis_kelamin + "_" + match.harga;
                            });
                        });
                    }
                }
            },

            fetchBookedTimes() {
                if (!this.tanggalSurvei) return;
                this.loadingJadwal = true;
                fetch("/api/jadwal/cek?tanggal=" + this.tanggalSurvei)
                    .then(res => res.json())
                    .then(data => {
                        this.bookedTimes = data;
                        if(this.bookedTimes.includes(this.waktuSurvei)) {
                            this.waktuSurvei = "";
                        }
                    })
                    .finally(() => {
                        this.loadingJadwal = false;
                    });
            },

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
            get ongkir() {
                if (this.metodePengiriman === "ambil_sendiri") return 0;
                return this.ongkirInfo ? this.ongkirInfo.ongkir : 0;
            },
            get grandTotal() {
                return this.totalHarga + this.ongkir;
            },
            get dalamJangkauan() {
                return this.ongkirInfo ? this.ongkirInfo.dalam_jangkauan : false;
            },
            get maxSurveiDate() {
                let d = new Date();
                d.setDate(d.getDate() + 7);
                return d.toISOString().split("T")[0];
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
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white capitalize">
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
                                        maxDate: new Date().fp_incr(7),
                                        onChange: function(selectedDates, dateStr) {
                                            tanggalSurvei = dateStr;
                                            fetchBookedTimes();
                                        }
                                    }) : null"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                    placeholder="Pilih Tanggal (maks 7 hari)">
                            </div>

                            {{-- Waktu Survei --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Waktu
                                    Survei <span class="text-red-500">*</span></label>
                                <input type="hidden" name="waktu_survei" x-model="waktuSurvei" :required="isSurvei">
                                <div class="grid grid-cols-2 gap-2 sm:grid-cols-4" :class="loadingJadwal ? 'opacity-50 pointer-events-none' : ''">
                                    <template x-for="time in ['09:00', '11:00', '13:00', '15:00']" :key="time">
                                        <button type="button"
                                            @click="if(!bookedTimes.includes(time)) waktuSurvei = time"
                                            :disabled="bookedTimes.includes(time) || loadingJadwal"
                                            :class="{
                                                'bg-brand-500 text-white border-brand-500 shadow-md transform scale-105': waktuSurvei === time,
                                                'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed dark:bg-gray-800 dark:text-gray-600 dark:border-gray-700': bookedTimes.includes(time),
                                                'bg-white text-gray-700 border-gray-300 hover:border-brand-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-white/5': waktuSurvei !== time && !bookedTimes.includes(time)
                                            }"
                                            class="flex items-center justify-center rounded-lg border px-3 py-2.5 text-sm font-semibold transition-all duration-200"
                                            x-text="time">
                                        </button>
                                    </template>
                                </div>
                                <p x-show="loadingJadwal" class="text-[10px] text-brand-600 mt-1 animate-pulse">Memeriksa ketersediaan...</p>
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
                            <i class="fas fa-info-circle mr-1"></i> Jika Anda memilih survei, <b>metode pembayaran</b> akan ditentukan <b>setelah survei selesai</b>.
                            Anda memiliki waktu 24 jam setelah survei selesai untuk melakukan pembayaran (transfer).
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2.5 Card Metode Pengiriman --}}
            <div x-show="!isSurvei" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="rounded-sm border border-gray-200 bg-white p-6 shadow-default dark:border-gray-800 dark:bg-gray-900"
                style="display: none;" x-cloak>
                <h3 class="mb-5 border-b border-gray-200 pb-2 font-medium text-black dark:border-gray-700 dark:text-white">
                    Metode Pengiriman
                </h3>

                <input type="hidden" name="metode_pengiriman" :value="isSurvei ? 'ambil_sendiri' : metodePengiriman">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Card Dikirim --}}
                    <div @click="dalamJangkauan ? metodePengiriman = 'dikirim' : null"
                        :class="{
                            'border-brand-500 bg-brand-50/50 dark:border-brand-500 dark:bg-brand-500/10 ring-1 ring-brand-500': metodePengiriman === 'dikirim',
                            'opacity-50 cursor-not-allowed': !dalamJangkauan,
                            'cursor-pointer hover:border-brand-400 hover:bg-gray-50 dark:hover:bg-gray-800': dalamJangkauan
                        }"
                        class="relative rounded-xl border border-gray-200 p-5 transition-all duration-200 dark:border-gray-700">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-brand-100 dark:bg-brand-500/20">
                                <svg class="w-6 h-6 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 dark:text-white">Kirim ke Alamat</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Biaya menyesuaikan jarak</p>
                                <template x-if="dalamJangkauan && ongkirInfo">
                                    <p class="text-xs font-bold text-brand-600 dark:text-brand-400 mt-2" x-text="formatRupiah(ongkirInfo.ongkir) + ' (' + ongkirInfo.jarak_km + ' km)'"></p>
                                </template>
                            </div>
                        </div>
                        {{-- Checkmark --}}
                        <div x-show="metodePengiriman === 'dikirim'" class="absolute top-3 right-3">
                            <div class="flex h-5 w-5 items-center justify-center rounded-full bg-brand-500 text-white">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                        {{-- Disabled overlay --}}
                        <template x-if="!dalamJangkauan">
                            <div class="mt-2 rounded-md bg-red-50 dark:bg-red-900/20 px-2 py-1.5">
                                <p class="text-[11px] text-red-600 dark:text-red-400 font-medium">⚠️ Alamat Anda di luar jangkauan pengiriman</p>
                            </div>
                        </template>
                    </div>

                    {{-- Card Ambil Sendiri --}}
                    <div @click="metodePengiriman = 'ambil_sendiri'"
                        :class="{
                            'border-green-500 bg-green-50/50 dark:border-green-500 dark:bg-green-500/10 ring-1 ring-green-500': metodePengiriman === 'ambil_sendiri',
                            'cursor-pointer hover:border-green-400 hover:bg-gray-50 dark:hover:bg-gray-800': true
                        }"
                        class="relative rounded-xl border border-gray-200 p-5 transition-all duration-200 dark:border-gray-700">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-green-100 dark:bg-green-500/20">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 dark:text-white">Ambil Langsung</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ke peternakan Sakadomas</p>
                                <p class="text-xs font-bold text-green-600 dark:text-green-400 mt-2">Gratis Ongkir</p>
                            </div>
                        </div>
                        {{-- Checkmark --}}
                        <div x-show="metodePengiriman === 'ambil_sendiri'" class="absolute top-3 right-3">
                            <div class="flex h-5 w-5 items-center justify-center rounded-full bg-green-500 text-white">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Card Pembayaran — HIDDEN jika survei --}}
            <div x-show="!isSurvei" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900 p-6"
                style="display: none;" x-cloak>
                <h3 class="font-medium text-black dark:text-white mb-5 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Metode Pembayaran
                </h3>
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

                    {{-- Rekening Info Card --}}
                    <div x-show="metode === 'transfer'" x-transition
                        class="rounded-xl border border-blue-100 bg-gradient-to-br from-blue-50 to-indigo-50 p-5 dark:border-blue-800/30 dark:from-blue-900/20 dark:to-indigo-900/20">
                        <p class="text-xs font-semibold text-blue-800 dark:text-blue-300 mb-3">Informasi Rekening Pembayaran</p>
                        <div class="space-y-3">
                            {{-- BRI --}}
                            <div class="flex items-center gap-3 rounded-lg bg-white dark:bg-gray-800 p-3 border border-blue-100 dark:border-gray-700">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 text-white font-bold text-xs flex-shrink-0">BRI</div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-800 dark:text-white tracking-wider">0123 4567 8901 234</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">a.n. Peternakan Sakadomas</p>
                                </div>
                                <button type="button" @click="navigator.clipboard.writeText('01234567890123'); $el.textContent = '✓ Copied'" class="text-xs text-brand-500 hover:text-brand-600 font-medium whitespace-nowrap">Salin</button>
                            </div>
                            {{-- BSI --}}
                            <div class="flex items-center gap-3 rounded-lg bg-white dark:bg-gray-800 p-3 border border-blue-100 dark:border-gray-700">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-600 text-white font-bold text-xs flex-shrink-0">BSI</div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-800 dark:text-white tracking-wider">7654 3210 9876</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">a.n. Peternakan Sakadomas</p>
                                </div>
                                <button type="button" @click="navigator.clipboard.writeText('765432109876'); $el.textContent = '✓ Copied'" class="text-xs text-brand-500 hover:text-brand-600 font-medium whitespace-nowrap">Salin</button>
                            </div>
                        </div>

                        {{-- Upload Bukti --}}
                        <div class="mt-4 pt-4 border-t border-blue-200 dark:border-blue-800/30">
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Upload Bukti Transfer <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <label for="uploadBukti" class="cursor-pointer inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition-all hover:bg-brand-600 hover:shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    Upload
                                </label>
                                <input type="file" id="uploadBukti" x-ref="buktiTransfer" name="bukti_pembayaran" accept="image/*"
                                    :required="!isSurvei && metode === 'transfer'"
                                    class="hidden"
                                    @change="fileName = $refs.buktiTransfer.files.length > 0 ? $refs.buktiTransfer.files[0].name : ''">
                                <span class="text-sm text-gray-600 dark:text-gray-400 italic truncate max-w-[200px] sm:max-w-xs"
                                    x-text="fileName ? fileName : 'Belum ada file dipilih'">
                                </span>
                            </div>
                            <p class="mt-2.5 text-xs text-red-500 font-medium italic">
                                * Perhatian: Pesanan dengan metode Transfer yang telah dikirimkan bukti pembayarannya tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info jika survei aktif --}}
            <div x-show="isSurvei" x-transition
                class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800/30 dark:bg-blue-900/20"
                style="display: none;">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <div class="text-sm text-blue-800 dark:text-blue-300">
                        <p class="font-semibold mb-1">Pembayaran Ditunda</p>
                        <p>Karena Anda memilih opsi survei, metode pembayaran dan upload bukti transfer akan tersedia <b>setelah survei selesai</b>. Anda memiliki <b>24 jam</b> setelah survei selesai untuk menyelesaikan pembayaran (transfer).</p>
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
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400"
                            x-text="jumlah + ' Ekor x ' + formatRupiah(selectedItem?.harga)"></span>
                        <span class="text-sm font-medium dark:text-white" x-text="formatRupiah(totalHarga)"></span>
                    </div>

                    {{-- Ongkir line --}}
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Ongkos Kirim</span>
                        <span class="text-sm font-medium" :class="ongkir === 0 ? 'text-green-600 dark:text-green-400' : 'dark:text-white'"
                            x-text="ongkir === 0 ? 'Gratis' : formatRupiah(ongkir)"></span>
                    </div>

                    {{-- Metode Pengiriman badge --}}
                    <div class="mt-2 mb-4 p-3 rounded-lg text-xs"
                        :class="metodePengiriman === 'dikirim'
                            ? 'bg-brand-50 text-brand-700 dark:bg-brand-900/10 dark:text-brand-400'
                            : 'bg-green-50 text-green-700 dark:bg-green-900/10 dark:text-green-400'">
                        <div class="flex items-center gap-2">
                            <template x-if="metodePengiriman === 'dikirim'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                            </template>
                            <template x-if="metodePengiriman === 'ambil_sendiri'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </template>
                            <b x-text="metodePengiriman === 'dikirim' ? 'Dikirim ke Alamat' : 'Ambil di Peternakan'"></b>
                        </div>
                    </div>

                    <div x-show="isSurvei"
                        class="mt-2 mb-4 p-3 bg-amber-50 rounded-lg text-xs text-amber-800 dark:bg-amber-900/20 dark:text-amber-400">
                        <b>+ Opsi Survei Kandang</b><br>
                        Jadwal: <span x-text="tanggalSurvei ? tanggalSurvei : '...' "></span> Pukul <span
                            x-text="waktuSurvei ? waktuSurvei : '...' "></span>
                        <p class="mt-1 text-[10px] opacity-75">Pembayaran akan dilakukan setelah survei selesai</p>
                    </div>

                    <div x-show="!isSurvei && metode"
                        class="mt-2 mb-4 p-3 bg-gray-50 rounded-lg text-xs text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                        <b>Metode Bayar:</b> <span x-text="metode === 'transfer' ? 'Transfer Bank' : 'Cash (COD)'"></span>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="font-bold dark:text-white">Total Tagihan</span>
                            <span class="text-xl font-bold text-brand-600" x-text="formatRupiah(grandTotal)"></span>
                        </div>
                    </div>
                    <button type="submit"
                        :disabled="metodePengiriman === 'dikirim' && !dalamJangkauan"
                        :class="metodePengiriman === 'dikirim' && !dalamJangkauan ? 'opacity-50 cursor-not-allowed' : 'hover:bg-brand-600'"
                        class="w-full mt-6 bg-brand-500 text-white py-3 rounded-lg font-bold transition">
                        <span x-text="isSurvei ? 'Ajukan Pesanan + Survei' : 'Konfirmasi Pesanan'"></span>
                    </button>
                </div>

                <div x-show="!selectedItem" class="text-center py-4 text-gray-500 text-sm italic">
                    Pilih produk untuk melihat ringkasan.
                </div>
            </div>
        </div>
    </form>
@endsection
