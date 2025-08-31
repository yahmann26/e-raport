<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }} - {{ $kelas->nama_kelas }}</title>
</head>
<body>
    <div class="header">
        <h2>DAFTAR NILAI SANTRI</h2>
        <h3>Kelas: {{ $kelas->nama_kelas }}</h3>
        <h3>Tahun Pelajaran: {{ $tapel->tahun_pelajaran }}</h3>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="bg-success">
                <tr>
                    <th rowspan="2" class="text-center" style="width: 30px;">No</th>
                    <th rowspan="2" class="text-center" style="width: 60px;">NIS</th>
                    <th rowspan="2" class="text-center" style="width: 150px;">Nama Santri</th>
                    <th colspan="{{ count($data_mapel_wajib) + count($data_mapel_pilihan) + count($data_mapel_muatan_lokal) }}" class="text-center">Nilai</th>
                    <th rowspan="2" class="text-center" style="width: 40px;">Jumlah</th>
                    <th rowspan="2" class="text-center" style="width: 40px;">Rata</th>
                    <th rowspan="2" class="text-center" style="width: 40px;">Rank</th>
                </tr>
                <tr>
                    @foreach($data_mapel_wajib->sortBy('pembelajaran.mapel.mapping_mapel.nomor_urut') as $mapel_wajib)
                    <th class="text-center" style="width: 40px;">{{ $mapel_wajib->pembelajaran->mapel->nama_mapel }}</th>
                    @endforeach

                    @foreach($data_mapel_pilihan->sortBy('pembelajaran.mapel.mapping_mapel.nomor_urut') as $mapel_pilihan)
                    <th class="text-center" style="width: 40px;">{{ $mapel_pilihan->pembelajaran->mapel->nama_mapel }}</th>
                    @endforeach

                    @foreach($data_mapel_muatan_lokal->sortBy('pembelajaran.mapel.mapping_mapel.nomor_urut') as $muatan_lokal)
                    <th class="text-center" style="width: 40px;">{{ $muatan_lokal->pembelajaran->mapel->nama_mapel }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <?php $no = 0; ?>
                @foreach($data_anggota_kelas as $anggota_kelas)
                <?php $no++; ?>
                <tr>
                    <td class="text-center">{{ $no }}</td>
                    <td class="text-center">{{ $anggota_kelas->santri->nis }}</td>
                    <td>{{ $anggota_kelas->santri->nama_lengkap }}</td>

                    @foreach($anggota_kelas->data_nilai_mapel_wajib->sortBy('pembelajaran.mapel.mapping_mapel.nomor_urut') as $nilai_mapel_wajib)
                    <td class="text-center">{{ $nilai_mapel_wajib->nilai_akhir }}</td>
                    @endforeach

                    @foreach($anggota_kelas->data_nilai_mapel_pilihan->sortBy('pembelajaran.mapel.mapping_mapel.nomor_urut') as $nilai_mapel_pilihan)
                    <td class="text-center">{{ $nilai_mapel_pilihan->nilai_akhir }}</td>
                    @endforeach

                    @foreach($anggota_kelas->data_nilai_mapel_muatan_lokal->sortBy('pembelajaran.mapel.mapping_mapel.nomor_urut') as $nilai_muatan_lokal)
                    <td class="text-center">{{ $nilai_muatan_lokal->nilai_akhir }}</td>
                    @endforeach

                    <td class="text-center">{{ number_format($anggota_kelas->jumlah_nilai, 1) }}</td>
                    <td class="text-center">{{ number_format($anggota_kelas->rt_nilai, 1) }}</td>
                    <td class="text-center">{{ $anggota_kelas->peringkat }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>