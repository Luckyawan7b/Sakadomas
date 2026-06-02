{{--
|--------------------------------------------------------------------------
| View: resources/views/landing/transfer-page.blade.php
|--------------------------------------------------------------------------
| Halaman Transfer Pembayaran dengan dynamic countdown timer.
| Route: GET /transaksi/pembayaran/{id}  → TransaksiController@halamanPembayaran
|
| Data dari Controller:
|   $transaksi  : Model Transaksi (with jenisTernak, detailTransaksi, survei)
|   $sisaDetik  : Integer — sisa detik hingga batas waktu
|   $batasWaktu : Carbon — waktu batas pembayaran
|   $waNumber   : Nomor WhatsApp
--}}

@extends('layouts.landing')

@section('title', 'Pembayaran #TRX-' . $transaksi->id_transaksi . ' | Smart-Saka')

@push('head')
<style>
    @keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
    .fade-in { animation: fadeUp 0.5s ease forwards; }
    .copy-toast { position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%); background: #3d6700; color: #fff; padding: 12px 24px; border-radius: 9999px; font-weight: 700; font-size: 14px; z-index: 999; opacity: 0; transition: opacity 0.3s; pointer-events: none; }
    .copy-toast.show { opacity: 1; }
</style>
@endpush

@section('content')

    {{-- ── NAVBAR ── --}}
    <x-landing.navbar />

    {{-- Toast notification --}}
    <div id="copy-toast" class="copy-toast">Nomor rekening berhasil disalin!</div>

    <main class="pt-32 pb-8">
        {{-- Step Indicator --}}
        <div class="max-w-4xl mx-auto px-8 mb-12">
            <div class="flex items-center justify-center gap-0">
                @foreach(['Data Pemesan', 'Pengambilan', 'Ringkasan'] as $step)
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-m3-primary text-white flex items-center justify-center text-xs font-bold">
                        <span class="material-symbols-outlined text-sm">check</span>
                    </div>
                    <span class="text-xs font-bold text-m3-primary hidden sm:inline">{{ $step }}</span>
                </div>
                <div class="w-12 h-0.5 bg-m3-primary mx-2"></div>
                @endforeach
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-m3-primary text-white flex items-center justify-center text-xs font-bold animate-pulse">4</div>
                    <span class="text-xs font-bold text-m3-primary hidden sm:inline">Pembayaran</span>
                </div>
            </div>
        </div>

        {{-- Countdown Banner --}}
        <div class="max-w-7xl mx-auto px-8 mb-10">
            <div class="bg-m3-primary text-m3-on-primary rounded-3xl p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-3xl">timer</span>
                    <div>
                        <p class="font-bold text-lg">Batas Waktu Transfer</p>
                        <p class="text-sm opacity-80">Selesaikan pembayaran sebelum {{ $batasWaktu->translatedFormat('d M Y H:i') }} WIB</p>
                    </div>
                </div>
                <div class="flex items-center gap-3" id="countdown">
                    <div class="bg-white/15 rounded-xl px-4 py-2 text-center min-w-[60px]">
                        <span class="text-2xl font-bold block" id="cd-hours">00</span>
                        <span class="text-[10px] opacity-70">Jam</span>
                    </div>
                    <span class="text-xl font-bold">:</span>
                    <div class="bg-white/15 rounded-xl px-4 py-2 text-center min-w-[60px]">
                        <span class="text-2xl font-bold block" id="cd-minutes">00</span>
                        <span class="text-[10px] opacity-70">Menit</span>
                    </div>
                    <span class="text-xl font-bold">:</span>
                    <div class="bg-white/15 rounded-xl px-4 py-2 text-center min-w-[60px]">
                        <span class="text-2xl font-bold block" id="cd-seconds">00</span>
                        <span class="text-[10px] opacity-70">Detik</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-8 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
            {{-- Left Column: Transfer Details --}}
            <div class="lg:col-span-7 space-y-10 fade-in">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-m3-primary tracking-tight mb-4">Selesaikan Pembayaran</h1>
                    <p class="text-m3-on-surface-variant text-lg">Transfer sejumlah yang tertera ke rekening di bawah ini untuk mengamankan pesanan Anda.</p>
                </div>

                {{-- Bank Details --}}
                <div class="bg-white rounded-3xl p-8 shadow-[0_10px_40px_rgba(61,103,0,0.06)]">
                    <h3 class="text-xl font-bold text-m3-primary mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-m3-primary">account_balance</span> Detail Rekening Tujuan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-[10px] uppercase tracking-widest font-bold text-m3-on-surface-variant mb-2">Nama Bank</p>
                            <p class="text-lg text-m3-primary font-bold">{{ config('smartsaka.bank_name', 'Bank Central Asia (BCA)') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest font-bold text-m3-on-surface-variant mb-2">Atas Nama</p>
                            <p class="text-lg text-m3-primary font-bold">{{ config('smartsaka.bank_account_name', 'PT Smart Saka Breeding') }}</p>
                        </div>
                    </div>
                    <div class="bg-m3-surface-container-low p-6 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <p class="text-[10px] uppercase tracking-widest font-bold text-m3-on-surface-variant mb-2">Nomor Rekening</p>
                            <p class="text-3xl text-m3-primary tracking-widest font-bold" id="account-number">{{ config('smartsaka.bank_account_number', '8930 1234 5678') }}</p>
                        </div>
                        <button onclick="copyAccountNumber()" id="copy-btn" class="flex items-center justify-center gap-2 px-6 py-3 bg-white border-2 border-m3-primary/20 rounded-full text-m3-primary hover:bg-m3-primary hover:text-white transition-all font-bold text-sm">
                            <span class="material-symbols-outlined text-lg">content_copy</span>
                            <span id="copy-text">Salin Nomor</span>
                        </button>
                    </div>
                    <div class="mt-6 p-4 bg-m3-primary-fixed/20 rounded-2xl flex gap-3 items-start">
                        <span class="material-symbols-outlined text-m3-primary">info</span>
                        <p class="text-sm text-m3-on-surface-variant"><strong class="text-m3-primary">Penting:</strong> Pastikan jumlah transfer sesuai dengan total tagihan. Pembayaran akan diverifikasi dalam 1x24 jam.</p>
                    </div>
                </div>

                {{-- Upload Section --}}
                <div class="bg-white rounded-3xl p-8 shadow-sm">
                    <h3 class="text-xl font-bold text-m3-primary mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-m3-primary">receipt_long</span> Upload Bukti Transfer
                    </h3>
                    <form method="POST" action="{{ route('transaksi.upload-bukti', $transaksi->id_transaksi) }}" enctype="multipart/form-data" id="upload-form">
                        @csrf
                        <input type="hidden" name="metode_pembayaran" value="transfer">
                        <input type="hidden" name="metode_pengiriman" value="{{ $transaksi->metode_pengiriman }}">

                        <div id="upload-area" class="border-2 border-dashed border-m3-primary/20 rounded-2xl p-10 text-center bg-m3-surface-container-lowest hover:bg-m3-primary/5 transition-colors cursor-pointer relative overflow-hidden" onclick="document.getElementById('file-input').click()">
                            <input type="file" id="file-input" name="bukti_pembayaran" accept="image/*" class="hidden" onchange="handleFileUpload(this)"/>
                            <div id="upload-placeholder">
                                <div class="w-20 h-20 mx-auto bg-m3-surface-container-low rounded-full flex items-center justify-center mb-6">
                                    <span class="material-symbols-outlined text-4xl text-m3-primary">upload_file</span>
                                </div>
                                <p class="text-xl font-bold text-m3-primary mb-2">Unggah Bukti Transfer</p>
                                <p class="text-m3-on-surface-variant mb-6">Klik atau seret file ke area ini (JPG, PNG maks. 2MB)</p>
                                <span class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-m3-primary/20 text-m3-primary font-bold rounded-full text-sm hover:border-m3-primary transition-all">
                                    <span class="material-symbols-outlined text-sm">folder_open</span> Pilih File
                                </span>
                            </div>
                            <div id="upload-preview" class="hidden">
                                <div class="flex items-center gap-4 p-4 bg-m3-primary-fixed/20 rounded-2xl">
                                    <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center overflow-hidden" id="preview-thumb">
                                        <span class="material-symbols-outlined text-3xl text-m3-primary">image</span>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <p class="font-bold text-m3-primary" id="file-name">file.jpg</p>
                                        <p class="text-xs text-m3-on-surface-variant" id="file-size">0 KB</p>
                                    </div>
                                    <button type="button" onclick="removeFile(event)" class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-m3-error hover:bg-m3-error-container transition-all">
                                        <span class="material-symbols-outlined">close</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if($errors->any())
                        <div class="mt-3 text-m3-error text-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">error</span>
                            {{ $errors->first() }}
                        </div>
                        @endif

                        {{-- Action Button --}}
                        <div class="pt-6">
                            <button type="submit" class="w-full px-10 py-5 bg-m3-primary text-m3-on-primary font-bold rounded-full shadow-lg hover:bg-m3-primary-container active:scale-[0.98] transition-all text-xl flex items-center justify-center gap-3">
                                Konfirmasi Pembayaran
                                <span class="material-symbols-outlined text-2xl">arrow_forward</span>
                            </button>
                            <p class="text-center text-xs text-m3-on-surface-variant mt-4">Dengan mengonfirmasi, Anda menyetujui syarat & ketentuan Smart-Saka</p>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Right Column: Order Summary --}}
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-8 shadow-[0_10px_40px_rgba(61,103,0,0.06)] sticky top-32 space-y-8">
                    {{-- Total Highlight --}}
                    <div class="text-center">
                        <div class="flex items-center justify-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-m3-primary text-sm" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                            <p class="text-[10px] tracking-widest text-m3-primary uppercase font-bold">Pesanan #TRX-{{ $transaksi->id_transaksi }}</p>
                        </div>
                        <p class="text-[10px] tracking-widest text-m3-outline uppercase mb-2">Jumlah Transfer</p>
                        <p class="text-5xl text-m3-primary font-bold tracking-tight">Rp {{ number_format($transaksi->total_harga + $transaksi->ongkir, 0, ',', '.') }}</p>
                    </div>

                    <div class="h-px bg-m3-outline-variant/30"></div>

                    <h2 class="text-xl font-bold text-m3-primary">Ringkasan Pesanan</h2>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-16 h-16 rounded-2xl overflow-hidden bg-m3-surface-container-low shrink-0 flex items-center justify-center">
                                <span class="material-symbols-outlined text-2xl text-m3-outline-variant">pets</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-m3-primary">{{ $transaksi->jenisTernak->jenis_ternak ?? 'Domba' }} {{ $transaksi->jenis_kelamin_pesanan }}</h4>
                                <p class="text-sm text-m3-outline">Jumlah: {{ $transaksi->total_jumlah }} ekor</p>
                            </div>
                        </div>
                        <div class="h-px bg-m3-outline-variant/20"></div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-m3-on-surface-variant">Harga Ternak</span>
                                <span class="text-m3-on-surface font-bold">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-m3-on-surface-variant">Ongkos Kirim</span>
                                <span class="text-m3-on-surface font-bold">
                                    @if($transaksi->ongkir > 0)
                                        Rp {{ number_format($transaksi->ongkir, 0, ',', '.') }}
                                    @else
                                        {{ $transaksi->metode_pengiriman === 'ambil_sendiri' ? 'Gratis (Ambil Sendiri)' : 'Konfirmasi Admin' }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between pt-4 border-t border-dashed border-m3-outline-variant">
                                <span class="font-bold text-m3-primary uppercase tracking-widest text-xs">Total</span>
                                <span class="text-2xl font-bold text-m3-primary">Rp {{ number_format($transaksi->total_harga + $transaksi->ongkir, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Protection badges --}}
                    <div class="p-4 bg-m3-surface-container-low rounded-2xl space-y-3">
                        <p class="text-xs font-bold text-m3-primary flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">shield</span> Proteksi Transaksi
                        </p>
                        <ul class="space-y-2">
                            <li class="flex items-center gap-2 text-[11px] text-m3-on-surface-variant">
                                <span class="material-symbols-outlined text-[14px] text-m3-primary">check_circle</span> Verifikasi Kesehatan Hewan
                            </li>
                            <li class="flex items-center gap-2 text-[11px] text-m3-on-surface-variant">
                                <span class="material-symbols-outlined text-[14px] text-m3-primary">check_circle</span> Garansi Pengiriman Aman
                            </li>
                            <li class="flex items-center gap-2 text-[11px] text-m3-on-surface-variant">
                                <span class="material-symbols-outlined text-[14px] text-m3-primary">check_circle</span> Pendampingan Pelihara (1 Bulan)
                            </li>
                        </ul>
                    </div>

                    {{-- WhatsApp Help --}}
                    <div class="bg-[#25D366]/10 p-4 rounded-2xl flex gap-3">
                        <svg class="w-5 h-5 text-[#25D366] shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <div>
                            <p class="text-sm font-bold text-[#25D366]">Butuh Bantuan?</p>
                            <p class="text-xs text-m3-on-surface-variant">Hubungi admin via WhatsApp untuk pertanyaan pembayaran</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- ── FOOTER ── --}}
    <x-landing.footer wa-number="{{ $waNumber ?? config('smartsaka.wa_number') }}" />

