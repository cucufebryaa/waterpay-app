@extends('layouts.app')

@section('title', 'Riwayat Pemakaian - Admin WaterPay')

@section('content')
<div class="container-fluid px-4">
    
    {{-- Header Page --}}
    <div class="row g-3 my-2">
        <div class="col-12">
            <div class="p-3 bg-white shadow-sm rounded-3 text-center">
                <h3 class="fw-bold mb-0 text-primary">DATA RIWAYAT PEMAKAIAN</h3>
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
    
    {{-- (Kita tidak perlu error validasi 'store'/'update' di sini) --}}

    {{-- Kotak Utama untuk Konten --}}
    <div class="row g-3 my-4">
        <div class="col-12">
            <div class="p-4 bg-white shadow-sm rounded-3">
                
                {{-- Tombol Filter (Lebih berguna daripada 'Tambah') --}}
                <div class="d-flex justify-content-end mb-4">
                    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalFilter">
                        <i class="bi bi-funnel me-2"></i>Filter Data
                    </button>
                </div>
                
                {{-- TABEL DATA Riwayat Pemakaian --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-primary text-white">
                            <tr class="text-center align-middle">
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Periode</th>
                                <th>Meter Awal</th>
                                <th>Meter Akhir</th>
                                <th>Total Pakai (m³)</th>
                                <th>Dicatat Oleh</th>
                                <th>Status Bayar</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daftarRiwayat as $item)
                            <tr class="text-center align-middle">
                                <td>{{ $item->id }}</td>
                                <td class="text-start">
                                    {{-- Cek jika relasi pelanggan ada --}}
                                    {{ $item->pelanggan->nama ?? 'N/A' }}
                                </td> 
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td>{{ $item->meter_awal }}</td>
                                <td>{{ $item->meter_akhir }}</td>
                                <td>
                                    <strong class="text-primary">{{ $item->total_pakai }} m³</strong>
                                </td>
                                <td>{{ $item->petugas->nama ?? 'N/A' }}</td>
                                <td>
                                    {{-- Cek jika relasi pembayaran ada --}}
                                    @if ($item->pembayaran)
                                        <span class="badge rounded-pill bg-success">Lunas</span>
                                    @else
                                        <span class="badge rounded-pill bg-warning text-dark">Belum Lunas</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- Tombol Detail (Satu-satunya aksi) --}}
                                    <button type="button" class="btn btn-sm btn-info" title="Detail"
                                            onclick="showDetailAlert({{ json_encode($item) }})">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Belum ada data riwayat pemakaian.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Tampilkan Link Paginasi --}}
                <div class="mt-4">
                    {{ $daftarRiwayat->links() }}
                </div>

            </div>
        </div>
    </div>
</div>

{{-- 
==================================================================================
MODAL (Hanya untuk CSS dan JS)
Kita tidak gunakan Modal Tambah/Edit
==================================================================================
--}}
@endsection

@push('scripts')

{{-- CSS UNTUK POPUP (SAMA PERSIS) --}}
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
    /* CSS untuk 2 kolom */
    .detail-popup-content { display: flex; gap: 1.5rem; }
    .detail-popup-column { flex: 1; min-width: 0; }
    @media (max-width: 768px) { .detail-popup-content { flex-direction: column; gap: 1rem; } }
</style>


{{-- 
==================================================================================
JAVASCRIPT (Diperbarui untuk Pemakaian)
==================================================================================
--}}
<script>
    /**
     * FUNGSI HELPER (SAMA)
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
        if (!hasData) { listHtml += '<p class="text-muted small m-0">Tidak ada data.</p>'; }
        listHtml += '</div>';
        return listHtml;
    }

    /**
     * FUNGSI POPUP DETAIL (Diperbarui untuk Pemakaian)
     */
    function showDetailAlert(item) {
        
        // Format Rupiah
        const formatRupiah = (angka) => {
             return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        };

        // Kolom Kiri: Detail Pemakaian
        const detailPemakaian = {
            'Periode Catat': new Date(item.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }),
            'Meter Awal': `${item.meter_awal} m³`,
            'Meter Akhir': `${item.meter_akhir} m³`,
            'Total Pakai': `<strong>${item.total_pakai} m³</strong>`,
            'Tarif Saat Itu': formatRupiah(item.tarif),
            'Dicatat Oleh': item.petugas ? item.petugas.nama : 'N/A',
            'Foto Meteran': `<a href="/${item.foto}" target="_blank">Lihat Foto</a>` // (Asumsi 'foto' adalah URL)
        };

        // Kolom Kanan: Detail Pelanggan & Produk
        const detailLain = {
            'Pelanggan': item.pelanggan ? item.pelanggan.nama : 'N/A',
            'Alamat': item.pelanggan ? item.pelanggan.alamat : 'N/A',
            'Produk': item.kode_product ? item.kode_product.nama_product : 'N/A',
            'Kode Produk': `<code>${item.kd_product}</code>`,
            'Status Bayar': item.pembayaran ? '<span class="text-success fw-bold">LUNAS</span>' : '<span class="text-danger fw-bold">BELUM LUNAS</span>'
        };
        
        // Buat HTML dengan 2 kolom
        let htmlContent = `
            <div class="detail-popup-content">
                <div class="detail-popup-column">
                    <div class="info-card">
                        <h5 class="mb-3"><i class="bi bi-speedometer2 me-2"></i>Detail Pemakaian</h5>
                        ${createDetailList(detailPemakaian)}
                    </div>
                </div>
                <div class="detail-popup-column">
                    <div class="info-card">
                        <h5 class="mb-3"><i class="bi bi-person-badge me-2"></i>Info Pelanggan & Tagihan</h5>
                        ${createDetailList(detailLain)}
                    </div>
                </div>
            </div>
        `;

        Swal.fire({
            title: `<i class="bi bi-journal-text me-2"></i> Detail Riwayat #${item.id}`,
            html: htmlContent,
            icon: null,
            width: '800px', // Popup lebar
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

    // Kita tidak perlu showDeleteAlert atau showEditModal untuk halaman ini
</script>
@endpush