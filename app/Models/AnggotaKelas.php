<?php

namespace App\Models;

use App\Models\Santri;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaKelas extends Model
{
    protected $table = 'anggota_kelas';
    protected $fillable = [
        'santri_id',
        'kelas_id',
        'pendaftaran',
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    public function nilai_absen()
    {
        return $this->hasOne(NilaiAbsen::class);
    }

    public function kehadiran()
    {
        return $this->hasOne(KehadiranSantri::class);
    }


    // Relasi KTSP
    public function ktsp_nilai_absen(): HasOne
    {
        return $this->hasOne(NilaiAbsen::class);
    }

    public function ktsp_nilai_setoran(): HasOne
    {
        return $this->hasOne(NilaiSetoran::class);
    }

    public function nilai_uas(): HasOne
    {
        return $this->hasOne(NilaiUas::class);
    }

    public function anggota_kelas(): HasOne
    {
        return $this->hasOne(AnggotaKelas::class);
    }

    public function nilai_akhir_raport(): HasMany
    {
        return $this->hasMany(NilaiAkhirRaport::class);
    }
}
