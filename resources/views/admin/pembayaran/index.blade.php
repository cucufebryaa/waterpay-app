@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')

<style>
    /* --- STYLE KONSISTEN (Sama dengan Halaman Lain) --- */
    :root {
        --primary-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    /* Page Header */
    .page-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1.5rem; border-bottom: 1px solid #e3e6f0; padding-bottom: 1rem;
    }
    .page-header h1 {
        font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0;
        display: flex; align-items: center; gap: 0.75rem;
    }
    .page-header h1 i { color: #3b82f6; }

    /* Stat Cards */
    .stat-card {
        border: none; border-radius: 16px; position: relative; overflow: hidden;
        transition: transform 0.2s; box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        color: white; height: 100%;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card .card-body { position: relative; z-index: 2; padding: 1.5rem; }
    .stat-icon {
        position: absolute; right: -10px; bottom: -10px;
        font-size: 4.5rem; opacity: 0.15; transform: rotate(-15deg); z-index: 1;
    }
    .bg-gradient-primary { background: var(--primary-gradient); }
    .bg-gradient-success { background: var(--success-gradient); }
    .bg-gradient-warning { background: var(--warning-gradient); }

    /* Table Container */
    .table-container {
        background: white; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: 1px solid #e8ecf1; overflow: hidden;
    }
    .modern-table thead th {
        background: #f8fafc; color: #64748b; font-weight: 600;
        font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;
        padding: 1rem; border-bottom: 2px solid #e2e8f0;
    }
    .modern-table tbody td {
        padding: 1rem; vertical-align: middle; font-size: 0.9rem; border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .modern-table tbody tr:hover { background-color: #f8fafc; }

    /* Badges & Buttons */
    .badge-status {
        padding: 0.5em 0.8em; border-radius: 50rem; font-weight: 600; font-size: 0.75rem;
        display: inline-flex; align-items: center; gap: 0.35rem;
    }
    .badge-success-soft { background-color: #dcfce7; color: #166534; }
    .badge-warning-soft { background-color: #fef3c7; color: #92400e; }
    .badge-danger-soft  { background-color: #fee2e2; color: #991b1b; }

    .btn-action {
        width: 32px; height: 32px; padding: 0; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.2s; border: 1px solid transparent;
    }
    .btn-action:hover { transform: scale(1.1); }
    .btn-view { background: #e0f2fe; color: #0284c7; }
    .btn-delete { background: #fee2e2; color: #ef4444; }

    /* SweetAlert Custom */
    .popup-detail .swal2-title {
        font-size: 1.1rem; border-bottom: 1px solid #eee; padding: 1em; margin: 0;
        background: #f8fafc; color: #334155;
    }
    .detail-row {
        display: flex; justify-content: space-between; padding: 0.75rem 0;
        border-bottom: 1px dashed #e2e8f0; font-size: 0.9rem;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { color: #64748b; }
    .detail-val { font-weight: 600; color: #1e293b; text-align: right; }
</style>

<div class="container-fluid px-0">
    
    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h1><i class="bi bi-wallet2"></i> Riwayat Pembayaran Masuk</h1>
            <p class="text-muted mb-0 small mt-1">Monitoring transaksi pembayaran otomatis (Payment Gateway).</p>
        </div>
        <div>
            <span class="badge bg-white text-dark border shadow-sm px-3 py-2 rounded-pill">
                <i class="bi bi-calendar-check me-2 text-success"></i>
                {{ now()->isoFormat('DD MMMM Y') }}
            </span>
        </div>
    </div>

    <!-- STATS CARDS (Opsional: Hitung di View untuk ringkasan cepat) -->
    <div class="row g-3 mb-4">
        @php
            $totalUang = $dataPembayaran->where('status', 'success')->sum('total_bayar');
            $totalSukses = $dataPembayaran->where('status', 'success')->count();
            $totalPending = $dataPembayaran->where('status', 'pending')->count();
        @endphp

        <div class="col-md-4">
            <div class="card stat-card bg-gradient-success">
                <div class="card-body">
                    <small class="text-white-50 text-uppercase fw-bold ls-1">Total Pendapatan</small>
                    <h3 class="fw-bold mt-1 mb-0">Rp {{ number_format($totalUang, 0, ',', '.') }}</h3>
                    <div class="mt-2 small text-white-50"><i class="bi bi-arrow-up-circle me-1"></i>Uang Masuk</div>
                    <i class="bi bi-cash-stack stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-primary">
                <div class="card-body">
                    <small class="text-white-50 text-uppercase fw-bold ls-1">Transaksi Berhasil</small>
                    <h3 class="fw-bold mt-1 mb-0">{{ $totalSukses }}</h3>
                    <div class="mt-2 small text-white-50">Pembayaran Terverifikasi</div>
                    <i class="bi bi-check-circle stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-warning">
                <div class="card-body">
                    <small class="text-white-50 text-uppercase fw-bold ls-1">Menunggu (Pending)</small>
                    <h3 class="fw-bold mt-1 mb-0">{{ $totalPending }}</h3>
                    <div class="mt-2 small text-white-50">Belum dibayar pelanggan</div>
                    <i class="bi bi-hourglass-split stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table modern-table mb-0" id="tabelPembayaran">
                <thead>
                    <tr>
                        <th class="ps-4">Waktu</th>
                        <th>Pelanggan</th>
                        <th>Tagihan Periode</th>
                        <th>Channel</th>
                        <th class="text-end">Total Bayar</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataPembayaran as $item)
                    <tr>
                        <td class="ps-4">
                            @if($item->tanggal_bayar)
                                <div class="fw-bold">{{ $item->tanggal_bayar->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $item->tanggal_bayar->format('H:i') }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->pelanggan->nama ?? 'Unknown' }}</div>
                            <small class="text-muted">ID: {{ $item->pelanggan->no_pelanggan ?? '-' }}</small>
                        </td>
                        <td>
                            @if($item->pemakaian)
                                <span class="badge bg-light text-primary border">
                                    {{ $item->pemakaian->created_at->translatedFormat('F Y') }}
                                </span>
                            @else
                                <span class="text-danger small">Data Terhapus</span>
                            @endif
                        </td>
                        <td>
                            <span class="small text-muted text-uppercase">
                                {{ $item->payment_channel ?? 'XENDIT' }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-dark">
                            Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($item->status == 'success')
                                <span class="badge-status badge-success-soft">
                                    <i class="bi bi-check-circle-fill"></i> LUNAS
                                </span>
                            @elseif($item->status == 'pending')
                                <span class="badge-status badge-warning-soft">
                                    <i class="bi bi-clock-history"></i> PENDING
                                </span>
                            @else
                                <span class="badge-status badge-danger-soft">
                                    <i class="bi bi-x-circle-fill"></i> {{ strtoupper($item->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn-action btn-view" 
                                        onclick="showDetail({{ json_encode($item) }})" 
                                        title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                                
                                {{-- Tombol Hapus (Hanya untuk data sampah/failed, data success sebaiknya jangan dihapus sembarangan) --}}
                                <button type="button" class="btn-action btn-delete" 
                                        onclick="confirmDelete('{{ $item->id }}')" 
                                        title="Hapus Data">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <form id="delete-form-{{ $item->id }}" 
                                  action="{{ route('admin.pembayaran.destroy', $item->id) }}" 
                                  method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-gray-300"></i>
                                Belum ada riwayat transaksi.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Format Rupiah Helper
    const formatRupiah = (angka) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }

    // Show Detail Modal (SweetAlert)
    function showDetail(data) {
        const tglBayar = data.tanggal_bayar 
            ? new Date(data.tanggal_bayar).toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' }) 
            : '-';
        
        const namaPelanggan = data.pelanggan ? data.pelanggan.nama : 'Unknown';
        const periode = data.pemakaian ? new Date(data.pemakaian.created_at).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' }) : '-';

        let htmlContent = `
            <div class="px-2">
                <div class="detail-row">
                    <span class="detail-label">ID Transaksi</span>
                    <span class="detail-val text-primary font-monospace">#${data.id}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Ref. Xendit</span>
                    <span class="detail-val small text-muted">${data.xendit_id || '-'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Pelanggan</span>
                    <span class="detail-val">${namaPelanggan}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tagihan Periode</span>
                    <span class="detail-val">${periode}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Waktu Bayar</span>
                    <span class="detail-val">${tglBayar}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Metode</span>
                    <span class="detail-val badge bg-light text-dark border">${data.payment_channel || 'XENDIT'}</span>
                </div>
                <div class="detail-row border-0 pt-3">
                    <span class="detail-label fw-bold">TOTAL DIBAYAR</span>
                    <span class="detail-val fs-5 text-success">${formatRupiah(data.total_bayar)}</span>
                </div>
            </div>
        `;

        Swal.fire({
            title: 'Detail Pembayaran',
            html: htmlContent,
            showConfirmButton: false,
            showCloseButton: true,
            customClass: { popup: 'popup-detail rounded-4' }
        });
    }

    // Confirm Delete
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data pembayaran ini akan dihapus permanen. Hati-hati jika ini data keuangan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endpush