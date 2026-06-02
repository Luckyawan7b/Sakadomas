<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;
use App\Models\Survei;
use App\Models\Ternak;
use App\Models\Keuangan;
use App\Services\FcmService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CheckTransaksi extends Command
{
    protected $signature = 'app:check-transaksi';
    protected $description = 'Auto-cancel transaksi expired, auto-complete pengiriman, dan reminder COD';

    public function handle()
    {
        $now = Carbon::now();
        $canceled = 0;
        $completed = 0;
        $reminded = 0;

        // ============================================================
        // 1. AUTO-CANCEL: Survei selesai + belum bayar 24 jam (transfer)
        // ============================================================
        $transaksiSurveiSelesai = Transaksi::with(['detailTransaksi', 'survei'])
            ->where('is_survei', true)
            ->where('status', 'pending')
            ->whereNull('bukti_pembayaran')
            ->where(function ($q) {
                $q->whereNull('metode_pembayaran')
                  ->orWhere('metode_pembayaran', 'transfer');
            })
            ->get();

        foreach ($transaksiSurveiSelesai as $trx) {
            $surveiSelesai = $trx->survei->where('status', 'selesai')->sortByDesc('id_survei')->first();
            if (!$surveiSelesai) continue;

            // Menggunakan tgl_survei sebagai acuan (tabel survei tidak punya updated_at)
            $tglSelesai = Carbon::parse($surveiSelesai->tgl_survei);
            if ($now->diffInHours($tglSelesai, false) <= -24) {
                // Kembalikan ternak ke siap jual
                foreach ($trx->detailTransaksi as $detail) {
                    Ternak::where('id_ternak', $detail->id_ternak)
                        ->update(['status_jual' => 'siap jual']);
                }

                // Batalkan survei aktif terkait
                $trx->survei()
                    ->whereIn('status', ['pending', 'disetujui'])
                    ->update([
                        'status'    => 'batal',
                        'ket_admin' => 'Otomatis batal karena transaksi dibatalkan.',
                    ]);

                $trx->update(['status' => 'batal']);
                $canceled++;
                $this->info("Auto-cancel TRX-{$trx->id_transaksi}: Survei selesai, belum bayar 24 jam.");

                // FCM ke user
                $this->notifyUser($trx->id_akun, $trx->id_transaksi, 'Pesanan dibatalkan karena melewati batas waktu pembayaran 24 jam.');
            }
        }

        // ============================================================
        // 2. AUTO-CANCEL: Survei batal + tidak re-submit 24 jam
        // ============================================================
        $transaksiSurveiBatal = Transaksi::with(['detailTransaksi', 'survei'])
            ->where('is_survei', true)
            ->where('status', 'pending')
            ->whereNull('bukti_pembayaran')
            ->get();

        foreach ($transaksiSurveiBatal as $trx) {
            // Skip jika ada survei aktif
            $surveiAktif = $trx->survei->whereIn('status', ['pending', 'disetujui', 'selesai'])->count();
            if ($surveiAktif > 0) continue;

            // Cek survei batal terakhir
            $surveiBatalTerakhir = $trx->survei->where('status', 'batal')->sortByDesc('id_survei')->first();
            if (!$surveiBatalTerakhir) continue;

            $tglBatal = Carbon::parse($surveiBatalTerakhir->tgl_survei);
            if ($now->diffInHours($tglBatal, false) <= -24) {
                foreach ($trx->detailTransaksi as $detail) {
                    Ternak::where('id_ternak', $detail->id_ternak)
                        ->update(['status_jual' => 'siap jual']);
                }
                $trx->update(['status' => 'batal']);
                $canceled++;
                $this->info("Auto-cancel TRX-{$trx->id_transaksi}: Survei batal, tidak re-submit 24 jam.");

                $this->notifyUser($trx->id_akun, $trx->id_transaksi, 'Pesanan dibatalkan karena jadwal survei tidak diajukan ulang dalam 24 jam.');
            }
        }

        // ============================================================
        // 3. AUTO-COMPLETE: Pengiriman sudah 24 jam
        // ============================================================
        $transaksiDikirim = Transaksi::with('detailTransaksi')
            ->where('status', 'dikirim')
            ->whereNotNull('tgl_dikirim')
            ->get();

        foreach ($transaksiDikirim as $trx) {
            $tglKirim = Carbon::parse($trx->tgl_dikirim);
            if ($now->diffInHours($tglKirim, false) <= -24) {
                $trx->update(['status' => 'selesai']);

                foreach ($trx->detailTransaksi as $detail) {
                    Ternak::where('id_ternak', $detail->id_ternak)->update([
                        'status_jual' => 'terjual',
                        'id_kamar'    => null,
                    ]);
                }

                // FIX BUG: Nominal harus termasuk ongkir
                Keuangan::create([
                    'ket'            => 'Pemasukan otomatis dari transaksi #TRX-' . $trx->id_transaksi,
                    'tanggal'        => $now->toDateString(),
                    'nominal'        => $trx->total_harga + $trx->ongkir,
                    'jenis_keuangan' => 'pemasukan',
                    'id_transaksi'   => $trx->id_transaksi,
                ]);

                $completed++;
                $this->info("Auto-complete TRX-{$trx->id_transaksi}: Pengiriman sudah 24 jam.");
            }
        }

        // ============================================================
        // 4. AUTO-CANCEL: Admin belum assign ternak dalam 24 jam
        //    (hanya pesanan non-survei)
        // ============================================================
        $transaksiTidakDirespons = Transaksi::with('detailTransaksi')
            ->where('is_survei', false)
            ->where('status', 'pending')
            ->whereNull('bukti_pembayaran')
            ->where('tgl_transaksi', '<=', $now->copy()->subHours(24))
            ->get()
            ->filter(fn($trx) => $trx->detailTransaksi->count() === 0);

        foreach ($transaksiTidakDirespons as $trx) {
            $trx->update(['status' => 'batal']);
            $canceled++;
            $this->info("Auto-cancel TRX-{$trx->id_transaksi}: Admin belum assign ternak dalam 24 jam.");

            // FCM ke user
            $this->notifyUser($trx->id_akun, $trx->id_transaksi, 'Pesanan dibatalkan karena tidak direspons dalam 24 jam. Silakan buat pesanan baru.');

            // FCM ke admin sebagai reminder
            $this->notifyAdmin("⚠️ Pesanan Terlewat", "Pesanan #TRX-{$trx->id_transaksi} otomatis batal karena tidak di-assign dalam 24 jam.");
        }

        // ============================================================
        // 5. AUTO-CANCEL: Batas survei kedaluwarsa (lewat 7 hari)
        // ============================================================
        $transaksiSurveiExpired = Transaksi::with(['detailTransaksi', 'survei'])
            ->where('is_survei', true)
            ->where('status', 'pending')
            ->whereNotNull('batas_survei')
            ->where('batas_survei', '<', $now->toDateString())
            ->get();

        foreach ($transaksiSurveiExpired as $trx) {
            // Skip jika ada survei yang selesai (sudah di-handle skenario #1)
            $adaSurveiSelesai = $trx->survei->where('status', 'selesai')->count() > 0;
            if ($adaSurveiSelesai) continue;

            // Skip jika masih ada survei aktif
            $adaSurveiAktif = $trx->survei->whereIn('status', ['pending', 'disetujui'])->count() > 0;
            if ($adaSurveiAktif) continue;

            foreach ($trx->detailTransaksi as $detail) {
                Ternak::where('id_ternak', $detail->id_ternak)
                    ->update(['status_jual' => 'siap jual']);
            }

            // Batalkan survei yang masih tersisa
            $trx->survei()
                ->whereIn('status', ['pending', 'disetujui'])
                ->update([
                    'status'    => 'batal',
                    'ket_admin' => 'Otomatis batal karena melewati batas waktu survei.',
                ]);

            $trx->update(['status' => 'batal']);
            $canceled++;
            $this->info("Auto-cancel TRX-{$trx->id_transaksi}: Batas survei kedaluwarsa.");

            $this->notifyUser($trx->id_akun, $trx->id_transaksi, 'Pesanan dibatalkan karena batas waktu survei telah habis.');
        }

        // ============================================================
        // 6. COD: Reminder hari ke-3 + Auto-cancel hari ke-5
        // ============================================================

        // 6a. REMINDER: COD sudah 3 hari (window 72-73 jam agar tidak spam)
        $codReminder = Transaksi::where('status', 'pending')
            ->where('metode_pembayaran', 'cash')
            ->where('tgl_transaksi', '<=', $now->copy()->subHours(72))
            ->where('tgl_transaksi', '>', $now->copy()->subHours(73))
            ->get();

        foreach ($codReminder as $trx) {
            $reminded++;
            $this->info("COD Reminder TRX-{$trx->id_transaksi}: Sudah 3 hari pending.");
            $this->notifyAdmin("⏰ COD Menunggu", "Pesanan COD #TRX-{$trx->id_transaksi} sudah 3 hari pending. Segera proses atau hubungi pembeli!");
        }

        // 6b. AUTO-CANCEL: COD pending lebih dari 5 hari
        $codExpired = Transaksi::with('detailTransaksi')
            ->where('status', 'pending')
            ->where('metode_pembayaran', 'cash')
            ->where('tgl_transaksi', '<=', $now->copy()->subHours(120))
            ->get();

        foreach ($codExpired as $trx) {
            foreach ($trx->detailTransaksi as $detail) {
                Ternak::where('id_ternak', $detail->id_ternak)
                    ->update(['status_jual' => 'siap jual']);
            }
            $trx->update(['status' => 'batal']);
            $canceled++;
            $this->info("Auto-cancel TRX-{$trx->id_transaksi}: COD pending lebih dari 5 hari.");

            $this->notifyUser($trx->id_akun, $trx->id_transaksi, 'Pesanan COD dibatalkan karena tidak diproses dalam 5 hari.');
            $this->notifyAdmin("🚫 COD Auto-Cancel", "Pesanan COD #TRX-{$trx->id_transaksi} otomatis batal (5 hari tidak diproses).");
        }

        // ============================================================
        // SELESAI
        // ============================================================
        $this->info("Selesai. Dibatalkan: {$canceled}, Diselesaikan: {$completed}, Reminder: {$reminded}");
        return Command::SUCCESS;
    }

    /**
     * Kirim FCM notification ke user
     */
    private function notifyUser(int $idAkun, int $idTrx, string $pesan): void
    {
        try {
            $fcm = new FcmService();
            $fcm->sendToUser($idAkun, '⏰ Pesanan Dibatalkan Otomatis', "Pesanan #TRX-{$idTrx}: {$pesan}");
        } catch (\Throwable $e) {
            Log::error("FCM auto-cancel user failed TRX-{$idTrx}: " . $e->getMessage());
        }
    }

    /**
     * Kirim FCM notification ke semua admin
     */
    private function notifyAdmin(string $title, string $body): void
    {
        try {
            $fcm = new FcmService();
            $fcm->sendToAllAdmins($title, $body);
        } catch (\Throwable $e) {
            Log::error("FCM admin notification failed: " . $e->getMessage());
        }
    }
}
