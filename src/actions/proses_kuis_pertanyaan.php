<?php
session_start();

require_once '../classes/pertanyaan_kuis.php';

header('Content-Type: application/json');

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak! Hanya admin yang dapat menambah materi.'
    ]);
    exit;
}


// === TAMBAHKAN KODE INI UNTUK MENANGKAP FORM ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kuisId = $_POST['kuis_id'] ?? 0;
    $pertanyaan = $_POST['pertanyaan'] ?? '';
    $opsi_a = $_POST['opsi_a'] ?? '';
    $opsi_b = $_POST['opsi_b'] ?? '';
    $opsi_c = $_POST['opsi_c'] ?? '';
    $opsi_d = $_POST['opsi_d'] ?? '';
    $jawaban = $_POST['jawaban'] ?? '';

    $pertanyaanModel = new PertanyaanKuis();

    $result = $pertanyaanModel->tambahPertanyaanKuis($kuisId, $pertanyaan, $opsi_a, $opsi_b, $opsi_c, $opsi_d, $jawaban);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pertanyaan berhasil ditambahkan.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Pertanyaan gagal ditambahkan.'
        ]);
    }
}
