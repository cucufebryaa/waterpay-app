@extends('layouts.auth')

@section('title', 'Login')
{{-- Kosongkan section illustration agar tidak muncul gambar --}}
@section('illustration', '') 
@section('form_column_class', 'col-12') 

@section('content')

<style>
    /* --- MODERN VARIABLES --- */
    :root {
        --primary-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --input-bg: #f8fafc;
    }

    /* --- PAGE LAYOUT --- */
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh; /* Full height agar selalu di tengah */
        width: 100%;
        padding: 1.5rem;
    }

    .login-card {
        background: white;
        border-radius: 1.5rem;
        padding: 3rem;
        /* Shadow halus */
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08); 
        width: 100%;
        max-width: 450px; /* Lebar optimal untuk login */
        border: 1px solid rgba(226, 232, 240, 0.6);
        text-align: center;
    }

    .login-title {
        font-weight: 800;
        color: var(--text-dark);
        letter-spacing: -0.5px;
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }

    /* --- FORM STYLING --- */
    .form-group {
        margin-bottom: 1.5rem;
        text-align: left;
    }

    .form-label {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--text-dark);
        margin-bottom: 0.6rem;
        display: block;
    }

    /* Input Group Styling */
    .input-group-modern {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1.1rem;
        transition: color 0.2s;
        z-index: 2;
    }

    .form-control {
        background-color: var(--input-bg);
        border: 2px solid var(--border-color);
        border-radius: 0.85rem;
        padding: 0.85rem 1.25rem 0.85rem 3rem; /* Padding kiri besar untuk icon */
        font-size: 1rem;
        color: var(--text-dark);
        transition: all 0.2s ease;
        width: 100%;
    }

    .form-control:focus {
        background-color: white;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .form-control:focus + .input-icon {
        color: #3b82f6;
    }

    /* --- BUTTONS --- */
    .btn-primary {
        background: var(--primary-gradient);
        border: none;
        border-radius: 0.85rem;
        padding: 0.9rem;
        font-weight: 700;
        font-size: 1rem;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        transition: all 0.3s;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
    }

    .btn-register-link {
        color: #3b82f6;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-register-link:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    /* Checkbox */
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
        margin-top: 0.15em;
        border: 2px solid var(--border-color);
        cursor: pointer;
    }
    .form-check-input:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 2rem 0;
        color: var(--text-muted);
        font-size: 0.85rem;
    }
    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid var(--border-color);
    }
    .divider::before { margin-right: .5em; }
    .divider::after { margin-left: .5em; }

    /* --- RESPONSIVE MOBILE STYLES --- */
    @media (max-width: 576px) {
        .login-container {
            padding: 1rem; /* Padding container dikurangi */
            align-items: center;
            min-height: 100vh;
        }

        .login-card { 
            padding: 2rem 1.5rem; /* Padding dalam card lebih rapat */
            border-radius: 1.2rem;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05); /* Shadow lebih halus untuk mobile */
        }

        .login-title {
            font-size: 1.5rem; /* Font judul sedikit mengecil */
        }

        .form-control {
            font-size: 16px; /* Mencegah auto-zoom pada iOS saat input diklik */
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
        }

        .btn-primary {
            padding: 0.8rem;
        }
        
        .divider {
            margin: 1.5rem 0;
        }
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="mb-4">
            {{-- Logo kecil opsional atau icon kunci --}}
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-shield-lock-fill fs-3"></i>
            </div>
            <h2 class="login-title">Selamat Datang</h2>
            <p class="text-muted small">Masuk ke dashboard Waterpay PAMS</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 px-3 py-3 d-flex align-items-center gap-3 text-start" role="alert" style="background-color: #fef2f2; color: #991b1b;">
                <i class="bi bi-exclamation-circle-fill fs-5 text-danger"></i>
                <span class="small fw-bold lh-sm">Username atau password yang Anda masukkan salah.</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            {{-- Input Username --}}
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <div class="input-group-modern">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
                    <i class="bi bi-person-fill input-icon"></i>
                </div>
            </div>
            
            {{-- Input Password --}}
            <div class="form-group">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="password" class="form-label mb-0">Password</label>
                    <a href="#" class="small fw-bold text-decoration-none" style="color: #64748b; font-size: 0.8rem;">Lupa?</a>
                </div>
                <div class="input-group-modern mt-2">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                    <i class="bi bi-key-fill input-icon"></i>
                </div>
            </div>
            
            <div class="form-group mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-muted small fw-bold ms-1 pt-1" for="remember">
                        Ingat Saya di perangkat ini
                    </label>
                </div>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    Login Sekarang <i class="bi bi-arrow-right-short fs-5"></i>
                </button>
            </div>
        </form>

        <div class="divider">Belum punya akun?</div>

        <div>
            <a href="{{ route('register.admin') }}" class="btn-register-link small">
                Daftarkan Perusahaan Baru
            </a>
        </div>
    </div>
</div>
    
@endsection