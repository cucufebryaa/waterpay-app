<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Waterpay App</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="auth-bg">
    <main class="auth-wrapper">
        <div class="auth-card">
            {{-- Tombol Close --}}
            <a href="{{ url('/') }}" class="btn-close-card" aria-label="Close"><i class="bi bi-x-lg"></i></a>

            <div class="row g-0">
                
                {{-- Kolom Ilustrasi sekarang bisa di-override --}}
                @section('illustration')
                <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center p-5">
                    <img src="{{ asset('images/hello.svg') }}" class="img-fluid auth-illustration-img" alt="Ilustrasi Login">
                </div>
                @show
                
                {{-- Kolom Form sekarang lebarnya dinamis --}}
                <div class="@yield('form_column_class', 'col-md-6') d-flex align-items-center justify-content-center p-5">
                    <div class="w-100">
                        @yield('content')
                    </div>
                </div>

            </div>
        </div>
    </main>
    <script src="{{ asset('js/auth.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/auth.js') }}"></script>

    {{-- Script untuk menampilkan notifikasi SweetAlert --}}
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: "{{ session('error') }}",
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Pendaftaran Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: true // Tampilkan tombol OK agar user bisa membaca pesan
        });
    </script>
    @endif
</body>
</html>