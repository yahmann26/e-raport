<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pondok extends Model
{
    protected $table = 'pondok';
    protected $fillable = [
        'nama_pondok',
        'npsn',
        'nss',
        'alamat',
        'kode_pos',
        'email',
        'nomor_telpon',
        'website',
        'kepala_pondok',
        'nip_kepala_pondok',
        'logo'
    ];
}
