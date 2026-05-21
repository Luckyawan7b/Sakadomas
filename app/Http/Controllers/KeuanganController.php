<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keuangan;
use App\Models\Transaksi;
use Carbon\Carbon;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    /**
     * Tampilan utama keuangan admin dengan filter dan agregat
     */
    public function index(Request $request)
    {
        $query = Keuangan::with('transaksi')->orderBy('tanggal', 'desc')->orderBy('id_keuangan', 'desc');

        // Filter berdasarkan Tanggal Awal
        if ($request->filled('tgl_awal')) {
            $query->whereDate('tanggal', '>=', $request->tgl_awal);
        }

        // Filter berdasarkan Tanggal Akhir
        if ($request->filled('tgl_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tgl_akhir);
        }

        // Filter berdasarkan Jenis Keuangan
        if ($request->filled('jenis_keuangan') && $request->jenis_keuangan !== 'semua') {
            $query->where('jenis_keuangan', $request->jenis_keuangan);
        }

        // Pencarian teks pada kolom keterangan
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('ket', 'like', "%{$q}%");
        }

        // Hitung total pemasukan, pengeluaran, dan saldo untuk data yang terfilter
        $totalPemasukan = (clone $query)->where('jenis_keuangan', 'pemasukan')->sum('nominal');
        $totalPengeluaran = (clone $query)->where('jenis_keuangan', 'pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        // Ambil data dengan paginasi
        $data_keuangan = $query->paginate(15);

        return view('pages.keuangan', compact(
            'data_keuangan',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo'
        ));
    }

    /**
     * Menyimpan data pemasukan/pengeluaran manual
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'        => 'required|date|before_or_equal:today',
            'nominal'        => 'required|integer|min:0',
            'jenis_keuangan' => 'required|in:pemasukan,pengeluaran',
            'ket'            => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Keuangan::create([
                    'tanggal'        => $request->tanggal,
                    'nominal'        => $request->nominal,
                    'jenis_keuangan' => $request->jenis_keuangan,
                    'ket'            => $request->ket,
                    'id_transaksi'   => null, // Menandakan input manual
                ]);
            });

            return back()->with('success', 'Catatan keuangan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan catatan keuangan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Mengupdate data pemasukan/pengeluaran manual
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'        => 'required|date|before_or_equal:today',
            'nominal'        => 'required|integer|min:0',
            'jenis_keuangan' => 'required|in:pemasukan,pengeluaran',
            'ket'            => 'required|string|max:255',
        ]);

        $keuangan = Keuangan::findOrFail($id);

        // Proteksi: Record otomatis dari transaksi tidak boleh di-edit secara manual
        if ($keuangan->id_transaksi !== null) {
            return back()->with('error', 'Catatan keuangan otomatis dari transaksi tidak dapat diedit secara manual.');
        }

        try {
            DB::transaction(function () use ($keuangan, $request) {
                $keuangan->update([
                    'tanggal'        => $request->tanggal,
                    'nominal'        => $request->nominal,
                    'jenis_keuangan' => $request->jenis_keuangan,
                    'ket'            => $request->ket,
                ]);
            });

            return back()->with('success', 'Catatan keuangan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui catatan keuangan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Mengunduh rekap keuangan berbentuk PDF secara dinamis sesuai filter
     */
    public function downloadPdf(Request $request)
    {
        $query = Keuangan::with('transaksi')->orderBy('tanggal', 'asc')->orderBy('id_keuangan', 'asc');

        if ($request->filled('tgl_awal')) {
            $query->whereDate('tanggal', '>=', $request->tgl_awal);
        }
        if ($request->filled('tgl_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tgl_akhir);
        }
        if ($request->filled('jenis_keuangan') && $request->jenis_keuangan !== 'semua') {
            $query->where('jenis_keuangan', $request->jenis_keuangan);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('ket', 'like', "%{$q}%");
        }

        $data_keuangan = $query->get();

        $totalPemasukan = $data_keuangan->where('jenis_keuangan', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $data_keuangan->where('jenis_keuangan', 'pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;

        // Memformat deskripsi periode laporan
        $periode = 'Semua Periode';
        if ($tgl_awal && $tgl_akhir) {
            $periode = Carbon::parse($tgl_awal)->translatedFormat('d F Y') . ' - ' . Carbon::parse($tgl_akhir)->translatedFormat('d F Y');
        } elseif ($tgl_awal) {
            $periode = 'Mulai ' . Carbon::parse($tgl_awal)->translatedFormat('d F Y');
        } elseif ($tgl_akhir) {
            $periode = 'Hingga ' . Carbon::parse($tgl_akhir)->translatedFormat('d F Y');
        }

        $filename = 'Rekap-Keuangan-' . str_replace([' ', ','], '-', $periode) . '.pdf';

        return Pdf::view('pages.pdf.rekap-keuangan', compact(
            'data_keuangan',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'periode'
        ))
        ->format('a4')
        ->name($filename);
    }
}
