<aside class="sidebar-wrapper">
    <div class="sidebar-heading text-center py-4 fs-4 fw-bold text-uppercase">
        Waterpay
    </div>

    {{-- 
      Kita ambil role user sekali saja di atas 
      agar lebih bersih di dalam pengecekan @if
    --}}
    @php
        $userRole = Auth::user()->role;
    @endphp

    <div class="list-group list-group-flush my-3">

        {{-- =================================================== --}}
        {{-- Menu Dashboard (Semua Role Punya)                 --}}
        {{-- =================================================== --}}
        
        {{-- Asumsi: Anda punya route name yang berbeda untuk tiap dashboard role --}}
        @if($userRole == 'superadmin')
            <a href="{{ route('superadmin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
        @elseif($userRole == 'admin')
            <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
        @elseif($userRole == 'petugas')
            <a href="#" class="list-group-item list-group-item-action {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
        @elseif($userRole == 'pelanggan')
            <a href="#" class="list-group-item list-group-item-action {{ request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
        @endif


        {{-- =================================================== --}}
        {{-- Menu Khusus Super Admin                         --}}
        {{-- =================================================== --}}
        @if($userRole == 'superadmin')
            <a href="{{ route('superadmin.companies.pending') }}" class="list-group-item list-group-item-action {{ request()->routeIs('superadmin.companies.pending*') ? 'active' : '' }}">
                <i class="bi bi-building-check me-2"></i>Approval Perusahaan
            </a>
            <a href="{{ route('superadmin.management-users.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('superadmin.management-users.index*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i>Manajemen User
            </a>
        @endif


        {{-- =================================================== --}}
        {{-- Menu Khusus Admin                               --}}
        {{-- =================================================== --}}
        @if($userRole == 'admin')
            <a href="{{ route('admin.petugas.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.petugas.index*') ? 'active' : '' }}">
                <i class="bi bi-person-badge me-2"></i>Data Petugas
            </a>
            <a href="{{ route('admin.pelanggan.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.pelanggan.index*') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill me-2"></i>Data Pelanggan
            </a>
            <a href="{{ route('admin.harga.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.harga.index*') ? 'active' : '' }}">
                <i class="bi bi-cash-coin me-2"></i>Setting Harga
            </a>
            <a href="#" class="list-group-item list-group-item-action {{ request()->routeIs('admin.info.index*') ? 'active' : '' }}">
                <i class="bi bi-info-circle me-2"></i>Set Informasi
            </a>
            <a href="{{ route('admin.pemakaian.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.pemakaian.index*') ? 'active' : '' }}">
                <i class="bi bi-droplet-half me-2"></i>Data Pemakaian
            </a>
            <a href="#" class="list-group-item list-group-item-action {{ request()->routeIs('admin.laporan.index*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan
            </a>
        @endif


        {{-- =================================================== --}}
        {{-- Menu Khusus Petugas                             --}}
        {{-- =================================================== --}}
        @if($userRole == 'petugas')
            <a href="#" class="list-group-item list-group-item-action {{ request()->routeIs('petugas.pemakaian.index*') ? 'active' : '' }}">
                <i class="bi bi-droplet-half me-2"></i>Pemakaian Air
            </a>
        @endif


        {{-- =================================================== --}}
        {{-- Menu Sharing (Admin & Pelanggan)                --}}
        {{-- =================================================== --}}
        @if(in_array($userRole, ['admin', 'pelanggan']))
            <a href="#" class="list-group-item list-group-item-action {{ request()->routeIs('*.tagihan.index*') ? 'active' : '' }}">
                <i class="bi bi-receipt me-2"></i>Tagihan/Pembayaran
            </a>
        @endif


        {{-- =================================================== --}}
        {{-- Menu Sharing (Petugas, Pelanggan)        --}}
        {{-- =================================================== --}}
        @if(in_array($userRole, ['petugas', 'pelanggan']))
            <a href="{{ route( $userRole . '.keluhan.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('*.keluhan.index*') ? 'active' : '' }}">
                <i class="bi bi-exclamation-octagon me-2"></i>Keluhan
            </a>
            <a href="{{ route( $userRole . '.informasi.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('*.informasi.index*') ? 'active' : '' }}">
                <i class="bi bi-info-circle me-2"></i>Informasi
            </a>
        @endif
    </div>
</aside>