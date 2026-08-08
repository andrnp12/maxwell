<?php

/**
 * Class Sertifikat
 * Mengelola operasi CRUD untuk data sertifikat
 * 
 * @package App\Admin
 * @version 1.0
 * @author System
 */

require_once 'dbconnect.php';

class Sertifikat
{
    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    public function getLastInsertId(): int
    {
        return $this->conn->insert_id;
    }

    /**
     * Validasi input sertifikat
     */
    public function validate(array $data, ?array $file = null): array
    {
        $errors = [];

        if (empty(trim($data['judul'] ?? ''))) {
            $errors['judul'] = 'Judul sertifikat wajib diisi.';
        } elseif (strlen($data['judul']) > 255) {
            $errors['judul'] = 'Judul sertifikat maksimal 255 karakter.';
        }

        // Validasi file hanya jika ada file yang diupload
        if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            if (!in_array($file['type'], $allowedTypes)) {
                $errors['file'] = 'Tipe file tidak diizinkan. Hanya PDF, JPG, dan PNG.';
            }

            if ($file['size'] > $maxSize) {
                $errors['file'] = 'Ukuran file melebihi batas maksimal 5MB.';
            }
        }

        return $errors;
    }

    /**
     * Upload file sertifikat
     */
    public function uploadFile(array $file, string $judul): array
    {
        $uploadDir = __DIR__ . '/../../uploads/sertifikat/';

        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];

        if (!in_array($ekstensi, $allowedExtensions)) {
            return [
                'success' => false,
                'message' => 'Hanya file PDF, JPG, dan PNG yang diperbolehkan.'
            ];
        }

        $judulFile = strtolower($judul);
        $judulFile = preg_replace('/[^a-z0-9]/', '_', $judulFile);
        $judulFile = trim($judulFile, '_');

        $namaFileTersimpan = 'sertifikat_' . $judulFile . '_' . time() . '.' . $ekstensi;
        $tujuanUpload = $uploadDir . $namaFileTersimpan;

        if (!move_uploaded_file($file['tmp_name'], $tujuanUpload)) {
            return [
                'success' => false,
                'message' => 'Gagal mengunggah file.'
            ];
        }

        return [
            'success' => true,
            'filename' => $namaFileTersimpan
        ];
    }

    /**
     * Hapus file fisik sertifikat
     */
    public function deleteFile(string $filename): bool
    {
        $filePath = __DIR__ . '/../../uploads/sertifikat/' . $filename;

        if (file_exists($filePath) && is_file($filePath)) {
            return unlink($filePath);
        }

        return true;
    }

    /**
     * Tambah sertifikat baru
     */
    public function create(array $data, ?array $file = null): array
    {
        // Validasi input
        $errors = $this->validate($data, $file);
        if (!empty($errors)) {
            return [
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $errors
            ];
        }

        $judul = trim($data['judul']);
        $namaFileTersimpan = null;

        // Upload file jika ada
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->uploadFile($file, $judul);
            if (!$uploadResult['success']) {
                return $uploadResult;
            }
            $namaFileTersimpan = $uploadResult['filename'];
        }

        $stmt = $this->conn->prepare("INSERT INTO sertifikat (judul, file) VALUES (?, ?)");
        if (!$stmt) {
            return [
                'success' => false,
                'message' => 'Error query insert sertifikat: ' . $this->conn->error
            ];
        }

        $stmt->bind_param("ss", $judul, $namaFileTersimpan);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Sertifikat berhasil ditambahkan.',
                'id' => $this->conn->insert_id
            ];
        } else {
            // Hapus file yang sudah terupload jika gagal insert
            if ($namaFileTersimpan) {
                $this->deleteFile($namaFileTersimpan);
            }
            return [
                'success' => false,
                'message' => 'Gagal menambahkan sertifikat: ' . $stmt->error
            ];
        }
    }

    /**
     * Update sertifikat
     */
    public function update(int $id, array $data, ?array $file = null): array
    {
        // Validasi input
        $errors = $this->validate($data, $file);
        if (!empty($errors)) {
            return [
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $errors
            ];
        }

        $sertifikatLama = $this->getById($id);
        if (!$sertifikatLama) {
            return [
                'success' => false,
                'message' => 'Sertifikat tidak ditemukan.'
            ];
        }

        $judul = trim($data['judul']);
        $namaFileTersimpan = $sertifikatLama['file'];

        // Upload file baru jika ada
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->uploadFile($file, $judul);
            if (!$uploadResult['success']) {
                return $uploadResult;
            }

            // Hapus file lama
            if (!empty($sertifikatLama['file'])) {
                $this->deleteFile($sertifikatLama['file']);
            }

            $namaFileTersimpan = $uploadResult['filename'];
        }

        $stmt = $this->conn->prepare("UPDATE sertifikat SET judul = ?, file = ? WHERE id = ?");
        if (!$stmt) {
            return [
                'success' => false,
                'message' => 'Error query update sertifikat: ' . $this->conn->error
            ];
        }

        $stmt->bind_param("ssi", $judul, $namaFileTersimpan, $id);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Sertifikat berhasil diperbarui.'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui sertifikat: ' . $stmt->error
            ];
        }
    }

    /**
     * Hapus sertifikat
     */
    public function delete(int $id): array
    {
        $sertifikat = $this->getById($id);
        if (!$sertifikat) {
            return [
                'success' => false,
                'message' => 'Sertifikat tidak ditemukan.'
            ];
        }

        $stmt = $this->conn->prepare("DELETE FROM sertifikat WHERE id = ?");
        if (!$stmt) {
            return [
                'success' => false,
                'message' => 'Error query delete sertifikat: ' . $this->conn->error
            ];
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Hapus file fisik jika ada
            if (!empty($sertifikat['file'])) {
                $this->deleteFile($sertifikat['file']);
            }

            return [
                'success' => true,
                'message' => 'Sertifikat berhasil dihapus.'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menghapus sertifikat: ' . $stmt->error
            ];
        }
    }

    /**
     * Ambil sertifikat by ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM sertifikat WHERE id = ?");
        if (!$stmt) {
            die("Error query get sertifikat by ID: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    /**
     * Ambil semua sertifikat (untuk client-side DataTables rendering)
     */
    public function getAll(): array
    {
        $result = $this->conn->query("SELECT * FROM sertifikat ORDER BY id DESC");
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }
}
