@if ($barangs->isEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <div class="rounded-circle bg-light text-muted d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; font-size: 1.75rem;">
                <i class="bi bi-boxes"></i>
            </div>
            <h2 class="h5 fw-bold mb-1">Belum ada aset</h2>
            <p class="text-muted mb-3">Data aset akan tampil di halaman ini setelah ditambahkan.</p>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAssetModal">
                <i class="bi bi-plus-lg me-1"></i>Tambah Aset Pertama
            </button>
        </div>
    </div>
@else
    @php
        $barangsByCategory = $barangs->groupBy(function($barang) {
            return $barang->kategori->nama_kategori ?? 'Tanpa Kategori';
        });
    @endphp

    @foreach ($barangsByCategory as $kategoriNama => $items)
        <div class="category-asset-section mb-5">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-folder2-open text-primary fs-5"></i>
                    <h2 class="h5 fw-bold mb-0 text-dark">{{ $kategoriNama }}</h2>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1">{{ $items->count() }} aset</span>
                </div>
                <a href="{{ route('qr.labels', ['q' => $kategoriNama]) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1">
                    <i class="bi bi-printer"></i>
                    <span>Cetak Label Kategori Ini</span>
                </a>
            </div>

            <div class="row g-3 g-lg-4">
                @foreach ($items as $barang)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100 asset-card border-0 shadow-sm overflow-hidden">
                            {{-- Card Photo Image Container --}}
                            <div class="asset-card-img-wrapper position-relative">
                                <img src="{{ $barang->image_url }}" alt="{{ $barang->nama_barang }}" class="asset-card-img" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80'">
                                
                                {{-- Badges Overlay --}}
                                <div class="position-absolute top-0 start-0 w-100 p-3 d-flex justify-content-between align-items-center">
                                    <span class="badge bg-dark bg-opacity-75 text-white font-monospace backdrop-blur px-2 py-1 shadow-sm">
                                        <i class="bi bi-upc me-1"></i>{{ $barang->kode_barang }}
                                    </span>
                                    @if ($barang->status === 'tersedia')
                                        <span class="badge bg-success shadow-sm px-2 py-1">
                                            <i class="bi bi-check-circle-fill me-1"></i>Tersedia
                                        </span>
                                    @elseif ($barang->status === 'dipinjam')
                                        <span class="badge bg-warning text-dark shadow-sm px-2 py-1">
                                            <i class="bi bi-clock-fill me-1"></i>Dipinjam
                                        </span>
                                    @else
                                        <span class="badge bg-secondary shadow-sm px-2 py-1">
                                            {{ ucfirst($barang->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="card-body p-4 d-flex flex-column">
                                {{-- Asset Title --}}
                                <h2 class="h5 fw-bold text-dark mb-2 text-truncate" title="{{ $barang->nama_barang }}">
                                    {{ $barang->nama_barang }}
                                </h2>

                                {{-- Metadata / Details --}}
                                <div class="asset-meta mb-3 flex-grow-1">
                                    <div class="d-flex align-items-center text-muted small mb-2">
                                        <i class="bi bi-tag me-2 text-primary opacity-75"></i>
                                        <span class="text-truncate">{{ $barang->kategori->nama_kategori ?? 'Tanpa Kategori' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center text-muted small mb-2">
                                        <i class="bi bi-geo-alt me-2 text-danger opacity-75"></i>
                                        <span class="text-truncate">{{ $barang->lokasi->nama_lokasi ?? 'Tanpa Lokasi' }}</span>
                                    </div>
                                    @if ($barang->tgl_pembelian)
                                        <div class="d-flex align-items-center text-muted small mb-2">
                                            <i class="bi bi-calendar3 me-2 text-success opacity-75"></i>
                                            <span>{{ $barang->tgl_pembelian->format('d M Y') }}</span>
                                        </div>
                                    @endif
                                    @if ($barang->spesifikasi)
                                        <div class="mt-2 pt-2 border-top border-light">
                                            <p class="text-muted small mb-0 text-truncate-2" title="{{ $barang->spesifikasi }}">
                                                {{ $barang->spesifikasi }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Card Actions --}}
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-light mt-auto">
                                    <a href="{{ route('qr.labels', ['kode_barang' => $barang->kode_barang]) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" title="Cetak label QR">
                                        <i class="bi bi-printer"></i>
                                        <span class="d-none d-sm-inline">Cetak Label</span>
                                    </a>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#editAsset{{ $barang->id_barang }}" title="Edit Aset">
                                            <i class="bi bi-pencil"></i>
                                            <span>Edit</span>
                                        </button>
                                        <form method="POST" action="{{ route('assets.destroy', $barang) }}" class="d-inline delete-asset-form m-0" data-asset-name="{{ $barang->nama_barang }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus Aset">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    @if ($barangs->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $barangs->links() }}
        </div>
    @endif
@endif