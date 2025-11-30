@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        body {
            background: #f8f9fc;
        }

        .welcome-header {
            padding: 2rem 0 1.5rem;
            border-bottom: 1px solid #e3e6f0;
            margin-bottom: 2rem;
        }

        .welcome-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }

        .welcome-header p {
            color: #6c757d;
            font-size: 0.95rem;
            margin: 0;
        }

        /* Modern Section Card */
        .modern-section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            overflow: hidden;
            border: 1px solid #e8ecf1;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .modern-section:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .section-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(to right, #f8f9fc, #ffffff);
            border-bottom: 1px solid #e8ecf1;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-header i {
            font-size: 1.25rem;
        }

        .section-header h5 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .section-content {
            padding: 1.5rem;
        }

        /* Task Table Styling */
        .task-table {
            margin: 0;
        }

        .task-table thead th {
            background: #f8f9fc;
            border: none;
            color: #5a6c7d;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.875rem 1rem;
        }

        .task-table tbody tr {
            border-bottom: 1px solid #f0f2f5;
            transition: all 0.2s ease;
        }

        .task-table tbody tr:last-child {
            border-bottom: none;
        }

        .task-table tbody tr:hover {
            background: #f8f9fc;
            transform: translateX(4px);
        }

        .task-table tbody td {
            padding: 1rem;
            vertical-align: middle;
            font-size: 0.9rem;
            border: none;
        }

        .customer-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }

        .task-time {
            font-size: 0.75rem;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .task-time i {
            font-size: 0.7rem;
        }

        .complaint-text {
            color: #5a6c7d;
            line-height: 1.4;
        }

        /* Status Badge Modern */
        .status-badge {
            padding: 0.4rem 0.875rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .status-badge i {
            font-size: 0.7rem;
        }

        .status-badge.status-open {
            background: #fee;
            color: #dc3545;
        }

        .status-badge.status-delegated {
            background: #e7f5ff;
            color: #0ea5e9;
        }

        .status-badge.status-onprogress {
            background: #fff3cd;
            color: #f59e0b;
        }

        .status-badge.status-completed {
            background: #d1f4e0;
            color: #10b981;
        }

        .status-badge.status-rejected {
            background: #e9ecef;
            color: #6c757d;
        }

        /* Info Alert Modern */
        .info-item {
            background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);
            border: none;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .info-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--info-gradient);
        }

        .info-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.15);
        }

        .info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .info-title {
            font-weight: 600;
            color: #0369a1;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }

        .info-date {
            font-size: 0.8rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .info-date i {
            font-size: 0.75rem;
        }

        .info-message {
            color: #334155;
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 3.5rem;
            opacity: 0.5;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 0.95rem;
            margin: 0;
        }

        /* Scrollbar Custom */
        .scroll-content::-webkit-scrollbar {
            width: 6px;
        }

        .scroll-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .scroll-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .scroll-content::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-header {
                padding: 1.5rem 0 1rem;
            }

            .section-header {
                padding: 1rem 1.25rem;
            }

            .section-content {
                padding: 1rem;
            }

            .task-table {
                font-size: 0.85rem;
            }

            .task-table thead th,
            .task-table tbody td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
    
    <!-- Welcome Header -->
    <div class="welcome-header">
        <h1>Halo, {{ $nama_petugas }}! 👋</h1>
        <p>Kelola tugas maintenance dan pantau informasi terbaru di area Anda</p>
    </div>

    <div class="row">
        <!-- Data Task Maintenance -->
        <div class="col-lg-8 mb-4">
            <div class="modern-section">
                <div class="section-header">
                    <i class="bi bi-list-check text-danger"></i>
                    <h5>Data Task Maintenance</h5>
                </div>
                <div class="section-content p-0">
                    <div class="scroll-content" style="max-height: 550px; overflow-y: auto;">
                        <table class="table task-table mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">Pelanggan</th>
                                    <th style="width: 25%;">Alamat</th>
                                    <th style="width: 35%;">Keluhan</th>
                                    <th class="text-center" style="width: 15%;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($keluhanMasuk as $keluhan)
                                <tr>
                                    <td>
                                        <div class="customer-name">{{ $keluhan->pelanggan->nama ?? 'No Name' }}</div>
                                        <div class="task-time">
                                            <i class="bi bi-clock"></i>
                                            {{ $keluhan->created_at->format('d M, H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $keluhan->pelanggan->alamat ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="complaint-text" title="{{ $keluhan->keluhan }}">
                                            {{ Str::limit($keluhan->keluhan, 50) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $statusClass = 'status-' . $keluhan->status;
                                            $statusIcon = [
                                                'open' => 'exclamation-circle',
                                                'delegated' => 'arrow-right-circle',
                                                'onprogress' => 'hourglass-split',
                                                'completed' => 'check-circle',
                                                'rejected' => 'x-circle'
                                            ][$keluhan->status] ?? 'circle';
                                        @endphp
                                        <span class="status-badge {{ $statusClass }}">
                                            <i class="bi bi-{{ $statusIcon }}"></i>
                                            {{ ucfirst($keluhan->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <p>Tidak ada task maintenance saat ini</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Internal & Pengumuman -->
        <div class="col-lg-4 mb-4">
            <div class="modern-section">
                <div class="section-header">
                    <i class="bi bi-megaphone text-primary"></i>
                    <h5>Informasi & Pengumuman</h5>
                </div>
                <div class="section-content">
                    <div class="scroll-content" style="max-height: 550px; overflow-y: auto;">
                        @forelse($informasiAdmin as $info)
                            <div class="info-item">
                                <div class="info-header">
                                    <div class="info-title">
                                        <i class="bi bi-info-circle-fill"></i>
                                        Info Layanan
                                    </div>
                                    <div class="info-date">
                                        <i class="bi bi-calendar3"></i>
                                        {{ \Carbon\Carbon::parse($info->tanggal)->format('d M Y') }}
                                    </div>
                                </div>
                                <p class="info-message">{{ $info->pesan }}</p>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-bell-slash"></i>
                                <p>Belum ada informasi terbaru</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection