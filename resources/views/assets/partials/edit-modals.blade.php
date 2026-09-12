@foreach ($barangs as $barang)
    <div class="modal fade" id="editAsset{{ $barang->id_barang }}" tabindex="-1" aria-labelledby="editAssetLabel{{ $barang->id_barang }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
            <div class="modal-header"><h2 class="modal-title h5" id="editAssetLabel{{ $barang->id_barang }}"><i class="bi bi-pencil me-2 text-primary"></i>Edit Aset</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
            <form method="POST" action="{{ route('assets.update', $barang) }}">
                @csrf @method('PUT')
                <div class="modal-body"><div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Kode aset</label><input name="kode_barang" class="form-control" value="{{ $barang->kode_barang }}" readonly required></div>
                    <div class="col-md-6"><label class="form-label">Nama aset</label><input name="nama_barang" class="form-control" value="{{ $barang->nama_barang }}" required></div>
                    <div class="col-md-6"><label class="form-label">Kategori</label><select name="id_kategori" class="form-select" required>@foreach ($kategoris as $kategori)<option value="{{ $kategori->id_kategori }}" {{ $barang->id_kategori == $kategori->id_kategori ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label">Lokasi</label><select name="id_lokasi" class="form-select" required>@foreach ($lokasis as $lokasi)<option value="{{ $lokasi->id_lokasi }}" {{ $barang->id_lokasi == $lokasi->id_lokasi ? 'selected' : '' }}>{{ $lokasi->nama_lokasi }}</option>@endforeach</select></div>
                    <div class="col-md-12"><label class="form-label">Tanggal pembelian</label><input name="tgl_pembelian" type="date" class="form-control" value="{{ optional($barang->tgl_pembelian)->format('Y-m-d') }}"></div>
                    <div class="col-12"><label class="form-label">Spesifikasi</label><textarea name="spesifikasi" class="form-control" rows="3">{{ $barang->spesifikasi }}</textarea></div>
                </div></div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</button></div>
            </form>
        </div></div>
    </div>
@endforeach
