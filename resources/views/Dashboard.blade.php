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
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                    @endif

                    @if (Auth::user()->role === 'admin' && ($pendingApprovalsCount ?? 0) > 0)
                        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm d-flex flex-column flex-md-row align-items-md-center justify-content-between p-3 p-md-4 mb-4 rounded-3" role="alert">
                            <div class="d-flex align-items-center gap-3 mb-3 mb-md-0">
                                <div class="rounded-circle bg-warning bg-opacity-25 text-dark d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px;">
                                    <i class="bi bi-bell-fill fs-5 text-warning"></i>
                                </div>
                                <div>
                                    <h3 class="h6 fw-bold mb-1 text-dark">Pemberitahuan Peminjaman Barang!</h3>
                                    <p class="mb-0 text-muted small">
                                        Ada <strong>{{ $pendingApprovalsCount }}</strong> pengajuan peminjaman barang baru dari user yang menunggu persetujuan Anda.
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('approvals.index') }}" class="btn btn-warning btn-sm text-dark fw-bold px-3 py-2 shadow-sm text-nowrap">
                                    <i class="bi bi-clipboard-check me-1"></i> Tinjau Pengajuan
                                </a>
                                <button type="button" class="btn-close ms-2 position-static" data-bs-dismiss="alert" aria-label="Tutup"></button>
                            </div>
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

                    @if (Auth::user()->role === 'admin')
                    {{-- ===== ADMIN STATS CARDS ===== --}}
                    <section class="row g-4 stats-grid mb-4" id="inventaris">
                        <div class="col-sm-6 col-xl">
                            <div class="card stat-card h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div><p class="text-muted small mb-2">Total Barang</p><h3 class="fw-bold mb-0">{{ $totalBarang }}</h3></div>
                                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-box-seam fs-5"></i></div>
                                </div>
                                @if ($totalBarang > 0)
                                    <small class="text-primary mt-3"><i class="bi bi-boxes"></i> Keseluruhan aset terdata</small>
                                @else
                                    <small class="text-muted mt-3"><i class="bi bi-dash"></i> Belum ada data</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl">
                            <div class="card stat-card h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div><p class="text-muted small mb-2">Tersedia</p><h3 class="fw-bold mb-0">{{ $barangTersedia }}</h3></div>
                                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-check-circle fs-5"></i></div>
                                </div>
                                @if ($barangTersedia > 0)
                                    <small class="text-success mt-3"><i class="bi bi-arrow-up"></i> Siap digunakan</small>
                                @else
                                    <small class="text-muted mt-3"><i class="bi bi-dash"></i> Tidak ada barang tersedia</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl" id="peminjaman">
                            <div class="card stat-card h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div><p class="text-muted small mb-2">Dipinjam</p><h3 class="fw-bold mb-0">{{ $barangDipinjam }}</h3></div>
                                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-arrow-left-right fs-5"></i></div>
                                </div>
                                @if ($barangDipinjam > 0)
                                    <small class="text-primary mt-3"><i class="bi bi-arrow-left-right"></i> Sedang dipinjam</small>
                                @else
                                    <small class="text-muted mt-3"><i class="bi bi-dash"></i> Tidak ada peminjaman aktif</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl">
                            <div class="card stat-card h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div><p class="text-muted small mb-2">Perlu Perbaikan</p><h3 class="fw-bold mb-0">{{ $barangRusak }}</h3></div>
                                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-tools fs-5"></i></div>
                                </div>
                                @if ($barangRusak > 0)
                                    <small class="text-warning mt-3"><i class="bi bi-exclamation-triangle"></i> Butuh perbaikan/servis</small>
                                @else
                                    <small class="text-success mt-3"><i class="bi bi-check-circle"></i> Semua kondisi terpantau</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl" id="peminjaman-terlambat">
                            <div class="card stat-card h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div><p class="text-muted small mb-2">Peminjaman Terlambat</p><h3 class="fw-bold mb-0">{{ $peminjamanTerlambat }}</h3></div>
                                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-exclamation-triangle-fill fs-5"></i></div>
                                </div>
                                @if ($peminjamanTerlambat > 0)
                                    <small class="text-danger mt-3"><i class="bi bi-arrow-up"></i> Perlu tindakan langsung</small>
                                @else
                                    <small class="text-success mt-3"><i class="bi bi-check-circle"></i> Tidak ada keterlambatan</small>
                                @endif
                            </div>
                        </div>
                    </section>
                    @else
                    {{-- ===== USER STATS CARDS ===== --}}
                    <section class="row g-4 stats-grid-4 mb-4" id="inventaris">
                        <div class="col-sm-6 col-xl-3">
                            <div class="card stat-card h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div><p class="text-muted small mb-2">Aset Aktif Saya</p><h3 class="fw-bold mb-0">{{ $userAktif }}</h3></div>
                                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-box-seam fs-5"></i></div>
                                </div>
                                @if ($userAktif > 0)
                                    <small class="text-success mt-3"><i class="bi bi-arrow-up"></i> Sedang digunakan</small>
                                @else
                                    <small class="text-muted mt-3"><i class="bi bi-dash"></i> Belum ada aset dipinjam</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card stat-card h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div><p class="text-muted small mb-2">Menunggu Admin</p><h3 class="fw-bold mb-0">{{ $userMenunggu }}</h3></div>
                                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-hourglass-split fs-5"></i></div>
                                </div>
                                @if ($userMenunggu > 0)
                                    <small class="text-warning mt-3"><i class="bi bi-clock"></i> Menunggu persetujuan</small>
                                @else
                                    <small class="text-muted mt-3"><i class="bi bi-dash"></i> Tidak ada pengajuan</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card stat-card h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div><p class="text-muted small mb-2">Status Terlambat</p><h3 class="fw-bold mb-0">{{ $userTerlambat }}</h3></div>
                                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-exclamation-triangle-fill fs-5"></i></div>
                                </div>
                                @if ($userTerlambat > 0)
                                    <small class="text-danger mt-3"><i class="bi bi-arrow-up"></i> Perlu segera dikembalikan</small>
                                @else
                                    <small class="text-success mt-3"><i class="bi bi-check-circle"></i> Tidak ada keterlambatan</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card stat-card h-100 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div><p class="text-muted small mb-2">Riwayat Selesai</p><h3 class="fw-bold mb-0">{{ $userSelesai }}</h3></div>
                                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"><i class="bi bi-check-circle fs-5"></i></div>
                                </div>
                                @if ($userSelesai > 0)
                                    <small class="text-primary mt-3"><i class="bi bi-check2"></i> Total dikembalikan</small>
                                @else
                                    <small class="text-muted mt-3"><i class="bi bi-dash"></i> Belum ada riwayat</small>
                                @endif
                            </div>
                        </div>
                    </section>
                    @endif

                    @if (Auth::user()->role !== 'admin')
                        <section class="mb-4" id="peminjaman-saya">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h2 class="h5 fw-bold mb-1">Peminjaman Saya</h2>
                                    <p class="text-muted small mb-0">Tandai barang setelah diserahkan kembali.</p>
                                </div>
                            </div>
                            <div class="card content-card">
                                <div class="card-body p-0">
                                    @forelse ($peminjamanAktif as $peminjaman)
                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom p-3 p-md-4">
                                            <div>
                                                <div class="fw-semibold">{{ $peminjaman->barang->nama_barang ?? 'Barang tidak ditemukan' }}</div>
                                                <small class="text-muted">
                                                    {{ $peminjaman->barang->kode_barang ?? '-' }}
                                                    @if ($peminjaman->tgl_kembali)
                                                        · Rencana kembali {{ $peminjaman->tgl_kembali->format('d M Y, H:i') }}
                                                    @endif
                                                </small>
                                            </div>
                                            <form method="POST" action="{{ route('loans.return', $peminjaman->id_pinjam) }}" id="returnForm-{{ $peminjaman->id_pinjam }}">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-outline-success text-nowrap btn-confirm-return"
                                                    data-form-id="returnForm-{{ $peminjaman->id_pinjam }}"
                                                    data-barang-name="{{ $peminjaman->barang->nama_barang ?? 'Barang' }}">
                                                    <i class="bi bi-box-arrow-in-down me-1"></i>Tandai Dikembalikan
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-muted">Tidak ada barang yang sedang Anda pinjam.</div>
                                    @endforelse
                                </div>
                            </div>
                        </section>
                    @endif

                    <section class="row g-4" id="laporan">
                        <div class="{{ Auth::user()->role === 'admin' ? 'col-lg-8' : 'col-12' }}">
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

                                    <div class="row g-3 g-lg-4">
                                        @forelse($barangTerbaru as $barang)
                                            <div class="{{ Auth::user()->role === 'admin' ? 'col-12 col-sm-6' : 'col-12 col-sm-6 col-lg-4' }}">
                                                <div class="card h-100 asset-card border-0 shadow-sm overflow-hidden" style="background: #ffffff; border: 1px solid #edf2f7 !important;">
                                                    {{-- Foto Aset & Status Overlay --}}
                                                    <div class="asset-card-img-wrapper position-relative" style="height: 160px;">
                                                        <img src="{{ $barang->image_url }}" alt="{{ $barang->nama_barang }}" class="asset-card-img" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80'">
                                                        
                                                        <div class="position-absolute top-0 start-0 w-100 p-2.5 d-flex justify-content-between align-items-center">
                                                            <span class="badge bg-dark bg-opacity-75 text-white font-monospace backdrop-blur px-2 py-1 shadow-sm">
                                                                <i class="bi bi-upc me-1"></i>{{ $barang->kode_barang }}
                                                            </span>
                                                            @if ($barang->status === 'tersedia')
                                                                <span class="badge bg-success shadow-sm px-2 py-1">
                                                                    <i class="bi bi-check-circle-fill me-1"></i>Tersedia
                                                                </span>
                                                            @elseif ($barang->status === 'dipinjam')
                                                                <span class="badge bg-warning text-dark shadow-sm px-2 py-1">
                                                                    <i class="bi bi-clock-fill me-1"></i>Dipinjam
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary shadow-sm px-2 py-1">
                                                                    {{ ucfirst($barang->status) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Body Info --}}
                                                    <div class="card-body p-3 d-flex flex-column">
                                                        <h3 class="h6 fw-bold text-dark mb-1 text-truncate" title="{{ $barang->nama_barang }}">
                                                            {{ $barang->nama_barang }}
                                                        </h3>
                                                        
                                                        <div class="d-flex align-items-center gap-2 text-muted small mb-2 flex-wrap" style="font-size: 0.8rem;">
                                                            <span class="d-inline-flex align-items-center gap-1 text-truncate">
                                                                <i class="bi bi-tag text-primary"></i> {{ $barang->kategori->nama_kategori ?? 'Umum' }}
                                                            </span>
                                                            <span>•</span>
                                                            <span class="d-inline-flex align-items-center gap-1 text-truncate">
                                                                <i class="bi bi-geo-alt text-danger"></i> {{ $barang->lokasi->nama_lokasi ?? 'Kantor' }}
                                                            </span>
                                                        </div>

                                                        @if ($barang->spesifikasi)
                                                            <p class="text-muted small mb-3 text-truncate-2" style="font-size: 0.8rem; min-height: 2.4em;" title="{{ $barang->spesifikasi }}">
                                                                {{ $barang->spesifikasi }}
                                                            </p>
                                                        @else
                                                            <p class="text-muted small mb-3 fst-italic" style="font-size: 0.8rem; min-height: 2.4em;">
                                                                Tidak ada spesifikasi khusus.
                                                            </p>
                                                        @endif

                                                        {{-- Actions --}}
                                                        <div class="d-flex align-items-center gap-2 mt-auto pt-2 border-top border-light">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary px-2.5 btn-detail-asset"
                                                                data-barang-name="{{ $barang->nama_barang }}"
                                                                data-barang-kode="{{ $barang->kode_barang }}"
                                                                data-barang-kategori="{{ $barang->kategori->nama_kategori ?? '-' }}"
                                                                data-barang-lokasi="{{ $barang->lokasi->nama_lokasi ?? '-' }}"
                                                                data-barang-status="{{ $barang->status }}"
                                                                data-barang-tgl="{{ $barang->tgl_pembelian ? $barang->tgl_pembelian->format('d M Y') : '-' }}"
                                                                data-barang-harga="{{ $barang->harga ? 'Rp ' . number_format($barang->harga, 0, ',', '.') : '-' }}"
                                                                data-barang-spec="{{ $barang->spesifikasi }}"
                                                                data-barang-image="{{ $barang->image_url }}"
                                                                data-barang-id="{{ $barang->id_barang }}"
                                                                title="Lihat Detail">
                                                                <i class="bi bi-eye me-1"></i>Detail
                                                            </button>

                                                            @if (Auth::user()->role === 'admin')
                                                                <a href="{{ route('qr.labels', ['kode_barang' => $barang->kode_barang]) }}" class="btn btn-sm btn-outline-primary flex-grow-1 text-nowrap">
                                                                    <i class="bi bi-printer me-1"></i>Label QR
                                                                </a>
                                                            @else
                                                                @if ($barang->status === 'tersedia')
                                                                    <button type="button" class="btn btn-sm btn-primary flex-grow-1 text-nowrap btn-borrow-asset"
                                                                        data-barang-id="{{ $barang->id_barang }}">
                                                                        <i class="bi bi-box-arrow-up-right me-1"></i>Pinjam
                                                                    </button>
                                                                @else
                                                                    <button type="button" class="btn btn-sm btn-light text-muted flex-grow-1 text-nowrap" disabled>
                                                                        <i class="bi bi-lock me-1"></i>{{ ucfirst($barang->status) }}
                                                                    </button>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-5 text-muted">
                                                <i class="bi bi-inbox display-5 d-block mb-3"></i>
                                                <p class="mb-0">Belum ada aset yang ditambahkan.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (Auth::user()->role === 'admin')
                            <div class="col-lg-4">
                                <div class="card content-card h-100">
                                    <div class="card-body p-4">
                                        <h2 class="h5 fw-bold mb-1">Aksi Cepat</h2>
                                        <p class="small text-muted mb-4">Akses cepat ke fitur utama</p>
                                        <div class="quick-actions">
                                            <a class="quick-action" href="#newAssetModal" data-bs-toggle="modal">
                                                <i class="bi bi-plus-circle"></i>
                                                <span class="small fw-semibold text-center">Tambah Aset</span>  
                                            </a>
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
                        @endif
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
                            <textarea id="borrowNote" name="keterangan" class="form-control" rows="3" placeholder="Keperluan peminjaman" required></textarea>
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

    {{-- Modal Detail Barang --}}
    <div class="modal fade" id="detailAssetModal" tabindex="-1" aria-labelledby="detailAssetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h2 class="modal-title h5 fw-bold" id="detailAssetModalLabel"><i class="bi bi-info-circle me-2 text-primary"></i>Detail Barang</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-5 text-center">
                            <img id="detailAssetImage" src="" alt="Foto Barang" class="img-fluid rounded-3 border shadow-sm bg-light" style="max-height: 240px; width: 100%; object-fit: cover;">
                        </div>
                        <div class="col-md-7">
                            <div class="mb-2">
                                <span id="detailAssetStatus" class="badge bg-success px-2 py-1"></span>
                            </div>
                            <h3 id="detailAssetName" class="h4 fw-bold mb-1"></h3>
                            <p id="detailAssetCode" class="text-muted mb-3 font-monospace small"></p>

                            <div class="small text-muted">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-tag text-primary"></i>
                                    <span id="detailAssetCategory"></span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-geo-alt text-danger"></i>
                                    <span id="detailAssetLocation"></span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-calendar3 text-success"></i>
                                    <span id="detailAssetTanggal"></span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-currency-dollar text-warning"></i>
                                    <span id="detailAssetPrice"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 pt-2 border-top">
                            <label class="form-label fw-semibold">Spesifikasi</label>
                            <p id="detailAssetSpec" class="mb-0 text-secondary"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    @if (Auth::user()->role !== 'admin')
                        <button type="button" class="btn btn-primary d-none" id="detailModalBorrowBtn">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Pinjam Barang Ini
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Pengembalian --}}
    <div class="modal fade" id="confirmReturnModal" tabindex="-1" aria-labelledby="confirmReturnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h2 class="modal-title h5 fw-bold" id="confirmReturnModalLabel"><i class="bi bi-box-arrow-in-down me-2 text-success"></i>Konfirmasi</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Tandai barang <strong id="confirmReturnItemName"></strong> sudah dikembalikan?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="confirmReturnBtn"><i class="bi bi-check-lg me-1"></i>Ya, Kembalikan</button>
                </div>
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
                            <div class="col-md-6">
                                <label for="tgl_pembelian" class="form-label">Tanggal pembelian</label>
                                <input id="tgl_pembelian" name="tgl_pembelian" type="date" class="form-control" value="{{ old('tgl_pembelian') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="harga" class="form-label">Harga</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input id="harga" type="text" class="form-control harga-input" value="{{ old('harga') ? number_format(old('harga'), 0, ',', '.') : '' }}" placeholder="Masukkan harga" required>
                                    <input type="hidden" name="harga" class="harga-raw-input" value="{{ old('harga', 0) }}">
                                </div>
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

    <script>
        // Confirm return modal handler
        let returnFormId = null;
        document.querySelectorAll('.btn-confirm-return').forEach(function(btn) {
            btn.addEventListener('click', function() {
                returnFormId = this.getAttribute('data-form-id');
                document.getElementById('confirmReturnItemName').textContent = this.getAttribute('data-barang-name');
                var modal = new bootstrap.Modal(document.getElementById('confirmReturnModal'));
                modal.show();
            });
        });
        document.getElementById('confirmReturnBtn').addEventListener('click', function() {
            if (returnFormId) {
                document.getElementById(returnFormId).submit();
            }
        });

        // Detail asset handler
        let currentDetailBarangId = null;
        const detailModalEl = document.getElementById('detailAssetModal');
        let detailModalInstance = null;
        if (detailModalEl) {
            detailModalInstance = new bootstrap.Modal(detailModalEl);
        }

        document.querySelectorAll('.btn-detail-asset').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const status = this.getAttribute('data-barang-status');
                const statusBadge = document.getElementById('detailAssetStatus');
                currentDetailBarangId = this.getAttribute('data-barang-id');

                if (statusBadge) {
                    statusBadge.textContent = status === 'tersedia' ? 'Tersedia' : status === 'dipinjam' ? 'Dipinjam' : (status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Tidak Tersedia');
                    statusBadge.className = status === 'tersedia'
                        ? 'badge bg-success px-2 py-1'
                        : status === 'dipinjam'
                            ? 'badge bg-warning text-dark px-2 py-1'
                            : 'badge bg-secondary px-2 py-1';
                }
                document.getElementById('detailAssetName').textContent = this.getAttribute('data-barang-name');
                document.getElementById('detailAssetCode').textContent = this.getAttribute('data-barang-kode');
                document.getElementById('detailAssetCategory').textContent = this.getAttribute('data-barang-kategori');
                document.getElementById('detailAssetLocation').textContent = this.getAttribute('data-barang-lokasi');
                document.getElementById('detailAssetTanggal').textContent = this.getAttribute('data-barang-tgl');
                document.getElementById('detailAssetPrice').textContent = this.getAttribute('data-barang-harga');
                document.getElementById('detailAssetSpec').textContent = this.getAttribute('data-barang-spec') || 'Tidak ada spesifikasi khusus.';
                document.getElementById('detailAssetImage').src = this.getAttribute('data-barang-image');

                const borrowBtn = document.getElementById('detailModalBorrowBtn');
                if (borrowBtn) {
                    if (status === 'tersedia') {
                        borrowBtn.classList.remove('d-none');
                    } else {
                        borrowBtn.classList.add('d-none');
                    }
                }

                if (detailModalInstance) {
                    detailModalInstance.show();
                }
            });
        });

        // Detail modal borrow button
        const detailModalBorrowBtn = document.getElementById('detailModalBorrowBtn');
        if (detailModalBorrowBtn) {
            detailModalBorrowBtn.addEventListener('click', function() {
                if (detailModalInstance) {
                    detailModalInstance.hide();
                }
                const select = document.getElementById('borrowAsset');
                if (select && currentDetailBarangId) {
                    select.value = currentDetailBarangId;
                }
                const borrowModal = new bootstrap.Modal(document.getElementById('borrowAssetModal'));
                borrowModal.show();
            });
        }

        // Quick borrow asset handler
        document.querySelectorAll('.btn-borrow-asset').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-barang-id');
                const select = document.getElementById('borrowAsset');
                if (select) {
                    select.value = id;
                }
                const borrowModal = new bootstrap.Modal(document.getElementById('borrowAssetModal'));
                borrowModal.show();
            });
        });
    </script>
</body>
</html>