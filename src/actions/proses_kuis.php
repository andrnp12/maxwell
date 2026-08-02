<?php
session_start();
require_once '../classes/kuis.php';

header('Content-Type: application/json');

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak!']);
    exit;
}

$kuis = new Kuis();
$action = $_POST['action'] ?? ($_SERVER['REQUEST_METHOD'] === 'DELETE' ? 'delete' : '');

// Ambil detail untuk edit (bisa tetap digunakan jika diperlukan di tempat lain)
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id > 0) {
        $data = $kuis->getKuisById($id);
        echo json_encode($data ? ['status' => 'success', 'data' => $data] : ['status' => 'error', 'message' => 'Kuis tidak ditemukan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak valid.']);
    }
    exit;
}

// Ambil materi yang tersedia untuk kuis (untuk dropdown tambah/edit)
if ($_SERVER['REQUEST_METHOD'] === "POST" && $action === 'get_materi') {
    $current_id = isset($_POST['current_id']) ? (int)$_POST['current_id'] : 0;
    
    require_once '../classes/materi.php';
    $materi = new Materi();
    
    // Ambil material_id dari kuis yang sedang diedit (jika ada)
    $current_material_id = null;
    if ($current_id > 0) {
        $kuisData = $kuis->getKuisById($current_id);
        if ($kuisData) {
            $current_material_id = $kuisData['material_id'];
        }
    }
    
    $dataMateri = $materi->getMateriNonKuis($current_material_id);
    
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
    $id_materi  = isset($_POST['id_materi']) ? (int)$_POST['id_materi'] : 0;
    $judul      = $_POST['judul_kuis'] ?? '';
    $passingGrade = isset($_POST['passing_grade']) ? (int)$_POST['passing_grade'] : 0;

    if (!empty($id)) {
        $result = $kuis->updateKuis($id, $id_materi, $judul, $passingGrade);
        $result['id'] = $id; // kembalikan ID untuk referensi JS
    } else {
        $result = $kuis->addKuis($id_materi, $judul, $passingGrade);
        // Asumsi $result mengandung insert_id atau ambil dari method addKuis
        if ($result['status'] === 'success' && !empty($result['id'])) {
            $result['id'] = $result['id'];
        }
    }

    // Ambil judul materi untuk rendering tabel di JS
    if ($result['status'] === 'success' && $id_materi > 0) {
        require_once '../classes/materi.php';
        $materi = new Materi();
        $materiData = $materi->getMateriById($id_materi);
        if ($materiData) {
            $result['material_title'] = $materiData['judul'];
        }
    }
    
    echo json_encode($result);
    exit;
}


// Hapus
if ($_SERVER['REQUEST_METHOD'] === "POST" && $action === 'delete') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($id > 0) {
        $success = $kuis->deleteKuis($id);
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Kuis berhasil dihapus.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Kuis gagal dihapus.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak valid.']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenali.']);
exit;
?>