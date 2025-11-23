@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')

    <style>
        .card-metric {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            cursor: default;
        }

        .card-metric:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .shadow-lg-custom {
            box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
        }
    </style>
    
    <div class="mb-4">
        <h1 class="h3 mb-1">Halo Petugas, {{ $nama_petugas }}!</h1>
        <p class="text-muted">Berikut adalah ringkasan operasional area Anda.</p>
    </div>

    <div class="row">
        {{-- Card 1: Pemakaian --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow-lg card-metric border border-primary"> 
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white rounded-3 p-3 me-3">
                        <i class="bi bi-droplet-half fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Total Pemakaian</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $pemakaianBulanIni ?? 0 }} m³</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Pemakaian Bulan Lalu --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow-lg card-metric border border-secondary">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-secondary text-white rounded-3 p-3 me-3">
                        <i class="bi bi-arrow-left-right fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Bulan Lalu</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $pemakaianBulanLalu ?? 0 }} m³</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Tagihan --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow-lg card-metric border border-success">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white rounded-3 p-3 me-3">
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Total Tagihan</h5>
                        <p class="fs-4 fw-bold mb-0">Rp {{ number_format($tagihanBulanIni ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Jatuh Tempo (Bisa diganti Target Penyelesaian jika mau) --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow-lg card-metric border border-danger">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger text-white rounded-3 p-3 me-3">
                        <i class="bi bi-calendar-check fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Periode Aktif</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $jatuhTempo ? $jatuhTempo->format('d M Y') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        {{-- Bagian Informasi (Sama Persis logicnya) --}}
        <div class="col-lg-7 mb-4">
            <div class="card h-100 shadow-lg-custom border">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 text-primary"><i class="bi bi-megaphone me-2"></i> Informasi Internal & Pengumuman</h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <div class="accordion" id="accordionInfoAdmin">
                        @forelse($informasiAdmin as $info)
                            <div class="alert alert-info border-0 shadow-sm mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0"><i class="bi bi-info-circle me-2"></i>Info Layanan</h6>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($info->tanggal)->format('d M Y') }}</small>
                                </div>
                                <hr class="my-1 text-info opacity-25">
                                <p class="mb-0 small text-dark">{{ $info->pesan }}</p>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-clipboard-x display-4"></i>
                                <p class="mt-2">Belum ada informasi terbaru.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card h-100 shadow-lg-custom border">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Data Task Maintenance</h5>
                </div>
                <div class="card-body p-2" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Pelanggan</th>
                                <th class="text-center">Alamat</th>
                                <th class="text-center">Isi Keluhan</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($keluhanMasuk as $keluhan)
                            <tr>
                                <td class="small fw-bold">
                                    {{ $keluhan->pelanggan->nama ?? 'No Name' }}
                                    <div class="text-muted fw-normal" style="font-size: 10px;">{{ $keluhan->created_at->format('d M H:i') }}</div>
                                </td>
                                <td class="small fw-bold text-center">
                                    {{ $keluhan->pelanggan->alamat ?? '-' }}
                                </td>
                                
                                <td class="small text-center" title="{{ $keluhan->keluhan }}">
                                    {{ Str::limit($keluhan->keluhan, 30) }}
                                </td>
                                
                                <td class="text-center">
                                    @php
                                        $badge = 'secondary';
                                        switch ($keluhan->status) {
                                            case 'open': $badge = 'danger'; break;
                                            case 'delegated': $badge = 'info'; break;
                                            case 'onprogress': $badge = 'warning'; break;
                                            case 'completed': $badge = 'success'; break;
                                            case 'rejected': $badge = 'dark'; break;
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ $keluhan->status }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted p-4">
                                    Tidak ada keluhan baru.
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