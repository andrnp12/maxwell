<?php
require_once '../../src/classes/auth.php';
require_once '../../src/classes/materi.php';
require_once '../../src/classes/progress_user.php';

$progress = new ProgressUser();
$auth = new auth();
$data = new Materi();

$auth->authOrNot();
$dataMateri = $data->getMateriById($_GET['id']);
$materialFinished = $progress->isMaterialFinished(
    $_SESSION['id'],
    (int)$_GET['id']
);

// Cek apakah kuis untuk materi ini sudah LULUS
$quizPassed = $progress->isQuizPassed($_SESSION['id'], (int)$_GET['id']);

// Bisa lanjut ke materi selanjutnya jika: materi selesai DAN kuis lulus
$canProceedToNext = $materialFinished && $quizPassed;

$previousMateri = $data->getPreviousMateri((int)$_GET['id']);
$nextMateri = $data->getNextMateri((int)$_GET['id']);

// Extract YouTube video ID from stored URL
$youtubeVideoId = $progress->getYouTubeVideoId($dataMateri['video_url'] ?? '');
$youtubeEmbedUrl = $youtubeVideoId ? "https://www.youtube.com/embed/{$youtubeVideoId}" : '';
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
                        <div class="col-lg-8"> <!-- Main Material Card -->
                            <div class="card border shadow-sm overflow-hidden" style="border-radius: 1rem;">
                                <div class="card-body p-4 p-lg-5"> <!-- ========================= HEADER MATERI ========================== -->
                                    <div class="mb-4"> <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3"> Materi Pembelajaran </span>
                                        <h1 class="h3 fw-bold text-dark mb-2 lh-base"> <?= htmlspecialchars($dataMateri['judul']) ?> </h1>
                                        <p class="text-muted mb-0"> <?= htmlspecialchars($dataMateri['deskripsi']) ?> </p>
                                    </div> <!-- ========================= INFORMASI MATERI ========================== -->
                                    <div class="row g-3 mb-4"> <!-- Tema -->
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded-3 h-100">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2"> <i class="mdi mdi-book-open-page-variant fs-4"></i> </div>
                                                    </div>
                                                    <div> <small class="text-muted d-block mb-1"> Tema Pembelajaran </small> <span class="fw-semibold text-dark"> <?= htmlspecialchars($dataMateri['tema']) ?> </span> </div>
                                                </div>
                                            </div>
                                        </div> <!-- Status -->
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded-3 h-100">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-2"> <i class="mdi mdi-school-outline fs-4"></i> </div>
                                                    </div>
                                                    <div> <small class="text-muted d-block mb-1"> Status Pembelajaran </small> <span class="fw-semibold text-dark"> <?= $materialFinished ? 'Materi Selesai' : 'Sedang Dipelajari' ?> </span> </div>
                                                </div>
                                            </div>
                                        </div> <!-- Quiz Status -->
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded-3 h-100">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="<?= $quizPassed ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' ?> rounded-3 p-2">
                                                            <i class="mdi mdi<?= $quizPassed ? '-check-circle' : '-alert-circle' ?> fs-4"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block mb-1"> Status Kuis </small>
                                                        <span class="fw-semibold text-dark">
                                                            <?php if ($quizPassed): ?>
                                                                <span class="badge bg-success"><i class="mdi mdi-check-circle me-1"></i>Lulus</span>
                                                            <?php elseif ($materialFinished): ?>
                                                                <span class="badge bg-warning text-dark"><i class="mdi mdi-alert-circle me-1"></i>Belum Lulus (Kuis Gagal)</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary"><i class="mdi mdi-clock-outline me-1"></i>Belum Dikerjakan</span>
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ========================= TUJUAN PEMBELAJARAN ========================== -->
                                    <?php
                                    $tujuanPembelajaran = $dataMateri['tujuan'] ?? '';

                                    $tujuanList = preg_split(
                                        '/\r\n|\r|\n/',
                                        trim($tujuanPembelajaran)
                                    );
                                    ?>

                                    <div class="mb-5">

                                        <div class="d-flex align-items-center mb-4">

                                            <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2 me-3">
                                                <i class="mdi mdi-target fs-4"></i>
                                            </div>

                                            <div>
                                                <h5 class="fw-bold mb-0">
                                                    Tujuan Pembelajaran
                                                </h5>

                                                <small class="text-muted">
                                                    Setelah mempelajari materi ini, Anda diharapkan mampu:
                                                </small>
                                            </div>

                                        </div>


                                        <div class="d-flex flex-column gap-3">

                                            <?php
                                            $nomor = 1;

                                            foreach ($tujuanList as $tujuan):

                                                $tujuan = trim($tujuan);

                                                if ($tujuan === '') {
                                                    continue;
                                                }
                                            ?>

                                                <div class="d-flex align-items-start gap-2">

                                                    <!-- Nomor -->
                                                    <div class="mt-1 flex-shrink-0">

                                                        <span
                                                            class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 15px; height: 15px;">

                                                            <?= $nomor ?>

                                                        </span>

                                                    </div>


                                                    <!-- Isi Tujuan -->
                                                    <div class="">

                                                        <p class="text-secondary">
                                                            <?= htmlspecialchars($tujuan) ?>
                                                        </p>

                                                    </div>

                                                </div>

                                            <?php
                                                $nomor++;
                                            endforeach;
                                            ?>

                                        </div>

                                    </div>
                                    <!-- ========================= VIDEO PEMBELAJARAN ========================== -->
                                    <div class="mb-5">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-2 me-3"> <i class="mdi mdi-play-circle-outline fs-4"></i> </div>
                                            <div>
                                                <h5 class="fw-bold mb-0"> Video Pembelajaran </h5> <small class="text-muted"> Simak video berikut sebelum membaca materi </small>
                                            </div>
                                        </div> <?php if ($youtubeEmbedUrl): ?> <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm"> <iframe src="<?= htmlspecialchars($youtubeEmbedUrl) ?>" title="Video Materi" class="border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen> </iframe> </div> <?php else: ?> <div class="alert alert-warning border-0 rounded-3">
                                                <div class="d-flex align-items-center"> <i class="mdi mdi-alert-outline fs-4 me-2"></i>
                                                    <div> <strong>Video tidak tersedia.</strong> <br> <small> URL Video YouTube tidak valid: <?= htmlspecialchars($dataMateri['video_url'] ?? '') ?> </small> </div>
                                                </div>
                                            </div> <?php endif; ?>
                                    </div>
                                    <!-- ========================= PDF VIEWER ========================== -->
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-2 me-3"> <i class="mdi mdi-file-pdf-box fs-4"></i> </div>
                                                <div>
                                                    <h5 class="fw-bold mb-0"> Dokumen Materi </h5> <small class="text-muted"> Gunakan dokumen berikut sebagai bahan pembelajaran </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div> <iframe src="/assets/ViewerJS/index.html?zoom=page-width#/uploads/materi/<?= htmlspecialchars($dataMateri['file']) ?>" title="Pratinjau PDF" class="w-100 border-0" style="height: 700px;"> </iframe> </div>
                                    </div>

                                    <!-- ========================= KESIMPULAN PEMBELAJARAN ========================== -->

                                    <div class="mt-5 mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 me-3"> <i class="mdi mdi-lightbulb-outline fs-4"></i> </div>
                                            <div>
                                                <h5 class="fw-bold mb-0"> Kesimpulan Pembelajaran </h5> <small class="text-muted"> Kesimpulan pembelajaran dari materi yang telah dipelajari </small>
                                            </div>
                                        </div>
                                        <div class="ps-1">
                                            <p class="text-secondary lh-lg mb-0"> <?= nl2br(htmlspecialchars($dataMateri['kesimpulan'])) ?> </p>
                                        </div>
                                    </div>

                                    <!-- ========================= NAVIGASI MATERI ========================== -->
                                    <div class="border-top pt-4 mt-5">
                                        <div class="d-flex align-items-center justify-content-between gap-3"> <!-- Previous -->
                                            <div> <?php if ($previousMateri): ?> <a href="detail-materi.php?id=<?= $previousMateri['id'] ?>" class="btn btn-outline-secondary rounded-pill"> <i class="mdi mdi-arrow-left me-1"></i> Sebelumnya </a> <?php endif; ?> </div> <!-- Next -->
                                            <div>
                                                <?php if ($nextMateri): ?>
                                                    <a id="btnNextMaterial"
                                                        href="<?= $canProceedToNext ? 'detail-materi.php?id=' . $nextMateri['id'] : '#' ?>"
                                                        class="btn <?= $canProceedToNext ? 'btn-primary' : 'btn-secondary' ?> rounded-pill <?= !$canProceedToNext ? 'disabled' : '' ?>">
                                                        <?= $canProceedToNext ? 'Selanjutnya' : 'Belum Lulus' ?>
                                                        <i class="mdi <?= $canProceedToNext ? 'mdi-arrow-right' : 'mdi-lock-outline' ?> ms-1"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- ========================= CUSTOM STYLE ========================== -->
                    <style>
                        .material-description {
                            font-size: 1rem;
                            line-height: 1.9;
                        }

                        .material-description p {
                            margin-bottom: 1rem;
                        }

                        .card {
                            transition: box-shadow 0.2s ease;
                        }

                        .btn {
                            transition: all 0.2s ease;
                        }

                        .btn:hover {
                            transform: translateY(-1px);
                        }

                        @media (max-width: 576px) {
                            /* .card-body {
                                padding: 1.25rem !important;
                            } */

                            .d-flex.justify-content-between {
                                align-items: stretch !important;
                            }

                            .ratio {
                                border-radius: .75rem !important;
                            }

                            iframe[title="Pratinjau PDF"] {
                                height: 550px !important;
                            }
                        }
                    </style>
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

    <script>
        const materialId = <?= (int)$dataMateri['id']; ?>;

        const materialFinished = <?= $materialFinished ? 'true' : 'false'; ?>;
        const quizPassed = <?= $quizPassed ? 'true' : 'false'; ?>;
        const canProceedToNext = <?= $canProceedToNext ? 'true' : 'false'; ?>;

        document.addEventListener("DOMContentLoaded", function() {

            // Kalau materi sudah selesai DAN kuis lulus, tidak perlu jalankan timer
            if (canProceedToNext) {
                // console.log("Materi sudah selesai dan kuis lulus.");
                return;
            }

            let elapsed = 0;
            let isCompleted = false;

            const MINIMUM_READ_TIME = 5;

            const timer = setInterval(function() {

                // Pause jika tab tidak aktif
                if (document.hidden) {
                    return;
                }

                elapsed++;

                // console.log("Belajar:", elapsed, "detik");

                if (elapsed >= MINIMUM_READ_TIME && !isCompleted) {

                    isCompleted = true;

                    clearInterval(timer);

                    saveProgress();
                }
            }, 1000);

            function saveProgress() {
                fetch("../../src/actions/proses_materi_end.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: new URLSearchParams({
                            material_id: materialId
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("HTTP Error " + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            console.log("Progress berhasil disimpan");

                            // Cek apakah kuis sudah lulus untuk mengaktifkan tombol next
                            if (quizPassed) {
                                const btn = document.getElementById("btnNextMaterial");

                                if (btn) {
                                    btn.classList.remove("btn-secondary");
                                    btn.classList.remove("disabled");

                                    btn.classList.add("btn-primary");

                                    <?php if ($nextMateri): ?>
                                        btn.href = "detail-materi.php?id=<?= $nextMateri['id'] ?>";
                                    <?php endif; ?>

                                    btn.innerHTML = `
                        Materi Selanjutnya
                        <i class="mdi mdi-arrow-right"></i>
                    `;
                                }
                            }
                        } else {
                            console.error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        });
    </script>
    </div>
</body>

</html>