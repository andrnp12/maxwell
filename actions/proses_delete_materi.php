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

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {

    $deleteMateri = new Materi();
    $result = $deleteMateri->deleteMateri((int)$_GET['id']);

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
