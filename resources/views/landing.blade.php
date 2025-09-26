<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waterpay App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid container-custom">
            <a class="navbar-brand" href="#">
                <span class="logo-text">Waterpay PAMS</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav mx-auto nav-links">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Narahubung</a>
                    </li>
                </ul>
                <div class="d-flex nav-buttons">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Masuk</a>
                    <a href="{{ route('register.admin') }}" class="btn btn-primary">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-content d-flex align-items-center justify-content-center">
        <div class="container-custom text-center text-md-start">
            <div class="row align-items-center">
                <div class="col-md-7 hero-text">
                    <h1 class="display-3 fw-bold mb-4">
                        Waterpay PAMS
                    </h1>
                    <p class="lead mb-4 text-muted">
                        Sistem pembayaran tagihan air bersih yang mudah, cepat, dan terintegrasi. Kelola pembayaran Anda dari mana saja, kapan saja.
                    </p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-md-start">
                    
                    </div>
                </div>
                <div class="col-md-5 hero-image-container d-none d-md-block">
                                    </div>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom text-center py-3">
        <p class="text-muted small mb-0">&copy; 2025 Waterpay PAMS. All Rights Reserved.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>