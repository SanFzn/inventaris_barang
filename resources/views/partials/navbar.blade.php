<header class="topbar border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
    <div class="navbar-left d-flex align-items-center gap-3">
        <a href="{{ route('dashboard') }}" class="navbar-brand fw-bold mb-0"><i class="bi bi-box-seam fs-5 text-primary"></i>Inventaris Barang</a>
    </div>
    <div class="d-flex align-items-center gap-2 gap-lg-3 flex-wrap justify-content-end">
       
        <a class="btn btn-light btn-sm bg-white position-relative" href="{{ route('notifications.index') }}" aria-label="Buka pusat notifikasi" title="Pusat notifikasi">
            <i class="bi bi-bell"></i>
            @if ($notificationUnreadCount > 0)
                <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle" aria-label="{{ $notificationUnreadCount }} notifikasi belum dibaca">{{ $notificationUnreadCount }}</span>
            @endif
        </a>
        <button type="button" class="btn p-0 border-0 bg-transparent d-flex align-items-center gap-2"
            data-bs-toggle="modal" data-bs-target="#profileModal"
            title="Lihat profil akun" aria-label="Buka profil akun">
            @if (Auth::user()->foto_profil)
                <img src="{{ Auth::user()->profilPhotoUrl() }}"
                    alt="Foto profil"
                    class="rounded-circle object-fit-cover"
                    style="width: 36px; height: 36px; object-fit: cover; border: 2px solid #e5e7eb;">
            @else
                <div class="avatar rounded-circle d-flex align-items-center justify-content-center fw-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <div class="d-none d-sm-block text-start">
                <div class="small fw-bold">{{ Auth::user()->name }}</div>
                <div class="small text-muted text-capitalize">{{ Auth::user()->role }}</div>
            </div>
            <i class="bi bi-chevron-down small text-muted d-none d-sm-inline"></i>
        </button>
    </div>
</header>

@include('partials.profile-modal')
