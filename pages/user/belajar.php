<?php
require_once '../../src/classes/auth.php';
require_once '../../src/classes/materi.php';
$auth = new auth();
$auth->authOrNot();

$data = new Materi();
$dataMateri = $data->getAllMateriUser($_SESSION['id']);

// bar
$total = count($dataMateri);

$selesai = 0;

foreach ($dataMateri as $materi) {

    if ($materi['material_selesai']) {
        $selesai++;
    }
}

$persen = $total > 0
    ? round(($selesai / $total) * 100)
    : 0;
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
                                    Modul Edukasi
                                </h4>
                                <p class="text-muted">
                                    Jelajahi berbagai modul edukasi yang tersedia!
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
                                    Kategori Belajar
                                </h5>
                                <div class="page-title-right">
                                    <div class="btn-group">
                                        <button aria-expanded="false" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" type="button">
                                            Filter Kategori
                                            <i class="mdi mdi-chevron-down">
                                            </i>
                                        </button>
                                        <div class="dropdown-menu dropdownmenu-primary">
                                            <a class="dropdown-item" href="#">
                                                Semua Materi
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                Terbaru
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                Populer
                                            </a>
                                            <div class="dropdown-divider">
                                            </div>
                                            <a class="dropdown-item" href="#">
                                                Selesai Dibaca
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="progress mb-3" style="height:8px;">
                                <div
                                    class="progress-bar"
                                    style="width: <?= $persen ?>%;">
                                </div>
                            </div>

                            <p class="text-muted mb-4">
                                Progress Belajar
                                <strong><?= $persen ?>%</strong>
                            </p>
                        </div>
                        <?php foreach ($dataMateri as $materi) : ?>
                            <a class="col-12 col-xl-6 col-md-6" href="detail-materi.php?id=<?= $materi['id'] ?>">
                                <div class="card mb-3 border border-success">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-3 text-center">
                                            <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items: center; justify-content: center;">
                                                <img src="/assets/icon/focus-group.webp" alt="icon" style="width: 40px; height: 40px;" />
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="card-body">
                                                <h5 class="card-title mb-0 font-weight-bold">
                                                    <?= $materi['judul'] ?>
                                                </h5>
                                                <p class="card-text mb-0">
                                                    <small class="text-muted text-truncate d-block" style="max-width: 100%;">
                                                        <?= $materi['deskripsi'] ?>
                                                    </small>
                                                </p>
                                                <?php if ($materi['material_selesai']) : ?>
                                                    <p class=" card-text">
                                                        <small class="badge bg-success text-white px-2 py-1 rounded-pill">
                                                            <i class="mdi mdi-check-circle"></i>
                                                            Telah Dipelajari
                                                        </small>
                                                    <?php else : ?>
                                                        <span class="badge bg-secondary text-white px-2 py-1 rounded-pill">
                                                            Belum Dipelajari
                                                        </span>
                                                    </p>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
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