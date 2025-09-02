@include('guru.components.header')
@include('guru.components.sidebar')

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
            <li class="breadcrumb-item "><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
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
              <h3 class="card-title"><i class="fas fa-paper-plane"></i> {{$title}}</h3>
            </div>

            <div class="card-body">
              <div class="callout callout-info">
                <form action="{{ route('kirimnilai.create') }}" method="GET">
                  @csrf
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Mata Pelajaran</label>
                    <div class="col-sm-10">
                      <select class="form-control select2" name="pembelajaran_id" style="width: 100%;" required onchange="this.form.submit();">
                        <option value="" disabled>-- Pilih Pembelajaran --</option>
                        @foreach($data_pembelajaran as $mapel)
                        <option value="{{$mapel->id}}" @if($mapel->id == $pembelajaran->id) selected @endif>{{$mapel->mapel->ringkasan_mapel}} {{$mapel->kelas->nama_kelas}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </form>
              </div>

              <!-- Nilai -->

              <div class="card">
                <div class="card-header bg-primary">
                  <h3 class="card-title"><i class="fas fa-file-invoice"></i> Nilai Raport</h3>
                </div>
                <form action="{{ route('kirimnilai.store') }}" method="POST">
                  @csrf
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover table-striped">
                        <thead class="bg-info">
                          <tr>
                            <th rowspan="2" class="text-center">No</th>
                            <th rowspan="2" class="text-center">Nama santri</th>
                            <th colspan="2" class="text-center">Nilai Raport</th>
                          </tr>
                          <tr>
                            <th class="text-center">Angka</th>
                            <th class="text-center">Huruf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <input type="hidden" name="pembelajaran_id" value="{{$pembelajaran->id}}">
                          {{-- <input type="hidden" name="kkm" value="{{$kkm->kkm}}"> --}}

                          <?php $no = 0; ?>
                          @foreach($data_anggota_kelas->sortBy('santri.nama_lengkap') as $anggota_kelas)
                          <?php $no++; ?>
                          <tr>
                            <td class="text-center" style="width: 5%;">{{$no}}</td>

                            <td>{{$anggota_kelas->santri->nama_lengkap}}</td>
                            <input type="hidden" name="anggota_kelas_id[]" value="{{$anggota_kelas->id}}">

                            {{-- <td class="text-center">{{$kkm->kkm}}</td> --}}
                            <td class="text-center">{{$anggota_kelas->nilai_akhir}}</td>
                            <input type="hidden" name="nilai_akhir[]" value="{{$anggota_kelas->nilai_akhir}}">
                            <td>{{terbilang($anggota_kelas->nilai_akhir)}}</td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="card-footer clearfix">
                    <button type="submit" class="btn btn-primary float-right">Kirim Nilai Akhir</button>
                    <a href="{{ route('kirimnilai.index') }}" class="btn btn-default float-right mr-2">Batal</a>
                  </div>
                </form>
              </div>

            </div>
          </div> <!-- /.card -->
        </div>

      </div>
      <!-- /.row -->
    </div>
    <!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@include('guru.components.footer')
