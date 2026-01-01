<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembayaran - {{ $periodeLabel }}</title>
    <style>
        /* --- STYLE KHUSUS PRINT PDF --- */
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0.5cm; 
            font-size: 10pt; 
            color: #333;
        }
        
        /* Header */
        .report-header { 
            border-bottom: 3px solid #3b82f6; /* Garis biru tebal */
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .report-header h1 { 
            font-size: 18pt; 
            margin: 0; 
            color: #1e293b;
        }
        .report-header p { 
            margin: 2px 0; 
            font-size: 9pt; 
            color: #64748b;
        }

        /* Ringkasan Statistik */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .summary-table td {
            width: 33.33%; /* Membagi menjadi 3 kolom */
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            vertical-align: top;
        }
        .summary-label { 
            font-size: 9pt; 
            color: #64748b; 
            text-transform: uppercase; 
            display: block; 
            margin-bottom: 4px;
        }
        .summary-value { 
            font-size: 14pt; 
            font-weight: bold;
        }
        .summary-total {
            background-color: #dcfce7; /* Latar belakang hijau lembut */
            border-color: #10b981;
        }
        .summary-total .summary-value {
            color: #16a34a; /* Warna hijau tegas */
        }
        
        /* Tabel Detail */
        .table-laporan { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px; 
        }
        .table-laporan th, .table-laporan td {
            border: 1px solid #f1f5f9;
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }
        .table-laporan th {
            background-color: #f8fafc;
            color: #475569; 
            font-weight: 600;
            font-size: 8.5pt;
            text-transform: uppercase;
        }
        .table-laporan td {
            font-size: 9pt;
            color: #334155;
        }
        .table-laporan tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* Status Badges */
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 8pt;
            display: inline-block;
            text-align: center;
        }
        .success { background-color: #10b981; color: white; } /* Hijau Solid */
        .pending { background-color: #f59e0b; color: white; } /* Kuning/Orange Solid */
        .failed { background-color: #ef4444; color: white; } /* Merah Solid */
        
        /* Alignment */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .text-muted { color: #94a3b8; font-size: 8pt; }
    </style>
</head>
<body>

    <div class="report-header">
        <h1>LAPORAN RIWAYAT PEMBAYARAN MASUK</h1>
        <p>Dicetak pada: {{ $tanggalCetak->format('d F Y H:i:s') }}</p>
        <p>Periode Data: {{ $periodeLabel }}</p>
    </div>

    <table class="summary-table">
        <tr>
            {{-- Kolom 1: Total Pendapatan --}}
            <td class="summary-total">
                <span class="summary-label">Total Pendapatan (Lunas)</span>
                <span class="summary-value">Rp {{ number_format($totalUang, 0, ',', '.') }}</span>
            </td>
            {{-- Kolom 2: Transaksi Berhasil --}}
            <td>
                <span class="summary-label">Transaksi Berhasil (Lunas)</span>
                <span class="summary-value text-right">{{ $totalSukses }} Transaksi</span>
            </td>
            {{-- Kolom 3: Menunggu Pembayaran --}}
            <td>
                <span class="summary-label">Menunggu (Pending)</span>
                <span class="summary-value text-right" style="color: #f59e0b;">{{ $totalPending }} Transaksi</span>
            </td>
        </tr>
    </table>
    
    <table class="table-laporan">
        <thead>
            <tr>
                <th style="width: 15%;">TGL/WAKTU</th>
                <th style="width: 25%;">PELANGGAN (ID)</th>
                <th style="width: 15%;">PERIODE TAGIHAN</th>
                <th style="width: 15%;">CHANNEL</th>
                <th style="width: 15%;" class="text-right">TOTAL BAYAR</th>
                <th style="width: 15%;" class="text-center">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dataPembayaran as $item)
            <tr>
                <td>
                    @if($item->tanggal_bayar)
                        <div class="fw-bold">{{ $item->tanggal_bayar->format('d/m/Y') }}</div>
                        <div class="text-muted">{{ $item->tanggal_bayar->format('H:i') }}</div>
                    @else
                        <div class="text-muted">-</div>
                    @endif
                </td>
                <td>
                    <div class="fw-bold">{{ $item->pelanggan->nama ?? 'Unknown' }}</div>
                    <div class="text-muted">ID: {{ $item->pelanggan->no_pelanggan ?? '-' }}</div>
                </td>
                <td>
                    @if($item->pemakaian)
                        {{ $item->pemakaian->created_at->translatedFormat('F Y') }}
                    @else
                        <span style="color: #ef4444;">Data Terhapus</span>
                    @endif
                </td>
                <td>{{ $item->payment_channel ?? 'XENDIT' }}</td>
                <td class="text-right fw-bold">
                    Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                </td>
                <td class="text-center">
                    @php
                        $status = strtolower($item->status);
                        $statusText = $item->status == 'success' ? 'LUNAS' : strtoupper($item->status);
                    @endphp
                    <span class="badge {{ $status }}">
                        {{ $statusText }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 30px;">
                    <div class="text-muted">Tidak ada data pembayaran yang ditemukan.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>