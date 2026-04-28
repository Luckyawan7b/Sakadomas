<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\transaksiModel;
use App\Models\surveiModel;
use App\Models\ternakModel;
use App\Models\keuanganModel;
use Carbon\Carbon;

class CheckTransaksi extends Command
{
    protected $signature = 'app:check-transaksi';
    protected $description = 'Auto-cancel transaksi yang expired dan auto-complete pengiriman 24 jam';

    public function handle()
    {
        $now = Carbon::now();
        $canceled = 0;
        $completed = 0;

        // ============================================================
        // 1. AUTO-CANCEL: Survei selesai + belum bayar 24 jam (transfer)
        // ============================================================
        $transaksiSurveiSelesai = transaksiModel::with(['detailTransaksi', 'survei'])
            ->where('is_survei', true)
            ->where('status', 'pending')
            ->whereNull('bukti_pembayaran')
            ->where(function ($q) {
                $q->whereNull('metode_pembayaran')
                  ->orWhere('metode_pembayaran', 'transfer');
            })
            ->get();

        foreach ($transaksiSurveiSelesai as $trx) {
            // Cek apakah ada survei yang sudah selesai
            $surveiSelesai = $trx->survei->where('status', 'selesai')->sortByDesc('id_survei')->first();
            if (!$surveiSelesai) continue;

            // Cek apakah sudah 24 jam sejak survei selesai (menggunakan tgl_survei sebagai proxy)
            $tglSelesai = Carbon::parse($surveiSelesai->tgl_survei);
            if ($now->diffInHours($tglSelesai) >= 24) {
                // Batalkan transaksi
                foreach ($trx->detailTransaksi as $detail) {
                    ternakModel::where('id_ternak', $detail->id_ternak)
                        ->update(['status_jual' => 'siap jual']);
                }
                $trx->update(['status' => 'batal']);
                $canceled++;
                $this->info("Auto-cancel TRX-{$trx->id_transaksi}: Survei selesai, belum bayar 24 jam.");
            }
        }

        // ============================================================
        // 2. AUTO-CANCEL: Survei batal + tidak re-submit 24 jam + belum bayar
        // ============================================================
        $transaksiSurveiBatal = transaksiModel::with(['detailTransaksi', 'survei'])
            ->where('is_survei', true)
            ->where('status', 'pending')
            ->whereNull('bukti_pembayaran')
            ->get();

        foreach ($transaksiSurveiBatal as $trx) {
            // Cek apakah ada survei aktif (pending / disetujui)
            $surveiAktif = $trx->survei->whereIn('status', ['pending', 'disetujui'])->count();
            if ($surveiAktif > 0) continue;

            // Cek apakah ada survei batal
            $surveiBatalTerakhir = $trx->survei->where('status', 'batal')->sortByDesc('id_survei')->first();
            if (!$surveiBatalTerakhir) continue;

            // Cek 24 jam sejak survei dibatalkan
            $tglBatal = Carbon::parse($surveiBatalTerakhir->tgl_survei);
            if ($now->diffInHours($tglBatal) >= 24) {
                foreach ($trx->detailTransaksi as $detail) {
                    ternakModel::where('id_ternak', $detail->id_ternak)
                        ->update(['status_jual' => 'siap jual']);
                }
                $trx->update(['status' => 'batal']);
                $canceled++;
                $this->info("Auto-cancel TRX-{$trx->id_transaksi}: Survei batal, tidak re-submit 24 jam.");
            }
        }

        // ============================================================
        // 3. AUTO-COMPLETE: Pengiriman sudah 24 jam
        // ============================================================
        $transaksiDikirim = transaksiModel::with('detailTransaksi')
            ->where('status', 'dikirim')
            ->whereNotNull('tgl_dikirim')
            ->get();

        foreach ($transaksiDikirim as $trx) {
            $tglKirim = Carbon::parse($trx->tgl_dikirim);
            if ($now->diffInHours($tglKirim) >= 24) {
                // Selesaikan transaksi
                $trx->update(['status' => 'selesai']);

                foreach ($trx->detailTransaksi as $detail) {
                    ternakModel::where('id_ternak', $detail->id_ternak)->update([
                        'status_jual' => 'terjual',
                        'id_kamar'    => null,
                    ]);
                }

                keuanganModel::create([
                    'ket'            => 'Pemasukan otomatis dari transaksi #TRX-' . $trx->id_transaksi,
                    'tanggal'        => $now->toDateString(),
                    'nominal'        => $trx->total_harga,
                    'jenis_keuangan' => 'pemasukan',
                    'id_transaksi'   => $trx->id_transaksi,
                ]);

                $completed++;
                $this->info("Auto-complete TRX-{$trx->id_transaksi}: Pengiriman sudah 24 jam.");
            }
        }

        $this->info("Selesai. Dibatalkan: {$canceled}, Diselesaikan: {$completed}");
        return Command::SUCCESS;
    }
}
