<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Ternak;
use Illuminate\Support\Facades\File;

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

    // ================================================================
    // HALAMAN KATALOG — Produk ternak dari value.json
    // ================================================================
    public function katalog(): View
    {
        $jsonPath = public_path('json/value.json');
        $klasifikasiData = [];
        if (File::exists($jsonPath)) {
            $klasifikasiData = json_decode(File::get($jsonPath), true)['ternak_klasifikasi'] ?? [];
        }

        $jenis_ternak = $this->getKatalogProduk($klasifikasiData);

        // Mapping gambar dummy per breed (nanti diganti asset dari images/katalog/)
        $dummyImages = [
            'crosstexel' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBWLRSZuZJcIQAhiYwsQ59y0iac_D_Kyh5eoVHwSIG8pPmhT9BUDGSUqp6Jt12d0H2Oz5BiWnLHHQZ5FOGXd0KUoqyL-UP9Q40tjPtOFDTy2U01IWG--P4pXTYWUhlTDlpWcYWQktpRcamUrNE0hciaz0_WFYi7hlbxNfHW9jc9em_l3DlL4MT1OV4csGGzwmf_wXoUI5Am_F82Cp5IhivPfAMXikqB4j6G-Y7lIyajFmbpjvH__doVCtpQxKzpcxCeIX-nWo_SxQ8',
            'merino'     => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDDfWYMdt4S5Ol1tliPLNS2EhX9BcrtC-aFYSNgPgUInFOd-91myKsES9cB8tOpUKGQJLiISCBjZ7EIoBnRihcwxBni4l6JCqtyvjLWo1P5wZOXFJIgUSbmrml-VZpJEyaLHX2OwiUvAK-NRbVhfsr4RxqMimINX5l0YSlJIL8Yn26h-DExypy9Dbsp-yFCNuUKZvwc5FLiAOw2TyL95YUl3XXQSQvRzN5u98j9epPfqCXD-KFJXmijT0THk8JKED-hu9WTDRjOhV0',
            'etawa'      => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAQbszwPJiqzI46G7N4_9vJIuvrOGzL_nmYoQ_ctHu1V_cexcddjmAp3m3aO5NXBO9hpfITxKcFMz5A59WKDlU3-mPV5_Iyy0daY-0Dp_OTBOYUe5Kcl3TDBVwAp8c4u1xaxTZIT0bfoTVGkXOn1y7qf1JkSKp4SUP3gg7N23GoM751o2WNZTwStq2TLKkrh1zD6SpR3vpEJjamuygWeomHpv0_8UsINpM9sJdA5D2Y-djAs5yq9_qC8SRy-KKFhuMFfGJ7Zp3Xv3Q',
            'default'    => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDIcIVV29uCwOcczKcRKi2h_K1t8P4e7LbLXRQBwS7uIELyGuSGqHqa4B1UjtAdmXFzjHjH4h5gJwPYvuXAF8XMx7Rs1K51YRCqJitHlDpfL3mPH7s2R1ySGjZmsh6zoBm14-W0KmUBq54G9fRYd9Sxcu14fo6Gf4Snu_O0py5qlDQzbavk5_tYA0opLY9IwaExqJPL-BSnOyYrqaa58DlO3tbRubhG2FwuOWb9wvdlFVisbICuA-vX2a2yJI3Oyq6kd3tBUXFHLLk',
        ];

        return view('landing.katalog', [
            'jenis_ternak'   => $jenis_ternak,
            'klasifikasi'    => $klasifikasiData,
            'dummyImages'    => $dummyImages,
            'waNumber'       => config('smartsaka.wa_number'),
            'email'          => config('smartsaka.email'),
            'address'        => config('smartsaka.address'),
            'mapsSrc'        => config('smartsaka.maps_embed_src'),
        ]);
    }

    // ================================================================
    // HALAMAN DETAIL PRODUK
    // ================================================================
    public function detailProduk($id): View
    {
        $ternak = Ternak::with(['jenis_ternak', 'kamar.kandang'])
            ->where('status_jual', 'siap jual')
            ->where('status_ternak', 'sehat')
            ->findOrFail($id);

        // Ambil produk serupa (jenis sama, kecuali dirinya sendiri)
        $produkSerupa = Ternak::with('jenis_ternak')
            ->where('id_jenis_ternak', $ternak->id_jenis_ternak)
            ->where('id_ternak', '!=', $ternak->id_ternak)
            ->where('status_jual', 'siap jual')
            ->where('status_ternak', 'sehat')
            ->limit(3)
            ->get();

        return view('landing.detail-produk', [
            'ternak'       => $ternak,
            'produkSerupa' => $produkSerupa,
            'waNumber'     => config('smartsaka.wa_number'),
        ]);
    }

    // ================================================================
    // HELPER: Parse value.json + Data Ternak → Katalog Produk
    // Logika sama persis dengan TransaksiController@createPesananUser
    // ================================================================
    private function getKatalogProduk(array $klasifikasiData = [])
    {
        $ternak_tersedia = Ternak::with('jenis_ternak')
            ->where('status_jual', 'siap jual')
            ->where('status_ternak', 'sehat')
            ->get();

        $jenis_ternak = $ternak_tersedia->map(function ($item) use ($klasifikasiData) {
            $usia  = $item->usia;
            $berat = $item->berat;
            $breed = $item->jenis_ternak->jenis_ternak ?? '';

            $mapJenis = [
                'crosstexel' => 'Cross Texel',
                'merino'     => 'Merino',
                'etawa'      => 'Etawa (PE)',
            ];
            $searchJenis = $mapJenis[strtolower($breed)] ?? $breed;

            if ($usia <= 5)       { $katUsia = 'Anakan/Bibit'; }
            elseif ($usia <= 11)  { $katUsia = 'Doro/Muda'; }
            else                  { $katUsia = 'Indukan/Dewasa'; }

            $kelasBerat = 'Uncategorized';
            $weightRange = '';
            $ageRange = '';
            foreach ($klasifikasiData as $dataBreed) {
                if ($dataBreed['breed_name'] === $searchJenis) {
                    foreach ($dataBreed['age_categories'] as $ageCat) {
                        if ($ageCat['category_name'] === $katUsia) {
                            $ageRange = $ageCat['age_range'] ?? '';
                            $lastClass = null;
                            foreach ($ageCat['weight_classes'] as $wClass) {
                                $lastClass = $wClass;
                                if ($berat >= $wClass['min_weight'] && $berat <= $wClass['max_weight']) {
                                    $kelasBerat = $wClass['class_name'];
                                    $weightRange = $wClass['weight_range'] ?? '';
                                    break 3;
                                }
                            }
                            if ($kelasBerat === 'Uncategorized' && $lastClass) {
                                if ($berat > $lastClass['max_weight']) {
                                    $kelasBerat = $lastClass['class_name'];
                                    $weightRange = $lastClass['weight_range'] ?? '';
                                } else {
                                    $kelasBerat = $ageCat['weight_classes'][0]['class_name'];
                                    $weightRange = $ageCat['weight_classes'][0]['weight_range'] ?? '';
                                }
                                break 2;
                            }
                        }
                    }
                }
            }

            return [
                'id_jenis'       => $item->id_jenis_ternak,
                'id_ternak'      => $item->id_ternak,
                'nama_produk'    => $breed . ' - ' . $katUsia,
                'breed'          => $breed,
                'kategori_usia'  => $katUsia,
                'kelas_berat'    => $kelasBerat,
                'jenis_kelamin'  => $item->jenis_kelamin,
                'harga'          => $item->harga,
                'berat'          => $berat,
                'usia'           => $usia,
                'weight_range'   => $weightRange,
                'age_range'      => $ageRange,
            ];
        })
        ->filter(fn($item) => $item['kelas_berat'] !== 'Uncategorized')
        ->groupBy(fn($item) => $item['nama_produk'] . $item['kelas_berat'] . $item['jenis_kelamin'] . $item['harga'])
        ->map(function ($group) {
            $first = $group->first();
            return [
                'id_jenis'       => $first['id_jenis'],
                'id_ternak'      => $first['id_ternak'],
                'nama_produk'    => $first['nama_produk'],
                'breed'          => $first['breed'],
                'kategori_usia'  => $first['kategori_usia'],
                'kelas_berat'    => $first['kelas_berat'],
                'jenis_kelamin'  => $first['jenis_kelamin'],
                'harga'          => $first['harga'],
                'weight_range'   => $first['weight_range'],
                'age_range'      => $first['age_range'],
                'stok'           => $group->count(),
            ];
        })
        ->values();

        return $jenis_ternak;
    }
}
