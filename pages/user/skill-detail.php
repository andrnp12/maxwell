<?php
require_once '../../src/classes/auth.php';
require_once '../../src/classes/kuis.php';
require_once '../../src/classes/pertanyaan_kuis.php';
require_once '../../src/classes/progress_user.php';

$kuis = new Kuis();
$auth = new auth();
$progress = new ProgressUser();
$dataPertanyaan = new PertanyaanKuis();
$auth->authOrNot();

$dataKuis = $kuis->getKuisById((int)$_GET['id']);

$dataPertanyaan = $dataPertanyaan->getAllPertanyaanKuis($_GET['id']);

if (!$dataKuis) {
    header("Location: skill.php");
    exit;
}

if (!$progress->isMaterialFinished($_SESSION['id'], $dataKuis['material_id'])) {

    header("Location: skill.php");
    exit;
}
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
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="skill.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Detail Pelatihan Skill
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm mb-4">
                                            <div class="d-flex align-items-center mt-3 mt-sm-0">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-xl me-3">
                                                        <img alt="" class="img-fluid" src="/assets/icon/focus-group.webp" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div>
                                                        <h5 class="mb-1">
                                                            <?= $dataKuis['judul'] ?>
                                                        </h5>
                                                        <p class="badge bg-light text-dark rounded-pill mb-0">
                                                            passing grade: <?= $dataKuis['passing_grade'] ?>
                                                        </p>
                                                        <p class="badge bg-light text-dark rounded-pill mb-0">
                                                            5 Soal pilihan ganda
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                            <div class="col-lg-12">
                                <div class="card border-0">

                                    <form id="formKuis" method="POST" action="../../src/actions/proses_submit_test.php">
                                        <input
                                            type="hidden"
                                            name="quiz_id"
                                            value="<?= (int)$dataKuis['id'] ?>">

                                        <div class="d-flex flex-column-reverse flex-md-row gap-3 gap-md-4">
                                            <!-- Soal -->

                                            <div class="col-lg-9">

                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title mb-0">
                                                            Soal Pertanyaan
                                                        </h4>
                                                    </div>

                                                    <div class="card-body">

                                                        <?php
                                                        $total = count($dataPertanyaan);

                                                        foreach ($dataPertanyaan as $i => $pertanyaan):

                                                            $no = $i + 1;
                                                        ?>

                                                            <div
                                                                class="question <?= $i == 0 ? '' : 'd-none' ?>"
                                                                data-index="<?= $i ?>">

                                                                <h5 class="mb-3">
                                                                    Soal <?= $no ?> dari <?= $total ?>
                                                                </h5>

                                                                <div class="progress mb-4">

                                                                    <div
                                                                        class="progress-bar progressBar"
                                                                        style="width:<?= ($no / $total) * 100 ?>%">
                                                                    </div>

                                                                </div>

                                                                <h4 class="mb-4">

                                                                    <?= $pertanyaan['pertanyaan'] ?>

                                                                </h4>

                                                                <?php
                                                                foreach (['a', 'b', 'c', 'd'] as $opsi):
                                                                ?>

                                                                    <div class="form-check border rounded p-3 mb-3">

                                                                        <input
                                                                            class="form-check-input jawaban"
                                                                            type="radio"
                                                                            name="jawaban[<?= $pertanyaan['id'] ?>]"
                                                                            id="q<?= $no . $opsi ?>"
                                                                            value="<?= strtoupper($opsi) ?>">

                                                                        <label
                                                                            class="form-check-label w-100"
                                                                            for="q<?= $no . $opsi ?>">

                                                                            <?= $pertanyaan["opsi_$opsi"] ?>

                                                                        </label>

                                                                    </div>

                                                                <?php endforeach; ?>

                                                            </div>

                                                        <?php endforeach; ?>

                                                        <div class="d-flex justify-content-between mb-3">

                                                            <button
                                                                type="button"
                                                                class="btn btn-secondary"
                                                                id="btnPrev">

                                                                Prev

                                                            </button>

                                                            <button
                                                                type="button"
                                                                class="btn btn-primary"
                                                                id="btnNext">

                                                                Next

                                                            </button>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                            <!-- Nomor soal -->
                                            <div class="col-lg-3">

                                                <div class="card">

                                                    <div class="card-header">

                                                        Nomor Soal

                                                    </div>

                                                    <div class="card-body">

                                                        <div
                                                            class="d-grid"
                                                            style="
                    grid-template-columns:repeat(5,1fr);
                    gap:10px;">

                                                            <?php foreach ($dataPertanyaan as $i => $p): ?>

                                                                <button
                                                                    type="button"
                                                                    class="btn btn-outline-secondary nomor-soal"
                                                                    data-index="<?= $i ?>">

                                                                    <?= $i + 1 ?>

                                                                </button>

                                                            <?php endforeach; ?>

                                                        </div>

                                                        <hr>
                                                        <button
                                                            type="button"
                                                            id="btnFinish"
                                                            class="btn btn-success w-100">
                                                            Selesai
                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </form>

                                    <div class="modal fade" id="finishModal" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">

                                                        Konfirmasi Penyelesaian

                                                    </h5>

                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal">
                                                    </button>

                                                </div>

                                                <div class="modal-body">

                                                    <p>Total Soal :
                                                        <strong id="totalSoal"></strong>
                                                    </p>

                                                    <p>Sudah Dijawab :
                                                        <strong id="sudahJawab"></strong>
                                                    </p>

                                                    <p>Belum Dijawab :
                                                        <strong id="belumJawab"></strong>
                                                    </p>

                                                    <div
                                                        id="daftarBelum"
                                                        class="alert alert-warning d-none">

                                                        <p class="mb-2">
                                                            Nomor yang belum dijawab
                                                        </p>

                                                        <div id="listBelum"></div>

                                                    </div>

                                                </div>

                                                <div class="modal-footer">

                                                    <button
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal">

                                                        Kembali

                                                    </button>

                                                    <button
                                                        id="submitQuiz"
                                                        class="btn btn-success">

                                                        Ya, Selesaikan

                                                    </button>

                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let current = 0;

            const questions = document.querySelectorAll(".question");
            const nomor = document.querySelectorAll(".nomor-soal");

            function updateNomorSoal() {

                nomor.forEach((btn, index) => {

                    btn.classList.remove(
                        "btn-primary",
                        "btn-success",
                        "btn-outline-secondary"
                    );

                    let question = questions[index];

                    let checked = question.querySelector(
                        "input[type=radio]:checked"
                    );

                    if (index === current) {

                        btn.classList.add("btn-primary");

                    } else if (checked) {

                        btn.classList.add("btn-success");

                    } else {

                        btn.classList.add("btn-outline-secondary");

                    }

                });

            }

            function tampil(index) {

                questions.forEach(q => q.classList.add("d-none"));

                questions[index].classList.remove("d-none");

                current = index;

                updateNomorSoal();

            }

            tampil(0);

            nomor.forEach(function(btn) {

                btn.addEventListener("click", function() {

                    let index = parseInt(this.dataset.index);

                    tampil(index);

                });

            });

            document.querySelectorAll(".jawaban").forEach(function(radio) {

                radio.addEventListener("change", function() {

                    updateNomorSoal();

                });

            });

            document.getElementById("btnPrev").addEventListener("click", function() {

                if (current > 0) {

                    tampil(current - 1);

                }

            });

            document.getElementById("btnNext").onclick = function() {

                let checked = questions[current].querySelector(
                    "input[type=radio]:checked"
                );

                if (!checked) {

                    alert("Silakan pilih jawaban terlebih dahulu.");

                    return;

                }

                if (current < questions.length - 1) {

                    tampil(current + 1);

                }

            }

            const modalFinish = new bootstrap.Modal(
                document.getElementById('finishModal')
            );

            document.getElementById("btnFinish").onclick = function() {

                let total = questions.length;

                let answered = 0;

                let belum = [];

                questions.forEach(function(q, index) {

                    let checked = q.querySelector(
                        "input[type=radio]:checked"
                    );

                    if (checked) {

                        answered++;

                    } else {

                        belum.push(index + 1);

                    }

                });

                document.getElementById("totalSoal").innerHTML = total;

                document.getElementById("sudahJawab").innerHTML = answered;

                document.getElementById("belumJawab").innerHTML = total - answered;

                let div = document.getElementById("daftarBelum");

                if (belum.length) {

                    div.classList.remove("d-none");

                    let list = document.getElementById("listBelum");

                    list.innerHTML = "";

                    belum.forEach(function(no) {

                        list.innerHTML += `
<button
    type="button"
    class="btn btn-outline-danger btn-sm m-1 goto-question"
    data-index="${no-1}">
    ${no}
</button>
`;

                    });

                } else {

                    div.classList.add("d-none");

                }

                modalFinish.show();

            }

            document.getElementById("submitQuiz").onclick = function() {

                this.disabled = true;

                this.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
        Mengoreksi...
    `;

                document.getElementById("formKuis").submit();
            }

            document.addEventListener("click", function(e) {

                if (e.target.classList.contains("goto-question")) {

                    let index = parseInt(e.target.dataset.index);

                    modalFinish.hide();

                    tampil(index);

                }

            });

        });
    </script>
</body>

</html>