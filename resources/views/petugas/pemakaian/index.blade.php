@extends('layouts.app')

@section('title', 'Input Meteran Bulan Ini')

@section('content')

<style>
    /* --- MODERN DASHBOARD STYLE --- */
    :root {
        --primary-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --glass-bg: rgba(255, 255, 255, 0.95);
    }

    .bg-gradient-primary { background: var(--primary-gradient); }
    .bg-gradient-success { background: var(--success-gradient); }

    /* Stat Cards */
    .stat-card {
        border: none;
        border-radius: 16px;
        transition: transform 0.2s;
        overflow: hidden;
        position: relative;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-icon {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 4rem;
        opacity: 0.15;
        transform: rotate(-15deg);
    }

    /* Tabs Navigation */
    .nav-pills-custom {
        background: #f3f4f6;
        padding: 6px;
        border-radius: 50rem;
        display: inline-flex;
        width: 100%;
    }
    .nav-pills-custom .nav-link {
        border-radius: 50rem;
        color: #6b7280;
        font-weight: 600;
        padding: 10px 20px;
        transition: all 0.3s;
        width: 100%;
        text-align: center;
    }
    .nav-pills-custom .nav-link.active {
        background: white;
        color: #2563eb;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    /* Task Card Refined */
    .task-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: all 0.2s;
        border-left: 5px solid transparent;
    }
    .task-card.pending { border-left-color: #3b82f6; }
    .task-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    /* Button Gradient */
    .btn-catat {
        background: var(--success-gradient);
        color: white;
        border: none;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        transition: all 0.3s;
    }
    .btn-catat:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.4);
        color: white;
    }

    /* Camera Elements */
    #camera-container {
        position: relative;
        width: 100%;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
        display: none;
    }
    #video-feed { width: 100%; height: auto; display: block; }
    #result-preview {
        width: 100%;
        border-radius: 12px;
        border: 3px solid #10b981;
        display: none;
    }
    .camera-overlay {
        position: absolute; bottom: 20px; left: 0; right: 0;
        display: flex; justify-content: center; gap: 15px; z-index: 10;
    }
    
    /* Table History Style */
    .table-history th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        font-weight: 700;
        border-bottom: 2px solid #e9ecef;
    }
    .table-history td {
        vertical-align: middle;
        font-size: 0.9rem;
    }
</style>

<!-- HEADER SECTION -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-0">Halo, {{ Auth::user()->name }}! 👋</h4>
            <small class="text-muted">Laporan Periode {{ \Carbon\Carbon::now()->isoFormat('MMMM Y') }}</small>
        </div>
        <div class="dropdown">
            <button class="btn btn-light rounded-circle shadow-sm" type="button">
                <i class="bi bi-bell"></i>
            </button>
        </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="row g-3">
        <div class="col-6">
            <div class="card stat-card bg-gradient-primary text-white h-100">
                <div class="card-body p-3">
                    <h2 class="fw-bold mb-0">{{ count($pelangganPending) }}</h2>
                    <small class="text-white-50 fw-bold text-uppercase" style="font-size: 0.7rem;">Belum Dicatat</small>
                    <i class="bi bi-hourglass-split stat-icon text-white"></i>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card stat-card bg-white text-success h-100 border-0 shadow-sm">
                <div class="card-body p-3 position-relative">
                    <h2 class="fw-bold mb-0">{{ count($riwayatPencatatan) }}</h2>
                    <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">Selesai</small>
                    <i class="bi bi-check-circle-fill stat-icon text-success"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MAIN CONTENT TABS -->
