<?php

require_once 'dbconnect.php';

class Informasi
{
    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }


    // =========================================================
    // KATEGORI
    // =========================================================

    public function getAllKategori(): array
    {
        $sql = "
            SELECT id, judul_kategori
            FROM categories
            ORDER BY id DESC
        ";

        $result = $this->conn->query($sql);

        $kategoriList = [];

        if ($result) {

            while ($row = $result->fetch_assoc()) {
                $kategoriList[] = $row;
            }
        }

        return $kategoriList;
    }


    public function getKategoriById(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $sql = "
            SELECT id, judul_kategori
            FROM categories
            WHERE id = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc();
    }


    public function addKategori(string $judul_kategori): array
    {
        $judul_kategori = trim($judul_kategori);

        if ($judul_kategori === '') {

            return [
                'status' => 'error',
                'message' => 'judul_kategori kategori tidak boleh kosong.'
            ];
        }

        $sql = "
            INSERT INTO categories (judul_kategori)
            VALUES (?)
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {

            return [
                'status' => 'error',
                'message' => 'Gagal menyiapkan query: ' .
                    $this->conn->error
            ];
        }

        $stmt->bind_param("s", $judul_kategori);

        if ($stmt->execute()) {

            return [
                'status' => 'success',
                'message' => 'Kategori berhasil ditambahkan.',
                'id' => $this->conn->insert_id,
                'judul_kategori' => $judul_kategori
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Kategori gagal ditambahkan: ' .
                $stmt->error
        ];
    }


    public function updateKategori(
        int $id,
        string $judul_kategori
    ): array {

        $judul_kategori = trim($judul_kategori);

        if ($id <= 0) {

            return [
                'status' => 'error',
                'message' => 'ID kategori tidak valid.'
            ];
        }

        if ($judul_kategori === '') {

            return [
                'status' => 'error',
                'message' => 'judul_kategori kategori tidak boleh kosong.'
            ];
        }

        $sql = "
            UPDATE categories
            SET judul_kategori = ?
            WHERE id = ?
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {

            return [
                'status' => 'error',
                'message' => 'Gagal menyiapkan query: ' .
                    $this->conn->error
            ];
        }

        $stmt->bind_param("si", $judul_kategori, $id);

        if (!$stmt->execute()) {

            return [
                'status' => 'error',
                'message' => 'Kategori gagal diperbarui: ' .
                    $stmt->error
            ];
        }

        // Pastikan ID memang ada
        if ($stmt->affected_rows === 0) {

            $check = $this->conn->prepare("
                SELECT id
                FROM categories
                WHERE id = ?
            ");

            $check->bind_param("i", $id);
            $check->execute();

            $result = $check->get_result();

            if ($result->num_rows === 0) {

                return [
                    'status' => 'error',
                    'message' => 'Kategori tidak ditemukan.'
                ];
            }
        }

        return [
            'status' => 'success',
            'message' => 'Kategori berhasil diperbarui.',
            'id' => $id,
            'judul_kategori' => $judul_kategori
        ];
    }


    public function deleteKategori(int $id): array
    {
        if ($id <= 0) {

            return [
                'status' => 'error',
                'message' => 'ID kategori tidak valid.'
            ];
        }

        $sql = "
            DELETE FROM categories
            WHERE id = ?
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {

            return [
                'status' => 'error',
                'message' => 'Gagal menyiapkan query: ' .
                    $this->conn->error
            ];
        }

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {

            return [
                'status' => 'error',
                'message' => 'Kategori gagal dihapus: ' .
                    $stmt->error
            ];
        }

        if ($stmt->affected_rows === 0) {

            return [
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan.'
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Kategori berhasil dihapus.',
            'id' => $id
        ];
    }


    // =========================================================
    // CONTENTS
    // =========================================================

    public function getContentsByKategori(
        int $categoryId
    ): array {

        if ($categoryId <= 0) {
            return [];
        }

        $sql = "
            SELECT
                id,
                category_id,
                judul,
                foto,
                deskripsi,
                created_at,
                updated_at
            FROM contents
            WHERE category_id = ?
            ORDER BY id DESC
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $categoryId);
        $stmt->execute();

        $result = $stmt->get_result();

        $contents = [];

        while ($row = $result->fetch_assoc()) {
            $contents[] = $row;
        }

        return $contents;
    }


    public function getContentById(
        int $id
    ): ?array {

        if ($id <= 0) {
            return null;
        }

        $sql = "
            SELECT
                id,
                category_id,
                judul,
                foto,
                deskripsi,
                created_at,
                updated_at
            FROM contents
            WHERE id = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc();
    }


    // =========================================================
    // ADD CONTENT
    // =========================================================

    public function addContent(
        int $categoryId,
        string $judul,
        ?string $foto,
        string $deskripsi
    ): array {

        $judul = trim($judul);
        $deskripsi = trim($deskripsi);

        if ($categoryId <= 0) {

            return [
                'status' => 'error',
                'message' => 'Kategori tidak valid.'
            ];
        }

        if ($judul === '') {

            return [
                'status' => 'error',
                'message' => 'Judul informasi tidak boleh kosong.'
            ];
        }

        if ($deskripsi === '') {

            return [
                'status' => 'error',
                'message' => 'Deskripsi informasi tidak boleh kosong.'
            ];
        }


        // Pastikan kategori memang ada
        $kategori = $this->getKategoriById($categoryId);

        if (!$kategori) {

            return [
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan.'
            ];
        }


        $sql = "
            INSERT INTO contents
                (category_id, judul, foto, deskripsi)
            VALUES
                (?, ?, ?, ?)
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {

            return [
                'status' => 'error',
                'message' => 'Gagal menyiapkan query: ' .
                    $this->conn->error
            ];
        }

        $stmt->bind_param(
            "isss",
            $categoryId,
            $judul,
            $foto,
            $deskripsi
        );


        if (!$stmt->execute()) {

            return [
                'status' => 'error',
                'message' => 'Informasi gagal ditambahkan: ' .
                    $stmt->error
            ];
        }


        $id = $this->conn->insert_id;


        return [
            'status' => 'success',
            'message' => 'Informasi berhasil ditambahkan.',
            'id' => $id,
            'category_id' => $categoryId,
            'judul' => $judul,
            'foto' => $foto,
            'deskripsi' => $deskripsi
        ];
    }


    // =========================================================
    // UPDATE CONTENT + FOTO
    // =========================================================

    public function updateContent(
        int $id,
        int $categoryId,
        string $judul,
        ?string $foto,
        string $deskripsi
    ): array {

        $judul = trim($judul);
        $deskripsi = trim($deskripsi);

        if ($id <= 0) {

            return [
                'status' => 'error',
                'message' => 'ID informasi tidak valid.'
            ];
        }

        if ($categoryId <= 0) {

            return [
                'status' => 'error',
                'message' => 'Kategori tidak valid.'
            ];
        }

        if ($judul === '') {

            return [
                'status' => 'error',
                'message' => 'Judul informasi tidak boleh kosong.'
            ];
        }

        if ($deskripsi === '') {

            return [
                'status' => 'error',
                'message' => 'Deskripsi informasi tidak boleh kosong.'
            ];
        }


        // Ambil data lama
        $contentLama =
            $this->getContentById($id);

        if (!$contentLama) {

            return [
                'status' => 'error',
                'message' => 'Informasi tidak ditemukan.'
            ];
        }


        // Pastikan kategori ada
        $kategori =
            $this->getKategoriById($categoryId);

        if (!$kategori) {

            return [
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan.'
            ];
        }


        $sql = "
            UPDATE contents
            SET
                category_id = ?,
                judul = ?,
                foto = ?,
                deskripsi = ?
            WHERE id = ?
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {

            return [
                'status' => 'error',
                'message' => 'Gagal menyiapkan query: ' .
                    $this->conn->error
            ];
        }

        $stmt->bind_param(
            "isssi",
            $categoryId,
            $judul,
            $foto,
            $deskripsi,
            $id
        );


        if (!$stmt->execute()) {

            return [
                'status' => 'error',
                'message' => 'Informasi gagal diperbarui: ' .
                    $stmt->error
            ];
        }


        // Hapus foto lama setelah database berhasil
        if (
            $foto !== null &&
            !empty($contentLama['foto']) &&
            $contentLama['foto'] !== $foto
        ) {

            $oldFile =
                '../../uploads/contents/' .
                $contentLama['foto'];

            if (is_file($oldFile)) {
                @unlink($oldFile);
            }
        }


        return [
            'status' => 'success',
            'message' => 'Informasi berhasil diperbarui.',
            'id' => $id,
            'category_id' => $categoryId,
            'judul' => $judul,
            'foto' => $foto,
            'deskripsi' => $deskripsi
        ];
    }


    // =========================================================
    // UPDATE CONTENT TANPA GANTI FOTO
    // =========================================================

    public function updateContentWithoutFoto(
        int $id,
        int $categoryId,
        string $judul,
        string $deskripsi
    ): array {

        $judul = trim($judul);
        $deskripsi = trim($deskripsi);

        if ($id <= 0) {

            return [
                'status' => 'error',
                'message' => 'ID informasi tidak valid.'
            ];
        }

        if ($categoryId <= 0) {

            return [
                'status' => 'error',
                'message' => 'Kategori tidak valid.'
            ];
        }

        if ($judul === '') {

            return [
                'status' => 'error',
                'message' => 'Judul informasi tidak boleh kosong.'
            ];
        }

        if ($deskripsi === '') {

            return [
                'status' => 'error',
                'message' => 'Deskripsi informasi tidak boleh kosong.'
            ];
        }


        $contentLama =
            $this->getContentById($id);

        if (!$contentLama) {

            return [
                'status' => 'error',
                'message' => 'Informasi tidak ditemukan.'
            ];
        }


        $sql = "
            UPDATE contents
            SET
                category_id = ?,
                judul = ?,
                deskripsi = ?
            WHERE id = ?
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {

            return [
                'status' => 'error',
                'message' => 'Gagal menyiapkan query: ' .
                    $this->conn->error
            ];
        }

        $stmt->bind_param(
            "issi",
            $categoryId,
            $judul,
            $deskripsi,
            $id
        );


        if (!$stmt->execute()) {

            return [
                'status' => 'error',
                'message' => 'Informasi gagal diperbarui: ' .
                    $stmt->error
            ];
        }


        return [
            'status' => 'success',
            'message' => 'Informasi berhasil diperbarui.',
            'id' => $id,
            'category_id' => $categoryId,
            'judul' => $judul,
            'foto' => $contentLama['foto'],
            'deskripsi' => $deskripsi
        ];
    }


    // =========================================================
    // DELETE CONTENT
    // =========================================================

    public function deleteContent(
        int $id
    ): array {

        if ($id <= 0) {

            return [
                'status' => 'error',
                'message' => 'ID informasi tidak valid.'
            ];
        }


        // Ambil data terlebih dahulu
        $content =
            $this->getContentById($id);

        if (!$content) {

            return [
                'status' => 'error',
                'message' => 'Informasi tidak ditemukan.'
            ];
        }


        $sql = "
            DELETE FROM contents
            WHERE id = ?
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {

            return [
                'status' => 'error',
                'message' => 'Gagal menyiapkan query: ' .
                    $this->conn->error
            ];
        }

        $stmt->bind_param("i", $id);


        if (!$stmt->execute()) {

            return [
                'status' => 'error',
                'message' => 'Informasi gagal dihapus: ' .
                    $stmt->error
            ];
        }


        if ($stmt->affected_rows === 0) {

            return [
                'status' => 'error',
                'message' => 'Informasi tidak ditemukan.'
            ];
        }


        // Hapus file foto setelah database berhasil dihapus
        if (!empty($content['foto'])) {

            $filePath =
                '../../uploads/contents/' .
                $content['foto'];

            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }


        return [
            'status' => 'success',
            'message' => 'Informasi berhasil dihapus.',
            'id' => $id
        ];
    }
}
