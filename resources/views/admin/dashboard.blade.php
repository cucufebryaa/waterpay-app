<!DOCTYPE html>
<html lang="id">
<head>
    </head>
<body>

<div class="d-flex" id="wrapper">
    <div class="bg-white" id="sidebar-wrapper">
        <div class="sidebar-heading py-4 fs-4 fw-bold text-center border-bottom">
            <a href="{{ route('superadmin.dashboard') }}" class="logo-link">
                <img src="{{ asset('images/waterpay.png') }}" alt="Waterpay PAMS Logo" class="logo-img">
            </a>
            <p class="mb-0 logo-text-p">Waterpay Pams</p>
        </div>
        <div class="list-group list-group-flush my-3">
            <a href="{{ route('superadmin.dashboard') }}" class="list-group-item list-group-item-action bg-transparent second-text active">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
            <a href="{{ route('superadmin.data-admin') }}" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                <i class="bi bi-person-circle me-2"></i>Admin
            </a>
            </div>
    </div>
    <div id="page-content-wrapper">
        </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/dashboard-script.js') }}"></script>
</body>
</html>