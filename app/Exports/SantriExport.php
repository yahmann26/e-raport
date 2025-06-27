<?php

namespace App\Exports;

use App\Models\santri;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class santriExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $time_download = date('Y-m-d H:i:s');

        $data_santri = Santri::where('status', 1)->orderBy('nis', 'ASC')->get();

        return view('exports.santri', compact('time_download', 'data_santri'));
    }
}
