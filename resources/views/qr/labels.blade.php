<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Label QR | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .label-preview {
            max-width: 340px;
            border: 1px dashed #adb5bd;
            transition: all 0.2s ease;
        }

        .label-preview.photo-mode {
            max-width: 460px;
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid #dfe7f5;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        #qr-code img { margin: auto; }

        @media print {
            body { background: #fff !important; }
            .no-print { display: none !important; }
            .label-preview { border: 0; box-shadow: none !important; }
            .label-preview.photo-mode {
                max-width: 100%;
                border: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body class="bg-light">
    <main class="container py-4 py-lg-5">
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <div>
                <a href="{{ route('dashboard') }}" class="text-decoration-none">&larr; Dashboard</a>
                <h1 class="h3 fw-bold mt-3 mb-1">Cetak Label QR</h1>
                <p class="text-muted mb-0">Buat label QR untuk aset inventaris.</p>
            </div>
        </div>
        <div class="row g-4 align-items-start">
            <section class="col-lg-5 no-print">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-3">Detail aset</h2>
                        <div class="mb-3">
                            <label for="asset-name" class="form-label">Nama aset</label>
                            <input id="asset-name" class="form-control" value="{{ request('nama_barang', 'Nama Aset') }}">
                        </div>
                        <div class="mb-3">
                            <label for="asset-code" class="form-label">Kode aset</label>
                            <input id="asset-code" class="form-control" value="{{ request('kode_barang', 'BRG-001') }}">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" id="downloadPngBtn" class="btn btn-success w-100">
                                <i class="bi bi-download me-1"></i>Download file foto QR
                            </button>
                            <button type="button" id="printPdfBtn" class="btn btn-outline-primary w-100">
                                <i class="bi bi-file-earmark-pdf me-1"></i>Simpan / cetak PDF
                            </button>
                            <button type="button" class="btn btn-primary w-100 print-mode-button" data-mode="physical" onclick="setPrintMode('physical'); setTimeout(() => window.print(), 150);">
                                <i class="bi bi-printer me-1"></i>Cetak bentuk fisik
                            </button>
                            <button type="button" class="btn btn-outline-secondary w-100 print-mode-button" data-mode="foto" onclick="setPrintMode('foto'); document.getElementById('label-preview-area').scrollIntoView({ behavior: 'smooth', block: 'center' });">
                                <i class="bi bi-file-earmark-image me-1"></i>Lihat bentuk foto di web
                            </button>
                        </div>
                    </div>
                </div>
            </section>
            <section class="col-lg-7" id="label-preview-area">
                <div id="labelPreview" class="label-preview bg-white shadow-sm rounded-3 mx-auto p-4 text-center {{ request('mode') === 'foto' ? 'photo-mode' : '' }}">
                    <div class="text-uppercase text-muted small fw-bold">Inventaris Barang</div>
                    <h2 id="preview-name" class="h5 fw-bold mt-2 mb-1"></h2>
                    <div id="qr-code" class="my-3 d-flex justify-content-center align-items-center" style="min-height: 180px;"></div>
                    <div id="preview-code" class="fw-bold"></div>
                </div>
            </section>
        </div>
    </main>
    <script>
        const nameInput = document.getElementById('asset-name');
        const codeInput = document.getElementById('asset-code');
        const previewName = document.getElementById('preview-name');
        const previewCode = document.getElementById('preview-code');
        const qrCode = document.getElementById('qr-code');
        const labelPreview = document.getElementById('labelPreview');
        const downloadPngBtn = document.getElementById('downloadPngBtn');
        const printPdfBtn = document.getElementById('printPdfBtn');

        function getQrImageUrl(code, size) {
            return 'https://api.qrserver.com/v1/create-qr-code/?size=' + size + 'x' + size + '&data=' + encodeURIComponent(code);
        }

        function setPrintMode(mode) {
            const isPhotoMode = mode === 'foto';
            labelPreview.classList.toggle('photo-mode', isPhotoMode);
            const url = new URL(window.location.href);
            url.searchParams.set('mode', mode);
            window.history.replaceState({}, '', url);
        }

        function renderLabel() {
            const name = nameInput.value.trim() || 'Nama aset';
            const code = codeInput.value.trim() || 'BRG-001';
            previewName.textContent = name;
            previewCode.textContent = code;
            qrCode.innerHTML = '';

            const img = document.createElement('img');
            const qrSize = labelPreview.classList.contains('photo-mode') ? 200 : 160;
            const qrUrl = '/qr/generate/' + encodeURIComponent(code) + '?size=' + qrSize;
            img.src = qrUrl;
            img.alt = 'QR Code ' + code;
            img.style.maxWidth = '100%';
            img.style.height = 'auto';
            img.style.display = 'block';
            qrCode.appendChild(img);
            return qrUrl;
        }

        downloadPngBtn.addEventListener('click', () => {
            const code = codeInput.value.trim() || 'BRG-001';
            const fileName = (code || 'qr-code').replace(/[^a-z0-9-_]+/gi, '-').toLowerCase();
            const link = document.createElement('a');
            link.href = '/qr/generate/' + encodeURIComponent(code) + '?size=1000';
            link.download = fileName + '.png';
            document.body.appendChild(link);
            link.click();
            link.remove();
        });

        printPdfBtn.addEventListener('click', () => {
            setPrintMode('foto');
            setTimeout(() => window.print(), 150);
        });

        const initialMode = new URLSearchParams(window.location.search).get('mode') || 'physical';
        if (initialMode === 'foto') {
            labelPreview.classList.add('photo-mode');
        }

        nameInput.addEventListener('input', renderLabel);
        codeInput.addEventListener('input', renderLabel);
        renderLabel();
    </script>
</body>
</html>
