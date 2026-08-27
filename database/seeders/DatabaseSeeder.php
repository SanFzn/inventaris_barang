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

        User::updateOrCreate(
            ['email' => 'admin@inventaris.local'],
            [
                'nama_lengkap' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
    }
}
