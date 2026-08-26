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
<style>
    .category-card {
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .category-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15) !important;
    }

    .category-icon {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.20);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .category-badge {
        background: rgba(255, 255, 255, 0.18);
        font-weight: 500;
    }
</style>

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
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-4">
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="informasi.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="row d-sm-flex align-items-center justify-content-between mb-2">
                                <h4 class="mb-sm-0 font-weight-bold mb-1">
                                    Daftar Informasi
                                </h4>
                                <p class="text-muted">
                                    Dapatkan berbagai macam informasi terbaru
                                </p>
                            </div>
                        </div>
                        <?php foreach ($dataContents as $contents) : ?>
                            <div class="col-12 col-md-6">
                                <a href="detail-informasi.php?id=<?= $contents['id'] ?>"
                                    class="text-decoration-none">

                                    <div class="card mb-3 text-white border-0 shadow-lg category-card"
                                        style="
                    border-radius: 1.25rem;
                    background-image: radial-gradient(
                        circle farthest-corner at 10% 20%,
                        rgba(14,174,87,1) 0%,
                        rgba(12,116,117,1) 90%
                    );
                 ">

                                        <div class="card-body p-4">
                                            <div class="col">

                                                <!-- Icon -->
                                                <div class="row">
                                                    <div class="">
                                                        <img src="/uploads/contents/<?= $contents['foto'] ?>" alt="" class="img-fluid rounded">
                                                    </div>
                                                </div>

                                                <!-- Content -->
                                                <div class="row mt-3">

                                                    <h4 class="mb-1 fw-bold">
                                                        <?= $contents['judul'] ?>
                                                    </h4>

                                                    <p class="d-block opacity-75 fs-6 text-truncate" style="max-width: 100%;">
                                                        <?= $contents['deskripsi'] ?>
                                                    </p>

                                                    <small class="d-block opacity-75">
                                                        <i class="fas fa-calendar-alt">
                                                            <?= $contents['updated_at'] ?>
                                                        </i>
                                                    </small>
                                                </div>
                                            </div>
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