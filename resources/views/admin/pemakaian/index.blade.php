@extends('layouts.app')

@section('title', 'Riwayat Pemakaian')

@section('content')

{{-- 
    1. STYLE CSS 
    (Disamakan dengan halaman Maintenance & Input Meter)
--}}
<style>
    /* --- Layout & Header --- */
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

    .counter-badge {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 20px;
        font-size: 0.95rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    /* --- Modern Section Card --- */
    .modern-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        overflow: hidden;
        border: 1px solid #e8ecf1;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
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

    /* --- Table Styling --- */
    .table-custom {
        margin: 0;
    }

    .table-custom thead th {
        background: #f8f9fc;
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1.25rem;
        border: none;
        white-space: nowrap;
    }

    .table-custom tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s;
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
        transform: translateX(4px);
    }

    .table-custom tbody td {
        padding: 1.25rem;
        vertical-align: middle;
        font-size: 0.9rem;
        border: none;
        color: #334155;
    }

    /* --- Typography & Elements --- */
    .customer-name {
        font-weight: 600;
        color: #1e293b;
        display: block;
    }

    .customer-meta {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    .usage-badge {
        background: #e0e7ff;
        color: #4338ca;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-weight: 700;
        font-family: monospace;
    }

    /* Status Badges */
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
    }

    .status-lunas {
        background: #dcfce7;
        color: #166534;
    }

    .status-belum {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Buttons */
    .btn-detail {
        background: white;
        border: 1px solid #e2e8f0;
        color: #475569;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .btn-detail:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }
    
    .btn-filter {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
    }

    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(37, 99, 235, 0.3);
        color: white;
    }

    /* --- Modal Custom --- */
    .modal-header-custom {
        background: linear-gradient(135deg, #3b82f6, #06b6d4);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem;
        border: none;
    }

    .detail-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1rem;
    }

    .img-evidence {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        background-color: #f8fafc;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
    }
</style>

{{-- 
    2. CONTENT
--}}

<div class="page-header">
    <h1><i class="bi bi-clock-history me-2"></i>Riwayat Pemakaian</h1>
    <div class="counter-badge">
        <i class="bi bi-database"></i>
        <span>{{ $daftarRiwayat->total() }} Data Tersimpan</span>
    </div>
</div>

{{-- Alerts --}}
@if (session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4 rounded-3 d-flex align-items-center">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="modern-section">
    <div class="section-header">
        <h5>
            <i class="bi bi-list-columns-reverse text-primary"></i>
            Daftar Log Meteran Air
        </h5>
        
        <button type="button" class="btn-filter" data-bs-toggle="modal" data-bs-target="#modalFilter">
            <i class="bi bi-funnel"></i>
            Filter & Pencarian
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 20%">Pelanggan</th>
                    <th style="width: 15%">Periode Catat</th>
                    <th style="width: 20%">Meter (Awal - Akhir)</th>
                    <th style="width: 15%">Total Pakai</th>
                    <th style="width: 15%" class="text-center">Status Bayar</th>
                    <th style="width: 10%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarRiwayat as $index => $item)
                <tr>
                    <td>
                        <span class="text-muted fw-bold">{{ $daftarRiwayat->firstItem() + $index }}</span>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="customer-name">{{ $item->pelanggan->nama ?? 'Unknown' }}</span>
                            <span class="customer-meta">
                                <i class="bi bi-upc-scan me-1"></i>{{ $item->pelanggan->no_pelanggan ?? '-' }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="text-secondary">
                            <i class="bi bi-calendar-event me-1"></i>
                            {{ $item->created_at->format('d M Y') }}
                        </div>
                        <small class="text-muted">{{ $item->created_at->format('H:i') }} WIB</small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted">{{ $item->meter_awal }}</span>
                            <i class="bi bi-arrow-right text-primary small"></i>
                            <span class="fw-bold text-dark">{{ $item->meter_akhir }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="usage-badge">
                            {{ $item->total_pakai }} m³
                        </span>
                    </td>
                    <td class="text-center">
                        @if ($item->pembayaran)
                            <span class="status-badge status-lunas">
                                <i class="bi bi-check-circle-fill"></i> Lunas
                            </span>
                        @else
                            <span class="status-badge status-belum">
                                <i class="bi bi-hourglass-split"></i> Belum
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button type="button" 
                                class="btn-detail" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalDetailRiwayat"
                                data-json="{{ json_encode($item) }}"
                                data-pelanggan="{{ $item->pelanggan->nama ?? '-' }}"
                                data-petugas="{{ $item->petugas->nama ?? '-' }}"
                                data-produk="{{ $item->kode_product->kd_product ?? '-' }}"
                                data-tarif="{{ $item->tarif }}">
                            <i class="bi bi-eye"></i> Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-clipboard-x display-4 text-gray-300 mb-3"></i>
                            <p class="mb-0 fw-bold">Belum ada data riwayat pemakaian.</p>
                            <small>Data akan muncul setelah petugas melakukan pencatatan.</small>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-4 py-3 border-top bg-light">
        {{ $daftarRiwayat->links() }}
    </div>
</div>

{{-- 
    3. MODALS
--}}

<div class="modal fade" id="modalFilter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Filter Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Fitur filter akan ditambahkan di sini (Bulan/Tahun/Status).</p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetailRiwayat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-bold text-white">
                    <i class="bi bi-file-earmark-text me-2"></i>Detail Pemakaian
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-7">
                        <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Informasi Umum</h6>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="detail-label">Pelanggan</div>
                                <div class="detail-value" id="d_pelanggan">-</div>
                            </div>
                            <div class="col-6">
                                <div class="detail-label">Golongan / Tarif</div>
                                <div class="detail-value" id="d_golongan">-</div>
                            </div>
                            <div class="col-6">
                                <div class="detail-label">Tanggal Catat</div>
                                <div class="detail-value" id="d_tanggal">-</div>
                            </div>
                            <div class="col-6">
                                <div class="detail-label">Petugas Pencatat</div>
                                <div class="detail-value" id="d_petugas">-</div>
                            </div>
                        </div>

                        <h6 class="text-primary fw-bold mb-3 border-bottom pb-2 mt-2">Data Meteran</h6>
                        <div class="bg-light p-3 rounded-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Meter Awal</span>
                                <span class="fw-bold" id="d_awal">0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Meter Akhir</span>
                                <span class="fw-bold" id="d_akhir">0</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-dark fw-bold">Total Pemakaian</span>
                                <span class="badge bg-primary fs-6" id="d_total">0 m³</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Bukti Foto</h6>
                        <div class="text-center">
                            <img src="" id="d_foto" class="img-evidence mb-2" alt="Bukti Meteran">
                            <small class="text-muted d-block fst-italic">
                                *Klik kanan "Open Image" untuk memperbesar
                            </small>
                            <a href="#" id="link_foto" target="_blank" class="btn btn-sm btn-outline-primary mt-2 rounded-pill w-100">
                                <i class="bi bi-box-arrow-up-right"></i> Buka Foto Penuh
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light px-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Base URL untuk akses Storage (Penting untuk foto)
        // Pastikan Anda sudah menjalankan: php artisan storage:link
        const storageBaseUrl = "{{ asset('storage') }}";

        const modalDetail = document.getElementById('modalDetailRiwayat');
        
        if (modalDetail) {
            modalDetail.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                
                // Ambil data dari atribut tombol
                const dataItem = JSON.parse(button.getAttribute('data-json'));
                const namaPelanggan = button.getAttribute('data-pelanggan');
                const namaPetugas = button.getAttribute('data-petugas');
                const kdProduk = button.getAttribute('data-produk');
                const tarif = parseInt(button.getAttribute('data-tarif')).toLocaleString('id-ID');

                // Helper Format Tanggal
                const date = new Date(dataItem.created_at);
                const formattedDate = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

                // Isi Data Text
                modalDetail.querySelector('#d_pelanggan').textContent = namaPelanggan;
                modalDetail.querySelector('#d_golongan').textContent = `${kdProduk} (Rp ${tarif}/m³)`;
                modalDetail.querySelector('#d_tanggal').textContent = formattedDate;
                modalDetail.querySelector('#d_petugas').textContent = namaPetugas;
                
                modalDetail.querySelector('#d_awal').textContent = dataItem.meter_awal + ' m³';
                modalDetail.querySelector('#d_akhir').textContent = dataItem.meter_akhir + ' m³';
                modalDetail.querySelector('#d_total').textContent = dataItem.total_pakai + ' m³';

                // LOGIKA FOTO (FIXED)
                const imgElement = modalDetail.querySelector('#d_foto');
                const linkElement = modalDetail.querySelector('#link_foto');
                
                if (dataItem.foto) {
                    // Gabungkan base URL storage dengan path foto dari database
                    const fullPath = `${storageBaseUrl}/${dataItem.foto}`;
                    
                    imgElement.src = fullPath;
                    linkElement.href = fullPath;
                    
                    imgElement.style.display = 'block';
                    linkElement.classList.remove('disabled');
                    linkElement.innerHTML = '<i class="bi bi-box-arrow-up-right"></i> Buka Foto Penuh';
                } else {
                    // Fallback jika tidak ada foto
                    imgElement.src = 'https://via.placeholder.com/400x300?text=No+Image';
                    linkElement.classList.add('disabled');
                    linkElement.innerHTML = 'Foto Tidak Tersedia';
                }
            });
        }
    });
</script>
@endpush