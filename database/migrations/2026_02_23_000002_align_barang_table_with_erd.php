<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Align barang table with ERD: harga_estimasi decimal, is_active.
     */
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('harga_estimasi');
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
