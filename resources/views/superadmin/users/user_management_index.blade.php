@extends('layouts.app')

@section('title', 'Manajemen Semua User')

{{-- Tambahan: Tampilkan notifikasi sukses/error dari proses delete --}}
@section('content')
<div class="container-fluid px-4">
    
    {{-- Header Page --}}
    <div class="row g-3 my-2">
        <div class="col-12">
            <div class="p-3 bg-white shadow-sm rounded-3 text-center">
                <h3 class="fw-bold mb-0 text-primary">LIST DATA PENGGUNA WATERPAY APP</h3>
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
    
    {{-- Kotak Utama untuk Konten dan Filter --}}
    <div class="row g-3 my-4">
        <div class="col-12">
            <div class="p-4 bg-white shadow-sm rounded-3">
                
                {{-- Bagian Filter Pencarian --}}
                <div class="d-flex flex-wrap justify-content-start align-items-center mb-4">
                    <form method="GET" action="{{ route('superadmin.management-users.index') }}" class="d-flex w-100 w-md-50 me-auto">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari Username..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </form>
                </div>
                
                {{-- TABEL DATA USER - Responsif --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-primary text-white">
                            <tr class="text-center align-middle">
                                <th>ID</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Nama Perusahaan</th>
                                <th>Status (Role)</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($userData as $user)
                            <tr class="text-center align-middle">
                                <td>{{ $user['id'] }}</td>
                                <td>{{ $user['username'] }}</td>
                                <td>{{ $user['nama_lengkap'] }}</td> 
                                <td>{{ $user['email'] }}</td>
                                <td>{{ $user['alamat'] }}</td>
                                <td>{{ $user['nama_perusahaan'] }}</td>
                                <td>
                                    <span class="badge 
                                        @if($user['role'] == 'Superadmin') bg-info 
                                        @elseif($user['role'] == 'Admin') bg-success 
                                        @elseif($user['role'] == 'Petugas') bg-warning text-dark
                                        @else bg-secondary @endif">
                                        {{ $user['role'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{-- 
                                      MODIFIKASI DI SINI:
                                      Mengganti <a> dengan <button> dan menambahkan form tersembunyi
                                    --}}
                                    <div class="d-flex flex-nowrap justify-content-center">
                                        <button type="button" class="btn btn-sm btn-info me-2" title="Detail"
                                                {{-- Mengirim seluruh data user (termasuk data popup) sebagai JSON --}}
                                                onclick="showDetailAlert({{ json_encode($user) }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                                onclick="showDeleteAlert('{{ $user['id'] }}', '{{ $user['username'] }}')">
                                            <i class="bi bi-trash"></i>
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
                                <td colspan="8" class="text-center py-4 text-muted">Tidak ada user yang ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .popup-profesional {
        border-radius: 0.75rem !important;
        padding: 0 !important;
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
        border: 0 !important;
    }
    .popup-profesional .swal2-header {
        background-color: #f8f9fa;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #dee2e6;
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
        margin: 0;
        width: 100%;
        box-sizing: border-box;
    }
    .popup-profesional .swal2-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #0d6efd;
        margin: 0;
        text-align: left;
    }

    /* 3. KUSTOMISASI KONTEN (Area HTML) */
    .popup-profesional .swal2-content {
        padding: 1.5rem !important;
        text-align: left !important;
        font-size: 1rem;
    }

    /* 4. BLOK INFO (MODERN "CARD") */
    .info-card {
        background-color: #ffffff;
        /* Shadow yang sangat halus untuk efek "mengambang" */
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0; /* Border sangat tipis */
        border-radius: 0.5rem; /* Sesuai card Bootstrap */
        padding: 1.25rem;
        margin-bottom: 1rem;
        height: 100%;
    }
    .info-card h5 {
        font-weight: 600;
        color: #343a40; /* Lebih gelap dari primary */
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    /* 5. STYLE KEY-VALUE PAIR (PALING PENTING) */
    .detail-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem; /* Jarak antar baris */
    }
    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 0.75rem;
        border-bottom: 1px dashed #e9ecef; /* Pemisah dashed yang halus */
    }
    .detail-item:last-child {
        border-bottom: 0; /* Hapus border di item terakhir */
    }
    .detail-key {
        font-size: 0.9em;
        color: #6c757d; /* text-muted */
        text-align: left;
        padding-right: 1rem;
    }
    .detail-value {
        font-size: 0.95em;
        font-weight: 600;
        color: #212529; /* text-dark */
        text-align: right;
    }
    /* Style khusus untuk 'badge' jika ada */
    .detail-value .badge {
        font-size: 0.9em;
    }

    /* 6. KUSTOMISASI TOMBOL (Tetap sama) */
    .popup-profesional .swal2-actions {
        padding: 0 1.5rem 1.5rem !important;
        margin: 0;
        width: 100%;
        box-sizing: border-box;
    }
    .popup-profesional .swal2-close {
        width: 32px;
        height: 32px;
        border-radius: 75%;
        border: none;
        background-color: #f8d7da;
        color: #b02a37; 
        font-size: 2.2rem;
        margin-right: 10px;
        margin-top: 10px;
        line-height: 30px;
        transition: all 0.2s ease-in-out;
    }

    /* Efek Hover yang diminta */
    .popup-profesional .swal2-close:hover {
        background-color: #dc3545; /* Merah solid (Bootstrap danger) */
        color: #ffffff; /* '×' menjadi putih */
        transform: scale(1.1) rotate(90deg); /* Efek zoom & putar */
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4); /* Bayangan merah */
    }
</style>
<script>
    function createDetailList(items, skipKeys = []) {
        let listHtml = '<div class="detail-list">';
        
        for (const [key, value] of Object.entries(items)) {
            if (!skipKeys.includes(key) && value !== null && value !== '') {
                // Format 'nama_lengkap' -> 'Nama Lengkap'
                const formattedKey = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                // Cek jika value adalah 'status' untuk styling
                let formattedValue = value;
                if (key === 'status') {
                    if (value === 'approved') {
                        formattedValue = '<span class="badge bg-success">Approved</span>';
                    } else if (value === 'rejected') {
                        formattedValue = '<span class="badge bg-danger">Rejected</span>';
                    } else if (value === 'pending') {
                        formattedValue = '<span class="badge bg-warning text-dark">Pending</span>';
                    }
                }

                listHtml += `
                    <div class="detail-item">
                        <span class="detail-key">${formattedKey}</span>
                        <span class="detail-value">${formattedValue}</span>
                    </div>
                `;
            }
        }
        
        // Jika tidak ada data yang ditampilkan setelah difilter
        if (listHtml === '<div class="detail-list">') {
            listHtml += '<p class="text-muted small m-0">Tidak ada data detail yang tersedia.</p>';
        }

        listHtml += '</div>';
        return listHtml;
    }

    function showDetailAlert(data) {
        let col1Html = '<div class="col-lg-6 mb-3 mb-lg-0">';
        col1Html += '<div class="info-card">'; 
        col1Html += '<h5 class="mb-3"><i class="bi bi-person-circle me-2"></i>Info Akun</h5>';
        
        const baseUserItems = {
            'ID User': data.base_user_info.id,
            'Username': data.base_user_info.username,
            'Email': data.base_user_info.email,
            'Role': `<span class="badge bg-info">${data.role}</span>`,
            'Akun Dibuat': new Date(data.base_user_info.created_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' })
        };
        col1Html += createDetailList(baseUserItems);
        col1Html += '</div></div>'; 

        // --- Blok 2: Info Role (Kolom Kanan) ---
        let col2Html = '<div class="col-lg-6">';
        col2Html += '<div class="info-card">'; 
        if (data.role_specific_info) {
            col2Html += `<h5 class="mb-3"><i class="bi bi-person-badge me-2"></i>Info ${data.role}</h5>`;
            const skipRoleKeys = ['id', 'id_user', 'id_company', 'created_at', 'updated_at', 'email_verified_at', 'password'];
            col2Html += createDetailList(data.role_specific_info, skipRoleKeys);
        } else {
            col2Html += `<h5 class="mb-3"><i class="bi bi-person-badge me-2"></i>Info ${data.role}</h5>`;
            col2Html += createDetailList({}); 
        }
        col2Html += '</div></div>'; 

        // --- Blok 3: Info Perusahaan (Baris Bawah, Penuh) ---
        let row2Html = '';
        if (data.company_info) {
            row2Html = '<div class="col-lg-12 mt-3">'; 
            row2Html += '<div class="info-card">'; 
            row2Html += '<h5 class="mb-3"><i class="bi bi-building me-2"></i>Info Perusahaan</h5>';
            const skipCompanyKeys = ['id', 'created_at', 'updated_at'];
            row2Html += createDetailList(data.company_info, skipCompanyKeys);
            row2Html += '</div></div>'; 
        }

        // --- GABUNGKAN SEMUA BLOK ---
        let htmlContent = `
            <div class="container-fluid">
                <div class="row">
                    ${col1Html}
                    ${col2Html}
                </div>
                <div class="row">
                    ${row2Html}
                </div>
            </div>
        `;

        // --- PANGGIL SWEETALERT (DENGAN PERUBAHAN) ---
        Swal.fire({
            title: `<i class="bi bi-person-vcard me-2"></i> Detail User: ${data.username}`,
            html: htmlContent,
            icon: null,
            width: '900px', 
            
            // --- PERUBAHAN DI SINI ---
            showCloseButton: true,      // 1. Tampilkan tombol 'X'
            showConfirmButton: false,   // 2. Sembunyikan tombol "Tutup" di bawah
            // --- SELESAI PERUBAHAN ---
            
            customClass: {
                popup: 'popup-profesional',
                header: 'popup-profesional-header',
                title: 'swal2-title',
                content: 'swal2-content'
                // 3. 'closeButton' dan 'confirmButton' dihapus dari sini
            }
        });
    }
    function showDeleteAlert(userId, username) {
        Swal.fire({
            title: 'Hapus User?',
            text: `Anda yakin ingin menghapus user "${username}"? Tindakan ini tidak dapat dibatalkan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + userId).submit();
            }
        });
    }
</script>
@endpush