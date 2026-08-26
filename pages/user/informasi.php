<?php
session_start();
require_once '../../src/classes/auth.php';
require_once '../../src/classes/informasi.php';

$auth = new auth();
$auth->authOrNot();

$informasi = new Informasi();
$dataKatergori = $informasi->getAllKategori();
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
                            <div class="row d-sm-flex align-items-center justify-content-between mb-2">
                                <h4 class="mb-sm-0 font-weight-bold mb-1">
                                    Daftar Informasi
                                </h4>
                                <p class="text-muted">
                                    Dapatkan berbagai macam informasi terbaru
                                </p>
                            </div>
                        </div>

                        <?php
                        $iconKategori = [
                            'berita'     => 'fas fa-newspaper',
                            'loker'      => 'fas fa-briefcase',
                            'lowongan'   => 'fas fa-briefcase',
                            'pengumuman' => 'fas fa-bullhorn',
                            'event'      => 'fas fa-calendar-alt',
                            'pendidikan' => 'fas fa-graduation-cap',
                            'artikel'    => 'fas fa-file-alt',
                        ];
                        ?>

                        <?php foreach ($dataKatergori as $kategori) : ?>

                            <?php
                            $judul = strtolower($kategori['judul_kategori']);
                            $icon = 'fas fa-folder';

                            foreach ($iconKategori as $keyword => $value) {
                                if (str_contains($judul, $keyword)) {
                                    $icon = $value;
                                    break;
                                }
                            }
                            ?>

                            <div class="col-12 col-md-6">
                                <a href="detail-kat-info.php?id=<?= $kategori['id'] ?>"
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
                                            <div class="d-flex align-items-center">

                                                <!-- Icon -->
                                                <div class="flex-shrink-0">
                                                    <div class="category-icon">
                                                        <i class="<?= $icon ?>"></i>
                                                    </div>
                                                </div>

                                                <!-- Content -->
                                                <div class="ms-3 flex-grow-1">

                                                    <h5 class="mb-1 font-weight-bold">
                                                        <?= $kategori['judul_kategori'] ?>
                                                    </h5>

                                                    <small class="d-block opacity-75">
                                                        Informasi terbaru terkait
                                                        <?= strtolower($kategori['judul_kategori']) ?>
                                                    </small>

                                                    <div class="mt-3">
                                                        <span class="badge rounded-pill category-badge">
                                                            <?= $kategori['jumlah_informasi'] ?>
                                                        </span>
                                                    </div>

                                                </div>

                                                <!-- Arrow -->
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-chevron-right"></i>
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