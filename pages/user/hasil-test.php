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
                            Anda berhasil menyelesaikan kuis.
                        </p>
                        <h4 class="fw-bold text-white">
                            <?= $hasil['judul'] ?>
                        </h4>
                        <div>
                            <small class="badge text-dark px-2 py-1 rounded-pill" style="background-color: rgba(233, 236, 239, 0.5);">
                                <i class="mdi mdi-information"></i>
                                Passing Grade : <?= $hasil['passing_grade'] ?>
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
                                <?= $hasil['percobaan'] ?>
                            </h3>
                            <small class="text-muted">
                                Percobaan
                            </small>
                        </div>
                    </div>
                </div>
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
                            class="btn btn-dark">
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
                    class="btn text-white btn-outline-light">
                    Kembali ke Daftar Kuis
                </a>
            </div>
        </div>
    </div>
</body>