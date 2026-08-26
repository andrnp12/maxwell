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

                        <div class="col-12 col-xl-6 col-md-6">
                            <a href="detail-kat-info.php?id=<?= $kategori['id'] ?>"
                                class="text-decoration-none">
                                <div class="card mb-3 text-white shadow-sm"
                                    style="border-radius: 1.25rem; background-image: radial-gradient( circle 1224px at 10.6% 8.8%,  rgba(255,255,255,1) 0%, rgba(153,202,251,1) 100.2% );">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-3 text-center">
                                            <div style="width: 56px; height: 56px; border-radius: 50%; background-color: rgba(125, 125, 126, 0.5); display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="<?= $icon ?? 'fas fa-folder' ?>" style="font-size: 24px;"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="card-body" style="padding-left: 0px;">
                                                <h5 class="card-title mb-0 font-weight-bold text-black">
                                                    <?= $kategori['judul_kategori'] ?>
                                                </h5>
                                                <p class="card-text mb-0">
                                                    <small class="text-truncate d-block text-black" style="max-width: 100%;">
                                                        Informasi terbaru terkait
                                                        <?= strtolower($kategori['judul_kategori']) ?>
                                                    </small>
                                                </p>
                                                <small class="badge text-black px-2 py-1 rounded-pill" style="background-color: rgba(165, 167, 168, 0.5);">
                                                    <?= $kategori['jumlah_informasi'] ?> info
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="text-start">
                                                <button class="btn btn-primary px-2 py-1" style="border-radius: 1.25rem;">
                                                    Details
                                                </button>
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