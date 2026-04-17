{{--
|--------------------------------------------------------------------------
| Component: x-landing.testimonial-slider
|--------------------------------------------------------------------------
| Testimonial carousel menggunakan Alpine.js x-data.
| Menggantikan slider Vanilla JS dari prototipe asli.
|
| Props:
|   - $testimonials : array of ['quote', 'name', 'role', 'image'] (optional)
|                     Default: data hardcoded di component
|                     Dari controller: $testimonials = Testimonial::active()->get()->toArray()
|
| Contoh dari Controller:
|   <x-landing.testimonial-slider :testimonials="$testimonials" />
--}}

@props([
    'testimonials' => null,
])

@php
    // Data default — ganti dengan data dari DB ketika sudah live
    $items = $testimonials ?? [
        [
            'quote' => 'Domba dari Smart-Saka sangat gemuk dan sehat. Kami sudah 3 tahun berturut-turut membeli di sini untuk kebutuhan kurban. Sangat puas dan tidak akan pindah ke tempat lain!',
            'name'  => 'Daniel M.',
            'role'  => 'Pelanggan Tetap • Surabaya',
            'image' => asset('images/landing/testi-collage-1.jpg'),
        ],
        [
            'quote' => 'Layanan luar biasa! Dari proses pemesanan, pengiriman sampai hewan tiba, semuanya profesional. Kambing Etawa yang saya beli produksi susunya benar-benar tinggi. Highly recommended!',
            'name'  => 'Siti Rahayu',
            'role'  => 'Usaha Susu Kambing • Malang',
            'image' => asset('images/landing/testi-collage-2.jpg'),
        ],
        [
            'quote' => 'Saya beli 10 ekor domba Crosstexel untuk investasi ternak. Dalam 6 bulan hasilnya melebihi ekspektasi. Tim Smart-Saka juga sangat responsif dalam memberikan pendampingan.',
            'name'  => 'Budi Prasetyo',
            'role'  => 'Peternak Mitra • Jember',
            'image' => asset('images/landing/testi-collage-3.jpg'),
        ],
    ];
    $total = count($items);
@endphp

{{--
    Alpine.js x-data untuk slider:
    - autoplay setiap 5.5 detik (interval di-reset saat user interaksi)
    - keyboard-accessible (tombol prev/next)
    - dots navigasi
--}}
<div
    x-data="{
        current: 0,
        total: {{ $total }},
        timer: null,
        startAuto() {
            clearInterval(this.timer);
            this.timer = setInterval(() => this.next(), 5500);
        },
        next() { this.current = (this.current + 1) % this.total; this.startAuto(); },
        prev() { this.current = (this.current - 1 + this.total) % this.total; this.startAuto(); },
        goto(i) { this.current = i; this.startAuto(); }
    }"
    x-init="startAuto()"
    @keydown.arrow-right.window="next()"
    @keydown.arrow-left.window="prev()"
    class="w-full"
    role="region"
    aria-label="Slider testimoni pelanggan"
>
    {{-- Slide container --}}
    <div class="relative overflow-hidden">
        <div
            class="flex transition-transform duration-500 ease-[cubic-bezier(.4,0,.2,1)]"
            :style="'transform: translateX(-' + (current * 100) + '%)'"
        >
            @foreach ($items as $i => $testi)
                <div class="w-full shrink-0" role="tabpanel" :aria-hidden="current !== {{ $i }}">
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-olive-100">

                        {{-- Stars --}}
                        <div class="flex gap-1 mb-5" aria-label="Rating 5 bintang">
                            @for ($s = 0; $s < 5; $s++)
                                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>

                        {{-- Quote --}}
                        <blockquote class="text-olive-800 text-base leading-relaxed mb-6 italic">
                            "{{ $testi['quote'] }}"
                        </blockquote>

                        {{-- Author --}}
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-olive-200 shrink-0">
                                <img
                                    src="{{ $testi['image'] }}"
                                    alt="Foto {{ $testi['name'] }}"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                    width="48"
                                    height="48"
                                >
                            </div>
                            <div>
                                <p class="font-semibold text-olive-800 text-sm">{{ $testi['name'] }}</p>
                                <p class="text-xs text-olive-500 mt-0.5">{{ $testi['role'] }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Controls --}}
    <div class="flex items-center gap-4 mt-6" role="group" aria-label="Kontrol slider">

        {{-- Prev --}}
        <button
            @click="prev()"
            class="w-10 h-10 rounded-xl border-2 border-olive-200 flex items-center justify-center text-olive-700 hover:border-olive-500 hover:bg-olive-50 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-olive-500"
            aria-label="Testimoni sebelumnya"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        {{-- Dots --}}
        <div class="flex gap-2" role="tablist" aria-label="Pilih slide testimoni">
            @foreach ($items as $i => $testi)
                <button
                    @click="goto({{ $i }})"
                    :class="current === {{ $i }}
                        ? 'w-6 bg-olive-700'
                        : 'w-2.5 bg-olive-200 hover:bg-olive-300'"
                    class="h-2.5 rounded-full transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-olive-500"
                    role="tab"
                    :aria-selected="current === {{ $i }}"
                    aria-label="Slide {{ $i + 1 }}: {{ $testi['name'] }}"
                ></button>
            @endforeach
        </div>

        {{-- Next --}}
        <button
            @click="next()"
            class="w-10 h-10 rounded-xl border-2 border-olive-200 flex items-center justify-center text-olive-700 hover:border-olive-500 hover:bg-olive-50 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-olive-500"
            aria-label="Testimoni berikutnya"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

    </div>
</div>

