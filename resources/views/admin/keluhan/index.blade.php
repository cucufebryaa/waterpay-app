@extends('layouts.app')

@section('title', 'Manajemen Keluhan - Admin WaterPay')

@section('content')

<style>
    /* Page Header */
    .page-header {
        padding: 0 0 1rem;
        border-bottom: 1px solid #e3e6f0;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-header h1 i {
        font-size: 1.5rem;
        color: #3b82f6;
    }

    /* Alert Styles */
    .alert {
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        border: none;
        animation: slideDown 0.3s ease;
    }

    .alert-success {
        background: linear-gradient(135deg, #d1f4e0 0%, #e8f9f0 100%);
        border-left: 4px solid #10b981;
        color: #065f46;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee 0%, #fef2f2 100%);
        border-left: 4px solid #ef4444;
        color: #7f1d1d;
    }

    .alert i {
        font-size: 1.25rem;
        margin-right: 0.5rem;
    }

    .alert ul {
        margin-bottom: 0;
        padding-left: 1.5rem;
    }

    /* Table Container */
    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: 1px solid #e8ecf1;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    /* Modern Table */
    .modern-table {
        margin: 0;
        width: 100%;
    }

    .modern-table thead th {
        background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1.25rem;
        border: none;
        white-space: nowrap;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid #f0f2f5;
        transition: all 0.2s ease;
    }

    .modern-table tbody tr:last-child {
        border-bottom: none;
    }

    .modern-table tbody tr:hover {
        background: #f8f9fc;
        transform: translateX(2px);
    }

    .modern-table tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        font-size: 0.9rem;
        border: none;
        color: #2c3e50;
    }

    .modern-table .text-start {
        text-align: left !important;
    }

    .modern-table .text-center {
        text-align: center !important;
    }

    /* Badge Styles */
    .badge-status {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .badge-open {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .badge-delegated {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        color: white;
    }

    .badge-onprogress {
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: white;
    }

    .badge-completed {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .badge-rejected {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .badge-secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.85rem;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .btn-action i {
        font-size: 1rem;
    }

    .btn-action.btn-info {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        color: white;
    }

    .btn-action.btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
    }

    .btn-action.btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .btn-action.btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-action.btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .btn-action.btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .btn-action.btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .btn-action.btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
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

    /* Modal Styling */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        padding: 1.25rem 1.5rem;
        border-radius: 16px 16px 0 0;
        border: none;
    }

    .modal-header.modal-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .modal-header.modal-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
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

    .section-divider {
        font-size: 0.9rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding-bottom: 0.75rem;
        margin-bottom: 1rem;
        border-bottom: 2px solid #e8ecf1;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-divider i {
        color: #3b82f6;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .form-label .text-danger {
        margin-left: 0.25rem;
    }

    .form-control, .form-select {
        border: 2px solid #e8ecf1;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #ef4444;
    }

    .form-control:disabled {
        background-color: #f8f9fc;
        color: #64748b;
        border-color: #e8ecf1;
    }

    .info-card {
        background: #f8f9fc;
        border: 1px solid #e8ecf1;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
    }

    .info-card .form-label {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0.25rem;
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
        background: #f8f9fc;
        color: #64748b;
        border: 2px solid #e8ecf1;
    }

    .modal-footer .btn-secondary:hover {
        background: #e8ecf1;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }

        .modern-table thead th,
        .modern-table tbody td {
            padding: 0.75rem;
            font-size: 0.85rem;
        }

        .action-buttons {
            flex-direction: column;
            width: 100%;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <h1>
        <i class="bi bi-ticket-detailed"></i>
        Manajemen Keluhan Pengguna
    </h1>
</div>

<!-- Alerts -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <strong>Validasi Gagal!</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Table Container -->
<div class="table-container">
    <div class="table-wrapper">
        <table class="modern-table">
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 15%;">Pelanggan</th>
                    <th style="width: 25%;">Keluhan</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 15%;">Petugas Ditugaskan</th>
                    <th style="width: 15%;">Tanggal Masuk</th>
                    <th style="width: 10%;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarKeluhan as $keluhan)
                <tr>
                    <td class="text-center">{{ $keluhan->id }}</td>
                    <td class="text-start">{{ $keluhan->pelanggan->nama ?? 'N/A' }}</td>
                    <td class="text-start">{{ Str::limit($keluhan->keluhan, 80) }}</td>
                    <td class="text-center">
                        @php
                            $statusClass = 'badge-secondary';
                            if ($keluhan->status == 'open') $statusClass = 'badge-open';
                            elseif ($keluhan->status == 'delegated') $statusClass = 'badge-delegated';
                            elseif ($keluhan->status == 'onprogress') $statusClass = 'badge-onprogress';
                            elseif ($keluhan->status == 'completed') $statusClass = 'badge-completed';
                            elseif ($keluhan->status == 'rejected') $statusClass = 'badge-rejected';
                        @endphp
                        <span class="badge-status {{ $statusClass }}">
                            {{ $keluhan->status ?? 'Baru' }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if ($keluhan->petugas)
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <i class="bi bi-person-check-fill text-success"></i>
                                <span>{{ $keluhan->petugas->nama ?? 'N/A' }}</span>
                            </div>
                        @else
                            <span class="text-muted fst-italic">Belum Ditugaskan</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $keluhan->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <div class="action-buttons">
                            <button type="button" 
                                    class="btn-action btn-info" 
                                    onclick="showDetailAlert({{ json_encode($keluhan) }})"
                                    title="Detail Tiket">
                                <i class="bi bi-eye"></i>
                            </button>
                            
                            @if (!in_array($keluhan->status, ['onprogress', 'completed', 'rejected']))
                                <button type="button" 
                                        class="btn-action btn-primary" 
                                        onclick="showDelegasiModal({{ json_encode($keluhan) }})"
                                        title="Delegasikan Petugas">
                                    <i class="bi bi-send"></i>
                                </button>
                            @endif
                            
                            @if ($keluhan->status == 'completed')
                                <button type="button" 
                                        class="btn-action btn-success" 
                                        onclick="showMaintenanceResult({{ json_encode($keluhan) }})"
                                        title="Lihat Bukti Pengerjaan">
                                    <i class="bi bi-images"></i>
                                </button>
                            @endif

                            <button type="button" 
                                    class="btn-action btn-danger" 
                                    onclick="showDeleteAlert('{{ $keluhan->id }}', 'Keluhan #{{ $keluhan->id }}')"
                                    title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        
                        <form id="form-delete-{{ $keluhan->id }}" 
                              action="{{ route('admin.keluhan.destroy', $keluhan->id) }}" 
                              method="POST" 
                              style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-ticket-detailed"></i>
                            <p>Belum ada data keluhan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Delegasi Petugas -->
<div class="modal fade" id="modalDelegasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="formDelegasi" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                
                <div class="modal-header modal-primary">
                    <h5 class="modal-title">
                        <i class="bi bi-send"></i>
                        Delegasi & Status Tiket
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <!-- Info Tiket -->
                    <div class="info-card">
                        <h6 class="section-divider">
                            <i class="bi bi-info-circle"></i>
                            Informasi Tiket
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Pelanggan</label>
                                <input type="text" class="form-control" id="delegasi_pelanggan" readonly disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tiket ID</label>
                                <input type="text" class="form-control" id="delegasi_id" readonly disabled>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Keluhan</label>
                                <textarea class="form-control" id="delegasi_judul" rows="2" readonly disabled></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Form Delegasi -->
                    <div class="row g-4">
                        <div class="col-12">
                            <h6 class="section-divider">
                                <i class="bi bi-person-gear"></i>
                                Tindakan Admin
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label for="delegasi_id_petugas" class="form-label">
                                Tugaskan ke Petugas<span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('id_petugas') is-invalid @enderror" 
                                    id="delegasi_id_petugas" 
                                    name="id_petugas" 
                                    required>
                                <option value="" disabled selected>Pilih Petugas...</option>
                                @foreach ($daftarPetugas as $petugas)
                                    <option value="{{ $petugas->id }}">
                                        {{ $petugas->nama }} ({{ $petugas->jabatan ?? 'Petugas' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_petugas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="delegasi_status" class="form-label">
                                Ubah Status<span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="delegasi_status" 
                                    name="status" 
                                    required>
                                <option value="open">Open</option>
                                <option value="delegated">Delegated</option>
                                <option value="onprogress">On Progress</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        Simpan Penugasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
    /* SweetAlert Custom Styles */
    .swal2-popup.popup-detail {
        border-radius: 16px !important;
        padding: 0 !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15) !important;
        border: none !important;
        width: 800px !important;
        max-width: 90vw !important;
    }

    .swal2-popup.popup-detail .swal2-header {
        background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
        padding: 1.25rem 1.5rem;
        border-radius: 16px 16px 0 0;
        margin: 0;
        border: none;
    }

    .swal2-popup.popup-detail .swal2-title {
        color: white !important;
        font-size: 1.25rem !important;
        font-weight: 600 !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
    }

    .swal2-popup.popup-detail .swal2-close {
        color: white !important;
        font-size: 1.5rem !important;
        transition: all 0.3s ease !important;
    }

    .swal2-popup.popup-detail .swal2-close:hover {
        transform: rotate(90deg) scale(1.1);
        color: #fef3c7 !important;
    }

    .swal2-popup.popup-detail .swal2-html-container {
        padding: 1.5rem !important;
        margin: 0 !important;
    }

    /* Detail Grid Layout */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .detail-card {
        background: #f8f9fc;
        border: 1px solid #e8ecf1;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .detail-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #3b82f6;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e8ecf1;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.625rem 0;
        border-bottom: 1px dashed #e8ecf1;
        transition: all 0.2s ease;
    }

    .detail-item:hover {
        background: rgba(59, 130, 246, 0.05);
        border-radius: 6px;
        padding: 0.625rem 0.5rem;
        margin: 0 -0.5rem;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
        flex: 1;
    }

    .detail-value {
        font-size: 0.9rem;
        color: #2c3e50;
        font-weight: 600;
        text-align: right;
        flex: 1;
    }

    .message-content {
        background: #f8f9fa;
        border: 1px solid #e8ecf1;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 0.5rem;
        font-size: 0.9rem;
        line-height: 1.5;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .maintenance-image {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Delete Confirmation Styling */
    .swal2-popup.popup-delete {
        border-radius: 16px !important;
        padding: 0 !important;
    }

    .swal2-popup.popup-delete .swal2-title {
        color: #dc2626 !important;
        font-size: 1.25rem !important;
        font-weight: 600 !important;
        padding: 1.5rem 1.5rem 0.5rem !important;
    }

    .swal2-popup.popup-delete .swal2-html-container {
        padding: 0.5rem 1.5rem 1rem !important;
        color: #64748b !important;
        font-size: 0.95rem !important;
    }

    .swal2-popup.popup-delete .swal2-actions {
        padding: 0 1.5rem 1.5rem !important;
        gap: 0.75rem !important;
    }

    .swal2-popup.popup-delete .swal2-confirm {
        background: linear-gradient(135deg, #ef4444, #dc2626) !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 0.75rem 1.5rem !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
    }

    .swal2-popup.popup-delete .swal2-cancel {
        background: #f8f9fc !important;
        color: #64748b !important;
        border: 2px solid #e8ecf1 !important;
        border-radius: 10px !important;
        padding: 0.75rem 1.5rem !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
    }

    .swal2-popup.popup-delete .swal2-confirm:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4) !important;
    }

    .swal2-popup.popup-delete .swal2-cancel:hover {
        background: #e8ecf1 !important;
        transform: translateY(-2px) !important;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .swal2-popup.popup-detail {
            width: 95vw !important;
        }
    }
</style>

<script>
    // Detail Alert
    function showDetailAlert(keluhanData) {
        const formatDate = (dateString) => {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        };

        const getStatusBadge = (status) => {
            const statusClass = {
                'open': 'badge-open',
                'delegated': 'badge-delegated', 
                'onprogress': 'badge-onprogress',
                'completed': 'badge-completed',
                'rejected': 'badge-rejected'
            }[status] || 'badge-secondary';
            
            return `<span class="badge-status ${statusClass}">${status}</span>`;
        };

        const detailKeluhan = {
            'ID Tiket': `<code>#${keluhanData.id}</code>`,
            'Pelanggan': `<strong>${keluhanData.pelanggan ? keluhanData.pelanggan.nama : 'N/A'}</strong>`,
            'Tanggal Pengajuan': formatDate(keluhanData.created_at)
        };

        const detailPenugasan = {
            'Status': getStatusBadge(keluhanData.status),
            'Petugas Ditugaskan': keluhanData.petugas ? 
                `<div class="d-flex align-items-center gap-1 justify-content-end">
                    <i class="bi bi-person-check-fill text-success"></i>
                    <span>${keluhanData.petugas.nama}</span>
                 </div>` : 
                '<span class="text-muted fst-italic">Belum Ditugaskan</span>',
            'Terakhir Diperbarui': formatDate(keluhanData.updated_at)
        };

        let htmlContent = `
            <div class="detail-grid">
                <div class="detail-card">
                    <div class="detail-card-title">
                        <i class="bi bi-file-earmark-text"></i>
                        Informasi Keluhan
                    </div>
                    ${createDetailList(detailKeluhan)}
                    <div style="margin-top: 1rem;">
                        <div class="detail-card-title">
                            <i class="bi bi-chat-text"></i>
                            Deskripsi Keluhan
                        </div>
                        <div class="message-content">
                            ${keluhanData.keluhan || 'Tidak ada deskripsi keluhan.'}
                        </div>
                    </div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-card-title">
                        <i class="bi bi-person-check"></i>
                        Status & Penugasan
                    </div>
                    ${createDetailList(detailPenugasan)}
                </div>
            </div>
        `;

        Swal.fire({
            title: `<i class="bi bi-ticket-detailed me-2"></i>Detail Keluhan #${keluhanData.id}`,
            html: htmlContent,
            icon: null,
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: 'popup-detail'
            },
            buttonsStyling: false
        });
    }

    function createDetailList(items) {
        let html = '';
        for (const [key, value] of Object.entries(items)) {
            html += `
                <div class="detail-item">
                    <span class="detail-label">${key}</span>
                    <span class="detail-value">${value}</span>
                </div>
            `;
        }
        return html;
    }

    // Delete Alert
    function showDeleteAlert(keluhanId, keluhanName) {
        Swal.fire({
            title: 'Hapus Keluhan?',
            html: `Anda yakin ingin menghapus <strong>"${keluhanName}"</strong>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan!</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-trash me-2"></i>Ya, Hapus!',
            cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Batal',
            customClass: {
                popup: 'popup-delete',
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary ms-2'
            },
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + keluhanId).submit();
            }
        });
    }

    // Delegasi Modal
    function showDelegasiModal(keluhanData) {
        const form = document.getElementById('formDelegasi');
        const baseUrl = "{{ rtrim(route('admin.keluhan.index'), '/') }}";
        form.action = `${baseUrl}/${keluhanData.id}`;

        // Populate Info Fields
        document.getElementById('delegasi_id').value = keluhanData.id || '';
        document.getElementById('delegasi_pelanggan').value = keluhanData.pelanggan ? keluhanData.pelanggan.nama : 'N/A';
        document.getElementById('delegasi_judul').value = keluhanData.keluhan || '';

        // Populate Form Fields
        document.getElementById('delegasi_id_petugas').value = keluhanData.id_petugas || '';
        document.getElementById('delegasi_status').value = keluhanData.status || 'open';

        // Show Modal
        var myModal = new bootstrap.Modal(document.getElementById('modalDelegasi'));
        myModal.show();
    }

    // Maintenance Result
    function showMaintenanceResult(keluhanData) {
        if (!keluhanData.maintenance) {
            Swal.fire({
                icon: 'info',
                title: 'Data Belum Tersedia',
                text: 'Data pengerjaan tidak ditemukan.',
                customClass: {
                    popup: 'popup-detail'
                }
            });
            return;
        }

        const m = keluhanData.maintenance;
        const fotoUrl = m.foto ? "{{ asset('storage') }}/" + m.foto : null;
        const tglSelesai = new Date(m.tanggal).toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        let htmlContent = `
            <div class="detail-grid" style="grid-template-columns: 1fr;">
                <div class="detail-card">
                    <div class="detail-card-title">
                        <i class="bi bi-clipboard-check"></i>
                        Hasil Pengerjaan
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Penyelesaian</span>
                        <span class="detail-value">${tglSelesai}</span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Keterangan Petugas</span>
                        <span class="detail-value text-start" style="flex: 2;">
                            <div class="message-content bg-success bg-opacity-10 border-success border-opacity-25">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                ${m.deskripsi}
                            </div>
                        </span>
                    </div>
                    
                    <div class="detail-item" style="align-items: flex-start;">
                        <span class="detail-label">Foto Bukti</span>
                        <span class="detail-value">
                            ${fotoUrl ? 
                                `<img src="${fotoUrl}" class="maintenance-image" alt="Bukti Pengerjaan">` : 
                                '<span class="text-muted fst-italic">Tidak ada foto bukti</span>'
                            }
                        </span>
                    </div>
                </div>
            </div>
        `;

        Swal.fire({
            title: `<i class="bi bi-images me-2"></i>Bukti Pengerjaan`,
            html: htmlContent,
            icon: null,
            showCloseButton: true,
            showConfirmButton: true,
            confirmButtonText: '<i class="bi bi-check me-2"></i>Mengerti',
            customClass: {
                popup: 'popup-detail',
                confirmButton: 'btn btn-success'
            },
            buttonsStyling: false
        });
    }

    // Bootstrap Validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
@endpush