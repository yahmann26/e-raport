<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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
    public function edit( Request $request)
    {
        $title = 'Profile';
        $admin = Admin::where('user_id', Auth::user()->id)->first();
        return view('admin.profile.index', compact('admin', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::findorfail($id);

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|min:3|max:100',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required',
            'email' => 'required|email|min:5|max:100|unique:admin,email,' . $admin->id,
            'nomor_hp' => 'required|numeric|digits_between:11,13|unique:admin,nomor_hp,' . $admin->id,
            'avatar' => 'max:2048|image',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        } else {
            if ($request->has('avatar')) {
                $avatar_file = $request->file('avatar');
                $name_avatar = 'profile_' . strtolower($request->nama_lengkap) . '.' . $avatar_file->getClientOriginalExtension();
                $avatar_file->move('assets/dist/img/avatar/', $name_avatar);

                $data = [
                    'nama_lengkap' => strtoupper($request->nama_lengkap),
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'email' => $request->email,
                    'nomor_hp' => $request->nomor_hp,
                    'avatar' => $name_avatar,
                ];
            } else {
                $data = [
                    'nama_lengkap' => strtoupper($request->nama_lengkap),
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'email' => $request->email,
                    'nomor_hp' => $request->nomor_hp,
                ];
            }
            $admin->update($data);
            return back()->with('toast_success', 'Profile anda berhasil diedit');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
