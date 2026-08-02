<?php
session_start();
require_once '../classes/materi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak! Hanya admin yang dapat menambah materi.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $videoUrl = $_POST['video_url'] ?? '';

    $noUrut = !empty($_POST['no_urut']) ? (int)$_POST['no_urut'] : 0;

    $file = isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] !== UPLOAD_ERR_NO_FILE
        ? $_FILES['file_materi']
        : null;

    if (empty($judul) || empty($deskripsi)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Judul dan deskripsi tidak boleh kosong!'
        ]);
        exit;
    }

    $materi = new Materi();
    $result = $materi->addMateri($judul, $deskripsi, $videoUrl, $noUrut, $file);

    if ($result['status'] === 'success') {
        echo json_encode([
            'status' => 'success',
            'message' => $result['message']
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'metode tidak diizinkan'
        ]);
    }
}
