<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PembelajaranExport;
use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Pembelajaran;
use App\Models\Tapel;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;

class PembelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tapel = Tapel::findorfail(session()->get('tapel_id'));
        $data_mapel = Mapel::where('tapel_id', $tapel->id)->orderBy('nama_mapel', 'ASC')->get();
        $data_kelas = Kelas::where('tapel_id', $tapel->id)->orderBy('tingkatan_kelas', 'ASC')->get();

        if (count($data_mapel) == 0) {
            return redirect('admin/mapel')->with('toast_warning', 'Mohon isikan data mata pelajaran');
        } elseif (count($data_kelas) == 0) {
            return redirect('admin/kelas')->with('toast_warning', 'Mohon isikan data kelas');
        } else {
            $title = 'Data Pembelajaran';
            $id_kelas = Kelas::where('tapel_id', $tapel->id)->orderBy('tingkatan_kelas', 'ASC')->get('id');
            $data_pembelajaran = Pembelajaran::whereIn('kelas_id', $id_kelas)->whereNotNull('guru_id')->where('status', 1)->orderBy('kelas_id', 'ASC')->get();
            return view('admin.pembelajaran.index', compact('title', 'data_kelas', 'data_pembelajaran'));
        }
    }

    /**
     * Menampilkan halaman settings pembelajaran (GET)
     */
    public function showSettings(Request $request)
    {
        $title = 'Setting Pembelajaran';
        $tapel = Tapel::findOrFail(session()->get('tapel_id'));

        // Default kelas_id jika tidak ada request
        $kelas_id = $request->kelas_id ?? Kelas::where('tapel_id', $tapel->id)
            ->orderBy('tingkatan_kelas', 'ASC')
            ->first()->id;

        $kelas = Kelas::findOrFail($kelas_id);

        // Ambil semua kelas yang terkait dengan tapel
        $data_kelas = Kelas::where('tapel_id', $tapel->id)
            ->orderBy('tingkatan_kelas', 'ASC')
            ->get();

        // Ambil data pembelajaran aktif di kelas yang dipilih
        $data_pembelajaran_kelas = Pembelajaran::where('kelas_id', $kelas_id)
            ->where('status', 1)
            ->with('mapel', 'guru', 'kelas')
            ->get();

        // Ambil semua mapel yang tersedia di tapel ini
        $all_mapel = Mapel::where('tapel_id', $tapel->id)->get();

        // Filter mapel yang belum aktif di KELAS LAIN (selain kelas yang dipilih)
        // Tapi BOLEH sudah aktif di kelas yang dipilih
        $data_mapel_tersedia = collect();

        foreach ($all_mapel as $mapel) {
            // Cek apakah mapel sudah aktif di kelas lain (selain kelas yang dipilih)
            $aktif_di_kelas_lain = Pembelajaran::where('mapel_id', $mapel->id)
                ->where('kelas_id', '!=', $kelas_id) // Kelas yang bukan kelas dipilih
                ->where('status', 1)
                ->exists();

            // Cek apakah mapel sudah aktif di kelas yang dipilih
            $aktif_di_kelas_dipilih = Pembelajaran::where('mapel_id', $mapel->id)
                ->where('kelas_id', $kelas_id)
                ->where('status', 1)
                ->exists();

            // Mapel akan ditampilkan sebagai tersedia jika:
            // 1. BELUM aktif di kelas lain manapun, ATAU
            // 2. SUDAH aktif di kelas yang dipilih (meskipun sudah aktif di kelas ini)
            if (!$aktif_di_kelas_lain || $aktif_di_kelas_dipilih) {
                $data_mapel_tersedia->push($mapel);
            }
        }

        // Untuk form tambah mapel baru, kita butuh mapel yang BELUM aktif di kelas yang dipilih
        $data_mapel_belum_aktif = $data_mapel_tersedia->filter(function ($mapel) use ($kelas_id) {
            $aktif_di_kelas_dipilih = Pembelajaran::where('mapel_id', $mapel->id)
                ->where('kelas_id', $kelas_id)
                ->where('status', 1)
                ->exists();

            return !$aktif_di_kelas_dipilih;
        });

        // Ambil semua guru untuk pemilihan
        $data_guru = Guru::orderBy('nama_lengkap', 'ASC')->get();

        // Kirim data ke view
        return view('admin.pembelajaran.settings', compact(
            'title',
            'tapel',
            'kelas',
            'data_kelas',
            'data_pembelajaran_kelas',
            'data_mapel_tersedia',
            'data_mapel_belum_aktif',
            'data_guru'
        ));
    }

    /**
     * Memproses form seleksi kelas (POST)
     */
    public function processSettings(Request $request)
    {
        // Validasi input
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id'
        ]);

        // Redirect ke halaman settings dengan parameter kelas_id
        return redirect()->route('pembelajaran.settings.show', ['kelas_id' => $request->kelas_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Proses update data pembelajaran yang sudah ada
        if (!is_null($request->pembelajaran_id)) {
            for ($count = 0; $count < count($request->pembelajaran_id); $count++) {
                $pembelajaran = Pembelajaran::findorfail($request->pembelajaran_id[$count]);

                // Jika status diubah menjadi 0, hapus data
                if ($request->update_status[$count] == 0) {
                    $pembelajaran->delete();
                } else {
                    // Jika status tetap 1, update data
                    $update_data = array(
                        'guru_id' => $request->update_guru_id[$count],
                        'status' => $request->update_status[$count],
                    );
                    $pembelajaran->update($update_data);
                }
            }
        }

        // Proses tambah data pembelajaran baru
        if (!is_null($request->mapel_id)) {
            $kelas = Kelas::find($request->kelas_id[0]);

            for ($count = 0; $count < count($request->mapel_id); $count++) {
                // Hanya simpan jika status aktif (1)
                if ($request->status[$count] == 1) {
                    $data_baru = array(
                        'kelas_id' => $request->kelas_id[$count],
                        'mapel_id' => $request->mapel_id[$count],
                        'guru_id' => $request->guru_id[$count],
                        'status' => $request->status[$count],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    );
                    $store_data_baru[] = $data_baru;
                }
            }

            if (!empty($store_data_baru)) {
                Pembelajaran::insert($store_data_baru);
            }
        }

        return redirect('admin/pembelajaran')->with('toast_success', 'Setting pembelajaran berhasil');
    }

    // public function export()
    // {
    //     $filename = 'data_pembelajaran ' . date('Y-m-d H_i_s') . '.xls';
    //     return Excel::download(new PembelajaranExport, $filename);
    // }
}
