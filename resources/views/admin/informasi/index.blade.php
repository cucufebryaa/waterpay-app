@extends('layouts.app')

@section('title', 'Manajemen Informasi - Admin WaterPay')

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

    .btn-add {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .btn-add i {
        font-size: 1.1rem;
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

    .btn-action.btn-warning {
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: white;
    }

    .btn-action.btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
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

    .modal-header.modal-create {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .modal-header.modal-edit {
        background: linear-gradient(135deg, #f59e0b, #f97316);
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

        .btn-add {
            width: 100%;
            justify-content: center;
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
        <i class="bi bi-info-circle"></i>
        Manajemen Data Informasi
    </h1>
    <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#modalTambahInformasi">
        <i class="bi bi-plus-circle"></i>
        <span>Tambah Informasi Baru</span>
    </button>
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
                    <th style="width: 20%;">Tanggal</th>
                    <th style="width: 60%;">Pesan</th>
                    <th style="width: 15%;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarInformasi as $item)
                <tr>
                    <td class="text-center">{{ $item->id }}</td>
                    <td class="text-center">
                        {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d F Y') : 'N/A' }}
                    </td>
                    <td class="text-start">{{ Illuminate\Support\Str::limit($item->pesan, 100) }}</td>
                    <td>
                        <div class="action-buttons">
                            <button type="button" 
                                    class="btn-action btn-info" 
                                    onclick="showDetailAlert({{ json_encode($item) }})"
                                    title="Detail">
                                <i class="bi bi-eye"></i>
                            </button>
                            
                            <button type="button" 
                                    class="btn-action btn-warning" 
                                    onclick="showEditModal({{ json_encode($item) }})"
                                    title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <button type="button" 
                                    class="btn-action btn-danger" 
                                    onclick="showDeleteAlert('{{ $item->id }}', '{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : 'ini' }}')"
                                    title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        
                        <form id="form-delete-{{ $item->id }}" 
                              action="{{ route('admin.informasi.destroy', $item->id) }}" 
                              method="POST" 
                              style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="bi bi-info-circle"></i>
                            <p>Belum ada data informasi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Informasi -->
<div class="modal fade" id="modalTambahInformasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.informasi.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                
                <div class="modal-header modal-create">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle"></i>
                        Tambah Informasi Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <h6 class="section-divider">
                                <i class="bi bi-calendar-event"></i>
                                Informasi Utama
                            </h6>
                        </div>

                        <div class="col-md-12">
                            <label for="tanggal" class="form-label">
                                Tanggal<span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('tanggal') is-invalid @enderror" 
                                   id="tanggal" 
                                   name="tanggal" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}" 
                                   required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="pesan" class="form-label">
                                Pesan Informasi<span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('pesan') is-invalid @enderror" 
                                      id="pesan" 
                                      name="pesan" 
                                      rows="6" 
                                      placeholder="Tulis pesan informasi di sini..."
                                      required>{{ old('pesan') }}</textarea>
                            @error('pesan')
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
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Informasi -->
<div class="modal fade" id="modalEditInformasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="formEditInformasi" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                
                <div class="modal-header modal-edit">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square"></i>
                        Edit Data Informasi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <h6 class="section-divider">
                                <i class="bi bi-calendar-event"></i>
                                Informasi Utama
                            </h6>
                        </div>

                        <div class="col-md-12">
                            <label for="edit_tanggal" class="form-label">
                                Tanggal<span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                        </div>

                        <div class="col-md-12">
                            <label for="edit_pesan" class="form-label">
                                Pesan Informasi<span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" 
                                      id="edit_pesan" 
                                      name="pesan" 
                                      rows="6" 
                                      placeholder="Tulis pesan informasi di sini..."
                                      required></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="bi bi-check-circle me-1"></i>
                        Simpan Perubahan
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
        width: 700px !important;
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
        grid-template-columns: 1fr;
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
    function showDetailAlert(informasiData) {
        const formatDate = (dateString) => {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        };

        const detailInformasi = {
            'Tanggal': `<strong>${formatDate(informasiData.tanggal)}</strong>`,
            'ID Informasi': informasiData.id || '-',
            'ID Perusahaan': informasiData.id_company || '-'
        };

        let htmlContent = `
            <div class="detail-grid">
                <div class="detail-card">
                    <div class="detail-card-title">
                        <i class="bi bi-info-circle"></i>
                        Informasi Detail
                    </div>
                    ${createDetailList(detailInformasi)}
                    
                    <div style="margin-top: 1rem;">
                        <div class="detail-card-title">
                            <i class="bi bi-chat-text"></i>
                            Isi Pesan
                        </div>
                        <div class="message-content">
                            ${informasiData.pesan || 'Tidak ada pesan.'}
                        </div>
                    </div>
                </div>
            </div>
        `;

        Swal.fire({
            title: `<i class="bi bi-info-circle me-2"></i>Detail Informasi`,
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
    function showDeleteAlert(informasiId, informasiTanggal) {
        Swal.fire({
            title: 'Hapus Informasi?',
            html: `Anda yakin ingin menghapus informasi untuk tanggal <strong>"${informasiTanggal}"</strong>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan!</small>`,
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
                document.getElementById('form-delete-' + informasiId).submit();
            }
        });
    }

    // Edit Modal
    function showEditModal(informasiData) {
        const form = document.getElementById('formEditInformasi');
        const baseUrl = "{{ rtrim(route('admin.informasi.index'), '/') }}";
        form.action = `${baseUrl}/${informasiData.id}`;

        // Populate Fields
        document.getElementById('edit_tanggal').value = informasiData.tanggal || '';
        document.getElementById('edit_pesan').value = informasiData.pesan || '';

        // Show Modal
        var myModal = new bootstrap.Modal(document.getElementById('modalEditInformasi'));
        myModal.show();
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

    // Auto-show modal if validation errors
    @if ($errors->any())
        @if ($errors->has('tanggal') || $errors->has('pesan'))
            @if (old('tanggal') || old('pesan'))
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('modalTambahInformasi'));
                myModal.show();
            });
            @endif
        @endif
    @endif

    // Auto-focus on message field when modal opens
    document.addEventListener('DOMContentLoaded', function() {
        const modalTambah = document.getElementById('modalTambahInformasi');
        if (modalTambah) {
            modalTambah.addEventListener('shown.bs.modal', function () {
                document.getElementById('pesan').focus();
            });
        }

        const modalEdit = document.getElementById('modalEditInformasi');
        if (modalEdit) {
            modalEdit.addEventListener('shown.bs.modal', function () {
                document.getElementById('edit_pesan').focus();
            });
        }
    });
</script>
@endpush