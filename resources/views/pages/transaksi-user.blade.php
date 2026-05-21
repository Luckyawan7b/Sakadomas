@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Buat Transaksi Offline (Admin)
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat transaksi offline tanpa melalui sistem. Transaksi langsung berstatus 'Selesai' dan ternak otomatis di-assign.</p>
        </div>
        <a href="{{ route('transaksi.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
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
    <form method="POST" action="{{ route('transaksi.store.admin') }}" enctype="multipart/form-data"
        x-data='{
            rawData: @json($jenis_ternak),
            ternakList: @json($ternak_list),
            ongkirInfo: @json($ongkirInfo),
            selectedKategori: "",
            selectedKelas: "",
            selectedKey: "",
            selectedTernakIds: [],
            jumlah: 0,
            metode: "transfer",
            metodePengiriman: "dikirim",
            ongkir: 0,
            isSurvei: false,
            waktuSurvei: "",
            tanggalSurvei: "",
            fileName: "",
            bookedTimes: [],
            loadingJadwal: false,

            init() {
                const oldJenis = @json(old('id_jenis_ternak'));
                const oldKelamin = @json(old('jenis_kelamin_pesanan'));
                const oldTernakIds = @json(old('id_ternak') ?? []);

                if (oldJenis && oldKelamin) {
                    const match = this.rawData.find(item =>
                        String(item.id_jenis) === String(oldJenis) &&
                        item.jenis_kelamin === oldKelamin
                    );
                    if (match) {
                        this.selectedKategori = match.nama_produk;
                        this.$nextTick(() => {
                            this.selectedKelas = match.kelas_berat;
                            this.$nextTick(() => {
                                this.selectedKey = match.nama_produk + "_" + match.kelas_berat + "_" + match.jenis_kelamin + "_" + match.harga;
                                this.$nextTick(() => {
                                    if (oldTernakIds.length > 0) {
                                        this.selectedTernakIds = oldTernakIds.map(id => String(id));
                                    }
                                });
                            });
                        });
                    }
                } else {
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
                }

                // Initialize watch for selectedTernakIds to auto-update jumlah
                this.$watch("selectedTernakIds", val => {
                    this.jumlah = val.length;
                });

                // Initialize ongkir
                this.ongkir = this.ongkirInfo ? this.ongkirInfo.ongkir : 0;
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
            get filteredTernak() {
                if(!this.selectedItem) return [];
                return this.ternakList.filter(item =>
                    String(item.id_jenis) === String(this.selectedItem.id_jenis) &&
                    item.nama_produk === this.selectedItem.nama_produk &&
                    item.kelas_berat === this.selectedItem.kelas_berat &&
                    item.jenis_kelamin === this.selectedItem.jenis_kelamin &&
                    String(item.harga) === String(this.selectedItem.harga)
                );
            },
            get totalHarga() {
                if (!this.selectedItem) return 0;
                let selectedSheep = this.filteredTernak.filter(t => this.selectedTernakIds.includes(String(t.id_ternak)));
                return selectedSheep.reduce((sum, t) => sum + Number(t.harga), 0);
            },
            get grandTotal() {
                return this.totalHarga + this.ongkir;
            },
            get dalamJangkauan() {
                return true;
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

        {{-- ============================================================ --}}
        {{-- LEFT COLUMN: Form Cards (2/3 width on xl) --}}
        {{-- ============================================================ --}}
        <div class="xl:col-span-2 flex flex-col gap-6">

            {{-- 1. Card Detail Pesanan --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-5 border-b border-gray-200 pb-3 text-base font-semibold text-black dark:border-gray-700 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Detail Pesanan Ternak
                </h3>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    {{-- Kategori Produk --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Kategori Produk</label>
                        <select x-model="selectedKategori" @change="selectedKelas = ''; selectedKey = ''; selectedTernakIds = []"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white capitalize transition-colors">
                            <option value="" disabled>-- Pilih Kategori (Breed + Usia) --</option>
                            <template x-for="kat in kategoriOptions" :key="kat">
                                <option :value="kat" x-text="kat" class="capitalize"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Kelas Berat --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Kelas Berat</label>
                        <select x-model="selectedKelas" @change="selectedKey = ''; selectedTernakIds = []" required
                            :disabled="!selectedKategori"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 disabled:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800 transition-colors">
                            <option value="" disabled>-- Pilih Kelas (Standard/Super) --</option>
                            <template x-for="kls in kelasOptions" :key="kls">
                                <option :value="kls" x-text="kls"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Jenis Kelamin</label>
                        <select x-model="selectedKey" @change="selectedTernakIds = []" required :disabled="!selectedKelas"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 disabled:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800 capitalize transition-colors">
                            <option value="" disabled>-- Pilih Kelamin --</option>
                            <template x-for="opt in genderOptions"
                                :key="opt.nama_produk + '_' + opt.kelas_berat + '_' + opt.jenis_kelamin + '_' + opt.harga">
                                <option class="capitalize"
                                    :value="opt.nama_produk + '_' + opt.kelas_berat + '_' + opt.jenis_kelamin + '_' + opt.harga"
                                    x-text="(opt.jenis_kelamin.toUpperCase()) + ' - ' + formatRupiah(opt.harga) + ' (Stok: ' + opt.stok + ')'">
                                </option>
                            </template>
                        </select>
                    </div>

                    {{-- Checklist Pilih Ternak --}}
                    <div class="md:col-span-2" x-show="selectedKey" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-400">Pilih Ternak yang Dijual</label>
                            <label class="flex items-center gap-2 text-xs font-semibold text-brand-500 dark:text-brand-400 cursor-pointer select-none">
                                <input type="checkbox"
                                    @change="if($el.checked) { selectedTernakIds = filteredTernak.map(t => String(t.id_ternak)) } else { selectedTernakIds = [] }"
                                    :checked="filteredTernak.length > 0 && selectedTernakIds.length === filteredTernak.length"
                                    class="rounded text-brand-500 focus:ring-brand-500 w-4 h-4 dark:bg-gray-900 border-gray-300 dark:border-gray-700">
                                Pilih Semua (<span x-text="filteredTernak.length"></span>)
                            </label>
                        </div>
                        <div class="space-y-1.5 max-h-56 overflow-y-auto pr-1 border border-gray-200 dark:border-gray-700 rounded-lg p-3 bg-gray-50 dark:bg-gray-800/40">
                            <template x-for="t in filteredTernak" :key="t.id_ternak">
                                <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-white dark:hover:bg-gray-800 cursor-pointer text-xs transition-all duration-150 border border-transparent hover:border-gray-200 dark:hover:border-gray-700 hover:shadow-sm">
                                    <input type="checkbox" name="id_ternak[]" :value="String(t.id_ternak)" x-model="selectedTernakIds"
                                        class="rounded text-brand-500 focus:ring-brand-500 w-4 h-4 dark:bg-gray-900 border-gray-300 dark:border-gray-700">
                                    <div class="flex-1">
                                        <span class="font-bold text-brand-500">#TRK-<span x-text="t.id_ternak"></span></span>
                                        <span class="text-gray-500 dark:text-gray-400">
                                            &bull; <span x-text="t.berat"></span>kg &bull; Usia <span x-text="t.usia"></span> bln
                                            <template x-if="t.kamar_info">
                                                <span>&bull; <span x-text="t.kamar_info"></span></span>
                                            </template>
                                        </span>
                                    </div>
                                </label>
                            </template>
                            <template x-if="filteredTernak.length === 0">
                                <p class="text-xs text-gray-500 italic py-2 text-center">Tidak ada stok ternak siap jual untuk kriteria ini.</p>
                            </template>
                        </div>
                    </div>

                    {{-- Input Jumlah --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Pesanan</label>
                        <input type="number" name="total_jumlah" :value="jumlah" readonly required min="1"
                            class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Jumlah pesanan terisi otomatis berdasarkan jumlah ternak yang dicentang di atas.</p>
                    </div>

                    <input type="hidden" name="id_jenis_ternak" :value="selectedItem?.id_jenis || ''">
                    <input type="hidden" name="jenis_kelamin_pesanan" :value="selectedItem?.jenis_kelamin || ''">
                    <input type="hidden" name="total_harga" :value="totalHarga">
                </div>
            </div>
            {{-- END Card Detail Pesanan --}}

            {{-- Opsi Survei Kandang dihapus untuk Admin --}}

            {{-- 2. Card Metode Pengiriman --}}
            <div x-show="!isSurvei"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                style="display: none;" x-cloak>
                <h3 class="mb-5 border-b border-gray-200 pb-3 text-base font-semibold text-black dark:border-gray-700 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
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
                    <div @click="metodePengiriman = 'ambil_sendiri'; ongkir = 0"
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

                {{-- Input Manual untuk Ongkir, Kurir, No Kurir jika dikirim --}}
                <div x-show="metodePengiriman === 'dikirim'" x-transition class="mt-6 space-y-4 border-t border-gray-200 pt-4 dark:border-gray-700">
                    <h4 class="font-semibold text-gray-800 dark:text-white text-sm">Informasi Kurir & Ongkir</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Ongkos Kirim (Rp)</label>
                            <input type="number" id="input_ongkir" name="ongkir" x-model.number="ongkir" :disabled="metodePengiriman !== 'dikirim'" required min="0"
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white transition-colors">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Kurir</label>
                            <input type="text" id="input_kurir" name="kurir" placeholder="Nama Kurir (opsional)"
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white transition-colors">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">No HP Kurir</label>
                            <input type="text" id="input_no_kurir" name="no_kurir" placeholder="No HP Kurir (opsional)"
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white transition-colors">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="ongkir" value="0" :disabled="metodePengiriman === 'dikirim'">
            </div>
            {{-- END Card Metode Pengiriman --}}

            {{-- 3. Card Pembayaran --}}
            <div x-show="!isSurvei"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 p-6"
                style="display: none;" x-cloak>
                <h3 class="text-base font-semibold text-black dark:text-white mb-5 border-b border-gray-200 dark:border-gray-700 pb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Metode Pembayaran
                </h3>
                <div class="flex flex-col gap-5">
                    <div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-300 p-4 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800 transition-all duration-200"
                                :class="metode === 'transfer' ?
                                    'border-brand-500 bg-brand-50/50 dark:border-brand-500 dark:bg-brand-500/10 ring-1 ring-brand-500' : ''">
                                <input type="radio" name="metode_pembayaran" value="transfer" x-model="metode"
                                    class="w-4 h-4 text-brand-600 focus:ring-brand-500">
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">Transfer Bank</p>
                                </div>
                            </label>

                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-300 p-4 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800 transition-all duration-200"
                                :class="metode === 'cash' ?
                                    'border-brand-500 bg-brand-50/50 dark:border-brand-500 dark:bg-brand-500/10 ring-1 ring-brand-500' : ''">
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
                                Upload Bukti Transfer <span class="text-xs text-gray-500">(Opsional)</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <label for="uploadBukti" class="cursor-pointer inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white transition-all hover:bg-brand-600 hover:shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    Upload
                                </label>
                                <input type="file" id="uploadBukti" x-ref="buktiTransfer" name="bukti_pembayaran" accept="image/*"
                                    class="hidden"
                                    @change="fileName = $refs.buktiTransfer.files.length > 0 ? $refs.buktiTransfer.files[0].name : ''">
                                <span class="text-sm text-gray-600 dark:text-gray-400 italic truncate max-w-[200px] sm:max-w-xs"
                                    x-text="fileName ? fileName : 'Belum ada file dipilih'">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- END Card Pembayaran --}}

        </div>
        {{-- END LEFT COLUMN --}}

        {{-- ============================================================ --}}
        {{-- RIGHT COLUMN: Ringkasan Pesanan (1/3 width, sticky sidebar) --}}
        {{-- ============================================================ --}}
        <div class="xl:col-span-1">
            <div class="sticky top-24 space-y-4">
                {{-- Summary Card --}}
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="mb-5 border-b border-gray-200 pb-3 text-base font-semibold text-black dark:border-gray-700 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/></svg>
                        Ringkasan Pesanan
                    </h3>

                    {{-- Content when product is selected --}}
                    <div x-show="selectedItem" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        style="display: none;" x-cloak>

                        {{-- Product Info --}}
                        <div class="flex justify-between items-start mb-3 pb-3 border-b border-dashed border-gray-200 dark:border-gray-700">
                            <div>
                                <p class="text-sm font-semibold text-gray-800 dark:text-white capitalize" x-text="selectedItem?.nama_produk"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" x-text="'Kelas ' + selectedItem?.kelas_berat"></p>
                            </div>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                :class="selectedItem?.jenis_kelamin === 'jantan'
                                    ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                    : 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400'"
                                x-text="selectedItem?.jenis_kelamin.toUpperCase()">
                            </span>
                        </div>

                        {{-- Line Items --}}
                        <div class="space-y-2.5 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400"
                                    x-text="jumlah + ' Ekor x ' + formatRupiah(selectedItem?.harga)"></span>
                                <span class="font-medium text-gray-800 dark:text-white" x-text="formatRupiah(totalHarga)"></span>
                            </div>

                            {{-- Ongkir line --}}
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Ongkos Kirim</span>
                                <span class="font-medium" :class="ongkir === 0 ? 'text-green-600 dark:text-green-400' : 'text-gray-800 dark:text-white'"
                                    x-text="ongkir === 0 ? 'Gratis' : formatRupiah(ongkir)"></span>
                            </div>
                        </div>

                        {{-- Metode Pengiriman badge --}}
                        <div class="mb-3 p-3 rounded-lg text-xs"
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

                        {{-- Metode Pembayaran badge --}}
                        <div x-show="metode"
                            class="mb-4 p-3 bg-gray-50 rounded-lg text-xs text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                            <b>Metode Bayar:</b> <span x-text="metode === 'transfer' ? 'Transfer Bank' : 'Cash (COD)'"></span>
                        </div>

                        {{-- Grand Total --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-800 dark:text-white">Total Tagihan</span>
                                <span class="text-xl font-bold text-brand-600 dark:text-brand-400" x-text="formatRupiah(grandTotal)"></span>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit"
                            :disabled="jumlah === 0"
                            :class="jumlah === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-brand-600 hover:shadow-lg'"
                            class="w-full mt-6 bg-brand-500 text-white py-3.5 rounded-xl font-bold transition-all duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Konfirmasi Transaksi</span>
                        </button>
                    </div>

                    {{-- Placeholder when no product is selected --}}
                    <div x-show="!selectedItem" class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Pilih produk untuk melihat ringkasan.</p>
                    </div>
                </div>

                {{-- Quick Info Card --}}
                <div class="rounded-xl border border-amber-200 bg-amber-50/50 p-4 dark:border-amber-800/30 dark:bg-amber-900/10">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed">Transaksi offline langsung berstatus <b>Selesai</b> dan tercatat di laporan keuangan secara otomatis.</p>
                    </div>
                </div>
            </div>
        </div>
        {{-- END RIGHT COLUMN --}}

    </form>
@endsection

