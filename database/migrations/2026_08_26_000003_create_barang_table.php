<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangTable extends Migration
{
    public function up()
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            $table->increments('id_barang');
            $table->string('kode_barang', 50)->unique();
            $table->string('nama_barang', 100);
            $table->unsignedInteger('id_kategori');
            $table->unsignedInteger('id_lokasi');
            $table->text('spesifikasi')->nullable();
            $table->date('tgl_pembelian')->nullable();
            $table->enum('status', ['tersedia', 'dipinjam', 'rusak', 'maintenance']);
            $table->string('file_qr', 255)->nullable();
            $table->index('id_kategori');
            $table->index('id_lokasi');

            $table->foreign('id_kategori')
                ->references('id_kategori')->on('kategori')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_lokasi')
                ->references('id_lokasi')->on('lokasi')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('barang');
    }
}