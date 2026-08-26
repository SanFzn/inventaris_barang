<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'barang'])->latest('id_pinjam');

        if ($request->user()->role !== 'admin') {
            $query->where('id_user', $request->user()->id_user);
        }
        if ($request->filled('status_pinjam')) {
            $query->where('status_pinjam', $request->input('status_pinjam'));
        }

        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'tgl_kembali' => 'nullable|date|after_or_equal:tgl_pinjam',
            'tgl_pinjam' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($data['id_barang']);
        if ($barang->status !== 'tersedia') {
            return response()->json(['message' => 'Barang tidak tersedia untuk dipinjam.'], 422);
        }

        $data['id_user'] = $request->user()->id_user;
        $data['tgl_pinjam'] = $data['tgl_pinjam'] ?? now();
        $data['status_pinjam'] = 'menunggu';

        return response()->json(Peminjaman::create($data)->load(['user', 'barang']), 201);
    }

    public function show(Peminjaman $peminjaman)
    {
        if (request()->user()->role !== 'admin' && $peminjaman->id_user !== request()->user()->id_user) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        return response()->json($peminjaman->load(['user', 'barang']));
    }

    public function approve(Peminjaman $peminjaman)
    {
        return DB::transaction(function () use ($peminjaman) {
            $peminjaman->load('barang');
            if ($peminjaman->status_pinjam !== 'menunggu' || $peminjaman->barang->status !== 'tersedia') {
                return response()->json(['message' => 'Peminjaman atau barang tidak dapat disetujui.'], 422);
            }

            $peminjaman->update(['status_pinjam' => 'dipinjam']);
            $peminjaman->barang->update(['status' => 'dipinjam']);
            return response()->json($peminjaman->fresh()->load(['user', 'barang']));
        });
    }

    public function reject(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_pinjam !== 'menunggu') {
            return response()->json(['message' => 'Hanya pengajuan menunggu yang dapat ditolak.'], 422);
        }

        $peminjaman->update(['status_pinjam' => 'ditolak']);
        return response()->json($peminjaman->fresh()->load(['user', 'barang']));
    }

    public function returnItem(Peminjaman $peminjaman)
    {
        return DB::transaction(function () use ($peminjaman) {
            if ($peminjaman->status_pinjam !== 'dipinjam') {
                return response()->json(['message' => 'Peminjaman ini tidak sedang berjalan.'], 422);
            }

            $peminjaman->update([
                'status_pinjam' => 'dikembalikan',
                'tgl_kembali' => now(),
            ]);
            $peminjaman->barang()->update(['status' => 'tersedia']);
            return response()->json($peminjaman->fresh()->load(['user', 'barang']));
        });
    }
}
