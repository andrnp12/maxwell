<?php
session_start();
require_once '../../src/classes/auth.php';
require_once '../../src/classes/informasi.php';

$auth = new auth();
$auth->authOrNot();

$informasi = new Informasi();
$kategoriId = (int) ($_GET['id'] ?? 0);
$dataContents = $informasi->getContentsByKategori($kategoriId);
// $jumlahIformasi = $informasi->countContent();

?>

<!--header start-->
<?php include('../include/header.php'); ?>
<!--headere end-->

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- ========== Topbar Start ========== -->
        <?php include('../include/topbar.php'); ?>
        <!-- ========== Topbar End ========== -->
        <!-- ========== Left Sidebar Start ========== -->
        <?php include('../include/sidebar.php'); ?>
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
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="informasi.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Daftar Informasi
                                    </h4>
                                    <p class="text-muted mb-0">
                                        Dapatkan berbagai macam informasi terbaru
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- end row -->
                    <?php foreach ($dataContents as $contents) : ?>
                        <div class="col-12 col-xl-6 col-md-6">
                            <a href="detail-informasi.php?id=<?= $contents['id'] ?>"
                                class="text-decoration-none">
                                <div class="card mb-3 text-white shadow-sm"
                                    style="border-radius: 1.25rem;">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-4 text-center">
                                            <img
                                                class="rounded-circle"
                                                src="<?= !empty($contents['foto']) ? '/uploads/contents/' . htmlspecialchars($contents['foto']) : '/uploads/profile/default.webp' ?>"
                                                alt="icon"
                                                style="width: 56px; height: 56px; object-fit: cover;"
                                                onerror="this.onerror=null; this.src='/uploads/profile/default.webp';" />
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body" style="padding-left: 0px;">
                                                <h5 class="card-title mb-0 font-weight-bold text-black">
                                                    <?= $contents['judul'] ?>
                                                </h5>
                                                <p class="card-text mb-0">
                                                    <small class="text-truncate d-block text-black" style="max-width: 100%;">
                                                        <?= $contents['deskripsi'] ?>
                                                    </small>
                                                </p>
                                                <small class="badge bg-light px-2 py-1 rounded-pill">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <?= $contents['updated_at'] ?>
                                                </small>
                                            </div>
                                        </div>
                                        <!-- <div class="col-3">
                                            <div class="text-end">
                                                <button class="btn btn-light px-2 py-1" style="border-radius: 1.25rem;">
                                                    Lihat
                                                </button>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </a>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        <!-- Footer Start -->
        <?php include("../include/footer.php"); ?>
        <!-- end Footer -->
    </div>
    <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <!-- Right Sidebar -->
    <?php include("../include/right-sidebar.php"); ?>
    <!-- /Right-bar -->
    <!-- javascript -->
    <?php include("../include/script.php"); ?>
    <!-- end javascript -->
</body>

</html>