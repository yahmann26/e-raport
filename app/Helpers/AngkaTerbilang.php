<?php
function terbilang($angka)
{
    $angka = abs($angka);
    $baca = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
    $hasil = "";

    // Pisahkan angka menjadi bilangan bulat dan desimal (maks 2 digit desimal)
    $pecahan = explode('.', number_format($angka, 2, '.', ''));

    // Bagian bilangan bulat
    $bilangan = (int)$pecahan[0];

    if ($bilangan < 12) {
        $hasil .= " " . $baca[$bilangan];
    } elseif ($bilangan < 20) {
        $hasil .= terbilang($bilangan - 10) . " Belas";
    } elseif ($bilangan < 100) {
        $hasil .= terbilang(floor($bilangan / 10)) . " Puluh";
        $sisa = $bilangan % 10;
        if ($sisa != 0) {
            $hasil .= " " . terbilang($sisa);
        }
    } elseif ($bilangan < 200) {
        $hasil .= " Seratus";
        $sisa = $bilangan - 100;
        if ($sisa != 0) {
            $hasil .= " " . terbilang($sisa);
        }
    } elseif ($bilangan < 1000) {
        $hasil .= terbilang(floor($bilangan / 100)) . " Ratus";
        $sisa = $bilangan % 100;
        if ($sisa != 0) {
            $hasil .= " " . terbilang($sisa);
        }
    } elseif ($bilangan < 2000) {
        $hasil .= " Seribu";
        $sisa = $bilangan - 1000;
        if ($sisa != 0) {
            $hasil .= " " . terbilang($sisa);
        }
    } elseif ($bilangan < 1000000) {
        $hasil .= terbilang(floor($bilangan / 1000)) . " Ribu";
        $sisa = $bilangan % 1000;
        if ($sisa != 0) {
            $hasil .= " " . terbilang($sisa);
        }
    } elseif ($bilangan < 1000000000) {
        $hasil .= terbilang(floor($bilangan / 1000000)) . " Juta";
        $sisa = $bilangan % 1000000;
        if ($sisa != 0) {
            $hasil .= " " . terbilang($sisa);
        }
    }

    // Bagian desimal
    if (isset($pecahan[1]) && (int)$pecahan[1] > 0) {
        $hasil .= " Koma";
        $desimal = rtrim($pecahan[1], '0'); // hilangkan nol di akhir
        $digits = str_split($desimal);
        foreach ($digits as $digit) {
            $hasil .= " " . $baca[(int)$digit];
        }
    }

    return trim($hasil);
}
