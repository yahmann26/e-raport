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
                            <h3 class="card-title"><i class="fas fa-users"></i> {{ $title }}</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool btn-sm" data-toggle="modal"
                                    data-target="#modal-tambah">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-tool btn-sm" data-toggle="modal"
                                    data-target="#modal-import">
                                    <i class="fas fa-upload"></i>
                                </button>
                                <a href="{{ route('santri.export') }}" class="btn btn-tool btn-sm">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Modal import  -->
                        <div class="modal fade" id="modal-import">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Import {{ $title }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form name="contact-form" action="{{ route('santri.import') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="callout callout-info">
                                                <h5>Download format import</h5>
                                                <p>Silahkan download file format import melalui tombol dibawah ini.</p>
                                                <a href="{{ route('santri.format_import') }}"
                                                    class="btn btn-primary text-white" style="text-decoration:none"><i
                                                        class="fas fa-file-download"></i> Download</a>
                                            </div>
                                            <div class="form-group row pt-2">
                                                <label for="file_import" class="col-sm-2 col-form-label">File
                                                    Import</label>
                                                <div class="col-sm-10">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input"
                                                            name="file_import" id="customFile"
                                                            accept="application/vnd.ms-excel">
                                                        <label class="custom-file-label" for="customFile">Pilih
                                                            file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-end">
                                            <button type="button" class="btn btn-default"
                                                data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Import</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal import -->

                        @include('admin.santri.tambah')

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1"
                                    class="table table-striped table-bordered table-valign-middle table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="15%">NIS</th>
                                            <th width="5%">NISN</th>
                                            <th width="20%">Nama Santri</th>
                                            <th width="15%">Tanggal Lahir</th>
                                            <th width="5%">L/P</th>
                                            <th width="10%">Kelas Saat Ini</th>
                                            <th width="10%">Status</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        @foreach ($data_santri as $santri)
                                            <?php $no++; ?>
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td>{{ $santri->nis }}</td>
                                                <td>{{ $santri->nisn }}</td>
                                                <td>{{ $santri->nama_lengkap }}</td>
                                                {{-- <td>{{ $santri->tanggal_lahir->format('d-M-Y') }}</td> --}}
                                                <td>{{ \Carbon\Carbon::parse($santri->tanggal_lahir)->translatedFormat('d F Y') }}
                                                </td>

                                                <td>{{ $santri->jenis_kelamin }}</td>
                                                <td>
                                                    @php
                                                        $anggota = $santri->anggota_kelas->first(function ($a) use (
                                                            $tapel,
                                                        ) {
                                                            return $a->kelas && $a->kelas->tapel_id == $tapel->id;
                                                        });
                                                    @endphp

                                                    @if ($anggota && $anggota->kelas)
                                                        {{ $anggota->kelas->nama_kelas }}
                                                    @else
                                                        <span class="text-danger">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($santri->status == 1)
                                                        <span class="badge light badge-success">Aktif</span>
                                                    @elseif ($santri->status == 2)
                                                        <span class="badge light badge-danger">Keluar</span>
                                                    @elseif ($santri->status == 3)
                                                        <span class="badge light badge-light">Lulus</span>
                                                    @endif
                                                <td>
                                                    <form action="{{ route('santri.destroy', $santri->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        @if ($santri->kelas_id != null)
                                                            <button type="button" class="btn btn-primary btn-sm mt-1"
                                                                data-toggle="modal"
                                                                data-target="#modal-registrasi{{ $santri->id }}"
                                                                title="Registrasi santri">
                                                                <i class="fas fa-user-cog"></i>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-primary btn-sm mt-1"
                                                                data-toggle="modal"
                                                                data-target="#modal-registrasi{{ $santri->id }}"
                                                                title="Registrasi santri" disabled>
                                                                <i class="fas fa-user-cog"></i>
                                                            </button>
                                                        @endif
                                                        <button type="button" class="btn btn-warning btn-sm mt-1"
                                                            data-toggle="modal"
                                                            data-target="#modal-edit{{ $santri->id }}">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-danger btn-sm mt-1"
                                                            onclick="return confirm('Hapus {{ $title }} ?')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Modal Registrasi  -->
                                            @if ($santri->kelas_id != null)
                                                <div class="modal fade" id="modal-registrasi{{ $santri->id }}">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Registrasi santri Keluar</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{ route('santri.registrasi') }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="callout callout-info">
                                                                        <h5>Diisi saat santri keluar dari sekolah</h5>
                                                                        <p>santri yang dapat diluluskan hanyalah santri
                                                                            yang berada pada kelas tingkat akhir pada
                                                                            semester genap.</p>
                                                                    </div>
                                                                    <input type="hidden" name="santri_id"
                                                                        value="{{ $santri->id }}">
                                                                    <div class="form-group row">
                                                                        <label for="nama_lengkap"
                                                                            class="col-sm-3 col-form-label">Nama
                                                                            santri</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="nama_lengkap" name="nama_lengkap"
                                                                                placeholder="Nama santri"
                                                                                value="{{ $santri->nama_lengkap }}"
                                                                                readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="keluar_karena"
                                                                            class="col-sm-3 col-form-label">Keluar
                                                                            Karena</label>
                                                                        <div class="col-sm-9 pt-1">
                                                                            <select class="form-control select2"
                                                                                name="keluar_karena"
                                                                                style="width: 100%;" required>
                                                                                <option value="">-- Pilih Jenis
                                                                                    Keluar --</option>
                                                                                @if ($santri->kelas->tingkatan_kelas == $tingkatan_akhir && $santri->kelas->tapel->semester == 2)
                                                                                    <option value="Lulus">Lulus
                                                                                    </option>
                                                                                @endif
                                                                                <option value="Mutasi">Mutasi</option>
                                                                                <option value="Dikeluarkan">Dikeluarkan
                                                                                </option>
                                                                                <option value="Mengundurkan Diri">
                                                                                    Mengundurkan Diri</option>
                                                                                <option value="Putus Sekolah">Putus
                                                                                    Sekolah</option>
                                                                                <option value="Wafat">Wafat</option>
                                                                                <option value="Hilang">Hilang</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="tanggal_keluar"
                                                                            class="col-sm-3 col-form-label">Tanggal
                                                                            Keluar Sekolah</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="date" class="form-control"
                                                                                id="tanggal_keluar"
                                                                                name="tanggal_keluar">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="alasan_keluar"
                                                                            class="col-sm-3 col-form-label">Alasan
                                                                            Keluar</label>
                                                                        <div class="col-sm-9">
                                                                            <textarea class="form-control" id="alasan_keluar" name="alasan_keluar" placeholder="Alasan Keluar"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer justify-content-end">
                                                                    <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">Batal</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <!-- End Modal Registrasi -->

                                            @include('admin.santri.edit')
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


@push('style')
    <script>
        function CheckPendaftaran(value) {
            document.getElementById('kelas_bawah').style.display = 'none';
            document.getElementById('kelas_all').style.display = 'none';

            if (value == 1) {
                document.getElementById('kelas_bawah').style.display = 'block';
                document.getElementById('kelas_bawah').setAttribute('name', 'kelas_id');
                document.getElementById('kelas_all').removeAttribute('name');
            } else {
                document.getElementById('kelas_all').style.display = 'block';
                document.getElementById('kelas_all').setAttribute('name', 'kelas_id');
                document.getElementById('kelas_bawah').removeAttribute('name');
            }
        }
    </script>
@endpush
