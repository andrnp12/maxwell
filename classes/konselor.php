<?php
require_once 'dbconnect.php';

class Konsultan
{
    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    public function addKonsultan(?array $file, string $nama, string $nomor, string $email, string $ringkasan): array
    {

        $namaFileTersimpan = null;

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/profile/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            if (!in_array($ekstensi, $allowedExtensions)) {
                return [
                    'status' => 'error',
                    'message' => 'Hanya file gambar yang diperbolehkan.'
                ];
            }

            $judulFile = strtolower($nama);
            $judulFile = preg_replace('/[^a-z0-9]/', '_', $judulFile);
            $judulFile = trim($judulFile, '_');

            $namaFileTersimpan = 'foto_' . $judulFile . '_' . '.' . $ekstensi;
            $tujuanUpload = $uploadDir . $namaFileTersimpan;

            if (!move_uploaded_file($file['tmp_name'], $tujuanUpload)) {
                return [
                    'status' => 'error',
                    'message' => 'Gagal mengunggah file.'
                ];
            }
        }

        $stmt = $this->conn->prepare("INSERT INTO konsultan (`foto`, nama, nomor, email, deskripsi) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Error query insert konsultan: " . $this->conn->error);
        }
        $stmt->bind_param("sssss", $namaFileTersimpan, $nama, $nomor, $email, $ringkasan);

        if ($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Konsultan berhasil ditambahkan.',
                'id' => $stmt->insert_id,
                'foto' => $namaFileTersimpan
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Gagal menambahkan konsultan: ' . $stmt->error
            ];
        }
    }

    public function getKonsultanById(int $id): array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM konsultan WHERE id = ?");
        if (!$stmt) {
            die("Error query get konsultan by ID: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function updateKonsultan(int $id, ?array $file, string $nama, string $nomor, string $email, string $ringkasan): array
    {
        $konsultanLama = $this->getKonsultanById($id);
        if (!$konsultanLama) {
            return [
                'status' => 'error',
                'message' => 'Konsultan tidak ditemukan.'
            ];
        }

        $namaFileTersimpan = $konsultanLama['foto'];

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/profile/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (!empty($namaFileTersimpan) && file_exists($uploadDir . $namaFileTersimpan)) {
                unlink($uploadDir . $namaFileTersimpan);
            }

            $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            if (!in_array($ekstensi, $allowedExtensions)) {
                return [
                    'status' => 'error',
                    'message' => 'Hanya file gambar yang diperbolehkan.'
                ];
            }

            $judulFile = strtolower($nama);
            $judulFile = preg_replace('/[^a-z0-9]/', '_', $judulFile);
            $judulFile = trim($judulFile, '_');

            $namaFileTersimpan = 'foto_' . $judulFile . '_' . time() . '.' . $ekstensi;
            $tujuanUpload = $uploadDir . $namaFileTersimpan;

            if (!move_uploaded_file($file['tmp_name'], $tujuanUpload)) {
                return [
                    'status' => 'error',
                    'message' => 'Gagal mengunggah file.'
                ];
            }
        }

        $stmt = $this->conn->prepare("UPDATE konsultan SET foto = ?, nama = ?, nomor = ?, email = ?, deskripsi = ? WHERE id = ?");
        if (!$stmt) {
            die("Error query update konsultan: " . $this->conn->error);
        }
        $stmt->bind_param("ssissi", $namaFileTersimpan, $nama, $nomor, $email, $ringkasan, $id);

        if ($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Konsultan berhasil diperbarui.',
                'foto' => $namaFileTersimpan
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Gagal memperbarui konsultan: ' . $stmt->error
        ];
    }

    public function deleteKonsultan(int $id): array
    {
        $konsultanLama = $this->getKonsultanById($id);
        if (!$konsultanLama) {
            return [
                'status' => 'error',
                'message' => 'Konsultan tidak ditemukan.'
            ];
        }

        $uploadDir = __DIR__ . '/../uploads/profile/';
        $namaFileTersimpan = $konsultanLama['foto'];

        $stmt = $this->conn->prepare("DELETE FROM konsultan WHERE id = ?");
        if (!$stmt) {
            die("Error query delete konsultan: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if (!empty($namaFileTersimpan) && file_exists($uploadDir . $namaFileTersimpan)) {
                unlink($uploadDir . $namaFileTersimpan);
            }

            return [
                'status' => 'success',
                'message' => 'Konsultan berhasil dihapus.'
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Gagal menghapus konsultan: ' . $stmt->error
        ];
    }

    public function getAllKonsultan(): array
    {
        $result = $this->conn->query("SELECT * FROM konsultan ORDER BY id ASC");
        $konsultanList = [];

        while ($row = $result->fetch_assoc()) {
            $konsultanList[] = $row;
        }

        return $konsultanList;
    }
}
