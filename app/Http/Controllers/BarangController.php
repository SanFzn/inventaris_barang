<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBarangRequest;
use App\Http\Requests\UpdateBarangRequest;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Storage;
use App\Services\QrCodeService;

class BarangController extends Controller
{
    public function __construct(private QrCodeService $qrCodeService)
    {
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $query = Barang::with(['kategori', 'lokasi'])->latest('id_barang');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($builder) use ($search) {
                $builder->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('nama_barang', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $barangs = $query->paginate(15);
        if ($request->expectsJson()) {
            return response()->json($barangs);
        }

        return view('assets.index', [
            'barangs' => $barangs,
            'kategoris' => Kategori::orderBy('nama_kategori')->get(),
            'lokasis' => Lokasi::orderBy('nama_lokasi')->get(),
        ]);
    }

    public function store(StoreBarangRequest $request)
    {
        $data = $request->validated();
        $data['kode_barang'] = $this->generateAssetCode($data['nama_barang']);
        $data['status'] = 'tersedia';

        if ($request->hasFile('file_qr')) {
            $data['file_qr'] = $request->file('file_qr')->store('qr', 'public');
        }

        $barang = Barang::create($data)->load(['kategori', 'lokasi']);
        $this->qrCodeService->generateFor($barang);

        if (!$request->expectsJson()) {
            if ($request->boolean('create_photo_label')) {
                return redirect()->route('qr.labels', [
                    'nama_barang' => $barang->nama_barang,
                    'kode_barang' => $barang->kode_barang,
                    'mode' => 'foto',
                ])->with('success', 'Aset berhasil ditambahkan.');
            }

            return redirect()->route('assets.index')->with('success', 'Aset berhasil ditambahkan.');
        }

        return response()->json($barang, 201);
    }

    private function generateAssetCode(string $assetName): string
    {
        $prefix = strtoupper(substr($assetName, 0, 4));

        do {
            $code = 'BRG-' . $prefix . '-' . random_int(100, 999);
        } while (Barang::where('kode_barang', $code)->exists());

        return $code;
    }

    public function show(Barang $barang)
    {
        return response()->json($barang->load(['kategori', 'lokasi', 'peminjaman.user']));
    }

    public function update(UpdateBarangRequest $request, Barang $barang)
    {
        $data = $request->validated();

        if ($request->hasFile('file_qr')) {
            if ($barang->file_qr) {
                Storage::disk('public')->delete($barang->file_qr);
            }
            $data['file_qr'] = $request->file('file_qr')->store('qr', 'public');
        }

        $barang->update($data);
        if (!$request->expectsJson()) {
            return redirect()->route('assets.index')->with('success', 'Aset berhasil diperbarui.');
        }

        return response()->json($barang->load(['kategori', 'lokasi']));
    }

    public function destroy(Barang $barang)
    {
        if ($barang->peminjaman()->whereIn('status_pinjam', ['menunggu', 'dipinjam'])->exists()) {
            return response()->json(['message' => 'Barang masih memiliki peminjaman aktif.'], 422);
        }

        if ($barang->file_qr) {
            Storage::disk('public')->delete($barang->file_qr);
        }
        $barang->delete();
        if (!request()->expectsJson()) {
            return redirect()->route('assets.index')->with('success', 'Aset berhasil dihapus.');
        }

        return response()->json(['message' => 'Barang berhasil dihapus.']);
    }

    public function generateQrImage($kodeBarang)
    {
        $kode = rawurldecode($kodeBarang);
        $barang = Barang::where('kode_barang', $kode)->first();

        if ($barang) {
            $this->qrCodeService->generateFor($barang);
            $filePath = public_path($barang->file_qr);
            if (file_exists($filePath)) {
                return response()->file($filePath, ['Content-Type' => 'image/png']);
            }
        }

        $filePath = $this->qrCodeService->pathFor($kode);
        if (!file_exists($filePath)) {
            return response()->json(['message' => 'Gagal membuat file QR.'], 500);
        }

        return response()->file($filePath, ['Content-Type' => 'image/png']);
    }
}
