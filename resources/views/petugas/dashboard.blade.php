@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
    {{-- Greeting --}}
    <div class="mb-4">
        <h1 class="h3 mb-1">Selamat Datang, {{ Auth::user()->name ?? Auth::user()->username }}!</h1>
        <p class="text-muted">Berikut adalah ringkasan tugas pencatatan dan keluhan Anda.</p>
    </div>

    {{-- Kartu Metrik --}}
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white rounded-3 p-3 me-3">
                        <i class="bi bi-people fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Total Pelanggan</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $totalTugasCatat }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white rounded-3 p-3 me-3">
                        <i class="bi bi-droplet-half fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Selesai Catat (Bulan Ini)</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $pencatatanSelesai }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning text-white rounded-3 p-3 me-3">
                        <i class="bi bi-exclamation-octagon fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Keluhan Ditangani</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $keluhanBaru }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info text-white rounded-3 p-3 me-3">
                        <i class="bi bi-check2-circle fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Keluhan Selesai</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $keluhanSelesai }}</p>
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
                        <h5 class="card-title mb-0">Grafik Pencatatan Selesai</h5>
                        
                        {{-- FORM FILTER TANGGAL --}}
                        <form id="filterForm" class="d-flex flex-wrap align-items-center gap-2">
                            <input type="date" id="startDate" class="form-control form-control-sm" style="width: auto;" 
                                   value="{{ $defaultStartDate->format('Y-m-d') }}">
                        
                            <span class="text-muted">hingga</span>
                            
                            <input type="date" id="endDate" class="form-control form-control-sm" style="width: auto;"
                                   value="{{ $defaultEndDate->format('Y-m-d') }}">
                        
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        </form>
                    </div>
                    {{-- ID Canvas ini berbeda dari superadmin/pelanggan --}}
                    <canvas id="readingChart"></canvas>
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
    // Sesuaikan ID canvas
    const ctx = document.getElementById('readingChart').getContext('2d');
    let readingChart; // Deklarasikan variabel chart

    // Fungsi untuk membuat atau memperbarui chart
    function renderChart(chartLabels, chartData) {
        if (readingChart) {
            readingChart.destroy(); // Hancurkan chart lama
        }
        readingChart = new Chart(ctx, {
            type: 'line', // Tipe 'line' seperti superadmin
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Jumlah Pencatatan Selesai',
                    data: chartData,
                    backgroundColor: 'rgba(25, 135, 84, 0.1)', // Warna hijau (success)
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1 // Sumbu Y harus angka bulat
                        }
                    }
                }
            }
        });
    }

    // Render chart awal saat halaman dimuat
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
        const url = `{{ route('petugas.chart.data') }}?start_date=${startDate}&end_date=${endDate}`;

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