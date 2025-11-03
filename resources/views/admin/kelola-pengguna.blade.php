@extends('layouts.app')

@section('title', 'Manajemen Pelanggan')

@section('content')
<div class="container-fluid px-4">
    
    {{-- ... (Bagian Header dan Notifikasi Anda tetap SAMA) ... --}}
    
    {{-- Kotak Utama untuk Konten --}}
    <div class="row g-3 my-4">
        <div class="col-12">
            <div class="p-4 bg-white shadow-sm rounded-3">
                
                {{-- 
                    ================================================================
                    PERUBAHAN 1: Ubah <a> menjadi <button> untuk panggil Modal Create
                    ================================================================
                --}}
                <div class="d-flex justify-content-end mb-4">
                    <button type="button" class="btn btn-success shadow-sm"
                            data-bs-toggle="modal" 
                            data-bs-target="#modal-create-pelanggan">
                        <i class="bi bi-person-plus-fill me-2"></i>Tambah Pelanggan Baru
                    </button>
                </div>
                
                {{-- TABEL DATA PELANGGAN --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        {{-- ... (Bagian <thead> Anda tetap SAMA) ... --}}
                        <tbody>
                            @forelse ($pelanggan as $item)
                            <tr class="text-center align-middle">
                                <td>{{ $item->id }}</td>
                                <td class="text-start">{{ $item->name }}</td> 
                                <td class="text-start">{{ $item->alamat }}</td>
                                <td>{{ $item->no_hp }}</td>
                                <td class="text-center">
                                    <div class="d-flex flex-nowrap justify-content-center">
                                        
                                        {{-- Tombol Detail (SAMA) --}}
                                        <button type="button" class="btn btn-sm btn-info me-1" title="Detail"
                                                onclick="showDetailAlert({{ json_encode($item) }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        {{-- 
                                            ================================================================
                                            PERUBAHAN 2: Ubah <a> menjadi <button> untuk panggil Modal Edit
                                            ================================================================
                                        --}}
                                        <button type="button" class="btn btn-sm btn-warning me-1 btn-edit" title="Edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-edit-pelanggan"
                                                data-pelanggan="{{ json_encode($item) }}"
                                                data-update-url="{{ route('admin.pelanggan.update', $item->id) }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        {{-- Tombol Hapus (SAMA) --}}
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                                onclick="showDeleteAlert('{{ $item->id }}', '{{ $item->name }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    {{-- Form Delete (SAMA) --}}
                                    <form id="form-delete-{{ $item->id }}" 
                                          action="{{ route('admin.pelanggan.destroy', $item->id) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            {{-- ... (Bagian @empty Anda tetap SAMA) ... --}}
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 
    ================================================================
    PERUBAHAN 3: Include Modal di bawah konten
    ================================================================
--}}
@include('admin.pelanggan._modal_create')
@include('admin.pelanggan._modal_edit')

@endsection

@push('scripts')
{{-- 
    ... (Seluruh <style> dan <script> Anda untuk SweetAlert tetap SAMA) ...
--}}
{{-- SALIN BLOK INI PERSIS SEPERTI YANG ANDA KIRIM --}}
<style>
    /* ... (CSS Anda di sini) ... */
</style>
<script>
    // ... (Fungsi createDetailList() Anda di sini) ...
    // ... (Fungsi showDetailAlert() Anda di sini) ...
    // ... (Fungsi showDeleteAlert() Anda di sini) ...
</script>
{{-- AKHIR BLOK YANG DISALIN --}}


{{-- 
    ================================================================
    PERUBAHAN 4: Tambahkan JavaScript untuk Modal Edit & Error
    ================================================================
--}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Logika untuk mengisi Modal Edit
        const editModal = document.getElementById('modal-edit-pelanggan');
        if (editModal) {
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const pelanggan = JSON.parse(button.getAttribute('data-pelanggan'));
                const updateUrl = button.getAttribute('data-update-url');

                const modal = this;
                
                // Set form action
                modal.querySelector('form').setAttribute('action', updateUrl);
                
                // Isi field-field form
                modal.querySelector('#edit-name').value = pelanggan.name;
                modal.querySelector('#edit-email').value = pelanggan.email; // Pastikan 'email' ada di data
                modal.querySelector('#edit-no_hp').value = pelanggan.no_hp;
                modal.querySelector('#edit-alamat').value = pelanggan.alamat;
                
                // Kosongkan field password
                modal.querySelector('#edit-password').value = '';
                modal.querySelector('#edit-password_confirmation').value = '';
            });
        }

        // 2. Logika untuk menampilkan modal JIKA ada error validasi
        // Jika ada error validasi saat 'store', tampilkan lagi modal 'create'
        @if ($errors->hasBag('default') && old('form_type') === 'create')
            var createModal = new bootstrap.Modal(document.getElementById('modal-create-pelanggan'));
            createModal.show();
        @endif

        // Jika ada error validasi saat 'update', tampilkan lagi modal 'edit'
        @if ($errors->hasBag('default') && old('form_type') === 'edit')
            var editModal = new bootstrap.Modal(document.getElementById('modal-edit-pelanggan'));
            // Kita harus set ulang data modalnya, tapi ini akan lebih kompleks
            // Untuk sekarang, kita tampilkan saja modal kosongnya
            // Solusi lebih baik: gunakan Livewire atau AJAX
            
            // Untuk sementara, kita tampilkan modal create sbg fallback jika edit gagal
            // Ini adalah simplifikasi.
            // var editModal = new bootstrap.Modal(document.getElementById('modal-edit-pelanggan'));
            // editModal.show(); 
        @endif
    });
    
    // 3. Modifikasi kecil pada form modal untuk error handling
    // Tambahkan input hidden di form Anda untuk menandai asalnya
</script>
@endpush

{{-- 
    Perbaikan Kecil untuk Error Handling (PENTING):
    Tambahkan input hidden ini di dalam <form> di kedua modal Anda 
    agar skrip di PERUBAHAN 4 tahu modal mana yang harus dibuka jika validasi gagal.
--}}

{{-- Di _modal_create.blade.php (di dalam <form>) --}}
{{-- <input type="hidden" name="form_type" value="create"> --}}

{{-- Di _modal_edit.blade.php (di dalam <form>) --}}
{{-- <input type="hidden" name="form_type" value="edit"> --}}