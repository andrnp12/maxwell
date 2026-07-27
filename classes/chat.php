<?php

require_once 'dbconnect.php';

class chat
{
    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    public function getAllMessage(int $id_user, int $id_konsultan): array
    {
        $sql = "SELECT * FROM chat_konsultan WHERE id_user = '$id_user' AND id_konselor = '$id_konsultan' ORDER BY time_stamp ASC";
        $result = $this->conn->query($sql);

        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        return $messages;
    }

    public function getListChat(int $id_login, string $role): array
    {
        // Logika Query: Mengambil pesan terakhir dari setiap lawan bicara
        // Asumsi nama tabel user gabungan adalah 'users' dan kolom namanya 'nama_lengkap'
        if ($role == 'user') {
            $sql = "SELECT u.id AS id_lawan, u.username AS nama, c.chat AS pesan_terakhir, c.time_stamp 
                FROM chat_konsultan c
                JOIN users u ON c.id_konselor = u.id
                WHERE c.id IN (
                    SELECT MAX(id) FROM chat_konsultan 
                    WHERE id_user = '$id_login' GROUP BY id_konselor
                ) ORDER BY c.time_stamp DESC";
        } else {
            $sql = "SELECT u.id AS id_lawan, u.username AS nama, c.chat AS pesan_terakhir, c.time_stamp 
                FROM chat_konsultan c
                JOIN users u ON c.id_user = u.id
                WHERE c.id IN (
                    SELECT MAX(id) FROM chat_konsultan 
                    WHERE id_konselor = '$id_login' GROUP BY id_user
                ) ORDER BY c.time_stamp DESC";
        }

        $result = $this->conn->query($sql);
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        return $messages;
    }


    // public function getUserMessage(int $idUser)
    // {
    //     $sql = "SELECT * FROM chat_konsultan WHERE id_user = ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bind_param("i", $idUser);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     $messages = [];
    //     while ($row = $result->fetch_assoc()) {
    //         $messages[] = $row;
    //     }
    //     return $messages;
    // }

    // public function getKonsultanMessage(int $idKonsultan): array
    // {
    //     $sql = "SELECT * FROM chat_konsultan WHERE id_konselor = ?";
    //     $stmt = $this->conn->prepare($sql); 
    //     $stmt->bind_param("i", $idKonsultan);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     $messages = [];
    //     while ($row = $result->fetch_assoc()) {
    //         $messages[] = $row;
    //     }
    //     return $messages;
    // }

    // Tambahkan string $pengirim di parameter
    public function sendUserMessage(int $idUser, int $idKonsultan, string $pengirim, string $isiChat): array
    {
        try {
            // Tambahkan kolom pengirim di query
            $sql = "
            INSERT INTO chat_konsultan
            (id_user, id_konselor, pengirim, chat, time_stamp)
            VALUES (?, ?, ?, ?, NOW())
        ";

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                return [
                    'status' => 'error',
                    'message' => 'Prepare failed: ' . $this->conn->error
                ];
            }

            // Tipe data bind_param: Integer, Integer, String, String ("iiss")
            $stmt->bind_param("iiss", $idUser, $idKonsultan, $pengirim, $isiChat);

            if ($stmt->execute()) {
                return [
                    'status' => 'success',
                    'message' => 'Pesan berhasil dikirim.',
                    'data' => [
                        'id_chat' => $this->conn->insert_id,
                        'id_user' => $idUser,
                        'id_konsultan' => $idKonsultan,
                        'pengirim' => $pengirim,
                        'chat' => $isiChat
                    ]
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Execute failed: ' . $stmt->error
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'message' => 'System Error: ' . $e->getMessage()
            ];
        }
    }
}
