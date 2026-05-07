<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RKBU extends Model
{
    protected $table = 'rkbu';

    protected $fillable = [
        'nama_barang',
        'jumlah',
        'tersedia',
        'kondisi',
        'kebutuhan',
        'kekurangan',
        'satuan',
        'perkiraan_biaya',
        'analisa',
        'total',
        // 'id_barang',
        'user_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
