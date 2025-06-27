<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2,
        h3 {
            margin-bottom: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .text-left {
            text-align: left;
        }
    </style>
</head>

<body>

    <table style="width: 100%; position: fixed; border: none; border-collapse: collapse;">
        <tr>
            <td style="width: 45%; text-align: left; border: none;">
                <h1>LOGO</h1>
            </td>
            <td style="width: 23%; text-align: left; border: none;">
                <p>NAMA</p>
                <p>NIS</p>
                <p>KELAS</p>
                <p>SEMESTER</p>
                <p>TAHUN PELAJARAN</p>
            </td>
            <td style="width: 2%; text-align: left; border: none;">
                <p>:</p>
                <p>:</p>
                <p>:</p>
                <p>:</p>
                <p>:</p>
            </td>
            <td style="width: 30%; text-align: left; border: none;">
                <p>{{ $anggota->santri->nama_lengkap }}</p>
                <p>{{ $anggota->santri->nis }}</p>
                <p>{{ $anggota->santri->kelas->nama_kelas }}</p>
                <p>{{ $anggota->santri->kelas->tapel->semester }}</p>
                <p>{{ $anggota->kelas->tapel->tahun_pelajaran }}</p>
            </td>
        </tr>
    </table>


    <table
        style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px; margin-bottom: 8px; table-layout: fixed;">
        <thead>
            <tr>
                <th rowspan="2"
                    style="border: 1px solid black; background-color: #e5e7eb; width: 5%; text-align: center; vertical-align: middle;">
                    No</th>
                <th rowspan="2"
                    style="border: 1px solid black; background-color: #e5e7eb; width: 25%; text-align: center; vertical-align: middle;">
                    Mata Pelajaran</th>
                <th colspan="2"
                    style="border: 1px solid black; background-color: #e5e7eb; width: 35%; text-align: center;">Nilai
                </th>
                <th colspan="2"
                    style="border: 1px solid black; background-color: #e5e7eb; width: 35%; text-align: center;">Nilai
                    Rata-rata Kelas</th>
            </tr>
            <tr>
                <th style="border: 1px solid black; background-color: #e5e7eb; width: 10ch; text-align: center;">Angka
                </th>
                <th style="border: 1px solid black; background-color: #e5e7eb; width: 25%; text-align: center;">Huruf
                </th>
                <th style="border: 1px solid black; background-color: #e5e7eb; width: 10%; text-align: center;">Angka
                </th>
                <th style="border: 1px solid black; background-color: #e5e7eb; width: 25%; text-align: center;">Huruf
                </th>
            </tr>
        </thead>
        <tbody>

            {{-- MAPEL WAJIB --}}
            <tr>
                <td colspan="6" style="border: 1px solid black; font-weight: bold; padding-left: 4px;">A. DURUSUL
                    AULA</td>
            </tr>
            @if ($data_nilai_mapel_wajib->isEmpty())
                <tr>
                    <td colspan="6"
                        style="border: 1px solid black; text-align: center; font-style: italic; color: #6b7280;">
                        Tidak ada data
                    </td>
                </tr>
            @else
                @foreach ($data_nilai_mapel_wajib as $i => $nilai)
                    @php
                        $nilai_angka = number_format($nilai->nilai_akhir, 1);
                        $rt_nilai = number_format($nilai->pembelajaran->rata_rata ?? 0, 1);
                    @endphp
                    <tr>
                        <td style="border: 1px solid black; text-align: left;">{{ $i + 1 }}</td>
                        <td style="border: 1px solid black; padding-left: 4px;">
                            {{ $nilai->pembelajaran->mapel->nama_mapel }}</td>
                        <td
                            style="border: 1px solid black; text-align: center; {{ $nilai_angka < 6 ? 'color:#b91c1c; font-style:italic;' : '' }}">
                            {{ str_replace('.', ',', $nilai_angka) }}</td>

                        <td
                            style="border: 1px solid black; padding-left: 4px; {{ $nilai_angka < 6 ? 'color:#b91c1c; font-style:italic;' : '' }}">
                            {{ terbilang($nilai_angka) }}</td>
                        <td
                            style="border: 1px solid black; text-align: center; {{ $rt_nilai < 6 ? 'color:#b91c1c; font-style:italic;' : '' }}">
                            {{ str_replace('.', ',', $rt_nilai) }}</td>
                        <td
                            style="border: 1px solid black; padding-left: 4px; {{ $rt_nilai < 6 ? 'color:#b91c1c; font-style:italic;' : '' }}">
                            {{ terbilang($rt_nilai) }}</td>
                    </tr>
                @endforeach
            @endif

            {{-- MAPEL PILIHAN --}}
            <tr>
                <td colspan="6" style="border: 1px solid black; font-weight: bold; padding-left: 4px;">B. DURUSUL
                    ZAIDAH</td>
            </tr>
            @if ($data_nilai_mapel_pilihan->isEmpty())
                <tr>
                    <td colspan="6"
                        style="border: 1px solid black; text-align: center; font-style: italic; color: #6b7280;">

                    </td>
                </tr>
            @else
                @foreach ($data_nilai_mapel_pilihan as $i => $nilai)
                    @php
                        $nilai_angka = number_format($nilai->nilai_akhir, 1);
                        $rt_nilai = number_format($nilai->pembelajaran->rata_rata ?? 0, 1);
                    @endphp
                    <tr>
                        <td style="border: 1px solid black; text-align: left;">{{ $i + 1 }}</td>
                        <td style="border: 1px solid black; padding-left: 4px;">
                            {{ $nilai->pembelajaran->mapel->nama_mapel }}</td>
                        <td
                            style="border: 1px solid black; text-align: center; {{ $nilai_angka < 6 ? 'color:#b91c1c; font-style:italic;' : '' }}">
                            {{ $nilai_angka }}</td>
                        <td
                            style="border: 1px solid black; padding-left: 4px; {{ $nilai_angka < 6 ? 'color:#b91c1c; font-style:italic;' : '' }}">
                            {{ terbilang($nilai_angka) }}</td>
                        <td
                            style="border: 1px solid black; text-align: center; {{ $rt_nilai < 6 ? 'color:#b91c1c; font-style:italic;' : '' }}">
                            {{ $rt_nilai }}</td>
                        <td
                            style="border: 1px solid black; padding-left: 4px; {{ $rt_nilai < 6 ? 'color:#b91c1c; font-style:italic;' : '' }}">
                            {{ terbilang($rt_nilai) }}</td>
                    </tr>
                @endforeach
            @endif

            {{-- TOTAL --}}
            <tr>
                <td colspan="2" style="border: 1px solid black; font-weight: bold; ">Jumlah</td>
                <td style="border: 1px solid black; text-align: center;">
                    {{ number_format($jumlah_nilai, 1) }}
                </td>
                <td
                    style="border: 1px solid black; padding-left: 4px; {{ $rt_nilai < 6 ? 'color:#b91c1c; font-style:italic;' : '' }}">
                    {{ terbilang($jumlah_nilai) }}</td>
                <td
                    style="border: 1px solid black; padding-left: 4px; text-align: center; {{ $rt_nilai < 6 ? 'color:#b91c1c; font-style:italic;' : '' }}">
                    {{ number_format($jumlahNilairata, 1) }}</td>
                <td
                    style="border: 1px solid black; padding-left: 4px; {{ $jumlahNilairata < 6 ? 'color:#b91c1c; font-style:italic;' : '' }}">
                    {{ terbilang($jumlahNilairata) }}</td>
            </tr>

            {{-- PERINGKAT --}}

            <tr>
                <td colspan="2" style="border-right: none;">Peringkat ke</td>
                <td style="border-left: none; border-right: none;">{{ $peringkat }}</td>
                <td style="border-left: none; border-right: none;">dari</td>
                <td style="border-left: none;">{{ $jumlah_santri }}</td>
                <td style="border-left: none;">santri</td>
            </tr>


        </tbody>
    </table>

</body>

</html>
