<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Tapel;
use App\Models\AnggotaKelas;
use App\Models\MappingMapel;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\NilaiAkhirRaport;
use App\Http\Controllers\Controller;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Response;

class LegerSantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Leger Nilai Siswa';
        $data_kelas = Kelas::where('tapel_id', session()->get('tapel_id'))->get();
        return view('admin.legernilai.pilihkelas', compact('title', 'data_kelas'));
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
        $title = 'Leger Nilai Siswa';
        $tapel = Tapel::findOrFail(session()->get('tapel_id'));
        $kelas = Kelas::findOrFail($request->kelas_id);
        $data_kelas = Kelas::where('tapel_id', session()->get('tapel_id'))->get();

        $data_id_mapel_semester_ini = Mapel::where('tapel_id', $tapel->id)->pluck('id');
        $data_id_mapel_wajib = MappingMapel::whereIn('mapel_id', $data_id_mapel_semester_ini)->where('kelompok', 1)->pluck('mapel_id');
        $data_id_mapel_pilihan = MappingMapel::whereIn('mapel_id', $data_id_mapel_semester_ini)->where('kelompok', 2)->pluck('mapel_id');
        $data_id_mapel_muatan_lokal = MappingMapel::whereIn('mapel_id', $data_id_mapel_semester_ini)->where('kelompok', 3)->pluck('mapel_id');

        $data_id_pembelajaran_all = Pembelajaran::where('kelas_id', $kelas->id)->pluck('id');
        $data_id_pembelajaran_mapel_wajib = Pembelajaran::where('kelas_id', $kelas->id)->whereIn('mapel_id', $data_id_mapel_wajib)->pluck('id');
        $data_id_pembelajaran_mapel_pilihan = Pembelajaran::where('kelas_id', $kelas->id)->whereIn('mapel_id', $data_id_mapel_pilihan)->pluck('id');
        $data_id_pembelajaran_mapel_muatan_lokal = Pembelajaran::where('kelas_id', $kelas->id)->whereIn('mapel_id', $data_id_mapel_muatan_lokal)->pluck('id');

        // Fix group by with subquery to get the latest nilai per pembelajaran
        $id_nilai_mapel_wajib = NilaiAkhirRaport::selectRaw('MAX(id) as id')
            ->whereIn('pembelajaran_id', $data_id_pembelajaran_mapel_wajib)
            ->groupBy('pembelajaran_id')
            ->pluck('id');

        $data_mapel_wajib = NilaiAkhirRaport::whereIn('id', $id_nilai_mapel_wajib)->get();

        $id_nilai_mapel_pilihan = NilaiAkhirRaport::selectRaw('MAX(id) as id')
            ->whereIn('pembelajaran_id', $data_id_pembelajaran_mapel_pilihan)
            ->groupBy('pembelajaran_id')
            ->pluck('id');

        $data_mapel_pilihan = NilaiAkhirRaport::whereIn('id', $id_nilai_mapel_pilihan)->get();

        $id_nilai_mapel_muatan_lokal = NilaiAkhirRaport::selectRaw('MAX(id) as id')
            ->whereIn('pembelajaran_id', $data_id_pembelajaran_mapel_muatan_lokal)
            ->groupBy('pembelajaran_id')
            ->pluck('id');

        $data_mapel_muatan_lokal = NilaiAkhirRaport::whereIn('id', $id_nilai_mapel_muatan_lokal)->get();

        // $data_ekstrakulikuler = Ekstrakulikuler::where('tapel_id', $tapel->id)->get();
        // $count_ekstrakulikuler = count($data_ekstrakulikuler);

        $data_anggota_kelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();

        foreach ($data_anggota_kelas as $anggota_kelas) {
            $data_nilai_mapel_wajib = NilaiAkhirRaport::whereIn('pembelajaran_id', $data_id_pembelajaran_mapel_wajib)
                ->where('anggota_kelas_id', $anggota_kelas->id)
                ->get();

            $data_nilai_mapel_pilihan = NilaiAkhirRaport::whereIn('pembelajaran_id', $data_id_pembelajaran_mapel_pilihan)
                ->where('anggota_kelas_id', $anggota_kelas->id)
                ->get();

            $data_nilai_mapel_muatan_lokal = NilaiAkhirRaport::whereIn('pembelajaran_id', $data_id_pembelajaran_mapel_muatan_lokal)
                ->where('anggota_kelas_id', $anggota_kelas->id)
                ->get();

            $anggota_kelas->data_nilai_mapel_wajib = $data_nilai_mapel_wajib;
            $anggota_kelas->data_nilai_mapel_pilihan = $data_nilai_mapel_pilihan;
            $anggota_kelas->data_nilai_mapel_muatan_lokal = $data_nilai_mapel_muatan_lokal;

            $jumlah_nilai = NilaiAkhirRaport::where('anggota_kelas_id', $anggota_kelas->id)->sum('nilai_akhir');
            $rt_nilai = NilaiAkhirRaport::where('anggota_kelas_id', $anggota_kelas->id)->avg('nilai_akhir');

            $anggota_kelas->jumlah_nilai = round($jumlah_nilai, 1);
            $anggota_kelas->rt_nilai = round($rt_nilai, 1);

            // Uncomment dan sesuaikan jika ekstrakurikuler diaktifkan
            // $anggota_kelas->data_nilai_ekstrakulikuler = Ekstrakulikuler::where('tapel_id', $tapel->id)->get();
            // foreach ($anggota_kelas->data_nilai_ekstrakulikuler as $data_nilai_ekstrakulikuler) {
            //     $cek_anggota_ekstra = AnggotaEkstrakulikuler::where('ekstrakulikuler_id', $data_nilai_ekstrakulikuler->id)
            //         ->where('anggota_kelas_id', $anggota_kelas->id)
            //         ->first();
            //     if (is_null($cek_anggota_ekstra)) {
            //         $data_nilai_ekstrakulikuler->nilai = '-';
            //     } else {
            //         $cek_nilai_ekstra = NilaiEkstrakulikuler::where('ekstrakulikuler_id', $data_nilai_ekstrakulikuler->id)
            //             ->where('anggota_ekstrakulikuler_id', $cek_anggota_ekstra->id)
            //             ->first();
            //         $data_nilai_ekstrakulikuler->nilai = $cek_nilai_ekstra ? $cek_nilai_ekstra->nilai : '-';
            //     }
            // }
        }

        $data_anggota_kelas = $data_anggota_kelas->sortByDesc('rt_nilai')->values();

        $peringkat = 1;
        $peringkatSebelumnya = null;
        $counter = 1;

        foreach ($data_anggota_kelas as $anggota_kelas) {
            if ($anggota_kelas->rt_nilai !== $peringkatSebelumnya) {
                $peringkat = $counter;
            }
            $anggota_kelas->peringkat = $peringkat;
            $peringkatSebelumnya = $anggota_kelas->rt_nilai;
            $counter++;
        }

        return view('admin.legernilai.index', compact(
            'title',
            'kelas',
            'data_kelas',
            'data_mapel_wajib',
            'data_mapel_pilihan',
            'data_mapel_muatan_lokal',
            // 'data_ekstrakulikuler',
            // 'count_ekstrakulikuler',
            'data_anggota_kelas'
        ));
    }


    /**
     * Display the specified resource.
     */
    

     
     public function cetak(Request $request, $kelas_id)
     {
         $title = 'Leger Nilai Siswa';
         $tapel = Tapel::findOrFail(session()->get('tapel_id'));
         $kelas = Kelas::findOrFail($kelas_id);
         $data_kelas = Kelas::where('tapel_id', session()->get('tapel_id'))->get();
     
         // Query data sama seperti method store
         $data_id_mapel_semester_ini = Mapel::where('tapel_id', $tapel->id)->pluck('id');
         $data_id_mapel_wajib = MappingMapel::whereIn('mapel_id', $data_id_mapel_semester_ini)->where('kelompok', 1)->pluck('mapel_id');
         $data_id_mapel_pilihan = MappingMapel::whereIn('mapel_id', $data_id_mapel_semester_ini)->where('kelompok', 2)->pluck('mapel_id');
         $data_id_mapel_muatan_lokal = MappingMapel::whereIn('mapel_id', $data_id_mapel_semester_ini)->where('kelompok', 3)->pluck('mapel_id');
     
         $data_id_pembelajaran_all = Pembelajaran::where('kelas_id', $kelas->id)->pluck('id');
         $data_id_pembelajaran_mapel_wajib = Pembelajaran::where('kelas_id', $kelas->id)->whereIn('mapel_id', $data_id_mapel_wajib)->pluck('id');
         $data_id_pembelajaran_mapel_pilihan = Pembelajaran::where('kelas_id', $kelas->id)->whereIn('mapel_id', $data_id_mapel_pilihan)->pluck('id');
         $data_id_pembelajaran_mapel_muatan_lokal = Pembelajaran::where('kelas_id', $kelas->id)->whereIn('mapel_id', $data_id_mapel_muatan_lokal)->pluck('id');
     
         // Fix group by with subquery to get the latest nilai per pembelajaran
         $id_nilai_mapel_wajib = NilaiAkhirRaport::selectRaw('MAX(id) as id')
             ->whereIn('pembelajaran_id', $data_id_pembelajaran_mapel_wajib)
             ->groupBy('pembelajaran_id')
             ->pluck('id');
     
         $data_mapel_wajib = NilaiAkhirRaport::whereIn('id', $id_nilai_mapel_wajib)->get();
     
         $id_nilai_mapel_pilihan = NilaiAkhirRaport::selectRaw('MAX(id) as id')
             ->whereIn('pembelajaran_id', $data_id_pembelajaran_mapel_pilihan)
             ->groupBy('pembelajaran_id')
             ->pluck('id');
     
         $data_mapel_pilihan = NilaiAkhirRaport::whereIn('id', $id_nilai_mapel_pilihan)->get();
     
         $id_nilai_mapel_muatan_lokal = NilaiAkhirRaport::selectRaw('MAX(id) as id')
             ->whereIn('pembelajaran_id', $data_id_pembelajaran_mapel_muatan_lokal)
             ->groupBy('pembelajaran_id')
             ->pluck('id');
     
         $data_mapel_muatan_lokal = NilaiAkhirRaport::whereIn('id', $id_nilai_mapel_muatan_lokal)->get();
     
         $data_anggota_kelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();
     
         foreach ($data_anggota_kelas as $anggota_kelas) {
             $data_nilai_mapel_wajib = NilaiAkhirRaport::whereIn('pembelajaran_id', $data_id_pembelajaran_mapel_wajib)
                 ->where('anggota_kelas_id', $anggota_kelas->id)
                 ->get();
     
             $data_nilai_mapel_pilihan = NilaiAkhirRaport::whereIn('pembelajaran_id', $data_id_pembelajaran_mapel_pilihan)
                 ->where('anggota_kelas_id', $anggota_kelas->id)
                 ->get();
     
             $data_nilai_mapel_muatan_lokal = NilaiAkhirRaport::whereIn('pembelajaran_id', $data_id_pembelajaran_mapel_muatan_lokal)
                 ->where('anggota_kelas_id', $anggota_kelas->id)
                 ->get();
     
             $anggota_kelas->data_nilai_mapel_wajib = $data_nilai_mapel_wajib;
             $anggota_kelas->data_nilai_mapel_pilihan = $data_nilai_mapel_pilihan;
             $anggota_kelas->data_nilai_mapel_muatan_lokal = $data_nilai_mapel_muatan_lokal;
     
             $jumlah_nilai = NilaiAkhirRaport::where('anggota_kelas_id', $anggota_kelas->id)->sum('nilai_akhir');
             $rt_nilai = NilaiAkhirRaport::where('anggota_kelas_id', $anggota_kelas->id)->avg('nilai_akhir');
     
             $anggota_kelas->jumlah_nilai = round($jumlah_nilai, 1);
             $anggota_kelas->rt_nilai = round($rt_nilai, 1);
         }
     
         $data_anggota_kelas = $data_anggota_kelas->sortByDesc('rt_nilai')->values();
     
         $peringkat = 1;
         $peringkatSebelumnya = null;
         $counter = 1;
     
         foreach ($data_anggota_kelas as $anggota_kelas) {
             if ($anggota_kelas->rt_nilai !== $peringkatSebelumnya) {
                 $peringkat = $counter;
             }
             $anggota_kelas->peringkat = $peringkat;
             $peringkatSebelumnya = $anggota_kelas->rt_nilai;
             $counter++;
         }
     
         // Render view ke string HTML
         $html = view('admin.legernilai.cetak', compact(
             'title',
             'kelas',
             'tapel',
             'data_mapel_wajib',
             'data_mapel_pilihan',
             'data_mapel_muatan_lokal',
             'data_anggota_kelas'
         ))->render();
     
         // Setup mPDF
         $mpdf = new Mpdf([
             'mode' => 'utf-8',
             'format' => 'A4-L', // Landscape orientation
             'margin_left' => 5,
             'margin_right' => 5,
             'margin_top' => 15,
             'margin_bottom' => 15,
             'margin_header' => 5,
             'margin_footer' => 5,
             'default_font' => 'arial'
         ]);
     
         // Add CSS styling
         $stylesheet = '
             <style>
                 body {
                     font-family: Arial, sans-serif;
                     font-size: 9pt;
                 }
                 .table {
                     width: 100%;
                     border-collapse: collapse;
                     page-break-inside: auto;
                 }
                 .table th, .table td {
                     border: 1px solid #000;
                     padding: 4px;
                     text-align: center;
                 }
                 .table th {
                     background-color: #d4edda;
                     font-weight: bold;
                 }
                 .text-center {
                     text-align: center;
                 }
                 .bg-success {
                     background-color: #d4edda !important;
                 }
                 .header {
                     text-align: center;
                     margin-bottom: 10px;
                 }
                 .header h2 {
                     margin: 0;
                     padding: 0;
                     font-size: 14pt;
                 }
                 .header h3 {
                     margin: 0;
                     padding: 0;
                     font-size: 12pt;
                 }
                 tr {
                     page-break-inside: avoid;
                 }
             </style>
         ';
         
         $mpdf->WriteHTML($stylesheet, 1);
         $mpdf->WriteHTML($html);
         
         // Output PDF
         $filename = 'Leger_Nilai_' . $kelas->nama_kelas . '.pdf';
         return Response::make($mpdf->Output($filename, 'I'), 200, [
             'Content-Type' => 'application/pdf',
         ]);
     }


}
