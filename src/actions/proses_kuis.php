<?php
session_start();
require_once '../classes/kuis.php';

header('Content-Type: application/json');

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak! Hanya admin yang dapat menambah materi.'
    ]);
    exit;
}

$kuis = new Kuis();

// ambil 
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id > 0) {
        $data = $kuis->getKuisById($id);

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

// edit
// Proses POST (Bisa untuk Tambah atau Edit)
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $id = (!empty($_POST['id_kuis'])) ? (int)$_POST['id_kuis'] : null;
    $id_materi = isset($_POST['id_materi']) ? (int)$_POST['id_materi'] : 0;;
    $judul = $_POST['judul_kuis'];
    $passingGrade = isset($_POST['passing_grade']) ? (int)$_POST['passing_grade'] : 0;
    if (!empty($id)) {
        $result = $kuis->updateKuis($id, $id_materi, $judul, $passingGrade);
    } else {
        $result = $kuis->addKuis($id_materi, $judul, $passingGrade);
    }

    if ($result['status'] === 'success') {
        echo json_encode([
            'status' => 'success',
            'message' => $result['message']
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => $result['message']
        ]);
    }

    exit;
}

// hapus
if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id > 0) {
        $result = $kuis->deleteKuis($id);
        echo json_encode($result);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID tidak valid atau tidak dikirim.'
        ]);
    }
    exit;
}
