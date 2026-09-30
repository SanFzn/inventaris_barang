<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Kelola Aset</h1>
        <p class="text-muted mb-0">Daftar dan kondisi aset inventaris.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-1 text-nowrap" data-bs-toggle="modal" data-bs-target="#addAssetModal">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Aset</span>
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('assets.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-4 col-lg-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="search" name="q" class="form-control bg-light border-start-0" placeholder="Cari nama atau kode aset..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-3">
                <select name="id_kategori" class="form-select form-select-sm bg-light" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id_kategori }}" {{ request('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2 col-lg-2">
                <select name="status" class="form-select form-select-sm bg-light" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                </select>
            </div>
            <div class="col-12 col-md-3 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                @if(request()->filled('q') || request()->filled('status') || request()->filled('id_kategori'))
                    <a href="{{ route('assets.index') }}" class="btn btn-light btn-sm" title="Reset filter"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>
