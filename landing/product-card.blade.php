{{--
|--------------------------------------------------------------------------
| Component: x-landing.product-card
|--------------------------------------------------------------------------
| Card produk ternak di section katalog.
| Mendukung looping dari Controller via props.
|
| Props:
|   - $title       : Nama produk (string, wajib)
|   - $description : Deskripsi singkat produk (string, wajib)
|   - $price       : Harga tampil, misal "Rp 3.500.000" (string, wajib)
|   - $priceRaw    : Harga angka untuk WA message, misal "3500000" (string)
|   - $image       : Path gambar via asset() (string, wajib)
|   - $imageAlt    : Alt text gambar (string, default: $title)
|   - $badge       : Label badge kiri atas, misal "Best Seller" (string, nullable)
|   - $badgeColor  : Variant warna badge: 'olive' | 'bark' | 'cream' | 'green' (default: 'olive')
|   - $category    : Label kategori kanan atas, misal "Jantan" (string, nullable)
|   - $waNumber    : Nomor WhatsApp untuk tombol Pesan (string)
|   - $delay       : Transition delay untuk stagger animation, misal "0.12s" (string)
|   - $featured    : Tampilkan card sebagai variant dark (untuk Paket Aqiqah) (bool)
|
| Contoh looping dari Controller:
|   @foreach($products as $product)
|       <x-landing.product-card
|           :title="$product->name"
|           :description="$product->description"
|           :price="'Rp ' . number_format($product->price, 0, ',', '.')"
|           :price-raw="$product->price"
|           :image="asset('images/products/' . $product->image)"
|           :badge="$product->badge"
|           :category="$product->category"
|           :delay="$loop->index * 0.07 . 's'"
|       />
|   @endforeach
--}}

@props([
    'title',
    'description',
    'price',
    'priceRaw'   => '',
    'image',
    'imageAlt'   => null,
    'badge'      => null,
    'badgeColor' => 'olive',
    'category'   => null,
    'waNumber'   => null,
    'delay'      => '0s',
    'featured'   => false,
])

@php
    $waNumber = $waNumber ?? config('smartsaka.wa_number');
    $badgeClasses = match($badgeColor) {
        'bark'  => 'bg-bark-600 text-cream-50',
        'cream' => 'bg-cream-400 text-olive-900',
        'green' => 'bg-olive-200 text-olive-800',
        default => 'bg-olive-700 text-cream-50',
    };

    $altText = $imageAlt ?? $title;

    // WA pesan pre-filled
    $waMessage = urlencode("Halo Smart-Saka! 🐑\n\nSaya tertarik dengan *{$title}* ({$price}).\n\nBisa minta info stok dan cara pemesanan?\n\nTerima kasih.");
    $waHref = "https://wa.me/{$waNumber}?text={$waMessage}";
@endphp

@if ($featured)

    {{-- ── VARIANT FEATURED (dark card) — untuk Paket Aqiqah & Kurban ── --}}
    <article
        class="product-card bg-gradient-to-br from-olive-700 to-olive-900 rounded-3xl overflow-hidden shadow-sm reveal"
        style="transition-delay: {{ $delay }}"
        aria-label="{{ $title }}"
    >
        <div class="p-8 flex flex-col justify-between h-full min-h-[320px]">
            <div>
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-3xl mb-6" aria-hidden="true">🐐</div>
                <h3 class="font-serif text-2xl text-cream-50 mb-3">{{ $title }}</h3>
                <p class="text-cream-200/80 text-sm leading-relaxed mb-6">{{ $description }}</p>
            </div>
            <div>
                <p class="text-cream-200 text-xs font-medium mb-2">Hubungi untuk penawaran spesial</p>
                <a
                    href="{{ $waHref }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-white text-olive-800 font-semibold text-sm px-5 py-2.5 rounded-xl hover:bg-cream-100 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-olive-800"
                    aria-label="Tanya harga {{ $title }} via WhatsApp"
                >
                    Tanya Harga
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </article>

@else

    {{-- ── VARIANT STANDAR ── --}}
    <article
        class="product-card bg-white rounded-3xl overflow-hidden shadow-sm border border-olive-100 reveal"
        style="transition-delay: {{ $delay }}"
        aria-label="{{ $title }}"
    >
        {{-- Image --}}
        <div class="relative aspect-[4/3] overflow-hidden">
            <img
                src="{{ $image }}"
                alt="{{ $altText }}"
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                loading="lazy"
                width="600"
                height="450"
            >

            {{-- Badge kiri atas --}}
            @if ($badge)
                <div class="absolute top-3 left-3">
                    <span class="{{ $badgeClasses }} text-xs font-bold px-3 py-1.5 rounded-full">{{ $badge }}</span>
                </div>
            @endif

            {{-- Quick add — langsung ke WA --}}
            <a
                href="{{ $waHref }}"
                target="_blank"
                rel="noopener noreferrer"
                class="absolute bottom-3 right-3 w-10 h-10 bg-white rounded-xl shadow-md flex items-center justify-center text-olive-700 hover:bg-olive-700 hover:text-white transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-olive-600"
                aria-label="Pesan {{ $title }} via WhatsApp"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </a>
        </div>

        {{-- Card body --}}
        <div class="p-6">
            <div class="flex items-start justify-between mb-1">
                <h3 class="font-serif text-xl text-olive-900">{{ $title }}</h3>
                @if ($category)
                    <span class="text-xs text-olive-500 bg-olive-50 px-2 py-0.5 rounded-full font-medium shrink-0 ml-2">{{ $category }}</span>
                @endif
            </div>

            <p class="text-olive-600/70 text-sm leading-relaxed mb-4">{{ $description }}</p>

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-olive-500 font-medium">Mulai dari</p>
                    <p class="font-serif text-2xl text-olive-800">{{ $price }}</p>
                </div>

                <a
                    href="{{ $waHref }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="bg-olive-700 hover:bg-olive-600 text-cream-50 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-olive-600 focus-visible:ring-offset-2"
                    aria-label="Pesan {{ $title }} ({{ $price }}) via WhatsApp"
                >
                    Pesan
                </a>
            </div>
        </div>
    </article>

@endif
