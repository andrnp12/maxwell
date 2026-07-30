<?php

require_once 'dbconnect.php';

class chatKomunitas
{
    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    public function getAllMessage(int $id_user, int $id_komunitas, int $lastId = 0): array
    {
        if ($lastId > 0) {
            $sql = "SELECT *
                FROM chat_komunitas
                WHERE id_user = ?
                AND id_komunitas = ?
                AND id > ?
                ORDER BY id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iii", $id_user, $id_komunitas, $lastId);
        } else {
            $sql = "SELECT *
                FROM chat_komunitas
                WHERE id_user = ?
                AND id_komunitas = ?
                ORDER BY id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $id_user, $id_komunitas);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        return $messages;
    }

    public function sendUserMessageKomunitas(int $idUser, int $idKomunitas, string $isiChat): array
    {
        try {
            $sql = "
        INSERT INTO chat_komunitas
        (id_user, id_komunitas, chat, time_stamp)
        VALUES (?, ?, ?, ?, NOW())";

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                return [
                    'status' => 'error',
                    'message' => 'Prepare failed: ' . $this->conn->error
                ];
            }

            $stmt->bind_param("iis", $idUser, $idKomunitas, $isiChat);

            if ($stmt->execute()) {
                return [
                    'status' => 'success',
                    'message' => 'Pesan berhasil dikirim.',
                    'data' => [
                        'id_chat_kom' => $this->conn->insert_id,
                        'id_user' => $idUser,
                        'id_komunitas' => $idKomunitas,
                        'chat' => $isiChat,
                        'time_stamp' => date('Y-m-d H:i:s')
                    ]
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Pesan gagal dikirim: ' . $stmt->error
            ];
        } catch (\Throwable $th) {
            return [
                'status' => 'error',
                'message' => 'Pesan gagal dikirim: ' . $th->getMessage()
            ];
        }
    }
};
