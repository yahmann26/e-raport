@include('guru.components.header')
@include('guru.components.sidebar')

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
                        <!-- <li class="breadcrumb-item "><a href="{{ route('guru.dashboard') }}">{{ $title }}</a></li> -->
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

            <!-- Info -->
            <div class="callout callout-success">
                <h5>{{ $pondok->nama_pondok }}</h5>
                <p>Tahun Pelajaran {{ $tapel->tahun_pelajaran }}
                    @if ($tapel->semester == 1)
                        Semester Ganjil
                    @else
                        Semester Genap
                    @endif
                </p>
            </div>
            <!-- End Info  -->
        </div>
        <!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@include('guru.components.footer')
