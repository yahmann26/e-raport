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
                            <h3 class="card-title"><i class="fas fa-print"></i> {{ $title }}</h3>
                        </div>

                        <div class="card-body">
                            <div class="callout callout-info">
                                <form action="{{ route('cetakraport.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Kelas</label>
                                        <div class="col-sm-10">
                                            <select class="form-control select2" name="kelas_id" style="width: 100%;"
                                                required onchange="this.form.submit();">
                                                <option value="" disabled>-- Pilih Kelas --</option>
                                                @foreach ($data_kelas->sortBy('tingkatan_kelas') as $kls)
                                                    <option value="{{ $kls->id }}"
                                                        @if ($kls->id == $kelas->id) selected @endif>
                                                        {{ $kls->nama_kelas }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <form id="bulkPrintForm" action="{{ route('cetakraport.bulk') }}" method="POST"
                                target="_blank">
                                @csrf
                                <input type="hidden" name="data_type" value="1">

                                <div class="d-flex justify-content-end mb-3">
                                    <button type="submit" id="printButton" class="btn btn-info"
                                        style="display: none;">
                                        <i class="fas fa-print"></i> Cetak Raport
                                    </button>
                                </div>

                                <div class="table-responsive callout callout-success">
                                    <table class="table table-bordered table-striped table-hover" id="example1">
                                        <thead class="bg-success">
                                            <tr>
                                                <th class="text-center" style="width: 5%;">
                                                    <input type="checkbox" id="selectAll">
                                                </th>
                                                <th class="text-center" style="width: 20%;">NIS</th>
                                                <th class="text-center" style="width: 35%;">Nama santri</th>
                                                <th class="text-center" style="width: 5%;">L/P</th>
                                                <th class="text-center" style="width: 25%;">Alamat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_anggota_kelas->sortBy('santri.nama_lengkap') as $anggota_kelas)
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="anggotaCheckbox"
                                                            name="anggota_kelas_id[]" value="{{ $anggota_kelas->id }}">
                                                    </td>
                                                    <td class="text-center">{{ $anggota_kelas->santri->nis }}</td>
                                                    <td>{{ $anggota_kelas->santri->nama_lengkap }}</td>
                                                    <td class="text-center">{{ $anggota_kelas->santri->jenis_kelamin }}
                                                    </td>
                                                    <td class="text-center">{{ $anggota_kelas->santri->alamat }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>

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
    const checkboxes = document.querySelectorAll('.anggotaCheckbox');
    const printButton = document.getElementById('printButton');
    const selectAll = document.getElementById('selectAll');
    const headerCheckbox = document.getElementById('headerCheckbox');

    function togglePrintButton() {
        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
        printButton.style.display = anyChecked ? 'inline-block' : 'none';
    }

    function updateHeaderBold() {
        if (Array.from(checkboxes).every(cb => cb.checked)) {
            headerCheckbox.classList.add('header-bold');
        } else {
            headerCheckbox.classList.remove('header-bold');
        }
    }

    // Saat "Select All" diklik
    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        togglePrintButton();
        updateHeaderBold();
    });

    // Saat checkbox individual diklik
    checkboxes.forEach(cb => cb.addEventListener('change', () => {
        togglePrintButton();
        updateHeaderBold();

        // Sinkronkan status Select All
        if (!cb.checked) {
            selectAll.checked = false;
        } else if (Array.from(checkboxes).every(cb => cb.checked)) {
            selectAll.checked = true;
        }
    }));
</script>

