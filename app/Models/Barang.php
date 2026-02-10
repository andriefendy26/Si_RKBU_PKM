<?php

namespace App\Models;

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
}
