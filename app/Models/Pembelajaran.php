<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembelajaran extends Model
{
    protected $table = 'pembelajaran';
    protected $fillable = [
        'kelas_id',
        'mapel_id',
        'guru_id',
        'status'
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function bobot_penilaian(): HasOne
    {
        return $this->hasOne(BobotPenilaian::class);
    }

    public function nilai_absen(): HasMany
    {
        return $this->hasMany(NilaiAbsen::class);
    }

    public function nilai_setoran(): HasMany
    {
        return $this->hasMany(NilaiSetoran::class);
    }

    public function nilai_uas(): HasMany
    {
        return $this->hasMany(NilaiUas::class);
    }

    public function nilai_akhir_raport(): HasMany
    {
        return $this->hasMany(NilaiAkhirRaport::class);
    }
}
