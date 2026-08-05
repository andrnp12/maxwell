<?php
session_start();
header('Content-Type: application/json');

require_once '../classes/komunitas.php';

$komunitas = new Komunitas();

try {

    if (!isset($_SESSION['id'])) {
        throw new Exception("Silakan login terlebih dahulu.");
    }

    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'join_group':

            $idKomunitas = (int) ($_POST['id_komunitas'] ?? 0);
            $idUser = (int) $_SESSION['id'];

            if ($idKomunitas <= 0) {
                throw new Exception("Komunitas tidak valid.");
            }

            $result = $komunitas->joinGroup(
                $idKomunitas,
                $idUser
            );

            break;

        case 'get_all':
            // Admin: Get all communities for DataTable
            $data = $komunitas->getAllKomunitasAdmin();
            $result = [
                'status' => 'success',
                'data' => $data
            ];
            break;

        case 'save':
            // Admin: Create new community
            // Check if admin (you may want to add role check here)
            
            $namaKomunitas = trim($_POST['nama_komunitas'] ?? '');
            $deskripsi = trim($_POST['deskripsi'] ?? '');
            
            if (empty($namaKomunitas)) {
                throw new Exception("Nama komunitas wajib diisi.");
            }
            if (empty($deskripsi)) {
                throw new Exception("Deskripsi wajib diisi.");
            }

            // Handle file upload
            $foto = 'default.jpg'; // default
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../../uploads/komunitas/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                $fileType = mime_content_type($_FILES['foto']['tmp_name']);
                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception("Format foto tidak didukung. Gunakan JPG, PNG, atau WebP.");
                }
                
                $maxSize = 5 * 1024 * 1024; // 5MB
                if ($_FILES['foto']['size'] > $maxSize) {
                    throw new Exception("Ukuran foto maksimal 5MB.");
                }
                
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto = 'komunitas_' . time() . '_' . uniqid() . '.' . $ext;
                $targetPath = $uploadDir . $foto;
                
                if (!move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
                    throw new Exception("Gagal mengunggah foto.");
                }
            }

            $result = $komunitas->createKomunitas($namaKomunitas, $deskripsi, $foto);
            break;

        case 'update':
            // Admin: Update community
            $id = (int) ($_POST['id'] ?? 0);
            $namaKomunitas = trim($_POST['nama_komunitas'] ?? '');
            $deskripsi = trim($_POST['deskripsi'] ?? '');
            
            if ($id <= 0) {
                throw new Exception("ID komunitas tidak valid.");
            }
            if (empty($namaKomunitas)) {
                throw new Exception("Nama komunitas wajib diisi.");
            }
            if (empty($deskripsi)) {
                throw new Exception("Deskripsi wajib diisi.");
            }

            // Handle file upload
            $foto = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../../uploads/komunitas/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                $fileType = mime_content_type($_FILES['foto']['tmp_name']);
                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception("Format foto tidak didukung. Gunakan JPG, PNG, atau WebP.");
                }
                
                $maxSize = 5 * 1024 * 1024; // 5MB
                if ($_FILES['foto']['size'] > $maxSize) {
                    throw new Exception("Ukuran foto maksimal 5MB.");
                }
                
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto = 'komunitas_' . time() . '_' . uniqid() . '.' . $ext;
                $targetPath = $uploadDir . $foto;
                
                if (!move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
                    throw new Exception("Gagal mengunggah foto.");
                }
            }

            $result = $komunitas->updateKomunitas($id, $namaKomunitas, $deskripsi, $foto);
            break;

        case 'delete':
            // Admin: Delete community
            $id = (int) ($_POST['id'] ?? 0);
            
            if ($id <= 0) {
                throw new Exception("ID komunitas tidak valid.");
            }

            $result = $komunitas->deleteKomunitas($id);
            break;

        default:

            throw new Exception("Action tidak dikenali.");
    }

    echo json_encode($result);
} catch (Throwable $e) {

    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]);
}
