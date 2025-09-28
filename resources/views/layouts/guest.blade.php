<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Waterpay PAMS')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <div class="loader-wrapper">
        <div class="loader"></div>
    </div>

    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
        {{-- 
            PERUBAHAN KUNCI DI SINI:
            - Kelas '.container-custom' diubah menjadi '.container-fluid'.
            - '.container-fluid' akan membuat lebar navbar menjadi 100% dari lebar layar.
        --}}
        <div class="container-fluid px-3">
            <a class="navbar-brand" href="/">
                <span class="logo-text">Waterpay App</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">About Us</a> 
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Contact</a>
                    </li>
                </ul>
                <div class="d-flex nav-buttons align-items-center">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                    <a href="{{ route('register.admin') }}" class="btn btn-primary">Register Company</a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>
    
    <footer class="footer-bottom text-center py-3">
        <p class="text-muted small mb-0">&copy; {{ date('Y') }} Waterpay PAMS. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>