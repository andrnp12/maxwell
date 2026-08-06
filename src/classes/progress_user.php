<?php

require_once 'dbconnect.php';

class ProgressUser
{

    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    public function getProgress(int $userId, int $materialId): ?array
    {
        $sql = "
            SELECT *
            FROM user_progress
            WHERE user_id = ?
            AND material_id = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $materialId);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    /**
     * Menandai materi selesai dipelajari
     */
    public function finishMaterial(int $userId, int $materialId): bool
    {
        $progress = $this->getProgress($userId, $materialId);

        if ($progress) {

            $sql = "
            UPDATE user_progress
            SET material_selesai = 1
            WHERE user_id = ?
            AND material_id = ?
        ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $materialId);

            $result = $stmt->execute();
        } else {

            $sql = "
            INSERT INTO user_progress
            (
                user_id,
                material_id,
                material_selesai,
                quizz_selesai
            )
            VALUES
            (?, ?, 1, 0)
            ON DUPLICATE KEY UPDATE
                material_selesai = VALUES(material_selesai)
        ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $materialId);

            $result = $stmt->execute();
        }

        // Jika gagal update/insert user_progress
        if (!$result) {
            return false;
        }

        // Simpan ke materials_progress
        $sql = "
        INSERT INTO materials_progress
        (
            user_id,
            material_id,
            material_selesai
        )
        VALUES (?, ?, 1)
        ON DUPLICATE KEY UPDATE
            material_selesai = VALUES(material_selesai)
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $materialId);

        return $stmt->execute();
    }

    /**
     * Menandai kuis selesai
     */
    public function finishQuiz(int $userId, int $materialId): bool
    {
        $progress = $this->getProgress($userId, $materialId);

        if (!$progress) {
            return false;
        }

        $sql = "
            UPDATE user_progress
            SET quizz_selesai = 1
            WHERE user_id = ?
            AND material_id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $materialId);

        return $stmt->execute();
    }

    /**
     * Cek apakah materi selesai
     */
    public function isMaterialFinished(int $userId, int $materialId): bool
    {
        $progress = $this->getProgress($userId, $materialId);

        return $progress && $progress['material_selesai'] == 1;
    }

    /**
     * Cek apakah kuis selesai
     */
    public function isQuizFinished(int $userId, int $materialId): bool
    {
        $progress = $this->getProgress($userId, $materialId);

        return $progress && $progress['quizz_selesai'] == 1;
    }
}
