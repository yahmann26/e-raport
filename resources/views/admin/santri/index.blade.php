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
                                    <form action="{{ route('santri.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group row">
                                                <label for="nama_lengkap" class="col-sm-3 col-form-label">Nama
                                                    santri</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="nama_lengkap"
                                                        name="nama_lengkap" placeholder="Nama santri"
                                                        value="{{ old('nama_lengkap') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="jenis_kelamin" class="col-sm-3 col-form-label">Jenis
                                                    Kelamin</label>
                                                <div class="col-sm-9 pt-1">
                                                    <label class="form-check-label mr-3"><input type="radio"
                                                            name="jenis_kelamin" value="L"
                                                            @if (old('jenis_kelamin') == 'L') checked @endif required>
                                                        Laki-Laki</label>
                                                    <label class="form-check-label mr-3"><input type="radio"
                                                            name="jenis_kelamin" value="P"
                                                            @if (old('jenis_kelamin') == 'P') checked @endif required>
                                                        Perempuan</label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="nama_wali" class="col-sm-3 col-form-label">Jenis
                                                    Pendaftaran</label>
                                                <div class="col-sm-3 pt-1">
                                                    <label class="form-check-label mr-3"><input type="radio"
                                                            name="jenis_pendaftaran"
                                                            onchange='CheckPendaftaran(this.value);' value="1"
                                                            @if (old('jenis_pendaftaran') == '1') checked @endif required>
                                                        santri Baru</label>
                                                    <label class="form-check-label mr-3"><input type="radio"
                                                            name="jenis_pendaftaran"
                                                            onchange='CheckPendaftaran(this.value);' value="2"
                                                            @if (old('jenis_pendaftaran') == '2') checked @endif required>
                                                        Pindahan</label>
                                                </div>
                                                <label for="kelas_id" class="col-sm-2 col-form-label">Kelas</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control" id="kelas" required>
                                                        <option value="">-- Pilih Kelas --</option>
                                                        <option value="" disabled>Silahkan pilih jenis
                                                            pendaftaran</option>
                                                    </select>

                                                    <select class="form-control" id="kelas_bawah"
                                                        style='display:none;'>
                                                        <option value="">-- Pilih Kelas --</option>
                                                        @foreach ($data_kelas_terendah as $kelas_rendah)
                                                            <option value="{{ $kelas_rendah->id }}">
                                                                {{ $kelas_rendah->nama_kelas }}</option>
                                                        @endforeach
                                                    </select>

                                                    <select class="form-control" id="kelas_all"
                                                        style='display:none;'>
                                                        <option value="">-- Pilih Kelas --</option>
                                                        @foreach ($data_kelas_all as $kelas_all)
                                                            <option value="{{ $kelas_all->id }}">
                                                                {{ $kelas_all->nama_kelas }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="nis" class="col-sm-3 col-form-label">NIS</label>
                                                <div class="col-sm-3">
                                                    <input type="number" class="form-control" id="nis"
                                                        name="nis" placeholder="NIS"
                                                        value="{{ old('nis') }}">
                                                </div>
                                                <label for="nisn" class="col-sm-2 col-form-label">NISN
                                                    <small><i>(Opsional)</i></small></label>
                                                <div class="col-sm-4">
                                                    <input type="number" class="form-control" id="nisn"
                                                        name="nisn" placeholder="NISN"
                                                        value="{{ old('nisn') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="tempat_lahir" class="col-sm-3 col-form-label">Tempat
                                                    Lahir</label>
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control" id="tempat_lahir"
                                                        name="tempat_lahir" placeholder="Tempat Lahir"
                                                        value="{{ old('tempat_lahir') }}">
                                                </div>
                                                <label for="tanggal_lahir" class="col-sm-2 col-form-label">Tanggal
                                                    Lahir</label>
                                                <div class="col-sm-4">
                                                    <input type="date" class="form-control" id="tanggal_lahir"
                                                        name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="agama" class="col-sm-3 col-form-label">Agama</label>
                                                <div class="col-sm-3">
                                                    <select class="form-control" name="agama" required>
                                                        <option value="">-- Pilih Agama --</option>
                                                        <option value="1">Islam</option>
                                                        <option value="2">Protestan</option>
                                                        <option value="3">Katolik</option>
                                                        <option value="4">Hindu</option>
                                                        <option value="5">Budha</option>
                                                        <option value="6">Khonghucu</option>
                                                        <option value="7">Kepercayaan</option>
                                                    </select>
                                                </div>
                                                <label for="anak_ke" class="col-sm-2 col-form-label">Anak Ke</label>
                                                <div class="col-sm-4">
                                                    <input type="number" class="form-control" id="anak_ke"
                                                        name="anak_ke" value="{{ old('anak_ke') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="status_dalam_keluarga"
                                                    class="col-sm-3 col-form-label">Status Dalam Keluarga</label>
                                                <div class="col-sm-9 pt-1">
                                                    <label class="form-check-label mr-3"><input type="radio"
                                                            name="status_dalam_keluarga" value="1"
                                                            @if (old('status_dalam_keluarga') == '1') checked @endif required>
                                                        Anak Kandung</label>
                                                    <label class="form-check-label mr-3"><input type="radio"
                                                            name="status_dalam_keluarga" value="2"
                                                            @if (old('status_dalam_keluarga') == '2') checked @endif required>
                                                        Anak Angkat</label>
                                                    <label class="form-check-label mr-3"><input type="radio"
                                                            name="status_dalam_keluarga" value="3"
                                                            @if (old('status_dalam_keluarga') == '3') checked @endif required>
                                                        Anak Tiri</label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="nuptk" class="col-sm-3 col-form-label">Alamat</label>
                                                <div class="col-sm-9">
                                                    <textarea class="form-control" id="alamat" name="alamat" placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="nomor_hp" class="col-sm-3 col-form-label">Nomor HP
                                                    <small><i>(opsional)</i></small></label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" id="nomor_hp"
                                                        name="nomor_hp" placeholder="Nomor HP"
                                                        value="{{ old('nomor_hp') }}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="nama_ayah" class="col-sm-3 col-form-label">Nama
                                                    Ayah</label>
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control" id="nama_ayah"
                                                        name="nama_ayah" placeholder="Nama Ayah"
                                                        value="{{ old('nama_ayah') }}">
                                                </div>
                                                <label for="nama_ibu" class="col-sm-2 col-form-label">Nama Ibu</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" id="nama_ibu"
                                                        name="nama_ibu" placeholder="Nama Ibu"
                                                        value="{{ old('nama_ibu') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="pekerjaan_ayah" class="col-sm-3 col-form-label">Pekerjaan
                                                    Ayah</label>
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control" id="pekerjaan_ayah"
                                                        name="pekerjaan_ayah" placeholder="Pekerjaan Ayah"
                                                        value="{{ old('pekerjaan_ayah') }}">
                                                </div>
                                                <label for="pekerjaan_ibu" class="col-sm-2 col-form-label">Pekerjaan
                                                    Ibu</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" id="pekerjaan_ibu"
                                                        name="pekerjaan_ibu" placeholder="Pekerjaan Ibu"
                                                        value="{{ old('pekerjaan_ibu') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="nama_wali" class="col-sm-3 col-form-label">Nama Wali
                                                    <small><i>(Opsional)</i></small></label>
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control" id="nama_wali"
                                                        name="nama_wali" placeholder="Nama Wali"
                                                        value="{{ old('nama_wali') }}">
                                                </div>
                                                <label for="pekerjaan_wali" class="col-sm-2 col-form-label">Pekerjaan
                                                    Wali <small><i>(Opsional)</i></small></label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" id="pekerjaan_wali"
                                                        name="pekerjaan_wali" placeholder="Pekerjaan Wali"
                                                        value="{{ old('pekerjaan_wali') }}">
                                                </div>
                                            </div>
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
                                <table id="example1"
                                    class="table table-striped table-bordered table-valign-middle table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIS</th>
                                            <th>NISN</th>
                                            <th>Nama santri</th>
                                            <th>Tanggal Lahir</th>
                                            <th>L/P</th>
                                            <th>Kelas Saat Ini</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
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
                                                <td>{{ $santri->tanggal_lahir->format('d-M-Y') }}</td>
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
                                                        <span class="text-danger">Belum masuk anggota kelas</span>
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

                                            <!-- Modal edit  -->
                                            <div class="modal fade" id="modal-edit{{ $santri->id }}">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit {{ $title }}</h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('santri.update', $santri->id) }}"
                                                            method="POST">
                                                            {{ method_field('PATCH') }}
                                                            @csrf
                                                            <div class="modal-body">
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
                                                                    <label for="jenis_kelamin"
                                                                        class="col-sm-3 col-form-label">Jenis
                                                                        Kelamin</label>
                                                                    <div class="col-sm-9 pt-1">
                                                                        <label class="form-check-label mr-3"><input
                                                                                type="radio" name="jenis_kelamin"
                                                                                value="L"
                                                                                @if ($santri->jenis_kelamin == 'L') checked @endif
                                                                                required> Laki-Laki</label>
                                                                        <label class="form-check-label mr-3"><input
                                                                                type="radio" name="jenis_kelamin"
                                                                                value="P"
                                                                                @if ($santri->jenis_kelamin == 'P') checked @endif
                                                                                required> Perempuan</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="nis"
                                                                        class="col-sm-3 col-form-label">NIS</label>
                                                                    <div class="col-sm-3">
                                                                        <input type="number" class="form-control"
                                                                            id="nis" name="nis"
                                                                            placeholder="NIS"
                                                                            value="{{ $santri->nis }}">
                                                                    </div>
                                                                    <label for="nisn"
                                                                        class="col-sm-2 col-form-label">NISN
                                                                        <small><i>(Opsional)</i></small></label>
                                                                    <div class="col-sm-4">
                                                                        <input type="number" class="form-control"
                                                                            id="nisn" name="nisn"
                                                                            placeholder="NISN"
                                                                            value="{{ $santri->nisn }}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="tempat_lahir"
                                                                        class="col-sm-3 col-form-label">Tempat
                                                                        Lahir</label>
                                                                    <div class="col-sm-3">
                                                                        <input type="text" class="form-control"
                                                                            id="tempat_lahir" name="tempat_lahir"
                                                                            placeholder="Tempat Lahir"
                                                                            value="{{ $santri->tempat_lahir }}">
                                                                    </div>
                                                                    <label for="tanggal_lahir"
                                                                        class="col-sm-2 col-form-label">Tanggal
                                                                        Lahir</label>
                                                                    <div class="col-sm-4">
                                                                        <input type="date" class="form-control"
                                                                            id="tanggal_lahir" name="tanggal_lahir"
                                                                            value="{{ $santri->tanggal_lahir->format('Y-m-d') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="agama"
                                                                        class="col-sm-3 col-form-label">Agama</label>
                                                                    <div class="col-sm-3">
                                                                        <select class="form-control" name="agama"
                                                                            required>
                                                                            <option value="{{ $santri->agama }}"
                                                                                selected>
                                                                                @if ($santri->agama == 1)
                                                                                    Islam
                                                                                @elseif($santri->agama == 2)
                                                                                    Protestan
                                                                                @elseif($santri->agama == 3)
                                                                                    Katolik
                                                                                @elseif($santri->agama == 4)
                                                                                    Hindu
                                                                                @elseif($santri->agama == 5)
                                                                                    Budha
                                                                                @elseif($santri->agama == 6)
                                                                                    Khonghucu
                                                                                @elseif($santri->agama == 7)
                                                                                    Kepercayaan
                                                                                @endif
                                                                            </option>
                                                                            <option value="1">Islam</option>
                                                                            <option value="2">Protestan</option>
                                                                            <option value="3">Katolik</option>
                                                                            <option value="4">Hindu</option>
                                                                            <option value="5">Budha</option>
                                                                            <option value="6">Khonghucu</option>
                                                                            <option value="7">Kepercayaan</option>
                                                                        </select>
                                                                    </div>
                                                                    <label for="anak_ke"
                                                                        class="col-sm-2 col-form-label">Anak Ke</label>
                                                                    <div class="col-sm-4">
                                                                        <input type="number" class="form-control"
                                                                            id="anak_ke" name="anak_ke"
                                                                            value="{{ $santri->anak_ke }}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="status_dalam_keluarga"
                                                                        class="col-sm-3 col-form-label">Status Dalam
                                                                        Keluarga</label>
                                                                    <div class="col-sm-9 pt-1">
                                                                        <label class="form-check-label mr-3"><input
                                                                                type="radio"
                                                                                name="status_dalam_keluarga"
                                                                                value="1"
                                                                                @if ($santri->status_dalam_keluarga == '1') checked @endif
                                                                                required> Anak Kandung</label>
                                                                        <label class="form-check-label mr-3"><input
                                                                                type="radio"
                                                                                name="status_dalam_keluarga"
                                                                                value="2"
                                                                                @if ($santri->status_dalam_keluarga == '2') checked @endif
                                                                                required> Anak Angkat</label>
                                                                        <label class="form-check-label mr-3"><input
                                                                                type="radio"
                                                                                name="status_dalam_keluarga"
                                                                                value="3"
                                                                                @if ($santri->status_dalam_keluarga == '3') checked @endif
                                                                                required> Anak Tiri</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="nuptk"
                                                                        class="col-sm-3 col-form-label">Alamat</label>
                                                                    <div class="col-sm-9">
                                                                        <textarea class="form-control" id="alamat" name="alamat" placeholder="Alamat lengkap">{{ $santri->alamat }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="nomor_hp"
                                                                        class="col-sm-3 col-form-label">Nomor HP
                                                                        <small><i>(opsional)</i></small></label>
                                                                    <div class="col-sm-9">
                                                                        <input type="number" class="form-control"
                                                                            id="nomor_hp" name="nomor_hp"
                                                                            placeholder="Nomor HP"
                                                                            value="{{ $santri->nomor_hp }}">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label for="nama_ayah"
                                                                        class="col-sm-3 col-form-label">Nama
                                                                        Ayah</label>
                                                                    <div class="col-sm-3">
                                                                        <input type="text" class="form-control"
                                                                            id="nama_ayah" name="nama_ayah"
                                                                            placeholder="Nama Ayah"
                                                                            value="{{ $santri->nama_ayah }}">
                                                                    </div>
                                                                    <label for="nama_ibu"
                                                                        class="col-sm-2 col-form-label">Nama
                                                                        Ibu</label>
                                                                    <div class="col-sm-4">
                                                                        <input type="text" class="form-control"
                                                                            id="nama_ibu" name="nama_ibu"
                                                                            placeholder="Nama Ibu"
                                                                            value="{{ $santri->nama_ibu }}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="pekerjaan_ayah"
                                                                        class="col-sm-3 col-form-label">Pekerjaan
                                                                        Ayah</label>
                                                                    <div class="col-sm-3">
                                                                        <input type="text" class="form-control"
                                                                            id="pekerjaan_ayah" name="pekerjaan_ayah"
                                                                            placeholder="Pekerjaan Ayah"
                                                                            value="{{ $santri->pekerjaan_ayah }}">
                                                                    </div>
                                                                    <label for="pekerjaan_ibu"
                                                                        class="col-sm-2 col-form-label">Pekerjaan
                                                                        Ibu</label>
                                                                    <div class="col-sm-4">
                                                                        <input type="text" class="form-control"
                                                                            id="pekerjaan_ibu" name="pekerjaan_ibu"
                                                                            placeholder="Pekerjaan Ibu"
                                                                            value="{{ $santri->pekerjaan_ibu }}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="nama_wali"
                                                                        class="col-sm-3 col-form-label">Nama Wali
                                                                        <small><i>(Opsional)</i></small></label>
                                                                    <div class="col-sm-3">
                                                                        <input type="text" class="form-control"
                                                                            id="nama_wali" name="nama_wali"
                                                                            placeholder="Nama Wali"
                                                                            value="{{ $santri->nama_wali }}">
                                                                    </div>
                                                                    <label for="pekerjaan_wali"
                                                                        class="col-sm-2 col-form-label">Pekerjaan Wali
                                                                        <small><i>(Opsional)</i></small></label>
                                                                    <div class="col-sm-4">
                                                                        <input type="text" class="form-control"
                                                                            id="pekerjaan_wali" name="pekerjaan_wali"
                                                                            placeholder="Pekerjaan Wali"
                                                                            value="{{ $santri->pekerjaan_wali }}">
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
