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
use App\Models\TglRaport;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Response;

class LegerSantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Leger Nilai sant';
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
        $title = 'Leger Nilai sant';
        $tapel = Tapel::findOrFail(session()->get('tapel_id'));
        $kelas = Kelas::findOrFail($request->kelas_id);
        $data_kelas = Kelas::where('tapel_id', session()->get('tapel_id'))->get();

        $data_id_mapel_semester_ini = Mapel::where('tapel_id', $tapel->id)->pluck('id');

        $data_id_pembelajaran_all = Pembelajaran::where('kelas_id', $kelas->id)->pluck('id');

        $id_nilai_mapel = NilaiAkhirRaport::selectRaw('MAX(id) as id')
            ->whereIn('pembelajaran_id', $data_id_pembelajaran_all)
            ->groupBy('pembelajaran_id')
            ->pluck('id');

        $data_mapel = NilaiAkhirRaport::whereIn('id', $id_nilai_mapel)->get();

        $data_anggota_kelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();

        foreach ($data_anggota_kelas as $anggota_kelas) {
            $data_nilai_mapel = NilaiAkhirRaport::whereIn('pembelajaran_id', $data_id_pembelajaran_all)
                ->where('anggota_kelas_id', $anggota_kelas->id)
                ->get();

            $anggota_kelas->data_nilai_mapel = $data_nilai_mapel;

            $jumlah_nilai = NilaiAkhirRaport::where('anggota_kelas_id', $anggota_kelas->id)->sum('nilai_akhir');
            $rt_nilai = NilaiAkhirRaport::where('anggota_kelas_id', $anggota_kelas->id)->avg('nilai_akhir');

            $anggota_kelas->jumlah_nilai = round($jumlah_nilai, 1);
            $anggota_kelas->rt_nilai = round($rt_nilai, 1);
        }

        // Hitung peringkat berdasarkan rt_nilai tertinggi
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
            'data_mapel',
            'data_anggota_kelas'
        ));
    }

    public function cetak(Request $request, $kelas_id)
    {
        $title = 'Leger Nilai Santri';
        $tapel = Tapel::findOrFail(session()->get('tapel_id'));
        $kelas = Kelas::findOrFail($kelas_id);
        $data_kelas = Kelas::where('tapel_id', session()->get('tapel_id'))->get();

        // Ambil semua id mapel semester ini
        $data_id_mapel_semester_ini = Mapel::where('tapel_id', $tapel->id)->pluck('id');

        // Ambil semua id pembelajaran di kelas ini
        $data_id_pembelajaran_all = Pembelajaran::where('kelas_id', $kelas->id)->pluck('id');

        // Ambil nilai akhir terakhir dari tiap pembelajaran
        $id_nilai_mapel = NilaiAkhirRaport::selectRaw('MAX(id) as id')
            ->whereIn('pembelajaran_id', $data_id_pembelajaran_all)
            ->groupBy('pembelajaran_id')
            ->pluck('id');

        $data_mapel = NilaiAkhirRaport::whereIn('id', $id_nilai_mapel)->get();

        // Ambil semua anggota kelas dengan relasi santri
        $data_anggota_kelas = AnggotaKelas::with('santri', 'kelas.guru')
            ->where('kelas_id', $kelas->id)
            ->get();

        $guru = Kelas::with('guru')->findOrFail($kelas->id);

        $tanggal_raport = TglRaport::where('tapel_id', session()->get('tapel_id'))->first();


        // Loop setiap anggota kelas untuk mengambil nilai dan hitung total & rata-rata
        foreach ($data_anggota_kelas as $anggota_kelas) {
            $data_nilai_mapel = NilaiAkhirRaport::whereIn('pembelajaran_id', $data_id_pembelajaran_all)
                ->where('anggota_kelas_id', $anggota_kelas->id)
                ->get();

            $anggota_kelas->data_nilai_mapel = $data_nilai_mapel;

            $jumlah_nilai = $data_nilai_mapel->sum('nilai_akhir');
            $rt_nilai = $data_nilai_mapel->avg('nilai_akhir');

            $anggota_kelas->jumlah_nilai = round($jumlah_nilai, 1);
            $anggota_kelas->rt_nilai = round($rt_nilai, 1);
        }

        // Hitung peringkat berdasarkan rt_nilai tertinggi
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

        // Render view ke HTML
        $html = view('admin.legernilai.cetak', compact(
            'title',
            'kelas',
            'tapel',
            'guru',
            'tanggal_raport',
            'data_mapel',
            'data_anggota_kelas'
        ))->render();

        // Setup mPDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => [330, 210], // Landscape F4 (Folio)
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 1,
            'margin_bottom' => 1,
            'margin_header' => 1,
            'margin_footer' => 1,
            'default_font' => 'arial',
            'tempDir' => storage_path('app/mpdf-temp'), 
        ]);

        // Tambahkan CSS
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
        $filename = 'Daftar_Nilai_' . $kelas->nama_kelas . '.pdf';
        return Response::make($mpdf->Output($filename, 'I'), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
