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

// Set true di awal agar materi pertama (index 0) selalu bisa diakses
$previousFinished = true;

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
                            <!-- <div class="page-title-box d-flex align-items-center justify-content-between pb-4">
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
                            </div> -->
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
                        <?php foreach ($dataMateri as $index => $materi) : ?>
                            <?php
                            // LOGIKA BARU: 
                            // Bisa akses jika: materi sebelumnya selesai OR materi ini sendiri sudah selesai
                            $canAccess = ($previousFinished || $materi['material_selesai'] == 1);

                            // Update status untuk materi berikutnya di loop selanjutnya
                            $previousFinished = ($materi['material_selesai'] == 1);
                            ?>

                            <a class="col-12 col-xl-6 col-md-6" href="<?= $canAccess ? 'detail-materi.php?id=' . $materi['id'] : '#' ?>"
                                <?= !$canAccess ? 'data-bs-toggle="modal" data-bs-target="#peringatanModal"' : '' ?>>

                                <?php if ($materi['material_selesai']) : ?>
                                    <!-- Jika sudah selesai: background success, text white, tanpa border-success -->
                                    <div class="card mb-3 text-white border-white shadow-lg" style="border-radius: 1.25rem; background-image: radial-gradient( circle farthest-corner at 10% 20%,  rgba(14,174,87,1) 0%, rgba(12,116,117,1) 90% );">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-3 text-center">
                                                <div style="width: 56px; height: 56px; border-radius: 50%; background-color: rgba(233, 236, 239, 0.5); display: inline-flex; align-items: center; justify-content: center;">
                                                    <img src="/assets/icon/book.webp" alt="icon" style="width: 32px; height: 32px;" />
                                                </div>

                                            </div>
                                            <div class="col-9">
                                                <div class="card-body" style="padding-left: 0px;">
                                                    <h5 class="card-title mb-0 font-weight-bold">
                                                        <?= $materi['judul'] ?>
                                                    </h5>
                                                    <p class="card-text mb-0">
                                                        <small class="text-truncate d-block" style="max-width: 100%; color: rgba(255,255,255,0.8);">
                                                            <?= $materi['deskripsi'] ?>
                                                        </small>
                                                    </p>
                                                    <!-- Jika sudah selesai -->
                                                    <small class="badge text-white px-2 py-1 rounded-pill" style="background-color: rgba(233, 236, 239, 0.5);">
                                                        <i class="mdi mdi-check-circle"></i>
                                                        Telah Dipelajari
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <!-- Jika belum selesai: background putih, border biasa -->
                                    <div class="card mb-3 border shadow-sm" style="border-radius: 1.25rem;">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-3 text-center">
                                                <!-- Icon circle untuk belum selesai: background abu-abu -->
                                                <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items: center; justify-content: center;">
                                                    <img src="/assets/icon/book.webp" alt="icon" style="width: 32px; height: 32px;" />
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <div class="card-body" style="padding-left: 0px;">
                                                    <h5 class="card-title mb-0 font-weight-bold">
                                                        <?= $materi['judul'] ?>
                                                    </h5>
                                                    <p class="card-text mb-0">
                                                        <small class="text-muted text-truncate d-block" style="max-width: 100%;">
                                                            <?= $materi['deskripsi'] ?>
                                                        </small>
                                                    </p>
                                                    <?php if ($canAccess) : ?>
                                                        <!-- Jika bisa diakses tapi belum selesai (Materi saat ini yang harus dipelajari) -->
                                                        <small class="badge bg-primary text-white px-2 py-1 rounded-pill">
                                                            <i class="mdi mdi-play-circle"></i>
                                                            <?= ($index === 0) ? 'Mulai Belajar' : 'Lanjutkan Belajar' ?>
                                                        </small>
                                                    <?php else : ?>
                                                        <!-- Jika benar-benar terkunci -->
                                                        <small class="badge bg-secondary text-white px-2 py-1 rounded-pill">
                                                            <i class="mdi mdi-lock"></i>
                                                            Kunci
                                                        </small>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- container-fluid -->
                <div class="modal fade" id="peringatanModal" tabindex="-1" aria-labelledby="peringatanModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="peringatanModalLabel">
                                    <i class="mdi mdi-lock text-warning"></i> Akses Kuis Terkunci
                                </h5>
                                <!-- Catatan: Jika menggunakan Bootstrap 4, ubah class="btn-close" menjadi class="close" dan tambahkan isi <span>&times;</span> -->
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <p>Anda belum bisa Mempelajari Materi ini.</p>
                                <strong>Silakan selesaikan materi dan kuis sebelumnya terlebih dahulu!</strong>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
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