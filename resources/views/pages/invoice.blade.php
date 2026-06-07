<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $noInvoice }} — {{ config('smartsaka.name', 'Smart-Saka') }}</title>
    <link rel="icon" href="https://i.postimg.cc/L51FGms2/Logo_Sakadomas.ico.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; color: #1e293b; line-height: 1.6; }

        /* ── Action Bar (hidden on print) ─────────────────────────── */
        .action-bar {
            position: sticky; top: 0; z-index: 50;
            background: linear-gradient(135deg, #465a3a 0%, #5b7a42 100%);
            padding: 12px 24px; display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 2px 12px rgba(0,0,0,.15);
        }
        .action-bar .left { display: flex; align-items: center; gap: 12px; }
        .action-bar .title { color: #fff; font-size: 15px; font-weight: 600; }
        .action-bar .subtitle { color: rgba(255,255,255,.7); font-size: 12px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; text-decoration: none; transition: all .2s; }
        .btn-back { background: rgba(255,255,255,.15); color: #fff; }
        .btn-back:hover { background: rgba(255,255,255,.25); }
        .btn-print { background: #fff; color: #465a3a; }
        .btn-print:hover { background: #e8f5e9; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.1); }
        .action-bar .actions { display: flex; gap: 8px; }

        /* ── Invoice Container ───────────────────────────────────── */
        .invoice-wrap { max-width: 820px; margin: 32px auto; padding: 0 16px; }
        .invoice {
            background: #fff; border-radius: 16px; overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,.06), 0 1px 4px rgba(0,0,0,.04);
        }

        /* ── Header ──────────────────────────────────────────────── */
        .invoice-header {
            background: linear-gradient(135deg, #465a3a 0%, #5b7a42 60%, #7da85c 100%);
            color: #fff; padding: 32px; display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;
        }
        .brand { display: flex; align-items: center; gap: 14px; }
        .brand img { width: 52px; height: 52px; border-radius: 12px; background: #fff; padding: 4px; }
        .brand-name { font-size: 22px; font-weight: 800; letter-spacing: -.3px; }
        .brand-tagline { font-size: 11px; color: rgba(255,255,255,.7); margin-top: 2px; }
        .invoice-meta { text-align: right; }
        .invoice-label { font-size: 28px; font-weight: 800; letter-spacing: 2px; opacity: .3; }
        .invoice-no { font-size: 13px; font-weight: 600; margin-top: 4px; background: rgba(255,255,255,.15); padding: 4px 12px; border-radius: 6px; display: inline-block; }
        .invoice-date { font-size: 12px; color: rgba(255,255,255,.7); margin-top: 6px; }

        /* ── Status Badge ────────────────────────────────────────── */
        .status-strip { padding: 0 32px; }
        .status-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 16px; border-radius: 0 0 10px 10px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
        }
        .status-lunas { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fef9c3; color: #a16207; }
        .status-batal { background: #fee2e2; color: #dc2626; }
        .status-proses { background: #dbeafe; color: #1d4ed8; }

        /* ── Body ────────────────────────────────────────────────── */
        .invoice-body { padding: 28px 32px 32px; }

        /* Info Grid */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }
        .info-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; }
        .info-card-title { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #94a3b8; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
        .info-card-title svg { width: 14px; height: 14px; }
        .info-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 13px; }
        .info-label { color: #64748b; }
        .info-value { font-weight: 600; color: #1e293b; text-align: right; text-transform: capitalize; }
        .capitalize { text-transform: capitalize; }

        /* ── Table ────────────────────────────────────────────────── */
        .table-section { margin-bottom: 24px; }
        .table-title { font-size: 14px; font-weight: 700; color: #334155; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .table-title svg { width: 18px; height: 18px; color: #5b7a42; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: #f1f5f9; padding: 10px 14px; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .5px; color: #64748b;
            border-bottom: 2px solid #e2e8f0; text-align: left;
        }
        thead th:last-child, thead th:nth-child(4), thead th:nth-child(5) { text-align: right; }
        tbody td { padding: 12px 14px; font-size: 13px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        tbody td:last-child, tbody td:nth-child(4), tbody td:nth-child(5) { text-align: right; }
        .tag-id { display: inline-flex; align-items: center; gap: 4px; background: #ede9fe; color: #6d28d9; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; }
        .tag-jk { font-size: 11px; padding: 2px 8px; border-radius: 6px; font-weight: 600; }
        .tag-jantan { background: #dbeafe; color: #1d4ed8; }
        .tag-betina { background: #fce7f3; color: #be185d; }
        tbody tr:hover { background: #f8fafc; }
        .empty-row td { text-align: center !important; color: #94a3b8; font-style: italic; padding: 20px; }

        /* ── Summary ──────────────────────────────────────────────── */
        .summary-section { display: flex; justify-content: flex-end; }
        .summary-box { width: 320px; }
        .summary-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 13px; }
        .summary-row .label { color: #64748b; }
        .summary-row .value { font-weight: 600; }
        .summary-divider { border-top: 2px dashed #e2e8f0; margin: 4px 0; }
        .summary-total { background: linear-gradient(135deg, #465a3a, #5b7a42); color: #fff; padding: 14px 16px; border-radius: 10px; margin-top: 8px; }
        .summary-total .label { font-size: 13px; font-weight: 600; color: #fff !important; }
        .summary-total .value { font-size: 20px; font-weight: 800; color: #fff !important; }

        /* ── Footer ──────────────────────────────────────────────── */
        .invoice-footer { padding: 0 32px 32px; }
        .notes-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 14px 16px; margin-bottom: 8px; page-break-inside: avoid; }
        .notes-box h4 { font-size: 12px; font-weight: 700; color: #92400e; margin-bottom: 6px; }
        .notes-box p { font-size: 11px; color: #78716c; line-height: 1.6; }

        .print-timestamp { text-align: center; margin-top: 16px; font-size: 10px; color: #94a3b8; page-break-inside: avoid; }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 640px) {
            .invoice-header { flex-direction: column; padding: 20px; }
            .invoice-meta { text-align: left; }
            .invoice-label { font-size: 20px; }
            .info-grid { grid-template-columns: 1fr; gap: 12px; }
            .invoice-body, .invoice-footer { padding-left: 16px; padding-right: 16px; }
            .status-strip { padding: 0 16px; }
            .summary-box { width: 100%; }
            .action-bar { flex-direction: column; gap: 10px; align-items: stretch; }
            .action-bar .actions { justify-content: flex-end; }
            table { font-size: 11px; }
            thead th, tbody td { padding: 8px 8px; }
        }

        /* ── Print Styles ────────────────────────────────────────── */
        @media print {
            @page { size: A4; margin: 10mm; }
            body { background: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; font-size: 0.95em; }
            .action-bar { display: none !important; }
            .invoice-wrap { margin: 0; padding: 0; max-width: 100%; }
            .invoice { box-shadow: none; border-radius: 0; }
            .invoice-header { border-radius: 0; padding: 20px 24px; gap: 12px; }
            .invoice-body { padding: 16px 24px 20px; }
            .invoice-footer { padding: 0 24px 20px; }
            .info-grid { margin-bottom: 16px; gap: 16px; }
            .table-section { margin-bottom: 16px; page-break-inside: avoid; }
            thead th, tbody td { padding: 8px 10px; font-size: 12px; }
            .summary-total { padding: 10px 12px; margin-top: 4px; }
            .print-timestamp { display: block !important; }
            tbody tr:hover { background: transparent !important; }
        }
    </style>
</head>
<body>

    {{-- ═══ ACTION BAR (hidden on print) ═══ --}}
    <div class="action-bar">
        <div class="left">
            <div>
                <div class="title">Invoice {{ $noInvoice }}</div>
                <div class="subtitle">{{ config('smartsaka.name', 'Smart-Saka') }} — Sistem Manajemen Peternakan</div>
            </div>
        </div>
        <div class="actions">
            <a href="{{ url()->previous() }}" class="btn btn-back">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
            <button onclick="window.print()" class="btn btn-print">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    {{-- ═══ INVOICE DOCUMENT ═══ --}}
    <div class="invoice-wrap">
        <div class="invoice">

            {{-- Header --}}
            <div class="invoice-header">
                <div class="brand">
                    @php
                        $logoPath = public_path('images/logo/Logo_Sakadomas.png');
                        $logoBase64 = '';
                        if (file_exists($logoPath)) {
                            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
                        } else {
                            $logoBase64 = 'https://i.postimg.cc/yNV69CrK/Logo_Sakadomas.png';
                        }
                    @endphp
                    <img src="{{ $logoBase64 }}" alt="Logo Sakadomas">
                    <div>
                        <div class="brand-name">SAKADOMAS</div>
                        <div class="brand-tagline">Peternakan Domba Modern — {{ config('smartsaka.address.city', 'Jember') }}, {{ config('smartsaka.address.province', 'Jawa Timur') }}</div>
                    </div>
                </div>
                <div class="invoice-meta">
                    <div class="invoice-label">INVOICE</div>
                    <div class="invoice-no">{{ $noInvoice }}</div>
                    <div class="invoice-date">{{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->translatedFormat('d F Y, H:i') }} WIB</div>
                </div>
            </div>

            {{-- Status Badge --}}
            <div class="status-strip">
                @php
                    $st = strtolower($transaksi->status);
                    $statusMap = [
                        'selesai'  => ['class' => 'status-lunas',  'text' => '✓ LUNAS — Transaksi Selesai'],
                        'diproses' => ['class' => 'status-proses', 'text' => '⏳ DIPROSES — Menunggu Pengiriman'],
                        'dikirim'  => ['class' => 'status-proses', 'text' => '🚚 DIKIRIM — Dalam Perjalanan'],
                        'batal'    => ['class' => 'status-batal',  'text' => '✕ BATAL — Transaksi Dibatalkan'],
                        'pending'  => ['class' => 'status-pending','text' => '⏳ PENDING — Menunggu Pembayaran'],
                    ];
                    $badge = $statusMap[$st] ?? $statusMap['pending'];
                @endphp
                <span class="status-badge {{ $badge['class'] }}">{{ $badge['text'] }}</span>
            </div>

            {{-- Body --}}
            <div class="invoice-body">
                {{-- Info Grid --}}
                <div class="info-grid">
                    {{-- Pelanggan --}}
                    <div class="info-card">
                        <div class="info-card-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Data Pelanggan
                        </div>
                        <div class="info-row"><span class="info-label">Nama</span><span class="info-value">{{ $transaksi->akun->nama ?? 'Guest' }}</span></div>
                        <div class="info-row"><span class="info-label">No. HP</span><span class="info-value">{{ $transaksi->akun->no_hp ?? '-' }}</span></div>
                        <div class="info-row"><span class="info-label">Alamat</span><span class="info-value">{{ $transaksi->akun->alamat ?? '-' }}</span></div>
                        <div class="info-row">
                            <span class="info-label">Desa / Kecamatan</span>
                            <span class="info-value">
                                {{ $transaksi->akun->desa->nama_desa ?? '-' }}, {{ $transaksi->akun->desa->kecamatan->nama_kecamatan ?? '-' }}
                            </span>
                        </div>
                    </div>

                    {{-- Detail Pembayaran --}}
                    <div class="info-card">
                        <div class="info-card-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Detail Pembayaran & Pengiriman
                        </div>
                        <div class="info-row"><span class="info-label">Metode Bayar</span><span class="info-value">{{ ucfirst($transaksi->metode_pembayaran ?? '-') }}</span></div>
                        <div class="info-row">
                            <span class="info-label">Pengiriman</span>
                            <span class="info-value">{{ $transaksi->metode_pengiriman === 'ambil_sendiri' ? '🏠 Ambil Sendiri' : '🚚 Dikirim' }}</span>
                        </div>
                        @if($transaksi->metode_pengiriman === 'dikirim')
                            <div class="info-row"><span class="info-label">Kurir</span><span class="info-value">{{ $transaksi->kurir ?? '-' }}</span></div>
                            <div class="info-row"><span class="info-label">No. HP Kurir</span><span class="info-value">{{ $transaksi->no_kurir ?? '-' }}</span></div>
                        @endif
                        <div class="info-row"><span class="info-label">Pesanan</span><span class="info-value">{{ $transaksi->jenisTernak->jenis_ternak ?? '-' }} — {{ ucfirst($transaksi->jenis_kelamin_pesanan) }}</span></div>
                    </div>
                </div>

                {{-- Items Table --}}
                <div class="table-section">
                    <div class="table-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Rincian Ternak yang Di-assign
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID Ternak</th>
                                <th>Jenis / Kelamin</th>
                                <th>Berat</th>
                                <th>Harga Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksi->detailTransaksi as $i => $detail)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td><span class="tag-id">#ID-{{ $detail->id_ternak }}</span></td>
                                    <td class="capitalize">
                                        {{ $detail->ternak->jenis_ternak->jenis_ternak ?? '-' }}
                                        <span class="tag-jk {{ ($detail->ternak->jenis_kelamin ?? '') === 'jantan' ? 'tag-jantan' : 'tag-betina' }}">
                                            {{ ucfirst($detail->ternak->jenis_kelamin ?? '-') }}
                                        </span>
                                    </td>
                                    <td>{{ $detail->ternak->berat ?? '-' }} kg</td>
                                    <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="5">Belum ada ternak yang di-assign ke transaksi ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Summary --}}
                <div class="summary-section">
                    <div class="summary-box">
                        <div class="summary-row">
                            <span class="label">Subtotal ({{ $transaksi->total_jumlah }} ekor)</span>
                            <span class="value">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Ongkos Kirim</span>
                            <span class="value">
                                @if($transaksi->metode_pengiriman === 'ambil_sendiri')
                                    Gratis
                                @else
                                    Rp {{ number_format($transaksi->ongkir, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-total">
                            <div class="summary-row" style="padding:0;">
                                <span class="label">Grand Total</span>
                                <span class="value">Rp {{ number_format($transaksi->total_harga + $transaksi->ongkir, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="invoice-footer">
                <div class="notes-box">
                    <h4>📋 Catatan & Ketentuan</h4>
                    <p>
                        1. Invoice ini merupakan bukti sah transaksi pembelian ternak di Peternakan Sakadomas.<br>
                        2. Ternak yang sudah dibeli dan diterima tidak dapat dikembalikan kecuali ada cacat tersembunyi yang dibuktikan dalam 24 jam.<br>
                        3. Untuk informasi lebih lanjut, hubungi WhatsApp: +{{ config('smartsaka.wa_number', '62895700326271') }}.
                    </p>
                </div>

                <div class="print-timestamp">
                    Dicetak pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i:s') }} WIB — Dokumen ini sah tanpa materai.
                </div>
            </div>

        </div>
    </div>

</body>
</html>
