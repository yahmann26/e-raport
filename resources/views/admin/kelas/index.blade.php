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
              <h3 class="card-title"><i class="fas fa-user-tie"></i> {{$title}}</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool btn-sm" data-toggle="modal" data-target="#modal-tambah">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
            </div>

            <!-- Modal tambah  -->
            <div class="modal fade" id="modal-tambah">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Tambah {{$title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{ route('kelas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                      <div class="form-group row">
                        <label for="tahun_pelajaran" class="col-sm-3 col-form-label">Tahun Pelajaran</label>
                        <div class="col-sm-9">
                          @if($tapel->semester == 1)
                          <input type="text" name="tahun_pelajaran" class="form-control" value="{{$tapel->tahun_pelajaran}} Semester Ganjil" readonly>
                          @else
                          <input type="text" name="tahun_pelajaran" class="form-control" value="{{$tapel->tahun_pelajaran}} Semester Genap" readonly>
                          @endif </div>
                      </div>
                      <div class="form-group row">
                        <label for="tingkatan_kelas" class="col-sm-3 col-form-label">Tingkatan Kelas</label>
                        <div class="col-sm-9">
                          <input type="number" class="form-control" id="tingkatan_kelas" name="tingkatan_kelas" placeholder="Tingkatan Kelas" value="{{old('tingkatan_kelas')}}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="nama_kelas" class="col-sm-3 col-form-label">Nama Kelas</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" placeholder="Nama Kelas" value="{{old('nama_kelas')}}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="guru_id" class="col-sm-3 col-form-label">Wali Kelas</label>
                        <div class="col-sm-9">
                          <select class="form-control select2" name="guru_id" style="width: 100%;" required>
                            <option value="">-- Pilih Wali Kelas --</option>
                            @foreach($data_guru as $guru)
                            <option value="{{$guru->id}}">{{$guru->nama_lengkap}}, {{$guru->gelar}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- End Modal tambah -->

            <div class="card-body">
              <div class="table-responsive">
                <table id="example1" class="table table-striped table-valign-middle table-hover">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Semester</th>
                      <th>Tingkat</th>
                      <th>Nama Kelas</th>
                      <th>Wali Kelas</th>
                      <th>Jml Anggota</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 0; ?>
                    @foreach($data_kelas as $kelas)
                    <?php $no++; ?>
                    <tr>
                      <td>{{$no}}</td>
                      <td>{{$kelas->tapel->tahun_pelajaran}}
                        @if($kelas->tapel->semester == 1)
                        Ganjil
                        @else
                        Genap
                        @endif
                      </td>
                      <td>{{$kelas->tingkatan_kelas}}</td>
                      <td>{{$kelas->nama_kelas}}</td>
                      <td>{{$kelas->guru->nama_lengkap}}, {{$kelas->guru->gelar}}</td>
                      <td>
                        <a href="{{ route('kelas.show', $kelas->id) }}" class="btn btn-primary btn-sm">
                          <i class="fas fa-list"></i> {{$kelas->jumlah_anggota}} Santri
                        </a>
                      </td>
                      <td>
                        <form action="{{ route('kelas.destroy', $kelas->id) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="button" class="btn btn-warning btn-sm mt-1" data-toggle="modal" data-target="#modal-edit{{$kelas->id}}">
                            <i class="fas fa-pencil-alt"></i>
                          </button>
                          <button type="submit" class="btn btn-danger btn-sm mt-1" onclick="return confirm('Hapus {{$title}} ?')">
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </form>
                      </td>
                    </tr>

                    <!-- Modal edit  -->
                    <div class="modal fade" id="modal-edit{{$kelas->id}}">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Edit {{$title}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                            {{ method_field('PATCH') }}
                            @csrf
                            <div class="modal-body">
                              <div class="form-group row">
                                <label for="tingkatan_kelas" class="col-sm-3 col-form-label">Tingkatan Kelas</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" id="tingkatan_kelas" name="tingkatan_kelas" value="{{$kelas->tingkatan_kelas}}" readonly>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="nama_kelas" class="col-sm-3 col-form-label">Nama Kelas</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" value="{{$kelas->nama_kelas}}">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="guru_id" class="col-sm-3 col-form-label">Wali Kelas</label>
                                <div class="col-sm-9">
                                  <select class="form-control select2" name="guru_id" style="width: 100%;" required>
                                    <option value="" disabled>-- Pilih Wali Kelas -- </option>
                                    @foreach($data_guru as $guru)
                                    <option value="{{$guru->id}}" @if($guru->id == $kelas->guru->id) selected @endif>
                                      {{$guru->nama_lengkap}}, {{$guru->gelar}}
                                    </option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer justify-content-end">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                              <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <!-- End Modal edit -->

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
