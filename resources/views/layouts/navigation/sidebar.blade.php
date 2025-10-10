<aside class="sidebar-wrapper">
    <div class="sidebar-heading text-center py-4 fs-4 fw-bold text-uppercase">
        Waterpay
    </div>
    <div class="list-group list-group-flush my-3">
        
        {{-- Menu untuk Semua Role yang Login --}}
        <a href="#" class="list-group-item list-group-item-action {{ request()->is('*/dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </a>

        {{-- Menu Khusus Super Admin --}}
        @if(Auth::user()->role == 'superadmin')
        <a href="{{ route('superadmin.companies.pending') }}" class="list-group-item list-group-item-action">
            <i class="bi bi-building-check me-2"></i>Approval Company
        </a>
        @endif

        {{-- START REVISI DROP-DOWN MANAJEMEN USER --}}
    <li class="nav-item">
        @php
            // Menentukan apakah salah satu submenu Management User aktif
            $isManagementActive = request()->routeIs('superadmin.management-users.index*');
            // Class kustom untuk styling parent (biru muda) saat salah satu submenu aktif
            $parentClass = $isManagementActive ? 'active-dropdown-parent' : '';
        @endphp
        {{-- Menu untuk Super Admin & Admin --}}
        @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
        <a href="{{ route('superadmin.management-users.index') }}" class="list-group-item list-group-item-action">
            <i class="bi bi-people me-2"></i>Manajemen User
        </a>
        @endif

        <a href="#" class="list-group-item list-group-item-action">
            <i class="bi bi-gear me-2"></i>Pengaturan
        </a>
    </div>
</aside>

<aside class="sidebar-wrapper">
    <div class="sidebar-heading text-center py-4 fs-4 fw-bold text-uppercase border-bottom">
        WATERPAY
    </div>
    
    <ul class="nav flex-column sidebar-nav">
        
        {{-- 1. Dashboard Admin --}}
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        {{-- 2. Data Petugas --}}
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('admin.petugas.index')) active @endif" href="{{ route('admin.petugas.index') }}">
                <i class="bi bi-person-workspace me-2"></i> Data Petugas
            </a>
        </li>
        
        {{-- 3. Data Pelanggan --}}
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('admin.pelanggan.index')) active @endif" href="{{ route('admin.pelanggan.index') }}">
                <i class="bi bi-person-lines-fill me-2"></i> Data Pelanggan
            </a>
        </li>

        {{-- 4. Pemakaian Air --}}
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('admin.pemakaian.index')) active @endif" href="{{ route('admin.pemakaian.index') }}">
                <i class="bi bi-droplet-half me-2"></i> Pemakaian Air
            </a>
        </li>
        
        {{-- 5. Pembayaran Pelanggan --}}
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('admin.pembayaran.index')) active @endif" href="{{ route('admin.pembayaran.index') }}">
                <i class="bi bi-credit-card me-2"></i> Pembayaran Pelanggan
            </a>
        </li>
        
        {{-- 6. Keluhan Pelanggan --}}
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('admin.keluhan.index')) active @endif" href="{{ route('admin.keluhan.index') }}">
                <i class="bi bi-chat-square-text me-2"></i> Keluhan Pelanggan
            </a>
        </li>

        {{-- 7. Laporan --}}
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('admin.laporan.index')) active @endif" href="{{ route('admin.laporan.index') }}">
                <i class="bi bi-bar-chart-line me-2"></i> Laporan
            </a>
        </li>
        
        {{-- 8. Informasi (Pengaturan/Umum) --}}
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('admin.informasi.index')) active @endif" href="{{ route('admin.informasi.index') }}">
                <i class="bi bi-info-circle me-2"></i> Informasi
            </a>
        </li>
        
        {{-- Pengaturan (Contohnya di footer/bagian bawah) --}}
        <li class="nav-item mt-auto">
            <a class="nav-link @if(request()->routeIs('admin.pengaturan')) active @endif" href="#">
                <i class="bi bi-gear-fill me-2"></i> Pengaturan
            </a>
        </li>
    </ul>
</aside>

