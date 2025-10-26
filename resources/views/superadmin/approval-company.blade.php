@extends('layouts.app')

@section('title', 'Riwayat Perusahaan Terdaftar')

@section('content')
<div class="container-fluid px-4">

    {{-- Header Page (Mengikuti style baru) --}}
    <div class="row g-3 my-2">
        <div class="col-12">
            <div class="p-3 bg-white shadow-sm rounded-3 text-center">
                <h3 class="fw-bold mb-0 text-primary">RIWAYAT PERUSAHAAN TERDAFTAR</h3>
            </div>
        </div>
    </div>
    
    {{-- Notifikasi Sukses/Error (Ditempatkan di luar kotak utama) --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Kotak Utama untuk Konten (Mengikuti style baru) --}}
    <div class="row g-3 my-4">
        <div class="col-12">
            <div class="p-4 bg-white shadow-sm rounded-3">
                
                {{-- Judul di dalam kotak (Opsional, menggantikan card-header lama) --}}
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-list-columns-reverse me-2"></i> Histori Perusahaan</h5>

                {{-- TABEL DATA - Responsif --}}
                <div class="table-responsive" style="min-height: 20px;">
                    <table class="table table-striped table-hover align-middle" id="companyHistoryTable" width="100%" cellspacing="0">
                        <thead class="table-primary text-white">
                            <tr class="text-center align-middle">
                                <th>ID</th>
                                <th>Nama Perusahaan</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th>Nama Bank</th>
                                <th>No Rekening</th>
                                <th>Username</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($companies as $company)
                            <tr class="text-center align-middle">
                                <td>{{ $company->id }}</td>
                                <td>{{ $company->nama_perusahaan }}</td>
                                <td>{{ $company->no_hp }}</td>
                                <td>{{ $company->alamat }}</td>
                                <td>{{ $company->nama_bank }}</td>
                                <td>{{ $company->no_rekening }}</td>
                                <td>{{ $company->penanggung_jawab ?? 'User Dihapus' }}</td>
                                <td>{{ $company->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if ($company->status === 'approved')
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Disetujui</span>
                                    @elseif ($company->status === 'rejected')
                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($company->status === 'pending')
                                        {{-- 
                                          MODIFIKASI DI SINI:
                                          Ganti dropdown dengan 2 tombol langsung dan 2 form tersembunyi
                                        --}}
                                        <div class="d-flex flex-nowrap justify-content-center">
                                            <button type="button" class="btn btn-sm btn-success me-1" 
                                                    title="Setujui"
                                                    onclick="showApproveAlert('{{ $company->id }}', '{{ $company->nama_perusahaan }}')">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                            
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    title="Tolak"
                                                    onclick="showRejectAlert('{{ $company->id }}', '{{ $company->nama_perusahaan }}')">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </div>

                                        <form id="form-approve-{{ $company->id }}" 
                                              action="{{ route('superadmin.companies.approve', $company) }}" 
                                              method="POST" style="display: none;">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                        
                                        <form id="form-reject-{{ $company->id }}" 
                                              action="{{ route('superadmin.companies.reject', $company) }}" 
                                              method="POST" style="display: none;">
                                            @csrf
                                            @method('PUT')
                                        </form>

                                    @else
                                        <span class="text-muted">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">Tidak ada riwayat perusahaan yang ditemukan.</td>
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
<script src="{{ asset('js/approval.js') }}"></script>
@endpush