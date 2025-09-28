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
        <a href="#" class="list-group-item list-group-item-action">
            <i class="bi bi-building-check me-2"></i>Approval Company
        </a>
        @endif

        {{-- Menu untuk Super Admin & Admin --}}
        @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
        <a href="#" class="list-group-item list-group-item-action">
            <i class="bi bi-people me-2"></i>Manajemen User
        </a>
        @endif
        
        <a href="#" class="list-group-item list-group-item-action">
            <i class="bi bi-gear me-2"></i>Pengaturan
        </a>
    </div>
</aside>