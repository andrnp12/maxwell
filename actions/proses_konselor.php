<?php
session_start();
require_once '../classes/konselor.php';

header('Content-Type: application/json');

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    ob_clean();
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak! Hanya admin yang memiliki wewenang.'
    ]);
    exit;
}

// 2. Pastikan Request menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    $konselor = new Konsultan();

    try {
        if ($action === 'save') {
            $nama = $_POST['nama'] ?? '';
            $nomor = $_POST['nomor'] ?? '';
            $email = $_POST['email'] ?? '';
            $ringkasan = $_POST['ringkasan'] ?? '';

            $file = isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE
                ? $_FILES['foto']
                : null;

            if ($id > 0) {
                $result = $konselor->updateKonsultan($id, $file, $nama, $nomor, $email, $ringkasan);
            } else {
                $result = $konselor->addKonsultan($file, $nama, $nomor, $email, $ringkasan);
            }
        }
        elseif ($action === 'delete') {
            if ($id > 0) {
                $result = $konselor->deleteKonsultan($id);
            } else {
                $result = ['status' => 'error', 'message' => 'ID tidak valid untuk dihapus.'];
            }
        }
        else {
            $result = ['status' => 'error', 'message' => 'Aksi tidak dikenal.'];
        }
    } catch (Exception $e) {
        $result = [
            'status' => 'error',
            'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
        ];
    }

    echo json_encode($result);
    exit;
}
