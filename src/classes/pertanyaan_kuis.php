<?php
require_once 'dbconnect.php';

class PertanyaanKuis
{

    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    public function tambahPertanyaanKuis(int $kuisId, string $pertanyaan, string $opsiA, string $opsiB, string $opsiC, string $opsiD, string $jawaban)
    {
        // Sesuaikan nama tabel dan kolom dengan yang ada di database Anda
        $sql = "INSERT INTO quiz_questions (kuis_id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        // "issssss" berarti: 1 integer (kuis_id), dan 6 string (sisanya)
        $stmt->bind_param("issssss", $kuisId, $pertanyaan, $opsiA, $opsiB, $opsiC, $opsiD, $jawaban);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function editPertanyaanKuis(int $id, string $pertanyaan, string $opsiA, string $opsiB, string $opsiC, string $opsiD, string $jawaban): array
    {
        $sql = "UPDATE quiz_questions SET pertanyaan = ?, opsi_a = ?, opsi_b = ?, opsi_c = ?, opsi_d = ?, jawaban = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssi", $pertanyaan, $opsiA, $opsiB, $opsiC, $opsiD, $jawaban, $id);

        if ($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Pertanyaan berhasil diperbarui.'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Gagal memperbarui pertanyaan: ' . $stmt->error
            ];
        }
    }

    public function getPertanyaanKuisbyId(int $id): array|null
    {
        $sql = "SELECT * FROM quiz_questions WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function getAllPertanyaanKuis(int $kuisId): array
    {
        $sql = "SELECT quiz_questions.* 
            FROM quiz_questions 
            INNER JOIN quizzes ON quiz_questions.kuis_id = quizzes.id 
            WHERE quizzes.id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $kuisId);
        $stmt->execute();

        $result = $stmt->get_result();
        $pertanyaanKuisList = [];

        while ($row = $result->fetch_assoc()) {
            $pertanyaanKuisList[] = $row;
        }

        return $pertanyaanKuisList;
    }

    public function deletePertanyaanKuis(int $id): array
    {
        $sql = "DELETE FROM `quiz_questions` WHERE `quiz_questions`.`id` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Pertanyaan berhasil dihapus.'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Gagal menghapus pertanyaan: ' . $stmt->error
            ];
        }
    }

    // user

    public function calculateResult(int $kuisId, array $jawabanUser): array
    {
        $soal = $this->getAllPertanyaanKuis($kuisId);

        $jumlahBenar = 0;
        $jumlahSalah = 0;

        foreach ($soal as $item) {

            $idSoal = (int)$item['id'];

            $jawaban = strtoupper(trim($jawabanUser[$idSoal] ?? ''));

            $jawabanBenar = strtoupper(trim($item['jawaban'] ?? ''));

            if ($jawaban === $jawabanBenar) {

                $jumlahBenar++;
            } else {

                $jumlahSalah++;
            }
        }

        $total = count($soal);

        $persentase = 0;

        if ($total > 0) {
            $persentase = round(($jumlahBenar / $total) * 100, 2);
        }

        return [
            'benar'      => $jumlahBenar,
            'salah'      => $jumlahSalah,
            'total'      => $total,
            'persentase' => $persentase
        ];
    }
}
