@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')

<style>
    /* Welcome Header */
    .welcome-header {
        padding: 2rem 0 1.5rem;
        border-bottom: 1px solid #e3e6f0;
        margin-bottom: 2rem;
    }

    .welcome-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.25rem;
    }

    .welcome-header p {
        color: #6c757d;
        font-size: 0.95rem;
        margin: 0;
    }

    /* Metric Cards */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .metric-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: 1px solid #e8ecf1;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }

    .metric-icon {
        width: 64px;
        height: 64px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .metric-icon i {
        font-size: 1.75rem;
        color: white;
    }

    .metric-icon.icon-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
    }

    .metric-icon.icon-secondary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }

    .metric-icon.icon-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .metric-icon.icon-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .metric-content {
        flex: 1;
    }

    .metric-label {
        font-size: 0.85rem;
        color: #9ca3af;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.375rem;
    }

    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1;
    }

    /* Modern Section Card */
    .modern-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        overflow: hidden;
        border: 1px solid #e8ecf1;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .section-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(to right, #f8f9fc, #ffffff);
        border-bottom: 1px solid #e8ecf1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-header h5 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-header i {
        font-size: 1.25rem;
        color: #3b82f6;
    }

    .btn-view-all {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        background: linear-gradient(135deg, #3b82f6, #06b6d4);
        color: white;
        border: none;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-view-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        color: white;
    }

    /* Info Cards */
    .info-grid {
        display: grid;
        gap: 1rem;
        padding: 1.5rem;
    }

    .info-item {
        background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);
        border: none;
        border-radius: 12px;
        padding: 1.25rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .info-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: linear-gradient(180deg, #3b82f6, #06b6d4);
    }

    .info-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 172, 254, 0.15);
    }

    .info-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .info-title {
        font-weight: 600;
        color: #0369a1;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-date {
        font-size: 0.8rem;
        color: #64748b;
    }

    .info-message {
        color: #334155;
        font-size: 0.9rem;
        line-height: 1.6;
        margin: 0;
    }

    /* Table Styling */
    .complaint-table {
        margin: 0;
    }

    .complaint-table thead th {
        background: #f8f9fc;
        border: none;
        color: #5a6c7d;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1.25rem;
    }

    .complaint-table tbody tr {
        border-bottom: 1px solid #f0f2f5;
        transition: all 0.2s ease;
    }

    .complaint-table tbody tr:last-child {
        border-bottom: none;
    }

    .complaint-table tbody tr:hover {
        background: #f8f9fc;
    }

    .complaint-table tbody td {
        padding: 1.25rem;
        vertical-align: middle;
        font-size: 0.9rem;
        border: none;
    }

    .complaint-date {
        color: #64748b;
        font-weight: 500;
    }

    .complaint-text {
        color: #2c3e50;
        line-height: 1.5;
    }

    /* Status Badge */
    .status-badge {
        padding: 0.4rem 0.875rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        white-space: nowrap;
    }

    .status-badge.status-open {
        background: #fee;
        color: #dc3545;
    }

    .status-badge.status-delegated {
        background: #e7f5ff;
        color: #0ea5e9;
    }

    .status-badge.status-onprogress {
        background: #fff3cd;
        color: #f59e0b;
    }

    .status-badge.status-completed {
        background: #d1f4e0;
        color: #10b981;
    }

    .status-badge.status-rejected {
        background: #e9ecef;
        color: #6c757d;
    }

    /* Action Buttons */
    .btn-detail {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .waiting-text {
        color: #9ca3af;
        font-size: 0.85rem;
        font-style: italic;
    }

    /* Modal Styling */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 1.25rem 1.5rem;
        border: none;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .detail-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .detail-value {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1.5rem;
    }

    .detail-box {
        background: #f8f9fc;
        border: 1px solid #e8ecf1;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        color: #2c3e50;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .img-bukti {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: 1px solid #e8ecf1;
    }

    .modal-footer {
        border: none;
        padding: 1rem 1.5rem 1.5rem;
    }

    .modal-footer .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #9ca3af;
    }

    .empty-state i {
        font-size: 4rem;
        opacity: 0.5;
        margin-bottom: 1rem;
    }

    .empty-state p {
        font-size: 0.95rem;
        margin: 0;
    }

    /* Scrollbar */
    .scroll-content::-webkit-scrollbar {
        width: 6px;
    }

    .scroll-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .scroll-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .metrics-grid {
            grid-template-columns: 1fr;
        }

        .metric-card {
            padding: 1.25rem;
        }

        .metric-icon {
            width: 56px;
            height: 56px;
        }

        .metric-value {
            font-size: 1.75rem;
        }

        .complaint-table thead th:nth-child(1),
        .complaint-table tbody td:nth-child(1) {
            display: none;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>

<!-- Welcome Header -->
<div class="welcome-header">
    <h1>Selamat Datang, {{ Auth::user()->pelanggan->name ?? Auth::user()->username }}! 👋</h1>
    <p>Ringkasan pemakaian dan tagihan Anda di WaterPay</p>
</div>

<!-- Metrics Grid -->
<div class="metrics-grid">
    <!-- Pemakaian Bulan Ini -->
    <div class="metric-card">
        <div class="metric-icon icon-primary">
            <i class="bi bi-droplet-half"></i>
        </div>
        <div class="metric-content">
            <div class="metric-label">Pemakaian Bulan Ini</div>
            <div class="metric-value">{{ $pemakaianBulanIni ?? 0 }} m³</div>
        </div>
    </div>

    <!-- Pemakaian Bulan Lalu -->
    <div class="metric-card">
        <div class="metric-icon icon-secondary">
            <i class="bi bi-arrow-left-right"></i>
        </div>
        <div class="metric-content">
            <div class="metric-label">Pemakaian Bulan Lalu</div>
            <div class="metric-value">{{ $pemakaianBulanLalu ?? 0 }} m³</div>
        </div>
    </div>

    <!-- Tagihan Bulan Ini -->
    <div class="metric-card">
        <div class="metric-icon icon-success">
            <i class="bi bi-wallet2"></i>
        </div>
        <div class="metric-content">
            <div class="metric-label">Tagihan Bulan Ini</div>
            <div class="metric-value">Rp {{ number_format($tagihanBulanIni ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Jatuh Tempo -->
    <div class="metric-card">
        <div class="metric-icon icon-danger">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div class="metric-content">
            <div class="metric-label">Jatuh Tempo</div>
            <div class="metric-value" style="font-size: 1.25rem;">{{ $jatuhTempo ? $jatuhTempo->format('d M Y') : '-' }}</div>
        </div>
    </div>
</div>

<!-- Informasi & Pengumuman -->
<div class="modern-section">
    <div class="section-header">
        <h5>
            <i class="bi bi-megaphone"></i>
            Informasi & Pengumuman
        </h5>
    </div>
    
    <div class="info-grid scroll-content" style="max-height: 400px; overflow-y: auto;">
        @forelse($informasiAdmin as $info)
            <div class="info-item">
                <div class="info-header">
                    <div class="info-title">
                        <i class="bi bi-info-circle-fill"></i>
                        Info Layanan
                    </div>
                    <div class="info-date">
                        {{ \Carbon\Carbon::parse($info->tanggal)->format('d M Y') }}
                    </div>
                </div>
                <p class="info-message">{{ $info->pesan }}</p>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                <i class="bi bi-bell-slash display-4"></i>
                <p class="mt-2">Tidak ada informasi terbaru</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Status Keluhan -->
<div class="modern-section">
    <div class="section-header">
        <h5>
            <i class="bi bi-clock-history"></i>
            Status Keluhan Terakhir
        </h5>
        <a href="#" class="btn-view-all">
            <span>Lihat Semua</span>
            <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table complaint-table mb-0">
            <thead>
                <tr>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 50%;">Ringkasan Masalah</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 20%;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($keluhanPelanggan as $keluhan)
                <tr>
                    <td>
                        <div class="complaint-date">
                            {{ $keluhan->created_at->format('d M Y') }}
                        </div>
                    </td>
                    <td>
                        <div class="complaint-text" title="{{ $keluhan->keluhan }}">
                            {{ \Illuminate\Support\Str::limit($keluhan->keluhan, 80) }}
                        </div>
                    </td>
                    <td>
                        @php
                            $statusClass = 'status-' . strtolower($keluhan->status);
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            {{ $keluhan->status }}
                        </span>
                    </td>
                    <td class="text-end">
                        @if(strtolower($keluhan->status) == 'completed')
                            <button type="button" 
                                    class="btn-detail" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalDetail{{ $keluhan->id }}">
                                <i class="bi bi-eye"></i>
                                <span>Detail</span>
                            </button>

                            <!-- Modal Detail Pengerjaan -->
                            <div class="modal fade" id="modalDetail{{ $keluhan->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="bi bi-file-earmark-check"></i>
                                                Laporan Pengerjaan
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if($keluhan->maintenance)
                                                <div class="mb-3">
                                                    <div class="detail-label">Tanggal Selesai</div>
                                                    <div class="detail-value">
                                                        <i class="bi bi-calendar-check me-2 text-success"></i>
                                                        {{ \Carbon\Carbon::parse($keluhan->maintenance->tanggal)->format('d F Y, H:i') }}
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <div class="detail-label">Keterangan Petugas</div>
                                                    <div class="detail-box">
                                                        {{ $keluhan->maintenance->deskripsi }}
                                                    </div>
                                                </div>

                                                @if($keluhan->maintenance->foto)
                                                    <div class="mb-3">
                                                        <div class="detail-label">Foto Bukti</div>
                                                        <div class="text-center">
                                                            <img src="{{ asset('storage/' . $keluhan->maintenance->foto) }}" 
                                                                 alt="Bukti Pengerjaan" 
                                                                 class="img-bukti">
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="alert alert-warning mb-0">
                                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                                        Tidak ada foto bukti yang dilampirkan
                                                    </div>
                                                @endif
                                            @else
                                                <div class="alert alert-danger mb-0">
                                                    <i class="bi bi-x-circle me-2"></i>
                                                    Data detail maintenance tidak ditemukan
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="waiting-text">Menunggu proses</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>Anda belum memiliki riwayat keluhan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection