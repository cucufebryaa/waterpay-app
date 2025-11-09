@extends('layouts.app') {{-- Menyesuaikan dengan layout Anda --}}

@section('title', 'Manajemen Harga - Admin WaterPay')

@section('content')
<div class="container-fluid px-4">
    
    {{-- Header Page --}}
    <div class="row g-3 my-2">
        <div class="col-12">
            <div class="p-3 bg-white shadow-sm rounded-3 text-center">
                <h3 class="fw-bold mb-0 text-primary">MANAJEMEN DATA HARGA</h3>
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
                
                {{-- Tombol Tambah Harga (Warna Hijau) --}}
                <div class="d-flex justify-content-end mb-4">
                    <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahHarga">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Harga Baru
                    </button>
                </div>
                
                {{-- TABEL DATA Harga --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-primary text-white">
                            <tr class="text-center align-middle">
                                <th style="width: 5%;">ID</th>
                                <th style="width: 10%;">Kode</th>
                                <th style="width: 20%;">Nama Produk/Paket</th>
                                <th style="width: 10%;">Tipe</th>
                                <th style="width: 15%;">Harga/M³</th>
                                <th style="width: 10%;">Biaya Admin</th>
                                <th style="width: 10%;">Denda</th>
                                <th style="width: 10%;">Batas Waktu Denda </th>
                                <th style="width: 10%;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daftarHarga as $item)
                            <tr class="text-center align-middle">
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->kode_product }}</td> 
                                <td class="text-start">{{ $item->nama_product }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $item->tipe == 'tunggal' ? 'bg-info text-dark' : 'bg-success text-white' }}">
                                        {{ ucfirst($item->tipe) }}
                                    </span>
                                </td>
                                <td class="text-end">Rp {{ number_format($item->harga_product, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($item->biaya_admin, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($item->denda, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $item->batas_waktu_denda }}</td>
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
                                                onclick="showDeleteAlert('{{ $item->id }}', '{{ $item->nama_product }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    {{-- Form Hapus (Tersembunyi) --}}
                                    <form id="form-delete-{{ $item->id }}" 
                                          action="{{ route('admin.harga.destroy', $item->id) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada data Harga.</td>
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
MODAL 1: TAMBAH HARGA (CREATE)
==================================================================================
--}}
<div class="modal fade" id="modalTambahHarga" tabindex="-1" aria-labelledby="modalTambahHargaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            
            <form action="{{ route('admin.harga.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                
                <div class="modal-header bg-success text-white p-4">
                    <h5 class="modal-title" id="modalTambahHargaLabel"><i class="bi bi-cash-coin me-2"></i>Form Tambah Harga Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="row g-3">
                        {{-- Field Form --}}
                        <div class="col-md-8">
                            <label for="nama_product" class="form-label fw-bold">Nama Produk/Paket <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_product') is-invalid @enderror" id="nama_product" name="nama_product" value="{{ old('nama_product') }}" required>
                            @error('nama_product')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tipe" class="form-label fw-bold">Tipe <span class="text-danger">*</span></label>
                            <select class="form-select @error('tipe') is-invalid @enderror" id="tipe" name="tipe" required>
                                <option value="" disabled selected>Pilih Tipe...</option>
                                <option value="tunggal" {{ old('tipe') == 'tunggal' ? 'selected' : '' }}>Tunggal (Per M³)</option>
                                <option value="paket" {{ old('tipe') == 'paket' ? 'selected' : '' }}>Paket</option>
                            </select>
                            @error('tipe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="harga_product" class="form-label fw-bold">Harga (per M³) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="1" class="form-control @error('harga_product') is-invalid @enderror" id="harga_product" name="harga_product" value="{{ old('harga_product') }}" required>
                            </div>
                            @error('harga_product')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="biaya_admin" class="form-label fw-bold">Biaya Admin <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="1" class="form-control @error('biaya_admin') is-invalid @enderror" id="biaya_admin" name="biaya_admin" value="{{ old('biaya_admin') }}" required>
                            </div>
                            @error('biaya_admin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="denda" class="form-label fw-bold">Denda <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="1" class="form-control @error('denda') is-invalid @enderror" id="denda" name="denda" value="{{ old('denda') }}" required>
                            </div>
                             @error('denda')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="batas_waktu_denda" class="form-label fw-bold">Batas Waktu Denda <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('batas_waktu_denda') is-invalid @enderror" id="batas_waktu_denda" name="batas_waktu_denda" value="{{ old('batas_waktu_denda') }}" required>
                            @error('batas_waktu_denda')
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
MODAL 2: EDIT HARGA (UPDATE)
==================================================================================
--}}
<div class="modal fade" id="modalEditHarga" tabindex="-1" aria-labelledby="modalEditHargaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            
            {{-- Form action akan di-set oleh JS, method POST, tapi kita tambahkan @method('PUT') --}}
            <form action="" method="POST" id="formEditHarga" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                
                <div class="modal-header bg-warning text-dark p-4">
                    <h5 class="modal-title" id="modalEditHargaLabel"><i class="bi bi-pencil-square me-2"></i>Form Edit Harga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                   <div class="row g-3">
                        {{-- Field Form --}}
                        <div class="col-md-8">
                            <label for="edit_nama_product" class="form-label fw-bold">Nama Produk/Paket <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama_product" name="nama_product" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_tipe" class="form-label fw-bold">Tipe <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_tipe" name="tipe" required>
                                <option value="tunggal">Tunggal (Per M³)</option>
                                <option value="paket">Paket</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="edit_harga_product" class="form-label fw-bold">Harga (per M³) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="1" class="form-control" id="edit_harga_product" name="harga_product" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_biaya_admin" class="form-label fw-bold">Biaya Admin <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="1" class="form-control" id="edit_biaya_admin" name="biaya_admin" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_denda" class="form-label fw-bold">Denda <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="1" class="form-control" id="edit_denda" name="denda" required>
                            </div>
                        </div>
                         <div class="col-md-12">
                            <label for="edit_batas_waktu_denda" class="form-label fw-bold">Batas Waktu Denda <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_batas_waktu_denda" name="batas_waktu_denda" required>
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
(Disesuaikan untuk 'Harga')
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
     * FUNGSI POPUP DETAIL (Layout 1 Kolom untuk Harga)
     */
    function showDetailAlert(hargaData) {
        
        // Format Rupiah
        const formatRupiah = (angka) => {
             return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        };

        // Objek untuk detail harga
        const detailHarga = {
            'Nama Produk': `<strong>${hargaData.nama_product}</strong>`,
            'Kode Produk': `<code>${hargaData.kode_product}</code>`,
            'Tipe': hargaData.tipe === 'tunggal' ? 'Tunggal (per M³)' : 'Paket',
            'Harga Pokok': formatRupiah(hargaData.harga_product),
            'Biaya Admin': formatRupiah(hargaData.biaya_admin),
            'Denda Keterlambatan': formatRupiah(hargaData.denda),
            'ID Perusahaan': hargaData.id_company,
            'ID Harga': hargaData.id,
            'batas_waktu_denda': hargaData.batas_waktu_denda,
        };
        
        // Buat HTML (layout 1 kolom sederhana)
        let htmlContent = `
            <div class="info-card">
                ${createDetailList(detailHarga)}
            </div>
        `;

        Swal.fire({
            title: `<i class="bi bi-cash-coin me-2"></i> Detail: ${hargaData.nama_product}`,
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
     * FUNGSI POPUP DELETE (Sama, hanya ganti variabel)
     */
    function showDeleteAlert(hargaId, hargaName) {
        Swal.fire({
            title: 'Hapus Data Harga?',
            text: `Anda yakin ingin menghapus "${hargaName}"? Tindakan ini tidak dapat dibatalkan!`,
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
                document.getElementById('form-delete-' + hargaId).submit();
            }
        });
    }

    /**
     * FUNGSI MODAL EDIT (Disesuaikan untuk Harga)
     * Mengisi dan menampilkan modal edit
     */
    function showEditModal(hargaData) {
        
        // 1. Set Form Action
        const form = document.getElementById('formEditHarga');
        // 'harga.index' akan menghasilkan '.../harga'. Kita tambahkan '/' dan id
        const baseUrl = "{{ rtrim(route('admin.harga.index'), '/') }}"; 
        form.action = `${baseUrl}/${hargaData.id}`;
        
        // 2. Populate Fields Harga
        document.getElementById('edit_nama_product').value = hargaData.nama_product || '';
        document.getElementById('edit_tipe').value = hargaData.tipe || 'tunggal';
        document.getElementById('edit_harga_product').value = parseFloat(hargaData.harga_product) || 0;
        document.getElementById('edit_biaya_admin').value = parseFloat(hargaData.biaya_admin) || 0;
        document.getElementById('edit_denda').value = parseFloat(hargaData.denda) || 0;
        document.getElementById('edit_batas_waktu_denda').value = hargaData.batas_waktu_denda || '';
        
        // 3. Tampilkan Modal
        var myModal = new bootstrap.Modal(document.getElementById('modalEditHarga'));
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

    @if ($errors->any())
        {{-- Hanya tampilkan modal jika error berasal dari field form TAMBAH --}}
        @if ($errors->has('nama_product') || $errors->has('tipe') || $errors->has('harga_product') || $errors->has('biaya_admin') || $errors->has('denda'))
            
            {{-- Cek juga jika ada 'old()' data (menandakan ini form 'create') --}}
            @if (old('nama_product') || old('tipe'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var myModal = new bootstrap.Modal(document.getElementById('modalTambahHarga'));
                    myModal.show();
                });
            </script>
            @endif
        @endif
    @endif

</script>
@endpush