{{-- Profile Account Modal --}}
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h2 class="modal-title h5" id="profileModalLabel">
                    <i class="bi bi-person-circle me-2 text-primary"></i>Profil Akun
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body pt-3">
                {{-- Avatar / Foto Profil & Info --}}
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                    {{-- Foto dengan overlay upload --}}
                    <div class="position-relative" style="flex-shrink: 0;">
                        <div class="profile-photo-wrap rounded-circle overflow-hidden"
                            style="width: 64px; height: 64px; cursor: pointer;"
                            onclick="document.getElementById('fotoProfilInput').click()"
                            title="Klik untuk ganti foto">

                            @if (Auth::user()->foto_profil)
                                <img id="profilePhotoPreview"
                                    src="{{ Auth::user()->profilPhotoUrl() }}"
                                    alt="Foto profil {{ Auth::user()->name }}"
                                    class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                            @else
                                <div id="profilePhotoInitial"
                                    class="w-100 h-100 d-flex align-items-center justify-content-center fw-bold fs-4 text-white"
                                    style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <img id="profilePhotoPreview" src="" alt="" class="w-100 h-100 d-none" style="object-fit: cover;">
                            @endif

                            {{-- Overlay hover --}}
                            <div class="profile-photo-overlay position-absolute top-0 start-0 w-100 h-100
                                d-flex align-items-center justify-content-center rounded-circle"
                                style="background: rgba(0,0,0,0.45); opacity: 0; transition: opacity .2s;">
                                <i class="bi bi-camera-fill text-white fs-5"></i>
                            </div>
                        </div>

                        {{-- Tombol hapus foto --}}
                        @if (Auth::user()->foto_profil)
                        <form method="POST" action="{{ route('profile.photo.delete') }}" id="deletePhotoForm" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="btn btn-danger btn-sm rounded-circle position-absolute p-0 d-flex align-items-center justify-content-center"
                                style="width: 22px; height: 22px; bottom: 0; right: 0; font-size: 10px;"
                                title="Hapus foto profil"
                                onclick="return confirm('Hapus foto profil?')">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </form>
                        @endif
                    </div>

                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold text-truncate">{{ Auth::user()->name }}</div>
                        <div class="small text-muted text-capitalize">{{ Auth::user()->role }}</div>
                        <div class="small text-muted text-truncate">{{ Auth::user()->email }}</div>
                        <div class="mt-1">
                            <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2"
                                onclick="document.getElementById('fotoProfilInput').click()" style="font-size: 0.75rem;">
                                <i class="bi bi-upload me-1"></i>Ganti Foto
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Form upload foto tersembunyi --}}
                <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data" id="photoUploadForm">
                    @csrf
                    <input type="file" id="fotoProfilInput" name="foto_profil"
                        accept="image/jpeg,image/png,image/webp" class="d-none">
                    @error('foto_profil')
                        <div class="alert alert-danger py-2 small mb-3">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </form>

                {{-- Flash messages inside modal --}}
                @if (session('profile_success'))
                    <div class="alert alert-success alert-dismissible fade show py-2 small" role="alert">
                        <i class="bi bi-check-circle me-1"></i>{{ session('profile_success') }}
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif
                @if (session('profile_error'))
                    <div class="alert alert-danger alert-dismissible fade show py-2 small" role="alert">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ session('profile_error') }}
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                {{-- Tabs --}}
                <ul class="nav nav-pills nav-fill gap-1 mb-3" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-info" data-bs-toggle="pill" data-bs-target="#pane-info"
                            type="button" role="tab" aria-controls="pane-info" aria-selected="true">
                            <i class="bi bi-person me-1"></i>Informasi
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-password" data-bs-toggle="pill" data-bs-target="#pane-password"
                            type="button" role="tab" aria-controls="pane-password" aria-selected="false">
                            <i class="bi bi-shield-lock me-1"></i>Ubah Password
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="profileTabContent">
                    {{-- Pane: Info --}}
                    <div class="tab-pane fade show active" id="pane-info" role="tabpanel" aria-labelledby="tab-info">
                        <form method="POST" action="{{ route('profile.update') }}" id="profileInfoForm">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="profile_username" class="form-label">Username</label>
                                <input type="text" id="profile_username" name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username', Auth::user()->username) }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="profile_email" class="form-label">Email</label>
                                <input type="email" id="profile_email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', Auth::user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control bg-light" value="{{ ucfirst(Auth::user()->role) }}" readonly>
                                <div class="form-text">Role tidak dapat diubah.</div>
                            </div>
                        </form>
                    </div>

                    {{-- Pane: Change Password --}}
                    <div class="tab-pane fade" id="pane-password" role="tabpanel" aria-labelledby="tab-password">
                        <form method="POST" action="{{ route('profile.password') }}" id="profilePasswordForm">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <div class="input-group">
                                    <input type="password" id="current_password" name="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        placeholder="Masukkan password saat ini" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password" tabindex="-1">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" id="new_password" name="new_password"
                                        class="form-control @error('new_password') is-invalid @enderror"
                                        placeholder="Minimal 6 karakter" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password" tabindex="-1">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-0">
                                <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                        class="form-control" placeholder="Ulangi password baru" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password_confirmation" tabindex="-1">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="saveProfileBtn">
                    <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Hover overlay on photo --}}
<style>
.profile-photo-wrap:hover .profile-photo-overlay { opacity: 1 !important; }
</style>

{{-- Re-open modal if there are profile-related errors or messages --}}
@if (session('profile_success') || session('profile_error') || $errors->has('username') || $errors->has('email') || $errors->has('current_password') || $errors->has('new_password') || $errors->has('foto_profil'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    var profileModalEl = document.getElementById('profileModal');
    if (!profileModalEl) return;
    var profileModal = new bootstrap.Modal(profileModalEl);
    profileModal.show();

    @if ($errors->has('current_password') || $errors->has('new_password'))
    profileModalEl.addEventListener('shown.bs.modal', function () {
        var passwordTab = document.getElementById('tab-password');
        if (passwordTab) { new bootstrap.Tab(passwordTab).show(); }
    }, { once: true });
    @endif
});
</script>
@endif

{{-- Photo preview & auto-submit on file select --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    var input   = document.getElementById('fotoProfilInput');
    var preview = document.getElementById('profilePhotoPreview');
    var initial = document.getElementById('profilePhotoInitial');
    var form    = document.getElementById('photoUploadForm');

    if (!input || !preview || !form) return;

    input.addEventListener('change', function () {
        var file = this.files[0];
        if (!file) return;

        // Validasi sisi klien: ukuran max 2 MB
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto maksimal 2 MB.');
            this.value = '';
            return;
        }

        // Preview langsung sebelum upload
        var reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (initial) initial.classList.add('d-none');
        };
        reader.readAsDataURL(file);

        // Auto-submit form upload
        form.submit();
    });
});
</script>
