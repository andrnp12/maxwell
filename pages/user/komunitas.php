<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once 'classes/konselor.php';
require_once '../../src/classes/auth.php';

$auth = new auth();
$auth->authOrNot();

$data = new Konsultan();

$konsultan = $data->getAllKonsultan();

include 'src/include/header.php';
?>


<?php include '../include/header.php'; ?>

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php include '../include/topbar.php'; ?>
        <!-- ========== Left Sidebar Start ========== -->
        <?php include '../include/sidebar.php'; ?>
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="row d-sm-flex align-items-center justify-content-between mb-2">
                                <h4 class="mb-sm-0 font-weight-bold mb-1">
                                    Forum
                                </h4>
                                <p class="text-muted">
                                    Bergabunglah dengan komunitas dan berinteraksi dengan sesama!
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- end row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between pb-4">
                                <h5 class="mb-sm-0">
                                    Daftar Komunitas
                                </h5>
                                <div class="page-title-right">
                                    <a class="btn btn-primary btn-rounded btn-sm waves-effect waves-light" href="daftar-komunitas.php">
                                        Lihat Semua
                                        <span>
                                            <i class="fas fa-angle-right"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-6 col-md-6">
                            <div class="card mb-3">
                                <div class="row g-0 align-items-center">
                                    <div class="col-3 text-center">
                                        <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items: center; justify-content: center;">
                                            <img src="/assets/icon/focus-group.webp" alt="icon" style="width: 40px; height: 40px;" />
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-body">
                                            <h5 class="card-title mb-0 font-weight-bold">
                                                Sahabat Tumbuh
                                            </h5>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Perkumpulan tumbuh bersama.
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-6 col-md-6">
                            <div class="card mb-3">
                                <div class="row g-0 align-items-center">
                                    <div class="col-3 text-center">
                                        <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items: center; justify-content: center;">
                                            <img src="/assets/icon/focus-group.webp" alt="icon" style="width: 40px; height: 40px;" />
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-body">
                                            <h5 class="card-title mb-0 font-weight-bold">
                                                Anti Nikah Dini
                                            </h5>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Perkumpulan tumbuh bersama.
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-6 col-md-6">
                            <div class="card mb-3">
                                <div class="row g-0 align-items-center">
                                    <div class="col-3 text-center">
                                        <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items: center; justify-content: center;">
                                            <img src="/assets/icon/focus-group.webp" alt="icon" style="width: 40px; height: 40px;" />
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-body">
                                            <h5 class="card-title mb-0 font-weight-bold">
                                                Remaja Berencana
                                            </h5>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Perkumpulan tumbuh bersama.
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between pb-4">
                                <h5 class="mb-sm-0">
                                    Hubungi Konseling
                                </h5>
                                <?= print_r($konsultan) ?>
                                <div class="page-title-right">
                                    <a class="btn btn-primary btn-rounded btn-sm waves-effect waves-light" href="daftar-konseling.php">
                                        Lihat Semua
                                        <span>
                                            <i class="fas fa-angle-right"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <<<<<<< HEAD:komunitas.php
                            <?php foreach ($konsultan as $konsul) : ?>
                            <div class="col-xl-3 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="mx-auto mb-4">
                                        <img alt="" class="avatar-xl rounded-circle img-thumbnail" src="assets/images/users/avatar-2.jpg">
                                    </div>
                                    <h5 class="font-size-16 mb-1">
                                        <a class="text-body" href="#">
                                            <?= $konsul['name'] ?>
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-2">
                                        <?= $konsul['deskripsi'] ?>
                                    </p>
                                </div>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-light text-truncate" type="button">
                                        <i class="uil uil-user me-1">
                                        </i>
                                        Profile
                                    </button>
                                    <button class="btn btn-outline-light text-truncate" type="button">
                                        <a href="chat.php?chat_type=personal&id_lawan=<?= $konsul['id'] ?>">
                                            <i class="uil uil-envelope-alt me-1">
                                            </i>
                                            Message
                                        </a>
                                    </button>
                                    =======
                                    <div class="col-xl-3 col-sm-6">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <div class="mx-auto mb-4">
                                                    <img alt="" class="avatar-xl rounded-circle img-thumbnail" src="/assets/images/users/avatar-2.jpg">
                                                </div>
                                                <h5 class="font-size-16 mb-1">
                                                    <a class="text-body" href="#">
                                                        Phyllis Gatlin
                                                    </a>
                                                </h5>
                                                <p class="text-muted mb-2">
                                                    Full Stack Developer
                                                </p>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-outline-light text-truncate" type="button">
                                                    <i class="uil uil-user me-1">
                                                    </i>
                                                    Profile
                                                </button>
                                                <button class="btn btn-outline-light text-truncate" type="button">
                                                    <i class="uil uil-envelope-alt me-1">
                                                    </i>
                                                    Message
                                                </button>
                                            </div>
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    <!-- end col -->
                                    <div class="col-xl-3 col-sm-6">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <div class="mx-auto mb-4">
                                                    <img alt="" class="avatar-xl rounded-circle img-thumbnail" src="/assets/images/users/avatar-1.jpg">
                                                    >>>>>>> main:pages/user/komunitas.php
                                                </div>
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        <<<<<<< HEAD:komunitas.php
                                            <?php endforeach; ?>=======<!-- end card -->
                                    </div>
                                    <!-- end col -->
                                    <div class="col-xl-3 col-sm-6">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <div class="mx-auto mb-4">
                                                    <img alt="" class="avatar-xl rounded-circle img-thumbnail" src="/assets/images/users/avatar-3.jpg">
                                                </div>
                                                <h5 class="font-size-16 mb-1">
                                                    <a class="text-body" href="#">
                                                        Darlene Smith
                                                    </a>
                                                </h5>
                                                <p class="text-muted mb-2">
                                                    UI/UX Designer
                                                </p>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-outline-light text-truncate" type="button">
                                                    <i class="uil uil-user me-1">
                                                    </i>
                                                    Profile
                                                </button>
                                                <button class="btn btn-outline-light text-truncate" type="button">
                                                    <i class="uil uil-envelope-alt me-1">
                                                    </i>
                                                    Message
                                                </button>
                                            </div>
                                        </div>
                                        <!-- end card -->
                                    </div>
                                    >>>>>>> main:pages/user/komunitas.php
                                    <!-- end col -->
                                </div>
                                <!-- end row -->
                            </div>
                            <!-- container-fluid -->
                    </div>
                    <!-- End Page-content -->
                    <?php include '../include/footer.php'; ?>
                </div>
                <!-- end main content-->
            </div>
            <!-- END layout-wrapper -->
            <!-- Right Sidebar -->
            <?php include '../include/right-sidebar.php'; ?>
            <!-- /Right-bar -->
            <!-- Right bar overlay-->
            <div class="rightbar-overlay">
            </div>
            <!-- JAVASCRIPT -->
            <?php include '../include/script.php'; ?>
</body>

</html>