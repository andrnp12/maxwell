<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<head>
    <meta charset="utf-8" />
    <title>
        Login | StarCode Kh - Minimal Admin &amp; Dashboard Template
    </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Premium Multipurpose Admin &amp; Dashboard Template" name="description">
    <meta content="admintem.com" name="author" />
    <!-- App favicon -->
    <link href="../assets/images/favicon.ico" rel="shortcut icon" />
    <!-- preloader css -->
    <link href="../assets/css/preloader.min.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap Css -->
    <link href="../assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="../assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    </link>
    </meta>
</head>

<!--header start-->
<?php include('component/header.php'); ?>
<!--header end-->

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- ========== Topbar Start ========== -->
        <?php include('component/topbar.php'); ?>
        <!-- ========== Topbar End ========== -->
        <!-- ========== Left Sidebar Start ========== -->
        <?php include('component/sidebar.php'); ?>
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
                            <div class="row d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-weight-bold mb-1">
                                    Halo, Davied Indra!
                                </h4>
                                <p class="text-muted">
                                    Siap untuk belajar lagi hari ini? Ayo mulai!
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-xl-12">
                            <!-- card -->
                            <div class="card card-h-100">
                                <!-- card body -->
                                <div class="card-body">
                                    <div class="d-flex flex-wrap align-items-center mb-4">
                                        <h4 class="card-title me-2">
                                            Progress Belajar
                                        </h4>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-sm">
                                            <div class="apex-charts" data-colors='["#5156be", "#34c38f"]' id="invested-overview">
                                            </div>
                                        </div>
                                        <div class="col-sm align-self-center">
                                            <div class="mt-4 mt-sm-0">
                                                <div>
                                                    <p class="mb-2">
                                                        Terus belajar dan kembangkan diri kamu untuk menjadi lebih baik lagi!
                                                    </p>
                                                    <button class="btn btn-primary btn-rounded btn-sm waves-effect waves-light" type="button">
                                                        Lihat Detail
                                                        <span>
                                                            <i class="fas fa-angle-right"></i>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row-->
                    <div class="row">
                        <div class="d-flex flex-wrap align-items-center mb-4">
                            <h5 class="font-weight-bold me-2">
                                Daftar Menu
                            </h5>
                        </div>
                        <div class="col-6 col-xl-3 col-md-6">
                            <!-- card -->
                            <div class="card card-h-100">
                                <!-- card body -->
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <h4 class="mb-3">
                                                <img src="../assets/icon/mortarboard.webp" alt="icon" style="width: 40px; height: 40px;" />
                                            </h4>
                                            <span class="text-muted lh-1 d-block text-truncate">
                                                Modul Edukasi
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                        <div class="col-6 col-xl-3 col-md-6">
                            <!-- card -->
                            <div class="card card-h-100">
                                <!-- card body -->
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <h4 class="mb-3">
                                                <img src="../assets/icon/edition.webp" alt="icon" style="width: 40px; height: 40px;" />
                                            </h4>
                                            <span class="text-muted lh-1 d-block text-truncate">
                                                Kelas Skill
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                        <div class="col-6 col-xl-3 col-md-6">
                            <!-- card -->
                            <div class="card card-h-100">
                                <!-- card body -->
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <h4 class="mb-3">
                                                <img src="../assets/icon/slack.webp" alt="icon" style="width: 40px; height: 40px;" />
                                            </h4>
                                            <span class="text-muted lh-1 d-block text-truncate">
                                                Kom. Sebaya
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                        <div class="col-6 col-xl-3 col-md-6">
                            <!-- card -->
                            <div class="card card-h-100">
                                <!-- card body -->
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <h4 class="mb-3">
                                                <img src="../assets/icon/like.webp" alt="icon" style="width: 40px; height: 40px;" />
                                            </h4>
                                            <span class="text-muted lh-1 d-block text-truncate">
                                                Kesiapan
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                        <div class="col-6 col-xl-3 col-md-6">
                            <!-- card -->
                            <div class="card card-h-100">
                                <!-- card body -->
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <h4 class="mb-3">
                                                <img src="../assets/icon/whatsapp.webp" alt="icon" style="width: 40px; height: 40px;" />
                                            </h4>
                                            <span class="text-muted lh-1 d-block text-truncate">
                                                Konseling
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                        <div class="col-6 col-xl-3 col-md-6">
                            <!-- card -->
                            <div class="card card-h-100">
                                <!-- card body -->
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <h4 class="mb-3">
                                                <img src="../assets/icon/family.webp" alt="icon" style="width: 40px; height: 40px;" />
                                            </h4>
                                            <span class="text-muted lh-1 d-block text-truncate">
                                                Ortu Peduli
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row-->
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <!-- Footer Start -->
            <?php include("component/footer.php"); ?>
            <!-- end Footer -->
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <!-- Right Sidebar -->
    <?php include("component/right-sidebar.php"); ?>
    <!-- /Right-bar -->
    <!-- javascript -->
    <?php include("component/script.php"); ?>
    <!-- end javascript -->

</body>

</html>