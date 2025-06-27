<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KehadiranSantri extends Model
{
    protected $table = 'kehadiran_santri';
    protected $fillable = [
        'anggota_kelas_id',
        'sakit',
        'izin',
        'tanpa_keterangan'
    ];

    public function anggota_kelas()
    {
        return $this->belongsTo(AnggotaKelas::class, 'anggota_kelas_id', 'id');
    }
}
