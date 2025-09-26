@extends('layouts.dashboard')

@section('title', 'Approval Company')

@section('content')
<div class="container-fluid px-4">
    <div class="row g-3 my-2">
        <div class="col-12">
            <div class="dashboard-card py-3 px-4 text-center">
                <h5 class="fw-bold mb-0">APPROVAL COMPANY</h5>
            </div>
        </div>
    </div>

    <div class="row g-3 my-4">
        <div class="col-12">
            <div class="p-4 bg-white shadow-sm rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <form method="GET" action="{{ route('superadmin.approval-company') }}" class="d-flex w-50 me-auto">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari Company..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nama Company</th>
                                <th scope="col">No HP</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Nama Bank</th>
                                <th scope="col">No Rekening</th>
                                <th scope="col">Penanggung Jawab</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($companies as $company)
                            <tr>
                                <td>{{ $company->id }}</td>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->no_hp }}</td>
                                <td>{{ $company->alamat }}</td>
                                <td>{{ $company->nama_bank }}</td>
                                <td>{{ $company->no_rekening }}</td>
                                <td>{{ $company->pj }}</td>
                                <td><span class="badge bg-secondary">{{ $company->status }}</span></td>
                                <td>
                                    <div class="d-flex">
                                        @if ($company->status === 'pending')
                                            <form action="{{ route('superadmin.approval-company.approve', $company->id) }}" method="POST" class="me-2">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Setujui">Approve</button>
                                            </form>
                                            <form action="{{ route('superadmin.approval-company.reject', $company->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" title="Tolak">Reject</button>
                                            </form>
                                        @endif
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