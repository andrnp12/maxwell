<?php
require_once '../../src/classes/auth.php';
require_once '../../src/classes/tests.php';

$auth = new auth();
$auth->authOrNot();

$testManager = new tests();

$userId = (int) ($_SESSION['id'] ?? 0);

$jenis = $_GET['type'] ?? 'pre';

if (!in_array($jenis, ['pre', 'post'], true)) {
    header('Location: index.php');
    exit;
}

$dataKuis = $testManager->getQuizByJenis($jenis);

if (!$dataKuis) {
    die('Quiz tidak ditemukan.');
}

$kuisId = (int) $dataKuis['id'];


$sudahMengerjakan = $testManager->hasUserTakenTest(
    $userId,
    $kuisId,
    $jenis
);

if ($sudahMengerjakan) {
    header('Location: index.php');
    exit;
}


$dataPertanyaan = $testManager->getQuestionsByQuiz($kuisId);

if (empty($dataPertanyaan)) {
    die('Soal quiz belum tersedia.');
}

$totalSoal = count($dataPertanyaan);

?>

<?php include '../include/header.php'; ?>

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- ========== Left Sidebar Start ========== -->
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
                                        <?= $jenis === 'pre' ? 'Pre-Test' : 'Post-Test' ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card border-white shadow-sm" style="border-radius: 1.25rem; background-image: radial-gradient( circle farthest-corner at 10% 20%,  rgba(14,174,87,1) 0%, rgba(12,116,117,1) 90% );">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-xl me-3" style="width: 72px; height: 72px; border-radius: 50%; background-color: rgba(233, 236, 239, 0.5); display: inline-flex; align-items: center; justify-content: center;">
                                                        <img src="/assets/icon/focus-group.webp" alt="icon" style="width: 40px; height: 40px;" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div>
                                                        <h5 class="mb-1 text-white">
                                                            <?= htmlspecialchars($dataKuis['judul']) ?>
                                                        </h5>
                                                        <p class="badge rounded-pill mb-0" style="background-color: rgba(255,255,255,0.2);">
                                                            passing grade: <?= $dataKuis['passing_grade'] ?? 70 ?>
                                                        </p>
                                                        <p class="badge rounded-pill mb-0" style="background-color: rgba(255,255,255,0.2);">
                                                            <?= $totalSoal ?> Soal pilihan ganda
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

                                    <form
                                        id="formKuis"
                                        method="POST"
                                        action="../../src/actions/proses_submit_test.php">

                                        <input
                                            type="hidden"
                                            name="quiz_id"
                                            value="<?= (int) $dataKuis['id'] ?>">

                                        <input
                                            type="hidden"
                                            name="jenis"
                                            value="<?= htmlspecialchars($jenis) ?>">

                                        <div class="d-flex flex-column-reverse flex-md-row">
                                            <!-- Soal -->

                                            <div class="col-lg-9">

                                                <div class="card" style="border-radius: 1.25rem;">

                                                    <div class="card-body">

                                                        <?php
                                                        $total = count($dataPertanyaan);
                                                        ?>


                                                        <?php foreach ($dataPertanyaan as $i => $pertanyaan): ?>

                                                            <?php $no = $i + 1; ?>

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

                                                        <div class="d-flex justify-content-between">

                                                            <button
                                                                type="button"
                                                                class="btn btn-secondary btn-rounded"
                                                                id="btnPrev">

                                                                Prev

                                                            </button>

                                                            <button
                                                                type="button"
                                                                class="btn btn-primary btn-rounded"
                                                                id="btnNext">

                                                                Next

                                                            </button>

                                                            <button
                                                                type="button"
                                                                class="btn btn-success btn-rounded d-none"
                                                                id="btnFinishQuiz">

                                                                Selesai

                                                            </button>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                            <!-- Nomor soal -->
                                            <div class="col-lg-3">

                                                <div class="card mb-4" style="border-radius: 1.25rem; background-color: #fff;">

                                                    <div class="card-body">

                                                        <h5 class="mb-3">
                                                            Nomor Soal
                                                        </h5>

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
                                                            class="btn btn-success btn-rounded w-100">
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
                                                        type="button"
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

            // Handle button visibility based on current question
            const btnNext = document.getElementById("btnNext");
            const btnFinishQuiz = document.getElementById("btnFinishQuiz");

            function updateNavigationButtons() {
                if (current === questions.length - 1) {
                    // Last question - show "Selesai" button, hide "Next"
                    btnNext.classList.add("d-none");
                    btnFinishQuiz.classList.remove("d-none");
                } else {
                    // Not last question - show "Next", hide "Selesai"
                    btnNext.classList.remove("d-none");
                    btnFinishQuiz.classList.add("d-none");
                }
            }

            // Add click handler for the "Selesai" button
            btnFinishQuiz.onclick = function() {
                document.getElementById("btnFinish").click();
            };

            // Update button visibility on question change
            const originalTampil = tampil;
            tampil = function(index) {
                originalTampil(index);
                updateNavigationButtons();
            };

            // Initialize button visibility
            updateNavigationButtons();

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