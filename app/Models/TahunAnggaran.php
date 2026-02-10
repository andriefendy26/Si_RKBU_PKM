<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TahunAnggaran extends Model
{
    //
    protected $table = "tahun_anggaran";
    protected $fillable  = [
        'name'
    ];

    // public function users(): BelongsToMany 
    // {
    //     return $this->belongsToMany(User::class, "id_tahun_anggaran")->withTimestamps();
    // }
}
