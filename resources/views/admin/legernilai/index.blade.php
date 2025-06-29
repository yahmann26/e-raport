@include('admin.components.header')
@include('admin.components.sidebar')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">{{$title}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item "><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">{{$title}}</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- ./row -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-table"></i> {{$title}}</h3>
            </div>

            <div class="card-body">
              <div class="callout callout-info">
                <form action="{{ route('leger.store') }}" method="POST">
                  @csrf
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Kelas</label>
                    <div class="col-sm-10">
                      <select class="form-control select2" name="kelas_id" style="width: 100%;" required onchange="this.form.submit();">
                        <option value="" disabled>-- Pilih Kelas --</option>
                        @foreach($data_kelas->sortBy('tingkatan_kelas') as $kls)
                        <option value="{{$kls->id}}" @if($kls->id == $kelas->id) selected @endif>{{$kls->nama_kelas}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </form>
              </div>

              <div>
                <a href="{{ route('leger.show', $kelas->id) }}" class="btn btn-primary float-right"><i class="fas fa-download"></i> Download Leger</a>
              </div>

              <div class="table-responsive pt-2">
                <table class="table table-bordered table-striped">
                  <thead class="bg-success">
                    <tr>
                      <th rowspan="2" class="text-center" style="width: 50px;">No</th>
                      <th rowspan="2" class="text-center" style="width: 50px;">NIS</th>
                      <th rowspan="2" class="text-center">Nama santri</th>
                      <th colspan="{{count($data_mapel_wajib)+count($data_mapel_pilihan)+count($data_mapel_muatan_lokal)}}" class="text-center">Nilai</th>

                      <th rowspan="2" class="text-center">Jumlah</th>
                      <th rowspan="2" class="text-center">Rata-Rata</th>
                      <th rowspan="2" class="text-center">Peringkat</th>
                      <th colspan="3" class="text-center">Kehadiran</th>
                      {{-- <th colspan="{{$count_ekstrakulikuler}}" class="text-center">Ekstrakulikuler</th> --}}
                    </tr>
                    <tr>
                      @foreach($data_mapel_wajib->sortBy('pembelajaran.mapel.mapping_mapel.nomor_urut') as $mapel_wajib)
                      <th class="text-center">{{$mapel_wajib->pembelajaran->mapel->ringkasan_mapel}}</th>
                      @endforeach

                      @foreach($data_mapel_pilihan->sortBy('pembelajaran.mapel.mapping_mapel.nomor_urut') as $mapel_pilihan)
                      <th class="text-center">{{$mapel_pilihan->pembelajaran->mapel->ringkasan_mapel}}</th>
                      @endforeach

                      @foreach($data_mapel_muatan_lokal->sortBy('pembelajaran.mapel.mapping_mapel.nomor_urut') as $muatan_lokal)
                      <th class="text-center">{{$muatan_lokal->pembelajaran->mapel->ringkasan_mapel}}</th>
                      @endforeach

                      @if(count($data_mapel_wajib)+count($data_mapel_pilihan)+count($data_mapel_muatan_lokal) == 0)
                      <td class="text-center">-</td>
                      @endif

                      <th class="text-center">S</th>
                      <th class="text-center">I</th>
                      <th class="text-center">A</th>
                      {{-- @foreach($data_ekstrakulikuler->sortBy('id') as $ekstrakulikuler)
                      <th class="text-center">{{$ekstrakulikuler->nama_ekstrakulikuler}}</th>
                      @endforeach --}}

                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 0; ?>
                    @foreach($data_anggota_kelas->sortBy('santri.nama_lengkap') as $anggota_kelas)
                    <?php $no++; ?>
                    <tr>
                      <td class="text-center">{{$no}}</td>
                      <td class="text-center">{{$anggota_kelas->santri->nis}}</td>
                      <td>{{$anggota_kelas->santri->nama_lengkap}}</td>

                      @foreach($anggota_kelas->data_nilai_mapel_wajib->sortBy('pembelajaran.mapel.mapping_mapel.nomor_urut') as $nilai_mapel_wajib)
                      <td class="text-center">{{$nilai_mapel_wajib->nilai_akhir}}</td>
                      @endforeach

                      @foreach($anggota_kelas->data_nilai_mapel_pilihan->sortBy('pembelajaran.mapel.mapping_mapel.nomor_urut') as $nilai_mapel_pilihan)
                      <td class="text-center">{{$nilai_mapel_pilihan->nilai_akhir}}</td>
                      @endforeach

                      @foreach($anggota_kelas->data_nilai_mapel_muatan_lokal->sortBy('pembelajaran.mapel.mapping_mapel.nomor_urut') as $nilai_muatan_lokal)
                      <td class="text-center">{{$nilai_muatan_lokal->nilai_akhir}}</td>
                      @endforeach

                      @if(count($data_mapel_wajib)+count($data_mapel_pilihan)+count($data_mapel_muatan_lokal) == 0)
                      <td></td>
                      @endif

                      <td class="text-center">{{number_format($anggota_kelas->jumlah_nilai, 1)}}</td>
                      <td class="text-center">{{number_format($anggota_kelas->rt_nilai, 1)}}</td>
                      <td class="text-center">{{$anggota_kelas->peringkat}}</td>

                      @if(!is_null($anggota_kelas->kehadiran))
                      <td class="text-center">{{$anggota_kelas->kehadiran->sakit}}</td>
                      <td class="text-center">{{$anggota_kelas->kehadiran->izin}}</td>
                      <td class="text-center">{{$anggota_kelas->kehadiran->tanpa_keterangan}}</td>
                      @else
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                      @endif

                      {{-- @foreach($anggota_kelas->data_nilai_ekstrakulikuler as $nilai_ekstrakulikuler)
                      @if($nilai_ekstrakulikuler->nilai == 1)
                      <td class="text-center">Kurang</td>
                      @elseif($nilai_ekstrakulikuler->nilai == 2)
                      <td class="text-center">Cukup</td>
                      @elseif($nilai_ekstrakulikuler->nilai == 3)
                      <td class="text-center">Baik</td>
                      @elseif($nilai_ekstrakulikuler->nilai == 4)
                      <td class="text-center">Sangat Baik</td>
                      @else
                      <td class="text-center">-</td>
                      @endif
                      @endforeach --}}
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

          </div>
          <!-- /.card -->
        </div>

      </div>
      <!-- /.row -->
    </div>
    <!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@include('admin.components.footer')
