<?php
require_once '../../src/classes/auth.php';
require_once '../../src/classes/materi.php';

$auth = new auth();
$auth->authOrNot();


$data = new Materi();
$dataMateri = $data->getMateriById($_GET['id']);

$previousMateri = $data->getPreviousMateri((int)$_GET['id']);
$nextMateri = $data->getNextMateri((int)$_GET['id']);
?>

<?php include '../include/header.php'; ?>

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php include("../include/topbar.php"); ?>
        <!-- ========== Left Sidebar Start ========== -->
        <?php include '../include/sidebar-ortu.php'; ?>
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
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="belajar.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Detail Materi
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
                                    <div class="">
                                        <div class="mb-3">
                                            <h4>
                                                <?= $dataMateri['judul']; ?>
                                            </h4>
                                        </div>
                                        <div class="mb-4">
                                            <img alt="" class="img-thumbnail mx-auto d-block" src="/assets/images/small/img-2.jpg" />
                                        </div>
                                        <div class="mt-4">
                                            <div class="text-muted font-size-14">
                                                <p>
                                                    <?= $dataMateri['deskripsi']; ?> </p>
                                                <div class="mt-3 mb-4">
                                                    <iframe src="/assets/ViewerJS/index.html?zoom=page-width#/uploads/materi/<?= htmlspecialchars($dataMateri['file']) ?>"
                                                        title="Pratinjau PDF"
                                                        class="w-100 rounded border"
                                                        style="min-height: 500px; border: 1px solid #dee2e6;">
                                                    </iframe>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="d-flex justify-content-between mt-4">

                                            <div>

                                                <?php if ($previousMateri): ?>

                                                    <a
                                                        href="detail-materi.php?id=<?= $previousMateri['id'] ?>"
                                                        class="btn btn-outline-light btn-sm btn-rounded waves-effect">

                                                        <i class="mdi mdi-arrow-left"></i>

                                                        Sebelumnya

                                                    </a>

                                                <?php endif; ?>

                                            </div>

                                            <div>
                                                <?php if ($nextMateri): ?>
                                                    <a
                                                        href="detail-materi.php?id=<?= $nextMateri['id'] ?>"
                                                        class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        Materi Selanjutnya
                                                        <i class="mdi mdi-arrow-right"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                        <!-- <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div>
                                        <h5 class="mb-3">
                                            Materi Lainnya
                                        </h5>
                                        <div class="list-group list-group-flush">
                                            <a class="list-group-item text-muted pb-3 pt-0 px-2" href="javascript: void(0);">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <img alt="" class="avatar-xl h-auto d-block rounded" src="/assets/images/small/img-3.jpg" />
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <h5 class="font-size-13 text-truncate">
                                                            Beautiful Day with Friends
                                                        </h5>
                                                        <p class="mb-0 text-truncate">
                                                            10 Apr, 2022
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="list-group-item text-muted py-3 px-2" href="javascript: void(0);">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <img alt="" class="avatar-xl h-auto d-block rounded" src="/assets/images/small/img-4.jpg" />
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <h5 class="font-size-13 text-truncate">
                                                            Drawing a sketch
                                                        </h5>
                                                        <p class="mb-0 text-truncate">
                                                            24 May, 2022
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="list-group-item text-muted py-3 px-2" href="javascript: void(0);">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <img alt="" class="avatar-xl h-auto d-block rounded" src="/assets/images/small/img-1.jpg" />
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <h5 class="font-size-13 text-truncate">
                                                            Coffee with friends
                                                        </h5>
                                                        <p class="mb-0 text-truncate">
                                                            15 June, 2022
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
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