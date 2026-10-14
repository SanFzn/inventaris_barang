<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddUserRoleToUsersTable extends Migration
{
    public function up()
    {
        // Ubah ENUM role agar mendukung 'admin', 'karyawan', dan 'user'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'karyawan', 'user') NOT NULL");
    }

    public function down()
    {
        // Kembalikan ke ENUM asal (ubah user yang ada menjadi karyawan terlebih dulu)
        DB::statement("UPDATE users SET role = 'karyawan' WHERE role = 'user'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'karyawan') NOT NULL");
    }
}
