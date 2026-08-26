<?php

namespace Database\Seeders;

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
