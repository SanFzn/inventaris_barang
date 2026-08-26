<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Label QR | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .label-preview { max-width: 340px; border: 1px dashed #adb5bd; }
        #qr-code img { margin: auto; }
        @media print {
            body { background: #fff !important; }
            .no-print { display: none !important; }
            .label-preview { border: 0; box-shadow: none !important; }
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
                            <input id="asset-name" class="form-control" value="">
                        </div>
                        <div class="mb-3">
                            <label for="asset-code" class="form-label">Kode aset</label>
                            <input id="asset-code" class="form-control" value="">
                        </div>
                        <button type="button" class="btn btn-primary w-100" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i>Cetak label
                        </button>
                    </div>
                </div>
            </section>
            <section class="col-lg-7">
                <div class="label-preview bg-white shadow-sm rounded-3 mx-auto p-4 text-center">
                    <div class="text-uppercase text-muted small fw-bold">Inventaris Barang</div>
                    <h2 id="preview-name" class="h5 fw-bold mt-2 mb-1"></h2>
                    <div id="qr-code" class="my-3"></div>
                    <div id="preview-code" class="fw-bold"></div>
                </div>
            </section>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        const nameInput = document.getElementById('asset-name');
        const codeInput = document.getElementById('asset-code');
        const previewName = document.getElementById('preview-name');
        const previewCode = document.getElementById('preview-code');
        const qrCode = document.getElementById('qr-code');

        function renderLabel() {
            const name = nameInput.value.trim();
            const code = codeInput.value.trim();
            previewName.textContent = name;
            previewCode.textContent = code;
            qrCode.replaceChildren();
            if (code) {
                new QRCode(qrCode, { text: code, width: 160, height: 160 });
            }
        }

        nameInput.addEventListener('input', renderLabel);
        codeInput.addEventListener('input', renderLabel);
        renderLabel();
    </script>
</body>
</html>
