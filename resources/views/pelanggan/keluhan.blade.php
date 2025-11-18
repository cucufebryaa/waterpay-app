@extends('layouts.app')

@section('title', 'Formulir Pengajuan Keluhan')

@section('content')


<div class="bg-custom-gray d-flex align-items-center justify-content-center">
    
    <div class="bg-decoration blob-1"></div>
    <div class="bg-decoration blob-2"></div>

    <div class="container px-4">
        <div class="row justify-content-center">
            <div class="col-12 fade-in">
                
                <div class="card form-card p-4 p-md-5">
                    <div class="text-center mb-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-50 rounded-circle p-3 mb-3 shadow-sm" style="width: 64px; height: 64px;">
                            <i class="fas fa-file-signature fa-lg text-secondary"></i>
                        </div>
                        <h3 class="fw-bold text-dark mb-1">Formulir Keluhan</h3>
                        <p class="text-muted">Data identitas Anda otomatis terisi oleh sistem.</p>
                    </div>

                    <form action="{{ route('pelanggan.keluhan.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" 
                                                   class="form-control rounded-3 bg-light" 
                                                   id="username" 
                                                   name="username" 
                                                   value="{{ $user->username ?? $user->name }}" 
                                                   readonly> <label for="username" class="text-muted">Username / Nama</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" 
                                                   class="form-control rounded-3 bg-light" 
                                                   id="email" 
                                                   name="email" 
                                                   value="{{ $user->email }}" 
                                                   readonly>
                                            <label for="email" class="text-muted">Email Address</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control rounded-3 bg-light" 
                                           id="company_name" 
                                           name="company_name" 
                                           value="{{ $company_name }}" 
                                           readonly>
                                    <label for="company_name" class="text-muted">Nama Perusahaan (Company Name)</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control rounded-3 @error('isi_keluhan') is-invalid @enderror" 
                                        placeholder="Leave a comment here" 
                                        id="isi_keluhan" 
                                        name="isi_keluhan" 
                                        style="height: 160px" 
                                        required>{{ old('isi_keluhan') }}</textarea>
                                    <label for="isi_keluhan" class="text-muted">Deskripsi Keluhan Anda</label>
                                    @error('isi_keluhan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold letter-spacing-1">
                                    KIRIM FORMULIR
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted opacity-75">&copy; {{ date('Y') }} Company System. Secure Form.</small>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection