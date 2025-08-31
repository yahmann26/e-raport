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
                        <li class="breadcrumb-item "><a href="{{ route('pembelajaran.index') }}">Data Pembelajaran</a>
                        </li>
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
                            <h3 class="card-title"><i class="fas fa-cog"></i> {{ $title }}</h3>
                        </div>

                        <div class="card-body">
                            <div class="callout callout-info">
                                <h5><i class="icon fas fa-info"></i> Informasi Sistem</h5>
                                <p>
                                    - Mata pelajaran hanya muncul jika belum aktif di kelas manapun pada tahun pelajaran
                                    {{ $tapel->tahun_pelajaran }}<br>
                                    - Data pembelajaran yang dinonaktifkan akan otomatis dihapus dari sistem<br>
                                    - Default status untuk mata pelajaran baru adalah <strong>Tidak Aktif</strong>
                                </p>
                            </div>

                            <div class="form-group row mx-1">
                                <label for="kelas_id" class="col-sm-2 col-form-label">Kelas</label>
                                <div class="col-sm-10">
                                    <form action="{{ route('pembelajaran.settings.process') }}" method="POST">
                                        @csrf
                                        <select class="form-control select2" name="kelas_id" style="width: 100%;"
                                            required onchange="this.form.submit();">
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach ($data_kelas as $d_kelas)
                                                <option value="{{ $d_kelas->id }}"
                                                    {{ $d_kelas->id == $kelas->id ? 'selected' : '' }}>
                                                    {{ $d_kelas->nama_kelas }} ( {{ $d_kelas->tapel->tahun_pelajaran }}
                                                    @if ($d_kelas->tapel->semester == 1)
                                                        Ganjil
                                                    @else
                                                        Genap
                                                    @endif)
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>

                            @if ($data_pembelajaran_kelas->count() > 0 || $data_mapel_belum_aktif->count() > 0)
                                <form action="{{ route('pembelajaran.store') }}" method="POST" id="pembelajaranForm">
                                    @csrf
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Kelas</th>
                                                    <th>Mata Pelajaran</th>
                                                    <th>Guru</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data pembelajaran aktif -->
                                                @foreach ($data_pembelajaran_kelas as $pembelajaran)
                                                    <tr>
                                                        <td>{{ $pembelajaran->kelas->nama_kelas }} (
                                                            {{ $pembelajaran->kelas->tapel->tahun_pelajaran }}
                                                            @if ($pembelajaran->kelas->tapel->semester == 1)
                                                                Ganjil
                                                            @else
                                                                Genap
                                                            @endif)
                                                        </td>
                                                        <td>{{ $pembelajaran->mapel->nama_mapel }} - {{ $pembelajaran->mapel->ringkasan_mapel }}
                                                            <input type="hidden" name="pembelajaran_id[]"
                                                                value="{{ $pembelajaran->id }}">
                                                        </td>
                                                        <td>
                                                            <select class="form-control select2" name="update_guru_id[]"
                                                                style="width: 100%;">
                                                                <option value="">-- Pilih Guru --</option>
                                                                @foreach ($data_guru as $guru)
                                                                    <option value="{{ $guru->id }}"
                                                                        @if (optional($pembelajaran->guru)->id == $guru->id) selected @endif>
                                                                        {{ $guru->nama_lengkap }}, {{ $guru->gelar }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control status-select"
                                                                name="update_status[]">
                                                                <option value="0">Tidak Aktif</option>
                                                                <option value="1" selected>Aktif</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger remove-row"
                                                                data-bs-toggle="tooltip" title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <!-- Data Mapel yang Belum Aktif -->
                                                @foreach ($data_mapel_belum_aktif as $mapel)
                                                    <tr>
                                                        <td>{{ $kelas->nama_kelas }} (
                                                            {{ $kelas->tapel->tahun_pelajaran }}
                                                            @if ($kelas->tapel->semester == 1)
                                                                Ganjil
                                                            @else
                                                                Genap
                                                            @endif)
                                                            <input type="hidden" name="kelas_id[]"
                                                                value="{{ $kelas->id }}">
                                                        </td>
                                                        <td>
                                                            {{ $mapel->nama_mapel }} - {{ $mapel->ringkasan_mapel }}
                                                            <input type="hidden" name="mapel_id[]"
                                                                value="{{ $mapel->id }}">
                                                        </td>
                                                        <td>
                                                            <select class="form-control select2" name="guru_id[]"
                                                                style="width: 100%;">
                                                                <option value="">-- Pilih Guru --</option>
                                                                @foreach ($data_guru as $guru)
                                                                    <option value="{{ $guru->id }}">
                                                                        {{ $guru->nama_lengkap }}, {{ $guru->gelar }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control status-select" name="status[]">
                                                                <option value="0" selected>Tidak Aktif</option>
                                                                <option value="1">Aktif</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger remove-row"
                                                                data-bs-toggle="tooltip" title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="card-footer clearfix">
                                        <button type="button" class="btn btn-info" id="checkAllActive">
                                            <i class="fas fa-check-square"></i> Tandai Semua Aktif
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="checkAllInactive">
                                            <i class="fas fa-square"></i> Tandai Semua Tidak Aktif
                                        </button>
                                        <button type="submit" class="btn btn-success float-right">Simpan</button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-info">
                                    <h5><i class="icon fas fa-info"></i> Informasi</h5>
                                    <p>Semua mata pelajaran sudah aktif di kelas ini atau belum ada mata pelajaran yang
                                        tersedia.</p>
                                </div>
                            @endif
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Konfirmasi sebelum menonaktifkan
        const form = document.getElementById('pembelajaranForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const statusSelects = document.querySelectorAll('select[name="update_status[]"]');
                let willDeactivate = false;

                statusSelects.forEach(select => {
                    if (select.value == 0) {
                        willDeactivate = true;
                    }
                });

                if (willDeactivate) {
                    if (!confirm(
                            'Data pembelajaran yang dinonaktifkan akan dihapus dari sistem. Lanjutkan?'
                            )) {
                        e.preventDefault();
                    }
                }
            });
        }

        // Fungsi untuk menghapus baris
        document.querySelectorAll('.remove-row').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                if (confirm('Apakah Anda yakin ingin menghapus baris ini?')) {
                    row.remove();

                    // Cek jika tidak ada baris lagi, tampilkan pesan
                    if (document.querySelectorAll('tbody tr').length === 0) {
                        document.querySelector('.table-responsive').innerHTML = `
              <div class="alert alert-info m-3">
                <h5><i class="icon fas fa-info"></i> Informasi</h5>
                <p>Tidak ada data pembelajaran yang tersedia.</p>
              </div>
            `;
                    }
                }
            });
        });

        // Tandai semua aktif
        const checkAllActive = document.getElementById('checkAllActive');
        if (checkAllActive) {
            checkAllActive.addEventListener('click', function() {
                document.querySelectorAll('.status-select').forEach(select => {
                    select.value = '1';
                });
            });
        }

        // Tandai semua tidak aktif
        const checkAllInactive = document.getElementById('checkAllInactive');
        if (checkAllInactive) {
            checkAllInactive.addEventListener('click', function() {
                document.querySelectorAll('.status-select').forEach(select => {
                    select.value = '0';
                });
            });
        }

        // Inisialisasi tooltip
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
