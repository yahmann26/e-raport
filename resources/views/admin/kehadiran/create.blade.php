@include('admin.components.header')
@include('admin.components.sidebar')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">{{ $title }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">{{ $title }}</li>
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
              <h3 class="card-title"><i class="fas fa-user-check"></i> {{ $title }}</h3>
            </div>
            <form action="{{ route('kehadiran.store') }}" method="POST">
              @csrf
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                    <thead class="bg-info">
                      <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th class="text-center" style="width: 10%;">NIS</th>
                        <th class="text-center" style="width: 35%;">Nama santri</th>
                        <th class="text-center" style="width: 5%;">L/P</th>
                        <th class="text-center" style="width: 10%;">Kelas</th>
                        <th class="text-center" style="width: 15%;">Sakit</th>
                        <th class="text-center" style="width: 15%;">Izin</th>
                        <th class="text-center" style="width: 15%;">Tanpa Keterangan</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php $no = 1; @endphp
                      @foreach ($data_anggota_kelas->sortBy('santri.nis') as $anggota_kelas)
                        <tr>
                          <input type="hidden" name="anggota_kelas_id[]" value="{{ $anggota_kelas->id }}">
                          <td class="text-center">{{ $no++ }}</td>
                          <td class="text-center">{{ $anggota_kelas->santri->nis }}</td>
                          <td>{{ $anggota_kelas->santri->nama_lengkap }}</td>
                          <td class="text-center">{{ $anggota_kelas->santri->jenis_kelamin }}</td>
                          <td class="text-center">{{ $anggota_kelas->kelas->nama_kelas }}</td>
                          <td>
                            <input type="number" class="form-control" name="sakit[]" min="0"
                              value="{{ $anggota_kelas->kehadiran->sakit ?? 0 }}" required
                              oninvalid="this.setCustomValidity('Isian tidak boleh kosong')"
                              oninput="setCustomValidity('')">
                          </td>
                          <td>
                            <input type="number" class="form-control" name="izin[]" min="0"
                              value="{{ $anggota_kelas->kehadiran->izin ?? 0 }}" required
                              oninvalid="this.setCustomValidity('Isian tidak boleh kosong')"
                              oninput="setCustomValidity('')">
                          </td>
                          <td>
                            <input type="number" class="form-control" name="tanpa_keterangan[]" min="0"
                              value="{{ $anggota_kelas->kehadiran->tanpa_keterangan ?? 0 }}" required
                              oninvalid="this.setCustomValidity('Isian tidak boleh kosong')"
                              oninput="setCustomValidity('')">
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="card-footer clearfix">
                <button type="submit" class="btn btn-primary float-right">Simpan</button>
              </div>
            </form>
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
