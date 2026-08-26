<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --ink: #172033;
            --muted: #718096;
            --blue: #246bfd;
            --soft-blue: #edf4ff;
            --canvas: #f6f8fc;
        }

        body {
            background: var(--canvas);
            color: var(--ink);
            font-family: "Segoe UI", sans-serif;
        }

        .sidebar {
            min-height: calc(100vh - 74px);
            background: var(--ink);
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            background: var(--white);
        }

        .sidebar .nav-link {
            color: #aeb9cc;
            border-radius: .5rem;
            padding: .75rem 1rem;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, .1);
        }

        .topbar { background: #fff; }
        .welcome-panel {
            background: linear-gradient(120deg, #246bfd, #4b8cff);
            color: #fff;
        }

        .stat-card, .content-card {
            border: 0;
            box-shadow: 0 8px 24px rgba(23, 32, 51, .05);
        }

        .stat-card {
            min-height: 150px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }

        .stats-grid > [class*="col-"] {
            width: auto;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            background: var(--soft-blue);
            color: var(--blue);
        }

        .text-muted { color: var(--muted) !important; }
        .avatar { width: 38px; height: 38px; background: #dce8ff; color: var(--blue); }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .quick-action {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .65rem;
            color: var(--ink);
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: .5rem;
            text-decoration: none;
            transition: border-color .2s ease, color .2s ease, box-shadow .2s ease;
        }

        .quick-action:hover {
            color: var(--blue);
            border-color: var(--blue);
            box-shadow: 0 6px 16px rgba(36, 107, 253, .12);
        }

        .quick-action i { font-size: 1.75rem; }

        .brand-name { font-size: 1.1rem; }

        .navbar-left { flex: 1 1 auto; }
        .navbar-search { width: min(420px, 36vw); }

        @media (max-width: 767.98px) {
            .sidebar { min-height: auto; }
            .sidebar .nav { flex-direction: row !important; overflow-x: auto; flex-wrap: nowrap; }
            .sidebar .nav-link { white-space: nowrap; }
        }

        @media (max-width: 1199.98px) {
            .stats-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }

        @media (max-width: 575.98px) {
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .navbar-search { width: 100%; }
            .navbar-left { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <header class="topbar border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
            <div class="navbar-left d-flex align-items-center gap-4">
                <i class="bi bi-box-seam fs-5"></i>
                <h1 class="h5 mb-0 fw-bold">Inventaris Barang</h1>
                <form method="GET" action="{{ route('dashboard') }}" class="navbar-search">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="search" name="q" class="form-control bg-light border-start-0" placeholder="Cari aset..." aria-label="Cari aset">
                    </div>
                </form>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3 flex-wrap justify-content-end">
                <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#scanQrModal">
                    <i class="bi bi-qr-code-scan me-1"></i> <span class="d-none d-lg-inline">Pindai QR</span>
                </button>
                <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#newAssetModal">
                    <i class="bi bi-plus-lg me-1"></i> <span class="d-none d-lg-inline">Aset Baru</span>
                </button>
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
                <form method="POST" action="{{ route('logout') }}" class="ms-2" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" type="submit">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <div class="row">
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
                    <a class="nav-link active" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="{{ route('assets.index') }}">
                        <i class="bi bi-boxes me-2"></i> Kelola Aset
                    </a>
                    <a class="nav-link" href="#scanQrModal" data-bs-toggle="modal">
                        <i class="bi bi-qr-code-scan me-2"></i> Pindai dan Lacak QR
                    </a>
                    <a class="nav-link" href="{{ route('approvals.index') }}">
                        <i class="bi bi-clipboard-check me-2"></i> Kelola Persetujuan
                    </a>
                    <a class="nav-link" href="{{ route('notifications.index') }}">
                        <i class="bi bi-bell me-2"></i> Pusat Notifikasi
                        <span class="badge rounded-pill bg-warning text-dark ms-auto">0</span>
                    </a>
                    <a class="nav-link" href="{{ route('qr.labels') }}">
                        <i class="bi bi-printer me-2"></i> Cetak Label QR
                    </a>
                </nav>

            </aside>

            <main class="col-md-9 col-lg-10 px-0">
                <div class="p-4 p-lg-5">
                    <section class="welcome-panel rounded-3 p-4 p-lg-4 mb-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <p class="text-white-50 mb-2">Selamat datang kembali</p>
                                <h2 class="fw-bold mb-2">Halo, {{ Auth::user()->name }}.</h2>
                                <p class="mb-0 text-white-50">Pantau kondisi dan aktivitas inventaris dari satu tempat.</p>
                            </div>
                        </div>
                    </section>

                    <section class="row g-4 stats-grid mb-4" id="inventaris">
                        <div class="col-sm-6 col-xl">
                            <div class="card stat-card h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div><p class="text-muted small mb-2">Total Barang</p><h3 class="fw-bold mb-0">{{ $totalBarang }}</h3></div>
                                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-box-seam fs-5"></i></div>
                                </div>
                                <small class="text-muted mt-3"><i class="bi bi-dash"></i> Belum ada data</small>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl">
                            <div class="card stat-card h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div><p class="text-muted small mb-2">Tersedia</p><h3 class="fw-bold mb-0">{{ $barangTersedia }}</h3></div>
                                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-check-circle fs-5"></i></div>
                                </div>
                                <small class="text-success mt-3"><i class="bi bi-arrow-up"></i> Siap digunakan</small>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl" id="peminjaman">
                            <div class="card stat-card h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div><p class="text-muted small mb-2">Dipinjam</p><h3 class="fw-bold mb-0">{{ $barangDipinjam }}</h3></div>
                                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-arrow-left-right fs-5"></i></div>
                                </div>
                                <small class="text-muted mt-3">Tidak ada peminjaman aktif</small>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl">
                            <div class="card stat-card h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div><p class="text-muted small mb-2">Perlu Perbaikan</p><h3 class="fw-bold mb-0">{{ $barangRusak }}</h3></div>
                                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-tools fs-5"></i></div>
                                </div>
                                <small class="text-muted mt-3">Semua kondisi terpantau</small>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl" id="peminjaman-terlambat">
                            <div class="card stat-card h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div><p class="text-muted small mb-2">Peminjaman Terlambat</p><h3 class="fw-bold mb-0">{{ $peminjamanTerlambat }}</h3></div>
                                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-exclamation-triangle-fill fs-5"></i></div>
                                </div>
                                <small class="text-danger mt-3"><i class="bi bi-arrow-up"></i> Perlu tindakan langsung</small>
                            </div>
                        </div>
                    </section>

                    <section class="row g-4" id="laporan">
                        <div class="col-lg-8">
                            <div class="card content-card h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div><h2 class="h5 fw-bold mb-1">Aset Terbaru</h2></div>
                                        <button class="btn btn-sm btn-outline-primary" type="button">Lihat semua</button>
                                    </div>
                                    @forelse($barangTerbaru as $barang)
                                        <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                            <div>
                                                <div class="fw-semibold">{{ $barang->nama_barang }}</div>
                                                <small class="text-muted">{{ $barang->kode_barang }} · {{ $barang->lokasi->nama_lokasi }}</small>
                                            </div>
                                            <span class="badge bg-light text-dark">{{ ucfirst($barang->status) }}</span>
                                        </div>
                                    @empty
                                        <div class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox display-5 d-block mb-3"></i>
                                            <p class="mb-0">Belum ada aset yang ditambahkan.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card content-card h-100">
                                <div class="card-body p-4">
                                    <h2 class="h5 fw-bold mb-1">Aksi Cepat</h2>
                                    <p class="small text-muted mb-4">Akses cepat ke fitur utama</p>
                                    <div class="quick-actions">
                                        <a class="quick-action" href="#scanQrModal" data-bs-toggle="modal">
                                            <i class="bi bi-qr-code-scan"></i>
                                            <span class="small fw-semibold text-center">Pindai QR</span>
                                        </a>
                                        <a class="quick-action" href="{{ route('qr.labels') }}">
                                            <i class="bi bi-printer"></i>
                                            <span class="small fw-semibold text-center">Cetak Label QR</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="scanQrModal" tabindex="-1" aria-labelledby="scanQrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h2 class="modal-title h5 fw-bold" id="scanQrModalLabel"><i class="bi bi-qr-code-scan me-2 text-primary"></i>Pindai dan Lacak QR</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center py-5">
                    <div id="dashboard-qr-reader"></div>
                    <div id="dashboard-scan-status" class="alert alert-secondary d-none mt-3" role="status"></div>
                    <div id="dashboard-scan-result" class="alert alert-success d-none mt-3" role="alert"></div>
                    <input type="file" id="dashboard-photo-input" accept="image/*" capture="environment" class="d-none">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" id="dashboard-photo-button"><i class="bi bi-image me-1"></i>Foto</button>
                    <button type="button" class="btn btn-primary" id="dashboard-camera-button"><i class="bi bi-camera me-1"></i>Buka Kamera</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newAssetModal" tabindex="-1" aria-labelledby="newAssetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h2 class="modal-title h5 fw-bold" id="newAssetModalLabel"><i class="bi bi-box-seam me-2 text-primary"></i>Tambah Aset Baru</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="assetName" class="form-label">Nama Aset</label>
                            <input type="text" class="form-control" id="assetName" name="nama_barang" placeholder="Contoh: Laptop Lenovo">
                        </div>
                        <div class="mb-3">
                            <label for="assetCode" class="form-label">Kode Aset</label>
                            <input type="text" class="form-control" id="assetCode" name="kode_barang" placeholder="Contoh: AST-001">
                        </div>
                        <div>
                            <label for="assetDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="assetDescription" name="spesifikasi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Aset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const dashboardReader = new Html5Qrcode('dashboard-qr-reader');
        const dashboardStatus = document.getElementById('dashboard-scan-status');
        const dashboardResult = document.getElementById('dashboard-scan-result');
        const dashboardCameraButton = document.getElementById('dashboard-camera-button');
        const dashboardPhotoButton = document.getElementById('dashboard-photo-button');
        const dashboardPhotoInput = document.getElementById('dashboard-photo-input');
        let dashboardCameraRunning = false;

        function setDashboardStatus(message, type = 'secondary') {
            dashboardStatus.className = `alert alert-${type} mt-3`;
            dashboardStatus.textContent = message;
        }

        function setDashboardResult(decodedText) {
            dashboardResult.className = 'alert alert-success mt-3';
            dashboardResult.innerHTML = '<strong>QR terbaca:</strong> <span></span>';
            dashboardResult.querySelector('span').textContent = decodedText;
        }

        function handleDashboardScan(decodedText) {
            if (dashboardCameraRunning) {
                dashboardReader.stop().catch(() => {});
                dashboardCameraRunning = false;
                dashboardCameraButton.innerHTML = '<i class="bi bi-camera me-1"></i>Buka Kamera';
            }
            setDashboardResult(decodedText);
            setDashboardStatus('Pemindaian selesai.', 'success');
        }

        dashboardCameraButton.addEventListener('click', async () => {
            if (dashboardCameraRunning) return;
            setDashboardStatus('Meminta izin kamera...', 'info');
            try {
                await dashboardReader.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: { width: 220, height: 220 } },
                    handleDashboardScan,
                    () => {}
                );
                dashboardCameraRunning = true;
                dashboardCameraButton.innerHTML = '<i class="bi bi-camera-fill me-1"></i>Kamera Aktif';
                setDashboardStatus('Arahkan kamera ke kode QR.', 'info');
            } catch (error) {
                setDashboardStatus('Kamera tidak dapat dibuka. Gunakan Foto atau periksa izin browser.', 'danger');
            }
        });

        dashboardPhotoButton.addEventListener('click', () => dashboardPhotoInput.click());
        dashboardPhotoInput.addEventListener('change', async (event) => {
            const file = event.target.files[0];
            if (!file) return;
            setDashboardStatus('Membaca QR dari foto...', 'info');
            try {
                setDashboardResult(await dashboardReader.scanFile(file, true));
                setDashboardStatus('Pemindaian selesai.', 'success');
            } catch (error) {
                setDashboardStatus('Kode QR tidak ditemukan pada foto.', 'warning');
            }
            dashboardPhotoInput.value = '';
        });

        document.getElementById('scanQrModal').addEventListener('hidden.bs.modal', () => {
            if (dashboardCameraRunning) {
                dashboardReader.stop().catch(() => {});
                dashboardCameraRunning = false;
            }
        });
    </script>
</body>
</html>