<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peminjaman Saya | Inventaris Barang</title>
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

                <section class="welcome-panel rounded-3 p-4 p-lg-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2 class="fw-bold mb-2">Peminjaman Saya</h2>
                            <p class="mb-0 text-white-50">Kelola dan pantau semua aktivitas peminjaman Anda.</p>
                        </div>
                    </div>
                </section>

                {{-- Filter Tabs --}}
                <section class="mb-4">
                    <div class="card content-card">
                        <div class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-0">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
                                <div>
                                    <h2 class="h5 fw-bold mb-1">Daftar Peminjaman</h2>
                                    <p class="text-muted small mb-0">Semua riwayat dan peminjaman aktif Anda.</p>
                                </div>
                                <a class="btn btn-sm btn-primary text-nowrap" href="#borrowAssetModal" data-bs-toggle="modal">
                                    <i class="bi bi-plus-circle me-1"></i> Pinjam Barang Baru
                                </a>
                            </div>
                            <ul class="nav nav-tabs border-0" id="loanTabs">
                                <li class="nav-item">
                                    <a class="nav-link {{ !request('filter') || request('filter') === 'semua' ? 'active' : '' }}"
                                       href="{{ route('loans.my', ['filter' => 'semua']) }}">
                                        Semua
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('filter') === 'aktif' ? 'active' : '' }}"
                                       href="{{ route('loans.my', ['filter' => 'aktif']) }}">
                                        Aktif
                                        @if($totalAktif > 0)
                                            <span class="badge bg-primary ms-1">{{ $totalAktif }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('filter') === 'menunggu' ? 'active' : '' }}"
                                       href="{{ route('loans.my', ['filter' => 'menunggu']) }}">
                                        Menunggu
                                        @if($totalMenunggu > 0)
                                            <span class="badge bg-warning text-dark ms-1">{{ $totalMenunggu }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('filter') === 'terlambat' ? 'active' : '' }}"
                                       href="{{ route('loans.my', ['filter' => 'terlambat']) }}">
                                        Terlambat
                                        @if($totalTerlambat > 0)
                                            <span class="badge bg-danger ms-1">{{ $totalTerlambat }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('filter') === 'selesai' ? 'active' : '' }}"
                                       href="{{ route('loans.my', ['filter' => 'selesai']) }}">
                                        Selesai
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-0">
                            @forelse($peminjaman as $item)
                                @php
                                    $isLate = $item->status_pinjam === 'dipinjam'
                                        && $item->tgl_kembali
                                        && $item->tgl_kembali->isPast();
                                @endphp
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom p-3 p-md-4">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $item->barang->nama_barang ?? 'Barang tidak ditemukan' }}</div>
                                            <small class="text-muted">
                                                {{ $item->barang->kode_barang ?? '-' }}
                                                &middot; Dipinjam {{ $item->tgl_pinjam->format('d M Y') }}
                                                @if ($item->tgl_kembali && $item->status_pinjam !== 'dikembalikan')
                                                    &middot; Rencana kembali {{ $item->tgl_kembali->format('d M Y, H:i') }}
                                                @elseif ($item->status_pinjam === 'dikembalikan' && $item->tgl_kembali)
                                                    &middot; Dikembalikan {{ $item->tgl_kembali->format('d M Y') }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                        @if ($isLate)
                                            <span class="badge bg-danger">Terlambat</span>
                                        @elseif ($item->status_pinjam === 'dipinjam')
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif ($item->status_pinjam === 'menunggu')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif ($item->status_pinjam === 'dikembalikan')
                                            <span class="badge bg-secondary">Selesai</span>
                                        @elseif ($item->status_pinjam === 'ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-light text-dark">{{ ucfirst($item->status_pinjam) }}</span>
                                        @endif

                                        @if ($item->status_pinjam === 'dipinjam')
                                            <form method="POST" action="{{ route('loans.return', $item->id_pinjam) }}" id="returnForm-{{ $item->id_pinjam }}">
                                                @csrf
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-success text-nowrap btn-confirm-return"
                                                    data-form-id="returnForm-{{ $item->id_pinjam }}"
                                                    data-barang-name="{{ $item->barang->nama_barang ?? 'Barang' }}">
                                                    <i class="bi bi-box-arrow-in-down me-1"></i>Kembalikan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox display-5 d-block mb-3"></i>
                                    <p class="mb-0">Tidak ada data peminjaman ditemukan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>

            </div>
        </main>
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

    {{-- Modal Pinjam Barang --}}
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
                                @forelse ($barangTersedia as $barang)
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
                        <button type="submit" class="btn btn-primary" @disabled($barangTersedia->isEmpty())><i class="bi bi-send me-1"></i>Kirim Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>

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
    </script>
</body>
</html>

