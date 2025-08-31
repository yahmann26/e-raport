<?php

namespace App\Http\Controllers\Admin;

use PDF;
use Mpdf\Mpdf;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Pondok;
use App\Models\TglRaport;
use App\Models\AnggotaKelas;
use App\Models\MappingMapel;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\KehadiranSantri;
use App\Models\NilaiAkhirRaport;
use Barryvdh\DomPDF\PDF as DomPdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CetakRaportSemesterSantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Cetak Raport Semester';
        $data_kelas = Kelas::where('tapel_id', session()->get('tapel_id'))->get();
        return view('admin.raportsemester.setpaper', compact('title', 'data_kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $title = 'Cetak Raport Semester';
        $kelas = Kelas::findorfail($request->kelas_id);
        $data_kelas = Kelas::where('tapel_id', session()->get('tapel_id'))->get();
        $data_anggota_kelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();

        $paper_size = $request->paper_size;
        $orientation = $request->orientation;

        return view('admin.raportsemester.index', compact('title', 'kelas', 'data_kelas', 'data_anggota_kelas', 'paper_size', 'orientation'));
    }

    public function bulk(Request $request)
    {
        $pondok = Pondok::findOrFail(1);
        $title = 'Cetak Raport';

        $idsArray = $request->input('anggota_kelas_id', []);
        if (empty($idsArray)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih');
        }

        $anggota_kelas_list = AnggotaKelas::with(['santri', 'kelas.tapel.tgl_raport', 'kelas.guru'])->whereIn('id', $idsArray)->get();
        if ($anggota_kelas_list->isEmpty()) {
            return redirect()->back()->with('error', 'Raport tidak ditemukan');
        }

        $tapel_id = session()->get('tapel_id');
        $mapel_semester_ini = Mapel::where('tapel_id', $tapel_id)->pluck('id');

        $mapel_wajib = MappingMapel::whereIn('mapel_id', $mapel_semester_ini)->where('kelompok', 1)->pluck('mapel_id');
        $mapel_pilihan = MappingMapel::whereIn('mapel_id', $mapel_semester_ini)->where('kelompok', 2)->pluck('mapel_id');
        $mapel_mulok = MappingMapel::whereIn('mapel_id', $mapel_semester_ini)->where('kelompok', 3)->pluck('mapel_id');

        // Hitung semua rata-rata santri
        $daftar_nilai_santri = [];
        foreach ($anggota_kelas_list as $anggota) {
            $kelas_id = $anggota->kelas_id;
            $pembelajaran_ids = Pembelajaran::where('kelas_id', $kelas_id)
                ->whereIn('mapel_id', $mapel_semester_ini)
                ->pluck('id');

            $nilai_ids = NilaiAkhirRaport::selectRaw('MAX(id) as id')
                ->whereIn('pembelajaran_id', $pembelajaran_ids)
                ->where('anggota_kelas_id', $anggota->id)
                ->groupBy('pembelajaran_id')
                ->pluck('id');

            $nilai_list = NilaiAkhirRaport::whereIn('id', $nilai_ids)->get();

            $jumlah_nilai = $nilai_list->sum('nilai_akhir');
            $jumlah_mapel = $nilai_list->count();
            $rata_rata = $jumlah_mapel > 0 ? round($jumlah_nilai / $jumlah_mapel, 2) : 0;

            $daftar_nilai_santri[$anggota->id] = $rata_rata;
        }

        // Urutkan dan tentukan peringkat
        arsort($daftar_nilai_santri);
        $peringkat_santri = [];
        $rank = 1;
        foreach ($daftar_nilai_santri as $id => $nilai) {
            $peringkat_santri[$id] = $rank++;
        }

        // PDF Init
        $mpdf = new \Mpdf\Mpdf([
            'setAutoTopMargin' => 'stretch',
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
        ]);

        $jumlah_santri = $anggota_kelas_list->count();

        $tanggal_raport = TglRaport::where('tapel_id', session()->get('tapel_id'))->first();

        foreach ($anggota_kelas_list as $anggota) {
            $kelas_id = $anggota->kelas_id;

            $pembelajaran_wajib = Pembelajaran::where('kelas_id', $kelas_id)->whereIn('mapel_id', $mapel_wajib)->pluck('id');
            $pembelajaran_pilihan = Pembelajaran::where('kelas_id', $kelas_id)->whereIn('mapel_id', $mapel_pilihan)->pluck('id');
            $pembelajaran_mulok = Pembelajaran::where('kelas_id', $kelas_id)->whereIn('mapel_id', $mapel_mulok)->pluck('id');

            $anggota_kelas_ids = AnggotaKelas::where('kelas_id', $kelas_id)->pluck('id');

            $rataRataPerMapel = function ($pembelajaran_ids) use ($anggota_kelas_ids) {
                return NilaiAkhirRaport::select('pembelajaran_id', DB::raw('AVG(nilai_akhir) as rata_rata'))
                    ->whereIn('pembelajaran_id', $pembelajaran_ids)
                    ->whereIn('anggota_kelas_id', $anggota_kelas_ids)
                    ->groupBy('pembelajaran_id')
                    ->pluck('rata_rata', 'pembelajaran_id');
            };

            $rata_wajib = $rataRataPerMapel($pembelajaran_wajib);
            $rata_pilihan = $rataRataPerMapel($pembelajaran_pilihan);
            $rata_mulok = $rataRataPerMapel($pembelajaran_mulok);

            $nilai_wajib_ids = NilaiAkhirRaport::selectRaw('MAX(id) as id')
                ->whereIn('pembelajaran_id', $pembelajaran_wajib)
                ->where('anggota_kelas_id', $anggota->id)
                ->groupBy('pembelajaran_id')->pluck('id');

            $nilai_pilihan_ids = NilaiAkhirRaport::selectRaw('MAX(id) as id')
                ->whereIn('pembelajaran_id', $pembelajaran_pilihan)
                ->where('anggota_kelas_id', $anggota->id)
                ->groupBy('pembelajaran_id')->pluck('id');

            $nilai_mulok_ids = NilaiAkhirRaport::selectRaw('MAX(id) as id')
                ->whereIn('pembelajaran_id', $pembelajaran_mulok)
                ->where('anggota_kelas_id', $anggota->id)
                ->groupBy('pembelajaran_id')->pluck('id');

            $data_nilai_mapel_wajib = NilaiAkhirRaport::with('pembelajaran.mapel')
                ->whereIn('id', $nilai_wajib_ids)->get();

            $data_nilai_mapel_pilihan = NilaiAkhirRaport::with('pembelajaran.mapel')
                ->whereIn('id', $nilai_pilihan_ids)->get();

            $data_nilai_mapel_mulok = NilaiAkhirRaport::with('pembelajaran.mapel')
                ->whereIn('id', $nilai_mulok_ids)->get();

            $data_nilai_mapel_wajib->each(function ($item) use ($rata_wajib) {
                $item->pembelajaran->rata_rata = $rata_wajib[$item->pembelajaran_id] ?? 0;
            });
            $data_nilai_mapel_pilihan->each(function ($item) use ($rata_pilihan) {
                $item->pembelajaran->rata_rata = $rata_pilihan[$item->pembelajaran_id] ?? 0;
            });
            $data_nilai_mapel_mulok->each(function ($item) use ($rata_mulok) {
                $item->pembelajaran->rata_rata = $rata_mulok[$item->pembelajaran_id] ?? 0;
            });

            $jumlah_nilai = $data_nilai_mapel_wajib->sum('nilai_akhir') +
                $data_nilai_mapel_pilihan->sum('nilai_akhir') +
                $data_nilai_mapel_mulok->sum('nilai_akhir');

            $jumlah_mapel = $data_nilai_mapel_wajib->count() + $data_nilai_mapel_pilihan->count() + $data_nilai_mapel_mulok->count();
            $rt_nilai = $jumlah_mapel > 0 ? round($jumlah_nilai / $jumlah_mapel, 1) : 0;

            $total_rata_mapel_wajib = $data_nilai_mapel_wajib->sum(fn($n) => $n->pembelajaran->rata_rata ?? 0);
            $total_rata_mapel_pilihan = $data_nilai_mapel_pilihan->sum(fn($n) => $n->pembelajaran->rata_rata ?? 0);
            $total_rata_mapel_mulok = $data_nilai_mapel_mulok->sum(fn($n) => $n->pembelajaran->rata_rata ?? 0);

            $total_mapel = $jumlah_mapel;
            $jumlahNilairata = $total_rata_mapel_wajib + $total_rata_mapel_pilihan + $total_rata_mapel_mulok;
            $rata_rata_kelas = $total_mapel > 0 ? $jumlahNilairata / $total_mapel : 0;

            $peringkat = $peringkat_santri[$anggota->id] ?? '-';

            $html = view('admin.raportsemester.cetak1', compact(
                'title',
                'pondok',
                'anggota',
                'data_nilai_mapel_wajib',
                'data_nilai_mapel_pilihan',
                'data_nilai_mapel_mulok',
                'jumlah_nilai',
                'rt_nilai',
                'rata_rata_kelas',
                'jumlahNilairata',
                'jumlah_santri',
                'peringkat',
                'tanggal_raport'
            ))->render();

            $mpdf->AddPage();
            $mpdf->WriteHTML($html);
        }

        return $mpdf->Output('raport_santri.pdf', 'I');
    }




    /**
     * Hitung rata-rata nilai per mapel di kelas tertentu.
     */
    private function getRataRataMapelPerKelas($kelas_id)
    {
        $pembelajaran = Pembelajaran::with('mapel')
            ->where('kelas_id', $kelas_id)
            ->get();

        $hasil = [];

        foreach ($pembelajaran as $p) {
            $nilai = NilaiAkhirRaport::where('pembelajaran_id', $p->id)->get();
            $jumlah_siswa = $nilai->count();
            $total_nilai = $nilai->sum('nilai_akhir');
            $rata_rata = $jumlah_siswa > 0 ? round($total_nilai / $jumlah_siswa, 2) : 0;

            $hasil[] = [
                'mapel' => $p->mapel->nama,
                'kode_mapel' => $p->mapel->kode ?? '-',
                'rata_rata' => $rata_rata,
                'jumlah_siswa' => $jumlah_siswa,
            ];
        }

        return $hasil;
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        // $pondok = Pondok::first();
        // $anggota_kelas = AnggotaKelas::findorfail($id);

        // if ($request->data_type == 1) {
        //     $title = 'Kelengkapan Raport';
        //     $kelengkapan_raport = PDF::loadview('walikelas.k13.raportsemester.kelengkapanraport', compact('title', 'sekolah', 'anggota_kelas'))->setPaper($request->paper_size, $request->orientation);
        //     return $kelengkapan_raport->stream('KELENGKAPAN RAPORT ' . $anggota_kelas->santri->nama_lengkap . ' (' . $anggota_kelas->kelas->nama_kelas . ').pdf');
        // } elseif ($request->data_type == 2) {
        //     $title = 'Cetak Raport';

        //     $data_id_mapel_semester_ini = Mapel::where('tapel_id', session()->get('tapel_id'))->get('id');
        //     $data_id_mapel_durusul_aula = MappingMapel::whereIn('mapel_id', $data_id_mapel_semester_ini)->where('kelompok', 1)->get('mapel_id');
        //     $data_id_mapel_durusul_zaidah = MappingMapel::whereIn('mapel_id', $data_id_mapel_semester_ini)->where('kelompok', 2)->get('mapel_id');
        //     $data_id_mapel_muatan_lokal = MappingMapel::whereIn('mapel_id', $data_id_mapel_semester_ini)->where('kelompok', 3)->get('mapel_id');

        //     $data_id_pembelajaran_durusul_aula = Pembelajaran::where('kelas_id', $anggota_kelas->kelas_id)->whereIn('mapel_id', $data_id_mapel_durusul_aula)->get('id');
        //     $data_id_pembelajaran_durusul_zaidah = Pembelajaran::where('kelas_id', $anggota_kelas->kelas_id)->whereIn('mapel_id', $data_id_mapel_durusul_zaidah)->get('id');
        //     $data_id_pembelajaran_muatan_lokal = Pembelajaran::where('kelas_id', $anggota_kelas->kelas_id)->whereIn('mapel_id', $data_id_mapel_muatan_lokal)->get('id');

        //     $data_nilai_mapel_durusul_aula = NilaiAkhirRaport::whereIn('pembelajaran_id', $data_id_pembelajaran_durusul_aula)->where('anggota_kelas_id', $anggota_kelas->id)->get();
        //     $data_nilai_mapel_durusul_zaidah = NilaiAkhirRaport::whereIn('pembelajaran_id', $data_id_pembelajaran_durusul_zaidah)->where('anggota_kelas_id', $anggota_kelas->id)->get();
        //     $data_nilai_mapel_muatan_lokal = NilaiAkhirRaport::whereIn('pembelajaran_id', $data_id_pembelajaran_muatan_lokal)->where('anggota_kelas_id', $anggota_kelas->id)->get();

        //     // $data_id_ekstrakulikuler = Ekstrakulikuler::where('tapel_id', session()->get('tapel_id'))->get('id');

        //     // $data_anggota_ekstrakulikuler = AnggotaEkstrakulikuler::whereIn('ekstrakulikuler_id', $data_id_ekstrakulikuler)->where('anggota_kelas_id', $anggota_kelas->id)->get();
        //     // foreach ($data_anggota_ekstrakulikuler as $anggota_ekstrakulikuler) {
        //     //     $cek_nilai_ekstra = NilaiEkstrakulikuler::where('anggota_ekstrakulikuler_id', $anggota_ekstrakulikuler->id)->first();
        //     //     if (is_null($cek_nilai_ekstra)) {
        //     //         $anggota_ekstrakulikuler->nilai = null;
        //     //         $anggota_ekstrakulikuler->deskripsi = null;
        //     //     } else {
        //     //         $anggota_ekstrakulikuler->nilai = $cek_nilai_ekstra->nilai;
        //     //         $anggota_ekstrakulikuler->deskripsi = $cek_nilai_ekstra->deskripsi;
        //     //     }
        //     // }

        //     $data_prestasi_santri = Prestasisantri::where('anggota_kelas_id', $anggota_kelas->id)->get();
        //     $kehadiran_santri = KehadiranSantri::where('anggota_kelas_id', $anggota_kelas->id)->first();
        //     $catatan_wali_kelas = CatatanWaliKelas::where('anggota_kelas_id', $anggota_kelas->id)->first();

        //     $cek_tanggal_raport = TglRaport::where('tapel_id', session()->get('tapel_id'))->first();
        //     if (is_null($cek_tanggal_raport)) {
        //         return back()->with('toast_warning', 'Tanggal raport belum disetting oleh admin');
        //     } else {
        //         $raport = PDF::loadview('walikelas.raportuas.raport', compact('title', 'pondok', 'anggota_kelas', 'data_nilai_mapel_durusul_aula', 'data_nilai_mapel_durusul_zaidah', 'data_nilai_mapel_muatan_lokal', 'data_anggota_ekstrakulikuler', 'data_prestasi_santri', 'kehadiran_santri', 'catatan_wali_kelas'))->setPaper($request->paper_size, $request->orientation);
        //         return $raport->stream('RAPORT ' . $anggota_kelas->santri->nama_lengkap . ' (' . $anggota_kelas->kelas->nama_kelas . ').pdf');
        //     }
        // }
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
