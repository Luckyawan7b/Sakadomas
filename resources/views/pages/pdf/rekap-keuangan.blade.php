<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekap Keuangan — {{ $periode }}</title>
    <link rel="icon" href="https://i.postimg.cc/L51FGms2/Logo_Sakadomas.ico.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #ffffff; color: #1e293b; line-height: 1.5; font-size: 13px; }

        .container { max-width: 820px; margin: 0 auto; padding: 20px; }

        /* ── Kop Surat ───────────────────────────────────────────── */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #465a3a;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .brand-section { display: flex; align-items: center; gap: 16px; }
        .brand-logo { width: 64px; height: 64px; border-radius: 12px; }
        .brand-info h1 { font-size: 24px; font-weight: 800; color: #465a3a; letter-spacing: -0.5px; }
        .brand-info p { font-size: 11px; color: #64748b; margin-top: 2px; }
        .doc-meta { text-align: right; }
        .doc-title { font-size: 18px; font-weight: 800; color: #334155; letter-spacing: 0.5px; }
        .doc-periode { font-size: 12px; font-weight: 600; color: #5b7a42; margin-top: 4px; }

        /* ── Summary Stats ───────────────────────────────────────── */
        .summary-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 28px; }
        .stat-card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 16px; position: relative; overflow: hidden; }
        .stat-card.pemasukan { border-left: 4px solid #10b981; background: #f0fdf4; }
        .stat-card.pengeluaran { border-left: 4px solid #ef4444; background: #fef2f2; }
        .stat-card.saldo { border-left: 4px solid #3b82f6; background: #eff6ff; }
        .stat-title { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; }
        .stat-value { font-size: 16px; font-weight: 800; margin-top: 6px; }
        .stat-value.pemasukan-text { color: #065f46; }
        .stat-value.pengeluaran-text { color: #991b1b; }
        .stat-value.saldo-text-positive { color: #1e40af; }
        .stat-value.saldo-text-negative { color: #9d174d; }

        /* ── Table Section ───────────────────────────────────────── */
        .table-title { font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        thead th {
            background: #465a3a; color: #ffffff; padding: 10px 12px; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .5px; text-align: left;
        }
        thead th:nth-child(4), thead th:nth-child(5) { text-align: right; }
        tbody tr { border-bottom: 1px solid #e2e8f0; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td { padding: 10px 12px; font-size: 12px; vertical-align: top; color: #334155; }
        tbody td:nth-child(4), tbody td:nth-child(5) { text-align: right; }
        
        .tag { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; }
        .tag-sistem { background: #dbeafe; color: #1e40af; }
        .tag-manual { background: #f1f5f9; color: #475569; }

        .text-green { color: #10b981; font-weight: 700; }
        .text-red { color: #ef4444; font-weight: 700; }
        .text-muted { color: #94a3b8; font-style: italic; }

        /* ── Signature Section ───────────────────────────────────── */
        .signature-section { display: flex; justify-content: space-between; margin-top: 40px; page-break-inside: avoid; }
        .sig-box { width: 200px; text-align: center; }
        .sig-box p { font-size: 12px; color: #475569; }
        .sig-box .sig-title { font-weight: 600; color: #1e293b; margin-bottom: 70px; }
        .sig-box .sig-name { font-weight: 700; color: #1e293b; border-bottom: 1px solid #1e293b; padding-bottom: 2px; }

        /* ── Footer ──────────────────────────────────────────────── */
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 12px; page-break-inside: avoid; }

        /* ── Print Adjustments ───────────────────────────────────── */
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .container { padding: 0; }
        }
        tr { page-break-inside: avoid; }
    </style>
</head>
<body>

<div class="container">
    {{-- Kop Surat --}}
    <div class="kop-surat">
        <div class="brand-section">
            @php
                $logoPath = public_path('images/logo/Logo_Sakadomas.png');
                $logoBase64 = '';
                if (file_exists($logoPath)) {
                    $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
                } else {
                    $logoBase64 = 'https://i.postimg.cc/yNV69CrK/Logo_Sakadomas.png';
                }
            @endphp
            <img src="{{ $logoBase64 }}" class="brand-logo" alt="Logo Sakadomas">
            <div class="brand-info">
                <h1>SAKADOMAS</h1>
                <p>Peternakan Domba Modern & Unggul — {{ config('smartsaka.address.city', 'Jember') }}, Jawa Timur</p>
                <p>Alamat: Desa Kemuning, Kec. Arjasa, Jember | WA: +{{ config('smartsaka.wa_number', '62895700326271') }}</p>
            </div>
        </div>
        <div class="doc-meta">
            <div class="doc-title">LAPORAN KEUANGAN</div>
            <div class="doc-periode">{{ $periode }}</div>
        </div>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="summary-grid">
        <div class="stat-card pemasukan">
            <div class="stat-title">Total Pemasukan</div>
            <div class="stat-value pemasukan-text">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card pengeluaran">
            <div class="stat-title">Total Pengeluaran</div>
            <div class="stat-value pengeluaran-text">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card saldo">
            <div class="stat-title">Saldo Bersih</div>
            <div class="stat-value {{ $saldo >= 0 ? 'saldo-text-positive' : 'saldo-text-negative' }}">
                Rp {{ number_format($saldo, 0, ',', '.') }}
            </div>
        </div>
    </div>

    {{-- Rincian Transaksi Table --}}
    <div class="table-title">Riwayat Mutasi Kas</div>
    <table>
        <thead>
            <tr>
                <th style="width: 12%">Tanggal</th>
                <th style="width: 48%">Keterangan</th>
                <th style="width: 15%">Sumber</th>
                <th style="width: 12.5%">Pemasukan</th>
                <th style="width: 12.5%">Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data_keuangan as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d-m-Y') }}</td>
                    <td>
                        <strong>{{ $item->ket }}</strong>
                        @if($item->id_transaksi)
                            <span style="font-size: 10px; color: #64748b; display: block; margin-top: 2px;">
                                No. Transaksi: #TRX-{{ $item->id_transaksi }}
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($item->id_transaksi)
                            <span class="tag tag-sistem">Sistem</span>
                        @else
                            <span class="tag tag-manual">Manual</span>
                        @endif
                    </td>
                    <td>
                        @if($item->jenis_keuangan === 'pemasukan')
                            <span class="text-green">+Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($item->jenis_keuangan === 'pengeluaran')
                            <span class="text-red">-Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #94a3b8; font-style: italic; padding: 24px;">
                        Tidak ada data transaksi keuangan dalam periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <div class="signature-section">
        <div class="sig-box">
            <p>Mengetahui,</p>
            <p class="sig-title">Pemilik Peternakan</p>
            <div class="sig-name">H. Sakadomas</div>
            <p style="margin-top: 4px;">Owner</p>
        </div>
        <div class="sig-box">
            <p>Jember, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p class="sig-title">Admin Keuangan</p>
            <div class="sig-name">{{ Auth::user()->nama ?? 'Admin Sakadomas' }}</div>
            <p style="margin-top: 4px;">Administrasi</p>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        Laporan Keuangan ini dicetak secara otomatis oleh {{ config('app.name', 'Smart-Saka') }} pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB.
    </div>
</div>

</body>
</html>
