<header class="topbar border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
    <div class="navbar-left d-flex align-items-center gap-4">
        <i class="bi bi-box-seam fs-5"></i>
        <h1 class="h5 mb-0 fw-bold">Inventaris Barang</h1>
        <form method="GET" action="{{ route('dashboard') }}" class="navbar-search">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                <input type="search" name="q" class="form-control bg-light border-start-0" placeholder="Cari aset..." aria-label="Cari aset" value="{{ request('q') }}">
            </div>
        </form>
    </div>
    <div class="d-flex align-items-center gap-2 gap-lg-3 flex-wrap justify-content-end">
        <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#scanQrModal">
            <i class="bi bi-qr-code-scan me-1"></i> <span class="d-none d-lg-inline">Pindai QR</span>
        </button>
        @if (Auth::user()->role === 'admin')
            <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#newAssetModal">
                <i class="bi bi-plus-lg me-1"></i> <span class="d-none d-lg-inline">Aset Baru</span>
            </button>
        @endif
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
        <form method="POST" action="{{ route('logout') }}" class="ms-2 logout-form">
            @csrf
            <button class="btn btn-outline-danger btn-sm" type="submit">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>
</header>
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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const logoutForm = document.querySelector('.logout-form');
        const logoutModalElement = document.getElementById('logoutConfirmModal');
        const confirmLogoutButton = document.getElementById('confirmLogoutButton');

        if (!logoutForm || !logoutModalElement || !confirmLogoutButton) {
            return;
        }

        const logoutModal = new bootstrap.Modal(logoutModalElement);
        logoutForm.addEventListener('submit', (event) => {
            event.preventDefault();
            logoutModal.show();
        });
        confirmLogoutButton.addEventListener('click', () => logoutForm.submit());
    });
</script>
