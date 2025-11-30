<aside class="sidebar-wrapper">
    <div class="sidebar-heading text-center">
        Waterpay
    </div>

    @php
        $userRole = Auth::user()->role;
    @endphp

    <div class="list-group list-group-flush">
        {{-- Dashboard Menu --}}
        @if($userRole == 'superadmin')
            <a href="{{ route('superadmin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>Dashboard
            </a>
        @elseif($userRole == 'admin')
            <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>Dashboard
            </a>
        @elseif($userRole == 'petugas')
            <a href="{{ route('petugas.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>Dashboard
            </a>
        @elseif($userRole == 'pelanggan')
            <a href="{{ route('pelanggan.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>Dashboard
            </a>
        @endif

        {{-- Super Admin Menu --}}
        @if($userRole == 'superadmin')
            <a href="{{ route('superadmin.companies.pending') }}" class="list-group-item list-group-item-action {{ request()->routeIs('superadmin.companies.pending*') ? 'active' : '' }}">
                <i class="bi bi-building-check"></i>Approval Perusahaan
            </a>
            <a href="{{ route('superadmin.management-users.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('superadmin.management-users.index*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>Manajemen User
            </a>
        @endif

        {{-- Admin Menu --}}
        @if($userRole == 'admin')
            <a href="{{ route('admin.petugas.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.petugas.index*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i>Data Petugas
            </a>
            <a href="{{ route('admin.pelanggan.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.pelanggan.index*') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i>Data Pelanggan
            </a>
            <a href="{{ route('admin.harga.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.harga.index*') ? 'active' : '' }}">
                <i class="bi bi-cash-coin"></i>Setting Harga
            </a>
            <a href="{{ route('admin.informasi.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.informasi.index*') ? 'active' : '' }}">
                <i class="bi bi-info-circle"></i>Set Informasi
            </a>
            <a href="{{ route('admin.pemakaian.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.pemakaian.index*') ? 'active' : '' }}">
                <i class="bi bi-droplet-half"></i>Data Pemakaian
            </a>
            <a href="{{ route('admin.keluhan.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.keluhan.index*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph"></i>Keluhan
            </a>
            <a href="{{ route('admin.pembayaran.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.pembayaran.index*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>Data Pembayaran
            </a>
        @endif

        {{-- Petugas Menu --}}
        @if($userRole == 'petugas')
            <a href="{{ route($userRole . '.maintenance.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('petugas.maintenance*') ? 'active' : '' }}">
                <i class="bi bi-tools"></i>Task Maintenance
            </a>
            <a href="{{ route($userRole . '.pemakaian.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('petugas.pemakaian.index*') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>Input Data Pemakaian
            </a>
        @endif

        {{-- Pelanggan Menu --}}
        @if(in_array($userRole, ['pelanggan']))
            <a href="{{ route($userRole . '.profile.edit') }}" class="list-group-item list-group-item-action {{ request()->routeIs('*.profile.edit') ? 'active' : '' }}">
                <i class="bi bi-person"></i>Kelola Profile
            </a>
            <a href="{{ route($userRole . '.tagihan.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('*.tagihan.index*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>Tagihan/Pembayaran
            </a>
            <a href="{{ route( $userRole . '.transaction.create') }}" class="list-group-item list-group-item-action {{ request()->routeIs('*.tagihan.index*') ? 'active' : '' }}">
                <i class="bi bi-exclamation-octagon"></i>Keluhan
            </a>
        @endif
    </div>
</aside>