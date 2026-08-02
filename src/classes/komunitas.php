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
}
