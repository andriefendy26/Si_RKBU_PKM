<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->string('spesifikasi');
            $table->string('satuan');
            $table->string('harga_estimasi');
            $table->string('disetujui');

            $table->unsignedBigInteger("id_tahun_anggaran");
            $table->unsignedBigInteger("id_kategori");
            $table->foreign(columns: 'id_tahun_anggaran')->references('id')->on('tahun_anggaran');
            $table->foreign('id_kategori')->references('id')->on('kategori_barang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
