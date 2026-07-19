<?php
require_once 'dbconnect.php';

class Materi
{
    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    private function getNextNoUrut(): int
    {
        $result = $this->conn->query("SELECT MAX(no_urut) AS max_urut FROM materials");
        $row = $result->fetch_assoc();

        return ($row['max_urut'] !== null) ? (int)$row['max_urut'] + 1 : 1;
    }

    public function addMateri(string $judul, string $deskripsi, string $videoUrl, ?int $noUrut, ?array $file): array
    {
        if (empty($noUrut) || $noUrut <= 0) {
            $noUrut = $this->getNextNoUrut();
        } else {
        }

        $namaFileTersimpan = null;

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            $allowedExtensions = ['pdf'];
            if (!in_array($ekstensi, $allowedExtensions)) {
                return [
                    'status' => 'error',
                    'message' => 'Hanya file PDF yang diperbolehkan.'
                ];
            }

            $judulFile = strtolower($judul);
            $judulFile = preg_replace('/[^a-z0-9]/', '_', $judulFile);
            $judulFile = trim($judulFile, '_');

            $namaFileTersimpan = 'materi_' . $judulFile . '_' . '.' . $ekstensi;
            $tujuanUpload = $uploadDir . $namaFileTersimpan;

            if (!move_uploaded_file($file['tmp_name'], $tujuanUpload)) {
                return [
                    'status' => 'error',
                    'message' => 'Gagal mengunggah file.'
                ];
            }
        }

        $stmt = $this->conn->prepare("INSERT INTO materials (judul, deskripsi, `file`, video_url, no_urut) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Error query insert materi: " . $this->conn->error);
        }
        $stmt->bind_param("ssssi", $judul, $deskripsi, $namaFileTersimpan, $videoUrl, $noUrut);

        if ($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Materi berhasil ditambahkan.'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Gagal menambahkan materi: ' . $stmt->error
            ];
        }
    }

    public function deleteMateri(int $id): array
    {
        // 1. Ambil nama file terlebih dahulu dan simpan di variabel
        $fileName = null;
        $stmtSelect = $this->conn->prepare("SELECT `file` FROM materials WHERE id = ?");
        if ($stmtSelect) {
            $stmtSelect->bind_param("i", $id);
            $stmtSelect->execute();
            $result = $stmtSelect->get_result();

            if ($row = $result->fetch_assoc()) {
                $fileName = $row['file'];
            }
            $stmtSelect->close(); // Tutup statement setelah selesai
        }

        // 2. Siapkan dan eksekusi query hapus DATABASE
        $stmt = $this->conn->prepare("DELETE FROM materials WHERE id = ?");
        if (!$stmt) {
            die("Error query delete materi: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);

        // 3. Cek apakah penghapusan database berhasil
        if ($stmt->execute()) {

            // JIKA berhasil, BARU kita hapus file fisiknya
            if ($fileName) {
                $filePath = __DIR__ . '/../uploads/' . $fileName;

                // Tambahkan is_file() untuk memastikan itu benar-benar file, bukan direktori
                if (file_exists($filePath) && is_file($filePath)) {
                    unlink($filePath);
                }
            }

            return [
                'status' => 'success',
                'message' => 'Materi berhasil dihapus.'
            ];
        } else {
            // Jika database gagal dihapus, file fisik tetap aman
            return [
                'status' => 'error',
                'message' => 'Gagal menghapus materi: ' . $stmt->error
            ];
        }
    }

    public function getMateriById(int $id): array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM materials WHERE id = ?");
        if (!$stmt) {
            die("Error query get materi by ID: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function updateMateri(int $id, string $judul, string $deskripsi, ?string $videoUrl, ?int $noUrut, ?array $file): array
    {
        $materiLama = $this->getMateriById($id);
        if (!$materiLama) {
            return [
                'status' => 'error',
                'message' => 'Materi tidak ditemukan.'
            ];
        }

        $namaFileTersimpan = $materiLama['file'];

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';

            if (!empty($materiLama['file']) && file_exists($uploadDir . $materiLama['file'])) {
                unlink($uploadDir . $materiLama['file']);
            }

            $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['pdf'];

            if (!in_array($ekstensi, $allowedExtensions)) {
                return [
                    'status' => 'error',
                    'message' => 'Hanya file PDF yang diperbolehkan.'
                ];
            }

            $judulFile = strtolower($judul);
            $judulFile = preg_replace('/[^a-z0-9]/', '_', $judulFile);
            $judulFile = trim($judulFile, '_');

            $namaFileTersimpan = 'Materi_' . $judulFile . '_' . time() . '.' . $ekstensi;
            if (!move_uploaded_file($file['tmp_name'], $uploadDir . $namaFileTersimpan)) {
                return [
                    'status' => 'error',
                    'message' => 'Gagal mengunggah file.'
                ];
            }
        }

        $stmt = $this->conn->prepare("UPDATE materials SET judul = ?, deskripsi = ?, `file` = ?, video_url = ?, no_urut = ? WHERE id = ?");
        $stmt->bind_param("ssssii", $judul, $deskripsi, $namaFileTersimpan, $videoUrl, $noUrut, $id);

        if ($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Materi berhasil diperbarui.'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Gagal memperbarui materi: ' . $stmt->error
            ];
        }
    }

    public function getAllMateri(): array
    {
        $result = $this->conn->query("SELECT * FROM materials ORDER BY no_urut ASC");
        $materiList = [];

        while ($row = $result->fetch_assoc()) {
            $materiList[] = $row;
        }

        return $materiList;
    }
}
