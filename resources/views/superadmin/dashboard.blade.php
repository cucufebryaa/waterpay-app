<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Admin</title>
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
            <a href="#" class="list-group-item list-group-item-action bg-transparent second-text active">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
            <a href="{{ route('superadmin.data-admin') }}" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                <i class="bi bi-person-circle me-2"></i>Admin
            </a>
            <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                <i class="bi bi-person-circle me-2"></i>Petugas
            </a>
            <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
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
        <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-list primary-text fs-4 me-3" id="menu-toggle"></i>
                <h2 class="fs-2 m-0">Dashboard</h2>
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
            <div class="row g-3 my-2">
                <div class="col-12">
                    <div class="dashboard-card py-3 px-4 text-center">
                        <h5 class="fw-bold mb-0">Selamat Datang di Sistem Tagihan Air Bersih</h5>
                    </div>
                </div>
            </div>

            <div class="row g-3 my-4">
                <div class="col-md-4">
                    <div class="p-4 bg-white shadow-sm rounded-3 text-center">
                        <h4 class="fw-bold text-dark mb-0">Jumlah Admin</h4>
                        <p class="fs-2 fw-bold primary-text mb-0 mt-2">{{ $adminCount }}</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-4 bg-white shadow-sm rounded-3 text-center">
                        <h4 class="fw-bold text-dark mb-0">Jumlah Petugas</h4>
                        <p class="fs-2 fw-bold primary-text mb-0 mt-2">{{ $petugasCount }}</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-4 bg-white shadow-sm rounded-3 text-center">
                        <h4 class="fw-bold text-dark mb-0">Jumlah Pelanggan</h4>
                        <p class="fs-2 fw-bold primary-text mb-0 mt-2">{{ $pelangganCount }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/dashboard-script.js') }}"></script>
</body>
</html>