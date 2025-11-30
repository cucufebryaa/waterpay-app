<nav class="navbar navbar-expand-lg modern-topbar">
    <div class="topbar-date">
        <i class="bi bi-calendar3"></i>
        <span>{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</span>
    </div>

    <div class="ms-auto">
        <div class="dropdown user-dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
                <span>{{ Auth::user()->name ?? Auth::user()->username }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-person-gear"></i>
                        Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item logout-btn">
                            <i class="bi bi-box-arrow-right"></i>
                            Exit
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>