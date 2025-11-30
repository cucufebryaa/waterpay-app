@extends('layouts.auth')

@section('title', 'Registrasi Perusahaan')
@section('illustration', '')
@section('form_column_class', 'col-md-12')

@section('content')

<style>
    /* --- MODERN VARIABLES (Consistent with Landing Page) --- */
    :root {
        --primary-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --input-bg: #f8fafc;
    }

    /* --- PAGE STYLING IMPROVED --- */
    .register-container {
        display: flex;
        justify-content: center;
        width: 100%;
    }

    .register-card {
        background: white;
        border-radius: 1.5rem;
        padding: 3.5rem; /* Padding diperbesar agar lebih lega */
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08); /* Shadow lebih halus & luas */
        width: 100%;
        max-width: 950px; /* Card diperlebar untuk memaksimalkan ruang */
        border: 1px solid rgba(226, 232, 240, 0.6);
        margin-bottom: 2rem;
    }

    .form-title {
        font-weight: 800;
        color: var(--text-dark);
        letter-spacing: -0.5px;
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }

    /* --- MODERN PROGRESS BAR --- */
    .progress-bar-container {
        position: relative;
        display: flex;
        justify-content: space-between;
        margin: 3rem auto 4rem; /* Jarak atas bawah lebih lega */
        padding: 0;
        max-width: 80%; /* Agar garis tidak terlalu mepet pinggir */
    }
    
    /* Garis background */
    .progress-bar-container::before {
        content: '';
        position: absolute;
        top: 25px; /* Disesuaikan dengan ukuran icon baru */
        left: 0;
        right: 0;
        height: 4px;
        background: #f1f5f9;
        z-index: 0;
        border-radius: 10px;
    }

    .progress-step {
        position: relative;
        z-index: 1;
        text-align: center;
        width: 33.33%;
        opacity: 0.6;
        transition: all 0.3s ease;
        cursor: default;
    }

    .progress-step.active {
        opacity: 1;
    }

    .progress-step-icon {
        width: 55px; /* Icon diperbesar */
        height: 55px;
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        color: var(--text-muted);
        font-size: 1.25rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }

    /* State Aktif */
    .progress-step.active .progress-step-icon {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3); /* Shadow icon lebih nyata */
        transform: scale(1.1);
    }

    .progress-step-label {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-top: 0.5rem;
    }

    /* --- FORM INPUTS MODERN --- */
    .form-group {
        margin-bottom: 1.5rem; /* Jarak antar input vertikal */
    }

    .form-group label {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--text-dark);
        margin-bottom: 0.6rem;
        display: block;
    }

    .form-control, .form-select {
        background-color: var(--input-bg);
        border: 2px solid var(--border-color);
        border-radius: 0.85rem;
        padding: 0.85rem 1.25rem; /* Padding dalam input diperbesar */
        font-size: 1rem;
        color: var(--text-dark);
        transition: all 0.2s ease;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        background-color: white;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .form-section-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
    }

    /* --- BUTTONS --- */
    .btn-primary {
        background: var(--primary-gradient);
        border: none;
        border-radius: 0.85rem;
        padding: 0.9rem 2.5rem; /* Tombol lebih besar */
        font-weight: 700;
        font-size: 1rem;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }

    .btn-outline-secondary {
        border: 2px solid var(--border-color);
        color: var(--text-muted);
        background: white;
        border-radius: 0.85rem;
        padding: 0.85rem 2rem;
        font-weight: 700;
        font-size: 1rem;
    }

    .btn-outline-secondary:hover {
        background: #f1f5f9;
        color: var(--text-dark);
        border-color: #cbd5e1;
    }

    /* Helper: Grid Gutter Custom */
    .row.g-custom {
        --bs-gutter-x: 2rem;
        --bs-gutter-y: 1.5rem;
    }

    /* Animasi Transisi Step */
    .form-step {
        display: none;
        animation: fadeIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .form-step.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .register-card {
            padding: 1.5rem;
        }
        .progress-bar-container {
            max-width: 100%;
            margin: 2rem 0;
        }
        .progress-step-icon {
            width: 40px; height: 40px; font-size: 1rem;
        }
        .progress-bar-container::before { top: 18px; }
        .form-section-title { font-size: 1.1rem; }
    }
</style>

