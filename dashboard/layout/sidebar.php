<!-- Page Sidebar -->
<div class="page-sidebar" id="sidebar">
    <!-- Sidebar Menu -->
    <ul class="nav sidebar-menu">
        <!--Dashboard-->
        <li>
            <a href="../index.php">
                <i class="menu-icon glyphicon glyphicon-home"></i>
                <span class="menu-text"> Home </span>
            </a>
        </li>
        <li>
            <a href="index.php">
                <i class="menu-icon glyphicon glyphicon-user"></i>
                <span class="menu-text"> <?php echo $_SESSION['name']; ?> </span>
            </a>
        </li>
        <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa fa-gear"></i>
                <span class="menu-text"> Setting </span>

                <i class="menu-expand"></i>
            </a>

            <ul class="submenu">
                <li>
                    <a href="?profile=<?php echo $_SESSION['id']; ?>">
                        <span class="menu-text">My Profile</span>
                    </a>
                </li>
                <li>
                    <a href="../logout.php">
                        <span class="menu-text">Logout</span>
                    </a>
                </li>
            </ul>
        </li>
        <?php
        if (isset($_SESSION['access'])) {
            if ($_SESSION['access'] == 'admin') {
                ?>
                <li>
                    <a href="#" class="menu-dropdown">
                        <i class="menu-icon fa fa-users"></i>
                        <span class="menu-text"> Users </span>

                        <i class="menu-expand"></i>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="?users=admin">
                                <span class="menu-text">Admin</span>
                            </a>
                        </li>
                        <li>
                            <a href="?users=guru">
                                <span class="menu-text">Guru</span>
                            </a>
                        </li>
                        <li>
                            <a href="?users=siswa">
                                <span class="menu-text">Siswa</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="menu-dropdown">
                        <i class="menu-icon fa fa-book"></i>
                        <span class="menu-text"> Akademik </span>

                        <i class="menu-expand"></i>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="?akademik=kelas">
                                <span class="menu-text">Kelas</span>
                            </a>
                        </li>
                        <li>
                            <a href="?akademik=tahun">
                                <span class="menu-text">Tahun ajaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="?akademik=pelajaran">
                                <span class="menu-text">Mata Pelajaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="?akademik=sekolah">
                                <span class="menu-text">Data Sekolah</span>
                            </a>
                        </li>
                        <li>
                            <a href="?akademik=guru_mengajar">
                                <span class="menu-text">Guru Mengajar</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="menu-dropdown">
                        <i class="menu-icon fa fa-signal"></i>
                        <span class="menu-text"> Nilai </span>

                        <i class="menu-expand"></i>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="?nilai=input">
                                <span class="menu-text">Input Nilai</span>
                            </a>
                        </li>
                        <li>
                            <a href="?nilai=tampil">
                                <span class="menu-text">Tampilkan Nilai</span>
                            </a>
                        </li>
                        <li>
                            <a href="?nilai=cetak">
                                <span class="menu-text">Cetak Nilai</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="menu-dropdown">
                        <i class="menu-icon fa fa-database"></i>
                        <span class="menu-text"> Data </span>

                        <i class="menu-expand"></i>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="?data=import">
                                <span class="menu-text">Import Data</span>
                            </a>
                        </li>
                    </ul>
                </li>


                <?php
            } elseif ($_SESSION['access'] == 'guru') {
                ?>
                <li>
                    <a href="#" class="menu-dropdown">
                        <i class="menu-icon fa fa-signal"></i>
                        <span class="menu-text"> Nilai </span>

                        <i class="menu-expand"></i>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="?nilai=input">
                                <span class="menu-text">Input Nilai</span>
                            </a>
                        </li>
                        <li>
                            <a href="?nilai=tampil">
                                <span class="menu-text">Tampilkan Nilai</span>
                            </a>
                        </li>
                        <li>
                            <a href="?nilai=cetak">
                                <span class="menu-text">Cetak Nilai</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php
            } elseif ($_SESSION['access'] == 'orang_tua') {
                ?>
                <li>
                    <a href="#" class="menu-dropdown">
                        <i class="menu-icon fa fa-signal"></i>
                        <span class="menu-text"> Nilai </span>

                        <i class="menu-expand"></i>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="?nilai=tampil">
                                <span class="menu-text">Tampilkan Nilai</span>
                            </a>
                        </li>
                        <li>
                            <a href="?nilai=cetak">
                                <span class="menu-text">Cetak Nilai</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php
            }
        }
        ?>
    </ul>
    <!-- /Sidebar Menu -->
</div>
<!-- /Page Sidebar -->