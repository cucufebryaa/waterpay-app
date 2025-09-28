<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
    <div class="d-flex align-items-center">
        <i class="bi bi-calendar3 me-2"></i>
        <span>{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</span>
    </div>

    <div class="ms-auto">
        <div class="dropdown">
            <a class="nav-link dropdown-toggle fw-bold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-2"></i>
                {{ Auth::user()->name ?? Auth::user()->username }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="post"> {{-- Ganti route logout nanti --}}
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i>Exit
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>