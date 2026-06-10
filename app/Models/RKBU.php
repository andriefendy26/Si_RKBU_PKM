<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RKBU extends Model
{
    protected $table = 'rkbu';

    protected $fillable = [
        'nama_barang',
        // 'jumlah',
        'tersedia',
        'kondisi',
        'kebutuhan',
        'kekurangan',
        'satuan',
        'perkiraan_biaya',
        'analisa',
        'total',
        'file_path',
        'user_id',
        'id_tahun_anggaran',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'total' => 'decimal:2',
            'file_path' => 'string',
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


    public function approve()
    {
        $this->status = 'approved';
        $this->save();
    }

    public function reject()
    {
        $this->status = 'rejected';
        $this->save();
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
