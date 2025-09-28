@extends('layouts.auth')

@section('title', 'Registrasi Perusahaan')
@section('illustration', '')
@section('form_column_class', 'col-md-12')

@section('content')
    
    <h1 class="h2 fw-bold text-center mb-2">Daftarkan Perusahaan Anda</h1>
    <p class="text-muted text-center mb-5">Ikuti 3 langkah mudah untuk memulai.</p>

    <div class="progress-bar-container mb-5">
        <div class="progress-bar-line"></div>
        <div class="progress-step active" data-step="1">
            <div class="progress-step-icon"><i class="bi bi-person-badge"></i></div>
            <div class="progress-step-label">Data PJ</div>
        </div>
        <div class="progress-step" data-step="2">
            <div class="progress-step-icon"><i class="bi bi-building"></i></div>
            <div class="progress-step-label">Data Perusahaan</div>
        </div>
        <div class="progress-step" data-step="3">
            <div class="progress-step-icon"><i class="bi bi-key"></i></div>
            <div class="progress-step-label">Akun</div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4" role="alert">
            <p class="mb-0"><strong>Oops!</strong> Harap periksa kembali isian Anda, ada beberapa hal yang perlu diperbaiki.</p>
        </div>
    @endif

    <form id="registrationForm" method="POST" action="{{ route('register.admin.store') }}" novalidate>
        @csrf
        
        <!-- Langkah 1: Data Diri Penanggung Jawab -->
        <div class="form-step active" data-step="1">
            <h5 class="form-section-title">Data Diri Penanggung Jawab</h5>
            <div class="row">
                <div class="col-md-6 form-group mb-4">
                    <label for="name" class="form-label">Nama Lengkap PJ</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 form-group mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 form-group mb-4">
                    <label for="no_hp" class="form-label">No. Handphone</label>
                    <input type="tel" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required>
                    @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                 <div class="col-md-6 form-group mb-4">
                    <label for="nik" class="form-label">NIK (Opsional)</label>
                    <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}">
                    @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary btn-next">Lanjut</button>
            </div>
        </div>

        <!-- Langkah 2: Data Perusahaan & Bank -->
        <div class="form-step" data-step="2">
            <h5 class="form-section-title">Data Perusahaan & Bank</h5>
            <div class="row">
                 <div class="col-md-6 form-group mb-4">
                    <label for="company_name" class="form-label">Nama Perusahaan (PAMS)</label>
                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                    @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 form-group mb-4">
                    <label for="alamat" class="form-label">Alamat Perusahaan</label>
                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ old('alamat') }}" required>
                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 form-group mb-4">
                    <label for="nama_bank" class="form-label">Nama Bank</label>
                    <select id="nama_bank" name="nama_bank" class="form-select @error('nama_bank') is-invalid @enderror" required>
                        <option selected disabled value="">Pilih Bank...</option>
                        <option value="BCA" {{ old('nama_bank') == 'BCA' ? 'selected' : '' }}>BCA</option>
                        <option value="Mandiri" {{ old('nama_bank') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                        <option value="BRI" {{ old('nama_bank') == 'BRI' ? 'selected' : '' }}>BRI</option>
                        <option value="BNI" {{ old('nama_bank') == 'BNI' ? 'selected' : '' }}>BNI</option>
                    </select>
                    @error('nama_bank') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 form-group mb-4">
                    <label for="no_rekening" class="form-label">No. Rekening</label>
                    <input type="text" class="form-control @error('no_rekening') is-invalid @enderror" id="no_rekening" name="no_rekening" value="{{ old('no_rekening') }}" required>
                    @error('no_rekening') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-prev">Kembali</button>
                <button type="button" class="btn btn-primary btn-next">Lanjut</button>
            </div>
        </div>
        
        <!-- Langkah 3: Akun Login -->
        <div class="form-step" data-step="3">
            <h5 class="form-section-title">Akun Login</h5>
            <div class="row">
                <div class="col-md-12 form-group mb-4">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 form-group mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 form-group mb-4">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-prev">Kembali</button>
                <button type="submit" class="btn btn-primary">Daftar</button>
            </div>
        </div>
    </form>
    
@endsection