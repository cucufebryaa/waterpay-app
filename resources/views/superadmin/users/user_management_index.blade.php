@extends('layouts.app')

@section('title', 'Manajemen Semua User')

@section('content')

<style>
    /* --- MODERN THEME VARIABLES --- */
    :root {
        --primary-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --info-gradient: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
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
    .bg-gradient-info { background: var(--info-gradient); }
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
    .badge-superadmin { background-color: #e0f2fe; color: #0284c7; }
    .badge-admin { background-color: #dcfce7; color: #166534; }
    .badge-petugas { background-color: #fef3c7; color: #92400e; }
    .badge-pelanggan { background-color: #f1f5f9; color: #475569; }

    /* Action Buttons */
    .btn-icon {
        width: 34px; height: 34px; padding: 0; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.2s; border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .btn-icon:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .btn-detail { background: #e0f2fe; color: #0284c7; }
    .btn-delete { background: var(--danger-gradient); color: white; }

    /* Search Input */
    .search-form .input-group {
        border-radius: 50rem; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }
    .search-form input { border: none; padding-left: 1.5rem; background: #f8fafc; }
    .search-form input:focus { background: white; box-shadow: none; }
    .search-form button { border-radius: 0 50rem 50rem 0; padding-left: 1.5rem; padding-right: 1.5rem; }

    /* Detail Popup Styling (Inline untuk SweetAlert) */
    .detail-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .detail-item { display: flex; justify-content: space-between; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; }
    .detail-item:last-child { border-bottom: none; }
    .detail-key { font-size: 0.85rem; color: #64748b; }
    .detail-value { font-size: 0.9rem; font-weight: 600; color: #1e293b; text-align: right; }
</style>

<div class="container-fluid px-0">
    
    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h1><i class="bi bi-people-fill"></i> Data Pengguna</h1>
            <p class="text-muted mb-0 small mt-1">Kelola seluruh akun pengguna dalam sistem Waterpay.</p>
        </div>
        <div>
            <span class="badge bg-white text-dark border shadow-sm px-3 py-2 rounded-pill">
                <i class="bi bi-database me-2 text-primary"></i>
                Total: {{ count($userData) }} User
            </span>
        </div>
    </div>

    <!-- STATS CARDS -->
    <div class="row g-4 mb-4">
        @php
            $usersCollection = collect($userData);
            $countAdmin = $usersCollection->where('role', 'Admin')->count();
            $countPetugas = $usersCollection->where('role', 'Petugas')->count();
            $countSuper = $usersCollection->where('role', 'Superadmin')->count();
        @endphp

        <div class="col-md-4">
            <div class="card stat-card bg-gradient-primary">
                <div class="card-body">
                    <small class="text-white-50 text-uppercase fw-bold ls-1">Superadmin</small>
                    <h2 class="fw-bold mt-2 mb-0">{{ $countSuper }}</h2>
                    <div class="mt-2 small text-white-50">Pengelola Sistem</div>
                    <i class="bi bi-shield-lock-fill stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-info">
                <div class="card-body">
                    <small class="text-white-50 text-uppercase fw-bold ls-1">Admin Perusahaan</small>
                    <h2 class="fw-bold mt-2 mb-0">{{ $countAdmin }}</h2>
                    <div class="mt-2 small text-white-50">Mitra PAMS</div>
                    <i class="bi bi-building-fill stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-warning">
                <div class="card-body">
                    <small class="text-white-50 text-uppercase fw-bold ls-1">Petugas Lapangan</small>
                    <h2 class="fw-bold mt-2 mb-0">{{ $countPetugas }}</h2>
                    <div class="mt-2 small text-white-50">Pencatat Meter</div>
                    <i class="bi bi-person-badge-fill stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="card border-0 shadow-none bg-transparent">
        
        <!-- Filter & Search -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <h5 class="fw-bold text-dark m-0"><i class="bi bi-list-task me-2 text-primary"></i>List User</h5>
            
            <form method="GET" action="{{ route('superadmin.management-users.index') }}" class="search-form w-100 w-md-auto" style="min-width: 300px;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari user..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </form>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center gap-3">
                <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-container">
            <div class="table-responsive">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th class="ps-4">User Info</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th>Perusahaan</th>
                            <th class="text-center">Role</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($userData as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold flex-shrink-0" style="width: 40px; height: 40px; border: 1px solid #e2e8f0;">
                                        {{ substr($user['nama_lengkap'] ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $user['nama_lengkap'] }}</div>
                                        <div class="small text-muted">@ {{ $user['username'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-secondary small">
                                    <i class="bi bi-envelope me-1"></i> {{ $user['email'] }}
                                </span>
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                    {{ $user['alamat'] ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @if($user['nama_perusahaan'])
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-building text-muted small"></i>
                                        <span class="fw-semibold text-dark small">{{ $user['nama_perusahaan'] }}</span>
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $roleClass = 'badge-pelanggan';
                                    $icon = 'bi-person';
                                    if($user['role'] == 'Superadmin') { $roleClass = 'badge-superadmin'; $icon = 'bi-shield-fill'; }
                                    elseif($user['role'] == 'Admin') { $roleClass = 'badge-admin'; $icon = 'bi-building'; }
                                    elseif($user['role'] == 'Petugas') { $roleClass = 'badge-petugas'; $icon = 'bi-person-badge'; }
                                @endphp
                                <span class="badge-status {{ $roleClass }}">
                                    <i class="bi {{ $icon }}"></i> {{ $user['role'] }}
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn-icon btn-detail" 
                                            onclick="showDetailAlert({{ json_encode($user) }})"
                                            title="Lihat Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    
                                    <button type="button" class="btn-icon btn-delete" 
                                            onclick="showDeleteAlert('{{ $user['id'] }}', '{{ $user['username'] }}')"
                                            title="Hapus User">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                                
                                <form id="form-delete-{{ $user['id'] }}" 
                                      action="{{ route('superadmin.management-users.destroy', $user['id']) }}" 
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-person-x fs-1 d-block mb-3 text-gray-300"></i>
                                    <p class="mb-0">Tidak ada user ditemukan.</p>
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
    // Helper function untuk detail list
    function createDetailList(items, skipKeys = []) {
        let listHtml = '<div class="detail-list bg-light p-3 rounded-3 mt-2 border">';
        let hasData = false;
        
        for (const [key, value] of Object.entries(items)) {
            if (!skipKeys.includes(key) && value !== null && value !== '') {
                hasData = true;
                // Format Key: snake_case to Title Case
                const formattedKey = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                
                listHtml += `
                    <div class="detail-item">
                        <span class="detail-key">${formattedKey}</span>
                        <span class="detail-value">${value}</span>
                    </div>
                `;
            }
        }
        
        if (!hasData) {
            listHtml += '<p class="text-muted small m-0 text-center">Tidak ada informasi tambahan.</p>';
        }

        listHtml += '</div>';
        return listHtml;
    }

    function showDetailAlert(data) {
        // --- 1. Info Utama ---
        const infoUtama = `
            <div class="text-center mb-4">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center text-primary mb-2" style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold; border: 2px solid #e2e8f0;">
                    ${(data.nama_lengkap || 'U').charAt(0)}
                </div>
                <h5 class="fw-bold mb-0">${data.nama_lengkap}</h5>
                <small class="text-muted">@${data.username}</small>
                <div class="mt-2">
                    <span class="badge bg-secondary">${data.role}</span>
                </div>
            </div>
        `;

        // --- 2. Detail Role Specific ---
        let roleHtml = '';
        if (data.role_specific_info) {
            roleHtml += `<h6 class="fw-bold text-start mb-2 small text-uppercase text-muted"><i class="bi bi-info-circle me-1"></i> Detail ${data.role}</h6>`;
            const skipRoleKeys = ['id', 'id_user', 'id_company', 'created_at', 'updated_at', 'email_verified_at', 'password'];
            roleHtml += createDetailList(data.role_specific_info, skipRoleKeys);
        }

        // --- 3. Detail Perusahaan ---
        let companyHtml = '';
        if (data.company_info) {
            companyHtml += `<div class="mt-3"><h6 class="fw-bold text-start mb-2 small text-uppercase text-muted"><i class="bi bi-building me-1"></i> Perusahaan</h6>`;
            const skipCompanyKeys = ['id', 'created_at', 'updated_at'];
            companyHtml += createDetailList(data.company_info, skipCompanyKeys);
            companyHtml += '</div>';
        }

        const fullContent = infoUtama + roleHtml + companyHtml;

        Swal.fire({
            html: fullContent,
            showCloseButton: true,
            showConfirmButton: false,
            width: '500px',
            customClass: {
                popup: 'rounded-4 border-0 shadow-lg'
            }
        });
    }

    function showDeleteAlert(userId, username) {
        Swal.fire({
            title: 'Hapus User?',
            text: `Anda yakin ingin menghapus user "${username}"? Tindakan ini permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + userId).submit();
            }
        });
    }
</script>
@endpush