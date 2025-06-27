<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-secondary">
    <!-- Brand Logo -->
    <a href="{{ route('guru.dashboard') }}" class="brand-link">
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
                    <a href="{{ route('guru.dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-header">RAPORT</li>
                <li class="nav-item">
                    <a href="{{ route('bobot.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-balance-scale"></i>
                        <p>
                            Bobot Penilaian
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-list-ol"></i>
                        <p>
                            Input Nilai
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview bg-secondary">
                        <li class="nav-item">
                            <a href="{{ route('nilaiabsen.index') }}" class="nav-link">
                                <i class="fas fa-edit nav-icon"></i>
                                <p>Nilai Absen</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('nilaisetoran.index') }}" class="nav-link">
                                <i class="fas fa-edit nav-icon"></i>
                                <p>Nilai Setoran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('nilaiuas.index') }}" class="nav-link">
                                <i class="fas fa-edit nav-icon"></i>
                                <p>Nilai UAS</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                        <i class="nav-icon fas fa-book-reader"></i>
                        <p>
                            Input Nilai Ekstrakulikuler
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-check"></i>
                        <p>
                            Nilai Akhir Raport
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview bg-secondary">
                        <li class="nav-item">
                            <a href="{{ route('kirimnilai.index') }}" class="nav-link">
                                <i class="fas fa-paper-plane nav-icon"></i>
                                <p>Kirim Nilai Akhir</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('lihatnilai.index') }}" class="nav-link">
                                <i class="fas fa-eye nav-icon"></i>
                                <p>Lihat Nilai Terkirim</p>
                            </a>
                        </li>
                    </ul>
                </li>

                
                <li class="nav-item">
                    <a href="" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Input Deskripsi Siswa
                        </p>
                    </a>
                </li>

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
