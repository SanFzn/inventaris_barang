<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeminjamanTable extends Migration
{
    public function up()
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            $table->increments('id_pinjam');
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_barang');
            $table->dateTime('tgl_pinjam');
            $table->dateTime('tgl_kembali')->nullable();
            $table->enum('status_pinjam', ['menunggu', 'dipinjam', 'dikembalikan', 'ditolak']);
            $table->text('keterangan')->nullable();
            $table->index('id_user');
            $table->index('id_barang');

            $table->foreign('id_user')
                ->references('id_user')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_barang')
                ->references('id_barang')->on('barang')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('peminjaman');
    }
}