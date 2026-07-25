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

    public function addKuis(int $id_materi, string $judul, int $passingGrade): array
    {
        $stmt = $this->conn->prepare("INSERT INTO quizzes (material_id, judul, passing_grade) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Error pada query: " . $this->conn->error);
        }

        $stmt->bind_param("isi", $id_materi, $judul, $passingGrade);

        if ($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Kuis berhasil ditambahkan.'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Kuis gagal ditambahkan.'
            ];
        };
    }

    public function updateKuis(int $id, int $id_materi, string $judul, int $passingGrade): array
    {
        $stmt = $this->conn->prepare("UPDATE quizzes SET material_id = ?, judul = ?, passing_grade = ? WHERE id = ?");
        if (!$stmt) {
            die("Error pada query: " . $this->conn->error);
        }

        $stmt->bind_param("isii", $id_materi, $judul, $passingGrade, $id);

        if ($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Kuis berhasil diperbarui.'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Kuis gagal diperbarui.'
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
        quizzes.passing_grade
    FROM quizzes
    INNER JOIN materials 
        ON quizzes.material_id = materials.id;");

        $kuisList = [];

        while ($row = $result->fetch_assoc()) {
            $kuisList[] = $row;
        }

        return $kuisList;
    }
}
