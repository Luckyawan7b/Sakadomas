<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ternak;
use App\Models\Transaksi;
use App\Models\Survei;
use App\Models\Keuangan;
use App\Models\Monitor;
use App\Models\Kandang;
use App\Models\Kamar;
use App\Models\JenisTernak;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ========================================
        // 1. KARTU METRIK UTAMA
        // ========================================
        $total_ternak = Ternak::where('status_ternak', '!=', 'mati')->where('status_jual', '!=', 'terjual')->count();
        $ternak_sehat = Ternak::where('status_ternak', 'sehat')->count();
        $ternak_sakit = Ternak::where('status_ternak', 'sakit')->count();

        // Transaksi baru (status = pending)
        $transaksi_baru = Transaksi::where('status', 'pending')->count();

        // Pendapatan bulan ini (dari tabel keuangan, jenis_keuangan = pemasukan)
        $pendapatan_bulan_ini = Keuangan::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->where('jenis_keuangan', 'pemasukan')
            ->sum('nominal');

        // Fallback: jika keuangan belum dipakai, ambil dari transaksi selesai
        if ($pendapatan_bulan_ini == 0) {
            $pendapatan_bulan_ini = Transaksi::where('status', 'selesai')
                ->whereMonth('tgl_transaksi', Carbon::now()->month)
                ->whereYear('tgl_transaksi', Carbon::now()->year)
                ->sum('total_harga');
        }

        // ========================================
        // 2. DISTRIBUSI STATUS PENJUALAN (Donut Chart)
        // ========================================
        $jual_siap = Ternak::where('status_jual', 'siap jual')->count();
        $jual_booking = Ternak::where('status_jual', 'booking')->count();
        $jual_terjual = Ternak::where('status_jual', 'terjual')->count();
        $jual_tidak = Ternak::where('status_jual', 'tidak dijual')->count();

        // ========================================
        // 3. TREN PENDAPATAN 6 BULAN TERAKHIR (Bar Chart)
        // ========================================
        $tren_labels = [];
        $tren_data = [];
        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $tren_labels[] = $namaBulan[$date->month - 1] . ' ' . $date->format('y');

            $pendapatan = Transaksi::where('status', 'selesai')
                ->whereMonth('tgl_transaksi', $date->month)
                ->whereYear('tgl_transaksi', $date->year)
                ->sum('total_harga');

            $tren_data[] = (int) $pendapatan;
        }

        // ========================================
        // 4. MONITORING REMINDER
        // ========================================
        $awalBulanIni = Carbon::now()->startOfMonth()->toDateString();

        // Ternak yang belum dimonitor bulan ini
        $ternakSudahMonitor = Monitor::where('tgl_monitoring', '>=', $awalBulanIni)
            ->distinct('id_ternak')
            ->pluck('id_ternak');

        $ternak_belum_monitor = Ternak::whereNotIn('id_ternak', $ternakSudahMonitor)
            ->where('status_ternak', '!=', 'mati')
            ->where('status_jual', '!=', 'terjual')
            ->with(['kamar.kandang', 'jenis_ternak'])
            ->orderBy('id_ternak', 'asc')
            ->get();

        $total_belum_monitor = $ternak_belum_monitor->count();
        $total_aktif = Ternak::whereNotIn('status_jual', ['terjual'])
            ->where('status_ternak', '!=', 'mati')
            ->count();
        $persen_sudah_monitor = $total_aktif > 0
            ? round((($total_aktif - $total_belum_monitor) / $total_aktif) * 100)
            : 100;

        // ========================================
        // 5. PESANAN BUTUH PENUGASAN
        // ========================================
        $pesanan_butuh_assign = Transaksi::whereIn('status', ['pending', 'diproses'])
            ->withCount('detailTransaksi')
            ->with(['akun', 'jenisTernak'])
            ->orderBy('tgl_transaksi', 'desc')
            ->get()
            ->filter(function ($trx) {
                return $trx->detail_transaksi_count < $trx->total_jumlah;
            })
            ->take(5);

        // ========================================
        // 6. KUNJUNGAN MENDATANG
        // ========================================
        $kunjungan_mendatang = Survei::whereIn('status', ['pending', 'disetujui'])
            ->where('tgl_survei', '>=', Carbon::today()->toDateString())
            ->with('akun')
            ->orderBy('tgl_survei', 'asc')
            ->take(5)
            ->get();

        // ========================================
        // 7. TRANSAKSI TERBARU (5 terakhir)
        // ========================================
        $transaksi_terbaru = Transaksi::with(['akun', 'jenisTernak'])
            ->orderBy('tgl_transaksi', 'desc')
            ->take(5)
            ->get();

        return view('pages.dashboard.ecommerce', compact(
            'total_ternak',
            'ternak_sehat',
            'ternak_sakit',
            'transaksi_baru',
            'pendapatan_bulan_ini',
            'jual_siap',
            'jual_booking',
            'jual_terjual',
            'jual_tidak',
            'tren_labels',
            'tren_data',
            'ternak_belum_monitor',
            'total_belum_monitor',
            'total_aktif',
            'persen_sudah_monitor',
            'pesanan_butuh_assign',
            'kunjungan_mendatang',
            'transaksi_terbaru'
        ))->with('title', 'SMART-SAKA | Dashboard');
    }
}

