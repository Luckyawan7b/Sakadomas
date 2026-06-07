<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaksi;

class SendInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaksi;
    public $noInvoice;
    public $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct(Transaksi $transaksi, $noInvoice, $pdfPath)
    {
        $this->transaksi = $transaksi;
        $this->noInvoice = $noInvoice;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Lunas - ' . $this->noInvoice,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('Invoice_' . str_replace('/', '_', $this->noInvoice) . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
