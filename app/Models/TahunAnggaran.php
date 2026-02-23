<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAnggaran extends Model
{
    protected $table = 'tahun_anggaran';

    protected $fillable = ['name'];

    public function kategoriBarangs(): HasMany
    {
        return $this->hasMany(KategoriBarang::class, 'id_tahun_anggaran');
    }

    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class, 'id_tahun_anggaran');
    }

    public function rkbus(): HasMany
    {
        return $this->hasMany(RKBU::class, 'id_tahun_anggaran');
    }
}

