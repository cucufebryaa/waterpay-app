@extends('layouts.dashboard')

@section('title', 'Data Pelanggan')

@section('content')
<div class="container-fluid px-4">
    <div class="row g-3 my-2">
        <div class="col-12">
            <div class="dashboard-card py-3 px-4 text-center">
                <h5 class="fw-bold mb-0">TABEL DATA PELANGGAN</h5>
            </div>
        </div>
    </div>
    
    <div class="row g-3 my-4">
        <div class="col-12">
            <div class="p-4 bg-white shadow-sm rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <form method="GET" action="{{ route('superadmin.data-pelanggan') }}" class="d-flex w-50 me-auto">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari Company / Username..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </form>
                    <a href="#" class="btn btn-primary">+ Pelanggan</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">No HP</th>
                                <th scope="col">Username</th>
                                <th scope="col">Password</th>
                                <th scope="col">Company</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pelanggan as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->alamat }}</td>
                                <td>{{ $p->no_hp }}</td>
                                <td>{{ $p->user->username }}</td>
                                <td>
                                    <span class="text-muted fst-italic">[terenkripsi]</span>
                                    <button class="btn btn-sm btn-light ms-2 toggle-password-btn" data-password="{{ $p->user->password }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                                <td>{{ $p->company->name }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('superadmin.pelanggan.login-as', $p->id) }}" class="btn btn-sm btn-success me-2" title="Login Langsung">
                                            <i class="bi bi-box-arrow-in-right"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-warning me-2" title="Edit Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="#" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus Data">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection