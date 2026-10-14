<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ Auth::user()->role === 'admin' ? 'Kelola Aset' : 'Daftar Aset' }} | Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/assets.css') }}">
</head>
<body class="container-fluid">
    @include('partials.navbar')

    <div class="row">
        @include('partials.sidebar')

        <main class="col-md-9 col-lg-10 px-0">
            <div class="p-4 p-lg-5">
                @include('assets.partials.header')
                @include('assets.partials.flash-messages')
                @include('assets.partials.card')
            </div>
        </main>
    </div>

    @if (Auth::user()->role === 'admin')
        @include('assets.partials.create-modal')
        @include('assets.partials.edit-modals')
        @include('assets.partials.delete-modal')
    @else
        {{-- Modal Pinjam Barang untuk User --}}
        <div class="modal fade" id="borrowAssetModal" tabindex="-1" aria-labelledby="borrowAssetModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h2 class="modal-title h5 fw-bold" id="borrowAssetModalLabel"><i class="bi bi-box-arrow-up-right me-2 text-primary"></i>Pinjam Barang</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <form method="POST" action="{{ route('loans.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Barang</label>
                                <input type="text" id="borrowAssetName" class="form-control bg-light" readonly>
                                <input type="hidden" name="id_barang" id="borrowAssetId">
                            </div>
                            <div class="mb-3">
                                <label for="borrowReturnDate" class="form-label">Rencana tanggal kembali</label>
                                <input id="borrowReturnDate" name="tgl_kembali" type="datetime-local" class="form-control" required>
                            </div>
                            <div>
                                <label for="borrowNote" class="form-label">Keterangan</label>
                                <textarea id="borrowNote" name="keterangan" class="form-control" rows="3" placeholder="Keperluan peminjaman" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Kirim Pengajuan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/assets.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    @if (Auth::user()->role !== 'admin')
    <script>
        // Populate borrow modal with card data
        const borrowModal = document.getElementById('borrowAssetModal');
        if (borrowModal) {
            borrowModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                document.getElementById('borrowAssetId').value = button.getAttribute('data-barang-id');
                document.getElementById('borrowAssetName').value =
                    button.getAttribute('data-barang-kode') + ' - ' + button.getAttribute('data-barang-name');
            });
        }
    </script>
    @endif
</body>
</html>
