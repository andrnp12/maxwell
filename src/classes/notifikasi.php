<?php
require_once 'dbconnect.php';

class Notifikasi
{
    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    public function getNotifikasiRole(string $role, int $userId): array
    {
        $items = [];
        $count = 0;

        if ($role === 'user') {
            // 1. Chat personal dari konselor (hanya pesan dari konselor, bukan dari user sendiri)
            $sql = "SELECT c.id, c.chat, c.time_stamp, u.name AS konselor_name
        FROM chat_konsultan c
        JOIN users u ON c.sender_id = u.id
        WHERE c.id_user = ?
        AND c.sender_id = c.id_konselor
        ORDER BY c.time_stamp DESC
        LIMIT 5";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $items[] = [
                    'id' => $row['id'],
                    'type' => 'chat_personal',
                    'message' => 'Pesan baru dari konselor: ' . $row['konselor_name'],
                    'icon' => 'message-square',
                    'time' => $row['time_stamp'],
                    'text' => $row['chat']
                ];
            }

            // 2. Chat grup komunitas yang diikuti (pesan terbaru per grup)
            // Skip jika tabel anggota_komunitas tidak ada
            $tableCheck = $this->conn->query("SHOW TABLES LIKE 'anggota_komunitas'");
            if ($tableCheck && $tableCheck->num_rows > 0) {
                $sql = "SELECT ck.id,
               ck.chat,
               ck.time_stamp,
               k.nama_komunitas,
               u.name AS sender_name
        FROM chat_komunitas ck
        JOIN anggota_komunitas ak
            ON ak.id_komunitas = ck.id_komunitas
        JOIN komunitas k
            ON k.id = ck.id_komunitas
        JOIN users u
            ON u.id = ck.sender_id
        WHERE ak.id_user = ?
        AND ck.id = (
            SELECT MAX(c2.id)
            FROM chat_komunitas c2
            WHERE c2.id_komunitas = ck.id_komunitas
            AND c2.sender_id != ?
        )
        ORDER BY ck.time_stamp DESC
        LIMIT 3";

                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ii", $userId, $userId);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $items[] = [
                        'id' => $row['id'],
                        'type' => 'chat_group',
                        'message' => 'Pesan di komunitas: ' . $row['nama_komunitas'],
                        'icon' => 'users',
                        'time' => $row['time_stamp'],
                        'text' => $row['sender_name'] . ': ' . $row['chat']
                    ];
                }
            }

            // 3. Materi baru yang belum dibaca (no_urut > progress user)
            $sql = "SELECT m.id, m.judul, m.deskripsi, m.no_urut
                    FROM materials m
                    WHERE m.no_urut > (
                        SELECT COALESCE(MAX(m2.no_urut), 0)
                        FROM materials m2
                        JOIN materials_progress mp ON mp.material_id = m2.id AND mp.user_id = ? AND mp.material_selesai = 1
                    )
                    ORDER BY m.no_urut ASC
                    LIMIT 3";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $items[] = [
                    'id' => $row['id'],
                    'type' => 'materi_baru',
                    'message' => 'Materi baru tersedia: ' . $row['judul'],
                    'icon' => 'book-open',
                    'time' => null,
                    'text' => $row['deskripsi'] ?? 'Materi ke-' . $row['no_urut']
                ];
            }

            // 4. Kuis/pretest/posttest baru yang belum dikerjakan
            $sql = "SELECT q.id, q.judul, q.jenis, q.passing_grade
                    FROM quizzes q
                    WHERE q.id NOT IN (
                        SELECT DISTINCT qr.kuis_id FROM quiz_results qr
                        WHERE qr.user_id = ? AND qr.kuis_id IS NOT NULL
                    )
                    ORDER BY q.id DESC
                    LIMIT 3";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $jenisLabel = match ($row['jenis']) {
                    'pre' => 'Pretest',
                    'post' => 'Posttest',
                    default => 'Kuis'
                };
                $items[] = [
                    'id' => $row['id'],
                    'type' => 'kuis_baru',
                    'message' => $jenisLabel . ' baru: ' . $row['judul'],
                    'icon' => 'help-circle',
                    'time' => null,
                    'text' => 'Passing grade: ' . $row['passing_grade'] . '%'
                ];
            }

            // 5. Hasil kuis yang baru keluar (quiz_results terbaru)
            // gunakan qr.id sebagai timestamp fallback karena tidak ada kolom created_at
            $sql = "SELECT qr.id, qr.nilai, qr.jenis, q.judul AS kuis_judul
                    FROM quiz_results qr
                    JOIN quizzes q ON q.id = qr.kuis_id
                    WHERE qr.user_id = ?
                    ORDER BY qr.id DESC
                    LIMIT 3";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $jenisLabel = match ($row['jenis']) {
                    'pre' => 'Pretest',
                    'post' => 'Posttest',
                    default => 'Kuis'
                };
                $items[] = [
                    'id' => $row['id'],
                    'type' => 'hasil_kuis',
                    'message' => 'Hasil ' . $jenisLabel . ': ' . $row['kuis_judul'],
                    'icon' => 'award',
                    'time' => null, // tidak ada created_at, gunakan null
                    'text' => 'Nilai: ' . $row['nilai']
                ];
            }

            // Hitung total count - cocokkan dengan logika display items (LIMIT per kategori, lalu slice 10)
            $tableCheck = $this->conn->query("SHOW TABLES LIKE 'anggota_komunitas'");
            $hasAnggotaTable = $tableCheck && $tableCheck->num_rows > 0;

            // Chat personal: hitung distinct konselor yang punya pesan baru (bukan total pesan)
            $chatPersonalSql = "SELECT COUNT(DISTINCT c.id_konselor)
    FROM chat_konsultan c
    WHERE c.id_user = ?
    AND c.sender_id = c.id_konselor";

            // Chat grup: hitung distinct komunitas dengan pesan (sudah benar)
            $chatGroupSql = $hasAnggotaTable ? "(SELECT COUNT(DISTINCT ck.id_komunitas)
                 FROM chat_komunitas ck
                 JOIN anggota_komunitas ak ON ak.id_komunitas = ck.id_komunitas AND ak.id_user = ?)" : "0";

            // Materi baru: hitung materi yang belum dibaca (sudah benar)
            $materiSql = "(SELECT COUNT(*) FROM materials m
                 WHERE m.no_urut > (
                     SELECT COALESCE(MAX(m2.no_urut), 0)
                     FROM materials m2
                     JOIN materials_progress mp ON mp.material_id = m2.id AND mp.user_id = ? AND mp.material_selesai = 1
                 ))";

            // Kuis baru: hitung kuis yang belum dikerjakan (sudah benar)
            $kuisSql = "(SELECT COUNT(*) FROM quizzes q
                 WHERE q.id NOT IN (
                     SELECT DISTINCT qr.kuis_id FROM quiz_results qr
                     WHERE qr.user_id = ? AND qr.kuis_id IS NOT NULL
                 ))";

            // Hasil kuis: hitung hasil terbaru (maks 3 seperti display)
            $hasilKuisSql = "(SELECT COUNT(*) FROM (
                 SELECT qr.id FROM quiz_results qr
                 JOIN quizzes q ON q.id = qr.kuis_id
                 WHERE qr.user_id = ?
                 ORDER BY qr.id DESC LIMIT 3
             ) AS subq)";

            $countSql = "SELECT
                ($chatPersonalSql) +
                " . ($hasAnggotaTable ? "($chatGroupSql) +" : "0 +") . "
                $materiSql +
                $kuisSql +
                $hasilKuisSql as total";

            $stmtCount = $this->conn->prepare($countSql);
            if ($hasAnggotaTable) {
                $stmtCount->bind_param("iiiii", $userId, $userId, $userId, $userId, $userId);
            } else {
                $stmtCount->bind_param("iiii", $userId, $userId, $userId, $userId);
            }
            $stmtCount->execute();
            $countRes = $stmtCount->get_result();
            $count = (int) $countRes->fetch_assoc()['total'];
        } elseif ($role === 'konsultan') {
            // 1. Chat personal dari user (hanya pesan dari user, bukan dari konsultan sendiri)
            $sql = "SELECT c.id, c.chat, c.time_stamp, u.name AS user_name
        FROM chat_konsultan c
        JOIN users u ON c.sender_id = u.id
        WHERE c.id_konselor = ?
        AND c.sender_id = c.id_user
        ORDER BY c.time_stamp DESC
        LIMIT 5";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $items[] = [
                    'id' => $row['id'],
                    'type' => 'chat_personal',
                    'message' => 'Pesan dari user: ' . $row['user_name'],
                    'icon' => 'message-square',
                    'time' => $row['time_stamp'],
                    'text' => $row['chat']
                ];
            }

            // Konsultan tidak bisa join komunitas, skip chat grup
            // Hitung distinct user yang mengirim pesan (bukan total pesan)
            $countSql = "SELECT
    (SELECT COUNT(DISTINCT c.id_user)
    FROM chat_konsultan c
    WHERE c.id_konselor = ?
    AND c.sender_id = c.id_user) as total";
            $stmtCount = $this->conn->prepare($countSql);
            $stmtCount->bind_param("i", $userId);
            $stmtCount->execute();
            $countRes = $stmtCount->get_result();
            $count = (int) $countRes->fetch_assoc()['total'];
        } else {
            // ortu / admin / lainnya
            // Ortua tidak bisa join komunitas, hanya materi
            // 1. Materi terbaru
            $sql = "SELECT id, judul, deskripsi FROM materials ORDER BY id DESC LIMIT 3";
            $res = $this->conn->query($sql);
            while ($row = $res->fetch_assoc()) {
                $items[] = [
                    'id' => $row['id'],
                    'type' => 'materi',
                    'message' => 'Materi baru: ' . $row['judul'],
                    'icon' => 'book-open',
                    'time' => null,
                    'text' => $row['deskripsi']
                ];
            }

            $countSql = "SELECT
                (SELECT COUNT(*) FROM (SELECT id FROM materials ORDER BY id DESC LIMIT 3) AS subq) as total";
            $countRes = $this->conn->query($countSql);
            $count = $countRes ? (int) $countRes->fetch_assoc()['total'] : 0;
        }

        // Urutkan item berdasarkan waktu (yang punya time dulu, yang tidak di akhir)
        usort($items, function ($a, $b) {
            if ($a['time'] && $b['time']) {
                return strtotime($b['time']) - strtotime($a['time']);
            }
            return $a['time'] ? -1 : ($b['time'] ? 1 : 0);
        });

        // Batasi total item yang dikembalikan
        $items = array_slice($items, 0, 10);

        return ['count' => $count, 'items' => $items];
    }
}
