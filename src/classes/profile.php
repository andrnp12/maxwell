<?php
require_once 'dbconnect.php';

class Profile
{
    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    public function getProfileById(int $id): array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        if (!$stmt) {
            die("Error query get User by ID: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function updateProfile(
        int $id,
        ?array $file,
        string $username,
        string $nama,
        string $nomor,
        string $email,
        string $password,
        string $no_kk,
        string $nik,
        string $alamat,
        int $usia,
        string $pendidikan,
        string $status,
        string $ringkasan
    ): array {
        $userLama = $this->getProfileById($id);

        if (!$userLama) {
            return [
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan.'
            ];
        }

        // Gunakan foto lama jika tidak ada upload baru
        $namaFileTersimpan = $userLama['foto'] ?? null;

        if ($file && $file['error'] === UPLOAD_ERR_OK) {

            $uploadDir = __DIR__ . '/../../uploads/profile/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Hapus foto lama
            if (
                !empty($namaFileTersimpan) &&
                file_exists($uploadDir . $namaFileTersimpan)
            ) {
                unlink($uploadDir . $namaFileTersimpan);
            }

            $ekstensi = strtolower(
                pathinfo($file['name'], PATHINFO_EXTENSION)
            );

            $allowedExtensions = [
                'jpg',
                'jpeg',
                'png',
                'gif',
                'bmp',
                'webp'
            ];

            if (!in_array($ekstensi, $allowedExtensions, true)) {
                return [
                    'status' => 'error',
                    'message' => 'Hanya file gambar yang diperbolehkan.'
                ];
            }

            $judulFile = strtolower($username);
            $judulFile = preg_replace('/[^a-z0-9]/', '_', $judulFile);
            $judulFile = trim($judulFile, '_');

            $namaFileTersimpan =
                'profile_' .
                $judulFile .
                '_' .
                time() .
                '.' .
                $ekstensi;

            $tujuanUpload = $uploadDir . $namaFileTersimpan;

            if (!move_uploaded_file(
                $file['tmp_name'],
                $tujuanUpload
            )) {
                return [
                    'status' => 'error',
                    'message' => 'Gagal mengunggah file.'
                ];
            }
        }

        if (!empty($password)) {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $sql = "UPDATE users SET
                    foto = ?,
                    username = ?,
                    name = ?,
                    nomor = ?,
                    email = ?,
                    password = ?,
                    no_kk = ?,
                    nik = ?,
                    alamat = ?,
                    usia = ?,
                    pendidikan = ?,
                    status = ?,
                    deskripsi = ?
                WHERE id = ?";

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                return [
                    'status' => 'error',
                    'message' => 'Error query: ' . $this->conn->error
                ];
            }

            $stmt->bind_param(
                "sssssssssisssi",
                $namaFileTersimpan,
                $username,
                $nama,
                $nomor,
                $email,
                $hashedPassword,
                $no_kk,
                $nik,
                $alamat,
                $usia,
                $pendidikan,
                $status,
                $ringkasan,
                $id
            );
        } else {

            $sql = "UPDATE users SET
                    foto = ?,
                    username = ?,
                    name = ?,
                    nomor = ?,
                    email = ?,
                    no_kk = ?,
                    nik = ?,
                    alamat = ?,
                    usia = ?,
                    pendidikan = ?,
                    status = ?,
                    deskripsi = ?
                WHERE id = ?";

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                return [
                    'status' => 'error',
                    'message' => 'Error query: ' . $this->conn->error
                ];
            }

            $stmt->bind_param(
                "ssssssssisssi",
                $namaFileTersimpan,
                $username,
                $nama,
                $nomor,
                $email,
                $no_kk,
                $nik,
                $alamat,
                $usia,
                $pendidikan,
                $status,
                $ringkasan,
                $id
            );
        }

        if ($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Profil berhasil diperbarui.',
                'foto' => $namaFileTersimpan
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Gagal memperbarui profil: ' . $stmt->error
        ];
    }

    public function getProfile(int $id): array
    {
        $profile = $this->getProfileById($id);

        if (!$profile) {
            return [
                'status' => 'error',
                'message' => 'User tidak ditemukan.'
            ];
        }

        return [
            'status' => 'success',
            'data' => $profile
        ];
    }
}
