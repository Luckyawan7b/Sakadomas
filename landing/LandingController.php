<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * =============================================================================
 * LandingController
 * Smart-Saka — Landing page utama
 * =============================================================================
 *
 * Route  : GET /  →  nama: home
 * Config : config/smartsaka.php  ← semua nilai bisnis ada di sini
 * .env   : SMARTSAKA_WA_NUMBER, SMARTSAKA_EMAIL, dll
 *
 * TODO: Uncomment model imports setelah dibuat:
 *   use App\Models\Product;
 *   use App\Models\Testimonial;
 *   use App\Models\Faq;
 * =============================================================================
 */
class LandingController extends Controller
{
    public function index(): View
    {
        return view('landing.index', [

            // ── Config bisnis — dibaca dari config/smartsaka.php ──────────
            'waNumber' => config('smartsaka.wa_number'),
            'email'    => config('smartsaka.email'),
            'address'  => config('smartsaka.address'),
            'mapsSrc'  => config('smartsaka.maps_embed_src'),
            'socials'  => config('smartsaka.socials'),

            // ── PRODUK ───────────────────────────────────────────────────
            // Uncomment setelah model Product dibuat:
            //
            // 'products' => Product::query()
            //     ->where('is_active', true)
            //     ->orderBy('sort_order')
            //     ->get(),
            //
            'products' => null, // Data statis ada di landing/index.blade.php

            // ── TESTIMONI ────────────────────────────────────────────────
            // Uncomment setelah model Testimonial dibuat:
            //
            // 'testimonials' => Testimonial::query()
            //     ->where('is_active', true)
            //     ->orderBy('sort_order')
            //     ->limit(6)
            //     ->get()
            //     ->map(fn ($t) => [
            //         'quote' => $t->quote,
            //         'name'  => $t->customer_name,
            //         'role'  => $t->customer_role,
            //         'image' => asset('images/testimonials/' . $t->image),
            //     ])
            //     ->toArray(),
            //
            'testimonials' => null, // Data default ada di komponen Blade

            // ── FAQ ──────────────────────────────────────────────────────
            // Uncomment setelah model Faq dibuat:
            //
            // 'faqs' => Faq::query()
            //     ->where('is_active', true)
            //     ->orderBy('sort_order')
            //     ->get()
            //     ->map(fn ($f) => [
            //         'question' => $f->question,
            //         'answer'   => $f->answer,
            //     ])
            //     ->toArray(),
            //
            'faqs' => null, // Data default ada di komponen Blade

        ]);
    }
}
