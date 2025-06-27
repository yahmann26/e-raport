<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BobotPenilaian extends Model
{
    protected $table = 'bobot_penilaian';

    protected $guarded = [];

    public function  pembelajaran() : BelongsTo
    {
        return $this->belongsTo(Pembelajaran::class);
    }
}
