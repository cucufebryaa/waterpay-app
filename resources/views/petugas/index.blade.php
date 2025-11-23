@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Task Maintenance</h1>
        <span class="badge bg-primary fs-6">{{ count($tasks) }} Tugas Aktif</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pekerjaan Saya</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tgl Lapor</th>
                            <th>Pelanggan</th>
                            <th>Masalah</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $index => $task)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($task->created_at)->format('d M Y H:i') }}</td>
                            <td>
                                <strong>{{ $task->pelanggan->nama ?? 'Unknown' }}</strong><br>
                                <small class="text-muted">{{ $task->pelanggan->alamat ?? '-' }}</small>
                            </td>
                            <td>{{ Str::limit($task->keluhan, 50) }}</td>
                            <td class="text-center">
                                @if($task->status == 'delegated')
                                    <span class="badge bg-danger">Delegated</span>
                                @elseif($task->status == 'onprogress')
                                    <span class="badge bg-warning text-dark">On Progress</span>
                                @endif
                            </td>
                            <td class="text-center">
                                
                                {{-- LOGIKA TOMBOL ACTION --}}
                                
                                @if($task->status == 'delegated')
                                    <form action="{{ route('petugas.maintenance.start', $task->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <i class="bi bi-play-circle me-1"></i> Mulai Kerjakan
                                        </button>
                                    </form>

                                @elseif($task->status == 'onprogress')
                                    <button type="button" 
                                            class="btn btn-success btn-sm w-100 btn-lapor"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalLapor"
                                            data-id="{{ $task->id }}"
                                            data-pelanggan="{{ $task->pelanggan->nama }}">
                                        <i class="bi bi-check-circle me-1"></i> Selesai & Lapor
                                    </button>
                                @endif

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-check2-all display-4"></i>
                                <p class="mt-2">Tidak ada tugas maintenance yang pending. Kerja bagus!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalLapor" tabindex="-1" aria-labelledby="modalLaporLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalLaporLabel">Lapor Penyelesaian Tugas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('petugas.maintenance.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="keluhan_id" id="keluhan_id">

                    <div class="alert alert-light border mb-3">
                        <small>Melaporkan penyelesaian untuk pelanggan: <strong id="nama_pelanggan_modal">-</strong></small>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label fw-bold">Foto Bukti Pengerjaan</label>
                        <input type="file" class="form-control" id="foto" name="foto" required accept="image/*">
                        <div class="form-text">Format: jpg, jpeg, png. Max 2MB.</div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-bold">Keterangan Pengerjaan</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required placeholder="Jelaskan apa yang diperbaiki..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalLapor = document.getElementById('modalLapor');
        
        modalLapor.addEventListener('show.bs.modal', function (event) {
            // Tombol yang memicu modal
            var button = event.relatedTarget;
            
            // Ambil data dari atribut data-*
            var idKeluhan = button.getAttribute('data-id');
            var namaPelanggan = button.getAttribute('data-pelanggan');

            // Isi value ke dalam input form modal
            var inputId = modalLapor.querySelector('#keluhan_id');
            var textNama = modalLapor.querySelector('#nama_pelanggan_modal');

            inputId.value = idKeluhan;
            textNama.textContent = namaPelanggan;
        });
    });
</script>
@endsection