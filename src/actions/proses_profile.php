<?php
session_start();
require_once '../classes/profile.php';

header('Content-Type: application/json');

if (!isset($_SESSION['is_logged_in'])) {
    ob_clean();
    echo json_encode([
        'status' => 'error',
        'message' => 'Sesi berakhir, silakan login kembali.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    $profile = new Profile();

    try {
        if ($action === 'update_profile') {
            if ($id <= 0) {
                throw new Exception('ID pengguna tidak valid.');
            }

            $username  = $_POST['username'] ?? '';
            $nama      = $_POST['name'] ?? '';
            $nomor     = $_POST['nomor'] ?? '';
            $email     = $_POST['email'] ?? '';
            $password  = $_POST['password'] ?? '';
            $ringkasan = $_POST['ringkasan'] ?? '';

            $file = isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE
                ? $_FILES['foto']
                : null;

            $result = $profile->updateProfile($id, $file, $username, $nama, $nomor, $email, $password, $ringkasan);
        }
    } catch (Exception $e) {
        $result = [
            'status' => 'error',
            'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
        ];
    }

    echo json_encode($result);
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak diizinkan.']);
}
