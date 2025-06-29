<?php

namespace App\Exports;

use App\Models\Guru;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GuruExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $time_download = date('Y-m-d H:i:s');

        $data_guru = Guru::orderBy('nama_lengkap', 'ASC')->get();

        return view('admin.guru.export', compact('time_download', 'data_guru'));
    }
}
