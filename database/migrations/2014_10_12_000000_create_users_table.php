<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            $table->increments('id_user');
            $table->string('nama_lengkap', 100);
            $table->string('email', 100);
            $table->string('password', 255);
            $table->enum('role', ['admin', 'karyawan']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
