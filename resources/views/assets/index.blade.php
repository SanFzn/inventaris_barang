<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Aset | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-4 py-lg-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('dashboard') }}" class="text-decoration-none">&larr; Dashboard</a>
                <h1 class="h3 fw-bold mt-3 mb-1">Kelola Aset</h1>
                <p class="text-muted mb-0">Daftar dan kondisi aset inventaris.</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssetModal">
                <i class="bi bi-plus-lg me-1"></i>Tambah Aset
            </button>
        </div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @if ($barangs->isEmpty())
                    <div class="text-center py-5">
                        <h2 class="h5">Belum ada aset</h2>
                        <p class="text-muted mb-0">Data aset akan tampil di halaman ini setelah ditambahkan.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr><th class="px-4">Kode</th><th>Nama Aset</th><th>Kategori</th><th>Lokasi</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($barangs as $barang)
                                    <tr>
                                        <td class="px-4 fw-semibold">{{ $barang->kode_barang }}</td>
                                        <td>{{ $barang->nama_barang }}</td>
                                        <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                        <td>{{ $barang->lokasi->nama_lokasi ?? '-' }}</td>
                                        <td><span class="badge bg-success">{{ ucfirst($barang->status) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </main>
    <div class="modal fade" id="addAssetModal" tabindex="-1" aria-labelledby="addAssetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="addAssetModalLabel"><i class="bi bi-box-seam me-2 text-primary"></i>Tambah Aset</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form method="POST" action="{{ route('assets.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6"><label for="kode_barang" class="form-label">Kode aset</label><input id="kode_barang" name="kode_barang" class="form-control" value="{{ old('kode_barang') }}" required></div>
                            <div class="col-md-6"><label for="nama_barang" class="form-label">Nama aset</label><input id="nama_barang" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}" required></div>
                            <div class="col-md-6"><label for="kategori" class="form-label">Kategori</label><input id="kategori" name="kategori" class="form-control" value="{{ old('kategori') }}" required></div>
                            <div class="col-md-6"><label for="lokasi" class="form-label">Lokasi</label><input id="lokasi" name="lokasi" class="form-control" value="{{ old('lokasi') }}" required></div>
                            <div class="col-md-6"><label for="tgl_pembelian" class="form-label">Tanggal pembelian</label><input id="tgl_pembelian" name="tgl_pembelian" type="date" class="form-control" value="{{ old('tgl_pembelian') }}"></div>
                            <div class="col-md-6"><label for="status" class="form-label">Status</label><select id="status" name="status" class="form-select" required><option value="tersedia">Tersedia</option><option value="dipinjam">Dipinjam</option><option value="rusak">Rusak</option><option value="maintenance">Maintenance</option></select></div>
                            <div class="col-12"><label for="spesifikasi" class="form-label">Spesifikasi</label><textarea id="spesifikasi" name="spesifikasi" class="form-control" rows="3">{{ old('spesifikasi') }}</textarea></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Aset</button></div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
