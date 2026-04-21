{{--
|--------------------------------------------------------------------------
| Component: x-landing.faq
|--------------------------------------------------------------------------
| Section FAQ dengan accordion Alpine.js.
| KOMPONEN BARU — tidak ada di prototipe asli.
|
| Props:
|   - $faqs     : array of ['question', 'answer'] (optional)
|                 Default: data hardcoded, relevan untuk bisnis ternak
|   - $waNumber : Nomor WA untuk CTA di bawah FAQ
|ww
| Dari Controller:
|   <x-landing.faq :faqs="$faqs" wa-number="{{ config('smartsaka.wa_number') }}" />
--}}

@props([
    'faqs' => null,
    'waNumber' => null,
])

@php
    $waNumber = $waNumber ?? config('smartsaka.wa_number');
    $items = $faqs ?? [
        [
            'question' => 'Bagaimana cara memesan domba atau kambing dari Smart-Saka?',
            'answer' =>
                'Pemesanan sangat mudah — cukup hubungi kami via WhatsApp di tombol yang tersedia, atau klik tombol "Pesan" pada produk yang Anda minati. Tim kami akan membantu dari proses pemilihan hewan, konfirmasi stok, hingga pengaturan pengiriman ke lokasi Anda.',
        ],
        [
            'question' => 'Apakah tersedia layanan pengiriman ke luar Jember?',
            'answer' =>
                'Ya, kami melayani pengiriman ke seluruh wilayah Jawa Timur dengan kendaraan khusus ternak yang aman dan nyaman. Untuk pengiriman ke luar Jawa Timur, silakan hubungi kami terlebih dahulu untuk koordinasi lebih lanjut.',
        ],
        [
            'question' => 'Apakah hewan ternak sudah divaksinasi dan ada sertifikat kesehatannya?',
            'answer' =>
                'Semua hewan di Smart-Saka menjalani vaksinasi rutin dan pemeriksaan kesehatan berkala oleh dokter hewan berpengalaman. Sertifikat kesehatan hewan dapat disediakan atas permintaan, terutama untuk pembelian dalam jumlah besar atau kebutuhan resmi.',
        ],
        [
            'question' => 'Berapa minimal pembelian dan apakah ada harga grosir?',
            'answer' =>
                'Tidak ada minimal pembelian — kami melayani dari 1 ekor hingga ratusan ekor. Untuk pembelian di atas 5 ekor, kami menyediakan harga khusus. Hubungi kami untuk mendapatkan penawaran terbaik sesuai kebutuhan Anda.',
        ],
        [
            'question' => 'Bagaimana dengan layanan aqiqah dan kurban?',
            'answer' =>
                'Kami menyediakan paket aqiqah dan kurban lengkap — mulai dari pemilihan hewan yang sesuai syariat, pemotongan oleh tim berpengalaman, hingga distribusi atau pengantaran daging ke lokasi yang Anda tentukan. Harga transparan dan proses amanah.',
        ],
        [
            'question' => 'Apakah tersedia konsultasi untuk pemula yang ingin mulai beternak?',
            'answer' =>
                'Tentu! Kami dengan senang hati memberikan konsultasi gratis untuk siapa saja yang ingin memulai usaha ternak domba atau kambing. Tim kami akan membimbing dari pemilihan bibit, manajemen pakan, hingga pengelolaan kandang yang efisien.',
        ],
    ];
@endphp

<section id="faq" class="py-24 lg:py-32 bg-cream-50 relative overflow-hidden" aria-labelledby="faq-heading">
    {{-- Decorative --}}
    <div class="absolute left-0 top-0 w-1/3 h-full dot-grid opacity-15 pointer-events-none" aria-hidden="true"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-5 lg:px-8">

        {{-- Section header --}}
        <div class="text-center mb-16 reveal">
            <div class="section-badge mb-4 mx-auto w-fit">FAQ</div>
            <h2 id="faq-heading" class="font-serif text-4xl lg:text-5xl text-olive-900 mb-4">
                Pertanyaan yang<br>
                <span class="italic text-bark-600">Sering Ditanyakan</span>
            </h2>
            <p class="text-olive-700/70 text-base max-w-xl mx-auto">
                Tidak menemukan jawaban yang Anda cari? Langsung hubungi kami via WhatsApp — kami siap membantu!
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-6 lg:gap-10 items-start">

            {{-- ── LEFT: FAQ Accordion ── --}}
            <div class="reveal-left space-y-3" x-data="{ openIndex: 0 }" role="list"
                aria-label="Daftar pertanyaan umum">
                @foreach ($items as $i => $faq)
                    <div class="bg-white rounded-2xl border border-olive-100 overflow-hidden transition-shadow hover:shadow-sm"
                        role="listitem">
                        <button @click="openIndex = openIndex === {{ $i }} ? null : {{ $i }}"
                            :aria-expanded="openIndex === {{ $i }}"
                            aria-controls="faq-answer-{{ $i }}"
                            class="w-full flex items-start justify-between gap-4 text-left px-6 py-5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-olive-400">
                            <span
                                class="font-semibold text-olive-900 text-sm leading-relaxed">{{ $faq['question'] }}</span>

                            {{-- Chevron icon — rotates on open --}}
                            <span
                                class="shrink-0 w-6 h-6 rounded-lg bg-olive-100 flex items-center justify-center text-olive-600 transition-transform duration-300 mt-0.5"
                                :class="openIndex === {{ $i }} ? 'rotate-180 bg-olive-200' : ''"
                                aria-hidden="true">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>

                        {{-- Answer — Alpine x-show dengan transition smooth --}}
                        <div id="faq-answer-{{ $i }}" x-show="openIndex === {{ $i }}"
                            x-transition:enter="transition ease-out duration-250"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1" class="px-6 pb-5" role="region">
                            <div class="text-olive-700/80 text-sm leading-relaxed border-t border-olive-100 pt-4">
                                {{ $faq['answer'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ── RIGHT: CTA Card ── --}}
            <div class="reveal-right lg:sticky lg:top-24">
                <div class="bg-olive-950 rounded-3xl p-8 lg:p-10 text-cream-50">
                    <div class="w-12 h-12 bg-olive-800 rounded-2xl flex items-center justify-center mb-6"
                        aria-hidden="true">
                        <svg class="w-6 h-6 text-olive-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>

                    <h3 class="font-serif text-2xl text-cream-50 mb-3">Masih ada pertanyaan?</h3>
                    <p class="text-cream-300/70 text-sm leading-relaxed mb-8">
                        Tim Smart-Saka siap membantu Anda setiap hari. Konsultasi gratis, jawaban cepat, dan tidak ada
                        pertanyaan yang terlalu kecil untuk kami.
                    </p>

                    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Smart-Saka! Saya punya pertanyaan tentang ternak Anda.') }}"
                        target="_blank" rel="noopener noreferrer"
                        class="w-full inline-flex items-center justify-center gap-3 bg-[#25D366] hover:bg-[#22c55e] text-white font-bold py-3.5 px-6 rounded-xl transition-all hover:shadow-lg hover:shadow-[#25D366]/30 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#25D366] focus-visible:ring-offset-2 focus-visible:ring-offset-olive-950"
                        aria-label="Hubungi Smart-Saka via WhatsApp">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                        Chat via WhatsApp
                    </a>

                    <div class="mt-6 pt-6 border-t border-olive-800 flex items-center gap-3">
                        <svg class="w-4 h-4 text-olive-500 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-cream-300/50 text-xs">Respon dalam &lt; 1 jam pada hari kerja</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
