<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        return response()->json(Kategori::withCount('barang')->orderBy('nama_kategori')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        return response()->json(Kategori::create($data), 201);
    }

    public function show(Kategori $kategori)
    {
        return response()->json($kategori->load('barang'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $kategori->update($request->validate([
            'nama_kategori' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
        ]));

        return response()->json($kategori);
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->barang()->exists()) {
            return response()->json(['message' => 'Kategori masih digunakan oleh barang.'], 422);
        }

        $kategori->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }
}
