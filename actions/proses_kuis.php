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

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $id_materi = $_POST['id_materi'] ?? '';
    $judul = $_POST['judul'] ?? '';
    $passingGrade = $_POST['passing_grade'] ?? '';

    $kuis = new Kuis();
    $result = $kuis->addKuis($id_materi, $judul, $passingGrade);

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
}
