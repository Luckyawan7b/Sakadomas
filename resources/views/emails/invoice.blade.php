<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { background-color: #ffffff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { color: #465a3a; margin: 0; }
        .content { color: #333333; line-height: 1.6; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777777; border-top: 1px solid #e2e8f0; padding-top: 20px; }
        .btn { display: inline-block; background-color: #465a3a; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tanda Terima Transaksi</h1>
            <p>Invoice #{{ $noInvoice }}</p>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $transaksi->akun->nama ?? 'Pelanggan' }}</strong>,</p>
            <p>Terima kasih telah berbelanja di {{ config('smartsaka.name', 'Sakadomas') }}. Transaksi Anda telah dinyatakan selesai.</p>
            <p>Bersama email ini, kami melampirkan salinan resmi invoice untuk pesanan Anda dalam format PDF sebagai bukti pembayaran yang sah.</p>
            <p>Jika ada pertanyaan, jangan ragu untuk menghubungi kami via WhatsApp di nomor {{ config('smartsaka.wa_number') }}.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('smartsaka.name', 'Sakadomas') }}. Semua Hak Dilindungi.
        </div>
    </div>
</body>
</html>
