<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelas;
use App\Models\Tapel;
use App\Models\NilaiUas;
use App\Models\NilaiAbsen;
use App\Models\NilaiSetoran;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\BobotPenilaian;
use App\Models\NilaiAkhirRaport;
use App\Http\Controllers\Controller;

class StatusPenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Status Penilaian';
        $data_kelas = Kelas::where('tapel_id', session()->get('tapel_id'))->get();
        return view('admin.statuspenilaian.pilihkelas', compact('title', 'data_kelas'));
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
        $title = 'Hasil Pengelolaan Nilai';
        $tapel = Tapel::findorfail(session()->get('tapel_id'));
        $data_kelas = Kelas::where('tapel_id', $tapel->id)->get();

        $kelas = Kelas::findorfail($request->kelas_id);

        $data_pembelajaran_kelas = Pembelajaran::where('kelas_id', $kelas->id)->where('status', 1)->get();
        foreach ($data_pembelajaran_kelas as $pembelajaran) {
            $bobot = BobotPenilaian::where('pembelajaran_id', $pembelajaran->id)->first();
            if (is_null($bobot)) {
                $pembelajaran->bobot = 0;
            } else {
                $pembelajaran->bobot = 1;
            }

            $nilai_absen = NilaiAbsen::where('pembelajaran_id', $pembelajaran->id)->first();
            if (is_null($nilai_absen)) {
                $pembelajaran->nilai_absen = 0;
            } else {
                $pembelajaran->nilai_absen = 1;
            }

            $nilai_setoran = NilaiSetoran::where('pembelajaran_id', $pembelajaran->id)->first();
            if (is_null($nilai_setoran)) {
                $pembelajaran->nilai_setoran = 0;
            } else {
                $pembelajaran->nilai_setoran = 1;
            }

            $uas = NilaiUas::where('pembelajaran_id', $pembelajaran->id)->first();
            if (is_null($uas)) {
                $pembelajaran->uas = 0;
            } else {
                $pembelajaran->uas = 1;
            }

            $nilai_akhir = NilaiAkhirRaport::where('pembelajaran_id', $pembelajaran->id)->first();
            if (is_null($nilai_akhir)) {
                $pembelajaran->nilai_akhir = 0;
            } else {
                $pembelajaran->nilai_akhir = 1;
            }

            // $deskripsi = DeskripsiNilaiSiswa::where('pembelajaran_id', $pembelajaran->id)->first();
            // if (is_null($deskripsi)) {
            //     $pembelajaran->deskripsi = 0;
            // } else {
            //     $pembelajaran->deskripsi = 1;
            // }
        }
        return view('admin.statuspenilaian.index', compact('title', 'kelas', 'data_kelas', 'data_pembelajaran_kelas'));
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
