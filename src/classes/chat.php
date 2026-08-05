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

    public function getAllMessage(
        int $id_user,
        int $id_konsultan,
        int $lastId = 0
    ): array {

        if ($lastId > 0) {

            $sql = "SELECT *
                FROM chat_konsultan
                WHERE id_user = ?
                AND id_konselor = ?
                AND id > ?
                ORDER BY id ASC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "iii",
                $id_user,
                $id_konsultan,
                $lastId
            );
        } else {

            $sql = "SELECT *
                FROM chat_konsultan
                WHERE id_user = ?
                AND id_konselor = ?
                ORDER BY id ASC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "ii",
                $id_user,
                $id_konsultan
            );
        }

        $stmt->execute();

        $result = $stmt->get_result();

        $messages = [];

        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        return $messages;
    }

    public function getListChat(int $id_login, string $role): array
    {
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

    /**
     * Get admin dashboard stats for chat activity
     * Returns counts for active conversations, total messages, etc.
     */
    public function getAdminDashboardStats(): array
    {
        $stats = [];

        // Total unique user-counselor conversations
        $sql = "SELECT COUNT(DISTINCT CONCAT(id_user, '-', id_konselor)) as total_conversations FROM chat_konsultan";
        $result = $this->conn->query($sql);
        $stats['total_conversations'] = $result ? (int)$result->fetch_assoc()['total_conversations'] : 0;

        // Total messages
        $sql = "SELECT COUNT(*) as total_messages FROM chat_konsultan";
        $result = $this->conn->query($sql);
        $stats['total_messages'] = $result ? (int)$result->fetch_assoc()['total_messages'] : 0;

        // Active conversations in last 7 days
        $sql = "SELECT COUNT(DISTINCT CONCAT(id_user, '-', id_konselor)) as active_conversations
                FROM chat_konsultan
                WHERE time_stamp >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $result = $this->conn->query($sql);
        $stats['active_conversations_7d'] = $result ? (int)$result->fetch_assoc()['active_conversations'] : 0;

        // Messages in last 7 days
        $sql = "SELECT COUNT(*) as messages_7d
                FROM chat_konsultan
                WHERE time_stamp >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $result = $this->conn->query($sql);
        $stats['messages_7d'] = $result ? (int)$result->fetch_assoc()['messages_7d'] : 0;

        // Unique users who chatted
        $sql = "SELECT COUNT(DISTINCT id_user) as unique_users_chatted FROM chat_konsultan";
        $result = $this->conn->query($sql);
        $stats['unique_users_chatted'] = $result ? (int)$result->fetch_assoc()['unique_users_chatted'] : 0;

        // Unique counselors who chatted
        $sql = "SELECT COUNT(DISTINCT id_konselor) as unique_counselors_chatted FROM chat_konsultan";
        $result = $this->conn->query($sql);
        $stats['unique_counselors_chatted'] = $result ? (int)$result->fetch_assoc()['unique_counselors_chatted'] : 0;

        return [
            'status' => 'success',
            'data' => $stats
        ];
    }

    /**
     * Get recent chat activity for admin dashboard
     */
    public function getRecentChatActivity(int $limit = 5): array
    {
        $sql = "
            SELECT ck.*, u.name as user_name, u.foto as user_foto, k.name as konselor_name, k.foto as konselor_foto
            FROM chat_konsultan ck
            JOIN users u ON ck.id_user = u.id
            JOIN users k ON ck.id_konselor = k.id
            ORDER BY ck.time_stamp DESC
            LIMIT ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $activities = [];
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }

        return [
            'status' => 'success',
            'data' => $activities
        ];
    }
}
