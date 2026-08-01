<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../classes/tests.php';

header('Content-Type: application/json');

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Anda harus login terlebih dahulu.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['id'];
    $testId = $_POST['test_id'] ?? 0;
    $tipeSesi = $_POST['tipe_sesi'] ?? 'pre';
    $userAnswer = $_POST['answers'] ?? [];

    if (empty($testId) || empty($userAnswer)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID tes dan jawaban harus diisi.'
        ]);
        exit;
    }

    $tests = new tests();
    $result = $tests->calculateScore($userId, $testId, $tipeSesi, $userAnswer);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Tes berhasil diselesaikan.',
            'data' => $result
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat memproses tes.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak valid.'
    ]);
}
