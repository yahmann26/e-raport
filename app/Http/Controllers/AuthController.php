<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tapel;
use App\Models\RiwayatLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        $data_tapel = Tapel::orderBy('id', 'DESC')->get();
        if (count($data_tapel) == 0) {
            $title = 'Setting Tahun Pelajaran';
            return view('auth.setting_tapel', compact('title'));
        } else {
            $title = 'Login';
            return view('auth.login', compact('title', 'data_tapel'));
        }
    }

    public function setting_tapel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun_pelajaran' => 'required|min:9|max:9',
            'semester' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        } else {
            $tapel = new Tapel([
                'tahun_pelajaran' => $request->tahun_pelajaran,
                'semester' => $request->semester,
            ]);
            $tapel->save();
            return back()->with('toast_success', 'Regitrasi berhasil');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|exists:user',
            'password' => 'required|min:6',
            // 'kurikulum' => 'required',
            'tahun_pelajaran' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        } else {
            $user_login = User::where('username', $request->username)->first();
            if (!Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                return back()->with('toast_error', 'password salah.');
            } elseif ($user_login->status == false) {
                return back()->with('toast_error', 'User ' . $user_login->username . ' telah dinonaktifkan');
            } else {
                $cek_riwayat = RiwayatLogin::where('user_id', Auth::id())->first();
                if (is_null($cek_riwayat)) {
                    $riwayat_login = new RiwayatLogin([
                        'user_id' => Auth::id(),
                        'status_login' => true
                    ]);
                    $riwayat_login->save();
                } else {
                    $cek_riwayat->update(['status_login' => true]);
                }
                session([
                    // 'kurikulum' => $request->kurikulum,
                    'tapel_id' => $request->tahun_pelajaran,
                ]);

                if (Auth::user()->role == 1) {
                    return redirect()->route('admin.dashboard')->with('toast_success', 'Login berhasil');
                } elseif (Auth::user()->role == 2) {
                    return redirect()->route('guru.dashboard')->with('toast_success', 'Login berhasil');
                }

                // return redirect('/dashboard')->with('toast_success', 'Login berhasil');
            }
        }
    }

    public function logout(Request $request)
    {
        RiwayatLogin::where('user_id', Auth::id())->update([
            'status_login' => false
        ]);
        $request->session()->flush();
        Auth::logout();
        return redirect('/')->with('toast_success', 'Logout berhasil');
    }
}
