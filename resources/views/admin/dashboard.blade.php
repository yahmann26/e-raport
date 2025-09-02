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
                        <li class="breadcrumb-item "><a href="{{ route('admin.dashboard') }}">{{ $title }}</a></li>
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
          <h5>{{ $pondok ? $pondok->nama_pondok : 'Nama pondok Tidak Tersedia' }}</h5>
          <p>
              Tahun Pelajaran {{ $tapel ? $tapel->tahun_pelajaran : '-' }}
              @if ($tapel && $tapel->semester == 1)
                  Semester Ganjil
              @elseif ($tapel)
                  Semester Genap
              @else
                  Semester Tidak Diketahui
              @endif
          </p>
      </div>
            <!-- End Info  -->

            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-tie"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Guru</span>
                            <span class="info-box-number">
                                {{ $jumlah_guru }}
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Santri</span>
                            <span class="info-box-number">{{ $jumlah_santri }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-layer-group"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Kelas</span>
                            <span class="info-box-number">{{ $jumlah_kelas }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-book-reader "></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Mapel</span>
                            <span class="info-box-number">{{ $jumlah_mapel }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->


            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-8">
                    <!-- MAP & BOX PANE -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Pengumuman</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        {{-- <div class="card-body pr-1">
              <div class="row">
                <div class="col-md-12">
                  <!-- The time line -->
                  <div class="timeline">
                    <!-- timeline time label -->
                    <div class="time-label">
                      <span class="bg-success">Pengumuman Terakhir</span>
                    </div>
                    <!-- /.timeline-label -->
                    <!-- timeline item -->
                    @foreach ($data_pengumuman->sortByDesc('created_at') as $pengumuman)
                    <div>
                      <i class="fas fa-envelope bg-primary"></i>
                      <div class="timeline-item">
                        <span class="time"><i class="far fa-clock"></i> {{$pengumuman->created_at}}</span>

                        <h3 class="timeline-header"><a href="#">{{$pengumuman->user->admin->nama_lengkap}}</a> {{$pengumuman->judul}} @if ($pengumuman->created_at != $pengumuman->updated_at)<small><i>edited</i></small>@endif</h3>

                        <div class="timeline-body">
                          {!! $pengumuman->isi !!}
                        </div>
                      </div>
                    </div>
                    @endforeach
                    <!-- END timeline item -->
                    <div>
                      <i class="fas fa-clock bg-gray"></i>
                    </div>
                  </div>
                </div>
                <!-- /.col -->
              </div>
            </div> --}}
                        <!-- /.card-body -->
                    </div>

                </div>
                <!-- /.col -->

                <div class="col-md-4">
                    <!-- PRODUCT LIST -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Riwayat Login</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                @foreach ($data_riwayat_login as $riwayat_login)
                                    <li class="item">

                                        <div class="product-img">
                                            @if ($riwayat_login->user->role == 1)
                                                <img src="assets/dist/img/avatar/{{ optional($riwayat_login->user->admin)->avatar ?? 'default.png' }}"
                                                    alt="Avatar" class="img-size-50">
                                            @elseif($riwayat_login->user->role == 2)
                                                <img src="assets/dist/img/avatar/{{ optional($riwayat_login->user->guru)->avatar ?? 'default.png' }}"
                                                    alt="Avatar" class="img-size-50">
                                            @elseif($riwayat_login->user->role == 3)
                                                <img src="assets/dist/img/avatar/{{ optional($riwayat_login->user->siswa)->avatar ?? 'default.png' }}"
                                                    alt="Avatar" class="img-size-50">
                                            @elseif($riwayat_login->user->role == 4)
                                                <img src="assets/dist/img/avatar/{{ optional($riwayat_login->user->kepala_pondok)->avatar ?? 'default.png' }}"
                                                    alt="Avatar" class="img-size-50">
                                            @elseif($riwayat_login->user->role == 5)
                                                <img src="assets/dist/img/avatar/{{ optional($riwayat_login->user->wakilkurikulum)->avatar ?? 'default.png' }}"
                                                    alt="Avatar" class="img-size-50">
                                            @else
                                                <img src="assets/dist/img/avatar/default.png" alt="Avatar"
                                                    class="img-size-50">
                                            @endif
                                        </div>

                                        <div class="product-info">
                                            <a href="javascript:void(0)" class="product-title">
                                                @if ($riwayat_login->user->role == 1)
                                                    {{ optional($riwayat_login->user->admin)->nama_lengkap }}
                                                @elseif($riwayat_login->user->role == 2)
                                                    {{ optional($riwayat_login->user->guru)->nama_lengkap }}
                                                @elseif($riwayat_login->user->role == 3)
                                                    {{ optional($riwayat_login->user->siswa)->nama_lengkap }}
                                                @elseif($riwayat_login->user->role == 4)
                                                    {{ optional($riwayat_login->user->kepala_pondok)->nama_lengkap }}
                                                @elseif($riwayat_login->user->role == 5)
                                                    {{ optional($riwayat_login->user->wakilkurikulum)->nama_lengkap }}
                                                @else
                                                    Nama Tidak Diketahui
                                                @endif

                                                @if ($riwayat_login->status_login)
                                                    <span class="badge badge-success float-right">Online</span>
                                                @else
                                                    <span class="badge badge-warning float-right">Offline</span>
                                                @endif
                                            </a>

                                            <span class="product-description">
                                                @if ($riwayat_login->user->role == 1)
                                                    Administrator
                                                @elseif($riwayat_login->user->role == 2)
                                                    Guru
                                                @elseif($riwayat_login->user->role == 3)
                                                    Siswa
                                                @elseif($riwayat_login->user->role == 4)
                                                    Kepala pondok
                                                @elseif($riwayat_login->user->role == 5)
                                                    Wakil Kurikulum
                                                @else
                                                    Tidak Diketahui
                                                @endif

                                                @if (!$riwayat_login->status_login)
                                                    <span class="time float-right"><i class="far fa-clock"></i>
                                                        {{ $riwayat_login->updated_at->diffForHumans() }}</span>
                                                @endif
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- /.card-body -->

                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@include('admin.components.footer')
