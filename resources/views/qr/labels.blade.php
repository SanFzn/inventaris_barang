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
                    <div class="col-12 col-md-5">
                        <label for="asset-search" class="form-label small fw-semibold">Cari aset</label>
                        <input type="search" id="asset-search" name="q" class="form-control" value="{{ request('q') ?? request('kode_barang') }}" placeholder="Cari nama atau kode aset...">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="category-filter" class="form-label small fw-semibold">Filter Kategori</label>
                        <select id="category-filter" name="id_kategori" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id_kategori }}" {{ request('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search me-1"></i>Filter</button>
                        @if (request()->filled('q') || request()->filled('kode_barang') || request()->filled('id_kategori'))
                            <a href="{{ route('qr.labels') }}" class="btn btn-outline-danger">Reset</a>
                        @endif
                    </div>
                </form>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 no-print">
                    <div class="d-flex align-items-center gap-2">
                        <span id="selectedCount" class="badge bg-primary bg-opacity-10 text-primary border border-primary fs-6 px-3 py-2 rounded-pill">Belum ada label dipilih</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" id="selectAllBtn" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center">
                            <i class="bi bi-check-all me-1 fs-6"></i>Pilih Semua
                        </button>
                        <button type="button" id="clearSelectionBtn" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center" disabled>
                            <i class="bi bi-x-circle me-1"></i>Batalkan Pilihan
                        </button>
                        <button type="button" id="downloadSelectedBtn" class="btn btn-success btn-sm d-inline-flex align-items-center" disabled>
                            <i class="bi bi-download me-1"></i>Simpan QR (<span class="download-count">0</span>)
                        </button>
                        <button type="button" id="printSelectedBtn" class="btn btn-primary btn-sm d-inline-flex align-items-center" disabled>
                            <i class="bi bi-printer me-1"></i>Cetak Label (<span class="print-count">0</span>)
                        </button>
                    </div>
                </div>

                @if ($barangs->isEmpty())
                    <div class="alert alert-info">Belum ada aset untuk dicetak.</div>
                @else
                    @php
                        $barangsByCategory = $barangs->groupBy(function($b) {
                            return $b->kategori->nama_kategori ?? 'Tanpa Kategori';
                        });
                    @endphp

                    @foreach ($barangsByCategory as $kategoriNama => $items)
                        <div class="category-qr-section mb-5" data-category-group="{{ Str::slug($kategoriNama) }}">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom no-print">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-folder2-open text-primary fs-5"></i>
                                    <h2 class="h5 fw-bold mb-0 text-dark">{{ $kategoriNama }}</h2>
                                    <span class="badge bg-light text-dark border rounded-pill">{{ $items->count() }} label</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary select-category-btn" data-category="{{ Str::slug($kategoriNama) }}">
                                    <i class="bi bi-check2-square me-1"></i>Pilih Kategori Ini
                                </button>
                            </div>

                            <section class="labels-grid">
                                @foreach ($items as $barang)
                                    @php
                                        $isSelected = request('kode_barang') === $barang->kode_barang || request('q') === $barang->kode_barang || $barangs->count() === 1;
                                    @endphp
                                    <article id="label-{{ $barang->id_barang }}" class="label-preview bg-white shadow-sm rounded-3 p-4 text-center {{ $isSelected ? 'border border-2 border-primary' : '' }}" data-category="{{ Str::slug($kategoriNama) }}" style="cursor: pointer;">
                                        <div class="label-selector form-check mb-2">
                                            <input class="form-check-input asset-selector" type="checkbox" name="selected_assets[]" value="{{ $barang->id_barang }}" data-code="{{ $barang->kode_barang }}" id="asset-{{ $barang->id_barang }}" {{ $isSelected ? 'checked' : '' }}>
                                            <label class="form-check-label" for="asset-{{ $barang->id_barang }}">Pilih label</label>
                                        </div>
                                        <div class="text-uppercase text-muted small fw-bold">Inventaris Barang</div>
                                        <h2 class="h5 fw-bold mt-2 mb-1 text-truncate" title="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</h2>
                                        <div class="badge bg-light text-muted border mb-2">{{ $kategoriNama }}</div>
                                        <div class="my-3 d-flex justify-content-center align-items-center">
                                            <img src="{{ route('qr.generate', $barang->kode_barang) }}" alt="QR Code {{ $barang->kode_barang }}">
                                        </div>
                                        <div class="fw-bold font-monospace">{{ $barang->kode_barang }}</div>
                                    </article>
                                @endforeach
                            </section>
                        </div>
                    @endforeach
                @endif
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/qr-labels.js') }}"></script>
</body>
</html>
