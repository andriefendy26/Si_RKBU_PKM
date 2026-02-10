<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class team_user extends Model
{
    //
    protected $table = "team_user";

    protected $fillable = [
        "team_id",
        "user_id",
    ];
}
