<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        return view('landing.index', [
            'waNumber' => config('smartsaka.wa_number'),
            'email'    => config('smartsaka.email'),
            'address'  => config('smartsaka.address'),
            'mapsSrc'  => config('smartsaka.maps_embed_src'),
            'socials'  => config('smartsaka.socials'),
            'products' => null,
            'testimonials' => null,
            'faqs' => null,
        ]);
    }
}

