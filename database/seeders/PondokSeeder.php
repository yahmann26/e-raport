<?php

namespace Database\Seeders;

use App\Models\Pondok;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PondokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pondok::create([
            'nama_pondok' => 'Pondok Al-Mubaarok',
            'npsn' => '0000000000',
            'kode_pos' => '56353',
            'alamat' => 'Jl. Wilodobanyu',
            'logo' => 'default.png',
            'kepala_pondok' => 'Ahmad',
        ]);
    }
}
