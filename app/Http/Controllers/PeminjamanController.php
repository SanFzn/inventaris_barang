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

        return response()->json($query->paginate(9));
    }

    public function approvals()
    {
        $peminjamans = Peminjaman::with(['user', 'barang'])
            ->where('status_pinjam', 'menunggu')
            ->latest('id_pinjam')
            ->get();

        return view('approvals.index', compact('peminjamans'));
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

        $peminjaman = Peminjaman::create($data)->load(['user', 'barang']);
        if (!$request->expectsJson()) {
            return redirect()->route('dashboard')->with('success', 'Pengajuan peminjaman berhasil dikirim.');
        }

        return response()->json($peminjaman, 201);
    }

    public function show(Peminjaman $peminjaman)
    {
        if (request()->user()->role !== 'admin' && $peminjaman->id_user !== request()->user()->id_user) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        return response()->json($peminjaman->load(['user', 'barang']));
    }

    public function approve(Request $request, Peminjaman $peminjaman)
    {
        return DB::transaction(function () use ($request, $peminjaman) {
            $peminjaman->load('barang');
            if ($peminjaman->status_pinjam !== 'menunggu' || $peminjaman->barang->status !== 'tersedia') {
                if (!$request->expectsJson()) {
                    return redirect()->route('approvals.index')->with('error', 'Peminjaman atau barang tidak dapat disetujui.');
                }
                return response()->json(['message' => 'Peminjaman atau barang tidak dapat disetujui.'], 422);
            }

            $peminjaman->update(['status_pinjam' => 'dipinjam']);
            $peminjaman->barang->update(['status' => 'dipinjam']);
            $peminjaman = $peminjaman->fresh()->load(['user', 'barang']);

            return $request->expectsJson()
                ? response()->json($peminjaman)
                : redirect()->route('approvals.index')->with('success', 'Peminjaman berhasil disetujui.');
        });
    }

    public function reject(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status_pinjam !== 'menunggu') {
            if (!$request->expectsJson()) {
                return redirect()->route('approvals.index')->with('error', 'Hanya pengajuan yang menunggu yang dapat ditolak.');
            }
            return response()->json(['message' => 'Hanya pengajuan menunggu yang dapat ditolak.'], 422);
        }

        $peminjaman->update(['status_pinjam' => 'ditolak']);
        $peminjaman = $peminjaman->fresh()->load(['user', 'barang']);

        return $request->expectsJson()
            ? response()->json($peminjaman)
            : redirect()->route('approvals.index')->with('success', 'Pengajuan peminjaman ditolak.');
    }

    public function returnItem(Request $request, Peminjaman $peminjaman)
    {
        if ($request->user()->role !== 'admin' && $peminjaman->id_user !== $request->user()->id_user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }

            abort(403);
        }

        return DB::transaction(function () use ($request, $peminjaman) {
            if ($peminjaman->status_pinjam !== 'dipinjam') {
                if (!$request->expectsJson()) {
                    return redirect()->route('dashboard')->with('error', 'Peminjaman ini tidak sedang berjalan.');
                }
                return response()->json(['message' => 'Peminjaman ini tidak sedang berjalan.'], 422);
            }

            $peminjaman->update([
                'status_pinjam' => 'dikembalikan',
                'tgl_kembali' => now(),
            ]);
            $peminjaman->barang()->update(['status' => 'tersedia']);
            $peminjaman = $peminjaman->fresh()->load(['user', 'barang']);

            return $request->expectsJson()
                ? response()->json($peminjaman)
                : redirect()->route('dashboard')->with('success', 'Barang berhasil ditandai sudah dikembalikan.');
        });
    }
}
