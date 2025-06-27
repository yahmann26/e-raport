<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tapel;
use App\Models\TglRaport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TglRaportController extends Controller
{
    public function index()
    {
        $title = 'Tanggal Raport';
        $tapel = Tapel::findorfail(session()->get('tapel_id'));
        $data_tgl_raport = TglRaport::where('tapel_id', $tapel->id)->get();
        return view('admin.tgl_raport.index', compact('title', 'tapel', 'data_tgl_raport'));
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
            'tapel_id' => 'required|unique:tgl_raport',
            'tempat_penerbitan' => 'required|min:3|max:50',
            'tanggal_pembagian' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        } else {
            $tgl_raport = new TglRaport([
                'tapel_id' => $request->tapel_id,
                'tempat_penerbitan' => $request->tempat_penerbitan,
                'tanggal_pembagian' => $request->tanggal_pembagian,
            ]);
            $tgl_raport->save();
            return back()->with('toast_success', 'Tanggal raport berhasil ditambahkan');
        }
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
            'tempat_penerbitan' => 'required|min:3|max:50',
            'tanggal_pembagian' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        } else {
            $tgl_raport = TglRaport::findorfail($id);
            $data_tgl_raport = [
                'tempat_penerbitan' => $request->tempat_penerbitan,
                'tanggal_pembagian' => $request->tanggal_pembagian,
            ];
            $tgl_raport->update($data_tgl_raport);
            return back()->with('toast_success', 'Tanggal raport  berhasil diedit');
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
        $tgl_raport = TglRaport::findorfail($id);
        try {
            $tgl_raport->delete();
            return back()->with('toast_success', 'Tanggal raport berhasil dihapus');
        } catch (\Throwable $th) {
            return back()->with('toast_error', 'Tanggal raport tidak dapat dihapus');
        }
    }
}
