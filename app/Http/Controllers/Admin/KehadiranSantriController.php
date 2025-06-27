<?php

namespace App\Http\Controllers\Admin;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Tapel;
use App\Models\Santri;
use App\Models\AnggotaKelas;
use Illuminate\Http\Request;
use App\Models\KehadiranSantri;
use App\Http\Controllers\Controller;

class KehadiranSantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Data Kehadiran';
        $tapel = Tapel::findOrFail(session()->get('tapel_id'));

        $kelas = Kelas::withCount([
            'anggota_kelas as jumlah_anggota_kelas',
            'anggota_kelas as jumlah_telah_dinilai' => function ($query) {
                $query->whereHas('kehadiran'); // hanya anggota yang punya kehadiran
            }
        ])
            ->where('tapel_id', $tapel->id)
            ->get();

        return view('admin.kehadiran.index', compact('title', 'kelas', 'tapel'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $title = 'Input Kehadiran';

        $kelas_id = $request->query('kelas_id');
        $data_anggota_kelas = AnggotaKelas::with(['santri', 'kelas', 'kehadiran'])
            ->where('kelas_id', $kelas_id)
            ->get();

        // dd($data_anggota_kelas);

        return view('admin.kehadiran.create', compact('title', 'data_anggota_kelas'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $anggota_kelas_ids = $request->input('anggota_kelas_id'); // array
        $sakit = $request->input('sakit'); // array
        $izin = $request->input('izin'); // array
        $tanpa_keterangan = $request->input('tanpa_keterangan'); // array

        foreach ($anggota_kelas_ids as $index => $id) {
            // Cek apakah data kehadiran sudah ada untuk anggota_kelas_id ini
            $kehadiran = KehadiranSantri::firstOrNew(['anggota_kelas_id' => $id]);

            $kehadiran->sakit = $sakit[$index] ?? 0;
            $kehadiran->izin = $izin[$index] ?? 0;
            $kehadiran->tanpa_keterangan = $tanpa_keterangan[$index] ?? 0;

            $kehadiran->save();
        }

        return redirect()->route('kehadiran.index')->with('success', 'Data kehadiran berhasil disimpan.');
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
