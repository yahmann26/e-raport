<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }} - {{ $kelas->nama_kelas }}</title>
</head>

<body>
    <div>
        <table style="width: 100%; border-collapse: collapse; border: none; background: none;">
            <tr>
                <td style="width: 30px; text-align: center; border: none;">MADIN ALMUBAROK</td>
                <td style="width: 150px; text-align: center; border: none;">
                    <h2 style="margin: 0; padding: 0;">DAFTAR NILAI SANTRI TAHUN PELAJARAN: {{ $tapel->tahun_pelajaran }}
                    </h2>
                </td>
                <td style="width: 30px; text-align: center; border: none;">Kelas {{ $kelas->nama_kelas }} Awwal</td>
            </tr>
        </table>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="bg-success">
                <tr>
                    <th rowspan="2" class="text-center" style="width: 30px;">NO</th>
                    {{-- <th rowspan="2" class="text-center" style="width: 60px;">NIS</th> --}}
                    <th rowspan="2" class="text-center" style="width: 150px;">NAMA SANTRI</th>
                    <th colspan="{{ count($data_mapel) }}" class="text-center">NILAI</th>
                    <th rowspan="2" class="text-center" style="width: 40px;">JUMLAH</th>
                    <th rowspan="2" class="text-center" style="width: 40px;">RATA-RATA</th>
                    <th rowspan="2" class="text-center" style="width: 40px;">RANK</th>
                </tr>
                <tr>
                    @foreach ($data_mapel->sortBy('pembelajaran.mapel.id') as $mapel)
                        <th class="text-center" style="width: 40px;">{{ $mapel->pembelajaran->mapel->ringkasan_mapel }}
                        </th>
                    @endforeach

                    @if (count($data_mapel) == 0)
                        <td class="text-center">-</td>
                    @endif
                </tr>
            </thead>
            <tbody>
                <?php $no = 0; ?>
                @foreach ($data_anggota_kelas->sortBy('santri.nis') as $anggota_kelas)
                    <?php $no++; ?>
                    <tr>
                        <td class="text-center">{{ $no }}</td>
                        {{-- <td class="text-center">{{ $anggota_kelas->santri->nis }}</td> --}}
                        <td style="text-align: left;">{{ $anggota_kelas->santri->nama_lengkap }}</td>

                        @foreach ($anggota_kelas->data_nilai_mapel->sortBy('pembelajaran.mapel.id') as $nilai_mapel)
                            <td class="text-center">{{ $nilai_mapel->nilai_akhir }}</td>
                        @endforeach

                        @if (count($anggota_kelas->data_nilai_mapel) == 0)
                            <td class="text-center">-</td>
                        @endif

                        <td class="text-center">{{ number_format($anggota_kelas->jumlah_nilai, 1) }}</td>
                        <td class="text-center">{{ number_format($anggota_kelas->rt_nilai, 1) }}</td>
                        <td class="text-center">{{ $anggota_kelas->peringkat }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table border="0" style="width: 100%;">
            <tr>
                <td style="width: 35%;"></td>
                <td style="width: 35%;"></td>
                <td style="width: 30%; text-align: center;">{{ $tanggal_raport->tempat_penerbitan }},
                    {{ \Carbon\Carbon::parse($tanggal_raport->tanggal_pembagian)->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td style="width: 35%; text-align: center;">
                    <p>Kepala Madin</p>
                    <br>
                    <br>
                    <br>
                    <br>
                    <p>TONI ZULIANTO</p>
                </td>
                <td style="width: 35%;"></td>
                <td style="width: 30%; text-align: center;">
                    <p>Wali Kelas</p>
                    <br>
                    <br>
                    <br>
                    <br>
                    <p>{{ $guru->guru->nama_lengkap }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
