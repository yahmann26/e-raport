<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\RiwayatLogin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        $data_riwayat_login = RiwayatLogin::where('user_id', '!=', Auth::user()->id)->where('updated_at', '>=', Carbon::today())->orderBy('status_login', 'DESC')->orderBy('updated_at', 'DESC')->get();


        if(Auth::user()->role == 1) {
            $jumlah_guru = Guru::all()->count();

            return view('admin.dashboard', compact(
                'title',
                'jumlah_guru',
                'data_riwayat_login'
            ));
        }
    }
}
