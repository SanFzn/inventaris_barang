<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach (['Elektronik', 'Perabot Kantor', 'Alat Tulis', 'Peralatan Operasional'] as $namaKategori) {
            Kategori::firstOrCreate(['nama_kategori' => $namaKategori]);
        }

        foreach (['Ruang Admin', 'Ruang Meeting', 'Gudang', 'Ruang Operasional'] as $namaLokasi) {
            Lokasi::firstOrCreate(['nama_lokasi' => $namaLokasi]);
        }

        foreach (['Laptop', 'Meja', 'Kursi', 'Printer', 'Monitor', 'Komputer', 'Tablet', 'Kabel Terminal', 'Pulpen' ] as $namaBarang) {
            $kategori = Kategori::inRandomOrder()->first();
            $lokasi = Lokasi::inRandomOrder()->first();

            \App\Models\Barang::firstOrCreate(
                ['nama_barang' => $namaBarang],
                [
                    'kode_barang' => 'BRG-' . strtoupper(substr($namaBarang, 0, 4)) . '-' . rand(100, 999),
                    'id_kategori' => $kategori->id_kategori,
                    'id_lokasi' => $lokasi->id_lokasi,
                    'spesifikasi' => 'Spesifikasi untuk ' . $namaBarang,
                    'tgl_pembelian' => now(),
                    'status' => 'tersedia',
                    'file_qr' => null,
                ]
            );
        }

        User::updateOrCreate(
            ['email' => 'admin@inventaris.local'],
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
    }
}