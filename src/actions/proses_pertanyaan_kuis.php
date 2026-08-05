<?php
session_start();
require_once '../classes/pertanyaan_kuis.php';

header('Content-Type: application/json');

// Check authentication
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak! Hanya admin yang dapat menambah materi.'
    ]);
    exit;
}

$pertanyaanModel = new PertanyaanKuis();

// Handle GET: retrieve a single question
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id > 0) {
        $data = $pertanyaanModel->getPertanyaanKuisbyId($id);
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

// Handle POST: add or edit based on presence of 'kuis_id'
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If adding a new question
    if (!empty($_POST['kuis_id'])) {
        $kuisId = (int)($_POST['kuis_id'] ?? 0);
        $pertanyaan = $_POST['pertanyaan'] ?? '';
        $opsi_a = $_POST['opsi_a'] ?? '';
        $opsi_b = $_POST['opsi_b'] ?? '';
        $opsi_c = $_POST['opsi_c'] ?? '';
        $opsi_d = $_POST['opsi_d'] ?? '';
        $jawaban = $_POST['jawaban'] ?? '';

        $result = $pertanyaanModel->tambahPertanyaanKuis($kuisId, $pertanyaan, $opsi_a, $opsi_b, $opsi_c, $opsi_d, $jawaban);

        if ($result) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Pertanyaan berhasil ditambahkan.',
                'id' => $result,
                'pertanyaan' => $pertanyaan,
                'opsi_a' => $opsi_a,
                'opsi_b' => $opsi_b,
                'opsi_c' => $opsi_c,
                'opsi_d' => $opsi_d,
                'jawaban' => $jawaban
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Pertanyaan gagal ditambahkan.'
            ]);
        }
    }
    // If editing an existing question
    elseif (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $pertanyaan = $_POST['pertanyaan'] ?? '';
        $opsi_a = $_POST['opsi_a'] ?? '';
        $opsi_b = $_POST['opsi_b'] ?? '';
        $opsi_c = $_POST['opsi_c'] ?? '';
        $opsi_d = $_POST['opsi_d'] ?? '';
        $jawaban = $_POST['jawaban'] ?? '';

        if ($pertanyaanModel->editPertanyaanKuis($id, $pertanyaan, $opsi_a, $opsi_b, $opsi_c, $opsi_d, $jawaban)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Pertanyaan berhasil diupdate.',
                'id' => $id,
                'pertanyaan' => $pertanyaan,
                'opsi_a' => $opsi_a,
                'opsi_b' => $opsi_b,
                'opsi_c' => $opsi_c,
                'opsi_d' => $opsi_d,
                'jawaban' => $jawaban
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui pertanyaan.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Parameter tidak valid untuk POST.'
        ]);
    }
    exit;
}

// Handle DELETE: delete a question
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id > 0) {
        $result = $pertanyaanModel->deletePertanyaanKuis($id);
        echo json_encode($result);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat menghapus pertanyaan.'
        ]);
    }
    exit;
}
?>