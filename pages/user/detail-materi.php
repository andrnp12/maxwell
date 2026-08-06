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

$previousMateri = $data->getPreviousMateri((int)$_GET['id']);
$nextMateri = $data->getNextMateri((int)$_GET['id']);
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
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="">
                                        <div class="mb-3">
                                            <h4>
                                                <?= htmlspecialchars($dataMateri['judul']) ?>
                                            </h4>
                                        </div>
                                        <div class="mb-4">
                                            <img alt="" class="img-thumbnail mx-auto d-block" src="/assets/images/small/img-2.jpg" />
                                        </div>
                                        <div class="mt-4">
                                            <div class="text-muted font-size-14">
                                                <p>
                                                    <?= htmlspecialchars($dataMateri['deskripsi']) ?>
                                                </p>
                                                <div class="mt-3 mb-4">
                                                    <iframe src="/assets/ViewerJS/index.html?zoom=page-width#/uploads/<?= htmlspecialchars($dataMateri['file']) ?>"
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

                                                    <?php if ($nextMateri): ?>

                                                        <a
                                                            id="btnNextMaterial"
                                                            href="<?= $materialFinished ? 'detail-materi.php?id=' . $nextMateri['id'] : '#' ?>"
                                                            class="btn <?= $materialFinished ? 'btn-primary' : 'btn-secondary' ?> btn-sm btn-rounded <?= !$materialFinished ? 'disabled' : '' ?>">

                                                            Materi Selanjutnya

                                                            <i class="mdi <?= $materialFinished ? 'mdi-arrow-right' : 'mdi-lock' ?>"></i>

                                                        </a>

                                                    <?php endif; ?>

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
                        <div class="col-lg-4">
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
                            </div>
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

        <script>
            const materialId = <?= (int)$dataMateri['id']; ?>;

            const materialFinished = <?= $materialFinished ? 'true' : 'false'; ?>;

            document.addEventListener("DOMContentLoaded", function() {

                // Kalau materi sudah selesai, tidak perlu jalankan timer
                if (materialFinished) {
                    // console.log("Materi sudah pernah dipelajari.");
                    return;
                }

                let elapsed = 0;
                let isCompleted = false;

                const MINIMUM_READ_TIME = 10;

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

                                const btn = document.getElementById("btnNextMaterial");

                                if (btn) {

                                    btn.classList.remove("btn-secondary");
                                    btn.classList.remove("disabled");

                                    btn.classList.add("btn-primary");

                                    btn.href = "detail-materi.php?id=<?= $nextMateri['id'] ?>";

                                    btn.innerHTML = `
                Materi Selanjutnya
                <i class="mdi mdi-arrow-right"></i>
            `;

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