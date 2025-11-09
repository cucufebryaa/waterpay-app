@extends('layouts.app') {{-- Menyesuaikan dengan layout Anda --}}

@section('title', 'Manajemen Informasi - Admin WaterPay')

@section('content')
<div class="container-fluid px-4">
    
    {{-- Header Page --}}
    <div class="row g-3 my-2">
        <div class="col-12">
            <div class="p-3 bg-white shadow-sm rounded-3 text-center">
                <h3 class="fw-bold mb-0 text-primary">MANAJEMEN DATA INFORMASI</h3>
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
                
                {{-- Tombol Tambah Informasi (Warna Hijau) --}}
                <div class="d-flex justify-content-end mb-4">
                    <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahInformasi">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Informasi Baru
                    </button>
                </div>
                
                {{-- TABEL DATA Informasi --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-primary text-white">
                            <tr class="text-center align-middle">
                                <th style="width: 5%;">ID</th>
                                <th style="width: 20%;">Tanggal</th>
                                <th style="width: 60%;">Pesan</th>
                                <th style="width: 15%;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daftarInformasi as $item)
                            <tr class="align-middle">
                                <td class="text-center">{{ $item->id }}</td>
                                <td class="text-center">
                                    {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d F Y') : 'N/A' }}
                                </td> 
                                <td class="text-start">{{ $item->pesan }}</td>
                                <td class="text-center">
                                    <div class="d-flex flex-nowrap justify-content-center">
                                        
                                        {{-- Tombol Detail --}}
                                        <button type="button" class="btn btn-sm btn-info me-1" title="Detail"
                                                onclick="showDetailAlert({{ json_encode($item) }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        {{-- TOMBOL EDIT --}}
                                        <button type="button" class="btn btn-sm btn-warning me-1" title="Edit"
                                                onclick="showEditModal({{ json_encode($item) }})">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                                onclick="showDeleteAlert('{{ $item->id }}', '{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : 'ini' }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    {{-- Form Hapus (Tersembunyi) --}}
                                    <form id="form-delete-{{ $item->id }}" 
                                          action="{{ route('admin.informasi.destroy', $item->id) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada data Informasi.</td>
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
MODAL 1: TAMBAH INFORMASI (CREATE)
[FIXED] ID dan aria-labelledby disesuaikan
==================================================================================
--}}
<div class="modal fade" id="modalTambahInformasi" tabindex="-1" aria-labelledby="modalTambahInformasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            
            <form action="{{ route('admin.informasi.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                
                <div class="modal-header bg-success text-white p-4">
                    <h5 class="modal-title" id="modalTambahInformasiLabel"><i class="bi bi-info-circle me-2"></i>Form Tambah Informasi Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="row g-3">
                        {{-- Field Form --}}
                        <div class="col-md-12">
                            <label for="tanggal" class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="pesan" class="form-label fw-bold">Pesan Informasi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('pesan') is-invalid @enderror" id="pesan" name="pesan" rows="5" required>{{ old('pesan') }}</textarea>
                            @error('pesan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
MODAL 2: EDIT INFORMASI (UPDATE)
[FIXED] ID dan aria-labelledby disesuaikan
==================================================================================
--}}
<div class="modal fade" id="modalEditInformasi" tabindex="-1" aria-labelledby="modalEditInformasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            
            {{-- Form action akan di-set oleh JS, method POST, tapi kita tambahkan @method('PUT') --}}
            <form action="" method="POST" id="formEditInformasi" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                
                <div class="modal-header bg-warning text-dark p-4">
                    <h5 class="modal-title" id="modalEditInformasiLabel"><i class="bi bi-pencil-square me-2"></i>Form Edit Informasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                   <div class="row g-3">
                        {{-- Field Form --}}
                        <div class="col-md-12">
                            <label for="edit_tanggal" class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                        </div>
                        <div class="col-md-12">
                            <label for="edit_pesan" class="form-label fw-bold">Pesan Informasi <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="edit_pesan" name="pesan" rows="5" required></textarea>
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
(Ini SAMA PERSIS dengan yang Anda berikan, tidak perlu diubah)
==================================================================================
--}}
<style>
    /* 1. CSS UTAMA POPUP (SAMA) */
    .popup-profesional {
        border-radius: 0.75rem !important; padding: 0 !important;
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important; border: 0 !important;
    }
    .popup-profesional .swal2-header {
        background-color: #f8f9fa; padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #dee2e6; border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem; margin: 0; width: 100%; box-sizing: border-box;
    }
    .popup-profesional.swal2-no-icon .swal2-title {
        font-size: 1.25rem; font-weight: 600; color: #0d6efd; 
        margin: 0; text-align: left;
    }
    .popup-profesional.swal2-no-icon .swal2-content {
        padding: 1.5rem !important; text-align: left !important; font-size: 1rem;
    }
    .popup-profesional .swal2-close {
        width: 32px; height: 32px; border-radius: 50%; border: none;
        background-color: #f8d7da; color: #b02a37; font-size: 2.2rem; line-height: 30px;
        transition: all 0.2s ease-in-out;
    }
    .popup-profesional .swal2-close:hover {
        background-color: #dc3545; color: #ffffff;
        transform: scale(1.1) rotate(90deg); box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
    }
    /* 2. CSS CARD INFO (SAMA) */
    .info-card {
        background-color: #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0; border-radius: 0.5rem; padding: 1.25rem;
        height: 100%;
    }
    .info-card h5 {
        font-weight: 600; color: #343a40; padding-bottom: 0.5rem;
        margin-bottom: 1rem; border-bottom: 1px solid #f0f0f0;
    }
    /* 3. CSS KEY-VALUE (SAMA) */
    .detail-list { display: flex; flex-direction: column; gap: 0.75rem; }
    .detail-item {
        display: flex; justify-content: space-between; align-items: center;
        padding-bottom: 0.75rem; border-bottom: 1px dashed #e9ecef;
    }
    .detail-item:last-child { border-bottom: 0; }
    .detail-key { font-size: 0.9em; color: #6c757d; text-align: left; padding-right: 1rem; }
    .detail-value { font-size: 0.95em; font-weight: 600; color: #212529; text-align: right; }
</style>


{{-- 
==================================================================================
JAVASCRIPT UNTUK POPUP & MODAL
(Disesuaikan untuk 'Informasi')
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
     * FUNGSI POPUP DETAIL (Layout 1 Kolom untuk Informasi)
     */
    function showDetailAlert(informasiData) {
        
        // Objek untuk detail informasi
        const detailInformasi = {
            'Tanggal': `<strong>${informasiData.tanggal}</strong>`,
            'Pesan': `<div class="text-start" style="white-space: pre-wrap;">${informasiData.pesan}</div>`,
            'ID Perusahaan': informasiData.id_company,
            'ID Informasi': informasiData.id,
        };
        
        // Buat HTML (layout 1 kolom sederhana)
        let htmlContent = `
            <div class="info-card">
                ${createDetailList(detailInformasi, ['pesan'])} 
                
                {{-- Pesan ditampilkan terpisah di bawah agar rapi --}}
                <h5 class="mt-4">Isi Pesan:</h5>
                <p style="white-space: pre-wrap; font-size: 0.95em; background-color: #f8f9fa; padding: 1rem; border-radius: 0.5rem;">${informasiData.pesan || 'Tidak ada pesan.'}</p>
            </div>
        `;

        Swal.fire({
            title: `<i class="bi bi-info-circle me-2"></i> Detail Informasi: ${informasiData.tanggal}`,
            html: htmlContent,
            icon: null, // <-- Ini menghasilkan class .swal2-no-icon
            width: '600px', // Popup lebih sempit
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
     * FUNGSI POPUP DELETE (Disesuaikan untuk Informasi)
     * [FIXED] Parameter dan teks disesuaikan
     */
    function showDeleteAlert(informasiId, informasiTanggal) {
        Swal.fire({
            title: 'Hapus Data Informasi?',
            text: `Anda yakin ingin menghapus informasi untuk tanggal "${informasiTanggal}"? Tindakan ini tidak dapat dibatalkan!`,
            icon: 'warning', 
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
                document.getElementById('form-delete-' + informasiId).submit();
            }
        });
    }

    /**
     * FUNGSI MODAL EDIT (Disesuaikan untuk Informasi)
     * [FIXED] Parameter, ID form, dan field disesuaikan
     */
    function showEditModal(informasiData) {
        
        // 1. Set Form Action
        const form = document.getElementById('formEditInformasi');
        // 'informasi.index' akan menghasilkan '.../informasi'. Kita tambahkan '/' dan id
        const baseUrl = "{{ rtrim(route('admin.informasi.index'), '/') }}"; 
        form.action = `${baseUrl}/${informasiData.id}`;
        
        // 2. Populate Fields Informasi
        document.getElementById('edit_tanggal').value = informasiData.tanggal || '';
        document.getElementById('edit_pesan').value = informasiData.pesan || '';
        
        // 3. Tampilkan Modal
        var myModal = new bootstrap.Modal(document.getElementById('modalEditInformasi'));
        myModal.show();
    }


    /* ==================================================================================
    SKRIP VALIDASI & MODAL
    ==================================================================================
    */
    
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

    @if ($errors->any())
        {{-- [FIXED] Cek error 'tanggal' atau 'pesan' --}}
        @if ($errors->has('tanggal') || $errors->has('pesan'))
            
            {{-- [FIXED] Cek 'old()' 'tanggal' atau 'pesan' --}}
            @if (old('tanggal') || old('pesan'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    {{-- [FIXED] Tampilkan modal 'modalTambahInformasi' --}}
                    var myModal = new bootstrap.Modal(document.getElementById('modalTambahInformasi'));
                    myModal.show();
                });
            </script>
            @endif
        @endif
    @endif

</script>
@endpush