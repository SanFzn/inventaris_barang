<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Pengguna | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/assets.css') }}">
</head>
<body class="container-fluid">
    @include('partials.navbar')

    <div class="row">
        @include('partials.sidebar')

        <main class="col-md-9 col-lg-10 px-0">
            <div class="p-4 p-lg-5">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm mb-4" role="alert">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                {{-- Header --}}
                <section class="welcome-panel rounded-3 p-4 p-lg-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2 class="fw-bold mb-2">Kelola Pengguna</h2>
                            <p class="mb-0 text-white-50">Buat, edit, dan hapus akun pengguna sistem.</p>
                        </div>
                    </div>
                </section>

                {{-- Stats Cards --}}
                <section class="row g-4 stats-grid-4 mb-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="card stat-card h-100 p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><p class="text-muted small mb-2">Total User</p><h3 class="fw-bold mb-0">{{ $totalUsers }}</h3></div>
                                <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-person fs-5"></i></div>
                            </div>
                            <small class="text-muted mt-3"><i class="bi bi-dash"></i> Akun role user</small>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card stat-card h-100 p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><p class="text-muted small mb-2">Total Karyawan</p><h3 class="fw-bold mb-0">{{ $totalKaryawan }}</h3></div>
                                <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-person-badge fs-5"></i></div>
                            </div>
                            <small class="text-muted mt-3"><i class="bi bi-dash"></i> Akun role karyawan</small>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card stat-card h-100 p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><p class="text-muted small mb-2">Total Admin</p><h3 class="fw-bold mb-0">{{ $totalAdmin }}</h3></div>
                                <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-shield-check fs-5"></i></div>
                            </div>
                            <small class="text-muted mt-3"><i class="bi bi-dash"></i> Akun role admin</small>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card stat-card h-100 p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><p class="text-muted small mb-2">Total Semua</p><h3 class="fw-bold mb-0">{{ $totalUsers + $totalKaryawan + $totalAdmin }}</h3></div>
                                <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-people fs-5"></i></div>
                            </div>
                            <small class="text-primary mt-3"><i class="bi bi-people"></i> Seluruh pengguna</small>
                        </div>
                    </div>
                </section>

                {{-- User Table --}}
                <section class="mb-4">
                    <div class="card content-card">
                        <div class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-0">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-start gap-3 mb-3">
                                <div>
                                    <h2 class="h5 fw-bold mb-1">Daftar Pengguna</h2>
                                    <p class="text-muted small mb-0">Kelola semua akun yang terdaftar di sistem.</p>
                                </div>
                                <button class="btn btn-primary btn-sm text-nowrap flex-shrink-0" data-bs-toggle="modal" data-bs-target="#createUserModal">
                                    <i class="bi bi-person-plus me-1"></i> Buat Akun Baru
                                </button>
                            </div>

                            {{-- Filter --}}
                            <form method="GET" action="{{ route('users.index') }}" class="d-flex flex-wrap gap-2 mb-3">
                                <div class="input-group input-group-sm" style="max-width: 240px;">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="search" name="q" class="form-control bg-light border-start-0" placeholder="Cari username / email..." value="{{ $search }}">
                                </div>
                                <select name="role" class="form-select form-select-sm" style="max-width: 160px;" onchange="this.form.submit()">
                                    <option value="">Semua Role</option>
                                    <option value="user" {{ $roleFilter === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="karyawan" {{ $roleFilter === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                    <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @if($search || $roleFilter)
                                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-light" title="Reset filter"><i class="bi bi-x-lg"></i></a>
                                @endif
                            </form>
                        </div>

                        <div class="card-body p-0">
                            @forelse($users as $user)
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom p-3 p-md-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold flex-shrink-0 text-primary"
                                             style="width:44px;height:44px;font-size:1.1rem;border:2px solid #e2e8f0;">
                                            @if($user->foto_profil)
                                                <img src="{{ $user->profilPhotoUrl() }}" alt="foto" class="rounded-circle w-100 h-100" style="object-fit:cover;">
                                            @else
                                                {{ strtoupper(substr($user->username, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $user->username }}</div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2 flex-shrink-0 flex-wrap">
                                        @php
                                            $roleColor = match($user->role) {
                                                'admin'    => 'bg-danger',
                                                'karyawan' => 'bg-warning text-dark',
                                                default    => 'bg-primary',
                                            };
                                            $roleLabel = match($user->role) {
                                                'admin'    => 'Admin',
                                                'karyawan' => 'Karyawan',
                                                default    => 'User',
                                            };
                                        @endphp
                                        <span class="badge {{ $roleColor }} px-3 py-2">{{ $roleLabel }}</span>

                                        <button class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id_user }}">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </button>

                                        <button class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" data-bs-target="#deleteUserModal-{{ $user->id_user }}">
                                            <i class="bi bi-trash3 me-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>

                                {{-- Edit Modal --}}
                                <div class="modal fade" id="editUserModal-{{ $user->id_user }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header">
                                                <h2 class="modal-title h5 fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Akun</h2>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('users.update', $user->id_user) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Username</label>
                                                        <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Role</label>
                                                        <select name="role" class="form-select" required>
                                                            <option value="user"     {{ $user->role === 'user'     ? 'selected' : '' }}>User</option>
                                                            <option value="karyawan" {{ $user->role === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                                            <option value="admin"    {{ $user->role === 'admin'    ? 'selected' : '' }}>Admin</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Password Baru <span class="text-muted small fw-normal">(kosongkan jika tidak diganti)</span></label>
                                                        <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter">
                                                    </div>
                                                    <div class="mb-1">
                                                        <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                                        <input type="password" name="password_confirmation" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Delete Modal --}}
                                <div class="modal fade" id="deleteUserModal-{{ $user->id_user }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-body text-center p-4">
                                                <div class="rounded-circle bg-light text-danger d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;font-size:1.75rem;">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </div>
                                                <h2 class="h5 fw-bold mb-2">Hapus Akun?</h2>
                                                <p class="text-muted small mb-4">Akun <strong>{{ $user->username }}</strong> akan dihapus permanen dan tidak bisa dikembalikan.</p>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                                                    <form method="POST" action="{{ route('users.destroy', $user->id_user) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger px-4"><i class="bi bi-trash3 me-1"></i>Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-people display-5 d-block mb-3"></i>
                                    <p class="mb-0">Tidak ada pengguna ditemukan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    {{-- Modal Buat Akun Baru --}}
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h2 class="modal-title h5 fw-bold" id="createUserModalLabel"><i class="bi bi-person-plus me-2 text-primary"></i>Buat Akun Baru</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="Nama pengguna unik" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@contoh.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                                <option value="karyawan" {{ old('role') === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <div class="form-text text-muted small">
                                <i class="bi bi-info-circle me-1"></i>
                                <strong>User</strong>: dapat meminjam. &nbsp;
                                <strong>Karyawan</strong>: dapat meminjam. &nbsp;
                                <strong>Admin</strong>: akses penuh.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-person-check me-1"></i>Buat Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
</body>
</html>

