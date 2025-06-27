<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MappingMapel extends Model
{
    protected $table = 'mapping_mapel';
    protected $fillable = [
        'mapel_id',
        'kelompok',
        'nomor_urut',
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
}
