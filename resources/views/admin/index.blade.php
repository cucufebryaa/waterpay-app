@extends('layouts.app')

@section('title', 'Data Petugas')

@section('content')
<div class="container-fluid px-4">
    
    {{-- Header Page --}}
    <div class="row g-3 my-2">
        <div class="col-12">
            <div class="p-3 bg-white shadow-sm rounded-3 text-center">
                <h3 class="fw-bold mb-0 text-primary">LIST DATA PETUGAS</h3>
            </div>
        </div>
    </div>
    
    {{-- Kotak Utama untuk Konten dan Filter --}}
    <div class="row g-3 my-4">
        <div class="col-12">
            <div class="p-4 bg-white shadow-sm rounded-3">
                
                {{-- Bagian Filter Pencarian dan Tombol Tambah --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <form method="GET" action="{{ route('admin.petugas.index') }}" class="d-flex w-100 w-md-50 me-auto">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari Nama / Username Petugas..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </form>
                    {{-- Ganti rute ke form tambah Petugas --}}
                    <a href="{{ route('admin.petugas.create') }}" class="btn btn-primary mt-2 mt-md-0">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Petugas
                    </a>
                </div>
                
                {{-- TABEL DATA PETUGAS - Responsif --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-primary text-white">
                            <tr>
                                <th>ID</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($petugas as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->user->username ?? 'N/A' }}</td>
                                <td>{{ $p->no_hp }}</td>
                                <td>{{ $p->alamat }}</td>
                                <td class="text-center">
                                    <div class="d-flex flex-nowrap justify-content-center">
                                        {{-- EDIT --}}
                                        <a href="{{ route('admin.petugas.edit', $p->id) }}" class="btn btn-sm btn-warning me-2" title="Edit Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        {{-- DELETE --}}
                                        <form action="{{ route('admin.petugas.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus Data">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada data Petugas yang ditemukan untuk perusahaan ini.</td>
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