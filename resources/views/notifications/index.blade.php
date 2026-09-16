<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pusat Notifikasi | Inventaris Barang</title>
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
                        <h1 class="h3 fw-bold mb-1">Pusat Notifikasi</h1>
                        <p class="text-muted mb-0">Pantau pembaruan dan aktivitas inventaris.</p>
                    </div>
                </div>
                <section class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-bell-slash text-secondary" style="font-size: 3rem;"></i>
                        <h2 class="h5 mt-3">Belum ada notifikasi</h2>
                        <p class="text-muted mb-0">Notifikasi baru akan muncul saat ada aktivitas pada inventaris.</p>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
</body>
</html>
