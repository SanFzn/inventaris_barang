<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if ($barangs->isEmpty())
            <div class="text-center py-5">
                <h2 class="h5">Belum ada aset</h2>
                <p class="text-muted mb-0">Data aset akan tampil di halaman ini setelah ditambahkan.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead><tr><th class="px-4">Kode</th><th>Nama Aset</th><th>Kategori</th><th>Lokasi</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach ($barangs as $barang)
                            <tr>
                                <td class="px-4 fw-semibold">{{ $barang->kode_barang }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $barang->lokasi->nama_lokasi ?? '-' }}</td>
                                <td><span class="badge bg-success">{{ ucfirst($barang->status) }}</span></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAsset{{ $barang->id_barang }}"><i class="bi bi-pencil"></i></button>
                                        <a href="{{ route('qr.labels', ['nama_barang' => $barang->nama_barang, 'kode_barang' => $barang->kode_barang]) }}" class="btn btn-sm btn-outline-secondary" title="Cetak label QR"><i class="bi bi-printer"></i></a>
                                        <form method="POST" action="{{ route('assets.destroy', $barang) }}" class="d-inline delete-asset-form" data-asset-name="{{ $barang->nama_barang }}">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
