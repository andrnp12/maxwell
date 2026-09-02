<?php
require_once 'dbconnect.php';

class Kuis
{
    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    public function addKuis(?int $id_materi, string $judul, int $passingGrade, string $jenis_kuis = 'kuis'): array
    {
        // For pretest/posttest, materi is optional
        if (in_array($jenis_kuis, ['pretest', 'posttest']) && ($id_materi === null || $id_materi === 0)) {
            $stmt = $this->conn->prepare("INSERT INTO quizzes (material_id, judul, passing_grade, jenis) VALUES (NULL, ?, ?, ?)");
            if (!$stmt) {
                return ['status' => 'error', 'message' => 'Error prepare: ' . $this->conn->error];
            }
            $stmt->bind_param("sis", $judul, $passingGrade, $jenis_kuis);
        } else {
            $stmt = $this->conn->prepare("INSERT INTO quizzes (material_id, judul, passing_grade, jenis) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                return ['status' => 'error', 'message' => 'Error prepare: ' . $this->conn->error];
            }
            $stmt->bind_param("isis", $id_materi, $judul, $passingGrade, $jenis_kuis);
        }

        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            echo json_encode([
                'status' => 'success',
                'message' => 'Kuis berhasil ditambahkan.',
                'id' => $id
            ]);
            exit;
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menambahkan kuis.'
            ]);
            exit;
        }
    }

    public function updateKuis(int $id, ?int $id_materi, string $judul, int $passingGrade, string $jenis_kuis = 'kuis'): array
    {
        // For pretest/posttest, materi is optional
        if (in_array($jenis_kuis, ['pretest', 'posttest']) && ($id_materi === null || $id_materi === 0)) {
            $stmt = $this->conn->prepare("UPDATE quizzes SET material_id = NULL, judul = ?, passing_grade = ?, jenis = ? WHERE id = ?");
            if (!$stmt) {
                return ['status' => 'error', 'message' => 'Error prepare: ' . $this->conn->error];
            }
            $stmt->bind_param("sisi", $judul, $passingGrade, $jenis_kuis, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE quizzes SET material_id = ?, judul = ?, passing_grade = ?, jenis = ? WHERE id = ?");
            if (!$stmt) {
                return ['status' => 'error', 'message' => 'Error prepare: ' . $this->conn->error];
            }
            $stmt->bind_param("isisi", $id_materi, $judul, $passingGrade, $jenis_kuis, $id);
        }

        if ($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Kuis berhasil diperbarui.'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Kuis gagal diperbarui: ' . $stmt->error
            ];
        };
    }

    public function deleteKuis(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM quizzes WHERE id = ?");
        if (!$stmt) {
            die("Error pada query: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function getKuisById(int $id): array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM quizzes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function getAllKuis(): array
    {
        $result = $this->conn->query("SELECT 
        quizzes.id AS id_kuis,
        quizzes.material_id, 
        quizzes.judul AS judul_kuis,
        materials.judul AS judul_materi,
        quizzes.passing_grade,
        quizzes.jenis
    FROM quizzes
    LEFT JOIN materials 
        ON quizzes.material_id = materials.id;");

        $kuisList = [];

        while ($row = $result->fetch_assoc()) {
            $kuisList[] = $row;
        }

        return $kuisList;
    }

    /**
     * Get all quiz-type quizzes only (jenis = 'kuis')
     * Excludes pretest and posttest
     */
    public function getAllKuisOnly(): array
    {
        $result = $this->conn->query("SELECT 
        quizzes.id AS id_kuis,
        quizzes.material_id, 
        quizzes.judul AS judul_kuis,
        materials.judul AS judul_materi,
        quizzes.passing_grade,
        quizzes.jenis
    FROM quizzes
    LEFT JOIN materials 
        ON quizzes.material_id = materials.id
    WHERE quizzes.jenis = 'kuis';");

        $kuisList = [];

        while ($row = $result->fetch_assoc()) {
            $kuisList[] = $row;
        }

        return $kuisList;
    }

    // user
    public function getAllKuisUser(int $userId): array
    {
        $sql = "
        SELECT
            q.id AS id_kuis,
            q.material_id,
            q.judul AS judul_kuis,
            q.passing_grade,
            q.jenis,

            m.judul AS judul_materi,

            COALESCE(up.material_selesai,0) AS material_selesai,
            COALESCE(up.quizz_selesai,0) AS quizz_selesai,

            qr.nilai,
            qr.lulus,
            qr.percobaan

        FROM quizzes q

        INNER JOIN materials m
            ON m.id = q.material_id

        LEFT JOIN user_progress up
            ON up.material_id = q.material_id
            AND up.user_id = ?

        LEFT JOIN (
            SELECT user_id, kuis_id, nilai, lulus, percobaan
            FROM quiz_results
            WHERE jenis = 'kuis'
            AND percobaan = (
                SELECT MAX(percobaan)
                FROM quiz_results qr2
                WHERE qr2.user_id = quiz_results.user_id
                AND qr2.kuis_id = quiz_results.kuis_id
                AND qr2.jenis = quiz_results.jenis
            )
        ) qr
            ON qr.user_id = ?
            AND qr.kuis_id = q.id

        WHERE q.jenis = 'kuis'

        ORDER BY m.no_urut ASC
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }
}
