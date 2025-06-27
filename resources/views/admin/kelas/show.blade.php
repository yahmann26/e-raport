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
                        <li class="breadcrumb-item "><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item "><a href="{{ route('kelas.index') }}">Kelas</a></li>
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
                            <h3 class="card-title"><i class="fas fa-users"></i> {{ $title }}
                                {{ $kelas->nama_kelas }} {{ $kelas->tapel->tahun_pelajaran }} Semester
                                @if ($kelas->tapel->semester == 1)
                                    Ganjil
                                @else
                                    Genap
                                @endif
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool btn-sm" data-toggle="modal"
                                    data-target="#modal-tambah">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Modal tambah  -->
                        <div class="modal fade" id="modal-tambah">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah {{ $title }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('kelas.anggota') }}" method="POST">
                                        @csrf

                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="callout callout-info">
                                                            <label>
                                                                {{ $kelas->nama_kelas }}
                                                                {{ $kelas->tapel->tahun_pelajaran }} Semester
                                                                @if ($kelas->tapel->semester == 1)
                                                                    Ganjil
                                                                @else
                                                                    Genap
                                                                @endif
                                                            </label>
                                                            <p>Untuk menambahkan anggota kelas, silahkan pindahkan nama
                                                                santri ke kolom sebelah kanan lalu klik tombol simpan.
                                                            </p>
                                                        </div>
                                                        <input type="hidden" name="kelas_id"
                                                            value="{{ $kelas->id }}">
                                                        <select class="duallistbox" multiple="multiple"
                                                            name="santri_id[]">
                                                            @foreach ($santri_belum_masuk_kelas as $belum_masuk_kelas)
                                                                <option value="{{ $belum_masuk_kelas->id }}">
                                                                    {{ $belum_masuk_kelas->nis }} |
                                                                    {{ $belum_masuk_kelas->nisn }} |
                                                                    {{ $belum_masuk_kelas->nama_lengkap }}
                                                                    ({{ $belum_masuk_kelas->kelas_sebelumhya ?? '-' }})
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <div class="form-group row pt-3 pb-0 justify-content-end">
                                                            <label for="pendaftaran"
                                                                class="col-sm-2 col-form-label">Jenis
                                                                Pendaftaran</label>
                                                            <div class="col-sm-4">
                                                                <select class="form-control" name="pendaftaran"
                                                                    required>
                                                                    <option value="">-- Pilih Jenis Pendaftaran --
                                                                    </option>
                                                                    <option value="2">Pindahan</option>
                                                                    @if ($kelas->tapel->semester == 1)
                                                                        <option value="1">Santri Baru</option>
                                                                        <option value="3">Naik Kelas</option>
                                                                        <option value="5">Mengulang</option>
                                                                    @else
                                                                        <option value="4">Lanjutan Semester
                                                                        </option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /.form-group -->
                                                </div>
                                                <!-- /.col -->
                                            </div>
                                            <!-- /.row -->
                                        </div>
                                        <div class="modal-footer justify-content-end">
                                            <button type="button" class="btn btn-default"
                                                data-dismiss="modal">Batal</button>
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
                                            <th>NIS</th>
                                            <th>NISN</th>
                                            <th>Nama Santri</th>
                                            <th>Tanggal Lahir</th>
                                            <th>L/P</th>
                                            <th>Pendaftaran</th>
                                            <th>Hapus Anggota</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        @foreach ($anggota_kelas as $anggota)
                                            <?php $no++; ?>
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td>{{ $anggota->santri->nis }}</td>
                                                <td>{{ $anggota->santri->nisn }}</td>
                                                <td>{{ $anggota->santri->nama_lengkap }}</td>
                                                <td>{{ $anggota->santri->tanggal_lahir }}</td>
                                                <td>{{ $anggota->santri->jenis_kelamin }}</td>
                                                <td>
                                                    @if ($anggota->pendaftaran == 1)
                                                        Santri Baru
                                                    @elseif ($anggota->pendaftaran == 2)
                                                        Pindahan
                                                    @elseif ($anggota->pendaftaran == 3)
                                                        Naik Kelas
                                                    @elseif ($anggota->pendaftaran == 4)
                                                        Naik Kelas
                                                    @elseif ($anggota->pendaftaran == 5)
                                                        Mengulang
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ route('kelas.anggota.delete', $anggota->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm mt-1"
                                                            onclick="return confirm('Hapus {{ $title }} ?')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
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
