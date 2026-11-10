<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pindai QR | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/qr.css') }}">
</head>
<body class="container-fluid" data-generate-url="{{ route('qr.generate', ':code') }}">
    @include('partials.navbar')

    <div class="row">
        @include('partials.sidebar')

        <main class="col-md-9 col-lg-10 px-0">
            <div class="p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Pindai dan Lacak QR</h1>
                        <p class="text-muted mb-0">Gunakan kamera untuk menemukan detail aset.</p>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div id="qr-reader" class="mb-3"></div>
                        <div id="scan-status" class="alert alert-secondary d-none" role="status"></div>
                        <div id="scan-result" class="alert alert-success d-none" role="alert"></div>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <button type="button" class="btn btn-primary" id="start-camera">
                                <i class="bi bi-camera me-1"></i>Buka Kamera
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="scan-photo">
                                <i class="bi bi-image me-1"></i>Pilih Foto
                            </button>
                            <input type="file" id="photo-input" accept="image/*" capture="environment" class="d-none">
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal untuk hasil upload foto -->
    <div class="modal fade" id="photoResultModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hasil Pemindaian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="modal-qr" style="margin-bottom: 1rem;"></div>
                    <p class="mb-2"><strong>QR terbaca:</strong></p>
                    <p id="modal-qr-text" style="word-break: break-all; font-size: 0.875rem; color: #666;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/qr-scan.js') }}"></script>
</body>
</html>
