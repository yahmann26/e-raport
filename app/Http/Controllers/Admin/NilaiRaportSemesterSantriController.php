<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\AnggotaKelas;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\NilaiAkhirRaport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NilaiRaportSemesterSantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Nilai Raport Semester';
        $data_mapel = Mapel::where('tapel_id', session()->get('tapel_id'))->get();
        $data_kelas = Kelas::where('tapel_id', session()->get('tapel_id'))->get();
        return view('admin.nilairaport.pilihkelas', compact('title', 'data_mapel', 'data_kelas'));
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
        $validator = Validator::make($request->all(), [
            'mapel_id' => 'required',
            'kelas_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        } else {
            $title = 'Nilai Raport Semester';
            $data_mapel = Mapel::where('tapel_id', session()->get('tapel_id'))->get();
            $data_kelas = Kelas::where('tapel_id', session()->get('tapel_id'))->get();

            $pembelajaran = Pembelajaran::where('mapel_id', $request->mapel_id)->where('kelas_id', $request->kelas_id)->first();
            if (is_null($pembelajaran)) {
                return back()->with('toast_error', 'Data pembelajaran tidak ditemukan');
            } else {
                $kelas = Kelas::findorfail($request->kelas_id);
                $mapel = Mapel::findorfail($request->mapel_id);

                $data_anggota_kelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();
                foreach ($data_anggota_kelas as $anggota_kelas) {
                    $anggota_kelas->nilai_raport = NilaiAkhirRaport::where('pembelajaran_id', $pembelajaran->id)->where('anggota_kelas_id', $anggota_kelas->id)->first();
                }

                return view('admin.nilairaport.index', compact('title', 'mapel', 'kelas', 'data_mapel', 'data_kelas', 'data_anggota_kelas'));
            }
        }
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
