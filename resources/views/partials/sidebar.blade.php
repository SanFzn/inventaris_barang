<aside class="col-md-3 col-lg-2 px-0 sidebar">
    <div class="d-flex align-items-center gap-2 px-4 py-4 text-white">
        <div class="brand-mark rounded-3 d-flex align-items-center justify-content-center">
            <i class="bi bi-buildings fs-5"></i>
        </div>
        <div>
            <div class="fw-bold brand-name">PT CGY</div>
        </div>
    </div>

    <nav class="nav flex-column gap-1 px-3">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}" href="{{ route('assets.index') }}">
            <i class="bi bi-boxes me-2"></i> Kelola Aset
        </a>
        <a class="nav-link" href="#scanQrModal" data-bs-toggle="modal">
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
    </nav>
</aside>