<div class="row justify-content-center">
    <div class="col-md-12">
        
        <!-- Tab Navigation -->
        <ul class="nav nav-pills-custom mb-4 shadow-sm" id="pills-tab" role="tablist">
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link active" id="pills-pending-tab" data-bs-toggle="pill" data-bs-target="#pills-pending" type="button" role="tab">
                    <i class="bi bi-list-task me-1"></i> Daftar Tugas
                </button>
            </li>
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link" id="pills-history-tab" data-bs-toggle="pill" data-bs-target="#pills-history" type="button" role="tab">
                    <i class="bi bi-clock-history me-1"></i> Riwayat
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="pills-tabContent">
            
            <!-- TAB 1: PENDING TASKS -->
            <div class="tab-pane fade show active" id="pills-pending" role="tabpanel">
                
                <!-- Search Bar -->
                <div class="card border-0 shadow-sm mb-4 rounded-4">
                    <div class="card-body p-2">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="searchInput" class="form-control border-0 bg-transparent" placeholder="Cari nama atau ID pelanggan...">
                        </div>
                    </div>
                </div>

                <div class="row" id="taskList">
                    @forelse($pelangganPending as $pelanggan)
                    <div class="col-md-6 col-lg-4 mb-3 task-item">
                        <div class="card h-100 task-card pending bg-white">
                            <div class="card-body position-relative">
                                <!-- Badge Product -->
                                <span class="position-absolute top-0 end-0 mt-3 me-3 badge bg-light text-secondary rounded-pill border">
                                    {{ $pelanggan->kode_product->kd_product ?? 'UMUM' }}
                                </span>

                                <div class="mb-3">
                                    <small class="text-primary fw-bold" style="font-size: 0.75rem;">ID: {{ $pelanggan->no_pelanggan }}</small>
                                    <h5 class="card-title fw-bold text-dark mt-1 mb-1">{{ $pelanggan->nama }}</h5>
                                    <p class="text-muted small mb-0">
                                        <i class="bi bi-geo-alt me-1"></i> {{ Str::limit($pelanggan->alamat, 35) }}
                                    </p>
                                </div>

                                <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="d-block text-secondary" style="font-size: 0.7rem;">METER LALU</span>
                                        <span class="fw-bold text-dark fs-5">{{ $pelanggan->stand_meter_terakhir }} <small class="fs-6 text-muted">m³</small></span>
                                    </div>
                                    <button type="button" 
                                            class="btn btn-catat rounded-pill px-4 py-2 fw-bold"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalCamInput"
                                            data-id="{{ $pelanggan->id }}"
                                            data-nama="{{ $pelanggan->nama }}"
                                            data-nopel="{{ $pelanggan->no_pelanggan }}"
                                            data-meter-awal="{{ $pelanggan->stand_meter_terakhir }}"
                                            {{-- Data di bawah ini opsional krn Controller sudah ambil via ID, tapi bagus untuk display JS --}}
                                            data-tarif="{{ $pelanggan->kode_product->harga_product ?? 0 }}"
                                            data-kd-product="{{ $pelanggan->kode_product->kd_product ?? '-' }}">
                                        <i class="bi bi-camera-fill me-1"></i> Catat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3">
                            <div class="bg-success bg-opacity-10 p-4 rounded-circle d-inline-block">
                                <i class="bi bi-check-lg text-success display-4"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark">Luar Biasa!</h4>
                        <p class="text-muted">Semua tugas pencatatan bulan ini telah selesai.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- TAB 2: HISTORY (DIPERBARUI) -->
            <div class="tab-pane fade" id="pills-history" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Riwayat & Tagihan</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-history">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">TANGGAL</th>
                                    <th>PELANGGAN</th>
                                    <th>TARIF / METER</th>
                                    <th class="text-end pe-4">TAGIHAN (Est)</th>
                                    <th class="text-end">BUKTI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatPencatatan as $riwayat)
                                <tr class="border-bottom border-light">
                                    <td class="ps-4">
                                        <span class="fw-bold text-dark d-block">{{ $riwayat->created_at->format('d/m') }}</span>
                                        <small class="text-muted">{{ $riwayat->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 150px;">
                                            {{ $riwayat->pelanggan->nama ?? '-' }}
                                        </div>
                                        <span class="badge bg-light text-secondary border mt-1">
                                            ID: {{ $riwayat->pelanggan->no_pelanggan ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- Informasi Produk & Meteran --}}
                                        <div class="d-flex flex-column">
                                            <div class="mb-1">
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                                    {{ $riwayat->kd_product ?? 'N/A' }}
                                                </span>
                                                <small class="text-muted ms-1">@ Rp {{ number_format($riwayat->tarif, 0, ',', '.') }}</small>
                                            </div>
                                            <small class="text-secondary">
                                                <i class="bi bi-speedometer2 me-1"></i>
                                                {{ $riwayat->meter_awal }} ➜ <strong>{{ $riwayat->meter_akhir }}</strong>
                                            </small>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        {{-- Informasi Tagihan --}}
                                        <div class="d-block">
                                            <h6 class="fw-bold text-primary mb-0">
                                                Rp {{ number_format($riwayat->total_tagihan ?? 0, 0, ',', '.') }}
                                            </h6>
                                            <small class="text-success fw-bold">
                                                Pakai: {{ $riwayat->total_pakai }} m³
                                            </small>
                                        </div>
                                    </td>
                                    <td class="text-end pe-3">
                                        @if($riwayat->foto)
                                            <!-- REVISI: Menggunakan Button & Modal, bukan Link -->
                                            <button type="button" 
                                                    class="btn btn-light btn-sm rounded-circle border text-primary btn-view-photo" 
                                                    data-src="{{ asset('storage/'.$riwayat->foto) }}"
                                                    data-bs-toggle="tooltip" 
                                                    title="Lihat Foto">
                                                <i class="bi bi-image"></i>
                                            </button>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-gray-300"></i>
                                        Belum ada data riwayat bulan ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- MODAL KAMERA (INPUT) -->
<div class="modal fade" id="modalCamInput" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-camera-reels me-2"></i>Ambil Foto Meteran
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('petugas.pemakaian.store') }}" method="POST" id="formLaporan">
                @csrf
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-7 bg-black d-flex align-items-center justify-content-center position-relative" style="min-height: 400px;">
                            
                            <div id="loading-cam" class="loading-cam">
                                <div class="spinner-border text-light mb-2" role="status"></div>
                                <div class="text-white small">Menyiapkan Kamera & GPS...</div>
                            </div>

                            <div id="camera-container">
                                <video id="video-feed" autoplay playsinline></video>
                                <div class="camera-overlay">
                                    <button type="button" id="btn-snap" class="btn btn-light rounded-circle p-4 shadow-lg border-4 border-white">
                                        <div class="rounded-circle bg-danger" style="width: 20px; height: 20px;"></div>
                                    </button>
                                </div>
                            </div>

                            <img id="result-preview" src="" alt="Hasil Foto">
                            <canvas id="canvas-process" style="display:none;"></canvas>

                            <button type="button" id="btn-retake" class="btn btn-warning btn-sm position-absolute top-0 end-0 m-3 shadow rounded-pill px-3" style="display:none;">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Ulang
                            </button>
                        </div>

                        <div class="col-lg-5 p-4 d-flex flex-column justify-content-between">
                            <div>
                                <input type="hidden" name="id_pelanggan" id="form_id_pelanggan">
                                <input type="hidden" name="meter_awal" id="form_meter_awal">
                                <input type="hidden" name="image_base64" id="image_base64" required>

                                <div class="text-center mb-4">
                                    <h5 class="fw-bold text-dark mb-1" id="display_nama">-</h5>
                                    <span class="badge bg-light text-secondary border px-3" id="display_nopel">-</span>
                                </div>

                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="small fw-bold text-muted mb-1">METER AWAL</label>
                                        <div class="p-2 bg-light rounded text-center border">
                                            <span class="fw-bold text-dark" id="txt_meter_awal">-</span> <small>m³</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="small fw-bold text-primary mb-1">PAKAI</label>
                                        <div class="p-2 bg-primary bg-opacity-10 rounded text-center border border-primary">
                                            <strong id="display_total_pakai" class="text-primary">0</strong> <small class="text-primary">m³</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="small fw-bold text-dark mb-2">INPUT METERAN SEKARANG</label>
                                    <div class="input-group input-group-lg">
                                        <input type="number" name="meter_akhir" id="input_meter_akhir" class="form-control fw-bold text-center" required placeholder="0000">
                                        <span class="input-group-text bg-light text-muted">m³</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" id="btn-submit" class="btn btn-secondary fw-bold py-3 rounded-pill" disabled>
                                    <i class="bi bi-lock-fill me-2"></i>Lengkapi Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL VIEW PHOTO (BARU) -->
