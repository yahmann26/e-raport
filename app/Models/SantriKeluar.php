<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SantriKeluar extends Model
{
    protected $table = 'santri_keluar';
    protected $fillable = [
        'santri_id',
        'keluar_karena',
        'tanggal_keluar',
        'alasan_keluar'
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }
}
