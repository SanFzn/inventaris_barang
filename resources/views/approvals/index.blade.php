<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Persetujuan | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-4 py-lg-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('dashboard') }}" class="text-decoration-none">&larr; Dashboard</a>
                <h1 class="h3 fw-bold mt-3 mb-1">Kelola Persetujuan</h1>
                <p class="text-muted mb-0">Tinjau permintaan peminjaman aset.</p>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <h2 class="h5">Tidak ada permintaan</h2>
                <p class="text-muted mb-0">Permintaan persetujuan baru akan tampil di halaman ini.</p>
            </div>
        </div>
    </main>
</body>
</html>
