@extends('layouts.app')

@section('title', 'Task Maintenance')

@section('content')

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem 0 1.5rem;
        border-bottom: 1px solid #e3e6f0;
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .task-counter {
        background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 20px;
        font-size: 0.95rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .task-counter i {
        font-size: 1.1rem;
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

    .modern-section:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .section-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(to right, #f8f9fc, #ffffff);
        border-bottom: 1px solid #e8ecf1;
        display: flex;
        align-items: center;
        justify-content: space-between;
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

    /* Task Table Styling */
    .task-table {
        margin: 0;
    }

    .task-table thead th {
        background: #f8f9fc;
        border: none;
        color: #5a6c7d;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1.25rem;
        white-space: nowrap;
    }

    .task-table tbody tr {
        border-bottom: 1px solid #f0f2f5;
        transition: all 0.2s ease;
    }

    .task-table tbody tr:last-child {
        border-bottom: none;
    }

    .task-table tbody tr:hover {
        background: #f8f9fc;
        transform: translateX(4px);
    }

    .task-table tbody td {
        padding: 1.25rem;
        vertical-align: middle;
        font-size: 0.9rem;
        border: none;
    }

    .customer-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .customer-name {
        font-weight: 600;
        color: #2c3e50;
    }

    .customer-address {
        font-size: 0.8rem;
        color: #9ca3af;
    }

    .task-date {
        color: #64748b;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .task-date i {
        font-size: 0.85rem;
        color: #3b82f6;
    }

    .complaint-text {
        color: #5a6c7d;
        line-height: 1.5;
    }

    /* Status Badge Modern */
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

    .status-badge i {
        font-size: 0.8rem;
    }

    .status-badge.status-delegated {
        background: #fee;
        color: #dc3545;
    }

    .status-badge.status-onprogress {
        background: #fff3cd;
        color: #f59e0b;
    }

    /* Action Buttons */
    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        white-space: nowrap;
    }

    .btn-action i {
        font-size: 1rem;
    }

    .btn-action.btn-start {
        background: linear-gradient(135deg, #3b82f6, #06b6d4);
        color: white;
    }

    .btn-action.btn-start:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }

    .btn-action.btn-complete {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .btn-action.btn-complete:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
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
        margin-bottom: 1.5rem;
        color: #10b981;
    }

    .empty-state h5 {
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        font-size: 0.95rem;
        margin: 0;
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

    .modal-title i {
        font-size: 1.25rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .info-box {
        background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);
        border: none;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #3b82f6;
    }

    .info-box small {
        color: #0369a1;
        font-size: 0.9rem;
    }

    .info-box strong {
        color: #1e40af;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label i {
        color: #3b82f6;
    }

    .form-control {
        border: 2px solid #e8ecf1;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .form-text {
        color: #9ca3af;
        font-size: 0.8rem;
        margin-top: 0.5rem;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    .modal-footer {
        border: none;
        padding: 1rem 1.5rem 1.5rem;
        gap: 0.75rem;
    }

    .modal-footer .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .modal-footer .btn-secondary {
        background: #e8ecf1;
        color: #5a6c7d;
        border: none;
    }

    .modal-footer .btn-secondary:hover {
        background: #cbd5e1;
        transform: translateY(-2px);
    }

    .modal-footer .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
    }

    .modal-footer .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .task-table {
            font-size: 0.85rem;
        }

        .task-table thead th,
        .task-table tbody td {
            padding: 0.875rem 0.75rem;
        }

        .btn-action {
            font-size: 0.8rem;
            padding: 0.5rem 0.875rem;
        }
    }

    @media (max-width: 768px) {
        .task-table thead th:nth-child(2),
        .task-table tbody td:nth-child(2) {
            display: none;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-tools me-2"></i>Task Maintenance</h1>
    <div class="task-counter">
        <i class="bi bi-clipboard-check"></i>
        <span>{{ count($tasks) }} Tugas Aktif</span>
    </div>
</div>

<!-- Main Content Section -->
<div class="modern-section">
    <div class="section-header">
        <h5>
            <i class="bi bi-list-task"></i>
            Daftar Pekerjaan Saya
        </h5>
    </div>
    
    <div class="section-content p-0">
        <div class="table-responsive">
            <table class="table task-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Tanggal</th>
                        <th style="width: 25%;">Pelanggan</th>
                        <th style="width: 30%;">Masalah</th>
                        <th class="text-center" style="width: 10%;">Status</th>
                        <th class="text-center" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $index => $task)
                    <tr>
                        <td class="text-center">
                            <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                        </td>
                        <td>
                            <div class="task-date">
                                <i class="bi bi-calendar3"></i>
                                {{ \Carbon\Carbon::parse($task->created_at)->format('d M Y H:i') }}
                            </div>
                        </td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-name">{{ $task->pelanggan->nama ?? 'Unknown' }}</span>
                                <span class="customer-address">
                                    <i class="bi bi-geo-alt"></i>
                                    {{ $task->pelanggan->alamat ?? '-' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="complaint-text">{{ Illuminate\Support\Str::limit($task->keluhan, 60) }}</div>
                        </td>
                        <td class="text-center">
                            @if($task->status == 'delegated')
                                <span class="status-badge status-delegated">
                                    <i class="bi bi-arrow-right-circle"></i>
                                    Delegated
                                </span>
                            @elseif($task->status == 'onprogress')
                                <span class="status-badge status-onprogress">
                                    <i class="bi bi-hourglass-split"></i>
                                    On Progress
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($task->status == 'delegated')
                                <form action="{{ route('petugas.maintenance.start', $task->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-action btn-start">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span>Mulai</span>
                                    </button>
                                </form>
                            @elseif($task->status == 'onprogress')
                                <button type="button" 
                                        class="btn btn-action btn-complete"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalLapor"
                                        data-id="{{ $task->id }}"
                                        data-pelanggan="{{ $task->pelanggan->nama }}">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Selesai</span>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-check2-all"></i>
                                <h5>Semua Tugas Selesai!</h5>
                                <p>Tidak ada tugas maintenance yang perlu dikerjakan saat ini. Kerja bagus!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Lapor Penyelesaian -->
<div class="modal fade" id="modalLapor" tabindex="-1" aria-labelledby="modalLaporLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLaporLabel">
                    <i class="bi bi-file-earmark-check"></i>
                    Lapor Penyelesaian Tugas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('petugas.maintenance.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="keluhan_id" id="keluhan_id">

                    <div class="info-box">
                        <small>
                            <i class="bi bi-info-circle me-1"></i>
                            Melaporkan penyelesaian untuk pelanggan: 
                            <strong id="nama_pelanggan_modal">-</strong>
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">
                            <i class="bi bi-camera"></i>
                            Foto Bukti Pengerjaan
                        </label>
                        <input type="file" 
                               class="form-control" 
                               id="foto" 
                               name="foto" 
                               required 
                               accept="image/*">
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Format: JPG, JPEG, PNG. Maksimal 2MB
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">
                            <i class="bi bi-card-text"></i>
                            Keterangan Pengerjaan
                        </label>
                        <textarea class="form-control" 
                                  id="deskripsi" 
                                  name="deskripsi" 
                                  rows="4" 
                                  required 
                                  placeholder="Jelaskan apa yang telah diperbaiki atau dikerjakan..."></textarea>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Berikan detail lengkap tentang pekerjaan yang telah diselesaikan
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-send-check me-1"></i>
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalLapor = document.getElementById('modalLapor');
        
        if (modalLapor) {
            modalLapor.addEventListener('show.bs.modal', function (event) {
                // Tombol yang memicu modal
                var button = event.relatedTarget;
                
                // Ambil data dari atribut data-*
                var idKeluhan = button.getAttribute('data-id');
                var namaPelanggan = button.getAttribute('data-pelanggan');

                // Isi value ke dalam input form modal
                var inputId = modalLapor.querySelector('#keluhan_id');
                var textNama = modalLapor.querySelector('#nama_pelanggan_modal');

                if (inputId) inputId.value = idKeluhan;
                if (textNama) textNama.textContent = namaPelanggan;
            });

            // Reset form when modal is closed
            modalLapor.addEventListener('hidden.bs.modal', function () {
                var form = modalLapor.querySelector('form');
                if (form) form.reset();
            });
        }

        // Preview image before upload
        var fotoInput = document.getElementById('foto');
        if (fotoInput) {
            fotoInput.addEventListener('change', function(e) {
                var file = e.target.files[0];
                if (file) {
                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar! Maksimal 2MB');
                        this.value = '';
                        return;
                    }
                    
                    // Validate file type
                    var validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!validTypes.includes(file.type)) {
                        alert('Format file tidak valid! Gunakan JPG, JPEG, atau PNG');
                        this.value = '';
                        return;
                    }
                }
            });
        }
    });
</script>
@endpush