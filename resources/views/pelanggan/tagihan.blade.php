@extends('layouts.app')

@section('title', 'Tagihan & Pembayaran')

@section('content')

<style>
    /* --- MODERN THEME VARIABLES --- */
    :root {
        --primary-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    /* --- STAT CARDS --- */
    .stat-card {
        border: none;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-icon {
        position: absolute;
        right: -15px;
        bottom: -15px;
        font-size: 5rem;
        opacity: 0.15;
        transform: rotate(-15deg);
    }
    .bg-gradient-danger { background: var(--danger-gradient); }
    .bg-gradient-success { background: var(--success-gradient); }

    /* --- TABS NAVIGATION --- */
    .nav-pills-custom {
        background: #f1f5f9;
        padding: 6px;
        border-radius: 50rem;
        display: inline-flex;
        width: 100%;
        margin-bottom: 1.5rem;
    }
    .nav-pills-custom .nav-link {
        border-radius: 50rem;
        color: #64748b;
        font-weight: 600;
        padding: 10px 20px;
        transition: all 0.3s;
        width: 100%;
        text-align: center;
        border: none;
    }
    .nav-pills-custom .nav-link.active {
        background: white;
        color: #2563eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    /* --- BILL CARD STYLE --- */
    .bill-card {
        border: none;
        border-radius: 16px;
        background: white;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .bill-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
    }
    .bill-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 6px;
        background: #e2e8f0;
    }
    .bill-card.status-unpaid::before { background: #ef4444; }
    .bill-card.status-pending::before { background: #f59e0b; }
    
    .price-large {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: #1e293b;
    }
    
    .btn-pay {
        background: var(--primary-gradient);
        border: none;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        transition: all 0.3s;
        color: white;
        font-weight: 600;
    }
    .btn-pay:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(59, 130, 246, 0.4);
        color: white;
    }

    /* --- TABLE STYLE --- */
    .table-history th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        font-weight: 700;
        background-color: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem;
    }
    .table-history td {
        vertical-align: middle;
        padding: 1rem;
        font-size: 0.9rem;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .stat-icon { font-size: 3.5rem; }
        .price-large { font-size: 1.5rem; }
    }
</style>

<!-- HEADER SECTION -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-0">Halo, {{ Auth::user()->name }}! 👋</h4>
            <small class="text-muted">Cek tagihan air dan riwayat pembayaran Anda di sini.</small>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
                <i class="bi bi-calendar-event me-2 text-primary"></i>
                {{ \Carbon\Carbon::now()->isoFormat('DD MMMM Y') }}
            </span>
        </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="row g-3">
        <!-- Card Total Tunggakan -->
        <div class="col-md-6">
            <div class="card stat-card bg-gradient-danger text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 2;">
                        <div>
                            <small class="text-white-50 fw-bold text-uppercase ls-1">Total Tagihan Aktif</small>
                            <h2 class="fw-bold mb-0 mt-1">
                                Rp {{ number_format($tagihanBelumBayar->sum('total_bayar_est'), 0, ',', '.') }}
                            </h2>
                            <span class="badge bg-white bg-opacity-20 mt-2 rounded-pill">
                                {{ $tagihanBelumBayar->count() }} Tagihan Belum Lunas
                            </span>
                        </div>
                    </div>
                    <i class="bi bi-wallet2 stat-icon text-white"></i>
                </div>
            </div>
        </div>

        <!-- Card Riwayat -->
        <div class="col-md-6">
            <div class="card stat-card bg-white h-100 border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 2;">
                        <div>
                            <small class="text-muted fw-bold text-uppercase ls-1">Total Pembayaran Sukses</small>
                            <h2 class="fw-bold text-success mb-0 mt-1">
                                {{ $riwayatPembayaran->count() }}
                            </h2>
                            <small class="text-muted">Transaksi Berhasil</small>
                        </div>
                    </div>
                    <i class="bi bi-receipt-cutoff stat-icon text-success"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="row justify-content-center">
    <div class="col-md-12">
        
        <!-- Alerts -->
        @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center gap-3">
            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
            <div>{{ session('error') }}</div>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center gap-3">
            <i class="bi bi-check-circle-fill fs-4"></i>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        <!-- Tabs Navigation -->
        <ul class="nav nav-pills-custom shadow-sm" id="pills-tab" role="tablist">
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link active" id="pills-unpaid-tab" data-bs-toggle="pill" data-bs-target="#pills-unpaid" type="button" role="tab">
                    <i class="bi bi-exclamation-circle me-2"></i>Tagihan Belum Lunas
                    @if($tagihanBelumBayar->count() > 0)
                        <span class="badge bg-danger ms-1 rounded-pill">{{ $tagihanBelumBayar->count() }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link" id="pills-history-tab" data-bs-toggle="pill" data-bs-target="#pills-history" type="button" role="tab">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Pembayaran
                </button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            
            <!-- TAB 1: TAGIHAN BELUM LUNAS -->
            <div class="tab-pane fade show active" id="pills-unpaid" role="tabpanel">
                <div class="row g-4">
                    @forelse($tagihanBelumBayar as $bill)
                    <div class="col-lg-4 col-md-6">
                        <div class="bill-card h-100 {{ $bill->status_pembayaran == 'pending' ? 'status-pending' : 'status-unpaid' }}">
                            <div class="card-body p-4 d-flex flex-column h-100">
                                
                                <!-- Header Card -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">
                                            <i class="bi bi-calendar-week me-1 text-primary"></i>
                                            {{ $bill->created_at->translatedFormat('F Y') }}
                                        </h6>
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            ID: INV-{{ $bill->id }}
                                        </small>
                                    </div>
                                    @if($bill->status_pembayaran == 'pending')
                                        <span class="badge bg-warning text-dark border border-warning rounded-pill">
                                            <i class="bi bi-hourglass-split me-1"></i>Menunggu
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill">
                                            Belum Bayar
                                        </span>
                                    @endif
                                </div>

                                <!-- Detail Usage -->
                                <div class="bg-light rounded-3 p-3 mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted small">Pemakaian Air</span>
                                        <span class="fw-bold text-dark">{{ $bill->total_pakai }} m³</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted small">Tagihan Dasar</span>
                                        <span class="fw-bold text-dark">Rp {{ number_format($bill->total_tagihan, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    @if($bill->is_telat)
                                        <div class="d-flex justify-content-between text-danger mt-2 pt-2 border-top">
                                            <span class="small fw-bold">
                                                <i class="bi bi-exclamation-circle-fill me-1"></i>Denda Telat
                                            </span>
                                            <span class="fw-bold">+ Rp {{ number_format($bill->denda_est, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Footer Card (Price & Action) -->
                                <div class="mt-auto">
                                    <div class="mb-3">
                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Total Pembayaran</small>
                                        <div class="price-large text-primary">
                                            Rp {{ number_format($bill->total_bayar_est, 0, ',', '.') }}
                                        </div>
                                        @if($bill->is_telat)
                                            <small class="text-danger" style="font-size: 0.75rem;">
                                                *Jatuh tempo terlewati ({{ $bill->jatuh_tempo_display }})
                                            </small>
                                        @else
                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                Jatuh tempo: {{ $bill->jatuh_tempo_display }}
                                            </small>
                                        @endif
                                    </div>

                                    @if($bill->status_pembayaran == 'pending' && $bill->xendit_payment_url)
                                        <a href="{{ $bill->xendit_payment_url }}" target="_blank" class="btn btn-warning w-100 py-3 rounded-pill fw-bold text-dark shadow-sm">
                                            <i class="bi bi-box-arrow-up-right me-2"></i>Lanjutkan Pembayaran
                                        </a>
                                    @else
                                        <form action="{{ route('pelanggan.tagihan.pay', $bill->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-pay w-100 py-3 rounded-pill">
                                                <i class="bi bi-credit-card-2-front-fill me-2"></i>Bayar Sekarang
                                            </button>
                                        </form>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3">
                            <div class="bg-success bg-opacity-10 p-4 rounded-circle d-inline-block shadow-sm">
                                <i class="bi bi-patch-check-fill text-success display-4"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark">Hebat! Tidak Ada Tagihan.</h4>
                        <p class="text-muted">Terima kasih telah membayar tagihan air tepat waktu.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- TAB 2: RIWAYAT PEMBAYARAN -->
            <div class="tab-pane fade" id="pills-history" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-receipt me-2 text-primary"></i>Daftar Transaksi Sukses</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-history align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Periode Tagihan</th>
                                    <th>Waktu Bayar</th>
                                    <th>Metode</th>
                                    <th class="text-end">Total Dibayar</th>
                                    <th class="text-center pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatPembayaran as $history)
                                <tr class="bg-white">
                                    <td class="ps-4">
                                        <span class="fw-bold text-dark d-block">
                                            {{ $history->pemakaian->created_at->translatedFormat('F Y') }}
                                        </span>
                                        <small class="text-muted">
                                            Pakai: {{ $history->pemakaian->total_pakai }} m³
                                        </small>
                                    </td>
                                    <td>
                                        <span class="d-block text-dark">{{ $history->tanggal_bayar->translatedFormat('d M Y') }}</span>
                                        <small class="text-muted">{{ $history->tanggal_bayar->format('H:i') }} WIB</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border">
                                            {{ $history->payment_channel ?? 'XENDIT' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-primary">
                                            Rp {{ number_format($history->total_bayar, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle-fill me-1"></i>LUNAS
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 text-gray-300"></i>
                                        <p>Belum ada riwayat pembayaran yang tercatat.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection