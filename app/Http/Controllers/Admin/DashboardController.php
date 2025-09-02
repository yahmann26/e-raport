<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\RiwayatLogin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Pondok;
use App\Models\Santri;
use App\Models\Tapel;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        $tapel = Tapel::findorfail(session()->get('tapel_id'));
        $pondok = Pondok::first();
        $data_riwayat_login = RiwayatLogin::where('user_id', '!=', Auth::user()->id)->where('updated_at', '>=', Carbon::today())->orderBy('status_login', 'DESC')->orderBy('updated_at', 'DESC')->get();


        if(Auth::user()->role == 1) {
            $jumlah_guru = Guru::all()->count();
            $jumlah_santri = Santri::all()->count();
            $jumlah_kelas = Kelas::all()->count();
            $jumlah_mapel = Mapel::all()->count();

            return view('admin.dashboard', compact(
                'title',
                'tapel',
                'pondok',
                'jumlah_guru',
                'jumlah_santri',
                'jumlah_kelas',
                'jumlah_mapel',
                'data_riwayat_login'
            ));
        }
    }
}
