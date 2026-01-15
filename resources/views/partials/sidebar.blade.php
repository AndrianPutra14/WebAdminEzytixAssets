<div class="sidebar">

    {{-- TOP --}}
    <div>
        <!-- LOGO -->
        <div class="sidebar-header d-flex align-items-center gap-2 mb-4">
            <div style="font-size:28px;">🟥</div>
            <div>
                <div class="fw-bold text-white">AVM System</div>
                <small>Maintenance Ezytix</small>
            </div>
        </div>

        <!-- MENU -->
        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span class="ms-2">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('data.maintenance') }}"
                   class="nav-link {{ request()->routeIs('data.maintenance') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-data"></i>
                    <span class="ms-2">Data Maintenance</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('data.barang') }}"
                   class="nav-link {{ request()->routeIs('data.barang') ? 'active' : '' }}">
                    <i class="bi bi-box"></i>
                    <span class="ms-2">Data Barang</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('buat.ticket') }}"
                   class="nav-link {{ request()->routeIs('buat.ticket') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    <span class="ms-2">Buat Ticket</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('history') }}"
                   class="nav-link {{ request()->routeIs('history') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span class="ms-2">History</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('manajemen.users') }}"
                   class="nav-link {{ request()->routeIs('manajemen.users') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span class="ms-2">Manajemen User</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('profile') }}"
                   class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <i class="bi bi-person"></i>
                    <span class="ms-2">Profile</span>
                </a>
            </li>
        </ul>
    </div>

    {{-- BOTTOM --}}
    <div class="sidebar-footer">
        <div class="user-box mb-3">
            <small>Logged in as</small>
            <div class="fw-semibold text-white">
                {{ auth()->user()->name ?? 'admin' }}
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn w-100 text-white" style="background: var(--primary);">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </div>
</div>
