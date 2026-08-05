<?php
require_once 'dbconnect.php';

class Komunitas
{
    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }


    public function getAllKomunitas(int $idLogin): array
    {
        $sql = "
        SELECT
            k.*,
            CASE
                WHEN ak.id IS NULL THEN 0
                ELSE 1
            END AS is_member
        FROM komunitas k
        LEFT JOIN anggota_komunitas ak
            ON ak.id_komunitas = k.id
           AND ak.id_user = ?
        ORDER BY k.nama_komunitas
    ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $idLogin);

        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * Get all communities for admin (without membership check)
     */
    public function getAllKomunitasAdmin(): array
    {
        $sql = "
        SELECT
            k.*
        FROM komunitas k
        ORDER BY k.nama_komunitas
    ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function joinGroup(
        int $idKomunitas,
        int $idUser
    ): array {

        try {

            // 1. cek komunitas
            $stmt = $this->conn->prepare("
            SELECT id
            FROM komunitas
            WHERE id = ?
        ");

            $stmt->bind_param("i", $idKomunitas);
            $stmt->execute();

            if ($stmt->get_result()->num_rows === 0) {

                throw new Exception("Komunitas tidak ditemukan.");
            }

            // 2. cek apakah sudah menjadi anggota
            $stmt = $this->conn->prepare("
            SELECT id
            FROM anggota_komunitas
            WHERE id_komunitas = ?
            AND id_user = ?
        ");

            $stmt->bind_param(
                "ii",
                $idKomunitas,
                $idUser
            );

            $stmt->execute();

            if ($stmt->get_result()->num_rows > 0) {

                return [
                    "status" => "success",
                    "message" => "Anda sudah menjadi anggota."
                ];
            }

            // 3. insert anggota baru
            $stmt = $this->conn->prepare("
            INSERT INTO anggota_komunitas
            (
                id_komunitas,
                id_user,
                role,
                joined_at
            )
            VALUES
            (
                ?,
                ?,
                'member',
                NOW()
            )
        ");

            $stmt->bind_param(
                "ii",
                $idKomunitas,
                $idUser
            );

            $stmt->execute();

            return [
                "status" => "success",
                "message" => "Berhasil bergabung ke komunitas."
            ];
        } catch (Throwable $e) {

            return [
                "status" => "error",
                "message" => $e->getMessage()
            ];
        }
    }

    /**
     * Create a new community
     */
    public function createKomunitas(string $namaKomunitas, string $deskripsi, string $foto): array
    {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO komunitas (nama_komunitas, deskripsi, foto)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("sss", $namaKomunitas, $deskripsi, $foto);
            $stmt->execute();

            return [
                "status" => "success",
                "message" => "Komunitas berhasil ditambahkan.",
                "id" => $this->conn->insert_id,
                "foto" => $foto
            ];
        } catch (Throwable $e) {
            return [
                "status" => "error",
                "message" => $e->getMessage()
            ];
        }
    }

    /**
     * Update community
     */
    public function updateKomunitas(int $id, string $namaKomunitas, string $deskripsi, ?string $foto = null): array
    {
        try {
            if ($foto) {
                $stmt = $this->conn->prepare("
                    UPDATE komunitas 
                    SET nama_komunitas = ?, deskripsi = ?, foto = ?
                    WHERE id = ?
                ");
                $stmt->bind_param("sssi", $namaKomunitas, $deskripsi, $foto, $id);
            } else {
                $stmt = $this->conn->prepare("
                    UPDATE komunitas 
                    SET nama_komunitas = ?, deskripsi = ?
                    WHERE id = ?
                ");
                $stmt->bind_param("ssi", $namaKomunitas, $deskripsi, $id);
            }
            $stmt->execute();

            return [
                "status" => "success",
                "message" => "Komunitas berhasil diperbarui.",
                "foto" => $foto
            ];
        } catch (Throwable $e) {
            return [
                "status" => "error",
                "message" => $e->getMessage()
            ];
        }
    }

    /**
     * Delete community
     */
    public function deleteKomunitas(int $id): array
    {
        try {
            // First check if community exists
            $stmt = $this->conn->prepare("SELECT id, foto FROM komunitas WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return [
                    "status" => "error",
                    "message" => "Komunitas tidak ditemukan."
                ];
            }
            
            $komunitas = $result->fetch_assoc();
            
            // Delete community (cascade will handle anggota_komunitas if FK is set)
            $stmt = $this->conn->prepare("DELETE FROM komunitas WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            return [
                "status" => "success",
                "message" => "Komunitas berhasil dihapus.",
                "foto" => $komunitas['foto']
            ];
        } catch (Throwable $e) {
            return [
                "status" => "error",
                "message" => $e->getMessage()
            ];
        }
    }
}
