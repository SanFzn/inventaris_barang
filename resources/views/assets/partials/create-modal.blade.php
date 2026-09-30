<div class="modal fade" id="addAssetModal" tabindex="-1" aria-labelledby="addAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h2 class="modal-title h5" id="addAssetModalLabel"><i class="bi bi-box-seam me-2 text-primary"></i>Tambah Aset</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
        <form method="POST" action="{{ route('assets.store') }}">
            @csrf
            <div class="modal-body"><div class="row g-3">
                <div class="col-md-6"><label for="kode_barang" class="form-label">Kode aset</label><input id="kode_barang" name="kode_barang" class="form-control" value="{{ old('kode_barang') }}" readonly required><div class="form-text">Kode dibuat otomatis dari nama aset.</div></div>
                <div class="col-md-6"><label for="nama_barang" class="form-label">Nama aset</label><input id="nama_barang" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}" required></div>
                <div class="col-md-6"><label for="id_kategori" class="form-label">Kategori</label><select id="id_kategori" name="id_kategori" class="form-select" required><option value="">Pilih kategori</option>@foreach ($kategoris as $kategori)<option value="{{ $kategori->id_kategori }}" {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>@endforeach</select></div>
                <div class="col-md-6"><label for="id_lokasi" class="form-label">Lokasi</label><select id="id_lokasi" name="id_lokasi" class="form-select" required><option value="">Pilih lokasi</option>@foreach ($lokasis as $lokasi)<option value="{{ $lokasi->id_lokasi }}" {{ old('id_lokasi') == $lokasi->id_lokasi ? 'selected' : '' }}>{{ $lokasi->nama_lokasi }}</option>@endforeach</select></div>
                <div class="col-12">
                    <label for="spesifikasi" class="form-label fw-semibold">Deskripsi / Spesifikasi <span class="text-danger">*</span></label>
                    <textarea id="spesifikasi" name="spesifikasi" class="form-control asset-desc-input @error('spesifikasi') is-invalid @enderror" rows="4" placeholder="Tuliskan deskripsi lengkap atau spesifikasi barang minimal 10 kata..." required>{{ old('spesifikasi') }}</textarea>
                    <div class="form-text d-flex justify-content-between align-items-center mt-1">
                        <span class="text-muted small">Wajib mendeskripsikan barang minimal 10 kata.</span>
                        <span class="word-counter text-danger small fw-semibold">0 / 10 kata</span>
                    </div>
                    @error('spesifikasi')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Aset</button></div>
        </form>
    </div></div>
</div>
