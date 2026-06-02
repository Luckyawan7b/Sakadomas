<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Models\Transaksi;
use App\Mail\SendInvoiceMail;
use Carbon\Carbon;

class ProcessInvoiceEmailJob implements ShouldQueue
{
    use Queueable;

    public $transaksi;

    /**
     * Create a new job instance.
     */
    public function __construct(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Load necessary relationships
            $this->transaksi->loadMissing([
                'akun.desa.kecamatan',
                'jenisTernak',
                'detailTransaksi.ternak.jenis_ternak'
            ]);

            $email = $this->transaksi->akun->email ?? null;

            if (!$email) {
                Log::warning('Invoice email not sent. User has no email.', ['id_transaksi' => $this->transaksi->id_transaksi]);
                return;
            }

            // Generate Invoice No
            $tglFormatted = Carbon::parse($this->transaksi->tgl_transaksi)->format('Ymd');
            $noInvoice = 'INV/' . $tglFormatted . '/TRX-' . $this->transaksi->id_transaksi;

            // Generate PDF
            $pdfFileName = 'invoice_' . $this->transaksi->id_transaksi . '_' . time() . '.pdf';
            $pdfPath = storage_path('app/temp/' . $pdfFileName);
            
            // Ensure temp directory exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            Pdf::view('pages.invoice', ['transaksi' => $this->transaksi, 'noInvoice' => $noInvoice])
                ->format('a4')
                ->save($pdfPath);

            // Send Email
            Mail::to($email)->send(new SendInvoiceMail($this->transaksi, $noInvoice, $pdfPath));

            // Clean up temporary PDF
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            Log::info('Invoice email sent successfully.', ['id_transaksi' => $this->transaksi->id_transaksi, 'email' => $email]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send invoice email.', [
                'id_transaksi' => $this->transaksi->id_transaksi ?? null,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
