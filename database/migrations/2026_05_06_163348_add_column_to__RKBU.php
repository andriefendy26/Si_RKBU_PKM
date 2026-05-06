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
        Schema::table('RKBU', function (Blueprint $table) {
            //
            $table->string('tersedia')->nullable();
            $table->string('kondisi')->nullable();
            $table->integer('kebutuhan')->nullable();
            $table->integer('kekurangan')->nullable();
            $table->string('satuan')->nullable();
            $table->unsignedInteger('perkiraan_biaya')->nullable();
            $table->string('analisa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('RKBU', function (Blueprint $table) {
            //
            $table->dropColumn('tersedia');
            $table->dropColumn('kondisi');
            $table->dropColumn('kebutuhan');
            $table->dropColumn('kekurangan');
            $table->dropColumn('satuan');
            $table->dropColumn('perkiraan_biaya');
            $table->dropColumn('analisa');
        });
    }
};
