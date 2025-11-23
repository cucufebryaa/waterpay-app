@extends('layouts.app') {{-- Menyesuaikan dengan layout Anda --}}

@section('title', 'Manajemen Keluhan - Admin WaterPay')

@section('content')
<div class="container-fluid px-4">
    
    {{-- Header Page --}}
    <div class="row g-3 my-2">
        <div class="col-12">
            <div class="p-3 bg-white shadow-sm rounded-3 text-center">
                <h3 class="fw-bold mb-0 text-primary">MANAJEMEN KELUHAN PENGGUNA</h3>
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
    
    {{-- Menampilkan SEMUA error validasi (untuk 'update' dari modal) --}}
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
                
                {{-- TABEL DATA KELUHAN --}}
                <div class="table-responsive">
                    <table id="tabelKeluhan" class="table table-striped table-hover align-middle">
                        <thead class="table-primary text-white">
                            <tr class="text-center align-middle">
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
                            <tr class="text-center align-middle">
                                <td>{{ $keluhan->id }}</td>
                                
                                {{-- Asumsi relasi 'pelanggan' dan punya field 'nama_lengkap' --}}
                                <td class="text-start">{{ $keluhan->pelanggan->nama ?? 'N/A' }}</td> 
                                
                                <td class="text-start">{{ $keluhan->keluhan }}</td>
                                
                                <td>
                                    @php
                                        $statusClass = 'bg-secondary'; // Default
                                        if ($keluhan->status == 'open') $statusClass = 'bg-primary';
                                        elseif ($keluhan->status == 'delegated') $statusClass = 'bg-info text-dark';
                                        elseif ($keluhan->status == 'onprogress') $statusClass = 'bg-warning text-dark';
                                        elseif ($keluhan->status == 'completed') $statusClass = 'bg-success';
                                        elseif ($keluhan->status == 'rejected') $statusClass = 'bg-danger';
                                    @endphp
                                    <span class="badge rounded-pill {{ $statusClass }} py-2 px-3">
                                        {{ $keluhan->status ?? 'Baru' }}
                                    </span>
                                </td>
                                
                                {{-- Asumsi relasi 'petugas' dan punya field 'nama' --}}
                                <td>
                                    @if ($keluhan->petugas)
                                        <i class="bi bi-person-check-fill text-success"></i>
                                        {{ $keluhan->petugas->nama ?? 'N/A' }}
                                    @else
                                        <span class="text-muted fst-italic">Belum Ditugaskan</span>
                                    @endif
                                </td>

                                <td>{{ $keluhan->created_at->format('d M Y H:i') }}</td>
                                
                                <td class="text-center">
                                    <div class="d-flex flex-nowrap justify-content-center">
                                        <button type="button" class="btn btn-sm btn-info me-1" title="Detail Tiket"
                                                onclick="showDetailAlert({{ json_encode($keluhan) }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @if (!in_array($keluhan->status, ['onprogress', 'completed', 'rejected']))
                                            <button type="button" class="btn btn-sm btn-primary me-1" title="Delegasikan Petugas"
                                                    onclick="showDelegasiModal({{ json_encode($keluhan) }})">
                                                <i class="bi bi-send"></i>
                                            </button>
                                        @endif
                                        @if ($keluhan->status == 'completed')
                                            <button type="button" class="btn btn-sm btn-success me-1" title="Lihat Bukti Pengerjaan"
                                                    onclick="showMaintenanceResult({{ json_encode($keluhan) }})">
                                                <i class="bi bi-images"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                                onclick="showDeleteAlert('{{ $keluhan->id }}', 'Keluhan #{{ $keluhan->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="form-delete-{{ $keluhan->id }}" 
                                        action="{{ route('admin.keluhan.destroy', $keluhan->id) }}" 
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data Keluhan.</td>
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
MODAL DELEGASI & STATUS (UPDATE)
(Menggantikan Modal Edit Harga)
==================================================================================
--}}
<div class="modal fade" id="modalDelegasi" tabindex="-1" aria-labelledby="modalDelegasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            
            {{-- Form action akan di-set oleh JS --}}
            <form action="" method="POST" id="formDelegasi" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title" id="modalDelegasiLabel"><i class="bi bi-send me-2"></i>Form Delegasi & Status Tiket</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                   
                   {{-- Info Tiket (Read-only) --}}
                   <div class="mb-3 p-3 bg-light rounded-3 border">
                       <div class="row g-2">
                           <div class="col-md-6">
                               <label class="form-label fw-bold small text-muted">Pelanggan:</label>
                               <input type="text" class="form-control" id="delegasi_pelanggan" readonly disabled>
                           </div>
                           <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Tiket ID:</label>
                                <input type="text" class="form-control" id="delegasi_id" readonly disabled>
                           </div>
                           <div class="col-12">
                                <label class="form-label fw-bold small text-muted">Keluhan:</label>
                               <input type="text" class="form-control" id="delegasi_judul" readonly disabled>
                           </div>
                       </div>
                   </div>
                   
                   <hr class="my-4">

                   {{-- Form Input (Tindakan Admin) --}}
                   <div class="row g-3">
                        <div class="col-md-6">
                            <label for="delegasi_id_petugas" class="form-label fw-bold">Tugaskan ke Petugas <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_petugas') is-invalid @enderror" id="delegasi_id_petugas" name="id_petugas" required>
                                <option value="" disabled>Pilih Petugas...</option>
                                {{-- Loop dari data $daftarPetugas yang dikirim Controller --}}
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
                            <label for="delegasi_status" class="form-label fw-bold">Ubah Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="delegasi_status" name="status" required>
                                <option value="open">Open</option>
                                <option value="delegated">Delegated</option>
                                <option value="onprogress">OnProgress</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                            </select>
                             @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                   </div>

                </div>
                
                <div class="modal-footer p-3 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Simpan Penugasan</button>
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
(Diambil dari template Harga Anda, tidak perlu diubah)
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
(Disesuaikan untuk 'Keluhan')
==================================================================================
--}}
<script>
    
    // Aktifkan juga DataTables untuk pencarian dan pagination jika mau
    // $(document).ready(function() {
    //     $('#tabelKeluhan').DataTable();
    // });

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
     * FUNGSI POPUP DETAIL (Disesuaikan untuk Keluhan)
     */
    function showDetailAlert(keluhanData) {
        
        // Objek untuk detail keluhan
        const detailKeluhan = {
            'ID Tiket': `<code>#${keluhanData.id}</code>`,
            'Pelanggan': `<strong>${keluhanData.pelanggan ? keluhanData.pelanggan.nama : 'N/A'}</strong>`,
            'Keluhan': `<div class="text-wrap" style="white-space: pre-wrap;">${keluhanData.keluhan || 'Tidak ada deskripsi.'}</div>`,
            'Tanggal Pengajuan': new Date(keluhanData.created_at).toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' })
        };
        
        // Objek untuk status penugasan
        const detailPenugasan = {
            'Status Saat Ini': `<span class="fw-bold text-primary">${keluhanData.status}</span>`,
            'Ditugaskan Kepada': `<strong>${keluhanData.petugas ? keluhanData.petugas.nama : 'Belum Ditugaskan'}</strong>`,
            'Terakhir Diperbarui': new Date(keluhanData.updated_at).toLocaleString('id-ID', { timeStyle: 'short' })
        };
        
        // Buat HTML (layout 2 kolom)
        let htmlContent = `
            <div class="row g-3">
                <div class="col-lg-12">
                    <div class="info-card">
                        <h5><i class="bi bi-file-earmark-text me-2"></i>Detail Keluhan</h5>
                        ${createDetailList(detailKeluhan, ['deskripsi'])}
                    </div>
                </div>
                <div class="col-lg-12 mt-3">
                     <div class="info-card">
                        <h5><i class="bi bi-person-check me-2"></i>Status & Penugasan</h5>
                        ${createDetailList(detailPenugasan)}
                    </div>
                </div>
            </div>
        `;

        Swal.fire({
            title: `<i class="bi bi-ticket-detailed me-2"></i> Detail Keluhan #${keluhanData.id}`,
            html: htmlContent,
            icon: null,
            width: '800px', // Popup lebih lebar
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
    function showDeleteAlert(keluhanId, keluhanName) {
        Swal.fire({
            title: 'Hapus Keluhan?',
            text: `Anda yakin ingin menghapus "${keluhanName}"? Tindakan ini tidak dapat dibatalkan!`,
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
                document.getElementById('form-delete-' + keluhanId).submit();
            }
        });
    }

    /**
     * FUNGSI MODAL DELEGASI (Dulu Edit)
     * Mengisi dan menampilkan modal delegasi
     */
    function showDelegasiModal(keluhanData) {
        
        // 1. Set Form Action
        const form = document.getElementById('formDelegasi');
        // 'keluhan.index' akan menghasilkan '.../keluhan'. Kita tambahkan '/' dan id
        const baseUrl = "{{ rtrim(route('admin.keluhan.index'), '/') }}"; 
        form.action = `${baseUrl}/${keluhanData.id}`;
        
        // 2. Populate Info Read-only
        document.getElementById('delegasi_id').value = keluhanData.id || '';
        document.getElementById('delegasi_pelanggan').value = keluhanData.pelanggan ? keluhanData.pelanggan.nama : 'N/A';
        document.getElementById('delegasi_judul').value = keluhanData.keluhan || '';
        
        // 3. Populate Form Fields
        // 'keluhanData.id_petugas' (misal: 5) akan otomatis terpilih di dropdown
        document.getElementById('delegasi_id_petugas').value = keluhanData.id_petugas || ''; 
        // 'keluhanData.status' (misal: "Diproses") akan otomatis terpilih
        document.getElementById('delegasi_status').value = keluhanData.status || 'Baru';
        
        // 4. Tampilkan Modal
        var myModal = new bootstrap.Modal(document.getElementById('modalDelegasi'));
        myModal.show();
    }


    // {{-- Script standar Bootstrap Validation --}}
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

    function showMaintenanceResult(keluhanData) {
        // 1. Cek apakah data maintenance ada
        if (!keluhanData.maintenance) {
            Swal.fire({
                icon: 'info',
                title: 'Data Belum Tersedia',
                text: 'Data pengerjaan tidak ditemukan di database (mungkin belum di-load controller).',
            });
            return;
        }

        const m = keluhanData.maintenance;
        
        // 2. Siapkan URL Foto
        // Pastikan path storage sesuai konfigurasi Laravel kamu
        const fotoUrl = "{{ asset('storage') }}/" + m.foto;

        // 3. Format Tanggal Selesai
        const tglSelesai = new Date(m.tanggal).toLocaleString('id-ID', { 
            dateStyle: 'full', timeStyle: 'short' 
        });

        // 4. Buat HTML Konten
        let htmlContent = `
            <div class="text-start">
                <div class="mb-3 p-3 bg-light border rounded">
                    <label class="small text-muted fw-bold">TANGGAL PENYELESAIAN</label>
                    <div class="fw-bold text-dark">${tglSelesai}</div>
                </div>

                <div class="mb-3">
                    <label class="small text-muted fw-bold">KETERANGAN PETUGAS</label>
                    <div class="alert alert-success border-0 shadow-sm text-dark mt-1">
                        <i class="bi bi-check-circle-fill me-2"></i> ${m.deskripsi}
                    </div>
                </div>

                <div class="mb-3">
                    <label class="small text-muted fw-bold mb-2">FOTO BUKTI LAPANGAN</label>
                    <div class="text-center bg-dark rounded p-2">
                        ${m.foto 
                            ? `<img src="${fotoUrl}" class="img-fluid rounded" style="max-height: 400px;" alt="Bukti Pengerjaan">` 
                            : '<span class="text-white-50 fst-italic">Tidak ada foto bukti.</span>'}
                    </div>
                </div>
            </div>
        `;

        // 5. Tampilkan SweetAlert
        Swal.fire({
            title: `<i class="bi bi-clipboard-check me-2"></i> Hasil Pengerjaan`,
            html: htmlContent,
            width: '600px',
            showConfirmButton: true,
            confirmButtonText: 'Tutup',
            customClass: {
                popup: 'popup-profesional', // Menggunakan style css yang sudah ada
                confirmButton: 'btn btn-secondary'
            }
        });
    }

</script>
@endpush