<div class="modal fade" id="modalViewPhoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center position-relative">
                <!-- Tombol Close Melayang -->
                <button type="button" class="btn btn-light rounded-circle shadow position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" style="z-index: 1056;">
                    <i class="bi bi-x-lg"></i>
                </button>
                <!-- Image Container -->
                <img id="img-popup-view" src="" class="img-fluid rounded-3 shadow-lg" style="max-height: 80vh; object-fit: contain; background: #000;" alt="Bukti Foto Meteran">
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. SETUP VARIABEL ---
        const namaPetugas = "{{ Auth::user()->name ?? 'Petugas' }}";
        
        // Elemen Kamera
        const video = document.getElementById('video-feed');
        const canvas = document.getElementById('canvas-process');
        const imgPreview = document.getElementById('result-preview');
        const camContainer = document.getElementById('camera-container');
        const loadingMsg = document.getElementById('loading-cam');
        
        // Tombol & Input
        const btnSnap = document.getElementById('btn-snap');
        const btnRetake = document.getElementById('btn-retake');
        const btnSubmit = document.getElementById('btn-submit');
        
        const inputBase64 = document.getElementById('image_base64');
        const inputMeterAkhir = document.getElementById('input_meter_akhir');
        const inputMeterAwal = document.getElementById('form_meter_awal');
        const txtMeterAwal = document.getElementById('txt_meter_awal');
        const dispTotal = document.getElementById('display_total_pakai');

        const modal = document.getElementById('modalCamInput');
        let stream = null;
        let currentLocation = { lat: '-', lng: '-' };

        // --- 2. LOGIKA UTAMA: CEK TOMBOL SUBMIT ---
        function checkSubmit() {
            const hasImage = inputBase64.value.trim().length > 0;
            const valAkhir = parseFloat(inputMeterAkhir.value);
            const valAwal = parseFloat(inputMeterAwal.value) || 0;
            const isMeterValid = !isNaN(valAkhir) && (valAkhir >= valAwal);

            if (hasImage && isMeterValid) {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('btn-secondary');
                btnSubmit.classList.add('btn-success');
                btnSubmit.innerHTML = '<i class="bi bi-save-fill me-2"></i>SIMPAN LAPORAN';
            } else {
                btnSubmit.disabled = true;
                btnSubmit.classList.remove('btn-success');
                btnSubmit.classList.add('btn-secondary');
                if(!hasImage) btnSubmit.innerHTML = '<i class="bi bi-camera me-2"></i>Ambil Foto Dulu';
                else if(!isMeterValid) btnSubmit.innerHTML = '<i class="bi bi-exclamation-circle me-2"></i>Cek Angka Meter';
            }
        }

        // --- 3. INPUT EVENT LISTENER ---
        inputMeterAkhir.addEventListener('input', function() {
            const akhir = parseFloat(this.value) || 0;
            const awal = parseFloat(inputMeterAwal.value) || 0;
            const total = akhir - awal;
            
            dispTotal.textContent = total;
            
            if (total < 0) {
                dispTotal.parentElement.classList.remove('bg-primary', 'border-primary', 'text-primary');
                dispTotal.parentElement.classList.add('bg-danger', 'border-danger', 'text-danger');
                dispTotal.classList.remove('text-primary');
                dispTotal.classList.add('text-danger');
                this.classList.add('is-invalid');
            } else {
                dispTotal.parentElement.classList.add('bg-primary', 'border-primary');
                dispTotal.parentElement.classList.remove('bg-danger', 'border-danger');
                dispTotal.classList.add('text-primary');
                dispTotal.classList.remove('text-danger');
                this.classList.remove('is-invalid');
            }
            
            checkSubmit();
        });

        // --- 4. MODAL & KAMERA ---
        modal.addEventListener('show.bs.modal', function(event) {
            const btn = event.relatedTarget;
            const meterAwal = btn.getAttribute('data-meter-awal');

            document.getElementById('form_id_pelanggan').value = btn.getAttribute('data-id');
            document.getElementById('form_meter_awal').value = meterAwal;
            
            document.getElementById('display_nama').textContent = btn.getAttribute('data-nama');
            document.getElementById('display_nopel').textContent = 'ID: ' + btn.getAttribute('data-nopel');
            txtMeterAwal.textContent = meterAwal;
            
            // Reset
            inputMeterAkhir.value = '';
            inputMeterAkhir.classList.remove('is-invalid');
            dispTotal.textContent = '0';
            
            resetCameraUI();
            startCamera();
            getGPS();
        });

        modal.addEventListener('hidden.bs.modal', function() {
            stopCamera();
        });

        // --- 5. FUNGSI KAMERA ---
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: "environment" }, audio: false 
                });
                video.srcObject = stream;
                video.onloadedmetadata = () => {
                    loadingMsg.style.display = 'none';
                    camContainer.style.display = 'block';
                };
            } catch (err) {
                loadingMsg.innerHTML = '<span class="text-danger"><i class="bi bi-camera-video-off fs-1"></i><br>Akses Kamera Ditolak</span>';
            }
        }

        function stopCamera() {
            if (stream) stream.getTracks().forEach(track => track.stop());
        }

        function getGPS() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (pos) => { currentLocation = { lat: pos.coords.latitude.toFixed(5), lng: pos.coords.longitude.toFixed(5) }; },
                    () => { currentLocation = { lat: '-', lng: '-' }; }
                );
            }
        }

        btnSnap.addEventListener('click', function() {
            if (!video.videoWidth) return;
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Watermark Modern
            const gradient = ctx.createLinearGradient(0, canvas.height - 120, 0, canvas.height);
            gradient.addColorStop(0, "transparent");
            gradient.addColorStop(1, "rgba(0,0,0,0.8)");
            ctx.fillStyle = gradient;
            ctx.fillRect(0, canvas.height - 120, canvas.width, 120);

            ctx.fillStyle = "#fff";
            ctx.shadowColor = "rgba(0,0,0,0.5)";
            ctx.shadowBlur = 4;
            ctx.font = "bold 24px sans-serif";
            const dateStr = new Date().toLocaleString('id-ID');
            
            ctx.fillText(dateStr, 30, canvas.height - 80);
            ctx.font = "20px sans-serif";
            ctx.fillText(`📍 ${currentLocation.lat}, ${currentLocation.lng}`, 30, canvas.height - 50);
            ctx.fillText(`👤 ${namaPetugas}`, 30, canvas.height - 20);

            const dataURL = canvas.toDataURL('image/jpeg', 0.85);
            inputBase64.value = dataURL;
            imgPreview.src = dataURL;

            camContainer.style.display = 'none';
            imgPreview.style.display = 'block';
            btnRetake.style.display = 'block';
            
            checkSubmit();
        });

        btnRetake.addEventListener('click', function() { resetCameraUI(); });

        function resetCameraUI() {
            camContainer.style.display = 'block';
            imgPreview.style.display = 'none';
            btnRetake.style.display = 'none';
            inputBase64.value = '';
            checkSubmit();
        }
        
        // Search Filter
        const searchInput = document.getElementById('searchInput');
        if(searchInput) {
            const taskItems = document.querySelectorAll('.task-item');
            searchInput.addEventListener('keyup', (e) => {
                const term = e.target.value.toLowerCase();
                taskItems.forEach(item => item.style.display = item.textContent.toLowerCase().includes(term) ? '' : 'none');
            });
        }

        // --- BARU: POPUP FOTO VIEWER ---
        const viewPhotoModalEl = document.getElementById('modalViewPhoto');
        if(viewPhotoModalEl) {
            const viewPhotoModal = new bootstrap.Modal(viewPhotoModalEl);
            const viewPhotoImg = document.getElementById('img-popup-view');

            document.querySelectorAll('.btn-view-photo').forEach(btn => {
                btn.addEventListener('click', function() {
                    const src = this.getAttribute('data-src');
                    viewPhotoImg.src = src;
                    viewPhotoModal.show();
                });
            });
        }
    });
</script>
@endpush