<?php
require_once 'dbconnect.php';


class tests
{
    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    public function hasUserTakenTest(
        int $userId,
        int $kuisId,
        string $jenis
    ): bool {

        $sql = "SELECT id
            FROM quiz_results
            WHERE user_id = ?
              AND kuis_id = ?
              AND jenis = ?
            LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("iis", $userId, $kuisId, $jenis);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function getQuizByJenis(string $jenis): ?array
    {
        $sql = "SELECT id, material_id, judul, passing_grade, jenis
                FROM quizzes
                WHERE jenis = ?
                AND material_id IS NULL
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("s", $jenis);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc();
    }

    public function getQuestionsByQuiz(int $kuisId): array
    {
        $sql = "SELECT 
                id,
                kuis_id,
                pertanyaan,
                opsi_a,
                opsi_b,
                opsi_c,
                opsi_d
            FROM quiz_questions
            WHERE kuis_id = ?
            ORDER BY id ASC";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("i", $kuisId);
        $stmt->execute();

        $result = $stmt->get_result();

        $questions = [];

        while ($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }

        return $questions;
    }


    public function getTestQuestion(): array|null
    {
        $result = $this->conn->query("SELECT * FROM tests LIMIT 1");
        // $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        $testData = $result->fetch_assoc();
        $testId = $testData['id'];

        // ambil soal berdasarkan test_id
        $stmtQuestions = $this->conn->prepare("SELECT id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d 
                         FROM test_questions WHERE test_id = ?");

        $stmtQuestions->bind_param("i", $testId);
        $stmtQuestions->execute();

        $resultQuestions = $stmtQuestions->get_result();
        $questions = [];

        while ($row = $resultQuestions->fetch_assoc()) {
            $questions[] = $row;
        }

        return [
            'test' => $testData,
            'questions' => $questions
        ];
    }

    public function calculateScore(int $userId, int $testId, string $tipeSesi, array $userAnswer): array
    {


        $stmt = $this->conn->prepare("SELECT id, jawaban_benar FROM test_questions WHERE test_id = ?");
        $stmt->bind_param("i", $testId);
        $stmt->execute();
        $result = $stmt->get_result();

        $totalSoal = $result->num_rows;
        $jawaban_benar = 0;

        if ($totalSoal === 0) {
            return [
                'score' => 0,
                'banar' => 0,
                'total' => 0,
            ];
        }

        // pencocokan jawaban
        while ($row = $result->fetch_assoc()) {
            $questionId = $row['id'];
            $correctAnswer = strtolower($row['jawaban_benar']);

            if (isset($userAnswer[$questionId]) && $userAnswer[$questionId] === $correctAnswer) {
                $jawaban_benar++;
            }
        }

        // perhitungan nilai
        $jawaban_salah = $totalSoal - $jawaban_benar;
        $score = round(($jawaban_benar / $totalSoal) * 100);

        $sudahDikerjakan = $this->hasUserTakenTest($userId, $testId, $tipeSesi);


        // Simpan hasil skor ke database
        if (!$sudahDikerjakan) {
            $stmtInsert = $this->conn->prepare("INSERT INTO test_result (user_id, test_id, tipe_sesi, nilai, jumlah_benar, jumlah_salah) VALUES (?, ?, ?, ?, ?, ?)");
            if (!$stmtInsert) {
                die("Error pada query: " . $this->conn->error);
            }
            $stmtInsert->bind_param("iisiii", $userId, $testId, $tipeSesi, $score, $jawaban_benar, $jawaban_salah);
            $stmtInsert->execute();
        }

        return [
            'score' => (int)$score,
            'benar' => $jawaban_benar,
            'salah' => $jawaban_salah,
            'total' => $totalSoal,
            'sudah_dikerjakan' => $sudahDikerjakan
        ];
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

        $result = $stmt->get_result();

        return $result->num_rows > 0;
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

    public function getBestResult(int $userId, int $quizId): ?array
    {
        $sql = "
        SELECT *
        FROM quiz_results
        WHERE user_id = ?
        AND kuis_id = ?
        ORDER BY nilai DESC, percobaan ASC
        LIMIT 1
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $quizId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
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

    // untuk dashboard

    public function getStatistic(int $userId, int $quizId): array
    {
        $sql = "
        SELECT
            COUNT(*) AS total_attempt,
            MAX(nilai) AS highest_score,
            AVG(nilai) AS average_score
        FROM quiz_results
        WHERE user_id = ?
        AND kuis_id = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $quizId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }
}
