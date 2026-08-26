<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pindai QR | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #qr-reader { max-width: 480px; margin: 0 auto; }
        #qr-reader video { border-radius: .5rem; }
    </style>
</head>
<body class="bg-light">
    <main class="container py-4 py-lg-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('dashboard') }}" class="text-decoration-none">&larr; Dashboard</a>
                <h1 class="h3 fw-bold mt-3 mb-1">Pindai dan Lacak QR</h1>
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
    </main>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const reader = new Html5Qrcode('qr-reader');
        const status = document.getElementById('scan-status');
        const result = document.getElementById('scan-result');
        const cameraButton = document.getElementById('start-camera');
        const photoButton = document.getElementById('scan-photo');
        const photoInput = document.getElementById('photo-input');
        let cameraRunning = false;

        function showStatus(message, type = 'secondary') {
            status.className = `alert alert-${type}`;
            status.textContent = message;
        }

        function showResult(decodedText) {
            result.className = 'alert alert-success';
            result.innerHTML = `<strong>QR terbaca:</strong> <span></span>`;
            result.querySelector('span').textContent = decodedText;
            showStatus('Pemindaian selesai.', 'success');
        }

        function onScanSuccess(decodedText) {
            if (cameraRunning) {
                reader.stop().catch(() => {});
                cameraRunning = false;
                cameraButton.textContent = 'Buka Kamera';
            }
            showResult(decodedText);
        }

        cameraButton.addEventListener('click', async () => {
            if (cameraRunning) return;
            result.className = 'alert alert-success d-none';
            showStatus('Meminta izin kamera...', 'info');
            try {
                await reader.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    onScanSuccess,
                    () => {}
                );
                cameraRunning = true;
                cameraButton.textContent = 'Kamera Aktif';
                showStatus('Arahkan kamera ke kode QR.', 'info');
            } catch (error) {
                showStatus('Kamera tidak dapat dibuka. Periksa izin browser atau gunakan Pilih Foto.', 'danger');
            }
        });

        photoButton.addEventListener('click', () => photoInput.click());
        photoInput.addEventListener('change', async (event) => {
            const file = event.target.files[0];
            if (!file) return;
            showStatus('Membaca QR dari foto...', 'info');
            try {
                const decodedText = await reader.scanFile(file, true);
                showResult(decodedText);
            } catch (error) {
                showStatus('Kode QR tidak ditemukan pada foto.', 'warning');
            }
            photoInput.value = '';
        });
    </script>
</body>
</html>
