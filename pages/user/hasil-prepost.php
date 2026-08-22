<?php

require_once '../../src/classes/auth.php';
require_once '../../src/classes/kuis.php';
require_once '../../src/classes/hasil_kuis.php';
require_once '../../src/classes/materi.php';
require_once '../../src/classes/tests.php';

$auth = new Auth();
$auth->authOrNot();

$resultId = filter_input(INPUT_GET, 'result', FILTER_VALIDATE_INT);
$type     = htmlspecialchars($_GET['type'] ?? '', ENT_QUOTES, 'UTF-8');

if (!$resultId) {
    header("Location: login.php");
    exit;
}

$userId = (int)$_SESSION['id'];

$hasilKuis = new HasilKuis();
$materi = new Materi();
$testManager = new tests();

$hasil = $hasilKuis->getResultById($resultId, $userId);

if (!$hasil) {
    header("Location: login.php");
    exit;
}

// Get quiz title if available
$quizData = $testManager->getQuizByJenis($type);
$quizTitle = $quizData ? $quizData['judul'] : ucfirst($type) . ' Test';

$passingGrade = $quizData['passing_grade'] ?? 70;
?>
<?php include '../include/header.php'; ?>

<body class="d-flex align-items-center justify-content-center min-vh-100 py-5" style="<?= $hasil['lulus'] ? 'background-color: #198754;' : 'background-color: #dc3545;' ?>">
    <div id="layout-wrapper">
        <div class="container-fluid">
            <div class="card bg-transparent border-0 mb-3">
                <div class="card-body text-center mb-3">

                    <?php if ($hasil['lulus']) : ?>
                        <div class="display-1 text-warning mb-3">
                            <i class="mdi mdi-trophy"></i>
                        </div>
                        <h2 class="fw-bold text-white">
                            Selamat!
                        </h2>
                        <p class="text-white-50">
                            Anda berhasil menyelesaikan <?= $type ?> Test.
                        </p>
                        <h4 class="fw-bold text-white">
                            <?= $quizTitle ?>
                        </h4>
                        <div>
                            <small class="badge text-dark px-2 py-1 rounded-pill" style="background-color: rgba(233, 236, 239, 0.5);">
                                <i class="mdi mdi-information"></i>
                                Passing Grade : <?= $passingGrade ?>
                            </small>
                        </div>
                    <?php else : ?>
                        <div class="display-1 text-warning mb-3">
                            <i class="mdi mdi-book-open-page-variant"></i>
                        </div>
                        <h2 class="fw-bold text-white">
                            Tetap Semangat!
                        </h2>
                        <p class="text-white-50">
                            Sedikit lagi Anda mencapai passing grade.
                        </p>
                        <h4 class="fw-bold text-white">
                            <?= $quizTitle ?>
                        </h4>
                        <div>
                            <small class="badge text-dark px-2 py-1 rounded-pill" style="background-color: rgba(233, 236, 239, 0.5);">
                                <i class="mdi mdi-information"></i>
                                Passing Grade : <?= $passingGrade ?>
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card bg-transparent border-0 mb-3">
                <div class="card-body text-center">
                    <small class="text-white-50">
                        Nilai Anda
                    </small>
                    <h1 class="display-2 fw-bold mb-3 text-white">
                        <?= $hasil['nilai'] ?>
                    </h1>
                    <?php if ($hasil['lulus']) : ?>
                        <span class="badge bg-white text-success fs-6">
                            LULUS
                        </span>
                    <?php else : ?>
                        <span class="badge bg-danger text-white fs-6">
                            BELUM LULUS
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card border-0 mb-5" style="border-radius: 1.25rem; background-color: rgba(233, 236, 239, 0.5);">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col">
                            <h3>
                                <?= $hasil['jumlah_benar'] ?>
                            </h3>
                            <small class="text-muted">
                                Jawaban Benar
                            </small>
                        </div>
                        <div class="col">
                            <h3>
                                <?= $hasil['jumlah_salah'] ?>
                            </h3>
                            <small class="text-muted">
                                Jawaban Salah
                            </small>
                        </div>
                        <div class="col">
                            <h3>
                                <?= $hasil['percobaan'] ?? 1 ?>
                            </h3>
                            <small class="text-muted">
                                Percobaan
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-grid gap-2 mb-3">
                <a
                    href="index.php"
                    class="btn btn-light">
                    Kembali ke Menu
                </a>
            </div>
        </div>
    </div>
</body>