@extends('layouts.app')

@section('title', 'Dashboard Super Admin')

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

    .metric-icon.icon-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
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
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
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

    .chart-filter {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.75rem;
    }

    .filter-input {
        padding: 0.5rem 0.875rem;
        border: 2px solid #e8ecf1;
        border-radius: 10px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        background: white;
    }

    .filter-input:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .filter-separator {
        color: #9ca3af;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .btn-filter {
        padding: 0.5rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        background: linear-gradient(135deg, #3b82f6, #06b6d4);
        color: white;
        border: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }

    .btn-filter:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-filter i {
        font-size: 1rem;
    }

    .chart-body {
        padding: 2rem;
    }

    .chart-container {
        position: relative;
        height: 400px;
    }

    /* Loading Spinner */
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.15em;
    }

    /* Empty State */
    .chart-empty {
        text-align: center;
        padding: 4rem 2rem;
        color: #9ca3af;
    }

    .chart-empty i {
        font-size: 4rem;
        opacity: 0.5;
        margin-bottom: 1rem;
    }

    .chart-empty p {
        font-size: 0.95rem;
        margin: 0;
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

        .chart-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .chart-filter {
            width: 100%;
        }

        .filter-input {
            flex: 1;
            min-width: 120px;
        }

        .chart-container {
            height: 300px;
        }
    }

    @media (max-width: 576px) {
        .welcome-header {
            padding: 1.5rem 0 1rem;
        }

        .chart-body {
            padding: 1.5rem;
        }
    }
</style>

<!-- Welcome Header -->
<div class="welcome-header">
    <h1>Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
    <p>Berikut adalah ringkasan data perusahaan di sistem Anda</p>
</div>

<!-- Metrics Grid -->
<div class="metrics-grid">
    <!-- Total Companies -->
    <div class="metric-card">
        <div class="metric-icon icon-primary">
            <i class="bi bi-buildings"></i>
        </div>
        <div class="metric-content">
            <div class="metric-label">Total Company</div>
            <div class="metric-value">{{ $totalCompanies }}</div>
        </div>
    </div>

    <!-- Pending Applications -->
    <div class="metric-card">
        <div class="metric-icon icon-warning">
            <i class="bi bi-file-earmark-text"></i>
        </div>
        <div class="metric-content">
            <div class="metric-label">Pengajuan Baru</div>
            <div class="metric-value">{{ $pendingCompanies }}</div>
        </div>
    </div>

    <!-- Approved -->
    <div class="metric-card">
        <div class="metric-icon icon-success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="metric-content">
            <div class="metric-label">Disetujui</div>
            <div class="metric-value">{{ $approvedCompanies }}</div>
        </div>
    </div>

    <!-- Rejected -->
    <div class="metric-card">
        <div class="metric-icon icon-danger">
            <i class="bi bi-x-circle"></i>
        </div>
        <div class="metric-content">
            <div class="metric-label">Ditolak</div>
            <div class="metric-value">{{ $rejectedCompanies }}</div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="chart-section">
    <div class="chart-header">
        <h5 class="chart-title">
            <i class="bi bi-graph-up"></i>
            Grafik Pengajuan Pendaftaran
        </h5>
        
        <!-- Filter Form -->
        <form id="filterForm" class="chart-filter">
            <input type="date" 
                   id="startDate" 
                   class="filter-input" 
                   value="{{ $defaultStartDate->format('Y-m-d') }}"
                   required>
            
            <span class="filter-separator">hingga</span>
            
            <input type="date" 
                   id="endDate" 
                   class="filter-input"
                   value="{{ $defaultEndDate->format('Y-m-d') }}"
                   required>
            
            <button type="submit" class="btn-filter" id="filterButton">
                <i class="bi bi-funnel"></i>
                <span>Filter</span>
            </button>
        </form>
    </div>
    
    <div class="chart-body">
        <div class="chart-container">
            <canvas id="submissionChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('submissionChart').getContext('2d');
    let submissionChart; // Deklarasikan variabel chart di luar

    // Fungsi untuk membuat atau memperbarui chart
    function renderChart(chartLabels, chartData) {
        if (submissionChart) {
            submissionChart.destroy(); // Hancurkan chart lama jika ada
        }
        
        submissionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Jumlah Pengajuan',
                    data: chartData,
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: 'rgba(59, 130, 246, 1)',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 13,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        borderColor: 'rgba(59, 130, 246, 0.5)',
                        borderWidth: 1,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Pengajuan: ' + context.parsed.y + ' perusahaan';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            },
                            color: '#64748b'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#64748b',
                            maxRotation: 45,
                            minRotation: 0
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    // Render chart awal saat halaman dimuat
    const initialChartData = @json($chartData);
    renderChart(initialChartData.labels, initialChartData.data);

    // Event listener untuk form filter
    const filterForm = document.getElementById('filterForm');
    const filterButton = document.getElementById('filterButton');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');

    filterForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah form dari reload halaman
        
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (!startDate || !endDate) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan pilih rentang tanggal terlebih dahulu.',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        // Validasi: start date tidak boleh lebih besar dari end date
        if (new Date(startDate) > new Date(endDate)) {
            Swal.fire({
                icon: 'error',
                title: 'Tanggal Tidak Valid',
                text: 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        // UI feedback: tampilkan loading
        const originalContent = filterButton.innerHTML;
        filterButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
        filterButton.disabled = true;

        // Buat URL untuk AJAX
        const url = `{{ route('superadmin.chart.data') }}?start_date=${startDate}&end_date=${endDate}`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Perbarui chart dengan data baru
                renderChart(data.labels, data.data);
                
                // Success notification
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data grafik berhasil diperbarui.',
                    timer: 1500,
                    showConfirmButton: false
                });
            })
            .catch(error => {
                console.error('Error fetching chart data:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal memuat data chart. Silakan coba lagi.',
                    confirmButtonColor: '#3b82f6'
                });
            })
            .finally(() => {
                // Kembalikan tombol ke state normal
                filterButton.innerHTML = originalContent;
                filterButton.disabled = false;
            });
    });

    // Set max date to today for both inputs
    const today = new Date().toISOString().split('T')[0];
    startDateInput.setAttribute('max', today);
    endDateInput.setAttribute('max', today);

    // Update end date min value when start date changes
    startDateInput.addEventListener('change', function() {
        endDateInput.setAttribute('min', this.value);
    });
});
</script>
@endpush