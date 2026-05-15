@extends('layouts.landing')

@section('title', 'Pengajuan Survei Berhasil | Smart-Saka Premium Sheep Farm')

@push('head')
<style>
    @keyframes pulse-ring { 0% { transform: scale(0.9); opacity: 1; } 100% { transform: scale(1.5); opacity: 0; } }
    .pulse-ring { animation: pulse-ring 2s ease-out infinite; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .fade-up { animation: fadeUp 0.6s ease forwards; }
    .fade-up-delay { animation: fadeUp 0.6s ease 0.2s forwards; opacity: 0; }
    .fade-up-delay2 { animation: fadeUp 0.6s ease 0.4s forwards; opacity: 0; }
</style>
@endpush

@section('content')
<x-landing.navbar />

<main class="pt-40 pb-20 max-w-5xl mx-auto px-8">
    <!-- Success Hero -->
    <div class="text-center mb-16 fade-up">
        <div class="relative inline-block mb-8">
            <div class="absolute inset-0 w-28 h-28 rounded-full bg-m3-primary/10 pulse-ring mx-auto"></div>
            <div class="relative w-28 h-28 rounded-full bg-m3-primary/10 flex items-center justify-center mx-auto">
                <span class="material-symbols-outlined !text-6xl text-m3-primary" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            </div>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold text-m3-primary mb-4 tracking-tight font-headline">Pengajuan Survei Berhasil!</h1>
        <p class="text-lg text-m3-on-surface-variant max-w-2xl mx-auto leading-relaxed">
            Tim kami akan mengonfirmasi jadwal kunjungan Anda dalam waktu <strong class="text-m3-primary">24 jam</strong>. Anda akan dihubungi melalui WhatsApp untuk koordinasi lebih lanjut.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
        <!-- Left: Summary + Countdown -->
        <div class="lg:col-span-2 space-y-8 fade-up-delay">
            <!-- Ringkasan -->
            <div class="bg-white rounded-3xl p-8 shadow-[0_10px_40px_rgba(61,103,0,0.06)]">
                <h2 class="text-xl font-bold text-m3-primary mb-6 flex items-center gap-3 font-headline">
                    <span class="material-symbols-outlined">summarize</span> Ringkasan Pengajuan
                </h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-m3-surface-container">
                        <span class="text-m3-on-surface-variant">Tanggal Kunjungan</span>
                        <span class="font-bold text-m3-on-surface">{{ \Carbon\Carbon::parse($survei->tgl_survei)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-m3-surface-container">
                        <span class="text-m3-on-surface-variant">Waktu</span>
                        <span class="font-bold text-m3-on-surface">{{ \Carbon\Carbon::parse($survei->tgl_survei)->format('H:i') }} WIB</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-m3-surface-container">
                        <span class="text-m3-on-surface-variant">Nama Pemesan</span>
                        <span class="font-bold text-m3-on-surface">{{ $survei->akun->nama }}</span>
                    </div>
                    <div class="flex justify-between items-start py-3">
                        <span class="text-m3-on-surface-variant">Lokasi Peternakan</span>
                        <span class="font-bold text-m3-on-surface text-right">
                            {{ config('smartsaka.farm_name', 'Smart-Saka Estate 1') }}<br/>
                            <span class="text-sm text-m3-on-surface-variant font-normal">{{ config('smartsaka.farm_address', 'Kec. Patrang, Kab. Jember') }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Countdown Timer -->
            <div class="bg-m3-primary text-m3-on-primary rounded-3xl p-8 shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <span class="material-symbols-outlined text-2xl">timer</span>
                    <h3 class="text-lg font-bold">Batas Konfirmasi Admin</h3>
                </div>
                <div class="grid grid-cols-4 gap-4 text-center" id="countdown">
                    <div class="bg-white/10 rounded-2xl p-4">
                        <span class="text-3xl font-bold block" id="cd-hours">00</span>
                        <span class="text-xs opacity-70">Jam</span>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-4">
                        <span class="text-3xl font-bold block" id="cd-minutes">00</span>
                        <span class="text-xs opacity-70">Menit</span>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-4">
                        <span class="text-3xl font-bold block" id="cd-seconds">00</span>
                        <span class="text-xs opacity-70">Detik</span>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-4 flex flex-col items-center justify-center">
                        <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">notifications_active</span>
                        <span class="text-xs opacity-70 mt-1">Aktif</span>
                    </div>
                </div>
                <p class="text-sm opacity-70 mt-4">Jika belum ada konfirmasi setelah 24 jam, silakan hubungi admin langsung.</p>
            </div>

            <!-- Timeline / Langkah Selanjutnya -->
            <div class="bg-white rounded-3xl p-8 shadow-sm">
                <h2 class="text-xl font-bold text-m3-primary mb-6 flex items-center gap-3 font-headline">
                    <span class="material-symbols-outlined">route</span> Langkah Selanjutnya
                </h2>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-m3-primary text-white flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check</span>
                            </div>
                            <div class="w-0.5 flex-1 bg-m3-primary mt-2"></div>
                        </div>
                        <div class="pb-6">
                            <p class="font-bold text-m3-primary">Pengajuan Terkirim</p>
                            <p class="text-sm text-m3-on-surface-variant">Formulir survei Anda sudah diterima sistem kami</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-m3-primary-fixed text-m3-primary flex items-center justify-center animate-pulse">
                                <span class="material-symbols-outlined text-sm">hourglass_top</span>
                            </div>
                            <div class="w-0.5 flex-1 bg-m3-outline-variant mt-2"></div>
                        </div>
                        <div class="pb-6">
                            <p class="font-bold text-m3-on-surface">Menunggu Konfirmasi Admin</p>
                            <p class="text-sm text-m3-on-surface-variant">Admin akan menghubungi via WhatsApp dalam 24 jam</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-m3-surface-container-high text-m3-outline flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm">calendar_month</span>
                            </div>
                            <div class="w-0.5 flex-1 bg-m3-outline-variant mt-2"></div>
                        </div>
                        <div class="pb-6">
                            <p class="font-bold text-m3-outline">Jadwal Dikonfirmasi</p>
                            <p class="text-sm text-m3-on-surface-variant">Tanggal dan waktu kunjungan final disetujui</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-m3-surface-container-high text-m3-outline flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm">agriculture</span>
                            </div>
                        </div>
                        <div>
                            <p class="font-bold text-m3-outline">Kunjungan ke Peternakan</p>
                            <p class="text-sm text-m3-on-surface-variant">Datang ke Smart-Saka Farm sesuai jadwal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="space-y-8 fade-up-delay2">
            <!-- CTA Buttons -->
            <div class="bg-white rounded-3xl p-8 shadow-sm space-y-4">
                <a href="https://wa.me/{{ config('smartsaka.wa_number') }}" target="_blank" class="w-full bg-[#25D366] text-white py-4 rounded-full font-bold text-center flex items-center justify-center gap-3 hover:opacity-90 transition-all shadow-lg">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Chat WhatsApp Admin
                </a>
                <a href="{{ route('katalog') }}" class="w-full bg-m3-primary text-m3-on-primary py-4 rounded-full font-bold text-center flex items-center justify-center gap-2 hover:bg-[#4f8207] transition-all">
                    <span class="material-symbols-outlined text-sm">storefront</span> Kembali ke Katalog
                </a>
                <a href="{{ route('home') }}" class="w-full bg-m3-surface-container text-m3-on-surface-variant py-4 rounded-full font-bold text-center flex items-center justify-center gap-2 hover:bg-m3-surface-variant transition-all">
                    <span class="material-symbols-outlined text-sm">home</span> Beranda
                </a>
            </div>

            <!-- FAQ Section -->
            <div class="bg-white rounded-3xl p-8 shadow-sm">
                <h3 class="text-lg font-bold text-m3-primary mb-6 flex items-center gap-2 font-headline">
                    <span class="material-symbols-outlined">help</span> Pertanyaan Umum
                </h3>
                <div class="space-y-4">
                    <details class="group">
                        <summary class="flex items-center justify-between cursor-pointer py-3 border-b border-m3-outline-variant/20 font-bold text-sm text-m3-on-surface">
                            Berapa lama konfirmasi admin?
                            <span class="material-symbols-outlined text-m3-primary group-open:rotate-180 transition-transform">expand_more</span>
                        </summary>
                        <p class="text-sm text-m3-on-surface-variant py-3">Admin akan mengonfirmasi jadwal survei Anda maksimal 24 jam setelah pengajuan diterima melalui WhatsApp.</p>
                    </details>
                    <details class="group">
                        <summary class="flex items-center justify-between cursor-pointer py-3 border-b border-m3-outline-variant/20 font-bold text-sm text-m3-on-surface">
                            Apakah bisa mengubah jadwal?
                            <span class="material-symbols-outlined text-m3-primary group-open:rotate-180 transition-transform">expand_more</span>
                        </summary>
                        <p class="text-sm text-m3-on-surface-variant py-3">Tentu bisa. Hubungi admin via WhatsApp untuk mengatur ulang jadwal kunjungan Anda.</p>
                    </details>
                    <details class="group">
                        <summary class="flex items-center justify-between cursor-pointer py-3 border-b border-m3-outline-variant/20 font-bold text-sm text-m3-on-surface">
                            Apa yang harus dibawa saat survei?
                            <span class="material-symbols-outlined text-m3-primary group-open:rotate-180 transition-transform">expand_more</span>
                        </summary>
                        <p class="text-sm text-m3-on-surface-variant py-3">Cukup membawa identitas diri (KTP/SIM). Jika berencana langsung membeli, siapkan DP minimal 30%.</p>
                    </details>
                    <details class="group">
                        <summary class="flex items-center justify-between cursor-pointer py-3 font-bold text-sm text-m3-on-surface">
                            Apakah survei dikenakan biaya?
                            <span class="material-symbols-outlined text-m3-primary group-open:rotate-180 transition-transform">expand_more</span>
                        </summary>
                        <p class="text-sm text-m3-on-surface-variant py-3">Tidak, survei ke peternakan Smart-Saka sepenuhnya gratis tanpa biaya apapun.</p>
                    </details>
                </div>
            </div>
        </div>
    </div>
</main>

<x-landing.footer
    wa-number="{{ config('smartsaka.wa_number') }}"
    email="{{ config('smartsaka.email') }}"
    :address="config('smartsaka.address')"
    map-src="{{ config('smartsaka.maps_embed_src') }}"
/>

@push('scripts')
<script>
    let totalSeconds = {{ $sisaDetik }};
    function updateCountdown() {
        if (totalSeconds <= 0) return;
        totalSeconds--;
        const h = Math.floor(totalSeconds / 3600);
        const m = Math.floor((totalSeconds % 3600) / 60);
        const s = totalSeconds % 60;
        document.getElementById('cd-hours').textContent = String(h).padStart(2, '0');
        document.getElementById('cd-minutes').textContent = String(m).padStart(2, '0');
        document.getElementById('cd-seconds').textContent = String(s).padStart(2, '0');
    }
    setInterval(updateCountdown, 1000);
    updateCountdown();
</script>
@endpush
@endsection
