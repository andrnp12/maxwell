<?php

require_once '../../src/classes/auth.php';
require_once '../../src/classes/kuis.php';
require_once '../../src/classes/hasil_kuis.php';
require_once '../../src/classes/materi.php';

$auth = new Auth();
$auth->authOrNot();

$resultId = filter_input(INPUT_GET, 'result', FILTER_VALIDATE_INT);

if (!$resultId) {
    header("Location: skill.php");
    exit;
}

$userId = (int)$_SESSION['id'];

$hasilKuis = new HasilKuis();
$materi = new Materi();

$hasil = $hasilKuis->getResultById($resultId, $userId);

if (!$hasil) {
    header("Location: skill.php");
    exit;
}

$nextMateri = $materi->getNextMateri(
    (int)$hasil['material_id']
);
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
                                Anda berhasil menyelesaikan kuis.
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
                        <?php if ($hasil['lulus']) : ?>
                            <span class="badge bg-success fs-6">
                                LULUS
                            </span>
                        <?php else : ?>
                            <span class="badge bg-danger fs-6">
                                BELUM LULUS
                            </span>
                        <?php endif; ?>
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
                            <div class="col">
                                <h3>
                                    <?= $hasil['percobaan'] ?>
                                </h3>
                                <small class="text-muted">
                                    Percobaan
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mb-3">
                    Passing Grade
                    <strong>
                        <?= $hasil['passing_grade'] ?>
                    </strong>
                </div>
                <div class="d-grid gap-2 mb-3">
                    <?php if (!$hasil['lulus']) : ?>
                        <a
                            href="detail-materi.php?id=<?= $hasil['material_id'] ?>"
                            class="btn btn-primary">
                            Pelajari Materi Lagi
                        </a>
                        <a
                            href="skill-detail.php?id=<?= $hasil['id'] ?>"
                            class="btn btn-warning">
                            Ulangi Kuis
                        </a>
                    <?php endif; ?>
                    <?php if ($hasil['lulus']) : ?>
                        <?php if ($nextMateri): ?>
                            <a
                                href="detail-materi.php?id=<?= $nextMateri['id'] ?>"
                                class="btn btn-success">
                                Materi Berikutnya
                            </a>
                        <?php else: ?>
                            <a
                                href="belajar.php"
                                class="btn btn-success">
                                Selesai Pembelajaran
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a
                        href="skill.php"
                        class="btn btn-outline-secondary">
                        Kembali ke Daftar Kuis
                    </a>
                </div>
            </div>

        </div>
    </div>
</body>