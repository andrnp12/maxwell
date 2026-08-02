<?php
require_once 'dbconnect.php';
class ChatV2
{
    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    private function getConfig(string $type): array
    {
        return match ($type) {

            'personal' => [

                'table' => 'chat_konsultan',

                'target_column' => 'id_konselor',

                'join_table' => 'users',

                'join_key' => 'id',

                'display_column' => 'username',

                'display_alias' => 'nama'
            ],

            'group' => [

                'table' => 'chat_komunitas',

                'target_column' => 'id_komunitas',

                'join_table' => 'komunitas',

                'join_key' => 'id',

                'display_column' => 'nama_komunitas',

                'display_alias' => 'nama'
            ],

            default => throw new InvalidArgumentException(
                "Chat type tidak valid."
            )
        };
    }

    private function fetchAll(mysqli_result $result): array
    {
        $rows = [];

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getMessages(
        string $type,
        int $idUser,
        int $targetId,
        int $lastId = 0
    ): array {

        try {

            $config = $this->getConfig($type);

            // =====================
            // CHAT GROUP
            // =====================
            if ($type === 'group') {

                $sql = "
                SELECT
                    c.*,
                    u.name,
                    u.foto
                FROM {$config['table']} c
                INNER JOIN users u
                    ON u.id = c.sender_id
                WHERE c.{$config['target_column']} = ?
                AND c.id > ?
                ORDER BY c.id ASC
            ";

                $stmt = $this->conn->prepare($sql);

                if (!$stmt) {
                    throw new Exception($this->conn->error);
                }

                $stmt->bind_param(
                    "ii",
                    $targetId,
                    $lastId
                );
            }
            // =====================
            // CHAT PERSONAL
            // =====================
            else {

                $sql = "
                SELECT *
                FROM {$config['table']}
                WHERE id_user = ?
                AND {$config['target_column']} = ?
                AND id > ?
                ORDER BY id ASC
            ";

                $stmt = $this->conn->prepare($sql);

                if (!$stmt) {
                    throw new Exception($this->conn->error);
                }

                $stmt->bind_param(
                    "iii",
                    $idUser,
                    $targetId,
                    $lastId
                );
            }

            $stmt->execute();

            $result = $stmt->get_result();

            return [
                'status' => 'success',
                'data' => $this->fetchAll($result)
            ];
        } catch (Throwable $e) {

            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function sendMessage(
        string $type,
        int $senderId,
        int $idUser,
        int $targetId,
        string $message
    ): array {

        try {

            $config = $this->getConfig($type);

            $sql = "
            INSERT INTO {$config['table']}
            (
                sender_id,
                id_user,
                {$config['target_column']},
                chat,
                time_stamp
            )
            VALUES
            (
                ?, ?, ?, ?, NOW()
            )
        ";

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception($this->conn->error);
            }

            $stmt->bind_param(
                "iiis",
                $senderId,
                $idUser,
                $targetId,
                $message
            );

            $stmt->execute();

            return [
                'status' => 'success',
                'message' => 'Pesan berhasil dikirim.',
                'insert_id' => $this->conn->insert_id
            ];
        } catch (Throwable $e) {

            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function getConversationList(
        string $type,
        int $loginId,
        ?string $role = null
    ): array {

        return match ($type) {

            'personal' => $this->getPersonalConversationList(
                $loginId,
                $role
            ),

            'group' => $this->getGroupConversationList(
                $loginId
            ),

            default => [
                'status' => 'error',
                'message' => 'Chat type tidak valid.'
            ]
        };
    }

    private function getPersonalConversationList(
        int $loginId,
        string $role
    ): array {

        if ($role === 'user') {

            $sql = "
            SELECT
                u.id AS id_lawan,
                u.username AS username,
                u.name AS name,
                c.chat AS pesan_terakhir,
                c.time_stamp
            FROM users u

            INNER JOIN (
                SELECT *
                FROM chat_konsultan
                WHERE id IN (
                    SELECT MAX(id)
                    FROM chat_konsultan
                    WHERE id_user = ?
                    GROUP BY id_konselor
                )
            ) c
                ON c.id_konselor = u.id

            ORDER BY c.time_stamp DESC
        ";
        } else {

            $sql = "
            SELECT
                u.id AS id_lawan,
                u.username AS username,
                u.name AS name,
                c.chat AS pesan_terakhir,
                c.time_stamp
            FROM users u

            INNER JOIN (
                SELECT *
                FROM chat_konsultan
                WHERE id IN (
                    SELECT MAX(id)
                    FROM chat_konsultan
                    WHERE id_konselor = ?
                    GROUP BY id_user
                )
            ) c
                ON c.id_user = u.id

            ORDER BY c.time_stamp DESC
        ";
        }

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return [
                'status' => 'error',
                'message' => $this->conn->error
            ];
        }

        $stmt->bind_param("i", $loginId);

        $stmt->execute();

        return [
            'status' => 'success',
            'data' => $this->fetchAll($stmt->get_result())
        ];
    }

    private function getGroupConversationList(int $loginId): array
    {
        $sql = "
        SELECT
            k.id AS id_lawan,
            k.nama_komunitas AS name,
            k.foto,

            ck.chat AS pesan_terakhir,
            ck.time_stamp

        FROM anggota_komunitas ak

        INNER JOIN komunitas k
            ON k.id = ak.id_komunitas

        LEFT JOIN (

            SELECT c1.*

            FROM chat_komunitas c1

            INNER JOIN (

                SELECT
                    id_komunitas,
                    MAX(id) AS last_id
                FROM chat_komunitas
                GROUP BY id_komunitas

            ) c2

            ON c1.id = c2.last_id

        ) ck

        ON ck.id_komunitas = k.id

        WHERE ak.id_user = ?

        ORDER BY
            ck.time_stamp DESC,
            k.nama_komunitas ASC
    ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return [
                'status' => 'error',
                'message' => $this->conn->error
            ];
        }

        $stmt->bind_param("i", $loginId);

        $stmt->execute();

        $result = $stmt->get_result();

        return [
            'status' => 'success',
            'data' => $this->fetchAll($result)
        ];
    }


    // group
    public function getRoomInfo(
        string $type,
        int $targetId
    ): array {
        if ($type === 'personal') {
            return $this->getPersonalRoomInfo($targetId);
        }

        if ($type === 'group') {
            return $this->getGroupRoomInfo($targetId);
        }

        return [
            'status' => 'error',
            'message' => 'Tipe chat tidak dikenal.'
        ];
    }

    private function getPersonalRoomInfo(
        int $userId
    ): array {

        $sql = "
        SELECT
            id,
            username,
            name,
            role,
            foto
        FROM users
        WHERE id = ?
        LIMIT 1
    ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {

            return [
                'status' => 'error',
                'message' => $this->conn->error
            ];
        }

        $stmt->bind_param("i", $userId);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 0) {

            return [
                'status' => 'error',
                'message' => 'User tidak ditemukan.'
            ];
        }

        $row = $result->fetch_assoc();

        return [

            'status' => 'success',

            'data' => [

                'id' => $row['id'],

                'username' => $row['username'],

                'name' => $row['name'],

                'foto' => $row['foto'],

                'subtitle' => ucfirst($row['role'])

            ]

        ];
    }

    private function getGroupRoomInfo(
        int $groupId
    ): array {

        $sql = "
        SELECT
            k.id,
            k.nama_komunitas,
            k.foto,
            COUNT(ak.id) AS jumlah_anggota

        FROM komunitas k

        LEFT JOIN anggota_komunitas ak
            ON ak.id_komunitas = k.id

        WHERE k.id = ?

        GROUP BY k.id
    ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {

            return [
                'status' => 'error',
                'message' => $this->conn->error
            ];
        }

        $stmt->bind_param("i", $groupId);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 0) {

            return [
                'status' => 'error',
                'message' => 'Komunitas tidak ditemukan.'
            ];
        }

        $row = $result->fetch_assoc();

        return [

            'status' => 'success',

            'data' => [

                'id' => $row['id'],

                'name' => $row['nama_komunitas'],

                'foto' => $row['foto'],

                'subtitle' => $row['jumlah_anggota'] . ' anggota'

            ]

        ];
    }

    public function resolveChatTarget(
        string $type,
        int $loginId,
        int $targetId,
        string $role
    ): array {

        if ($type === 'group') {

            return [
                'status' => 'success',
                'data' => [
                    'id_user' => $loginId,
                    'target'  => $targetId
                ]
            ];
        }

        if ($role === 'user') {

            return [
                'status' => 'success',
                'data' => [
                    'id_user' => $loginId,
                    'target'  => $targetId
                ]
            ];
        }

        return [
            'status' => 'success',
            'data' => [
                'id_user' => $targetId,
                'target'  => $loginId
            ]
        ];
    }

    private function resolveTable(string $type): array
    {
        switch ($type) {

            case 'personal':

                return [
                    'table'      => 'chat_konsultan',
                    'target_key' => 'id_konselor'
                ];

            case 'group':

                return [
                    'table'      => 'chat_komunitas',
                    'target_key' => 'id_komunitas'
                ];

            default:

                throw new InvalidArgumentException(
                    "Tipe chat tidak dikenal."
                );
        }
    }
}
