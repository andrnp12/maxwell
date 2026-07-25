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
$model = new PertanyaanKuis();

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id > 0) {
        $data = $model->getPertanyaanKuisbyId($id);

        if ($data) {
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Kuis tidak ditemukan.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID tidak valid atau tidak dikirim.'
        ]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $id = $_POST['id'] ?? '';
    $pertanyaan = $_POST['pertanyaan'] ?? '';
    $opsi_a = $_POST['opsi_a'] ?? '';
    $opsi_b = $_POST['opsi_b'] ?? '';
    $opsi_c = $_POST['opsi_c'] ?? '';
    $opsi_d = $_POST['opsi_d'] ?? '';
    $jawaban = $_POST['jawaban'] ?? '';

    if ($model->editPertanyaanKuis($id, $pertanyaan, $opsi_a, $opsi_b, $opsi_c, $opsi_d, $jawaban)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pertanyaan berhasil diupdate.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat memperbarui pertanyaan.'
        ]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id > 0) {

        $result = $model->deletePertanyaanKuis($id);
        echo json_encode($result);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat menghapus pertanyaan.'
        ]);
    }
    exit;
}
