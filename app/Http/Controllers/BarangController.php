<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index(Request $request)
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:barang,kode_barang',
            'nama_barang' => 'required|string|max:100',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'spesifikasi' => 'nullable|string',
            'tgl_pembelian' => 'nullable|date',
            'file_qr' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);
        $data['status'] = 'tersedia';

        if ($request->hasFile('file_qr')) {
            $data['file_qr'] = $request->file('file_qr')->store('qr', 'public');
        }

        $barang = Barang::create($data)->load(['kategori', 'lokasi']);
        if (!$request->expectsJson()) {
            return redirect()->route('assets.index')->with('success', 'Aset berhasil ditambahkan.');
        }

        return response()->json($barang, 201);
    }

    public function show(Barang $barang)
    {
        return response()->json($barang->load(['kategori', 'lokasi', 'peminjaman.user']));
    }

    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:barang,kode_barang,' . $barang->id_barang . ',id_barang',
            'nama_barang' => 'required|string|max:100',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'spesifikasi' => 'nullable|string',
            'tgl_pembelian' => 'nullable|date',
            'file_qr' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);

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
}
