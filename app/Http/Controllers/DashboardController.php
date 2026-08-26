<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DashboardController extends Controller
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

        return view('Dashboard', [
            'totalBarang' => Barang::count(),
            'barangTersedia' => Barang::where('status', 'tersedia')->count(),
            'barangDipinjam' => Barang::where('status', 'dipinjam')->count(),
            'barangRusak' => Barang::whereIn('status', ['rusak', 'maintenance'])->count(),
            'peminjamanTerlambat' => Peminjaman::where('status_pinjam', 'dipinjam')
                ->whereNotNull('tgl_kembali')->where('tgl_kembali', '<', now())->count(),
            'barangTerbaru' => $query->take(5)->get(),
        ]);
    }
}
