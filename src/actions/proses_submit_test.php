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

$userId = (int)$_SESSION['id'];

$quizId = filter_input(INPUT_POST, 'quiz_id', FILTER_VALIDATE_INT);

if (!$quizId) {

    header("Location: ../../pages/user/skill.php");
    exit;
}

$jawabanUser = $_POST['jawaban'] ?? [];

$kuis = new Kuis();
$pertanyaan = new PertanyaanKuis();
$progress = new ProgressUser();
$hasilKuis = new HasilKuis();

$dataKuis = $kuis->getKuisById($quizId);

$hasil = $pertanyaan->calculateResult(
    $quizId,
    $jawabanUser
);

if (!$dataKuis) {

    header("Location: ../../pages/user/skill.php");
    exit;
}

if (
    !$progress->isMaterialFinished(
        $userId,
        (int)$dataKuis['material_id']
    )
) {

    header("Location: ../../pages/user/skill.php");
    exit;
}

$hasil = $pertanyaan->calculateResult(
    $quizId,
    $jawabanUser
);

$lulus = (
    $hasil['persentase'] >= $dataKuis['passing_grade']
);

$resultId = $hasilKuis->saveResult(
    $userId,
    $quizId,
    $hasil['benar'],
    $hasil['salah'],
    $hasil['persentase'],
    $lulus,
    'kuis'
);

if (!$resultId) {
    header("Location: ../../pages/user/skill.php");
    exit;
}

if ($lulus) {
    $progress->finishQuiz(
        $userId,
        (int)$dataKuis['material_id']
    );
}

header(
    "Location: ../../pages/user/hasil-test.php?result=" . $resultId
);

exit;
