<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pondok;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PondokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Profil Pondok';
        $pondok = Pondok::first();
        return view('admin.pondok.index', compact('title', 'pondok'));
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
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_pondok' => 'required|min:5|max:100',
            'npsn' => 'required|numeric|digits_between:8,10',
            'nss' => 'nullable|numeric|digits:15',
            'alamat' => 'required|min:10|max:255',
            'kode_pos' => 'required|numeric|digits:5',
            'nomor_telpon' => 'required|numeric|digits_between:5,13',
            'website' => 'nullable|min:5|max:100',
            'email' => 'required|email|min:5|max:35',
            'kepala_pondok' => 'required|min:3|max:100',
            'nip_kepala_pondok' => 'nullable|digits:18',
            'logo' => 'nullable|image|max:2048',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return back()
                ->with('toast_error', $validator->messages()->first())
                ->withInput();
        }

        try {
            $pondok = Pondok::findOrFail($id);

            // Siapkan data yang akan diupdate
            $data_pondok = [
                'nama_pondok' => strtoupper($request->nama_pondok),
                'npsn' => $request->npsn,
                'nss' => $request->nss,
                'alamat' => $request->alamat,
                'kode_pos' => $request->kode_pos,
                'email' => $request->email,
                'nomor_telpon' => $request->nomor_telpon,
                'website' => $request->website,
                'kepala_pondok' => strtoupper($request->kepala_pondok),
                'nip_kepala_pondok' => $request->nip_kepala_pondok,
            ];

            // Jika ada file logo baru
            if ($request->hasFile('logo')) {
                // Hapus file logo lama jika ada
                if ($pondok->logo && file_exists(public_path('assets/images/logo/' . $pondok->logo))) {
                    @unlink(public_path('assets/images/logo/' . $pondok->logo));
                }

                // Simpan file logo baru
                $logo_file = $request->file('logo');
                $name_logo = 'logo_' . time() . '.' . $logo_file->getClientOriginalExtension();
                $logo_file->move(public_path('assets/images/logo/'), $name_logo);

                $data_pondok['logo'] = $name_logo;
            }

            // Update data pondok
            $pondok->update($data_pondok);

            dd($data_pondok, $pondok->toArray());


            return back()->with('toast_success', 'Data pondok berhasil diedit');
        } catch (\Exception $e) {
            // Tangani error
            return back()->with('toast_error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
}
