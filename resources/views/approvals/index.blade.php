<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Persetujuan | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="container-fluid">
    @include('partials.navbar')

    <div class="row">
        @include('partials.sidebar')

        <main class="col-md-9 col-lg-10 px-0">
            <div class="p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Kelola Persetujuan</h1>
                        <p class="text-muted mb-0">{{ $peminjamans->count() }} pengajuan menunggu persetujuan.</p>
                    </div>
                </div>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if ($peminjamans->isEmpty())
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-clipboard-check text-secondary display-5 d-block mb-3"></i>
                            <h2 class="h5">Tidak ada permintaan</h2>
                            <p class="text-muted mb-0">Pengajuan peminjaman yang menunggu akan tampil di sini.</p>
                        </div>
                    </div>
                @else
                    <div class="card border-0 shadow-sm">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Peminjam</th>
                                        <th>Barang</th>
                                        <th>Waktu Pengajuan</th>
                                        <th>Rencana Kembali</th>
                                        <th>Keterangan</th>
                                        <th class="text-end">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($peminjamans as $peminjaman)
                                        <tr>
                                            <td>{{ $peminjaman->user->name ?? 'User tidak ditemukan' }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $peminjaman->barang->nama_barang ?? 'Barang tidak ditemukan' }}</div>
                                                <small class="text-muted">{{ $peminjaman->barang->kode_barang ?? '-' }}</small>
                                            </td>
                                            <td>{{ optional($peminjaman->tgl_pinjam)->format('d M Y, H:i') }}</td>
                                            <td>{{ optional($peminjaman->tgl_kembali)->format('d M Y, H:i') ?? '-' }}</td>
                                            <td>{{ $peminjaman->keterangan ?: '-' }}</td>
                                            <td>
                                                <div class="d-flex justify-content-end gap-2">
                                                    <form method="POST" action="{{ route('approvals.approve', $peminjaman->id_pinjam) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bi bi-check-lg me-1"></i>Setujui
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('approvals.reject', $peminjaman->id_pinjam) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-x-lg me-1"></i>Tolak
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
</body>
</html>
