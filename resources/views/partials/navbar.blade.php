<header class="topbar border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
    <div class="navbar-left d-flex align-items-center gap-3">
        <i class="bi bi-box-seam fs-5 text-primary"></i>
        <a href="{{ route('dashboard') }}" class="navbar-brand fw-bold mb-0">Inventaris Barang</a>
    </div>
    <div class="d-flex align-items-center gap-2 gap-lg-3 flex-wrap justify-content-end">
       
        <a class="btn btn-light btn-sm bg-white position-relative" href="{{ route('notifications.index') }}" aria-label="Buka pusat notifikasi" title="Pusat notifikasi">
            <i class="bi bi-bell"></i>
            @if (session('notification_count', 0) > 0)
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" aria-label="Ada notifikasi baru"></span>
            @endif
        </a>
        <div class="avatar rounded-circle d-flex align-items-center justify-content-center fw-bold">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="d-none d-sm-block">
            <div class="small fw-bold">{{ Auth::user()->name }}</div>
            <div class="small text-muted text-capitalize">{{ Auth::user()->role }}</div>
        </div>
    </div>
</header>
