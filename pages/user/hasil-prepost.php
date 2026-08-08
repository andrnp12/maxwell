<?php

require_once '../../src/classes/auth.php';
require_once '../../src/classes/kuis.php';
require_once '../../src/classes/hasil_kuis.php';
require_once '../../src/classes/materi.php';

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

$hasil = $hasilKuis->getResultById($resultId, $userId);

if (!$hasil) {
    header("Location: login.php");
    exit;
}
?>
<?php include '../include/header.php'; ?>

<body>
    <div id="layout-wrapper">
        <div class="">

            <div class="container-fluid">
                <div class="card mb-3">
                    <div class="card-body text-center mb-3">

                        <?php if ($hasil['lulus']) : ?>
                            <div class="display-1 text-success">
                                <i class="mdi mdi-trophy"></i>
                            </div>
                            <h2 class="fw-bold">
                                Selamat!
                            </h2>
                            <p class="text-muted">
                                Anda berhasil menyelesaikan <?= $type ?> Test.
                            </p>
                        <?php else : ?>
                            <div class="display-1 text-warning mb-3">
                                <i class="mdi mdi-book-open-page-variant"></i>
                            </div>
                            <h2 class="fw-bold">
                                Tetap Semangat!
                            </h2>
                            <p class="text-muted">
                                Sedikit lagi Anda mencapai passing grade.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <small class="text-muted">
                            Nilai Anda
                        </small>
                        <h1 class="display-2 fw-bold mb-3">
                            <?= $hasil['nilai'] ?>
                        </h1>
                    </div>
                </div>
                <div class="card mb-3">
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
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2 mb-3">
                    <a
                        href="index.php"
                        class="btn btn-success">
                        Ke Menu Utama
                    </a>
                </div>
            </div>

        </div>
    </div>
</body>