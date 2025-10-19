@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@push('styles')
{{-- Jika Anda butuh CSS khusus untuk halaman ini --}}
@endpush

@section('content')
    {{-- Greeting --}}
    <div class="mb-4">
        <h1 class="h3 mb-1">Selamat Datang, {{ Auth::user()->name ?? Auth::user()->username }}!</h1>
        <p class="text-muted">Berikut adalah ringkasan data tagihan dan pemakaian air Anda.</p>
    </div>

    {{-- Kartu Metrik --}}
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning text-white rounded-3 p-3 me-3">
                        <i class="bi bi-file-earmark-text fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Tagihan Belum Dibayar</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $tagihanBelumBayar }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white rounded-3 p-3 me-3">
                        <i class="bi bi-check-circle fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Tagihan Lunas</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $tagihanLunas }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info text-white rounded-3 p-3 me-3">
                        <i class="bi bi-exclamation-octagon fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Total Keluhan</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $totalKeluhan }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Grafik Pemakaian Air (m³)</h5>
                        
                        {{-- FORM FILTER TANGGAL --}}
                        <form id="filterForm" class="d-flex flex-wrap align-items-center gap-2">
                            {{-- Ganti tipe input ke 'month' jika lebih cocok, tapi 'date' lebih konsisten --}}
                            <input type="date" id="startDate" class="form-control form-control-sm" style="width: auto;" 
                                   value="{{ $defaultStartDate->format('Y-m-d') }}">
                        
                            <span class="text-muted">hingga</span>
                            
                            <input type="date" id="endDate" class="form-control form-control-sm" style="width: auto;"
                                   value="{{ $defaultEndDate->format('Y-m-d') }}">
                        
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        </form>
                    </div>
                    <canvas id="usageChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Pastikan Chart.js sudah di-load di layout utama (app.blade.php) --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('usageChart').getContext('2d');
    let usageChart; // Deklarasikan variabel chart di luar

    // Fungsi untuk membuat atau memperbarui chart
    function renderChart(chartLabels, chartData) {
        if (usageChart) {
            usageChart.destroy(); // Hancurkan chart lama jika ada
        }
        usageChart = new Chart(ctx, {
            type: 'bar', // Bar chart mungkin lebih cocok untuk pemakaian bulanan
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Pemakaian Air (m³)',
                    data: chartData,
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            // Tambahan: label untuk m³
                            callback: function(value) {
                                return value + ' m³';
                            }
                        }
                    }
                }
            }
        });
    }

    // Render chart awal saat halaman dimuat
    // Ambil data dari controller
    const initialChartData = @json($chartData);
    renderChart(initialChartData.labels, initialChartData.data);

    // Event listener untuk form filter
    const filterForm = document.getElementById('filterForm');
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault(); 
        
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const filterButton = this.querySelector('button[type="submit"]');

        if (!startDate || !endDate) {
            alert('Silakan pilih rentang tanggal terlebih dahulu.');
            return;
        }

        // UI feedback
        filterButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        filterButton.disabled = true;

        // URL AJAX (pastikan route-nya ada)
        const url = `{{ route('pelanggan.chart.data') }}?start_date=${startDate}&end_date=${endDate}`;

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
            })
            .catch(error => {
                console.error('Error fetching chart data:', error);
                alert('Gagal memuat data chart. Silakan coba lagi.');
            })
            .finally(() => {
                // Kembalikan tombol ke state normal
                filterButton.innerHTML = 'Filter';
                filterButton.disabled = false;
            });
    });
});
</script>
@endpush