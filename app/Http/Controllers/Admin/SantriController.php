<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Tapel;
use App\Models\Santri;
use App\Models\AnggotaKelas;
use App\Models\santriKeluar;
use Illuminate\Http\Request;
use App\Exports\santriExport;
use App\Imports\santriImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Alignment as StyleAlignment;
use PhpOffice\PhpSpreadsheet\Style\Border as StyleBorder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SantriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Data Santri';
        $tapel = Tapel::findOrFail(session()->get('tapel_id'));
        $jumlah_kelas = Kelas::where('tapel_id', $tapel->id)->count();

        $tingkatan_terendah = null;
        $tingkatan_akhir = null;
        $data_kelas_terendah = collect();
        $data_kelas_all = collect();

        if ($jumlah_kelas > 0) {
            $tingkatan_terendah = Kelas::where('tapel_id', $tapel->id)->min('tingkatan_kelas');
            $tingkatan_akhir = Kelas::where('tapel_id', $tapel->id)->max('tingkatan_kelas');

            $data_kelas_terendah = Kelas::where('tapel_id', $tapel->id)
                ->where('tingkatan_kelas', $tingkatan_terendah)
                ->orderBy('nama_kelas', 'ASC')
                ->get();

            $data_kelas_all = Kelas::where('tapel_id', $tapel->id)
                ->orderBy('tingkatan_kelas', 'ASC')
                ->get();
        }

        // Ambil semua santri tanpa filter whereHas
        $data_santri = Santri::with(['anggota_kelas.kelas' => function ($query) use ($tapel) {
            $query->where('tapel_id', $tapel->id);
        }])
            ->orderBy('nis', 'DESC')
            ->get();

        return view('admin.santri.index', compact('title', 'data_kelas_all', 'data_kelas_terendah', 'data_santri', 'tingkatan_akhir', 'tapel'));
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
            'nama_lengkap' => 'required|min:3|max:100',
            'jenis_kelamin' => 'required',
            'jenis_pendaftaran' => 'required',
            'kelas_id' => 'required',
            'nis' => 'required|numeric|digits_between:1,10|unique:santri',
            'nisn' => 'nullable|numeric|digits:10|unique:santri',
            'tempat_lahir' => 'required|min:3|max:50',
            'tanggal_lahir' => 'required',
            'anak_ke' => 'required|numeric|digits_between:1,2',
            'status_dalam_keluarga' => 'required',
            'alamat' => 'required|min:3|max:255',
            'nomor_hp' => 'nullable|numeric|digits_between:11,13',
            'nama_ayah' => 'required|min:3|max:100',
            'nama_ibu' => 'required|min:3|max:100',
            'pekerjaan_ayah' => 'required|min:3|max:100',
            'pekerjaan_ibu' => 'required|min:3|max:100',
            'nama_wali' => 'nullable|min:3|max:100',
            'pekerjaan_wali' => 'nullable|min:3|max:100',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('toast_error', $validator->messages()->all()[0])
                ->withInput();
        }

        try {
            $user = new User([
                'username' => strtolower(str_replace(' ', '', $request->nama_lengkap . $request->nis)),
                'password' => bcrypt('123456'),
                'role' => 3,
                'status' => true,
            ]);
            $user->save();
        } catch (\Throwable $th) {
            return back()->with('toast_error', 'Username telah digunakan');
        }

        $santri = new santri([
            'user_id' => $user->id,
            'kelas_id' => $request->kelas_id,
            'jenis_pendaftaran' => $request->jenis_pendaftaran,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'nama_lengkap' => strtoupper($request->nama_lengkap),
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'status_dalam_keluarga' => $request->status_dalam_keluarga,
            'anak_ke' => $request->anak_ke,
            'alamat' => $request->alamat,
            'nomor_hp' => $request->nomor_hp,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'nama_wali' => $request->nama_wali,
            'pekerjaan_wali' => $request->pekerjaan_wali,
            'avatar' => 'default.png',
            'status' => 1,
        ]);
        $santri->save();

        $anggota_kelas = new AnggotaKelas([
            'santri_id' => $santri->id,
            'kelas_id' => $request->kelas_id,
            'pendaftaran' => $request->jenis_pendaftaran,
        ]);
        $anggota_kelas->save();

        return back()->with('toast_success', 'Santri berhasil ditambahkan');
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
        $santri = santri::findorfail($id);
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|min:3|max:100',
            'jenis_kelamin' => 'required',
            'nis' => 'required|numeric|digits_between:1,10|unique:santri,nis,' . $santri->id,
            'nisn' => 'nullable|numeric|digits:10|unique:santri,nisn,' . $santri->id,
            'tempat_lahir' => 'required|min:3|max:50',
            'tanggal_lahir' => 'required',
            // 'agama' => 'required',
            'anak_ke' => 'required|numeric|digits_between:1,2',
            'status_dalam_keluarga' => 'required',
            'alamat' => 'required|min:3|max:255',
            'nomor_hp' => 'nullable|numeric|digits_between:11,13|unique:santri,nomor_hp,' . $santri->id,

            'nama_ayah' => 'required|min:3|max:100',
            'nama_ibu' => 'required|min:3|max:100',
            'pekerjaan_ayah' => 'required|min:3|max:100',
            'pekerjaan_ibu' => 'required|min:3|max:100',
            'nama_wali' => 'nullable|min:3|max:100',
            'pekerjaan_wali' => 'nullable|min:3|max:100',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('toast_error', $validator->messages()->all()[0])
                ->withInput();
        } else {
            $data_santri = [
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'nama_lengkap' => strtoupper($request->nama_lengkap),
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                // 'agama' => $request->agama,
                'status_dalam_keluarga' => $request->status_dalam_keluarga,
                'anak_ke' => $request->anak_ke,
                'alamat' => $request->alamat,
                'nomor_hp' => $request->nomor_hp,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'nama_wali' => $request->nama_wali,
                'pekerjaan_wali' => $request->pekerjaan_wali,
            ];
            $santri->update($data_santri);
            return back()->with('toast_success', 'santri berhasil diedit');
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
        $data_santri = santri::findorfail($id);
        $data_user = User::findorfail($data_santri->user_id);

        $data_anggota_kelas = AnggotaKelas::where('santri_id', $data_santri->id)->get();
        if ($data_anggota_kelas->count() == 0) {
            $data_santri->delete();
            $data_user->delete();
            return back()->with('toast_success', 'santri berhasil dihapus');
        } elseif ($data_anggota_kelas->count() == 1) {
            try {
                $anggota_kelas = AnggotaKelas::where('santri_id', $data_santri->id)->first();
                $anggota_kelas->delete();
                $data_santri->delete();
                $data_user->delete();
                return back()->with('toast_success', 'santri berhasil dihapus');
            } catch (\Throwable $th) {
                return back()->with('toast_error', 'Data santri tidak dapat dihapus');
            }
        } else {
            return back()->with('toast_error', 'Data santri tidak dapat dihapus');
        }
    }

    public function export()
    {
        $filename = 'data_santri ' . date('Y-m-d H_i_s') . '.xls';
        return Excel::download(new santriExport(), $filename);
    }

    public function format_import()
    {
        $file = public_path() . '/format_import/format_import_santri.xls';
        $headers = ['Content-Type: application/xls'];
        return Response::download($file, 'format_import_santri ' . date('Y-m-d H_i_s') . '.xls', $headers);
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new santriImport(), $request->file('file_import'));
            return back()->with('toast_success', 'Data santri berhasil diimport');
        } catch (\Throwable $th) {
            return back()->with('toast_error', 'Maaf, format data tidak sesuai');
        }
    }

    public function registrasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'santri_id' => 'required',
            'keluar_karena' => 'required|max:30',
            'tanggal_keluar' => 'required',
            'alasan_keluar' => 'nullable|max:255',
        ]);
        if ($validator->fails()) {
            return back()
                ->with('toast_error', $validator->messages()->all()[0])
                ->withInput();
        } else {
            $santri_keluar = new santriKeluar([
                'santri_id' => $request->input('santri_id'),
                'keluar_karena' => $request->input('keluar_karena'),
                'tanggal_keluar' => $request->input('tanggal_keluar'),
                'alasan_keluar' => $request->input('alasan_keluar'),
            ]);
            $santri_keluar->save();

            $santri = santri::findorfail($request->santri_id);
            $anggota_kelas = AnggotaKelas::where('santri_id', $santri->id)->where('kelas_id', $santri->kelas_id)->first();
            $anggota_kelas->delete();

            if ($request->keluar_karena == 'Lulus') {
                $update_santri = [
                    'kelas_id' => null,
                    'status' => 3,
                ];
            } else {
                $update_santri = [
                    'kelas_id' => null,
                    'status' => 2,
                ];
            }
            $santri->update($update_santri);
            User::findorfail($santri->user_id)->update(['status' => false]);
            return redirect('admin/santri')->with('toast_success', 'Registrasi santri berhasil');
        }
    }
}
