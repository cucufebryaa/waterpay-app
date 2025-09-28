@extends('layouts.auth') {{-- Kita gunakan layout auth agar simpel --}}

@section('title', 'Status Akun')
@section('illustration', '')
@section('form_column_class', 'col-md-12')

@section('content')
    <div class="text-center p-5">

        @if($status == 'pending')
            <div class="mb-4">
                <i class="bi bi-hourglass-split display-1 text-warning"></i>
            </div>
            <h1 class="h2 fw-bold">Akun Anda Sedang Ditinjau</h1>
            <p class="text-muted fs-5">
                Terima kasih telah mendaftar. Pendaftaran perusahaan Anda sedang dalam proses verifikasi oleh Super Admin.
                <br>
                Anda akan mendapatkan notifikasi melalui email jika akun Anda telah disetujui.
            </p>
        @elseif($status == 'rejected')
            <div class="mb-4">
                <i class="bi bi-x-circle-fill display-1 text-danger"></i>
            </div>
            <h1 class="h2 fw-bold">Pendaftaran Ditolak</h1>
            <p class="text-muted fs-5">
                Mohon maaf, pendaftaran perusahaan Anda belum dapat kami setujui saat ini.
                <br>
                Silakan hubungi Super Admin untuk informasi lebih lanjut.
            </p>
        @endif
        
        <div class="mt-5">
            <form action="{{ route('logout') }}" method="post"> {{-- Ganti dengan route logout nanti --}}
                @csrf
                <button type="submit" class="btn btn-primary">Kembali ke Halaman Login</button>
            </form>
        </div>
    </div>
@endsection