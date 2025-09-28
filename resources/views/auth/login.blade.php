@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    
    <h1 class="h2 fw-bold text-center mb-4">Waterpay Log In</h1>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            Username atau password salah.
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        {{-- Input Username --}}
        <div class="form-group mb-4">
            <label for="username" class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username Anda" value="{{ old('username') }}" required autofocus>
            </div>
        </div>
        
        {{-- Input Password --}}
        <div class="form-group mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
            </div>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                    Ingat Saya
                </label>
            </div>
            <a href="#" class="small">Lupa Password?</a>
        </div>
        
        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary btn-lg">Login</button>
        </div>
    </form>

    <div class="text-center mt-4">
        <p class="text-muted mb-2">Belum memiliki akun perusahaan?</p>
        <a href="{{ route('register.admin') }}" class="btn btn-register-link">
            Daftarkan Perusahaan PAM Anda
        </a>
    </div>
    
@endsection