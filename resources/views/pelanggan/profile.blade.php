@extends('layouts.app') 

@section('title', 'Edit Profil')

@section('content')

<style>
    /* Page Header */
    .profile-header {
        padding: 0 0 1rem;
        border-bottom: 1px solid #e3e6f0;
        margin-bottom: 1.5rem;
    }

    .profile-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .profile-header h1 i {
        font-size: 1.5rem;
        color: #3b82f6;
    }

    .profile-header p {
        color: #6c757d;
        font-size: 0.95rem;
        margin: 0;
    }

    /* Form Container */
    .form-container {
        width: 100%;
        max-width: 100%;
    }

    .modern-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: 1px solid #e8ecf1;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .card-section {
        padding: 2rem;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #2c3e50;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e8ecf1;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        font-size: 1.1rem;
        color: #3b82f6;
    }

    /* Form Elements */
    .form-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control {
        border: 2px solid #e8ecf1;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        background: white;
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .invalid-feedback {
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .invalid-feedback::before {
        content: '⚠';
        font-size: 1rem;
    }

    /* Input Group */
    .input-group {
        border-radius: 10px;
        overflow: hidden;
    }

    .input-group-text {
        background: #f8f9fc;
        border: 2px solid #e8ecf1;
        border-right: none;
        color: #64748b;
        padding: 0.75rem 1rem;
    }

    .input-group .form-control {
        border-left: none;
    }

    .input-group:focus-within .input-group-text {
        border-color: #3b82f6;
        background: #eff6ff;
        color: #3b82f6;
    }

    .input-group:focus-within .form-control {
        border-color: #3b82f6;
    }

    /* Password Section */
    .password-section {
        background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
        border: 2px solid #bfdbfe;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .password-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .password-header i {
        font-size: 1.25rem;
        color: #3b82f6;
    }

    .password-header h6 {
        margin: 0;
        font-weight: 700;
        color: #1e40af;
        font-size: 0.95rem;
    }

    .password-note {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 1rem;
        padding-left: 2rem;
    }

    /* Textarea */
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    /* Buttons */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding-top: 2rem;
        border-top: 2px solid #e8ecf1;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn i {
        font-size: 1rem;
    }

    .btn-cancel {
        background: #f8f9fc;
        color: #64748b;
        border: 2px solid #e8ecf1;
    }

    .btn-cancel:hover {
        background: #e8ecf1;
        color: #475569;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .btn-save {
        background: linear-gradient(135deg, #3b82f6, #06b6d4);
        color: white;
        border: none;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        color: white;
    }

    /* Alert Success */
    .alert-success {
        background: linear-gradient(135deg, #d1f4e0 0%, #e8f9f0 100%);
        border: 2px solid #10b981;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideDown 0.3s ease;
    }

    .alert-success i {
        font-size: 1.5rem;
        color: #059669;
    }

    .alert-success .alert-content {
        flex: 1;
        color: #065f46;
        font-weight: 500;
    }

    .btn-close {
        padding: 0.5rem;
        opacity: 0.5;
        transition: opacity 0.3s ease;
    }

    .btn-close:hover {
        opacity: 1;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-header {
            padding: 0 0 0.75rem;
        }

        .profile-header h1 {
            font-size: 1.5rem;
        }

        .card-section {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .password-section {
            padding: 1.25rem;
        }
    }

    @media (max-width: 576px) {
        .card-section {
            padding: 1.25rem;
        }

        .section-title {
            font-size: 0.9rem;
        }

        .profile-header h1 {
            font-size: 1.35rem;
        }
    }
</style>

<!-- Page Header -->
<div class="profile-header">
    <h1>
        <i class="bi bi-person-gear"></i>
        Edit Profil Saya
    </h1>
    <p>Perbarui informasi akun dan data pribadi Anda</p>
</div>

<div class="form-container">
    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <div class="alert-content">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Form Card -->
    <div class="modern-card">
        <div class="card-section">
            <form action="{{ route('pelanggan.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Account Information Section -->
                <div class="section-title">
                    <i class="bi bi-shield-lock"></i>
                    Informasi Akun
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-at"></i>
                            </span>
                            <input type="text" 
                                   class="form-control @error('username') is-invalid @enderror" 
                                   id="username" 
                                   name="username" 
                                   value="{{ old('username', $user->username) }}" 
                                   placeholder="Username unik">
                        </div>
                        @error('username')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   placeholder="email@contoh.com">
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password Section -->
                <div class="password-section">
                    <div class="password-header">
                        <i class="bi bi-key"></i>
                        <h6>Ganti Password (Opsional)</h6>
                    </div>
                    <p class="password-note">
                        <i class="bi bi-info-circle me-1"></i>
                        Kosongkan jika tidak ingin mengubah password
                    </p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Minimal 8 karakter">
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                <!-- Personal Information Section -->
                <div class="section-title">
                    <i class="bi bi-person-vcard"></i>
                    Data Pribadi
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $pelanggan->nama ?? '') }}"
                               placeholder="Nama lengkap Anda">
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="no_hp" class="form-label">Nomor HP / WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-telephone"></i>
                            </span>
                            <input type="text" 
                                   class="form-control @error('no_hp') is-invalid @enderror" 
                                   id="no_hp" 
                                   name="no_hp" 
                                   value="{{ old('no_hp', $pelanggan->no_hp ?? '') }}"
                                   placeholder="08xxxxxxxxxx">
                        </div>
                        @error('no_hp')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" 
                                  name="alamat" 
                                  rows="4" 
                                  placeholder="Jalan, RT/RW, Kelurahan, Kecamatan...">{{ old('alamat', $pelanggan->alamat ?? '') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ url()->previous() }}" class="btn btn-cancel">
                        <i class="bi bi-x-circle"></i>
                        <span>Batal</span>
                    </a>
                    <button type="submit" class="btn btn-save">
                        <i class="bi bi-check-circle"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Auto dismiss alert after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.querySelector('.alert-success');
        if (alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        }
    });

    // Password confirmation validation
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');

    if (password && passwordConfirm) {
        passwordConfirm.addEventListener('input', function() {
            if (this.value !== password.value && this.value.length > 0) {
                this.setCustomValidity('Password tidak cocok');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });

        password.addEventListener('input', function() {
            if (passwordConfirm.value.length > 0) {
                passwordConfirm.dispatchEvent(new Event('input'));
            }
        });
    }

    // Phone number format validation (Indonesian)
    const noHp = document.getElementById('no_hp');
    if (noHp) {
        noHp.addEventListener('input', function(e) {
            // Remove non-numeric characters
            let value = this.value.replace(/\D/g, '');
            
            // Limit to 15 digits
            if (value.length > 15) {
                value = value.substring(0, 15);
            }
            
            this.value = value;
        });
    }
</script>
@endpush