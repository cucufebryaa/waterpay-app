@extends('layouts.app') 

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="card shadow-lg border-0 rounded-3">
                
                <div class="card-header bg-primary text-white p-4 rounded-top-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-gear fs-3 me-3"></i>
                        <div>
                            <h4 class="mb-0 fw-bold">Edit Profil Saya</h4>
                            <small class="text-white-50">Perbarui informasi akun dan data pribadi Anda.</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('pelanggan.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT') 

                        <h6 class="text-uppercase text-muted fw-bold mb-3 border-bottom pb-2">
                            <i class="bi bi-shield-lock me-1"></i> Informasi Akun
                        </h6>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-at"></i></span>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                           id="username" name="username" 
                                           value="{{ old('username', $user->username) }}" placeholder="Username unik">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" 
                                           value="{{ old('email', $user->email) }}" placeholder="email@contoh.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-light border border-light-subtle mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-key me-2 text-primary"></i>
                                <small class="fw-bold text-primary">Ganti Password (Opsional)</small>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label small text-muted">Password Baru</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Kosongkan jika tidak diubah">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label small text-muted">Konfirmasi Password</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>

                        <h6 class="text-uppercase text-muted fw-bold mb-3 border-bottom pb-2 mt-5">
                            <i class="bi bi-person-vcard me-1"></i> Data Pribadi
                        </h6>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" 
                                       value="{{ old('name', $pelanggan->nama ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="no_hp" class="form-label fw-semibold">Nomor HP / WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror" 
                                           id="no_hp" name="no_hp" 
                                           value="{{ old('no_hp', $pelanggan->no_hp ?? '') }}">
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="alamat" class="form-label fw-semibold">Alamat Lengkap</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                          id="alamat" name="alamat" rows="3" placeholder="Jalan, RT/RW, Kelurahan...">{{ old('alamat', $pelanggan->alamat ?? '') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
            </div>
    </div>
</div>
@endsection