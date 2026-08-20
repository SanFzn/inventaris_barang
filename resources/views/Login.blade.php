<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="min-vh-100 d-flex align-items-center justify-content-center p-4">
        <div class="card border-0 shadow-sm" style="max-width: 460px; width: 100%;">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 fw-bold mb-2">Masuk ke akun Anda</h1>
                <p class="text-muted mb-4">Gunakan nama lengkap dan password untuk melanjutkan.</p>
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                <form method="POST" action="{{ url('/login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input id="nama_lengkap" type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Masuk</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>