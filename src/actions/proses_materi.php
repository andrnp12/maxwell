<?php
session_start();
require_once '../classes/materi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak! Hanya admin yang dapat melakukan operasi pada materi.'
    ]);
    exit;
}

$materi = new Materi();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === "POST" && $action === 'get_materi') {
    $current_id = isset($_POST['current_id']) ? (int)$_POST['current_id'] : 0;
    
    // Ambil material_id dari materi yang sedang diedit (jika ada)
    $current_material_id = null;
    if ($current_id > 0) {
        $materiData = $materi->getMateriById($current_id);
        if ($materiData) {
            $current_material_id = $materiData['id'];
        }
    }
    
    $dataMateri = $materi->getAllMateri(); // Untuk materi, kita tidak perlu filter seperti kuis
    
    echo json_encode([
        'status' => 'success',
        'data' => $dataMateri,
        'current_material_id' => $current_material_id
    ]);
    exit;
}

// Simpan (Tambah / Edit)
if ($_SERVER['REQUEST_METHOD'] === "POST" && $action === 'save') {
    // Penyesuaian nama field agar cocok dengan kiriman JS baru
    $id         = !empty($_POST['id']) ? (int)$_POST['id'] : null;
    $judul      = $_POST['judul'] ?? '';
    $deskripsi  = $_POST['deskripsi'] ?? '';
    $videoUrl   = $_POST['video_url'] ?? '';
    $noUrut     = !empty($_POST['no_urut']) ? (int)$_POST['no_urut'] : 0;
    
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

    if (!empty($id)) {
        // Update materi
        $result = $materi->updateMateri($id, $judul, $deskripsi, $videoUrl, $noUrut, $file);
        $result['id'] = $id; // kembalikan ID untuk referensi JS
    } else {
        // Tambah materi baru
        $result = $materi->addMateri($judul, $deskripsi, $videoUrl, $noUrut, $file);
        // Dapatkan ID yang baru disisipkan menggunakan method baru
        if ($result['status'] === 'success') {
            $result['id'] = $materi->getLastInsertId();
        }
    }
    
    // Ambil data materi untuk rendering tabel di JS
    if ($result['status'] === 'success') {
        // Dapatkan data materi terbaru dari database untuk mendapatkan informasi file yang benar
        $materiData = $materi->getMateriById($result['id']);
        if ($materiData) {
            $result['judul'] = $materiData['judul'];
            $result['deskripsi'] = $materiData['deskripsi'];
            $result['video_url'] = $materiData['video_url'];
            $result['no_urut'] = $materiData['no_urut'];
            $result['file'] = $materiData['file']; // Tambahkan data file dari database
        }
    }
    
    echo json_encode($result);
    exit;
}

// Hapus materi
if ((($_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') && 
     isset($_GET['action']) && $_GET['action'] === 'delete') ||
    (($_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') && 
     isset($_POST['action']) && $_POST['action'] === 'delete')) {
    
    // Get ID from appropriate source based on request method
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
            'status' => 'error',
            'message' => 'ID materi tidak valid.'
        ]);
        exit;
    }
    
    $result = $materi->deleteMateri($id);
    
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

echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenali.']);
exit;
?>