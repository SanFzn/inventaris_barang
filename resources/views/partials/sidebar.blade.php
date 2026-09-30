<aside class="col-md-3 col-lg-2 px-0 sidebar d-flex flex-column">
    <div class="d-none d-md-flex align-items-center gap-2 px-4 py-4 text-white">
        <div class="brand-mark rounded-3 d-flex align-items-center justify-content-center">
            <i class="bi bi-buildings fs-5"></i>
        </div>
        <div>
            <div class="fw-bold brand-name">PT CGY</div>
        </div>
    </div>

    <nav class="nav flex-column gap-1 px-3 flex-grow-1">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}" href="{{ route('assets.index') }}">
            <i class="bi bi-boxes me-2"></i> Kelola Aset
        </a>
        <a class="nav-link {{ request()->routeIs('qr.index') ? 'active' : '' }}" href="{{ route('qr.index') }}">
            <i class="bi bi-qr-code-scan me-2"></i> Pindai dan Lacak QR
        </a>
        <a class="nav-link {{ request()->routeIs('approvals.*') ? 'active' : '' }}" href="{{ route('approvals.index') }}">
            <i class="bi bi-clipboard-check me-2"></i> Kelola Persetujuan
        </a>
        <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
            <i class="bi bi-bell me-2"></i> Pusat Notifikasi
            <span class="badge rounded-pill bg-warning text-dark ms-auto">0</span>
        </a>
        <a class="nav-link {{ request()->routeIs('qr.labels') ? 'active' : '' }}" href="{{ route('qr.labels') }}">
            <i class="bi bi-printer me-2"></i> Cetak Label QR
        </a>

        {{-- Logout Sejajar Menu --}}
        <div class="mt-md-auto pt-2 pt-md-3 border-top border-secondary border-opacity-25 pb-2 pb-md-3 sidebar-logout-wrapper">
            <form method="POST" action="{{ route('logout') }}" class="logout-form m-0 w-100">
                @csrf
                <button type="submit" class="nav-link logout-nav-link">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>
    </nav>
</aside>

{{-- Logout Confirmation Modal --}}
<div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="rounded-circle bg-light text-danger d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; font-size: 1.75rem;">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
                <h2 class="h5 fw-bold mb-2" id="logoutConfirmModalLabel">Keluar dari aplikasi?</h2>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmLogoutButton"><i class="bi bi-box-arrow-right me-1"></i>Logout</button>
                </div>
            </div>
        </div>
    </div>
</div>
