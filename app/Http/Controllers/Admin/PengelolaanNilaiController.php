<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Tapel;
use App\Models\Pondok;
use App\Models\AnggotaKelas;
use App\Models\MappingMapel;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\NilaiAkhirRaport;
use App\Http\Controllers\Controller;

class PengelolaanNilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Hasil Pengelolaan Nilai santri';
        $data_kelas = Kelas::where('tapel_id', session()->get('tapel_id'))->get();
        return view('admin.hasilpengelolaannilai.pilihkelas', compact('title', 'data_kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $title = 'Hasil Pengelolaan Nilai Santri';
        $pondok = Pondok::first();
        $tapel = Tapel::findOrFail(session()->get('tapel_id'));
        $data_kelas = Kelas::where('tapel_id', $tapel->id)->get();
        $kelas = Kelas::findOrFail($request->kelas_id);

        // Data ID per kelompok mapel
        $data_id_mapel_semester_ini = Mapel::where('tapel_id', $tapel->id)->pluck('id');
        $data_id_mapel_wajib = MappingMapel::whereIn('mapel_id', $data_id_mapel_semester_ini)->where('kelompok', 1)->pluck('mapel_id');
        $data_id_mapel_pilihan = MappingMapel::whereIn('mapel_id', $data_id_mapel_semester_ini)->where('kelompok', 2)->pluck('mapel_id');
        $data_id_mapel_muatan_lokal = MappingMapel::whereIn('mapel_id', $data_id_mapel_semester_ini)->where('kelompok', 3)->pluck('mapel_id');

        // Rata-rata nilai per mapel dikelompokkan
        $rata_rata_per_kelompok = [
            'wajib' => [],
            'pilihan' => [],
            'muatan_lokal' => [],
        ];

        $mapel_ids = Mapel::where('tapel_id', $tapel->id)->pluck('id');

        foreach ($mapel_ids as $mapel_id) {
            $pembelajaran_ids = Pembelajaran::where('kelas_id', $kelas->id)
                ->where('mapel_id', $mapel_id)
                ->pluck('id');

            if ($pembelajaran_ids->count() > 0) {
                $avg_nilai = NilaiAkhirRaport::whereIn('pembelajaran_id', $pembelajaran_ids)->avg('nilai_akhir');
                $mapel = Mapel::find($mapel_id);

                $data = [
                    'nama_mapel' => $mapel->nama_mapel,
                    'rata_nilai' => round($avg_nilai, 2),
                ];

                if ($data_id_mapel_wajib->contains($mapel_id)) {
                    $rata_rata_per_kelompok['wajib'][] = $data;
                } elseif ($data_id_mapel_pilihan->contains($mapel_id)) {
                    $rata_rata_per_kelompok['pilihan'][] = $data;
                } elseif ($data_id_mapel_muatan_lokal->contains($mapel_id)) {
                    $rata_rata_per_kelompok['muatan_lokal'][] = $data;
                }
            }
        }

        // Data nilai per anggota kelas
        $data_anggota_kelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();
        foreach ($data_anggota_kelas as $anggota_kelas) {
            $data_id_pembelajaran_wajib = Pembelajaran::where('kelas_id', $anggota_kelas->kelas_id)->whereIn('mapel_id', $data_id_mapel_wajib)->pluck('id');
            $data_id_pembelajaran_pilihan = Pembelajaran::where('kelas_id', $anggota_kelas->kelas_id)->whereIn('mapel_id', $data_id_mapel_pilihan)->pluck('id');
            $data_id_pembelajaran_muatan_lokal = Pembelajaran::where('kelas_id', $anggota_kelas->kelas_id)->whereIn('mapel_id', $data_id_mapel_muatan_lokal)->pluck('id');

            $anggota_kelas->data_nilai_mapel_wajib = NilaiAkhirRaport::whereIn('pembelajaran_id', $data_id_pembelajaran_wajib)->where('anggota_kelas_id', $anggota_kelas->id)->get();
            $anggota_kelas->data_nilai_mapel_pilihan = NilaiAkhirRaport::whereIn('pembelajaran_id', $data_id_pembelajaran_pilihan)->where('anggota_kelas_id', $anggota_kelas->id)->get();
            $anggota_kelas->data_nilai_mapel_muatan_lokal = NilaiAkhirRaport::whereIn('pembelajaran_id', $data_id_pembelajaran_muatan_lokal)->where('anggota_kelas_id', $anggota_kelas->id)->get();
        }

        return view('admin.hasilpengelolaannilai.index', compact(
            'title',
            'kelas',
            'data_kelas',
            'pondok',
            'data_anggota_kelas',
            'rata_rata_per_kelompok'
        ));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
