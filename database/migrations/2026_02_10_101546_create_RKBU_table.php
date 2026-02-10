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
        Schema::create('RKBU', function (Blueprint $table) {
            $table->id();
            $table->string('jumlah');
            $table->string('total');

            $table->unsignedBigInteger("id_tahun_anggaran");
            $table->unsignedBigInteger("id_barang");
            $table->foreign('id_barang')->references('id')->on('barang');
            $table->foreign('id_tahun_anggaran')->references('id')->on('tahun_anggaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('RKBU');
    }
};