@endsection

@push('scripts')
<script>
    // Dynamic Countdown Timer — sisa detik dari server
    let totalSeconds = {{ $sisaDetik }};

    function updateCountdown() {
        if (totalSeconds <= 0) {
            document.getElementById('cd-hours').textContent = '00';
            document.getElementById('cd-minutes').textContent = '00';
            document.getElementById('cd-seconds').textContent = '00';
            return;
        }
        totalSeconds--;
        const h = Math.floor(totalSeconds / 3600);
        const m = Math.floor((totalSeconds % 3600) / 60);
        const s = totalSeconds % 60;
        document.getElementById('cd-hours').textContent = String(h).padStart(2, '0');
        document.getElementById('cd-minutes').textContent = String(m).padStart(2, '0');
        document.getElementById('cd-seconds').textContent = String(s).padStart(2, '0');
    }
    updateCountdown(); // Initial render
    setInterval(updateCountdown, 1000);

    // Copy account number
    function copyAccountNumber() {
        const number = document.getElementById('account-number').textContent.replace(/\s/g, '');
        navigator.clipboard.writeText(number).then(() => {
            const toast = document.getElementById('copy-toast');
            toast.classList.add('show');
            const copyText = document.getElementById('copy-text');
            copyText.textContent = 'Tersalin!';
            setTimeout(() => {
                toast.classList.remove('show');
                copyText.textContent = 'Salin Nomor';
            }, 2000);
        });
    }

    // File upload with preview
    function handleFileUpload(input) {
        const file = input.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            return;
        }
        document.getElementById('upload-placeholder').classList.add('hidden');
        document.getElementById('upload-preview').classList.remove('hidden');
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-size').textContent = (file.size / 1024).toFixed(1) + ' KB';
        document.getElementById('upload-area').classList.add('border-m3-primary');

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const thumb = document.getElementById('preview-thumb');
                thumb.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover" alt="preview"/>';
            };
            reader.readAsDataURL(file);
        }
    }

    function removeFile(e) {
        e.stopPropagation();
        document.getElementById('file-input').value = '';
        document.getElementById('upload-placeholder').classList.remove('hidden');
        document.getElementById('upload-preview').classList.add('hidden');
        document.getElementById('upload-area').classList.remove('border-m3-primary');
        document.getElementById('preview-thumb').innerHTML = '<span class="material-symbols-outlined text-3xl text-m3-primary">image</span>';
    }
</script>
@endpush
