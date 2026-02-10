<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    //
    protected $table = "barang";

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        "spesifikasi",
        'satuan',
        'harga_estiimasi',
        'disetujui',

        'id_tahun_anggaran',
        'id_kategori'
    ];

    // protected static function booted(): void
    // {
    //     static::addGlobalScope('tahun_anggaran', function (Builder $query) {
    //         if (auth()->hasUser()) {
    //             $query->where('id_tahun_anggaran', auth()->user()->id_tahun_anggaran);
    //             // or with a `team` relationship defined:
    //             $query->whereBelongsTo(auth()->user()->tahunAnggaran);
    //         }
    //     });
    // }
}
