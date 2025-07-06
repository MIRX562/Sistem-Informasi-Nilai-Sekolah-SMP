<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['id'])) {
    header('location:../index.php');
}

$id = $_SESSION['id'];
$dataprofile = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$row = mysqli_fetch_array($dataprofile);
?>
<?php
require_once('layout/header.php');
?>
<!-- Main Container -->
<div class="main-container container-fluid">
    <!-- Page Container -->
    <div class="page-container">
        <!-- Sidebar -->
        <?php require_once('layout/sidebar.php'); ?>
        <!-- /Sidebar -->
        <!-- Page Content -->
        <div class="page-content">
            <!-- Page Breadcrumb -->
            <div class="page-breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="#">Home</a>
                    </li>
                </ul>
            </div>
            <!-- /Page Breadcrumb -->
            <!-- Page Header -->
            <div class="page-header position-relative">
                <div class="header-title">
                </div>
                <!--Header Buttons-->
                <div class="header-buttons">
                    <a class="sidebar-toggler" href="#">
                        <i class="fa fa-arrows-h"></i>
                    </a>
                    <a class="refresh" id="refresh-toggler" href="#">
                        <i class="glyphicon glyphicon-refresh"></i>
                    </a>
                    <a class="fullscreen" id="fullscreen-toggler" href="#">
                        <i class="glyphicon glyphicon-fullscreen"></i>
                    </a>
                </div>
                <!--Header Buttons End-->
            </div>
            <!-- /Page Header -->
            <!-- Page Body -->
            <div class="page-body">
                <!-- Your Content Goes Here -->
                <div class="row">
                    <?php
                    // ===================== USERS ROUTES =====================
                    if (isset($_GET['users'])) {
                        if ($_GET['users'] == 'admin') {
                            include('tables/admin_table.php');
                        } elseif ($_GET['users'] == 'admin-create') {
                            include('forms/create_admin.php');
                            include('core/create.php');
                        } elseif ($_GET['users'] == 'guru') {
                            include('tables/guru_table.php');
                        } elseif ($_GET['users'] == 'guru-create') {
                            include('forms/create_guru.php');
                            include('core/create.php');
                        } elseif ($_GET['users'] == 'siswa') {
                            include('tables/siswa_table.php');
                        } elseif ($_GET['users'] == 'siswa-create') {
                            include('forms/create_siswa.php');
                            include('core/create.php');
                        }
                        // ===================== END USERS ROUTES =====================
                    } elseif (isset($_GET['admin-edit'])) {
                        include('core/edit.php');
                        include('forms/edit_admin.php');
                    } elseif (isset($_GET['admin-del'])) {
                        include('core/delete.php');
                    } elseif (isset($_GET['guru-edit'])) {
                        include('core/edit.php');
                        include('forms/edit_guru.php');
                    } elseif (isset($_GET['guru-del'])) {
                        include('core/delete.php');
                    } elseif (isset($_GET['siswa-edit'])) {
                        include('core/edit.php');
                        include('forms/edit_siswa.php');
                    } elseif (isset($_GET['siswa-del'])) {
                        include('core/delete.php');
                        // ===================== AKADEMIK ROUTES =====================
                    } elseif (isset($_GET['akademik'])) {
                        if ($_GET['akademik'] == 'kelas') {
                            include('tables/kelas_table.php');
                        } elseif ($_GET['akademik'] == 'kelas-create') {
                            include('forms/create_kelas.php');
                            include('core/create.php');
                        } elseif ($_GET['akademik'] == 'tahun') {
                            include('tables/tahun_table.php');
                        } elseif ($_GET['akademik'] == 'tahun-create') {
                            include('forms/create_tahun.php');
                            include('core/create.php');
                        } elseif ($_GET['akademik'] == 'pelajaran') {
                            include('tables/pelajaran_table.php');
                        } elseif ($_GET['akademik'] == 'pelajaran-create') {
                            include('forms/create_pelajaran.php');
                            include('core/create.php');
                        } elseif ($_GET['akademik'] == 'sekolah') {
                            include('tables/sekolah_table.php');
                        } elseif ($_GET['akademik'] == 'sekolah-create') {
                            include('forms/create_sekolah.php');
                            include('core/create.php');
                        } elseif ($_GET['akademik'] == 'guru_mengajar') {
                            if (isset($_GET['tahun_id']) && isset($_GET['semester_id'])) {
                                include('guru_mengajar/list_guru.php');
                            } else {
                                include('guru_mengajar/list_tahun.php');
                            }
                        } elseif ($_GET['akademik'] == 'guru_mengajar-list_guru' && isset($_GET['tahun_id']) && isset($_GET['semester_id'])) {
                            include('guru_mengajar/list_guru.php');
                        } elseif ($_GET['akademik'] == 'guru_mengajar-manage' && isset($_GET['guru_id']) && isset($_GET['tahun_id']) && isset($_GET['semester_id'])) {
                            include('guru_mengajar/manage.php');
                        } elseif ($_GET['akademik'] == 'guru_mengajar-create' && isset($_GET['guru_id']) && isset($_GET['tahun_id']) && isset($_GET['semester_id'])) {
                            include('forms/create_guru_mengajar.php');
                        } elseif ($_GET['akademik'] == 'guru_mengajar-edit' && isset($_GET['id']) && isset($_GET['guru_id']) && isset($_GET['tahun_id']) && isset($_GET['semester_id'])) {
                            include('forms/edit_guru_mengajar.php');
                        } elseif ($_GET['akademik'] == 'guru_mengajar-del' && isset($_GET['id'])) {
                            include('core/delete_guru_mengajar.php');
                        }
                        // ===================== END AKADEMIK ROUTES =====================
                    } elseif (isset($_GET['kelas-edit'])) {
                        include('core/edit.php');
                        include('forms/edit_kelas.php');
                    } elseif (isset($_GET['kelas-del'])) {
                        include('core/delete.php');
                    } elseif (isset($_GET['tahun-edit'])) {
                        include('core/edit.php');
                        include('forms/edit_tahun.php');
                    } elseif (isset($_GET['tahun-del'])) {
                        include('core/delete.php');
                    } elseif (isset($_GET['set_active_tahun'])) {
                        include('core/update.php');
                    } elseif (isset($_GET['pelajaran-edit'])) {
                        include('core/edit.php');
                        include('forms/edit_pelajaran.php');
                    } elseif (isset($_GET['pelajaran-del'])) {
                        include('core/delete.php');
                    } elseif (isset($_GET['sekolah-edit'])) {
                        include('core/edit.php');
                        include('forms/edit_sekolah.php');
                    } elseif (isset($_GET['sekolah-del'])) {
                        include('core/delete.php');
                    } elseif (isset($_GET['nilai'])) {
                        if ($_GET['nilai'] == 'tampil') {
                            include('forms/cari_nilai.php');
                        } elseif ($_GET['nilai'] == 'input') {
                            include('forms/input_nilai.php');
                            include('core/create.php');
                        } elseif ($_GET['nilai'] == 'cetak') {
                            include('forms/cetak_nilai_rapor.php');
                        }
                        // elseif ($_GET['nilai'] == 'generate') {
                        //     include('core/generate_report_pdf.php');
                        // }
                        // ===================== END NILAI ROUTES =====================
                    } elseif (isset($_GET['nilai-edit'])) {
                        include('core/edit.php');
                        include('forms/edit_nilai.php');
                    } elseif (isset($_GET['nilai-del'])) {
                        include('core/delete.php');
                        // ===================== MODUL ROUTES =====================
                    } elseif (isset($_GET['modul'])) {
                        if ($_GET['modul'] == 'upload') {
                            include('forms/upload.php');
                            include('core/create.php');
                        } elseif ($_GET['modul'] == 'download') {
                            include('tables/download_table.php');
                        }
                        // ===================== END MODUL ROUTES =====================
                    } elseif (isset($_GET['modul-del'])) {
                        include('core/delete.php');
                        // ===================== ARTIKEL ROUTES =====================
                    } elseif (isset($_GET['artikel'])) {
                        if ($_GET['artikel'] == 'list') {
                            include('tables/artikel_table.php');
                        } elseif ($_GET['artikel'] == 'artikel-create') {
                            include('forms/create_artikel.php');
                            include('core/create.php');
                        } elseif ($_GET['artikel'] == 'kategori') {
                            include('tables/kategori_table.php');
                        } elseif ($_GET['artikel'] == 'kategori-create') {
                            include('forms/create_kategori.php');
                            include('core/create.php');
                        }
                        // ===================== END ARTIKEL ROUTES =====================
                    } elseif (isset($_GET['artikel-edit'])) {
                        include('core/edit.php');
                        include('forms/edit_artikel.php');
                    } elseif (isset($_GET['artikel-del'])) {
                        include('core/delete.php');
                    } elseif (isset($_GET['kategori-del'])) {
                        include('core/delete.php');
                    } elseif (isset($_GET['kategori-edit'])) {
                        include('core/edit.php');
                        include('forms/edit_kategori.php');
                        // ===================== PROFILE & SETTINGS ROUTES =====================
                    } elseif (isset($_GET['profile'])) {
                        include('core/edit.php');
                        include('tables/profile.php');
                    } elseif (isset($_GET['change'])) {
                        include('core/edit.php');
                        include('forms/change_password.php');
                        // ===================== DATA IMPORT ROUTE =====================
                    } elseif (isset($_GET['data']) && $_GET['data'] == 'import') {
                        include('core/data_import.php');
                        // ===================== DETAIL ROUTES =====================
                    } elseif (isset($_GET['kelas-det'])) {
                        include('tables/detail_kelas.php');
                    } elseif (isset($_GET['pelajaran-det'])) {
                        include('tables/detail_pelajaran.php');
                    } elseif (isset($_GET['tahun-detail'])) {
                        include('tables/tahun_detail.php');
                    } else {
                        // Dashboard root: show dashboard based on user access
                        if ($row['access'] == 'admin') {
                            include('dashboard_admin.php');
                        } elseif ($row['access'] == 'guru') {
                            include('dashboard_guru.php');
                        } elseif ($row['access'] == 'orang_tua') {
                            include('dashboard_orangtua.php');
                        } else {
                            echo '<div class="alert alert-info">Selamat datang di dashboard.</div>';
                        }
                    }
                    ?>
                </div>
            </div>
            <!-- /Page Body -->
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Container -->
    <!-- Main Container -->
</div>
<?php
require_once('layout/footer.php');
?>