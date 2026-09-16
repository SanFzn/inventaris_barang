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
                        <p class="text-muted mb-0">Tinjau permintaan peminjaman aset.</p>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-clipboard-check text-secondary display-5 d-block mb-3"></i>
                        <h2 class="h5">Tidak ada permintaan</h2>
                        <p class="text-muted mb-0">Permintaan persetujuan baru akan tampil di halaman ini.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
</body>
</html>
