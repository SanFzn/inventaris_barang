<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Inventaris Barang</title>
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
                        <div id="loginAlert" class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm mb-4" role="alert">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                    @endif
                    <section class="welcome-panel rounded-3 p-4 p-lg-4 mb-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
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
                                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                                        <div>
                                            <h2 class="h5 fw-bold mb-1">Aset Terbaru</h2>
                                            <p class="text-muted small mb-0">Pantau aset yang baru ditambahkan.</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 m-0">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                                    <input type="search" name="q" class="form-control bg-light border-start-0" placeholder="Cari aset..." value="{{ request('q') }}" style="min-width: 160px; max-width: 220px;">
                                                </div>
                                                @if(request()->filled('q'))
                                                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light" title="Reset pencarian"><i class="bi bi-x-lg"></i></a>
                                                @endif
                                            </form>
                                            <a class="btn btn-sm btn-outline-primary text-nowrap" href="{{ route('assets.index') }}">Lihat semua</a>
                                        </div>
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
                                        @if (Auth::user()->role === 'admin')
                                            <a class="quick-action" href="#newAssetModal" data-bs-toggle="modal">
                                                <i class="bi bi-plus-circle"></i>
                                                <span class="small fw-semibold text-center">Tambah Aset</span>
                                            </a>
                                        @endif
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

    <div class="modal fade" id="borrowAssetModal" tabindex="-1" aria-labelledby="borrowAssetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h2 class="modal-title h5 fw-bold" id="borrowAssetModalLabel"><i class="bi bi-box-arrow-up-right me-2 text-primary"></i>Pinjam Barang</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form method="POST" action="{{ route('loans.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="borrowAsset" class="form-label">Barang</label>
                            <select id="borrowAsset" name="id_barang" class="form-select" required>
                                <option value="">Pilih barang tersedia</option>
                                @forelse ($barangUntukDipinjam as $barang)
                                    <option value="{{ $barang->id_barang }}">{{ $barang->kode_barang }} - {{ $barang->nama_barang }}</option>
                                @empty
                                    <option value="" disabled>Tidak ada barang tersedia</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="borrowReturnDate" class="form-label">Rencana tanggal kembali</label>
                            <input id="borrowReturnDate" name="tgl_kembali" type="datetime-local" class="form-control">
                        </div>
                        <div>
                            <label for="borrowNote" class="form-label">Keterangan</label>
                            <textarea id="borrowNote" name="keterangan" class="form-control" rows="3" placeholder="Keperluan peminjaman"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" @disabled($barangUntukDipinjam->isEmpty())><i class="bi bi-send me-1"></i>Kirim Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="scanQrModal" tabindex="-1" aria-labelledby="scanQrModalLabel" aria-hidden="true" data-generate-url="{{ route('qr.generate', ':code') }}">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h2 class="modal-title h5 fw-bold" id="scanQrModalLabel"><i class="bi bi-qr-code-scan me-2 text-primary"></i>Pindai dan Lacak QR</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center p-4">
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
                <form action="{{ route('assets.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="kode_barang" class="form-label">Kode aset</label>
                                <input id="kode_barang" name="kode_barang" class="form-control" value="{{ old('kode_barang') }}" readonly required>
                                <div class="form-text">
                                    Kode dibuat otomatis dari nama aset.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="nama_barang" class="form-label">Nama aset</label>
                                <input id="nama_barang" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="assetCategory" class="form-label">Kategori</label>
                                <select class="form-select" id="assetCategory" name="id_kategori" required>
                                    <option value="">Pilih kategori</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="assetLocation" class="form-label">Lokasi</label>
                                <select class="form-select" id="assetLocation" name="id_lokasi" required>
                                    <option value="">Pilih lokasi</option>
                                    @foreach ($lokasis as $lokasi)
                                        <option value="{{ $lokasi->id_lokasi }}">{{ $lokasi->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="assetDescription" class="form-label fw-semibold">Deskripsi / Spesifikasi <span class="text-danger">*</span></label>
                            <textarea class="form-control asset-desc-input" id="assetDescription" name="spesifikasi" rows="4" placeholder="Tuliskan deskripsi lengkap atau spesifikasi barang minimal 10 kata..." required></textarea>
                            <div class="form-text d-flex justify-content-between align-items-center mt-1">
                                <span class="text-muted small">Wajib mendeskripsikan barang minimal 10 kata.</span>
                                <span class="word-counter text-danger small fw-semibold">0 / 10 kata</span>
                            </div>
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
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/assets.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>