<?php
require_once '../../src/classes/auth.php';
require_once '../../src/classes/kuis.php';
$auth = new auth();
$auth->authOrNot();

$data = new Kuis();
$dataKuis = $data->getAllKuisUser($_SESSION['id']);

$total = count($dataKuis);

// Hitung progress berdasarkan kuis yang LULUS (nilai >= KKM), bukan hanya selesai dikerjakan
$selesai = 0;
$belumLulus = 0;

foreach ($dataKuis as $kuis) {
    $hasAttempted = isset($kuis['nilai']) && $kuis['nilai'] !== null;
    $hasPassed = isset($kuis['lulus']) && $kuis['lulus'] == 1;

    if ($hasPassed) {
        $selesai++;
    } elseif ($hasAttempted && !$hasPassed) {
        $belumLulus++;
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
                                    Skill Saya
                                </h4>
                                <p class="text-muted">
                                    Kelola kompetensi Anda dengan mudah!
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- end row -->
                    <div class="row">
                        <!-- end card header -->
                        <!-- <div class="col-12">
                            <div class="carousel slide pointer-event" data-bs-ride="carousel" id="carouselExampleCaption">
                                <div class="carousel-inner" role="listbox">
                                    <div class="carousel-item">
                                        <img alt="..." class="d-block img-fluid mx-auto w-100" src="/assets/images/small/img-7.jpg">
                                        <div class="carousel-caption d-block text-white-50">
                                            <h5 class="text-white">
                                                Komunikasi Asertif
                                            </h5>
                                            <p>
                                                Kemampuan untuk menyampaikan pikiran dan perasaan dengan jelas dan sopan.
                                            </p>
                                            
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img alt="..." class="d-block img-fluid mx-auto w-100" src="/assets/images/small/img-5.jpg">
                                        <div class="carousel-caption d-block text-white-50">
                                            <h5 class="text-white">
                                                Problem Solving
                                            </h5>
                                            <p>
                                                Kemampuan untuk menyelesaikan masalah secara efektif dan efisien.
                                            </p>
                                            
                                        </div>
                                    </div>
                                    <div class="carousel-item active">
                                        <img alt="..." class="d-block img-fluid mx-auto w-100" src="/assets/images/small/img-4.jpg">
                                        <div class="carousel-caption d-block text-white-50">
                                            <h5 class="text-white">
                                                Leadership
                                            </h5>
                                            <p>
                                                Kemampuan untuk memimpin dan menginspirasi orang lain.
                                            </p>
                                           
                                        </div>
                                    </div>
                                </div>
                                <a class="carousel-control-prev" data-bs-slide="prev" href="#carouselExampleCaption" role="button">
                                    <span aria-hidden="true" class="carousel-control-prev-icon">
                                    </span>
                                    <span class="sr-only">
                                        Previous
                                    </span>
                                </a>
                                <a class="carousel-control-next" data-bs-slide="next" href="#carouselExampleCaption" role="button">
                                    <span aria-hidden="true" class="carousel-control-next-icon">
                                    </span>
                                    <span class="sr-only">
                                        Next
                                    </span>
                                </a>
                            </div>
                        </div> -->
                        <div class="col-12">
                            <!-- <div class="page-title-box d-flex align-items-center justify-content-between py-4">
                                <h5 class="mb-sm-0">
                                    Daftar Skill Tes
                                </h5>
                                <div class="page-title-right">
                                    <div class="btn-group">
                                        <button aria-expanded="false" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" type="button">
                                            Filter Skill
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
                                    class="progress-bar bg-success"
                                    style="width: <?= $persen ?>%;"
                                    title="Lulus: <?= $selesai ?>">
                                </div>
                                <?php if ($belumLulus > 0): ?>
                                    <?php $persenGagal = $total > 0 ? round(($belumLulus / $total) * 100) : 0; ?>
                                    <div
                                        class="progress-bar bg-danger"
                                        style="width: <?= $persenGagal ?>%;"
                                        title="Belum Lulus: <?= $belumLulus ?>">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <p class="text-muted mb-4">
                                Progress Belajar
                                <strong><?= $persen ?>%</strong>
                                <?php if ($belumLulus > 0): ?>
                                    <span class="text-danger ms-2">
                                        <i class="mdi mdi-alert-circle"></i> <?= $belumLulus ?> Belum Lulus
                                    </span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php foreach ($dataKuis as $index => $kuis): ?>
                            <?php
                            $canAccess = $kuis['material_selesai'] == 1;

                            // Determine quiz status
                            $hasAttempted = isset($kuis['nilai']) && $kuis['nilai'] !== null;
                            $hasPassed = isset($kuis['lulus']) && $kuis['lulus'] == 1;
                            $nilai = $hasAttempted ? (float)$kuis['nilai'] : 0;
                            $kkm = (int)$kuis['passing_grade'];
                            $percobaan = isset($kuis['percobaan']) ? (int)$kuis['percobaan'] : 0;

                            // Status: 0 = Belum Dikerjakan, 1 = Belum Lulus (Gagal), 2 = Lulus
                            if (!$hasAttempted) {
                                $status = 'belum_dikerjakan';
                                $statusLabel = 'Belum Dikerjakan';
                                $statusIcon = 'mdi-pencil';
                                $badgeClass = 'bg-secondary';
                                $badgeBgStyle = 'background-color: #6c757d;';
                                $cardStyle = 'border shadow-sm';
                                $cardBgStyle = 'background-color: #fff;';
                                $textColorClass = '';
                                $iconBgStyle = 'background-color: #e9ecef;';
                                $iconImg = '/assets/icon/pencil.webp';
                            } elseif ($hasPassed) {
                                $status = 'lulus';
                                $statusLabel = 'Lulus';
                                $statusIcon = 'mdi-check-circle';
                                $badgeClass = 'bg-success';
                                $badgeBgStyle = 'background-color: rgba(233, 236, 239, 0.5);';
                                $cardStyle = 'text-white border-white shadow-lg';
                                $cardBgStyle = 'background-image: radial-gradient( circle farthest-corner at 10% 20%,  rgba(14,174,87,1) 0%, rgba(12,116,117,1) 90% );';
                                $textColorClass = 'text-white';
                                $iconBgStyle = 'background-color: rgba(233, 236, 239, 0.5);';
                                $iconImg = '/assets/icon/pencil.webp';
                            } else {
                                $status = 'belum_lulus';
                                $statusLabel = 'Belum Lulus';
                                $statusIcon = 'mdi-close-circle';
                                $badgeClass = 'bg-danger';
                                $badgeBgStyle = 'background-color: #dc3545;';
                                $cardStyle = 'border shadow-sm';
                                $cardBgStyle = 'background-color: #fff;';
                                $textColorClass = '';
                                $iconBgStyle = 'background-color: #f8d7da;';
                                $iconImg = '/assets/icon/pencil.webp';
                            }
                            ?>
                            <a
                                class="col-12 col-xl-6 col-md-6 text-decoration-none"
                                href="<?= $canAccess ? 'skill-detail.php?id=' . $kuis['id_kuis'] : '#' ?>"
                                <?= !$canAccess ? 'data-bs-toggle="modal" data-bs-target="#peringatanModal"' : '' ?>>

                                <div class="card mb-3 <?= $cardStyle ?>" style="border-radius: 1.25rem; <?= $cardBgStyle ?>">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-3 text-center">
                                            <div style="width: 56px; height: 56px; border-radius: 50%; <?= $iconBgStyle ?> display: inline-flex; align-items: center; justify-content: center;">
                                                <img src="<?= $iconImg ?>" alt="icon" style="width: 32px; height: 32px;" />
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="card-body <?= $textColorClass ?>" style="padding-left: 0px;">
                                                <h5 class="card-title mb-0 font-weight-bold">
                                                    <?= htmlspecialchars($kuis['judul_kuis']) ?>
                                                </h5>
                                                <?php if (!$canAccess): ?>
                                                    <!-- Jika terkunci -->
                                                    <small class="badge bg-secondary text-white px-2 py-1 rounded-pill">
                                                        <i class="mdi mdi-lock"></i>
                                                        Kunci
                                                    </small>
                                                <?php elseif ($status === 'belum_dikerjakan'): ?>
                                                    <!-- Belum Dikerjakan -->
                                                    <small class="badge bg-light px-2 py-1 rounded-pill">
                                                        KKM : <?= $kkm ?>
                                                    </small>
                                                    <small class="badge bg-secondary text-white px-2 py-1 rounded-pill">
                                                        <i class="mdi <?= $statusIcon ?>"></i>
                                                        <?= $statusLabel ?>
                                                    </small>
                                                <?php elseif ($status === 'lulus'): ?>
                                                    <!-- Lulus -->
                                                    <small class="badge <?= $textColorClass ?> px-2 py-1 rounded-pill" style="<?= $badgeBgStyle ?>">
                                                        KKM : <?= $kkm ?>
                                                    </small>
                                                    <small class="badge <?= $textColorClass ?> px-2 py-1 rounded-pill" style="<?= $badgeBgStyle ?>">
                                                        Percobaan ke-<?= $percobaan ?>
                                                    </small>
                                                    <small class="badge <?= $textColorClass ?> px-2 py-1 rounded-pill" style="<?= $badgeBgStyle ?>">
                                                        <i class="mdi <?= $statusIcon ?>"></i>
                                                        <?= $statusLabel ?>
                                                        (<?= $nilai ?>)
                                                    </small>
                                                <?php else: ?>
                                                    <!-- Belum Lulus (Gagal) -->
                                                    <small class="badge bg-light px-2 py-1 rounded-pill">
                                                        KKM : <?= $kkm ?>
                                                    </small>
                                                    <small class="badge bg-light px-2 py-1 rounded-pill">
                                                        Percobaan ke-<?= $percobaan ?>
                                                    </small>
                                                    <small class="badge bg-danger text-white px-2 py-1 rounded-pill">
                                                        <i class="mdi <?= $statusIcon ?>"></i>
                                                        <?= $statusLabel ?>
                                                        (<?= $nilai ?>)
                                                    </small>
                                                <?php endif; ?>
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
        <!-- Pop-up (Modal) HTML -->
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
                        <p>Anda belum bisa mengerjakan kuis ini.</p>
                        <strong>Silakan selesaikan materi terlebih dahulu!</strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
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

    <script>
        // Initialize Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>

</html>