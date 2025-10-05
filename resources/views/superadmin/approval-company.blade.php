@extends('layouts.app')

@section('title', 'Riwayat Perusahaan Terdaftar')

@section('content')
<div class="mb-4">
<h1 class="h3 mb-1">Riwayat Perusahaan</h1>
<p class="text-muted">Daftar lengkap semua perusahaan yang terdaftar, beserta status persetujuannya.</p>
</div>

{{-- Notifikasi Sukses/Error (Jika diarahkan dari Approval) --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Tabel Riwayat Perusahaan --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-list-columns-reverse me-2"></i> Histori Perusahaan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-sm" id="companyHistoryTable" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>No. Rekening</th>
                        <th>PJ (User)</th>
                        <th>Created At</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi (Jika Pending)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($companies as $company)
                    <tr>
                        <td>{{ $company->id }}</td>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->no_hp }}</td>
                        <td>{{ $company->alamat }}</td>
                        <td>{{ $company->no_rekening }}</td>
                        {{-- Nama Penanggung Jawab dari relasi owner --}}
                        <td>{{ $company->owner->name ?? 'User Dihapus' }}</td>
                        <td>{{ $company->created_at->format('Y-m-d H:i') }}</td>
                        <td class="text-center">
                            @if ($company->status === 'approved')
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Disetujui</span>
                            @elseif ($company->status === 'rejected')
                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Pending</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($company->status === 'pending')
                                {{-- Dropdown Action (Sama seperti di halaman pending) --}}
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear-fill"></i> Proses
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form action="{{ route('superadmin.companies.approve', $company) }}" method="POST" style="display: block;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="dropdown-item text-success" 
                                                        onclick="return confirm('Anda yakin ingin MENYETUJUI perusahaan {{ $company->name }}?')">
                                                    <i class="bi bi-check-circle me-2"></i> Approve
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('superadmin.companies.reject', $company) }}" method="POST" style="display: block;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="dropdown-item text-danger" 
                                                        onclick="return confirm('Anda yakin ingin MENOLAK perusahaan {{ $company->name }}?')">
                                                    <i class="bi bi-x-circle me-2"></i> Reject
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <span class="text-muted">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Tidak ada riwayat perusahaan yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Vanilla JS untuk inisialisasi DataTable --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
// Asumsi DataTables sudah tersedia di layouts.app Anda
if (typeof $ !== 'undefined' && $.fn.DataTable) {
$('#companyHistoryTable').DataTable({
"pageLength": 10,
"ordering": true,
"order": [[6, 'desc']], // Urutkan berdasarkan Created At
"info": true,
"searching": true,
"language": {
"url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json" // Opsional: gunakan bahasa Indonesia
}
});
}
});
</script>

@endpush