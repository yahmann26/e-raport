<?php

namespace App\Models;

use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tapel extends Model
{
    protected $table = 'tapel';
    protected $fillable = [
        'tahun_pelajaran',
        'semester'
    ];

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }

    public function mapel(): HasMany
    {
        return $this->hasMany(Mapel::class);
    }

    public function tgl_raport(): HasOne
    {
        return $this->hasOne(TglRaport::class);
    }
}
