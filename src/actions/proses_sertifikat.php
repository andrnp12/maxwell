<?php

/**
 * Proses Sertifikat
 * Handler untuk operasi CRUD sertifikat via AJAX
 * 
 * @package App\Admin
 * @version 1.0
 * @author System
 */

session_start();
require_once '../classes/Sertifikat.php';

header('Content-Type: application/json');

// Cek autentikasi dan autorisasi admin
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        'success' => false,
        'message' => 'Akses ditolak! Hanya admin yang dapat melakukan operasi pada sertifikat.'
    ]);
    exit;
}

$sertifikat = new Sertifikat();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ============================================================
// ACTION: ADD (Tambah Sertifikat Baru)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $judul = trim($_POST['judul'] ?? '');
    $file = isset($_FILES['file_sertifikat']) && $_FILES['file_sertifikat']['error'] !== UPLOAD_ERR_NO_FILE
        ? $_FILES['file_sertifikat']
        : null;

    // Validasi required
    if (empty($judul)) {
        echo json_encode([
            'success' => false,
            'message' => 'Judul sertifikat wajib diisi!',
            'errors' => ['judul' => 'Judul sertifikat wajib diisi.']
        ]);
        exit;
    }

    if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
        echo json_encode([
            'success' => false,
            'message' => 'File sertifikat wajib diunggah!',
            'errors' => ['file' => 'File sertifikat wajib diunggah.']
        ]);
        exit;
    }

    $data = ['judul' => $judul];
    $result = $sertifikat->create($data, $file);

    // Tambahkan data untuk response
    if ($result['success']) {
        $newData = $sertifikat->getById($result['id']);
        if ($newData) {
            $result['data'] = $newData;
        }
    }

    echo json_encode($result);
    exit;
}

// ============================================================
// ACTION: EDIT (Update Sertifikat)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $judul = trim($_POST['judul'] ?? '');
    $file = isset($_FILES['file_sertifikat']) && $_FILES['file_sertifikat']['error'] !== UPLOAD_ERR_NO_FILE
        ? $_FILES['file_sertifikat']
        : null;

    if ($id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'ID sertifikat tidak valid.'
        ]);
        exit;
    }

    if (empty($judul)) {
        echo json_encode([
            'success' => false,
            'message' => 'Judul sertifikat wajib diisi!',
            'errors' => ['judul' => 'Judul sertifikat wajib diisi.']
        ]);
        exit;
    }

    $data = ['judul' => $judul];
    $result = $sertifikat->update($id, $data, $file);

    // Tambahkan data untuk response
    if ($result['success']) {
        $updatedData = $sertifikat->getById($id);
        if ($updatedData) {
            $result['data'] = $updatedData;
        }
    }

    echo json_encode($result);
    exit;
}

// ============================================================
// ACTION: DELETE (Hapus Sertifikat)
// ============================================================
if (($action === 'delete') && in_array($_SERVER['REQUEST_METHOD'], ['POST', 'DELETE', 'GET'])) {
    // Get ID from appropriate source
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = isset($_DELETE['id']) ? (int)$_DELETE['id'] : 0;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    } else { // POST
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    }

    if ($id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'ID sertifikat tidak valid.'
        ]);
        exit;
    }

    $result = $sertifikat->delete($id);
    echo json_encode($result);
    exit;
}

// ============================================================
// ACTION: GET (Ambil data by ID untuk prefill edit modal)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'get') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'ID sertifikat tidak valid.'
        ]);
        exit;
    }

    $data = $sertifikat->getById($id);

    if ($data) {
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Sertifikat tidak ditemukan.'
        ]);
    }
    exit;
}

// ============================================================
// ACTION: GET_SERTIFIKAT (Ambil semua data untuk refresh tabel - seperti get_materi)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'get_sertifikat') {
    $data = $sertifikat->getAll();

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
    exit;
}

// ============================================================
// ACTION TIDAK DIKENALI
// ============================================================
echo json_encode([
    'success' => false,
    'message' => 'Aksi tidak dikenali: ' . $action
]);
exit;
