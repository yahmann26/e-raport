<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TglRaport extends Model
{
    protected $table = 'tgl_raport';
    protected $fillable = [
        'tapel_id',
        'tempat_penerbitan',
        'tanggal_pembagian',
    ];

    protected $casts = [
        'tanggal_pembagian' => 'datetime',
    ];
    public function tapel()
    {
        return $this->belongsTo(Tapel::class);
    }
}
