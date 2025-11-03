@extends('layouts.app')

@section('title', 'Manajemen Petugas')

@section('content')
<div class="container-fluid px-4">
    
    {{-- Header Page --}}
    <div class="row g-3 my-2">
        <div class="col-12">
            <div class="p-3 bg-white shadow-sm rounded-3 text-center">
                <h3 class="fw-bold mb-0 text-primary">MANAJEMEN DATA PETUGAS</h3>
            </div>
        </div>
    </div>
    
    {{-- Notifikasi Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    {{-- Menampilkan SEMUA error validasi (untuk 'store' dan 'update') --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> 
            <strong>Validasi Gagal!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    {{-- Kotak Utama untuk Konten --}}
    <div class="row g-3 my-4">
        <div class="col-12">
            <div class="p-4 bg-white shadow-sm rounded-3">
                
                {{-- Tombol Tambah Petugas (Warna Hijau) --}}
                <div class="d-flex justify-content-end mb-4">
                    <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPetugas">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Petugas Baru
                    </button>
                </div>
                
                {{-- TABEL DATA PETUGAS --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-primary text-white">
                            <tr class="text-center align-middle">
                                <th style="width: 5%;">ID</th>
                                <th style="width: 25%;">Nama Petugas</th>
                                <th style="width: 35%;">Alamat</th>
                                <th style="width: 20%;">No. Handphone</th>
                                <th style="width: 15%;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($petugas as $item)
                            <tr class="text-center align-middle">
                                <td>{{ $item->id }}</td>
                                <td class="text-start">{{ $item->nama }}</td> 
                                <td class="text-start">{{ $item->alamat }}</td>
                                <td>{{ $item->no_hp }}</td>
                                <td class="text-center">
                                    <div class="d-flex flex-nowrap justify-content-center">
                                        
                                        {{-- Tombol Detail - Panggil JS (Pastikan $item sdh include 'user') --}}
                                        <button type="button" class="btn btn-sm btn-info me-1" title="Detail"
                                                onclick="showDetailAlert({{ json_encode($item) }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        {{-- TOMBOL EDIT --}}
                                        <button type="button" class="btn btn-sm btn-warning me-1" title="Edit"
                                                onclick="showEditModal({{ json_encode($item) }})">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                                onclick="showDeleteAlert('{{ $item->id }}', '{{ $item->nama }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <form id="form-delete-{{ $item->id }}" 
                                          action="{{ route('admin.petugas.destroy', $item->id) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data petugas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- 
==================================================================================
MODAL 1: TAMBAH PETUGAS (CREATE)
==================================================================================
--}}
<div class="modal fade" id="modalTambahPetugas" tabindex="-1" aria-labelledby="modalTambahPetugasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            
            <form action="{{ route('admin.petugas.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                
                <div class="modal-header bg-success text-white p-4">
                    <h5 class="modal-title" id="modalTambahPetugasLabel"><i class="bi bi-person-plus-fill me-2"></i>Form Tambah Petugas Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="row g-3">
                        {{-- Kolom Kiri: Info Petugas --}}
                        <div class="col-md-6">
                            <h6 class="text-muted border-bottom pb-2 mb-3">Informasi Petugas</h6>
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-bold">Nama Lengkap Petugas <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-bold">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="no_hp" class="form-label fw-bold">No. Handphone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789" required>
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Kolom Kanan: Info Akun Login --}}
                        <div class="col-md-6">
                            <h6 class="text-muted border-bottom pb-2 mb-3">Akun Login (User)</h6>
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="petugas@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="petugas_unik_123" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nik" class="form-label fw-bold">NIK <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}" placeholder="16 Digit NIK KTP" required maxlength="16" minlength="16">
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer p-3 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save me-2"></i>Simpan Data</button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- 
==================================================================================
MODAL 2: EDIT PETUGAS (UPDATE)
==================================================================================
--}}
<div class="modal fade" id="modalEditPetugas" tabindex="-1" aria-labelledby="modalEditPetugasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            
            {{-- Form action akan di-set oleh JS, method POST, tapi kita tambahkan @method('PUT') --}}
            <form action="" method="POST" id="formEditPetugas" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                
                <div class="modal-header bg-warning text-dark p-4">
                    <h5 class="modal-title" id="modalEditPetugasLabel"><i class="bi bi-pencil-square me-2"></i>Form Edit Petugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="row g-3">
                        {{-- Kolom Kiri: Info Petugas --}}
                        <div class="col-md-6">
                            <h6 class="text-muted border-bottom pb-2 mb-3">Informasi Petugas</h6>
                            <div class="mb-3">
                                <label for="edit_nama" class="form-label fw-bold">Nama Lengkap Petugas <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_nama" name="nama" required>
                                {{-- Kita tidak bisa menampilkan error validasi 'edit' di sini tanpa AJAX --}}
                            </div>
                            <div class="mb-3">
                                <label for="edit_alamat" class="form-label fw-bold">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="edit_no_hp" class="form-label fw-bold">No. Handphone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="edit_no_hp" name="no_hp" required>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Info Akun Login --}}
                        <div class="col-md-6">
                            <h6 class="text-muted border-bottom pb-2 mb-3">Akun Login (User)</h6>
                            <div class="mb-3">
                                <label for="edit_email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_username" class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_nik" class="form-label fw-bold">NIK <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_nik" name="nik" required maxlength="16" minlength="16">
                            </div>
                            
                            <hr class="my-3">
                            <p class="small text-muted mb-2">Kosongkan password jika tidak ingin mengubahnya.</p>
                            
                            <div class="mb-3">
                                <label for="edit_password" class="form-label fw-bold">Password Baru</label>
                                <input type="password" class="form-control" id="edit_password" name="password" placeholder="Isi password baru">
                            </div>
                            <div class="mb-3">
                                <label for="edit_password_confirmation" class="form-label fw-bold">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation" placeholder="Ketik ulang password baru">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer p-3 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark"><i class="bi bi-save me-2"></i>Simpan Perubahan</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Memuat SweetAlert2 (pastikan sudah ada di layout) --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

