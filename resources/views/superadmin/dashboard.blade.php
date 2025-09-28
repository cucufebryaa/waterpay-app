@extends('layouts.app')

@section('title', 'Dashboard Super Admin')

@section('content')
    {{-- Greeting --}}
    <div class="mb-4">
        <h1 class="h3 mb-1">Selamat Datang, {{ Auth::user()->name }}!</h1>
        <p class="text-muted">Berikut adalah ringkasan data perusahaan di sistem Anda.</p>
    </div>

    {{-- Kartu Metrik --}}
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white rounded-3 p-3 me-3">
                        <i class="bi bi-buildings fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Total Company</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $totalCompanies }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning text-white rounded-3 p-3 me-3">
                        <i class="bi bi-file-earmark-text fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Pengajuan Baru</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $pendingCompanies }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white rounded-3 p-3 me-3">
                        <i class="bi bi-check-circle fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Disetujui</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $approvedCompanies }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger text-white rounded-3 p-3 me-3">
                        <i class="bi bi-x-circle fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Ditolak</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $rejectedCompanies }}</p>
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
                        <h5 class="card-title mb-0">Grafik Pengajuan Pendaftaran</h5>
                        
                        {{-- FORM FILTER TANGGAL --}}
                        <form id="filterForm" class="d-flex flex-wrap align-items-center gap-2">
    
                            {{-- TAMBAHKAN atribut 'value' di sini --}}
                            <input type="date" id="startDate" class="form-control form-control-sm" style="width: auto;" 
                                   value="{{ $defaultStartDate->format('Y-m-d') }}">
                        
                            <span class="text-muted">hingga</span>
                            
                            {{-- TAMBAHKAN atribut 'value' di sini juga --}}
                            <input type="date" id="endDate" class="form-control form-control-sm" style="width: auto;"
                                   value="{{ $defaultEndDate->format('Y-m-d') }}">
                        
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        </form>
                    </div>
                    <canvas id="submissionChart"></canvas>
                </div>
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
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderColor: 'rgba(13, 110, 253, 1)',
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
                            stepSize: 1 // Pastikan sumbu Y hanya menampilkan angka bulat
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
        e.preventDefault(); // Mencegah form dari reload halaman
        
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const filterButton = this.querySelector('button[type="submit"]');

        if (!startDate || !endDate) {
            alert('Silakan pilih rentang tanggal terlebih dahulu.');
            return;
        }

        // UI feedback: tampilkan loading
        filterButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
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