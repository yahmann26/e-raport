<?php

namespace App\Http\Controllers\Guru;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Tapel;
use App\Models\NilaiUas;
use App\Models\NilaiAbsen;
use App\Models\AnggotaKelas;
use App\Models\NilaiSetoran;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\BobotPenilaian;
use App\Http\Controllers\Controller;
use App\Models\NilaiAkhirRaport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KirimNilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Kirim Nilai Akhir';
        $tapel = Tapel::findorfail(session()->get('tapel_id'));

        $guru = Guru::where('user_id', Auth::user()->id)->first();
        $id_kelas = Kelas::where('tapel_id', $tapel->id)->get('id');
        $data_pembelajaran = Pembelajaran::where('guru_id', $guru->id)->whereIn('kelas_id', $id_kelas)->where('status', 1)->orderBy('mapel_id', 'ASC')->orderBy('kelas_id', 'ASC')->get();

        return view('guru.kirimnilai.index', compact('title', 'data_pembelajaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pembelajaran_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        } else {
            $pembelajaran = Pembelajaran::findorfail($request->pembelajaran_id);

            // $kkm = KtspKkmMapel::where('mapel_id', $pembelajaran->mapel_id)->where('kelas_id', $pembelajaran->kelas_id)->first();
            $bobot_penilaian = BobotPenilaian::where('pembelajaran_id', $pembelajaran->id)->first();

            if (is_null($bobot_penilaian)) {
                return back()->with('toast_warning', 'Bobot penilaian belum ditentukan');
            }

            $nilai_absen = NilaiAbsen::where('pembelajaran_id', $pembelajaran->id)->get();
            $nilai_setoran = NilaiSetoran::where('pembelajaran_id', $pembelajaran->id)->get();
            $nilai_uas = NilaiUas::where('pembelajaran_id', $pembelajaran->id)->get();

            if (count($nilai_absen) == 0 || count($nilai_setoran) == 0 || count($nilai_uas) == 0) {
                return back()->with('toast_warning', 'Data nilai belum lengkap');
            } else {
                // Data Master
                $title = 'Kirim Nilai Akhir';
                $tapel = Tapel::findorfail(session()->get('tapel_id'));

                $guru = Guru::where('user_id', Auth::user()->id)->first();
                $id_kelas = Kelas::where('tapel_id', $tapel->id)->get('id');
                $data_pembelajaran = Pembelajaran::where('guru_id', $guru->id)->whereIn('kelas_id', $id_kelas)->where('status', 1)->orderBy('mapel_id', 'ASC')->orderBy('kelas_id', 'ASC')->get();


                // Data Nilai
                $data_anggota_kelas = AnggotaKelas::where('kelas_id', $pembelajaran->kelas_id)->get();
                foreach ($data_anggota_kelas as $anggota_kelas) {

                    $nilai_absen = NilaiAbsen::where('anggota_kelas_id', $anggota_kelas->id)->where('pembelajaran_id', $pembelajaran->id)->first();
                    $nilai_setoran = NilaiSetoran::where('anggota_kelas_id', $anggota_kelas->id)->where('pembelajaran_id', $pembelajaran->id)->first();
                    $nilai_uas = NilaiUas::where('anggota_kelas_id', $anggota_kelas->id)->where('pembelajaran_id', $pembelajaran->id)->first();

                    $nilai_akhir_raport = (($nilai_absen->nilai * $bobot_penilaian->bobot_absen) + ($nilai_setoran->nilai * $bobot_penilaian->bobot_setoran) + ($nilai_uas->nilai * $bobot_penilaian->bobot_uas)) / ($bobot_penilaian->bobot_absen + $bobot_penilaian->bobot_setoran + $bobot_penilaian->bobot_uas);

                    $anggota_kelas->nilai_akhir = number_format($nilai_akhir_raport, 1);
                }
                return view('guru.kirimnilai.create', compact('title', 'data_pembelajaran', 'pembelajaran', 'data_anggota_kelas'));
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        for ($cound_siswa = 0; $cound_siswa < count($request->anggota_kelas_id); $cound_siswa++) {
            $data_nilai = array(
                'pembelajaran_id' => $request->pembelajaran_id,
                'anggota_kelas_id' => $request->anggota_kelas_id[$cound_siswa],
                'nilai_akhir' => ltrim($request->nilai_akhir[$cound_siswa]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            );

            $cek_nilai = NilaiAkhirRaport::where('pembelajaran_id', $request->pembelajaran_id)->where('anggota_kelas_id', $request->anggota_kelas_id[$cound_siswa])->first();
            if (is_null($cek_nilai)) {
                NilaiAkhirRaport::insert($data_nilai);
            } else {
                $cek_nilai->update($data_nilai);
            }
        }
        return redirect('guru/kirimnilai')->with('toast_success', 'Nilai akhir raport berhasil dikirim');
    }
}
