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

    public function getAllKuis(): array
    {
        $result = $this->conn->query("SELECT 
    quizzes.id AS id_kuis,
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
