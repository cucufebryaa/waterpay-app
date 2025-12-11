@extends('layouts.app')

@section('title', 'Manajemen Perusahaan')

@section('content')

<style>
    /* --- MODERN THEME VARIABLES --- */
    :root {
        --primary-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    /* Page Header */
    .page-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 2rem; border-bottom: 1px solid #e3e6f0; padding-bottom: 1rem;
    }
    .page-header h1 {
        font-size: 1.5rem; font-weight: 800; color: #1e293b; margin: 0;
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
        position: absolute; right: -15px; bottom: -15px;
        font-size: 5rem; opacity: 0.15; transform: rotate(-15deg); z-index: 1;
    }
    .bg-gradient-primary { background: var(--primary-gradient); }
    .bg-gradient-success { background: var(--success-gradient); }
    .bg-gradient-warning { background: var(--warning-gradient); }

    /* Table Container */
    .table-container {
        background: white; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #e8ecf1; overflow: hidden;
    }
    .modern-table { width: 100%; margin-bottom: 0; }
    .modern-table thead th {
        background: #f8fafc; color: #64748b; font-weight: 700;
        font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;
        padding: 1.25rem 1rem; border-bottom: 2px solid #e2e8f0; border-top: none;
    }
    .modern-table tbody td {
        padding: 1rem; vertical-align: middle; font-size: 0.9rem; border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .modern-table tbody tr:last-child td { border-bottom: none; }
    .modern-table tbody tr:hover { background-color: #f8fafc; }

    /* Badges */
    .badge-status {
        padding: 0.5em 0.9em; border-radius: 50rem; font-weight: 700; font-size: 0.7rem;
        display: inline-flex; align-items: center; gap: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .badge-success-soft { background-color: #dcfce7; color: #166534; }
    .badge-warning-soft { background-color: #fef3c7; color: #92400e; }
    .badge-danger-soft  { background-color: #fee2e2; color: #991b1b; }

    /* Action Buttons */
    .btn-icon {
        width: 34px; height: 34px; padding: 0; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.2s; border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .btn-icon:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .btn-approve { background: var(--success-gradient); color: white; }
    .btn-reject { background: var(--danger-gradient); color: white; }
    .btn-detail { background: #e0f2fe; color: #0284c7; }

    /* Search Input */
    .search-box {
        position: relative; max-width: 300px;
    }
    .search-box input {
        border-radius: 50rem; padding-left: 2.5rem; border: 1px solid #e2e8f0; background: #f8fafc;
    }
    .search-box i {
        position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;
    }
</style>

<div class="container-fluid px-0">
    
    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h1><i class="bi bi-building-check"></i> Daftar Perusahaan</h1>
            <p class="text-muted mb-0 small mt-1">Kelola pendaftaran dan status kemitraan PAMS.</p>
        </div>
        <div>
            <span class="badge bg-white text-dark border shadow-sm px-3 py-2 rounded-pill">
                <i class="bi bi-clock-history me-2 text-primary"></i>
                Updated: {{ now()->format('H:i') }}
            </span>
        </div>
    </div>

    <!-- STATISTICS CARDS -->
    <div class="row g-4 mb-4">
        @php
            // Hitung statistik sederhana dari collection $companies
            $total = $companies->count();
            $approved = $companies->where('status', 'approved')->count();
            $pending = $companies->where('status', 'pending')->count();
        @endphp

        <div class="col-md-4">
            <div class="card stat-card bg-gradient-primary">
                <div class="card-body">
                    <small class="text-white-50 text-uppercase fw-bold ls-1">Total Terdaftar</small>
                    <h2 class="fw-bold mt-2 mb-0">{{ $total }}</h2>
                    <div class="mt-2 small text-white-50">Perusahaan dalam sistem</div>
                    <i class="bi bi-buildings stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-success">
                <div class="card-body">
                    <small class="text-white-50 text-uppercase fw-bold ls-1">Aktif / Disetujui</small>
                    <h2 class="fw-bold mt-2 mb-0">{{ $approved }}</h2>
                    <div class="mt-2 small text-white-50">Mitra terverifikasi</div>
                    <i class="bi bi-check-circle stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-warning">
                <div class="card-body">
                    <small class="text-white-50 text-uppercase fw-bold ls-1">Perlu Persetujuan</small>
                    <h2 class="fw-bold mt-2 mb-0">{{ $pending }}</h2>
                    <div class="mt-2 small text-white-50">Menunggu review admin</div>
                    <i class="bi bi-hourglass-split stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="card border-0 shadow-none bg-transparent">
        
        <!-- Filter & Search (Opsional, tampilan saja) -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark m-0"><i class="bi bi-list-stars me-2 text-primary"></i>Data Perusahaan</h5>
            {{-- Search Box Sederhana (Fungsionalitas perlu JS tambahan jika ingin client-side search) --}}
            {{-- <div class="search-box d-none d-md-block">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" placeholder="Cari perusahaan...">
            </div> --}}
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center gap-3">
                <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-container">
            <div class="table-responsive">
                <table class="table modern-table" id="companyHistoryTable">
                    <thead>
                        <tr>
                            <th class="ps-4">Perusahaan</th>
                            <th>Email</th>
                            <th>Bank & Rekening</th>
                            <th>Penanggung Jawab</th>
                            <th>Tgl Daftar</th>
                            <th class="text-center">Status</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($companies as $company)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $company->nama_perusahaan }}</div>
                                <small class="text-muted d-block text-truncate" style="max-width: 150px;">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $company->alamat }}
                                </small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-whatsapp text-success"></i>
                                    <span>{{ $company->no_hp }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $company->nama_bank }}</div>
                                <span class="badge bg-light text-secondary border font-monospace">
                                    {{ $company->no_rekening }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 30px; height: 30px; border: 1px solid #e2e8f0;">
                                        {{ substr($company->penanggung_jawab ?? 'U', 0, 1) }}
                                    </div>
                                    <span>{{ $company->penanggung_jawab ?? 'User Dihapus' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-secondary small">
                                    {{ $company->created_at->format('d M Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($company->status === 'approved')
                                    <span class="badge-status badge-success-soft">
                                        <i class="bi bi-check-circle-fill"></i> Aktif
                                    </span>
                                @elseif ($company->status === 'rejected')
                                    <span class="badge-status badge-danger-soft">
                                        <i class="bi bi-x-circle-fill"></i> Ditolak
                                    </span>
                                @else
                                    <span class="badge-status badge-warning-soft">
                                        <i class="bi bi-clock-history"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if ($company->status === 'pending')
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn-icon btn-approve" 
                                                onclick="confirmAction('approve', '{{ $company->id }}', '{{ $company->nama_perusahaan }}')"
                                                title="Setujui Pendaftaran">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        
                                        <button type="button" class="btn-icon btn-reject" 
                                                onclick="confirmAction('reject', '{{ $company->id }}', '{{ $company->nama_perusahaan }}')"
                                                title="Tolak Pendaftaran">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>

                                    <form id="form-approve-{{ $company->id }}" 
                                          action="{{ route('superadmin.companies.approve', $company) }}" 
                                          method="POST" class="d-none">
                                        @csrf @method('PUT')
                                    </form>
                                    
                                    <form id="form-reject-{{ $company->id }}" 
                                          action="{{ route('superadmin.companies.reject', $company) }}" 
                                          method="POST" class="d-none">
                                        @csrf @method('PUT')
                                    </form>
                                @else
                                    <span class="text-muted small fst-italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 text-gray-300"></i>
                                    <p class="mb-0">Tidak ada data perusahaan ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Fungsi Konfirmasi Universal (SweetAlert2)
    function confirmAction(type, id, name) {
        let title, text, icon, confirmBtnText, confirmBtnColor;

        if (type === 'approve') {
            title = 'Setujui Perusahaan?';
            text = `Anda akan mengaktifkan akun untuk "${name}". Mereka akan dapat login ke sistem.`;
            icon = 'question';
            confirmBtnText = 'Ya, Setujui!';
            confirmBtnColor = '#10b981'; // Green
        } else {
            title = 'Tolak Pendaftaran?';
            text = `Anda akan menolak pengajuan dari "${name}". Tindakan ini tidak dapat dibatalkan.`;
            icon = 'warning';
            confirmBtnText = 'Ya, Tolak!';
            confirmBtnColor = '#ef4444'; // Red
        }

        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmBtnColor,
            cancelButtonColor: '#64748b',
            confirmButtonText: confirmBtnText,
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-4' // Rounded corners for SweetAlert
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form sesuai ID dan Tipe
                document.getElementById(`form-${type}-${id}`).submit();
            }
        });
    }
</script>
@endpush