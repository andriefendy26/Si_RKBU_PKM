<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RKBU extends Model
{
    protected $table = 'RKBU';

    protected $fillable = [
        'jumlah',
        'total',
        'id_barang',
        'id_tahun_anggaran',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'total' => 'decimal:2',
        ];
    }

    public function tahunAnggaran(): BelongsTo
    {
        return $this->belongsTo(TahunAnggaran::class, 'id_tahun_anggaran');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
