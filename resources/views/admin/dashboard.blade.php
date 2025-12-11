@extends('layouts.app')

@section('title', 'Dashboard Operasional')

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

    .metric-icon.icon-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .metric-icon.icon-info {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
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

    .metric-value.value-primary {
        color: #3b82f6;
    }

    .metric-value.value-success {
        color: #10b981;
    }

    .metric-value.value-info {
        color: #06b6d4;
    }

    .metric-value.value-danger {
        color: #ef4444;
    }

    /* Chart Section */
    .chart-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        overflow: hidden;
        border: 1px solid #e8ecf1;
        margin-bottom: 2rem;
    }

    .chart-header {
        padding: 1.5rem;
        background: linear-gradient(to right, #f8f9fc, #ffffff);
        border-bottom: 1px solid #e8ecf1;
    }

    .chart-title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .chart-title i {
        font-size: 1.25rem;
        color: #3b82f6;
    }

    .chart-body {
        padding: 2rem;
    }

    .chart-placeholder {
        height: 350px;
        background: linear-gradient(135deg, #f8f9fc 0%, #e8ecf1 100%);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        border: 2px dashed #cbd5e1;
    }

    .chart-placeholder i {
        font-size: 4rem;
        color: #cbd5e1;
    }

    .chart-placeholder p {
        font-size: 1rem;
        color: #9ca3af;
        font-weight: 500;
        margin: 0;
    }

    /* Quick Stats Grid */
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-item {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        border: 1px solid #e8ecf1;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fc;
        color: #3b82f6;
        flex-shrink: 0;
    }

    .stat-icon i {
        font-size: 1.25rem;
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #9ca3af;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2c3e50;
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

        .metric-icon i {
            font-size: 1.5rem;
        }

        .metric-value {
            font-size: 1.75rem;
        }

        .chart-body {
            padding: 1.5rem;
        }

        .chart-placeholder {
            height: 250px;
        }

        .chart-placeholder i {
            font-size: 3rem;
        }

        .quick-stats {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .welcome-header {
            padding: 1.5rem 0 1rem;
        }

        .metric-value {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Welcome Header -->
<div class="welcome-header">
    <h1>Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
    <p>Berikut adalah ringkasan operasional perusahaan Anda</p>
</div>

<!-- Metrics Grid -->
<div class="metrics-grid">
    <!-- Total Petugas -->
    <div class="metric-card">
        <div class="metric-icon icon-primary">
            <i class="bi bi-person-workspace"></i>
        </div>
        <div class="metric-content">
            <div class="metric-label">Total Petugas</div>
            <div class="metric-value value-primary">{{ $totalPetugas }}</div>
        </div>
    </div>

    <!-- Total Pelanggan -->
    <div class="metric-card">
        <div class="metric-icon icon-success">
            <i class="bi bi-person-lines-fill"></i>
        </div>
        <div class="metric-content">
            <div class="metric-label">Total Pelanggan</div>
            <div class="metric-value value-success">{{ $totalPelanggan }}</div>
        </div>
    </div>

    <!-- Pembayaran Bulan Ini -->
    <div class="metric-card">
        <div class="metric-icon icon-info">
            <i class="bi bi-wallet2"></i>
        </div>
        <div class="metric-content">
            <div class="metric-label">Pembayaran Lunas Bulan Ini</div>
            <div class="metric-value value-info">
                @if(is_numeric($pembayaranBulanIni))
                    Rp {{ number_format($pembayaranBulanIni, 0, ',', '.') }}
                @else
                    Rp {{ $pembayaranBulanIni }}
                @endif
            </div>
        </div>
    </div>

    <!-- Total Tunggakan -->
    <div class="metric-card">
        <div class="metric-icon icon-danger">
            <i class="bi bi-credit-card-2-back"></i>
        </div>
        <div class="metric-content">
            <div class="metric-label">Total Tunggakan</div>
            <div class="metric-value value-danger">
                @if(is_numeric($totalTunggakan))
                    Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
                @else
                    Rp {{ $totalTunggakan }}
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="chart-section">
    <div class="chart-header">
        <h5 class="chart-title"><i class="bi bi-graph-up-arrow"></i>Grafik Laporan Bulanan</h5>
    </div>
    <div class="chart-body">
        <canvas id="financialOperationalChart" style="max-height: 450px; width: 100%;"></canvas>
    </div>
</div>

<script>
    // Ambil data dari Laravel Blade (Real-Time Data)
    const months = @json($months);
    const dataLunas = @json($pembayaranBulanIni);
    const dataTunggakan = @json($totalTunggakan);

    const ctx = document.getElementById('financialOperationalChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar', // Bar chart untuk perbandingan
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Pembayaran Lunas (Rp)',
                    data: dataLunas,
                    backgroundColor: 'rgba(40, 167, 69, 0.7)', // Hijau (Lunas)
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Total Tagihan Tertunggak (Rp)',
                    data: dataTunggakan,
                    backgroundColor: 'rgba(220, 53, 69, 0.7)', // Merah (Tunggakan)
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nominal (Rp)'
                    },
                    ticks: {
                        // Format mata uang Rupiah
                        callback: function(value, index, ticks) {
                        // Pastikan nilai adalah angka
                        if (typeof value === 'number') {
                            // Menggunakan toLocaleString dengan locale 'id-ID' untuk format Indonesia
                            // style: 'currency' -> Menambahkan simbol mata uang
                            // currency: 'IDR' -> Menetapkan mata uang Rupiah
                            // maximumFractionDigits: 0 -> Menghilangkan angka desimal
                            
                            return value.toLocaleString('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0, // Pastikan tidak ada desimal
                                maximumFractionDigits: 0  // Pastikan tidak ada desimal
                            });
                            
                        }
                        return value;   
                    }
                    }
                }
            }
        }
    });
</script>

<!-- Quick Stats (Optional - bisa dihapus jika tidak perlu) -->
{{-- 
<div class="row">
    <div class="col-12">
        <h5 class="mb-3" style="color: #2c3e50; font-weight: 600;">
            <i class="bi bi-speedometer2 me-2" style="color: #3b82f6;"></i>
            Statistik Cepat
        </h5>
    </div>
</div>

<div class="quick-stats">
    <div class="stat-item">
        <div class="stat-icon">
            <i class="bi bi-droplet"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Pemakaian Air Hari Ini</div>
            <div class="stat-value">1,245 m³</div>
        </div>
    </div>

    <div class="stat-item">
        <div class="stat-icon">
            <i class="bi bi-clipboard-check"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Task Selesai</div>
            <div class="stat-value">34 Tasks</div>
        </div>
    </div>

    <div class="stat-item">
        <div class="stat-icon">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Keluhan Aktif</div>
            <div class="stat-value">12 Keluhan</div>
        </div>
    </div>

    <div class="stat-item">
        <div class="stat-icon">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Pembayaran Berhasil</div>
            <div class="stat-value">89%</div>
        </div>
    </div>
</div>
--}}

@endsection

@push('scripts')
<script>
    // Jika nanti mau tambahkan Chart.js
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('operationalChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Pendapatan',
                        data: [12000, 19000, 15000, 25000, 22000, 30000],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    });
</script>
@endpush