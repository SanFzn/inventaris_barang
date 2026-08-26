<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        return response()->json(Lokasi::withCount('barang')->orderBy('nama_lokasi')->get());
    }

    public function store(Request $request)
    {
        return response()->json(Lokasi::create($request->validate([
            'nama_lokasi' => 'required|string|max:50',
        ])), 201);
    }

    public function show(Lokasi $lokasi)
    {
        return response()->json($lokasi->load('barang'));
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $lokasi->update($request->validate([
            'nama_lokasi' => 'required|string|max:50',
        ]));

        return response()->json($lokasi);
    }

    public function destroy(Lokasi $lokasi)
    {
        if ($lokasi->barang()->exists()) {
            return response()->json(['message' => 'Lokasi masih digunakan oleh barang.'], 422);
        }

        $lokasi->delete();
        return response()->json(['message' => 'Lokasi berhasil dihapus.']);
    }
}
