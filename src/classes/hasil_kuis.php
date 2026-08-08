<?php

require_once 'dbconnect.php';

class HasilKuis
{
    public dbconnect $db;
    public mysqli $conn;
    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    public function getAttempt(
        int $userId,
        int $quizId,
        string $jenis
    ): int {
        $sql = "
        SELECT COALESCE(MAX(percobaan), 0) AS percobaan
        FROM quiz_results
        WHERE user_id = ?
        AND kuis_id = ?
        AND jenis = ?
    ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "iis",
            $userId,
            $quizId,
            $jenis
        );

        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return ((int) $result['percobaan']) + 1;
    }

    public function saveResult(
        int $userId,
        int $quizId,
        int $jumlahBenar,
        int $jumlahSalah,
        float $nilai,
        bool $lulus,
        string $jenis = 'kuis'
    ): int|false {

        $attempt = $this->getAttempt(
            $userId,
            $quizId,
            $jenis
        );

        $sql = "
        INSERT INTO quiz_results
        (
            user_id,
            kuis_id,
            jumlah_benar,
            jumlah_salah,
            nilai,
            percobaan,
            lulus,
            jenis
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?)
    ";

        $stmt = $this->conn->prepare($sql);

        $lulus = $lulus ? 1 : 0;

        $stmt->bind_param(
            "iiiidiis",
            $userId,
            $quizId,
            $jumlahBenar,
            $jumlahSalah,
            $nilai,
            $attempt,
            $lulus,
            $jenis
        );

        if ($stmt->execute()) {
            return (int) $this->conn->insert_id;
        }

        return false;
    }

    public function getResultById(int $resultId, int $userId): ?array
    {
        $sql = "
        SELECT
            qr.*,
            q.judul,
            q.passing_grade,
            q.material_id
        FROM quiz_results qr
        INNER JOIN quizzes q
            ON q.id = qr.kuis_id
        WHERE qr.id = ?
        AND qr.user_id = ?
        LIMIT 1
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $resultId, $userId);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc();
    }

    public function getLastResult(int $userId, int $quizId): ?array
    {
        $sql = "
        SELECT *
        FROM quiz_results
        WHERE user_id = ?
        AND kuis_id = ?
        ORDER BY percobaan DESC
        LIMIT 1
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $quizId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getHighestScore(int $userId, int $quizId): float
    {
        $sql = "
        SELECT MAX(nilai) AS nilai
        FROM quiz_results
        WHERE user_id = ?
        AND kuis_id = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $quizId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return (float)$result['nilai'];
    }

    public function hasPassed(int $userId, int $quizId): bool
    {
        $sql = "
        SELECT id
        FROM quiz_results
        WHERE user_id = ?
        AND kuis_id = ?
        AND lulus = 1
        LIMIT 1
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $quizId);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    public function getHistory(int $userId, int $quizId): array
    {
        $sql = "
        SELECT *
        FROM quiz_results
        WHERE user_id = ?
        AND kuis_id = ?
        ORDER BY percobaan ASC
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $quizId);
        $stmt->execute();

        $result = $stmt->get_result();

        $history = [];

        while ($row = $result->fetch_assoc()) {
            $history[] = $row;
        }

        return $history;
    }
}
