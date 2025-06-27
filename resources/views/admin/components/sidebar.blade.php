<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-secondary">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset('assets/images/logo/logowsb.png') }}" alt="Logo" class="brand-image img-circle">
        <span class="brand-text font-weight-light">Aplikasi Raport Digital</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>
                            Data User
                        </p>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="{{ route('pengumuman.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>
                            Pengumuman
                        </p>
                    </a>
                </li> --}}

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-server"></i>
                        <p>
                            Data Master
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview bg-secondary">
                        <li class="nav-item">
                            <a href="{{ route('admin.pondok') }}" class="nav-link">
                                <i class="fas fa-school nav-icon"></i>
                                <p>Profil Pondok</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('guru.index') }}" class="nav-link">
                                <i class="fas fa-user-tie nav-icon"></i>
                                <p>Data Guru</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tapel.index') }}" class="nav-link">
                                <i class="fas fa-calendar-week nav-icon"></i>
                                <p>Data Tahun Pelajaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mapel.index') }}" class="nav-link">
                                <i class="fas fa-book nav-icon"></i>
                                <p>Data Mata Pelajaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kelas.index') }}" class="nav-link">
                                <i class="fas fa-layer-group nav-icon"></i>
                                <p>Data Kelas & Wali</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('santri.index') }}" class="nav-link">
                                <i class="fas fa-users nav-icon"></i>
                                <p>Data Santri</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pembelajaran.index') }}" class="nav-link">
                                <i class="fas fa-book-open nav-icon"></i>
                                <p>Data Pembelajaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fas fa-book-reader nav-icon"></i>
                                <p>Data Ekstrakulikuler</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-list-ol"></i>
                        <p>
                            Hasil Penilaian
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview bg-secondary">
                        <li class="nav-item">
                            <a href="{{ route('kehadiran.index') }}" class="nav-link">
                                <i class="fas fa-user-check nav-icon"></i>
                                <p>
                                    Input Kehadiran Santri
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('leger.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-table"></i>
                                <p>
                                    Leger Nilai Santri
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('statuspenilaian.index') }}" class="nav-link">
                                <i class="fas fa-check-circle nav-icon"></i>
                                <p>Status Penilaian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pengelolaannilai.index') }}" class="nav-link">
                                <i class="fas fa-check-square nav-icon"></i>
                                <p>Hasil Pengelolaan Nilai</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('nilairaport.index') }}" class="nav-link">
                                <i class="fas fa-clipboard-check nav-icon"></i>
                                <p>Nilai Raport Semester</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">SETTING RAPORT</li>
                <li class="nav-item">
                    <a href="{{ route('mapping.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-list-ol"></i>
                        <p>
                            Mapping Mapel
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tglraport.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-calendar-week"></i>
                        <p>
                            Input Tanggal Raport
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('cetakraport.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-print"></i>
                        <p>
                            Cetak Raport
                        </p>
                    </a>
                </li>

                {{-- @if (Session::get('kurikulum') == '2013')

        <!-- Kurikulum 2013 -->
        <li class="nav-header">SETTING RAPORT </li>
        <li class="nav-item">
          <a href="{{ route('k13mapping.index') }}" class="nav-link">
            <i class="nav-icon fas fa-list-ol"></i>
            <p>
              Mapping Mapel
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('k13kkm.index') }}" class="nav-link">
            <i class="nav-icon fas fa-greater-than-equal"></i>
            <p>
              KKM Mapel
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('k13interval.index') }}" class="nav-link">
            <i class="nav-icon fas fa-columns"></i>
            <p>
              Interval Predikat
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('k13sikap.index') }}" class="nav-link">
            <i class="nav-icon fas fa-clipboard"></i>
            <p>
              Butir-Butir Sikap
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('k13kd.index') }}" class="nav-link">
            <i class="nav-icon fas fa-clipboard-list"></i>
            <p>
              Data Kompetensi Dasar
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('k13tglraport.index') }}" class="nav-link">
            <i class="nav-icon fas fa-calendar-week"></i>
            <p>
              Input Tanggal Raport
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admink13validasi.index') }}" class="nav-link">
            <i class="nav-icon fas fa-check-square"></i>
            <p>
              Validasi Data
            </p>
          </a>
        </li>

        <li class="nav-header">HASIL RAPORT </li>
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-list-ol"></i>
            <p>
              Hasil Penilaian
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview bg-secondary">
            <li class="nav-item">
              <a href="{{ route('k13statuspenilaian.index') }}" class="nav-link">
                <i class="fas fa-check-circle nav-icon"></i>
                <p>Status Penilaian</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('k13pengelolaannilai.index') }}" class="nav-link">
                <i class="fas fa-check-square nav-icon"></i>
                <p>Hasil Pengelolaan Nilai</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('k13nilairaport.index') }}" class="nav-link">
                <i class="fas fa-clipboard-check nav-icon"></i>
                <p>Nilai Raport Semester</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="{{ route('rekapkehadiran.index') }}" class="nav-link">
            <i class="nav-icon fas fa-calendar-check"></i>
            <p>
              Rekap Kehadiran santri
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('k13leger.index') }}" class="nav-link">
            <i class="nav-icon fas fa-table"></i>
            <p>
              Leger Nilai santri
            </p>
          </a>
        </li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-print"></i>
            <p>
              Cetak Raport
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview bg-secondary">
            <li class="nav-item">
              <a href="{{ route('k13raportpts.index') }}" class="nav-link">
                <i class="fas fa-print nav-icon"></i>
                <p>Raport Tengah Semester</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('k13raportsemester.index') }}" class="nav-link">
                <i class="fas fa-print nav-icon"></i>
                <p>Raport Semester</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- End Kurikulum 2013 -->

        @elseif(Session::get('kurikulum') == '2006')

        <!-- Kurikulum 2006 -->
        <li class="nav-header">SETTING RAPORT KTSP 2006</li>
        <li class="nav-item">
          <a href="{{ route('mapping.index') }}" class="nav-link">
            <i class="nav-icon fas fa-list-ol"></i>
            <p>
              Mapping Mapel
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('kkm.index') }}" class="nav-link">
            <i class="nav-icon fas fa-greater-than-equal"></i>
            <p>
              KKM Mapel
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('interval.index') }}" class="nav-link">
            <i class="nav-icon fas fa-columns"></i>
            <p>
              Interval Predikat
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('tglraport.index') }}" class="nav-link">
            <i class="nav-icon fas fa-calendar-week"></i>
            <p>
              Input Tanggal Raport
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('adminvalidasi.index') }}" class="nav-link">
            <i class="nav-icon fas fa-check-square"></i>
            <p>
              Validasi Data
            </p>
          </a>
        </li>

        <li class="nav-header">HASIL RAPORT KTSP 2006</li>
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-list-ol"></i>
            <p>
              Hasil Penilaian
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview bg-secondary">
            <li class="nav-item">
              <a href="{{ route('adminktspstatuspenilaian.index') }}" class="nav-link">
                <i class="fas fa-check-circle nav-icon"></i>
                <p>Status Penilaian</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('adminktsppengelolaannilai.index') }}" class="nav-link">
                <i class="fas fa-check-square nav-icon"></i>
                <p>Hasil Pengelolaan Nilai</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('adminktspnilairaport.index') }}" class="nav-link">
                <i class="fas fa-clipboard-check nav-icon"></i>
                <p>Nilai Raport Semester</p>
              </a>
            </li>
          </ul>
        </li>


        <li class="nav-item">
          <a href="{{ route('adminrekapkehadiran.index') }}" class="nav-link">
            <i class="nav-icon fas fa-calendar-check"></i>
            <p>
              Rekap Kehadiran santri
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('adminktspleger.index') }}" class="nav-link">
            <i class="nav-icon fas fa-table"></i>
            <p>
              Leger Nilai santri
            </p>
          </a>
        </li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-print"></i>
            <p>
              Cetak Raport
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview bg-secondary">
            <li class="nav-item">
              <a href="{{ route('adminktspraportuts.index') }}" class="nav-link">
                <i class="fas fa-print nav-icon"></i>
                <p>Raport Tengah Semester</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('adminktspraportsemester.index') }}" class="nav-link">
                <i class="fas fa-print nav-icon"></i>
                <p>Raport Semester</p>
              </a>
            </li>
          </ul>
        </li>
        <!-- End Kurikulum 2006 -->

        @endif --}}

                <li class="nav-item bg-danger mt-2">
                    <a href="{{ route('logout') }}" class="nav-link" id="logoutBtn">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Keluar / Logout</p>
                    </a>

                    <!-- Tambahkan SweetAlert2 -->
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        document.getElementById("logoutBtn").addEventListener("click", function(event) {
                            event.preventDefault(); // Mencegah logout langsung

                            Swal.fire({
                                title: "Anda yakin ingin keluar?",
                                text: "Semua sesi akan berakhir dan Anda harus login kembali.",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#d33",
                                cancelButtonColor: "#3085d6",
                                confirmButtonText: "Ya, Keluar",
                                cancelButtonText: "Batal"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('logout') }}";
                                }
                            });
                        });
                    </script>

                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