<div class="register-container">
    <div class="register-card">
        <div class="text-center mb-5">
            <h2 class="form-title">Daftarkan Perusahaan</h2>
            <p class="text-muted" style="font-size: 1.05rem;">Mulai transformasi digital PAMS Anda dalam 3 langkah mudah.</p>
        </div>

        <!-- Progress Stepper Modern -->
        <div class="progress-bar-container">
            <div class="progress-step active" data-step="1">
                <div class="progress-step-icon"><i class="bi bi-person-fill"></i></div>
                <div class="progress-step-label">Penanggung Jawab</div>
            </div>
            <div class="progress-step" data-step="2">
                <div class="progress-step-icon"><i class="bi bi-buildings-fill"></i></div>
                <div class="progress-step-label">Perusahaan</div>
            </div>
            <div class="progress-step" data-step="3">
                <div class="progress-step-icon"><i class="bi bi-shield-lock-fill"></i></div>
                <div class="progress-step-label">Akun</div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-5 px-4 py-3 d-flex align-items-center gap-3" role="alert" style="background-color: #fef2f2; color: #991b1b;">
                <div class="p-2 bg-white rounded-circle shadow-sm text-danger">
                    <i class="bi bi-exclamation-circle-fill fs-5"></i>
                </div>
                <div>
                    <strong class="d-block mb-1">Perhatian!</strong>
                    <span>Harap periksa kembali isian formulir di bawah ini.</span>
                </div>
            </div>
        @endif

        <form id="registrationForm" method="POST" action="{{ route('register.admin.store') }}" novalidate>
            @csrf
            
            <!-- Langkah 1: Data Diri Penanggung Jawab -->
            <div class="form-step active" data-step="1">
                <h5 class="form-section-title"><i class="bi bi-person-badge-fill me-3 text-primary fs-4"></i>Data Penanggung Jawab</h5>
                <div class="row g-custom">
                    <div class="col-md-6 form-group">
                        <label for="name">Nama Lengkap PJ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="email">Email PJ <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="budi@email.com" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="no_hp">No. Handphone (WhatsApp) <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="08123456789" required>
                        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6 form-group">
                        <label for="nik">NIK (Opsional)</label>
                        <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}" placeholder="16 Digit NIK">
                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4 pt-2">
                    <button type="button" class="btn btn-primary btn-next px-5">Lanjut <i class="bi bi-arrow-right ms-2"></i></button>
                </div>
            </div>

            <!-- Langkah 2: Data Perusahaan & Bank -->
            <div class="form-step" data-step="2">
                <h5 class="form-section-title"><i class="bi bi-building-fill me-3 text-primary fs-4"></i>Informasi Perusahaan & Bank</h5>
                <div class="row g-custom">
                     <div class="col-md-6 form-group">
                        <label for="company_name">Nama PAMS / Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="Contoh: Tirta Jaya Abadi" required>
                        @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="alamat">Alamat Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ old('alamat') }}" placeholder="Jl. Merdeka No. 10" required>
                        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="nama_bank">Nama Bank <span class="text-danger">*</span></label>
                        <select id="nama_bank" name="nama_bank" class="form-select @error('nama_bank') is-invalid @enderror" required>
                            <option selected disabled value="">-- Pilih Bank --</option>
                            <option value="BCA" {{ old('nama_bank') == 'BCA' ? 'selected' : '' }}>BCA</option>
                            <option value="Mandiri" {{ old('nama_bank') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                            <option value="BRI" {{ old('nama_bank') == 'BRI' ? 'selected' : '' }}>BRI</option>
                            <option value="BNI" {{ old('nama_bank') == 'BNI' ? 'selected' : '' }}>BNI</option>
                            <option value="BSI" {{ old('nama_bank') == 'BSI' ? 'selected' : '' }}>BSI</option>
                        </select>
                        @error('nama_bank') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="no_rekening">No. Rekening <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('no_rekening') is-invalid @enderror" id="no_rekening" name="no_rekening" value="{{ old('no_rekening') }}" placeholder="Contoh: 1234567890" required>
                        @error('no_rekening') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4 pt-2">
                    <button type="button" class="btn btn-outline-secondary btn-prev px-4"><i class="bi bi-arrow-left me-2"></i>Kembali</button>
                    <button type="button" class="btn btn-primary btn-next px-5">Lanjut <i class="bi bi-arrow-right ms-2"></i></button>
                </div>
            </div>
            
            <!-- Langkah 3: Akun Login -->
            <div class="form-step" data-step="3">
                <h5 class="form-section-title"><i class="bi bi-shield-lock-fill me-3 text-primary fs-4"></i>Keamanan Akun</h5>
                <div class="alert alert-info border-0 bg-opacity-10 bg-primary text-primary small mb-4 rounded-4 px-4">
                    <i class="bi bi-info-circle-fill me-2"></i> Akun ini akan menjadi <strong>Admin Utama</strong> (Super Admin) untuk dashboard PAMS Anda.
                </div>
                <div class="row g-custom">
                    <div class="col-md-12 form-group">
                        <label for="username">Username Login <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="Buat username unik (tanpa spasi)" required>
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimal 8 karakter" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4 pt-2">
                    <button type="button" class="btn btn-outline-secondary btn-prev px-4"><i class="bi bi-arrow-left me-2"></i>Kembali</button>
                    <button type="submit" class="btn btn-primary px-5 shadow-lg">
                        <i class="bi bi-check-circle-fill me-2"></i>Daftar Sekarang
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
    
@endsection