{{-- 
==================================================================================
CSS UNTUK POPUP
==================================================================================
--}}
<style>
    /* 1. CSS UTAMA POPUP (SAMA) */
    .popup-profesional {
        border-radius: 0.75rem !important;
        padding: 0 !important;
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
        border: 0 !important;
    }
    .popup-profesional .swal2-header {
        background-color: #f8f9fa;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #dee2e6;
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
        margin: 0;
        width: 100%;
        box-sizing: border-box;
    }

    /* Perbaikan: HANYA Terapkan text-align: left ke popup DETAIL (no-icon) */
    .popup-profesional.swal2-no-icon .swal2-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #0d6efd; 
        margin: 0;
        text-align: left; /* Rata kiri HANYA untuk detail */
    }

    /* Perbaikan: HANYA Terapkan text-align: left ke popup DETAIL (no-icon) */
    .popup-profesional.swal2-no-icon .swal2-content {
        padding: 1.5rem !important;
        text-align: left !important; /* Rata kiri HANYA untuk detail */
        font-size: 1rem;
    }


    .popup-profesional .swal2-close {
        width: 32px; height: 32px; border-radius: 50%; border: none;
        background-color: #f8d7da; color: #b02a37;
        font-size: 2.2rem; line-height: 30px;
        transition: all 0.2s ease-in-out;
    }
    .popup-profesional .swal2-close:hover {
        background-color: #dc3545; color: #ffffff;
        transform: scale(1.1) rotate(90deg);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
    }

    /* 2. CSS CARD INFO (SAMA) */
    .info-card {
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
        border-radius: 0.5rem;
        padding: 1.25rem;
        height: 100%;
    }
    .info-card h5 {
        font-weight: 600;
        color: #343a40;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    /* 3. CSS KEY-VALUE (SAMA) */
    .detail-list { display: flex; flex-direction: column; gap: 0.75rem; }
    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 0.75rem;
        border-bottom: 1px dashed #e9ecef;
    }
    .detail-item:last-child { border-bottom: 0; }
    .detail-key { font-size: 0.9em; color: #6c757d; text-align: left; padding-right: 1rem; }
    .detail-value { font-size: 0.95em; font-weight: 600; color: #212529; text-align: right; }
    
    /* 4. CSS BARU: 2-COLUMN LAYOUT UNTUK POPUP DETAIL */
    .detail-popup-content {
        display: flex;
        gap: 1.5rem; /* Jarak antar kolom */
    }
    .detail-popup-column {
        flex: 1; /* Setiap kolom mengambil 50% */
        min-width: 0; /* Mencegah overflow */
    }
    /* Buat 1 kolom di layar kecil */
    @media (max-width: 768px) {
        .detail-popup-content {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>


{{-- 
==================================================================================
JAVASCRIPT UNTUK POPUP & MODAL
==================================================================================
--}}
<script>
    /**
     * FUNGSI HELPER (SAMA)
     * Membuat list data Key-Value
     */
    function createDetailList(items, skipKeys = []) {
        let listHtml = '<div class="detail-list">';
        let hasData = false;
        for (const [key, value] of Object.entries(items)) {
            if (!skipKeys.includes(key) && value !== null && value !== '') {
                hasData = true;
                // Key sudah diformat dari pemanggilan fungsi
                listHtml += `
                    <div class="detail-item">
                        <span class="detail-key">${key}</span>
                        <span class="detail-value">${value}</span>
                    </div>
                `;
            }
        }
        if (!hasData) {
            listHtml += '<p class="text-muted small m-0">Tidak ada data detail.</p>';
        }
        listHtml += '</div>';
        return listHtml;
    }

    /**
     * FUNGSI POPUP DETAIL (Layout 2 Kolom)
     */
    function showDetailAlert(petugasData) {
        
        // Objek untuk kolom kiri (Info Petugas)
        const detailPetugas = {
            'Nama Lengkap': petugasData.nama,
            'Alamat': petugasData.alamat,
            'No. Handphone': petugasData.no_hp,
            'ID Petugas': petugasData.id,
            'ID Perusahaan': petugasData.id_company
        };
        
        // Objek untuk kolom kanan (Info Akun)
        const detailUser = {};
        if (petugasData.user) {
            detailUser['Username'] = petugasData.user.username;
            detailUser['Email'] = petugasData.user.email;
            detailUser['NIK'] = petugasData.user.nik;
            detailUser['ID User'] = petugasData.user.id;
        } else {
            detailUser['Info Akun'] = '<span class="text-danger">Data akun login tidak ditemukan.</span>';
        }

        // Buat HTML dengan 2 kolom
        let htmlContent = `
            <div class="detail-popup-content">
                <div class="detail-popup-column">
                    <div class="info-card">
                        <h5 class="mb-3"><i class="bi bi-person-badge me-2"></i>Info Petugas</h5>
                        ${createDetailList(detailPetugas)}
                    </div>
                </div>
                <div class="detail-popup-column">
                    <div class="info-card">
                        <h5 class="mb-3"><i class="bi bi-key-fill me-2"></i>Info Akun Login</h5>
                        ${createDetailList(detailUser)}
                    </div>
                </div>
            </div>
        `;

        Swal.fire({
            title: `<i class="bi bi-person-vcard me-2"></i> Detail: ${petugasData.nama}`,
            html: htmlContent,
            icon: null, // <-- Ini menghasilkan class .swal2-no-icon
            width: '800px', // Popup lebih lebar untuk 2 kolom
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: 'popup-profesional',
                header: 'popup-profesional-header',
                title: 'swal2-title',
                content: 'swal2-content'
            }
        });
    }

    /**
     * FUNGSI POPUP DELETE (Sudah Rapi)
     */
    function showDeleteAlert(petugasId, petugasName) {
        Swal.fire({
            title: 'Hapus Petugas?',
            text: `Anda yakin ingin menghapus "${petugasName}"? Tindakan ini tidak dapat dibatalkan!`,
            icon: 'warning', // <-- Ini TIDAK menghasilkan .swal2-no-icon
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'popup-profesional',
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-outline-secondary ms-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + petugasId).submit();
            }
        });
    }

    /**
     * FUNGSI MODAL EDIT
     * Mengisi dan menampilkan modal edit
     */
    function showEditModal(petugasData) {
        // Cek apakah data user (relasi) ada
        if (!petugasData.user) {
            Swal.fire({
                title: 'Error', 
                text: 'Data akun (user) tidak lengkap, tidak dapat mengedit.', 
                icon: 'error',
                customClass: { popup: 'popup-profesional' }
            });
            return;
        }
        
        // 1. Set Form Action
        const form = document.getElementById('formEditPetugas');
        // Dapatkan URL dasar dari helper Laravel (pastikan tidak ada / di akhir)
        const baseUrl = "{{ rtrim(url('/admin/petugas'), '/') }}";
        form.action = `${baseUrl}/${petugasData.id}`;
        
        // 2. Populate Fields Petugas
        document.getElementById('edit_nama').value = petugasData.nama || '';
        document.getElementById('edit_alamat').value = petugasData.alamat || '';
        document.getElementById('edit_no_hp').value = petugasData.no_hp || '';
        
        // 3. Populate Fields User
        document.getElementById('edit_email').value = petugasData.user.email || '';
        document.getElementById('edit_username').value = petugasData.user.username || '';
        document.getElementById('edit_nik').value = petugasData.user.nik || '';
        
        // 4. Kosongkan field password
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_password_confirmation').value = '';
        
        // 5. Tampilkan Modal
        var myModal = new bootstrap.Modal(document.getElementById('modalEditPetugas'));
        myModal.show();
    }


    {{-- 
    ==================================================================================
    SKRIP VALIDASI & MODAL
    ==================================================================================
    --}}
    
    // Script standar Bootstrap Validation
    (function () {
      'use strict'
      var forms = document.querySelectorAll('.needs-validation')
      Array.prototype.slice.call(forms)
        .forEach(function (form) {
          form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }
            form.classList.add('was-validated')
          }, false)
        })
    })()

    // Script untuk menampilkan kembali modal CREATE jika ada error validasi
    @if ($errors->any())
        {{-- Hanya tampilkan modal jika error berasal dari field form TAMBAH --}}
        @if ($errors->has('nama') || $errors->has('email') || $errors->has('no_hp') || $errors->has('password') || $errors->has('username') || $errors->has('nik'))
            {{-- Cek juga apakah ini error 'unique' (yg bisa jadi dari 'edit')
                 Kita asumsikan jika ada 'old()' data, ini adalah form 'create' --}}
            @if (old('nama') || old('email') || old('username') || old('nik'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var myModal = new bootstrap.Modal(document.getElementById('modalTambahPetugas'));
                    myModal.show();
                });
            </script>
            @endif
        @endif
    @endif

</script>
@endpush

