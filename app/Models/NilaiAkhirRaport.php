<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiAkhirRaport extends Model
{
    protected $table = 'nilai_akhir_raport';

    protected $guarded = [];

    public function pembelajaran()
    {
        return $this->belongsTo(Pembelajaran::class);
    }

    public function anggota_kelas()
    {
        return $this->belongsTo(AnggotaKelas::class);
    }

}
