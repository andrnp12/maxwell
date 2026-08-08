<?php

require_once '../classes/auth.php';
require_once '../classes/kuis.php';
require_once '../classes/pertanyaan_kuis.php';
require_once '../classes/progress_user.php';
require_once '../classes/hasil_kuis.php';

$auth = new Auth();
$auth->authOrNot();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../pages/user/skill.php");
    exit;
}

$userId = (int) $_SESSION['id'];

$quizId = filter_input(
    INPUT_POST,
    'quiz_id',
    FILTER_VALIDATE_INT
);

$jenis = $_POST['jenis'] ?? null;

if (!$quizId) {
    header("Location: ../../pages/user/skill.php");
    exit;
}

if (!in_array($jenis, ['kuis', 'pre', 'post'], true)) {
    header("Location: ../../pages/user/skill.php");
    exit;
}

$jawabanUser = $_POST['jawaban'] ?? [];

$kuis = new Kuis();
$pertanyaan = new PertanyaanKuis();
$progress = new ProgressUser();
$hasilKuis = new HasilKuis();


$dataKuis = $kuis->getKuisById($quizId);

if (!$dataKuis) {
    header("Location: ../../pages/user/skill.php");
    exit;
}

if ($jenis === 'kuis') {

    if (
        !$progress->isMaterialFinished(
            $userId,
            (int) $dataKuis['material_id']
        )
    ) {
        header("Location: ../../pages/user/skill.php");
        exit;
    }
}

$hasil = $pertanyaan->calculateResult(
    $quizId,
    $jawabanUser
);

if ($jenis === 'kuis') {
    $lulus = (
        $hasil['persentase'] >= $dataKuis['passing_grade']
    );
} else {
    $lulus = 1;
}

$resultId = $hasilKuis->saveResult(
    $userId,
    $quizId,
    $hasil['benar'],
    $hasil['salah'],
    $hasil['persentase'],
    $lulus,
    $jenis
);

if (!$resultId) {
    header("Location: ../../pages/user/skill.php");
    exit;
}

if ($jenis === 'kuis' && $lulus) {

    $progress->finishQuiz(
        $userId,
        (int) $dataKuis['material_id']
    );
}

if ($jenis === 'kuis') {
    header("Location: ../../pages/user/hasil-test.php?result=$resultId");
} else {
    header("Location: ../../pages/user/hasil-prepost.php?result=$resultId&type=$jenis");
}

exit;
