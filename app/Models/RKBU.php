<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RKBU extends Model
{
    //
    protected $table = "RKBU";

    protected $fillable = [
        "jumlah",
        "total",

        "id_barang",
        "id_tahun_anggaran",
    ];
}