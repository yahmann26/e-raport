<?php

namespace App\Models;

use App\Models\MappingMapel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mapel extends Model
{
    protected $table = 'mapel';
    protected $fillable = [
        'tapel_id',
        'nama_mapel',
        'ringkasan_mapel'
    ];

    public function tapel(): BelongsTo
    {
        return $this->belongsTo(Tapel::class);
    }

    public function pembelajaran(): HasMany
    {
        return $this->hasMany(Pembelajaran::class);
    }

    public function mapping_mapel(): HasOne
    {
        return $this->hasOne(MappingMapel::class);
    }
}
