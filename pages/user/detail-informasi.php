<?php
session_start();
require_once '../../src/classes/auth.php';
require_once '../../src/classes/informasi.php';

$auth = new auth();
$auth->authOrNot();

$informasi = new Informasi();
$contentId = (int) ($_GET['id'] ?? 0);
$detail = $informasi->getContentById($contentId);
// $jumlahIformasi = $informasi->countContent();

?>

<!--header start-->
<?php include('../include/header.php'); ?>
<!--headere end-->

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php include("../include/topbar.php"); ?>
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
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="detail-kat-info.php?id=<?= $detail['category_id'] ?>">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Detail Informasi
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow-sm" style="border-radius: 1.25rem;">
                                <div class="card-body">
                                    <?php if ($detail): ?>
                                        <div class="">
                                            <div class="mb-3">
                                                <h2 class="fw-bold ">
                                                    <?= htmlspecialchars($detail['judul'] ?? ''); ?>
                                                </h2>
                                            </div>
                                            <div class="mb-4">
                                                <img
                                                    class="img-thumbnail mx-auto d-block"
                                                    src="<?= !empty($detail['foto']) ? '/uploads/contents/' . htmlspecialchars($detail['foto']) : '/uploads/profile/default.webp' ?>"
                                                    alt="image"
                                                    onerror="this.onerror=null; this.src='/uploads/profile/default.webp';" />
                                            </div>
                                            <div class="mt-4">
                                                <div class="text-muted font-size-14">
                                                    <p class="fs-6">
                                                        <?= htmlspecialchars($detail['deskripsi'] ?? ''); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-4">
                                            <p class="text-muted">Informasi tidak ditemukan.</p>
                                            <a href="detail-kat-info.php?id=<?= $detail['category_id'] ?? 0 ?>" class="btn btn-outline-primary">Kembali</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                        <!-- end card -->
                    </div>
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
    </div>
</body>

</html>