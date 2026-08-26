<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pusat Notifikasi | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-4 py-lg-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('dashboard') }}" class="text-decoration-none">&larr; Dashboard</a>
                <h1 class="h3 fw-bold mt-3 mb-1">Pusat Notifikasi</h1>
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
    </main>
</body>
</html>
