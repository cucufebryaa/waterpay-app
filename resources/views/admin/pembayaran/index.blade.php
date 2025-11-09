@extends('layouts.app') {{-- Menyesuaikan dengan layout Anda --}}

@section('title', 'Manajemen Pembayaran - Admin WaterPay')

@section('content')
<div class="container-fluid px-4">
    
    {{-- Header Page --}}
    <div class="row g-3 my-2">
        <div class="col-12">
            <div class="p-3 bg-white shadow-sm rounded-3 text-center">
                <h3 class="fw-bold mb-0 text-primary">MANAJEMEN PEMBAYARAN</h3>
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
    
    {{-- Menampilkan SEMUA error validasi (untuk modal 'update') --}}
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
                
                {{-- TABEL DATA PEMBAYARAN --}}
                <div class="table-responsive">
                    <table id="tabelPembayaran" class="table table-striped table-hover align-middle">
                        <thead class="table-primary text-white">
                            <tr class="text-center align-middle">
                                <th style="width: 5%;">ID</th>
                                <th style="width: 15%;">Pelanggan</th>
                                <th style="width: 15%;">ID Tagihan</th>
                                <th style="width: 15%;">Tgl. Bayar</th>
                                <th style="width: 15%;">Jumlah Bayar</th>
                                <th style="width: 15%;">Metode</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 10%;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataPembayaran as $item)
                            <tr class="text-center align-middle">
                                <td>{{ $item->id }}</td>
                                
                                {{-- Asumsi relasi 'pelanggan' dan punya field 'nama_lengkap' --}}
                                <td class="text-start">{{ $item->pelanggan->nama_lengkap ?? 'N/A' }}</td> 
                                
                                {{-- Asumsi relasi 'tagihan' dan punya field 'id_tagihan' (atau 'nomor_tagihan') --}}
                                <td><code>{{ $item->tagihan->nomor_tagihan ?? $item->id_tagihan ?? 'N/A' }}</code></td>
                                
                                {{-- Asumsi 'tanggal_bayar' adalah Carbon instance --}}
                                <td>{{ $item->tanggal_bayar ? $item->tanggal_bayar->format('d M Y H:i') : 'N/A' }}</td>

                                <td class="text-end fw-bold">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                                
                                <td>{{ $item->metode_pembayaran ?? 'N/A' }}</td>

                                <td>
                                    @php
                                        $statusClass = 'bg-secondary'; // Default
                                        if ($item->status == 'Pending') $statusClass = 'bg-warning text-dark';
                                        elseif ($item->status == 'Success') $statusClass = 'bg-success';
                                        elseif ($item->status == 'Failed') $statusClass = 'bg-danger';
                                    @endphp
                                    <span class="badge rounded-pill {{ $statusClass }} py-2 px-3">
                                        {{ $item->status ?? 'N/A' }}
                                    </span>
                                </td>
                                
                                <td class="text-center">
                                    <div class="d-flex flex-nowrap justify-content-center">
                                        
                                        {{-- Tombol Detail --}}
                                        <button type="button" class="btn btn-sm btn-info me-1" title="Detail Pembayaran"
                                                onclick="showDetailAlert({{ json_encode($item) }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        {{-- TOMBOL KONFIRMASI (dulu Edit) --}}
                                        <button type="button" class="btn btn-sm btn-warning me-1" title="Konfirmasi / Tolak Pembayaran"
                                                onclick="showKonfirmasiModal({{ json_encode($item) }})">
                                            <i class="bi bi-patch-check"></i>
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus Data"
                                                onclick="showDeleteAlert('{{ $item->id }}', 'Pembayaran #{{ $item->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    {{-- Form Hapus (Tersembunyi) --}}
                                    <form id="form-delete-{{ $item->id }}" 
                                          action="{{ route('admin.pembayaran.destroy', $item->id) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada data Pembayaran.</td>
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
MODAL KONFIRMASI PEMBAYARAN (UPDATE)
==================================================================================
--}}
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            
            {{-- Form action akan di-set oleh JS --}}
            <form action="" method="POST" id="formKonfirmasi" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                
                <div class="modal-header bg-warning text-dark p-4">
                    <h5 class="modal-title" id="modalKonfirmasiLabel"><i class="bi bi-patch-check me-2"></i>Form Konfirmasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                   
                   {{-- Info Pembayaran (Read-only) --}}
                   <div class="mb-3 p-3 bg-light rounded-3 border">
                       <div class="row g-2">
                           <div class="col-md-6">
                               <label class="form-label fw-bold small text-muted">Pelanggan:</label>
                               <input type="text" class="form-control" id="konfirmasi_pelanggan" readonly disabled>
                           </div>
                           <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">ID Pembayaran:</label>
                                <input type="text" class="form-control" id="konfirmasi_id" readonly disabled>
                           </div>
                           <div class="col-12">
                                <label class="form-label fw-bold small text-muted">Jumlah Bayar:</label>
                               <input type="text" class="form-control fw-bold text-success" id="konfirmasi_jumlah" readonly disabled style="font-size: 1.1rem;">
                           </div>
                       </div>
                   </div>
                   
                   <hr class="my-4">

                   {{-- Form Input (Tindakan Admin) --}}
                   <div class="row g-3">
                        <div class="col-md-12">
                            <label for="konfirmasi_status" class="form-label fw-bold">Ubah Status Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="konfirmasi_status" name="status" required>
                                <option value="" disabled>Pilih Status...</option>
                                <option value="Pending">Pending (Tunda)</option>
                                <option value="Success">Success (Konfirmasi & Terima)</option>
                                <option value="Failed">Failed (Tolak Pembayaran)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="konfirmasi_catatan_admin" class="form-label fw-bold">Catatan Admin (Opsional)</label>
                            <textarea class="form-control @error('catatan_admin') is-invalid @enderror" id="konfirmasi_catatan_admin" name="catatan_admin" rows="3" placeholder="Contoh: Pembayaran ditolak karena bukti tidak valid."></textarea>
                            @error('catatan_admin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                   </div>

                </div>
                
                <div class="modal-footer p-3 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark"><i class="bi bi-save me-2"></i>Simpan Status</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')

{{-- 
==================================================================================
CSS STYLING UNTUK POPUP SWEETALERT
(Diambil dari template Harga/Keluhan Anda)
==================================================================================
--}}
<style>
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
    .info-card {
        background-color: #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0; border-radius: 0.5rem; padding: 1.25rem;
        height: 100%;
    }
    .info-card h5 {
        font-weight: 600; color: #343a40; padding-bottom: 0.5rem;
        margin-bottom: 1rem; border-bottom: 1px solid #f0f0f0;
    }
    .detail-list { display: flex; flex-direction: column; gap: 0.75rem; }
    .detail-item {
        display: flex; justify-content: space-between; align-items: flex-start; /* align-items-start untuk text panjang */
        padding-bottom: 0.75rem; border-bottom: 1px dashed #e9ecef;
    }
    .detail-item:last-child { border-bottom: 0; }
    .detail-key { font-size: 0.9em; color: #6c757d; text-align: left; padding-right: 1rem; flex-shrink: 0; }
    .detail-value { font-size: 0.95em; font-weight: 600; color: #212529; text-align: right; word-break: break-all; } /* word-break */
    .detail-bukti-bayar {
        text-align: center; padding-top: 1rem;
    }
    .detail-bukti-bayar img {
        max-width: 100%; height: auto; max-height: 400px;
        border-radius: 0.5rem; border: 1px solid #dee2e6;
        cursor: zoom-in;
    }
</style>


{{-- 
==================================================================================
JAVASCRIPT UNTUK POPUP & MODAL
(Disesuaikan untuk 'Pembayaran')
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
            if (!skipKeys.includes(key) && value !== null && value !== '' && value !== undefined) {
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
     * FUNGSI HELPER FORMAT RUPIAH
     */
    const formatRupiah = (angka) => {
         return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    };

    /**
     * FUNGSI POPUP DETAIL (Disesuaikan untuk Pembayaran)
     */
    function showDetailAlert(data) {
        
        // Objek untuk detail keluhan
        const detailPembayaran = {
            'ID Pembayaran': `<code>#${data.id}</code>`,
            'Pelanggan': `<strong>${data.pelanggan ? data.pelanggan.nama_lengkap : 'N/A'}</strong>`,
            'ID Tagihan': `<code>${data.tagihan ? (data.tagihan.nomor_tagihan || data.id_tagihan) : 'N/A'}</code>`,
            'Jumlah Bayar': `<span class="fw-bold text-success">${formatRupiah(data.jumlah_bayar)}</span>`,
            'Metode Bayar': data.metode_pembayaran || 'N/A',
            'Tgl. Transaksi': data.tanggal_bayar ? new Date(data.tanggal_bayar).toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' }) : 'N/A',
            'Status Saat Ini': `<span class="fw-bold text-primary">${data.status}</span>`,
            'Catatan Admin': data.catatan_admin || 'Tidak ada catatan.'
        };

        // Buat HTML untuk bukti bayar
        let buktiHtml = '<div class="info-card"><h5><i class="bi bi-card-image me-2"></i>Bukti Pembayaran</h5>';
        if (data.bukti_bayar) {
            // Asumsi bukti_bayar adalah URL gambar
            buktiHtml += `
                <div class="detail-bukti-bayar">
                    <img src="${data.bukti_bayar}" alt="Bukti Pembayaran" 
                         onerror="this.alt='Gagal memuat bukti'; this.src='https://placehold.co/400x300/eee/aaa?text=Bukti+Tidak+Valid';"
                         onclick="window.open('${data.bukti_bayar}', '_blank')">
                    <small class="text-muted d-block mt-2">Klik gambar untuk memperbesar</small>
                </div>`;
        } else {
            buktiHtml += '<p class="text-muted m-0">Tidak ada bukti bayar yang diunggah.</p>';
        }
        buktiHtml += '</div>';
        
        // Buat HTML (layout 2 kolom)
        let htmlContent = `
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="info-card">
                        <h5><i class="bi bi-file-earmark-text me-2"></i>Detail Transaksi</h5>
                        ${createDetailList(detailPembayaran)}
                    </div>
                </div>
                <div class="col-lg-6">
                    ${buktiHtml}
                </div>
            </div>
        `;

        Swal.fire({
            title: `<i class="bi bi-wallet2 me-2"></i> Detail Pembayaran #${data.id}`,
            html: htmlContent,
            icon: null,
            width: '900px', // Popup lebih lebar
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
    function showDeleteAlert(bayarId, bayarName) {
        Swal.fire({
            title: 'Hapus Data Pembayaran?',
            text: `Anda yakin ingin menghapus "${bayarName}"? Tindakan ini tidak dapat dibatalkan!`,
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
                document.getElementById('form-delete-' + bayarId).submit();
            }
        });
    }

    /**
     * FUNGSI MODAL KONFIRMASI (Dulu Edit/Delegasi)
     */
    function showKonfirmasiModal(data) {
        
        // 1. Set Form Action
        const form = document.getElementById('formKonfirmasi');
        const baseUrl = "{{ rtrim(route('admin.pembayaran.index'), '/') }}"; 
        form.action = `${baseUrl}/${data.id}`;
        
        // 2. Populate Info Read-only
        document.getElementById('konfirmasi_id').value = data.id || '';
        document.getElementById('konfirmasi_pelanggan').value = data.pelanggan ? data.pelanggan.nama_lengkap : 'N/A';
        document.getElementById('konfirmasi_jumlah').value = formatRupiah(data.jumlah_bayar);
        
        // 3. Populate Form Fields
        document.getElementById('konfirmasi_status').value = data.status || 'Pending';
        document.getElementById('konfirmasi_catatan_admin').value = data.catatan_admin || '';
        
        // 4. Tampilkan Modal
        var myModal = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));
        myModal.show();
    }


    {{-- Script standar Bootstrap Validation --}}
    (function () {
      'use"use strict'
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

</script>
@endpush