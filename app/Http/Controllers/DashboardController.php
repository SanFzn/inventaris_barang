<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Barang::with(['kategori', 'lokasi'])->latest('id_barang');
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($builder) use ($search) {
                $builder->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('nama_barang', 'like', "%{$search}%");
            });
        }

        // Stats for user dashboard cards (non-admin)
        $userPeminjamanAktif = $user->peminjaman()->where('status_pinjam', 'dipinjam')->count();
        $userPeminjamanMenunggu = $user->peminjaman()->where('status_pinjam', 'menunggu')->count();
        $userPeminjamanTerlambat = $user->peminjaman()
            ->where('status_pinjam', 'dipinjam')
            ->whereNotNull('tgl_kembali')
            ->where('tgl_kembali', '<', now())
            ->count();
        $userPeminjamanSelesai = $user->peminjaman()->where('status_pinjam', 'dikembalikan')->count();

        return view('dashboard', [
            'totalBarang'           => Barang::count(),
            'barangTersedia'        => Barang::where('status', 'tersedia')->count(),
            'barangDipinjam'        => Barang::where('status', 'dipinjam')->count(),
            'barangRusak'           => Barang::whereIn('status', ['rusak', 'maintenance'])->count(),
            'peminjamanTerlambat'   => Peminjaman::where('status_pinjam', 'dipinjam')
                ->whereNotNull('tgl_kembali')->where('tgl_kembali', '<', now())->count(),
            'barangTerbaru'         => $query->take(6)->get(),
            'barangUntukDipinjam'   => Barang::where('status', 'tersedia')->orderBy('nama_barang')->get(),
            'peminjamanAktif'       => $user->peminjaman()
                ->with('barang')
                ->where('status_pinjam', 'dipinjam')
                ->latest('id_pinjam')
                ->get(),
            'kategoris'             => Kategori::orderBy('nama_kategori')->get(),
            'lokasis'               => Lokasi::orderBy('nama_lokasi')->get(),
            // User-specific stats
            'userAktif'             => $userPeminjamanAktif,
            'userMenunggu'          => $userPeminjamanMenunggu,
            'userTerlambat'         => $userPeminjamanTerlambat,
            'userSelesai'           => $userPeminjamanSelesai,
        ]);
    }

    public function myLoans(Request $request)
    {
        $user = $request->user();
        $filter = $request->input('filter', 'semua');

        $query = $user->peminjaman()->with('barang')->latest('id_pinjam');

        if ($filter === 'aktif') {
            $query->where('status_pinjam', 'dipinjam')
                ->where(function ($q) {
                    $q->whereNull('tgl_kembali')->orWhere('tgl_kembali', '>=', now());
                });
        } elseif ($filter === 'menunggu') {
            $query->where('status_pinjam', 'menunggu');
        } elseif ($filter === 'terlambat') {
            $query->where('status_pinjam', 'dipinjam')
                ->whereNotNull('tgl_kembali')
                ->where('tgl_kembali', '<', now());
        } elseif ($filter === 'selesai') {
            $query->whereIn('status_pinjam', ['dikembalikan', 'ditolak']);
        }

        return view('loans.my', [
            'peminjaman'     => $query->get(),
            'totalAktif'     => $user->peminjaman()->where('status_pinjam', 'dipinjam')
                ->where(function ($q) { $q->whereNull('tgl_kembali')->orWhere('tgl_kembali', '>=', now()); })->count(),
            'totalMenunggu'  => $user->peminjaman()->where('status_pinjam', 'menunggu')->count(),
            'totalTerlambat' => $user->peminjaman()->where('status_pinjam', 'dipinjam')
                ->whereNotNull('tgl_kembali')->where('tgl_kembali', '<', now())->count(),
            'totalSelesai'   => $user->peminjaman()->where('status_pinjam', 'dikembalikan')->count(),
            'barangTersedia' => Barang::where('status', 'tersedia')->orderBy('nama_barang')->get(),
        ]);
    }
}
