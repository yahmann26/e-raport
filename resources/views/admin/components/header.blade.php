<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Raport Digital {{ $title }}</title>

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/logo/logowsb.png') }}">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- pace-progress -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/pace-progress/themes/black/pace-theme-flat-top.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed pace-primary">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown pr-2">
                    <!-- User Block  -->
                    <a class="user-block" data-toggle="dropdown" href="#">
                        @if (Auth::user()->role == 1 && Auth::user()->admin)
                            <img class="img-circle" src="/assets/dist/img/avatar/{{ Auth::user()->admin->avatar }}"
                                alt="User Image">
                            <span class="username">{{ Auth::user()->admin->nama_lengkap }}</span>
                            <span class="description">Administrator</span>
                        @elseif (Auth::user()->role == 2 && Auth::user()->guru)
                            <img class="img-circle" src="/assets/dist/img/avatar/{{ Auth::user()->guru->avatar }}"
                                alt="User Image">
                            <span class="username">{{ Auth::user()->guru->nama_lengkap }}</span>
                            <span class="description">{{ session()->get('akses_sebagai') }}</span>
                        @elseif (Auth::user()->role == 4 && Auth::user()->kepalaSekolah)
                            <img class="img-circle"
                                src="/assets/dist/img/avatar/{{ Auth::user()->kepalaSekolah->avatar }}"
                                alt="User Image">
                            <span class="username">{{ Auth::user()->kepalaSekolah->nama_lengkap }}</span>
                            <span class="description">Kepala Sekolah</span>
                        @elseif (Auth::user()->role == 5 && Auth::user()->wakilKurikulum)
                            <img class="img-circle"
                                src="/assets/dist/img/avatar/{{ Auth::user()->wakilKurikulum->avatar }}"
                                alt="User Image">
                            <span class="username">{{ Auth::user()->wakilKurikulum->nama_lengkap }}</span>
                            <span class="description">Wakil Kurikulum</span>
                        @elseif (Auth::user()->siswa)
                            <img class="img-circle" src="/assets/dist/img/avatar/{{ Auth::user()->siswa->avatar }}"
                                alt="User Image">
                            <span class="username">{{ Auth::user()->siswa->nama_lengkap }}</span>
                            <span class="description">Siswa</span>
                        @else
                            <img class="img-circle" src="/assets/dist/img/avatar/default.png" alt="User Image">
                            <span class="username">Pengguna</span>
                            <span class="description">Tidak Diketahui</span>
                        @endif
                    </a>
                    <!-- End User Block  -->

                    <!-- User Dropdown  -->
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">Akun Saya</span>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.profile') }}" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.password') }}" class="dropdown-item">
                            <i class="fas fa-key mr-2"></i> Ganti Password
                        </a>

                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item dropdown-footer bg-danger"
                            onclick="return confirm('Apakah anda yakin ingin keluar ?')">
                            <i class="fas fa-sign-out-alt mr-1"></i> Keluar / Logout
                        </a>
                    </div>
                    <!-- End User Dropdown  -->
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
