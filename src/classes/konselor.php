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

    public function addKonsultan(?array $file, string $username, string $nama, string $nomor, string $email, string $ringkasan, string $password): array
    {
        $namaFileTersimpan = null;

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../uploads/profile/';

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

            $namaFileTersimpan = 'foto_' . $judulFile . '_' . time() . '.' . $ekstensi;
            $tujuanUpload = $uploadDir . $namaFileTersimpan;

            if (!move_uploaded_file($file['tmp_name'], $tujuanUpload)) {
                return [
                    'status' => 'error',
                    'message' => 'Gagal mengunggah file.'
                ];
            }
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO users (`foto`, `username`, `name`, `nomor`, `email`, `deskripsi`, `password`, `role`) VALUES (?, ?, ?, ?, ?, ?, ?, 'konsultan')");
        if (!$stmt) {
            die("Error query insert konsultan: " . $this->conn->error);
        }
        $stmt->bind_param("sssssss", $namaFileTersimpan, $username, $nama, $nomor, $email, $ringkasan, $hashedPassword);

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
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'konsultan'");
        if (!$stmt) {
            die("Error query get konsultan by ID: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function updateKonsultan(int $id, ?array $file, string $username, string $nama, string $nomor, string $email, string $ringkasan, string $password = ''): array
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

        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE users SET foto = ?, username = ?, `name` = ?, nomor = ?, email = ?, deskripsi = ?, password = ? WHERE id = ? AND role = 'konsultan'");
            if (!$stmt) {
                die("Error query update konsultan: " . $this->conn->error);
            }
            $stmt->bind_param("sssssssi", $namaFileTersimpan, $username, $nama, $nomor, $email, $ringkasan, $hashedPassword, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET foto = ?, username = ?, `name` = ?, nomor = ?, email = ?, deskripsi = ? WHERE id = ? AND role = 'konsultan'");
            if (!$stmt) {
                die("Error query update konsultan: " . $this->conn->error);
            }
            $stmt->bind_param("ssssssi", $namaFileTersimpan, $username, $nama, $nomor, $email, $ringkasan, $id);
        }

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

        $uploadDir = __DIR__ . '/../../uploads/profile/';
        $namaFileTersimpan = $konsultanLama['foto'];

        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ? AND role = 'konsultan'");
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
        $result = $this->conn->query("SELECT id, `username`, `name`, foto, nomor, email, deskripsi FROM users WHERE role = 'konsultan'");
        $konsultanList = [];

        while ($row = $result->fetch_assoc()) {
            $konsultanList[] = $row;
        }

        return $konsultanList;
    }

    public function totalPesan(int $idKonsultan): int
    {
        $sql = "SELECT COUNT(*) AS total FROM chat_konsultan WHERE id_konselor = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idKonsultan);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function totalKonsultasi(int $idKonsultan): int
    {
        $sql = "SELECT COUNT(DISTINCT sender_id) AS total FROM chat_konsultan WHERE sender_id != ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idKonsultan);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // mengambil 3 data teratas dari konsultan
    public function getTopKonsultan(): array
    {
        $result = $this->conn->query("SELECT id, `username`, `name`, foto, nomor, email, deskripsi FROM users WHERE role = 'konsultan' LIMIT 3");
        $konsultanList = [];

        while ($row = $result->fetch_assoc()) {
            $konsultanList[] = $row;
        }

        return $konsultanList;
    }
}
