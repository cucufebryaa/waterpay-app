@extends('layouts.app')

@section('title', 'Manajemen Pelanggan')

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
        min-height: 100px;
    }

    .password-note {
        background: #eff6ff;
        border: 2px solid #bfdbfe;
        border-radius: 10px;
        padding: 0.875rem;
        margin: 1rem 0;
        font-size: 0.85rem;
        color: #1e40af;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .password-note i {
        font-size: 1rem;
        color: #3b82f6;
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

    /* SweetAlert Custom Styles */
    .swal2-popup.popup-detail {
        border-radius: 16px;
        padding: 0;
        width: 800px;
        max-width: 90%;
    }

    .swal2-popup.popup-detail .swal2-title {
        background: linear-gradient(to right, #f8f9fc, #ffffff);
        padding: 1.25rem 1.5rem;
        border-bottom: 2px solid #e8ecf1;
        margin: 0;
        font-size: 1.25rem;
        color: #2c3e50;
        border-radius: 16px 16px 0 0;
    }

    .swal2-popup.popup-detail .swal2-html-container {
        padding: 1.5rem;
        margin: 0;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .detail-card {
        background: #f8f9fc;
        border: 1px solid #e8ecf1;
        border-radius: 12px;
        padding: 1.25rem;
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
        padding: 0.625rem 0;
        border-bottom: 1px dashed #e8ecf1;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
    }

    .detail-value {
        font-size: 0.9rem;
        color: #2c3e50;
        font-weight: 600;
        text-align: right;
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

        .detail-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <h1>
        <i class="bi bi-person-lines-fill"></i>
        Manajemen Data Pelanggan
    </h1>
    <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#modalTambahPelanggan">
        <i class="bi bi-plus-circle"></i>
        <span>Tambah Pelanggan</span>
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
                    <th style="width: 20%;">Nama Pelanggan</th>
                    <th style="width: 20%;">Golongan Tarif</th>
                    <th style="width: 25%;">Alamat</th>
                    <th style="width: 15%;">No. Handphone</th>
                    <th style="width: 15%;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pelanggan as $item)
                <tr>
                    <td class="text-center">{{ $item->id }}</td>
                    <td class="text-start">
                        <div class="fw-bold">{{ $item->nama }}</div>
                        <small class="text-muted">{{ $item->user->username ?? '-' }}</small>
                    </td>
                    <td>
                        @if($item->kode_product)
                            <span class="badge bg-light text-primary border border-primary">
                                {{ $item->kode_product->kode_product }}
                            </span>
                            <div class="small mt-1 text-muted">{{ \Illuminate\Support\Str::limit($item->kode_product->nama_product, 20) }}</div>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">
                                Belum Diset
                            </span>
                        @endif
                    </td>
                    <td class="text-start">{{ \Illuminate\Support\Str::limit($item->alamat, 30) }}</td>
                    <td class="text-center">{{ $item->no_hp }}</td>
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
                                    onclick="showDeleteAlert('{{ $item->id }}', '{{ $item->nama }}')"
                                    title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        
                        <form id="form-delete-{{ $item->id }}" 
                              action="{{ route('admin.pelanggan.destroy', $item->id) }}" 
                              method="POST" 
                              style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>Belum ada data pelanggan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Pelanggan -->
<div class="modal fade" id="modalTambahPelanggan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.pelanggan.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                
                <div class="modal-header modal-create">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus-fill"></i>
                        Tambah Pelanggan Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <h6 class="section-divider">
                                <i class="bi bi-person-badge"></i>
                                Informasi Pelanggan
                            </h6>
                            
                            <div class="mb-3">
                                <label for="nama" class="form-label">
                                    Nama Lengkap<span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama') }}" 
                                       required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- FIELD BARU: PILIH PRODUK/TARIF -->
                            <div class="mb-3">
                                <label for="id_product" class="form-label">
                                    Golongan Tarif (Produk)<span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('id_product') is-invalid @enderror" 
                                        id="id_product" 
                                        name="id_product" 
                                        required>
                                    <option value="" selected disabled>-- Pilih Tarif --</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}" {{ old('id_product') == $prod->id ? 'selected' : '' }}>
                                            {{ $prod->kode_product }} - {{ $prod->nama_product }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_product')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text small">Wajib dipilih agar tagihan bisa dihitung.</div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">
                                    Alamat<span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                          id="alamat" 
                                          name="alamat" 
                                          rows="3" 
                                          required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="no_hp" class="form-label">
                                    No. Handphone<span class="text-danger">*</span>
                                </label>
                                <input type="tel" 
                                       class="form-control @error('no_hp') is-invalid @enderror" 
                                       id="no_hp" 
                                       name="no_hp" 
                                       value="{{ old('no_hp') }}" 
                                       placeholder="08123456789" 
                                       required>
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <h6 class="section-divider">
                                <i class="bi bi-key"></i>
                                Akun Login
                            </h6>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    Email<span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="pelanggan@email.com" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    Username<span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       id="username" 
                                       name="username" 
                                       value="{{ old('username') }}" 
                                       placeholder="pelanggan123" 
                                       required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nik" class="form-label">
                                    NIK<span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nik') is-invalid @enderror" 
                                       id="nik" 
                                       name="nik" 
                                       value="{{ old('nik') }}" 
                                       placeholder="16 Digit NIK" 
                                       maxlength="16" 
                                       minlength="16" 
                                       required>
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    Password<span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    Konfirmasi Password<span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required>
                            </div>
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

<!-- Modal Edit Pelanggan -->
<div class="modal fade" id="modalEditPelanggan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="formEditPelanggan" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                
                <div class="modal-header modal-edit">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square"></i>
                        Edit Data Pelanggan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <h6 class="section-divider">
                                <i class="bi bi-person-badge"></i>
                                Informasi Pelanggan
                            </h6>

                            <div class="mb-3">
                                <label for="edit_nama" class="form-label">
                                    Nama Lengkap<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="edit_nama" name="nama" required>
                            </div>

                            <!-- FIELD BARU: EDIT PRODUK/TARIF -->
                            <div class="mb-3">
                                <label for="edit_id_product" class="form-label">
                                    Golongan Tarif (Produk)<span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="edit_id_product" name="id_product" required>
                                    <option value="" disabled>-- Pilih Tarif --</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}">
                                            {{ $prod->kode_product }} - {{ $prod->nama_product }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_alamat" class="form-label">
                                    Alamat<span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="edit_no_hp" class="form-label">
                                    No. Handphone<span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control" id="edit_no_hp" name="no_hp" required>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <h6 class="section-divider">
                                <i class="bi bi-key"></i>
                                Akun Login
                            </h6>

                            <div class="mb-3">
                                <label for="edit_email" class="form-label">
                                    Email<span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_username" class="form-label">
                                    Username<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="edit_username" name="username" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_nik" class="form-label">
                                    NIK<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="edit_nik" name="nik" maxlength="16" minlength="16" required>
                            </div>

                            <div class="password-note">
                                <i class="bi bi-info-circle"></i>
                                <span>Kosongkan password jika tidak ingin mengubahnya</span>
                            </div>

                            <div class="mb-3">
                                <label for="edit_password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="edit_password" name="password">
                            </div>

                            <div class="mb-3">
                                <label for="edit_password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation">
                            </div>
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
<script>
    // Detail Alert (DIPERBARUI)
    function showDetailAlert(pelangganData) {
        // Ambil info produk aman (handle null)
        const prodInfo = pelangganData.kode_product 
            ? `${pelangganData.kode_product.nama_product} (${pelangganData.kode_product.kode_product})`
            : '<span class="text-danger">Belum diset</span>';

        const detailPelanggan = {
            'Nama Lengkap': pelangganData.nama || '-',
            'Alamat': pelangganData.alamat || '-',
            'Golongan Tarif': prodInfo, // Info Tarif Ditambahkan
            'No. HP': pelangganData.no_hp || '-',
            'ID Pelanggan': pelangganData.id || '-',
        };

        const detailUser = pelangganData.user ? {
            'Username': pelangganData.user.username || '-',
            'Email': pelangganData.user.email || '-',
            'NIK': pelangganData.user.nik || '-',
            'ID User': pelangganData.user.id || '-'
        } : {
            'Info Akun': '<span style="color: #ef4444;">Data tidak ditemukan</span>'
        };

        let htmlContent = `
            <div class="detail-grid">
                <div class="detail-card">
                    <div class="detail-card-title">
                        <i class="bi bi-person-badge"></i>
                        Informasi Pelanggan
                    </div>
                    ${createDetailList(detailPelanggan)}
                </div>
                <div class="detail-card">
                    <div class="detail-card-title">
                        <i class="bi bi-key"></i>
                        Informasi Akun
                    </div>
                    ${createDetailList(detailUser)}
                </div>
            </div>
        `;

        Swal.fire({
            title: `Detail: ${pelangganData.nama}`,
            html: htmlContent,
            icon: null,
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: 'popup-detail'
            }
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
    function showDeleteAlert(pelangganId, pelangganName) {
        Swal.fire({
            title: 'Hapus Pelanggan?',
            html: `Anda yakin ingin menghapus <strong>"${pelangganName}"</strong>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan!</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-trash me-2"></i>Ya, Hapus!',
            cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Batal',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary ms-2'
            },
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + pelangganId).submit();
            }
        });
    }

    // Edit Modal (DIPERBARUI)
    function showEditModal(pelangganData) {
        if (!pelangganData.user) {
            Swal.fire({
                title: 'Error',
                text: 'Data akun (user) tidak lengkap, tidak dapat mengedit.',
                icon: 'error'
            });
            return;
        }

        const form = document.getElementById('formEditPelanggan');
        const baseUrl = "{{ rtrim(url('/admin/pelanggan'), '/') }}";
        form.action = `${baseUrl}/${pelangganData.id}`;

        // Populate Pelanggan Fields
        document.getElementById('edit_nama').value = pelangganData.nama || '';
        document.getElementById('edit_alamat').value = pelangganData.alamat || '';
        document.getElementById('edit_no_hp').value = pelangganData.no_hp || '';
        
        // SET NILAI DROPDOWN PRODUK
        // Pastikan element ada dan nilai id_product diset
        const selectProduct = document.getElementById('edit_id_product');
        if(selectProduct && pelangganData.id_product) {
            selectProduct.value = pelangganData.id_product;
        }

        // Populate User Fields
        document.getElementById('edit_email').value = pelangganData.user.email || '';
        document.getElementById('edit_username').value = pelangganData.user.username || '';
        document.getElementById('edit_nik').value = pelangganData.user.nik || '';

        // Clear Password Fields
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_password_confirmation').value = '';

        // Show Modal
        var myModal = new bootstrap.Modal(document.getElementById('modalEditPelanggan'));
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

    // Auto-show modal if validation errors (DIPERBARUI untuk field id_product)
    @if ($errors->any())
        @if ($errors->has('nama') || $errors->has('email') || $errors->has('no_hp') || $errors->has('id_product') || $errors->has('password') || $errors->has('username') || $errors->has('nik'))
            @if (old('nama') || old('email') || old('username') || old('nik'))
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('modalTambahPelanggan'));
                myModal.show();
            });
            @endif
        @endif
    @endif

    // Phone & NIK validation
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInputs = ['no_hp', 'edit_no_hp'];
        phoneInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('input', function(e) {
                    let value = this.value.replace(/\D/g, '');
                    if (value.length > 15) value = value.substring(0, 15);
                    this.value = value;
                });
            }
        });

        const nikInputs = ['nik', 'edit_nik'];
        nikInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('input', function(e) {
                    let value = this.value.replace(/\D/g, '');
                    if (value.length > 16) value = value.substring(0, 16);
                    this.value = value;
                });
            }
        });

        function validatePasswordConfirmation(passwordId, confirmId) {
            const password = document.getElementById(passwordId);
            const confirm = document.getElementById(confirmId);

            if (password && confirm) {
                confirm.addEventListener('input', function() {
                    if (this.value && password.value !== this.value) {
                        this.setCustomValidity('Password tidak cocok');
                    } else {
                        this.setCustomValidity('');
                    }
                });
                password.addEventListener('input', function() {
                    if (confirm.value) confirm.dispatchEvent(new Event('input'));
                });
            }
        }
        validatePasswordConfirmation('password', 'password_confirmation');
        validatePasswordConfirmation('edit_password', 'edit_password_confirmation');
    });
</script>
@endpush