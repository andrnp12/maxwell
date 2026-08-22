<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../../src/classes/auth.php';
require_once '../../src/classes/konselor.php';

$data = new Konsultan();
$auth = new auth();
$auth->authOrNot();
$konsultan = $data->getAllKonsultan();
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
                            <div class="mb-4">
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="komunitas.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Daftar Konselor
                                    </h4>
                                    <p class="text-muted mb-0">
                                        Temukan konselor yang sesuai dengan kebutuhan Anda.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- end row -->
                    <div class="row">
                        <?php foreach ($konsultan as $index => $konsul) : ?>
                            <div class="col-12 col-xl-6 col-md-6">
                                <div class="text-decoration-none">
                                    <div class="card mb-3 border shadow-sm" style="border-radius: 1.25rem; background-image: radial-gradient( circle farthest-corner at 10% 20%,  rgba(0,0,70,1) 0.3%, rgba(28,181,224,1) 90.2% );">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-3 text-center">
                                                <div style="width: 56px; height: 56px; border-radius: 50%; background-color: rgba(233, 236, 239, 0.5); display: inline-flex; align-items: center; justify-content: center;">
                                                    <img
                                                        alt=""
                                                        class="rounded-circle"
                                                        style="width: 40px; height: 40px; object-fit: cover;"
                                                        src="<?= !empty($konsul['foto']) ? '/uploads/profile/' . htmlspecialchars($konsul['foto']) : '/uploads/profile/default.webp' ?>"
                                                        onerror="this.onerror=null; this.src='/uploads/profile/default.webp';">

                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="card-body" style="padding-left: 0px;">
                                                    <h5 class="card-title mb-0 font-weight-bold text-white">
                                                        <?= $konsul['name'] ?>
                                                    </h5>
                                                    <p class="card-text mb-0 text-white-50">
                                                        <small class="text-truncate d-block" style="max-width: 100%;">
                                                            <?= $konsul['deskripsi'] ?>
                                                        </small>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <button
                                                    class="btn btn-light btn-join px-2 py-1"
                                                    style="border-radius: 1.25rem; margin-left: 15px;"
                                                    data-id="chat.php?id=<?= $konsul['id'] ?>&type=personal">
                                                    Hubungi
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
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