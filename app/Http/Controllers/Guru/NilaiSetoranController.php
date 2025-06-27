<?php

namespace App\Http\Controllers\Guru;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Tapel;
use App\Models\AnggotaKelas;
use App\Models\NilaiSetoran;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NilaiSetoranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Rata-Rata Nilai Setoran';
        $tapel = Tapel::findorfail(session()->get('tapel_id'));

        $guru = Guru::where('user_id', Auth::user()->id)->first();
        $id_kelas = Kelas::where('tapel_id', $tapel->id)->get('id');

        $data_penilaian = Pembelajaran::where('guru_id', $guru->id)->whereIn('kelas_id', $id_kelas)->where('status', 1)->orderBy('mapel_id', 'ASC')->orderBy('kelas_id', 'ASC')->get();

        foreach ($data_penilaian as $penilaian) {
            $data_anggota_kelas = AnggotaKelas::where('kelas_id', $penilaian->kelas_id)->get();
            $data_nilai_setoran = NilaiSetoran::where('pembelajaran_id', $penilaian->id)->get();

            $penilaian->jumlah_anggota_kelas = count($data_anggota_kelas);
            $penilaian->jumlah_telah_dinilai = count($data_nilai_setoran);
        }

        return view('guru.nilaisetoran.index', compact('title', 'data_penilaian'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pembelajaran = Pembelajaran::findorfail($request->pembelajaran_id);
        $data_anggota_kelas = AnggotaKelas::where('kelas_id', $pembelajaran->kelas_id)->get();

        $data_nilai_setoran = Nilaisetoran::where('pembelajaran_id', $pembelajaran->id)->get();

        if (count($data_nilai_setoran) == 0) {
            $title = 'Input Rata-Rata Nilai setoran';
            return view('guru.nilaisetoran.create', compact('title', 'pembelajaran', 'data_anggota_kelas'));
        } else {
            $title = 'Edit Rata-Rata Nilai setoran';
            return view('guru.nilaisetoran.edit', compact('title', 'pembelajaran', 'data_nilai_setoran'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (is_null($request->anggota_kelas_id)) {
            return back()->with('toast_error', 'Data santri tidak ditemukan');
        } else {
            for ($cound_santri = 0; $cound_santri < count($request->anggota_kelas_id); $cound_santri++) {

                if ($request->nilai[$cound_santri] >= 0 && $request->nilai[$cound_santri] <= 100) {
                    $data_nilai = array(
                        'pembelajaran_id' => $request->pembelajaran_id,
                        'anggota_kelas_id' => $request->anggota_kelas_id[$cound_santri],
                        'nilai' => ltrim($request->nilai[$cound_santri]),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    );
                    $data_nilai_santri[] = $data_nilai;
                } else {
                    return back()->with('toast_error', 'Nilai harus berisi antara 0 s/d 100');
                }
            }
            $store_data_nilai = $data_nilai_santri;
            Nilaisetoran::insert($store_data_nilai);
            return redirect('guru/nilaisetoran')->with('toast_success', 'Data nilai setoran berhasil disimpan.');
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
        for ($cound_santri = 0; $cound_santri < count($request->anggota_kelas_id); $cound_santri++) {

            if ($request->nilai[$cound_santri] >= 0 && $request->nilai[$cound_santri] <= 100) {
                $nilai = Nilaisetoran::where('pembelajaran_id', $id)->where('anggota_kelas_id', $request->anggota_kelas_id[$cound_santri])->first();

                $data_nilai = [
                    'nilai' => ltrim($request->nilai[$cound_santri]),
                    'updated_at' => Carbon::now(),
                ];
                $nilai->update($data_nilai);
            } else {
                return back()->with('toast_error', 'Nilai harus berisi antara 0 s/d 100');
            }
        }
        return redirect('guru/nilaisetoran')->with('toast_success', 'Data nilai setoran berhasil diedit.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
