<?php
function terbilang($angka)
{
    $angka = abs($angka);
    $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");

    $hasil = "";

    // Pisahkan angka menjadi integer dan desimal
    $pecahan = explode('.', number_format($angka, 2, '.', ''));

    // Bagian bulat
    $bilangan = (int)$pecahan[0];
    if ($bilangan < 12) {
        $hasil .= " " . $baca[$bilangan];
    } else if ($bilangan < 20) {
        $hasil .= terbilang($bilangan - 10) . " Belas";
    } else if ($bilangan < 100) {
        $hasil .= terbilang($bilangan / 10) . " Puluh" . terbilang($bilangan % 10);
    } else if ($bilangan < 200) {
        $hasil .= " Seratus" . terbilang($bilangan - 100);
    } else if ($bilangan < 1000) {
        $hasil .= terbilang($bilangan / 100) . " Ratus" . terbilang($bilangan % 100);
    } else if ($bilangan < 2000) {
        $hasil .= " Seribu" . terbilang($bilangan - 1000);
    } else if ($bilangan < 1000000) {
        $hasil .= terbilang($bilangan / 1000) . " Ribu" . terbilang($bilangan % 1000);
    } else if ($bilangan < 1000000000) {
        $hasil .= terbilang($bilangan / 1000000) . " Juta" . terbilang($bilangan % 1000000);
    }

    // Bagian desimal
    if (isset($pecahan[1]) && (int)$pecahan[1] > 0) {
        $hasil .= " Koma";
        $digits = str_split(rtrim($pecahan[1], '0')); // hapus nol di akhir desimal
        foreach ($digits as $digit) {
            $hasil .= " " . $baca[$digit];
        }
    }

    return trim($hasil);
}
