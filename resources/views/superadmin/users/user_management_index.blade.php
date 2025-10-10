@extends('layouts.app')

@section('title', 'Manajemen Semua User')

@section('content')
<div class="container-fluid px-4">
    
    {{-- Header Page --}}
    <div class="row g-3 my-2">
        <div class="col-12">
            <div class="p-3 bg-white shadow-sm rounded-3 text-center">
                <h3 class="fw-bold mb-0 text-primary">LIST MANAGEMENT USER</h3>
            </div>
        </div>
    </div>
    
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
                            <tr>
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
                            <tr>
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
                                    <div class="d-flex flex-nowrap justify-content-center">
                                        {{-- Halaman Detail/Aksi Tambahan akan diarahkan di sini --}}
                                        <a href="#" class="btn btn-sm btn-info me-2" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
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