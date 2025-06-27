<?php

namespace App\Http\Controllers\Guru;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Tapel;
use App\Models\AnggotaKelas;
use App\Models\Pembelajaran;
use App\Models\RiwayatLogin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pondok;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        $pondok = Pondok::first();
        $tapel = Tapel::findorfail(session()->get('tapel_id'));
        $guru = Guru::where('user_id', Auth::user()->id)->first();

        // if (session()->get('akses_sebagai') == 'Guru Mapel') {
        //     $id_kelas = Kelas::where('tapel_id', $tapel->id)->get('id');

        //     $jumlah_kelas_diampu = count(Pembelajaran::where('guru_id', $guru->id)->whereIn('kelas_id', $id_kelas)->where('status', 1)->groupBy('kelas_id')->get());
        //     $jumlah_mapel_diampu = count(Pembelajaran::where('guru_id', $guru->id)->whereIn('kelas_id', $id_kelas)->where('status', 1)->groupBy('mapel_id')->get());

        //     $id_kelas_diampu = Pembelajaran::where('guru_id', $guru->id)->whereIn('kelas_id', $id_kelas)->where('status', 1)->groupBy('kelas_id')->get('kelas_id');
        //     $jumlah_siswa_diampu = AnggotaKelas::whereIn('kelas_id', $id_kelas_diampu)->count();

        //     // $jumlah_ekstrakulikuler_diampu = Ekstrakulikuler::where('pembina_id', $guru->id)->count();

        //     $data_capaian_penilaian = Pembelajaran::where('guru_id', $guru->id)->whereIn('kelas_id', $id_kelas)->where('status', 1)->get();


        //     return view('guru.dashboard', compact(
        //         'title',
        //         'data_pengumuman',
        //         'data_riwayat_login',
        //         'sekolah',
        //         'tapel',
        //         'jumlah_kelas_diampu',
        //         'jumlah_mapel_diampu',
        //         'jumlah_siswa_diampu',
        //         'jumlah_ekstrakulikuler_diampu',
        //         'data_capaian_penilaian',
        //     ));
        // } elseif (session()->get('akses_sebagai') == 'Wali Kelas') {

        //     $id_kelas_diampu = Kelas::where('tapel_id', $tapel->id)->where('guru_id', $guru->id)->get('id');
        //     $jumlah_anggota_kelas = count(AnggotaKelas::whereIn('kelas_id', $id_kelas_diampu)->get());

        //     $id_pembelajaran_kelas = Pembelajaran::whereIn('kelas_id', $id_kelas_diampu)->where('status', 1)->get('id');
        //     // if (session()->get('kurikulum') == '2013') {
        //     //     $jumlah_kirim_nilai = count(K13NilaiAkhirRaport::whereIn('pembelajaran_id', $id_pembelajaran_kelas)->groupBy('pembelajaran_id')->get());
        //     //     $jumlah_proses_deskripsi = count(K13DeskripsiNilaiSiswa::whereIn('pembelajaran_id', $id_pembelajaran_kelas)->groupBy('pembelajaran_id')->get());
        //     // } elseif (session()->get('kurikulum') == '2006') {
        //     //     $jumlah_kirim_nilai = count(KtspNilaiAkhirRaport::whereIn('pembelajaran_id', $id_pembelajaran_kelas)->groupBy('pembelajaran_id')->get());
        //     //     $jumlah_proses_deskripsi = count(KtspDeskripsiNilaiSiswa::whereIn('pembelajaran_id', $id_pembelajaran_kelas)->groupBy('pembelajaran_id')->get());
        //     // }

        //     // Dashboard Wali Kelas
        //     return view('dashboard.walikelas', compact(
        //         'title',
        //         'data_pengumuman',
        //         'data_riwayat_login',
        //         'sekolah',
        //         'tapel',
        //         'jumlah_anggota_kelas',
        //         'jumlah_kirim_nilai',
        //         'jumlah_proses_deskripsi',
        //     ));
        // }

        return view('guru.dashboard', compact(
            'title',
            'pondok',
            'tapel'
        ));
    }
}
