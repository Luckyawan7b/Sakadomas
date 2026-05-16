<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\Ternak;
use App\Models\JenisTernak;
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
        $jsonPath = public_path('json/value.json');
        $klasifikasiData = [];
        if (File::exists($jsonPath)) {
            $klasifikasiData = json_decode(File::get($jsonPath), true)['ternak_klasifikasi'] ?? [];
        }

        $allKatalog = $this->getKatalogProduk($klasifikasiData);

        // Koleksi Super (Unggulan) - Filter yang kelas_berat == 'Super'
        $featuredProducts = $allKatalog->where('kelas_berat', 'Super')->take(5);

        // Fallback jika tidak ada Super, ambil 5 terbaru
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = $allKatalog->take(5);
        }

        return view('landing.index', [
            'featuredProducts' => $featuredProducts,

            // ── Config bisnis — dibaca dari config/smartsaka.php ──────────
            'waNumber' => config('smartsaka.wa_number'),
            'email'    => config('smartsaka.email'),
            'address'  => config('smartsaka.address'),
            'mapsSrc'  => config('smartsaka.maps_embed_src'),
            'socials'  => config('smartsaka.socials'),

            'testimonials' => null,
            'faqs' => null,
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

        return view('landing.katalog', [
            'jenis_ternak'   => $jenis_ternak,
            'klasifikasi'    => $klasifikasiData,
            'waNumber'       => config('smartsaka.wa_number'),
            'email'          => config('smartsaka.email'),
            'address'        => config('smartsaka.address'),
            'mapsSrc'        => config('smartsaka.maps_embed_src'),
        ]);
    }

    private function getDummyImages()
    {
        return [
            'crosstexel' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBWLRSZuZJcIQAhiYwsQ59y0iac_D_Kyh5eoVHwSIG8pPmhT9BUDGSUqp6Jt12d0H2Oz5BiWnLHHQZ5FOGXd0KUoqyL-UP9Q40tjPtOFDTy2U01IWG--P4pXTYWUhlTDlpWcYWQktpRcamUrNE0hciaz0_WFYi7hlbxNfHW9jc9em_l3DlL4MT1OV4csGGzwmf_wXoUI5Am_F82Cp5IhivPfAMXikqB4j6G-Y7lIyajFmbpjvH__doVCtpQxKzpcxCeIX-nWo_SxQ8',
            'merino'     => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDDfWYMdt4S5Ol1tliPLNS2EhX9BcrtC-aFYSNgPgUInFOd-91myKsES9cB8tOpUKGQJLiISCBjZ7EIoBnRihcwxBni4l6JCqtyvjLWo1P5wZOXFJIgUSbmrml-VZpJEyaLHX2OwiUvAK-NRbVhfsr4RxqMimINX5l0YSlJIL8Yn26h-DExypy9Dbsp-yFCNuUKZvwc5FLiAOw2TyL95YUl3XXQSQvRzN5u98j9epPfqCXD-KFJXmijT0THk8JKED-hu9WTDRjOhV0',
            'etawa'      => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAQbszwPJiqzI46G7N4_9vJIuvrOGzL_nmYoQ_ctHu1V_cexcddjmAp3m3aO5NXBO9hpfITxKcFMz5A59WKDlU3-mPV5_Iyy0daY-0Dp_OTBOYUe5Kcl3TDBVwAp8c4u1xaxTZIT0bfoTVGkXOn1y7qf1JkSKp4SUP3gg7N23GoM751o2WNZTwStq2TLKkrh1zD6SpR3vpEJjamuygWeomHpv0_8UsINpM9sJdA5D2Y-djAs5yq9_qC8SRy-KKFhuMFfGJ7Zp3Xv3Q',
            'default'    => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDIcIVV29uCwOcczKcRKi2h_K1t8P4e7LbLXRQBwS7uIELyGuSGqHqa4B1UjtAdmXFzjHjH4h5gJwPYvuXAF8XMx7Rs1K51YRCqJitHlDpfL3mPH7s2R1ySGjZmsh6zoBm14-W0KmUBq54G9fRYd9Sxcu14fo6Gf4Snu_O0py5qlDQzbavk5_tYA0opLY9IwaExqJPL-BSnOyYrqaa58DlO3tbRubhG2FwuOWb9wvdlFVisbICuA-vX2a2yJI3Oyq6kd3tBUXFHLLk',
        ];
    }


    // ================================================================
    // HALAMAN DETAIL PRODUK (Slug-Based, Kategori)
    // ================================================================
    public function detailProduk($slug): View
    {

        $parsed = self::parseSlug($slug);
        if (!$parsed) {
            abort(404);
        }

        $jsonPath = public_path('json/value.json');
        $klasifikasiData = [];
        if (File::exists($jsonPath)) {
            $klasifikasiData = json_decode(File::get($jsonPath), true)['ternak_klasifikasi'] ?? [];
        }
        $allKatalog = collect($this->getKatalogProduk($klasifikasiData));

        $produk = null;
        $mapJenis = [
            'cross texel' => 'Cross Texel',
            'merino'      => 'Merino',
            'etawa'       => 'Etawa (PE)',
        ];
        $searchBreed = $mapJenis[strtolower($parsed['breed'])] ?? $parsed['breed'];

        foreach ($klasifikasiData as $dataBreed) {
            if ($dataBreed['breed_name'] === $searchBreed) {
                foreach ($dataBreed['age_categories'] as $ageCat) {
                    if (Str::slug($ageCat['category_name']) === Str::slug($parsed['kategori_usia'])) {
                        foreach ($ageCat['weight_classes'] as $wClass) {
                            if (Str::slug($wClass['class_name']) === Str::slug($parsed['kelas_berat'])) {
                                // Ambil harga dari JSON
                                $harga = $wClass['prices'][$parsed['jenis_kelamin']] ?? 0;

                                // Cari id_jenis_ternak dari database
                                $mapBreedDb = [
                                    'Cross Texel' => 'crosstexel',
                                    'Merino'      => 'merino',
                                    'Etawa (PE)'  => 'etawa',
                                ];
                                $breedDb = $mapBreedDb[$searchBreed] ?? strtolower($searchBreed);
                                $jenisTernak = JenisTernak::where('jenis_ternak', 'LIKE', "%{$breedDb}%")->first();

                                if (!$jenisTernak) {
                                    abort(404);
                                }

                                $katalogItem = $allKatalog->firstWhere('slug', $slug);
                                $stok = $katalogItem ? $katalogItem['stok'] : 0;

                                $produk = [
                                    'breed'          => $searchBreed,
                                    'breed_db'       => $jenisTernak->jenis_ternak,
                                    'kategori_usia'  => $ageCat['category_name'],
                                    'kelas_berat'    => $wClass['class_name'],
                                    'jenis_kelamin'  => $parsed['jenis_kelamin'],
                                    'harga'          => $harga,
                                    'weight_range'   => $wClass['weight_range'] ?? '',
                                    'age_range'      => $ageCat['age_range'] ?? '',
                                    'stok'           => $stok,
                                    'id_jenis'       => $jenisTernak->id_jenis_ternak,
                                    'slug'           => $slug,
                                ];
                                break 3;
                            }
                        }
                    }
                }
            }
        }

        if (!$produk) {
            abort(404);
        }

        // 4. Produk Serupa: tipe lain dari katalog (breed sama, kelas/usia berbeda)
        $produkSerupa = $allKatalog
            ->filter(fn($item) => $item['slug'] !== $slug) // Bukan diri sendiri
            ->sortByDesc(fn($item) => $item['breed'] === $produk['breed_db'] ? 1 : 0) // Prioritas breed sama
            ->take(3)
            ->values();

        return view('landing.detail-produk', [
            'produk'       => $produk,
            'produkSerupa' => $produkSerupa,
            'dummyImages'  => $this->getDummyImages(),
            'waNumber'     => config('smartsaka.wa_number'),
        ]);
    }

    // ================================================================
    // HELPER: Generate Slug dari Data Produk
    // ================================================================
    public static function generateSlug(array $produk): string
    {
        // Normalize DB breed names to display names for consistent slugs
        $breedMap = [
            'crosstexel' => 'Cross Texel',
            'merino'     => 'Merino',
            'etawa'      => 'Etawa PE',
        ];
        $breed = $breedMap[strtolower($produk['breed'])] ?? $produk['breed'];

        return Str::slug(
            $breed . ' ' .
            $produk['kategori_usia'] . ' ' .
            $produk['kelas_berat'] . ' ' .
            $produk['jenis_kelamin']
        );
    }

    // ================================================================
    // HELPER: Parse Slug Kembali ke Komponen
    // ================================================================
    public static function parseSlug(string $slug): ?array
    {
        // Daftar breed yang dikenali
        $knownBreeds = [
            'cross-texel' => 'Cross Texel',
            'merino'      => 'Merino',
            'etawa-pe'    => 'Etawa (PE)',
        ];

        // Daftar kategori usia yang dikenali (Str::slug removes / entirely)
        $knownUsia = [
            'anakanbibit'     => 'Anakan/Bibit',
            'doromuda'        => 'Doro/Muda',
            'indukandewasa'   => 'Indukan/Dewasa',
        ];

        // Daftar kelas berat
        $knownKelas = ['standard', 'medium', 'super'];

        // Daftar jenis kelamin
        $knownKelamin = ['jantan', 'betina'];

        // Coba cocokkan breed dari awal slug
        $matchedBreed = null;
        $remaining = $slug;
        foreach ($knownBreeds as $slugBreed => $namaBreed) {
            if (str_starts_with($slug, $slugBreed . '-')) {
                $matchedBreed = $namaBreed;
                $remaining = substr($slug, strlen($slugBreed) + 1);
                break;
            }
        }
        if (!$matchedBreed) return null;

        // Coba cocokkan kategori usia
        $matchedUsia = null;
        foreach ($knownUsia as $slugUsia => $namaUsia) {
            if (str_starts_with($remaining, $slugUsia . '-')) {
                $matchedUsia = $namaUsia;
                $remaining = substr($remaining, strlen($slugUsia) + 1);
                break;
            }
        }
        if (!$matchedUsia) return null;

        // Pisahkan sisa: kelas-kelamin
        $parts = explode('-', $remaining);
        if (count($parts) < 2) return null;

        $kelas = $parts[0];
        $kelamin = $parts[1];

        if (!in_array($kelas, $knownKelas)) return null;
        if (!in_array($kelamin, $knownKelamin)) return null;

        return [
            'breed'         => $matchedBreed,
            'kategori_usia' => $matchedUsia,
            'kelas_berat'   => ucfirst($kelas),
            'jenis_kelamin' => ucfirst($kelamin),
        ];
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
            $data = [
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
            $data['slug'] = self::generateSlug($data);
            return $data;
        })
        ->values();

        return $jenis_ternak;
    }
}
