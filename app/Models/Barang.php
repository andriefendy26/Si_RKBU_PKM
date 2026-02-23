<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'spesifikasi',
        'satuan',
        'harga_estimasi',
        'is_active',
        'id_tahun_anggaran',
        'id_kategori',
    ];

    protected function casts(): array
    {
        return [
            'harga_estimasi' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function tahunAnggaran(): BelongsTo
    {
        return $this->belongsTo(TahunAnggaran::class, 'id_tahun_anggaran');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori');
    }

    public function rkbus(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RKBU::class, 'id_barang');
    }
}

