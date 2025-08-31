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
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">{{$title}}</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-user-friends"></i> {{$title}}</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool btn-sm" data-toggle="modal" data-target="#modal-tambah">
                  <i class="fas fa-plus"></i>
                </button>
                <a href="{{ route('user.export') }}" class="btn btn-tool btn-sm">
                  <i class="fas fa-download"></i>
                </a>
              </div>
            </div>

            @include('admin.user.tambah')

            <div class="card-body">
              <div class="table-responsive">
                <table id="example1" class="table table-striped table-bordered table-valign-middle table-hover">
                  <thead>
                    <tr>
                      <th width="5%">No</th>
                      <th width="25%">Nama Lengkap</th>
                      <th width="25%">Username</th>
                      <th width="15%">Level</th>
                      <th width="15%">Status Akun</th>
                      <th width="15%">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $no = 1; @endphp
                    @foreach($data_user as $user)
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>
                        @switch($user->role)
                          @case(1)
                            {{ optional($user->admin)->nama_lengkap }}
                            @break
                          @case(2)
                            {{ optional($user->guru)->nama_lengkap }}
                            @break
                          @case(3)
                            {{ optional($user->santri)->nama_lengkap }}
                            @break
                          {{-- @case(4)
                            {{ optional($user->kepala_sekolah)->nama_lengkap }}
                            @break
                          @case(5)
                            {{ optional($user->wakilkurikulum)->nama_lengkap }} --}}
                            @break
                          @default
                            -
                        @endswitch
                      </td>
                      <td>{{ $user->username }}</td>
                      <td>
                        @switch($user->role)
                          @case(1)
                            Administrator
                            @break
                          @case(2)
                            Guru
                            @break
                          @case(3)
                            Santri
                            @break
                          @case(4)
                            Kepala Sekolah
                            @break
                          @case(5)
                            Wakil Kurikulum
                            @break
                          @default
                            Tidak Diketahui
                        @endswitch
                      </td>
                      <td>
                        @if($user->status)
                          <span class="badge bg-success">Aktif</span>
                        @else
                          <span class="badge bg-danger">Non Aktif</span>
                        @endif
                      </td>
                      <td>
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit{{ $user->id }}">
                          <i class="fas fa-pencil-alt"></i> Edit
                        </button>
                      </td>
                    </tr>

                    @include('admin.user.edit')

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

