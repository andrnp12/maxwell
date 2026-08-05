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

    public function getLastInsertId(): int
    {
        return $this->conn->insert_id;
    }

    private function getNextNoUrut(): int
    {
        $result = $this->conn->query("SELECT MAX(no_urut) AS max_urut FROM materials");
        $row = $result->fetch_assoc();

        return ($row['max_urut'] !== null) ? (int)$row['max_urut'] + 1 : 1;
    }

    private function shiftNoUrut(int $noUrut, ?int $excludeId = null): void
    {
        // Shift all materials with no_urut >= $noUrut up by 1
        // Exclude the current material being edited (if any)
        $sql = "UPDATE materials SET no_urut = no_urut + 1 WHERE no_urut >= ?";
        $params = [$noUrut];
        $types = "i";
        
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
            $types .= "i";
        }
        
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
        }
    }

    public function addMateri(string $judul, string $deskripsi, string $videoUrl, ?int $noUrut, ?array $file): array
    {
        if (empty($noUrut) || $noUrut <= 0) {
            $noUrut = $this->getNextNoUrut();
        } else {
            // If no_urut is specified, shift existing materials to make room
            $this->shiftNoUrut($noUrut);
        }

        $namaFileTersimpan = null;

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../uploads/';

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
        // 1. Ambil nama file dan no_urut terlebih dahulu
        $fileName = null;
        $deletedNoUrut = null;
        $stmtSelect = $this->conn->prepare("SELECT `file`, `no_urut` FROM materials WHERE id = ?");
        if ($stmtSelect) {
            $stmtSelect->bind_param("i", $id);
            $stmtSelect->execute();
            $result = $stmtSelect->get_result();

            if ($row = $result->fetch_assoc()) {
                $fileName = $row['file'];
                $deletedNoUrut = $row['no_urut'];
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

            // Shift down materials with no_urut > deletedNoUrut to fill the gap
            if ($deletedNoUrut !== null) {
                $this->shiftNoUrutRange($deletedNoUrut + 1, PHP_INT_MAX, null, -1);
            }

            // JIKA berhasil, BARU kita hapus file fisiknya
            if ($fileName) {
                $filePath = __DIR__ . '/../../uploads/' . $fileName;

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
            $uploadDir = __DIR__ . '/../../uploads/';

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

        // Handle no_urut shifting if it's being changed
        $oldNoUrut = $materiLama['no_urut'];
        if ($noUrut !== null && $noUrut > 0 && $noUrut !== $oldNoUrut) {
            // If moving to a lower number, shift materials from new position up
            // If moving to a higher number, shift materials from old position down
            if ($noUrut < $oldNoUrut) {
                // Moving up: shift materials in range [new_no_urut, old_no_urut - 1] up by 1
                $this->shiftNoUrutRange($noUrut, $oldNoUrut - 1, $id, 1);
            } else {
                // Moving down: shift materials in range [old_no_urut + 1, new_no_urut] down by 1
                $this->shiftNoUrutRange($oldNoUrut + 1, $noUrut, $id, -1);
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

    private function shiftNoUrutRange(int $start, int $end, ?int $excludeId = null, int $direction = 1): void
    {
        // Shift materials in range [start, end] by direction (1 = up, -1 = down)
        $sql = "UPDATE materials SET no_urut = no_urut + ? WHERE no_urut BETWEEN ? AND ?";
        $params = [$direction, $start, $end];
        $types = "iii";
        
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
            $types .= "i";
        }
        
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
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

    // Tambahkan parameter $current_material_id (opsional, diset null jika untuk form tambah)
    public function getMateriNonKuis($current_material_id = null): array
    {
        if ($current_material_id) {
            // Jika sedang edit, ambil materi yang kosong ATAU materi yang sedang dipakai kuis ini
            $stmt = $this->conn->prepare("
                SELECT m.*
                FROM materials m
                LEFT JOIN quizzes k ON m.id = k.material_id
                WHERE k.material_id IS NULL OR m.id = ?
            ");
            $stmt->bind_param("i", $current_material_id);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            // Jika sedang tambah data baru (tidak ada ID), pakai query lama Anda
            $result = $this->conn->query("
            SELECT m.* 
            FROM materials m
            LEFT JOIN quizzes k ON m.id = k.material_id
            WHERE k.material_id IS NULL
        ");
        }

        $MateriPilihan = [];
        while ($row = $result->fetch_assoc()) {
            $MateriPilihan[] = $row;
        }

        return $MateriPilihan;
    }
}