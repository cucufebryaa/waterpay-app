<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Waterpay PAMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
</head>
<body>

<div class="d-flex" id="wrapper">
    <div class="bg-white" id="sidebar-wrapper">
        <div class="sidebar-heading text-center py-4 fs-4 fw-bold text-uppercase border-bottom">
            <span class="logo-text">WATERPAY</span>
        </div>
        <div class="list-group list-group-flush my-3">
            <a href="{{ route('superadmin.dashboard') }}" class="list-group-item list-group-item-action bg-transparent second-text active">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
            <a href="{{ route('superadmin.data-admin') }}" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                <i class="bi bi-person-circle me-2"></i>Admin
            </a>
            <a href="{{ route('superadmin.data-petugas') }}" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                <i class="bi bi-person-circle me-2"></i>Petugas
            </a>
            <a href="{{ route('superadmin.data-pelanggan') }}" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                <i class="bi bi-person-circle me-2"></i>Pelanggan
            </a>
            <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                <i class="bi bi-building me-2"></i>Approval Company
            </a>
            <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                <i class="bi bi-gear-wide-connected me-2"></i>Pengaturan
            </a>
        </div>
    </div>
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4 border-bottom">
            <div class="d-flex align-items-center">
                <i class="bi bi-list primary-text fs-4 me-3" id="menu-toggle"></i>
                <h2 class="fs-2 m-0">@yield('title')</h2>
            </div>
            <div class="ms-auto d-flex align-items-center">
                <a href="#" class="text-dark me-3"><i class="bi bi-bell fs-5"></i></a>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2 text-uppercase">{{ Auth::user()->username }}</span>
                        <i class="bi bi-person-circle me-2 fs-5"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid px-4">
            @yield('content')
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/dashboard-script.js') }}"></script>
</body>
</html>