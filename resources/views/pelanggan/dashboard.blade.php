@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')

    {{-- Custom CSS untuk Animasi Hover --}}
    <style>
        /* Menggunakan kelas custom untuk mempermudah targeting */
        .card-metric {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            cursor: default;
        }

        .card-metric:hover {
            transform: translateY(-5px); /* Menggeser ke atas sedikit */
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important; /* Shadow yang lebih tebal */
        }
        
        /* Membuat shadow dan border lebih tebal pada kartu */
        .shadow-lg-custom {
            box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
        }
    </style>
    
    {{-- 1. Greeting (Sapaan) --}}
    <div class="mb-4">
        <h1 class="h3 mb-1">Selamat Datang, {{ Auth::user()->pelanggan->name ?? Auth::user()->username }}!</h1>
        <p class="text-muted">Ringkasan pemakaian dan tagihan Anda di WaterPay.</p>
    </div>

    ---

    {{-- 2. Kartu Metrik (Ditingkatkan) --}}
    <div class="row">
        {{-- Pemakaian Bulan Ini --}}
        <div class="col-lg-3 col-md-6 mb-4">
            {{-- Menggunakan class custom untuk hover dan shadow-lg --}}
            <div class="card h-100 shadow-lg card-metric border border-primary"> 
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white rounded-3 p-3 me-3">
                        <i class="bi bi-droplet-half fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Pemakaian Bulan Ini</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $pemakaianBulanIni ?? 0 }} m³</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pemakaian Bulan Lalu --}}
        <div class="col-lg-3 col-md-6 mb-4">
            {{-- Menggunakan class custom untuk hover dan shadow-lg --}}
            <div class="card h-100 shadow-lg card-metric border border-secondary">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-secondary text-white rounded-3 p-3 me-3">
                        <i class="bi bi-arrow-left-right fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Pemakaian Bulan Lalu</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $pemakaianBulanLalu ?? 0 }} m³</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tagihan Bulan Ini --}}
        <div class="col-lg-3 col-md-6 mb-4">
            {{-- Menggunakan class custom untuk hover dan shadow-lg --}}
            <div class="card h-100 shadow-lg card-metric border border-success">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white rounded-3 p-3 me-3">
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Tagihan Bulan Ini</h5>
                        <p class="fs-4 fw-bold mb-0">Rp {{ number_format($tagihanBulanIni ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jatuh Tempo --}}
        <div class="col-lg-3 col-md-6 mb-4">
            {{-- Menggunakan class custom untuk hover dan shadow-lg --}}
            <div class="card h-100 shadow-lg card-metric border border-danger">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger text-white rounded-3 p-3 me-3">
                        <i class="bi bi-calendar-check fs-3"></i>
                    </div>
                    <div>
                        <h5 class="card-title text-muted mb-1">Jatuh Tempo</h5>
                        <p class="fs-4 fw-bold mb-0">{{ $jatuhTempo ? $jatuhTempo->format('d M Y') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    ---

    {{-- 3. Baris Informasi & Status Keluhan (Dikoreksi) --}}
    <div class="row mt-4">
        
        {{-- Kolom Informasi Admin (col-lg-8) --}}
        <div class="col-lg-8 mb-4">
            {{-- Menggunakan shadow-lg-custom untuk shadow yang lebih tebal --}}
            <div class="card h-100 shadow-lg-custom border">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-megaphone me-2"></i> Informasi & Pengumuman</h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <div class="accordion" id="accordionInfoAdmin">
                        
                        @forelse ($informasiAdmin as $info)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-{{ $info['id'] }}">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $info['id'] }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse-{{ $info['id'] }}">
                                    <strong>{{ $info['judul'] }}</strong>
                                </button>
                            </h2>
                            <div id="collapse-{{ $info['id'] }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading-{{ $info['id'] }}" data-bs-parent="#accordionInfoAdmin">
                                <div class="accordion-body">
                                    <p class="text-muted small">{{ $info['tanggal']->format('d M Y, H:i') }}</p>
                                    <p>{{ $info['isi'] }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center p-3">
                            <p class="text-muted mb-0">Belum ada informasi terbaru saat ini.</p>
                        </div>
                        @endforelse
                        
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Status Keluhan (col-lg-4) --}}
        <div class="col-lg-4 mb-4">
            {{-- Menggunakan shadow-lg-custom untuk shadow yang lebih tebal --}}
            <div class="card h-100 shadow-lg-custom border">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-clock-history me-2"></i> Status Keluhan Terakhir</h5>
                </div>
                <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Ringkasan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($keluhanPelanggan as $keluhan)
                            <tr>
                                <td class="small">{{ $keluhan->created_at->format('d M y') }}</td>
                                <td class="small" title="{{ $keluhan->keluhan }}">{{ Str::limit($keluhan->keluhan, 25) }}</td>
                                <td>
                                    @php
                                        // Menentukan warna badge berdasarkan status string
                                        $badge = 'secondary';
                                        switch ($keluhan->status) {
                                            case 'Open':
                                                $badge = 'danger'; // Baru/Belum ditangani
                                                break;
                                            case 'Delegated':
                                                $badge = 'info'; // Sudah didelegasikan
                                                break;
                                            case 'OnProgress':
                                                $badge = 'warning'; // Sedang dikerjakan
                                                break;
                                            case 'Completed':
                                                $badge = 'success'; // Selesai
                                                break;
                                            case 'Rejected':
                                                $badge = 'dark'; // Ditolak
                                                break;
                                            default:
                                                $badge = 'secondary';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ $keluhan->status }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted p-4">Anda belum memiliki riwayat keluhan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if (count($keluhanPelanggan) > 0)
                <div class="card-footer text-center">
                    {{-- Asumsi Anda memiliki route 'pelanggan.keluhan.index' untuk melihat semua keluhan --}}
                    <a href="{{ route('pelanggan.dashboard') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua Keluhan</a> 
                </div>
                @endif
            </div>
        </div>
    </div>

@endsection