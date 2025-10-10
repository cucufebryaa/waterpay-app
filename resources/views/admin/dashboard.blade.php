@extends('layouts.app')

@section('title', 'Dashboard Operasional')

@section('content')
<div class="container-fluid px-4">
    {{-- Header Selamat Datang --}}
    <div class="alert alert-light p-3 shadow-sm rounded-3">
        <h4 class="mb-0 text-dark">Selamat Datang, {{ Auth::user()->name }}!</h4>
        <p class="mb-0 text-muted">Berikut adalah ringkasan operasional perusahaan Anda.</p>
    </div>

    {{-- Kartu Metrik --}}
    <div class="row mt-4 g-4">
        {{-- Total Petugas --}}
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white rounded-3 p-3 me-3">
                        <i class="bi bi-person-workspace fs-3"></i>
                    </div>
                    <div>
                        <h6 class="card-title text-muted mb-1">Total Petugas</h6>
                        <p class="fs-4 fw-bold mb-0 text-primary">{{ $totalPetugas }}</p> 
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Total Pelanggan --}}
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white rounded-3 p-3 me-3">
                        <i class="bi bi-person-lines-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="card-title text-muted mb-1">Total Pelanggan</h6>
                        <p class="fs-4 fw-bold mb-0 text-success">{{ $totalPelanggan }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pembayaran Bulan Ini --}}
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info text-white rounded-3 p-3 me-3">
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                    <div>
                        <h6 class="card-title text-muted mb-1">Pembayaran Bulan Ini</h6>
                        <p class="fs-4 fw-bold mb-0 text-info">Rp {{ $pembayaranBulanIni }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Total Tunggakan --}}
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger text-white rounded-3 p-3 me-3">
                        <i class="bi bi-credit-card-2-back fs-3"></i>
                    </div>
                    <div>
                        <h6 class="card-title text-muted mb-1">Total Tunggakan</h6>
                        <p class="fs-4 fw-bold mb-0 text-danger">Rp {{ $totalTunggakan }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Area Grafik/Konten lainnya --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-primary">Grafik Operasional Bulanan</h5>
                    {{-- Placeholder untuk Grafik --}}
                    <div style="height: 300px; background-color: #eee; text-align: center; line-height: 300px;">
                        [Area Chart/Tabel Data Lainnya]
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection