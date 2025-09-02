<?php

namespace App\Http\Controllers\Guru;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Tapel;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\BobotPenilaian;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BobotPenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Bobot Penilaian';
        $tapel = Tapel::findorfail(session()->get('tapel_id'));

        $guru = Guru::where('user_id', Auth::user()->id)->first();
        $id_kelas = Kelas::where('tapel_id', $tapel->id)->get('id');

        $data_bobot_penilaian = Pembelajaran::where('guru_id', $guru->id)->whereIn('kelas_id', $id_kelas)->where('status', 1)->orderBy('mapel_id', 'ASC')->orderBy('kelas_id', 'ASC')->get();
        foreach ($data_bobot_penilaian as $penilaian) {
            $bobot = BobotPenilaian::where('pembelajaran_id', $penilaian->id)->first();
            if (is_null($bobot)) {
                $penilaian->bobot_setoran = null;
                $penilaian->bobot_absen = null;
                $penilaian->bobot_uas = null;
            } else {
                $penilaian->bobot_setoran = $bobot->bobot_setoran;
                $penilaian->bobot_absen = $bobot->bobot_absen;
                $penilaian->bobot_uas = $bobot->bobot_uas;
            }
        }

        return view('guru.bobot.index', compact('title', 'data_bobot_penilaian'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pembelajaran_id' => 'required',
            'bobot_setoran' => 'required|numeric',
            'bobot_absen' => 'required|numeric',
            'bobot_uas' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        } else {
            $bobot = new BobotPenilaian([
                'pembelajaran_id' => $request->pembelajaran_id,
                'bobot_setoran' => $request->bobot_setoran,
                'bobot_absen' => $request->bobot_absen,
                'bobot_uas' => $request->bobot_uas,
            ]);
            $bobot->save();
            return back()->with('toast_success', 'Bobot penilaian berhasil disimpan');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bobot_setoran' => 'required|numeric',
            'bobot_absen' => 'required|numeric',
            'bobot_uas' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        } else {
            $bobot = BobotPenilaian::where('pembelajaran_id', $id)->first();
            $data = [
                'bobot_setoran' => $request->bobot_setoran,
                'bobot_absen' => $request->bobot_absen,
                'bobot_uas' => $request->bobot_uas,
            ];

            $bobot->update($data);
            return back()->with('toast_success', 'Bobot penilaian berhasil diedit');
        }
    }
}
