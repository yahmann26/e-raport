<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKelas;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\santri;
use App\Models\Tapel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tapel = Tapel::findorfail(session()->get('tapel_id'));

        $title = 'Data Kelas';
        $data_kelas = Kelas::where('tapel_id', $tapel->id)->orderBy('tingkatan_kelas', 'ASC')->get();
        foreach ($data_kelas as $kelas) {
            $jumlah_anggota = santri::where('kelas_id', $kelas->id)->count();
            $kelas->jumlah_anggota = $jumlah_anggota;
        }
        $data_guru = Guru::orderBy('nama_lengkap', 'ASC')->get();
        return view('admin.kelas.index', compact('title', 'data_kelas', 'tapel', 'data_guru'));
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
            'tingkatan_kelas' => 'required|numeric|digits_between:1,2',
            'nama_kelas' => 'required|min:1|max:30',
            'guru_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        } else {
            $tapel = Tapel::findorfail(session()->get('tapel_id'));
            $kelas = new Kelas([
                'tapel_id' => $tapel->id,
                'guru_id' => $request->guru_id,
                'tingkatan_kelas' => $request->tingkatan_kelas,
                'nama_kelas' => $request->nama_kelas,
            ]);
            $kelas->save();
            return back()->with('toast_success', 'Kelas berhasil ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = 'Anggota Kelas';
        $kelas = Kelas::findOrFail($id);

        $tapel_id = session('tapel_id');

        // Anggota kelas pada kelas ini
        $anggota_kelas = AnggotaKelas::join('santri', 'anggota_kelas.santri_id', '=', 'santri.id')
            ->orderBy('santri.nama_lengkap', 'ASC')
            ->where('anggota_kelas.kelas_id', $id)
            ->get();

        // Santri yang status aktif (1), belum punya kelas untuk tapel ini
        // Artinya belum ada di anggota_kelas pada tapel_id ini
        $santri_belum_masuk_kelas = Santri::where('status', 1)
            ->whereDoesntHave('anggota_kelas', function ($query) use ($tapel_id) {
                $query->whereHas('kelas', function ($q) use ($tapel_id) {
                    $q->where('tapel_id', $tapel_id);
                });
            })
            ->get();

        // Cari kelas sebelumnya untuk tiap santri belum masuk kelas
        foreach ($santri_belum_masuk_kelas as $belum_masuk_kelas) {
            $kelas_sebelumnya = AnggotaKelas::where('santri_id', $belum_masuk_kelas->id)
                ->orderBy('id', 'DESC')
                ->first();

            $belum_masuk_kelas->kelas_sebelumhya = $kelas_sebelumnya
                ? $kelas_sebelumnya->kelas->nama_kelas
                : null;
        }

        return view('admin.kelas.show', compact('title', 'kelas', 'anggota_kelas', 'santri_belum_masuk_kelas'));
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
            'nama_kelas' => 'required|min:1|max:30',
            'guru_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        } else {
            $kelas = Kelas::findorfail($id);
            $data_kelas = [
                'nama_kelas' => $request->nama_kelas,
                'guru_id' => $request->guru_id,
            ];
            $kelas->update($data_kelas);
            return back()->with('toast_success', 'Kelas berhasil diedit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kelas = Kelas::findorfail($id);
        try {
            $kelas->delete();
            return back()->with('toast_success', 'Kelas berhasil dihapus');
        } catch (\Throwable $th) {
            return back()->with('toast_warning', 'Kosongkan anggota kelas terlebih dahulu');
        }
    }

    public function store_anggota(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'santri_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('toast_warning', 'Tidak ada santri yang dipilih');
        }

        // Ambil kelas dari ID kelas yang dikirim
        $kelas = Kelas::findOrFail($request->kelas_id);

        $tapel_id = $kelas->tapel_id;  // ambil tapel_id dari kelas

        $santri_id = $request->input('santri_id');
        $insert_data = [];

        foreach ($santri_id as $id) {
            // Cek jika santri sudah masuk kelas di tahun pelajaran yang sama
            $sudah_ada = AnggotaKelas::where('santri_id', $id)
                ->whereHas('kelas', function ($query) use ($tapel_id) {
                    $query->where('tapel_id', $tapel_id);
                })
                ->exists();

            if (!$sudah_ada) {
                $insert_data[] = [
                    'santri_id' => $id,
                    'kelas_id' => $request->kelas_id,
                    'pendaftaran' => $request->pendaftaran,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        if (!empty($insert_data)) {
            AnggotaKelas::insert($insert_data);

            santri::whereIn('id', array_column($insert_data, 'santri_id'))
                ->update(['kelas_id' => $request->kelas_id]);

            return back()->with('toast_success', 'Anggota kelas berhasil ditambahkan');
        } else {
            return back()->with('toast_info', 'Semua santri sudah berada di kelas pada tahun pelajaran ini.');
        }
    }



    public function delete_anggota($id)
    {
        try {
            $anggota_kelas = AnggotaKelas::findorfail($id);
            $santri = santri::findorfail($anggota_kelas->santri_id);

            $update_kelas_id = [
                'kelas_id' => null,
            ];
            $anggota_kelas->delete();
            $santri->update($update_kelas_id);
            return back()->with('toast_success', 'Anggota kelas berhasil dihapus');
        } catch (\Throwable $th) {
            return back()->with('toast_error', 'Anggota kelas tidak dapat dihapus');
        }
    }
}
