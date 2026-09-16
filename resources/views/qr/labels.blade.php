<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Label QR | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/qr.css') }}">
</head>
<body class="container-fluid">
    @include('partials.navbar')

    <div class="row">
        @include('partials.sidebar')

        <main class="col-md-9 col-lg-10 px-0">
            <div class="p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Cetak Label QR</h1>
                        <p class="text-muted mb-0">Buat label QR untuk aset inventaris.</p>
                    </div>
                </div>
                <form method="GET" action="{{ route('qr.labels') }}" class="row g-2 align-items-end mb-4 no-print">
                    <div class="col-md-5">
                        <label for="asset-search" class="form-label">Cari aset</label>
                        <input type="search" id="asset-search" name="q" class="form-control" value="{{ request('q') }}" placeholder="Cari nama atau kode aset">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search me-1"></i>Cari</button>
                        <a href="{{ route('qr.labels') }}" class="btn btn-outline-danger">Reset</a>
                    </div>
                </form>
                <div class="d-flex justify-content-end align-items-center gap-3 mb-4 no-print">
                    <span id="selectedCount" class="text-muted">Belum ada label dipilih</span>
                    <button type="button" id="clearSelectionBtn" class="btn btn-outline-danger" disabled>
                        <i class="bi bi-x-circle me-1"></i>Batalkan pilihan
                    </button>
                    <button type="button" id="downloadSelectedBtn" class="btn btn-success" disabled>
                        <i class="bi bi-download me-1"></i>Simpan QR sebagai foto
                    </button>
                    <button type="button" id="printSelectedBtn" class="btn btn-primary">
                        <i class="bi bi-printer me-1"></i>Cetak label
                    </button>
                </div>
                @if ($barangs->isEmpty())
                    <div class="alert alert-info">Belum ada aset untuk dicetak.</div>
                @else
                    <section class="labels-grid">
                        @foreach ($barangs as $barang)
                            <article class="label-preview bg-white shadow-sm rounded-3 p-4 text-center">
                                <div class="label-selector form-check mb-2">
                                    <input class="form-check-input asset-selector" type="radio" name="selected_asset" value="{{ $barang->id_barang }}" data-code="{{ $barang->kode_barang }}" id="asset-{{ $barang->id_barang }}">
                                    <label class="form-check-label" for="asset-{{ $barang->id_barang }}">Pilih label</label>
                                </div>
                                <div class="text-uppercase text-muted small fw-bold">Inventaris Barang</div>
                                <h2 class="h5 fw-bold mt-2 mb-1">{{ $barang->nama_barang }}</h2>
                                <div class="my-3 d-flex justify-content-center align-items-center">
                                    <img src="{{ route('qr.generate', $barang->kode_barang) }}" alt="QR Code {{ $barang->kode_barang }}">
                                </div>
                                <div class="fw-bold">{{ $barang->kode_barang }}</div>
                            </article>
                        @endforeach
                    </section>
                @endif
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/qr-labels.js') }}"></script>
</body>
</html>
