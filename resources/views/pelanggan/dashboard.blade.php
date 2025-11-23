@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

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

        .img-bukti {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
    
    <div class="mb-4">
        <h1 class="h3 mb-1">Selamat Datang, {{ Auth::user()->pelanggan->name ?? Auth::user()->username }}!</h1>
        <p class="text-muted">Ringkasan pemakaian dan tagihan Anda di WaterPay.</p>
    </div>

    {{-- ROW 1: METRIK (Tetap sama) --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
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

        <div class="col-lg-3 col-md-6 mb-4">
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

        <div class="col-lg-3 col-md-6 mb-4">
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

        <div class="col-lg-3 col-md-6 mb-4">
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

    {{-- ROW 2: INFORMASI (Full Width 12 Col) --}}
    <div class="row mt-2">
        <div class="col-12 mb-4">
            <div class="card shadow-lg-custom border">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 text-primary"><i class="bi bi-megaphone me-2"></i> Informasi & Pengumuman</h5>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @if(count($informasiAdmin) > 0)
                        <div class="row">
                            @foreach($informasiAdmin as $info)
                                <div class="col-md-12 mb-3">
                                    <div class="alert alert-info h-100">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="fw-bold"><i class="bi bi-info-circle me-1"></i> Info Layanan</h6>
                                            <small>{{ \Carbon\Carbon::parse($info->tanggal)->format('d M Y') }}</small>
                                        </div>
                                        <hr class="my-2">
                                        <p class="mb-0 small">{{ $info->pesan }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted my-3">Tidak ada informasi terbaru.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 3: STATUS KELUHAN (Full Width 12 Col) --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-lg-custom border">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-dark"><i class="bi bi-clock-history me-2"></i> Status Keluhan Terakhir</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 15%">Tanggal</th>
                                    <th style="width: 50%">Ringkasan Masalah</th>
                                    <th style="width: 15%">Status</th>
                                    <th style="width: 20%" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($keluhanPelanggan as $keluhan)
                                <tr>
                                    <td>{{ $keluhan->created_at->format('d M Y') }}</td>
                                    <td title="{{ $keluhan->keluhan }}">{{ Str::limit($keluhan->keluhan, 80) }}</td>
                                    <td>
                                        @php
                                            $badge = 'secondary';
                                            $status = strtolower($keluhan->status); // Normalisasi huruf kecil
                                            switch ($status) {
                                                case 'open': $badge = 'danger'; break;
                                                case 'delegated': $badge = 'info'; break;
                                                case 'onprogress': $badge = 'warning'; break;
                                                case 'completed': $badge = 'success'; break;
                                                case 'rejected': $badge = 'dark'; break;
                                            }
                                        @endphp
                                        <span class="badge bg-{{ $badge }} text-uppercase">{{ $keluhan->status }}</span>
                                    </td>
                                    <td class="text-end">
                                        @if(strtolower($keluhan->status) == 'completed')
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalDetail{{ $keluhan->id }}">
                                                <i class="bi bi-eye me-1"></i> Detail Pengerjaan
                                            </button>

                                            {{-- MODAL DETAIL PENGERJAAN (Disimpan dalam loop agar unik per item) --}}
                                            <div class="modal fade text-start" id="modalDetail{{ $keluhan->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title">Laporan Pengerjaan</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{-- Cek apakah ada data maintenance --}}
                                                            @if($keluhan->maintenance)
                                                                <div class="mb-3">
                                                                    <label class="fw-bold text-muted small">TANGGAL SELESAI</label>
                                                                    <p class="fw-bold text-dark">{{ \Carbon\Carbon::parse($keluhan->maintenance->tanggal)->format('d F Y, H:i') }}</p>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="fw-bold text-muted small">KETERANGAN PETUGAS</label>
                                                                    <div class="alert alert-light border">
                                                                        {{ $keluhan->maintenance->deskripsi }}
                                                                    </div>
                                                                </div>

                                                                @if($keluhan->maintenance->foto)
                                                                    <div class="mb-3">
                                                                        <label class="fw-bold text-muted small">FOTO BUKTI</label>
                                                                        <div class="text-center mt-2">
                                                                            <img src="{{ asset('storage/' . $keluhan->maintenance->foto) }}" 
                                                                                 alt="Bukti Pengerjaan" 
                                                                                 class="img-bukti img-fluid">
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="alert alert-warning py-2 small">Tidak ada foto bukti yang dilampirkan.</div>
                                                                @endif
                                                            @else
                                                                <div class="alert alert-danger">Data detail maintenance tidak ditemukan.</div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- END MODAL --}}
                                        @else
                                            <span class="text-muted small fst-italic">Menunggu proses</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted p-5">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        Anda belum memiliki riwayat keluhan.
                                    </td>
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