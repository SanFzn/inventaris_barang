<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="bg-light">
    @if(session('success'))
        <div class="logout-toast alert alert-success d-flex align-items-center gap-2 shadow" role="status">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif
    <main class="min-vh-100 d-flex align-items-center justify-content-center p-4">
        <div class="card border-0 shadow-sm" style="max-width: 460px; width: 100%;">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 fw-bold mb-2">Masuk ke akun Anda</h1>
                <p class="text-muted mb-4">Gunakan username dan password untuk melanjutkan.</p>
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                <form method="POST" action="{{ url('/login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input id="username" type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input id="password" type="password" name="password" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword" aria-label="Tampilkan password" aria-pressed="false">
                                <i class="bi bi-eye" id="toggleIcon" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Masuk</button>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const togglePasswordButton = document.getElementById('togglePassword');
            const toggleIcon = document.getElementById('toggleIcon');

            if (!passwordInput || !togglePasswordButton) return;

            togglePasswordButton.addEventListener('click', () => {
                const isPasswordVisible = passwordInput.type === 'text';
                passwordInput.type = isPasswordVisible ? 'password' : 'text';
                togglePasswordButton.setAttribute('aria-pressed', String(!isPasswordVisible));
                togglePasswordButton.setAttribute(
                    'aria-label',
                    isPasswordVisible ? 'Tampilkan password' : 'Sembunyikan password'
                );
                if (toggleIcon) {
                    toggleIcon.className = isPasswordVisible ? 'bi bi-eye' : 'bi bi-eye-slash';
                }
            });
        });
    </script>
</body>
</html>