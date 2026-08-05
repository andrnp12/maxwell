<?php
require_once 'dbconnect.php';

class User
{
    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    /**
     * Get all users with their quiz results aggregated
     * Pretest/Posttest: best score (MAX)
     * Kuis Rata2: average of best scores per quiz (for each quiz, take user's best attempt, then average across quizzes)
     */
    public function getAllUsersWithQuizResults(): array
    {
        $sql = "
            SELECT
                u.id,
                u.name,
                u.username,
                u.email,
                u.role,
                u.foto,
                -- Pretest: best score (MAX)
                COALESCE(MAX(CASE WHEN qr.jenis = 'pre' THEN qr.nilai END), 0) AS pretest_nilai,
                -- Posttest: best score (MAX)
                COALESCE(MAX(CASE WHEN qr.jenis = 'post' THEN qr.nilai END), 0) AS posttest_nilai,
                -- Kuis Rata2: average of best scores per quiz
                COALESCE((
                    SELECT AVG(best_per_quiz.max_nilai)
                    FROM (
                        SELECT qr2.kuis_id, MAX(qr2.nilai) AS max_nilai
                        FROM quiz_results qr2
                        WHERE qr2.user_id = u.id
                        AND qr2.jenis = 'kuis'
                        AND qr2.kuis_id IS NOT NULL
                        GROUP BY qr2.kuis_id
                    ) AS best_per_quiz
                ), 0) AS kuis_rata2,
                -- Count of attempts for each type
                COUNT(CASE WHEN qr.jenis = 'pre' THEN 1 END) AS pretest_attempts,
                COUNT(CASE WHEN qr.jenis = 'post' THEN 1 END) AS posttest_attempts,
                COUNT(CASE WHEN qr.jenis = 'kuis' THEN 1 END) AS kuis_attempts
            FROM users u
            LEFT JOIN quiz_results qr ON u.id = qr.user_id
            WHERE u.role = 'user'
            GROUP BY u.id, u.name, u.username, u.email, u.role, u.foto
            ORDER BY u.name ASC
        ";

        $result = $this->conn->query($sql);

        if (!$result) {
            return [
                'status' => 'error',
                'message' => 'Query error: ' . $this->conn->error
            ];
        }

        $users = [];
        while ($row = $result->fetch_assoc()) {
            // Format numbers nicely
            $row['pretest_nilai'] = $row['pretest_nilai'] > 0 ? round($row['pretest_nilai'], 1) : '-';
            $row['posttest_nilai'] = $row['posttest_nilai'] > 0 ? round($row['posttest_nilai'], 1) : '-';
            $row['kuis_rata2'] = $row['kuis_rata2'] > 0 ? round($row['kuis_rata2'], 1) : '-';
            $users[] = $row;
        }

        return [
            'status' => 'success',
            'data' => $users
        ];
    }

    /**
     * Get single user with quiz results
     */
    public function getUserWithQuizResults(int $userId): array
    {
        $sql = "
            SELECT
                u.id,
                u.name,
                u.username,
                u.email,
                u.role,
                u.foto,
                u.deskripsi,
                u.nomor,
                -- Pretest: best score (MAX)
                COALESCE(MAX(CASE WHEN qr.jenis = 'pre' THEN qr.nilai END), 0) AS pretest_nilai,
                -- Posttest: best score (MAX)
                COALESCE(MAX(CASE WHEN qr.jenis = 'post' THEN qr.nilai END), 0) AS posttest_nilai,
                -- Kuis Rata2: average of best scores per quiz
                COALESCE((
                    SELECT AVG(best_per_quiz.max_nilai)
                    FROM (
                        SELECT qr2.kuis_id, MAX(qr2.nilai) AS max_nilai
                        FROM quiz_results qr2
                        WHERE qr2.user_id = u.id
                        AND qr2.jenis = 'kuis'
                        AND qr2.kuis_id IS NOT NULL
                        GROUP BY qr2.kuis_id
                    ) AS best_per_quiz
                ), 0) AS kuis_rata2,
                -- Count of attempts for each type
                COUNT(CASE WHEN qr.jenis = 'pre' THEN 1 END) AS pretest_attempts,
                COUNT(CASE WHEN qr.jenis = 'post' THEN 1 END) AS posttest_attempts,
                COUNT(CASE WHEN qr.jenis = 'kuis' THEN 1 END) AS kuis_attempts
            FROM users u
            LEFT JOIN quiz_results qr ON u.id = qr.user_id
            WHERE u.id = ? AND u.role = 'user'
            GROUP BY u.id, u.name, u.username, u.email, u.role, u.foto, u.deskripsi, u.nomor
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return [
                'status' => 'error',
                'message' => 'User tidak ditemukan.'
            ];
        }

        $row = $result->fetch_assoc();

        // Format numbers nicely
        $row['pretest_nilai'] = $row['pretest_nilai'] > 0 ? round($row['pretest_nilai'], 1) : '-';
        $row['posttest_nilai'] = $row['posttest_nilai'] > 0 ? round($row['posttest_nilai'], 1) : '-';
        $row['kuis_rata2'] = $row['kuis_rata2'] > 0 ? round($row['kuis_rata2'], 1) : '-';

        return [
            'status' => 'success',
            'data' => $row
        ];
    }

    /**
     * Get all users (basic info only)
     */
    public function getAllUsers(): array
    {
        $sql = "SELECT id, name, username, email, role, foto FROM users WHERE role = 'user' ORDER BY name ASC";
        $result = $this->conn->query($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return [
            'status' => 'success',
            'data' => $users
        ];
    }

    /**
     * Get detailed quiz results for a user (for detail page)
     * Returns attempts grouped by type: pre, post, kuis
     */
    public function getUserQuizResultsDetail(int $userId): array
    {
        // Get pretest attempts (latest first)
        $pretestSql = "
            SELECT qr.*, q.judul AS kuis_judul
            FROM quiz_results qr
            LEFT JOIN quizzes q ON qr.kuis_id = q.id
            WHERE qr.user_id = ? AND qr.jenis = 'pre'
            ORDER BY qr.id DESC
        ";
        $stmt = $this->conn->prepare($pretestSql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $pretestResult = $stmt->get_result();
        $pretestAttempts = [];
        while ($row = $pretestResult->fetch_assoc()) {
            $pretestAttempts[] = $row;
        }

        // Get posttest attempts (latest first)
        $posttestSql = "
            SELECT qr.*, q.judul AS kuis_judul
            FROM quiz_results qr
            LEFT JOIN quizzes q ON qr.kuis_id = q.id
            WHERE qr.user_id = ? AND qr.jenis = 'post'
            ORDER BY qr.id DESC
        ";
        $stmt = $this->conn->prepare($posttestSql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $posttestResult = $stmt->get_result();
        $posttestAttempts = [];
        while ($row = $posttestResult->fetch_assoc()) {
            $posttestAttempts[] = $row;
        }

        // Get kuis attempts grouped by quiz
        $kuisSql = "
            SELECT qr.*, q.judul AS kuis_judul, q.jenis AS kuis_jenis
            FROM quiz_results qr
            LEFT JOIN quizzes q ON qr.kuis_id = q.id
            WHERE qr.user_id = ? AND qr.jenis = 'kuis'
            ORDER BY qr.kuis_id, qr.id DESC
        ";
        $stmt = $this->conn->prepare($kuisSql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $kuisResult = $stmt->get_result();
        $kuisAttempts = [];
        while ($row = $kuisResult->fetch_assoc()) {
            $kuisAttempts[] = $row;
        }

        // Group kuis attempts by quiz
        $kuisByQuiz = [];
        foreach ($kuisAttempts as $attempt) {
            $kuisId = $attempt['kuis_id'] ?? 0;
            if (!isset($kuisByQuiz[$kuisId])) {
                $kuisByQuiz[$kuisId] = [
                    'kuis_id' => $kuisId,
                    'kuis_judul' => $attempt['kuis_judul'] ?? 'Kuis Tanpa Judul',
                    'kuis_jenis' => $attempt['kuis_jenis'] ?? 'kuis',
                    'attempts' => []
                ];
            }
            $kuisByQuiz[$kuisId]['attempts'][] = $attempt;
        }

        return [
            'status' => 'success',
            'data' => [
                'pretest' => $pretestAttempts,
                'posttest' => $posttestAttempts,
                'kuis' => array_values($kuisByQuiz)
            ]
        ];
    }
    /**
     * Get dashboard statistics for admin
     * Returns aggregated counts for key metric cards
     */
    public function getDashboardStats(): array
    {
        $sql = "
            SELECT
                -- Total users (role = 'user')
                (SELECT COUNT(*) FROM users WHERE role = 'user') AS total_users,
                -- Active users (has at least one quiz attempt)
                (SELECT COUNT(DISTINCT user_id) FROM quiz_results WHERE user_id IN (SELECT id FROM users WHERE role = 'user')) AS active_users,
                -- Users who completed pretest
                (SELECT COUNT(DISTINCT user_id) FROM quiz_results WHERE jenis = 'pre' AND user_id IN (SELECT id FROM users WHERE role = 'user')) AS pretest_completed,
                -- Users who completed at least one kuis
                (SELECT COUNT(DISTINCT user_id) FROM quiz_results WHERE jenis = 'kuis' AND user_id IN (SELECT id FROM users WHERE role = 'user')) AS kuis_completed,
                -- Users who completed posttest
                (SELECT COUNT(DISTINCT user_id) FROM quiz_results WHERE jenis = 'post' AND user_id IN (SELECT id FROM users WHERE role = 'user')) AS posttest_completed,
                -- Total quiz attempts
                (SELECT COUNT(*) FROM quiz_results WHERE user_id IN (SELECT id FROM users WHERE role = 'user')) AS total_attempts,
                -- Average pretest score
                (SELECT COALESCE(AVG(nilai), 0) FROM quiz_results WHERE jenis = 'pre' AND user_id IN (SELECT id FROM users WHERE role = 'user')) AS avg_pretest,
                -- Average kuis score (best per quiz per user, then average)
                (SELECT COALESCE(AVG(best_per_quiz.max_nilai), 0)
                    FROM (
                        SELECT qr2.user_id, qr2.kuis_id, MAX(qr2.nilai) AS max_nilai
                        FROM quiz_results qr2
                        WHERE qr2.jenis = 'kuis'
                        AND qr2.kuis_id IS NOT NULL
                        AND qr2.user_id IN (SELECT id FROM users WHERE role = 'user')
                        GROUP BY qr2.user_id, qr2.kuis_id
                    ) AS best_per_quiz
                ) AS avg_kuis,
                -- Average posttest score
                (SELECT COALESCE(AVG(nilai), 0) FROM quiz_results WHERE jenis = 'post' AND user_id IN (SELECT id FROM users WHERE role = 'user')) AS avg_posttest
        ";

        $result = $this->conn->query($sql);

        if (!$result) {
            return [
                'status' => 'error',
                'message' => 'Query error: ' . $this->conn->error
            ];
        }

        $row = $result->fetch_assoc();

        // Format numbers
        $row['avg_pretest'] = round($row['avg_pretest'], 1);
        $row['avg_kuis'] = round($row['avg_kuis'], 1);
        $row['avg_posttest'] = round($row['avg_posttest'], 1);

        return [
            'status' => 'success',
            'data' => $row
        ];
    }

    /**
     * Get learning funnel data for admin dashboard
     * Shows progression: Registered → Pretest → Kuis → Posttest
     */
    public function getLearningFunnel(): array
    {
        $sql = "
            SELECT
                -- Total registered users
                (SELECT COUNT(*) FROM users WHERE role = 'user') AS registered,
                -- Users who attempted pretest
                (SELECT COUNT(DISTINCT user_id) FROM quiz_results WHERE jenis = 'pre' AND user_id IN (SELECT id FROM users WHERE role = 'user')) AS pretest_attempted,
                -- Users who completed pretest (scored > 0)
                (SELECT COUNT(DISTINCT user_id) FROM quiz_results WHERE jenis = 'pre' AND nilai > 0 AND user_id IN (SELECT id FROM users WHERE role = 'user')) AS pretest_completed,
                -- Users who attempted at least one kuis
                (SELECT COUNT(DISTINCT user_id) FROM quiz_results WHERE jenis = 'kuis' AND user_id IN (SELECT id FROM users WHERE role = 'user')) AS kuis_attempted,
                -- Users who completed at least one kuis (scored > 0)
                (SELECT COUNT(DISTINCT user_id) FROM quiz_results WHERE jenis = 'kuis' AND nilai > 0 AND user_id IN (SELECT id FROM users WHERE role = 'user')) AS kuis_completed,
                -- Users who attempted posttest
                (SELECT COUNT(DISTINCT user_id) FROM quiz_results WHERE jenis = 'post' AND user_id IN (SELECT id FROM users WHERE role = 'user')) AS posttest_attempted,
                -- Users who completed posttest (scored > 0)
                (SELECT COUNT(DISTINCT user_id) FROM quiz_results WHERE jenis = 'post' AND nilai > 0 AND user_id IN (SELECT id FROM users WHERE role = 'user')) AS posttest_completed
        ";

        $result = $this->conn->query($sql);

        if (!$result) {
            return [
                'status' => 'error',
                'message' => 'Query error: ' . $this->conn->error
            ];
        }

        $row = $result->fetch_assoc();

        // Calculate percentages relative to registered users
        $registered = max((int)$row['registered'], 1);
        $funnel = [
            'registered' => (int)$row['registered'],
            'pretest_attempted' => (int)$row['pretest_attempted'],
            'pretest_completed' => (int)$row['pretest_completed'],
            'kuis_attempted' => (int)$row['kuis_attempted'],
            'kuis_completed' => (int)$row['kuis_completed'],
            'posttest_attempted' => (int)$row['posttest_attempted'],
            'posttest_completed' => (int)$row['posttest_completed'],
            'pretest_rate' => round(($row['pretest_completed'] / $registered) * 100, 1),
            'kuis_rate' => round(($row['kuis_completed'] / $registered) * 100, 1),
            'posttest_rate' => round(($row['posttest_completed'] / $registered) * 100, 1),
        ];

        return [
            'status' => 'success',
            'data' => $funnel
        ];
    }

    /**
     * Get score distribution for chart visualization
     * Returns score ranges and counts for pretest, kuis, posttest
     */
    public function getScoreDistribution(): array
    {
        $ranges = [
            ['min' => 0, 'max' => 20, 'label' => '0-20'],
            ['min' => 21, 'max' => 40, 'label' => '21-40'],
            ['min' => 41, 'max' => 60, 'label' => '41-60'],
            ['min' => 61, 'max' => 80, 'label' => '61-80'],
            ['min' => 81, 'max' => 100, 'label' => '81-100'],
        ];

        $distribution = [
            'pretest' => [],
            'kuis' => [],
            'posttest' => [],
        ];

        foreach ($ranges as $range) {
            // Pretest distribution (best score per user)
            $pretestSql = "
                SELECT COUNT(*) as count FROM (
                    SELECT user_id, MAX(nilai) as best_score
                    FROM quiz_results
                    WHERE jenis = 'pre' AND user_id IN (SELECT id FROM users WHERE role = 'user')
                    GROUP BY user_id
                ) AS user_best
                WHERE best_score BETWEEN {$range['min']} AND {$range['max']}
            ";
            $result = $this->conn->query($pretestSql);
            $distribution['pretest'][] = [
                'range' => $range['label'],
                'count' => $result ? (int)$result->fetch_assoc()['count'] : 0
            ];

            // Kuis distribution (average of best scores per quiz per user)
            $kuisSql = "
                SELECT COUNT(*) as count FROM (
                    SELECT user_id, AVG(max_nilai) as avg_best
                    FROM (
                        SELECT user_id, kuis_id, MAX(nilai) as max_nilai
                        FROM quiz_results
                        WHERE jenis = 'kuis' AND kuis_id IS NOT NULL AND user_id IN (SELECT id FROM users WHERE role = 'user')
                        GROUP BY user_id, kuis_id
                    ) AS best_per_quiz
                    GROUP BY user_id
                ) AS user_avg
                WHERE avg_best BETWEEN {$range['min']} AND {$range['max']}
            ";
            $result = $this->conn->query($kuisSql);
            $distribution['kuis'][] = [
                'range' => $range['label'],
                'count' => $result ? (int)$result->fetch_assoc()['count'] : 0
            ];

            // Posttest distribution (best score per user)
            $posttestSql = "
                SELECT COUNT(*) as count FROM (
                    SELECT user_id, MAX(nilai) as best_score
                    FROM quiz_results
                    WHERE jenis = 'post' AND user_id IN (SELECT id FROM users WHERE role = 'user')
                    GROUP BY user_id
                ) AS user_best
                WHERE best_score BETWEEN {$range['min']} AND {$range['max']}
            ";
            $result = $this->conn->query($posttestSql);
            $distribution['posttest'][] = [
                'range' => $range['label'],
                'count' => $result ? (int)$result->fetch_assoc()['count'] : 0
            ];
        }

        return [
            'status' => 'success',
            'data' => $distribution
        ];
    }

    /**
     * Get recent activity for admin dashboard
     * Returns latest user registrations, quiz completions, etc.
     */
    public function getRecentActivity(int $limit = 10): array
    {
        $activities = [];

        // Recent user registrations (ordered by ID since no created_at column)
        $sql = "
            SELECT 'registration' as type, u.name, u.email, u.id as timestamp,
                   CONCAT('Pengguna baru mendaftar: ', u.name) as description
            FROM users u
            WHERE u.role = 'user'
            ORDER BY u.id DESC
            LIMIT ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }

        // Recent quiz completions (pre, kuis, post) - ordered by ID since no created_at column
        $sql = "
            SELECT 'quiz_completion' as type, u.name, qr.jenis, qr.nilai, q.judul as kuis_judul, qr.id as timestamp,
                   CONCAT(u.name, ' menyelesaikan ', qr.jenis, ' ', COALESCE(q.judul, ''), ' dengan nilai ', qr.nilai) as description
            FROM quiz_results qr
            JOIN users u ON qr.user_id = u.id
            LEFT JOIN quizzes q ON qr.kuis_id = q.id
            WHERE u.role = 'user'
            ORDER BY qr.id DESC
            LIMIT ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }

        // Sort all activities by timestamp descending
        usort($activities, function ($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });

        // Limit to requested amount
        $activities = array_slice($activities, 0, $limit);

        return [
            'status' => 'success',
            'data' => $activities
        ];
    }

    /**
     * Get material progress statistics for admin dashboard
     * Returns counts for materials completed, in progress, not started
     */
    public function getMaterialProgressStats(): array
    {
        $stats = [];

        // Total materials
        $sql = "SELECT COUNT(*) as total_materials FROM materials";
        $result = $this->conn->query($sql);
        $stats['total_materials'] = $result ? (int)$result->fetch_assoc()['total_materials'] : 0;

        // Total users
        $sql = "SELECT COUNT(*) as total_users FROM users WHERE role = 'user'";
        $result = $this->conn->query($sql);
        $stats['total_users'] = $result ? (int)$result->fetch_assoc()['total_users'] : 0;

        // Total possible progress records (users x materials)
        $stats['total_possible'] = $stats['total_users'] * $stats['total_materials'];

        // Completed progress records
        $sql = "SELECT COUNT(*) as completed FROM materials_progress WHERE material_selesai = 1";
        $result = $this->conn->query($sql);
        $stats['completed'] = $result ? (int)$result->fetch_assoc()['completed'] : 0;

        // In progress (not completed but has record)
        $sql = "SELECT COUNT(*) as in_progress FROM materials_progress WHERE material_selesai = 0";
        $result = $this->conn->query($sql);
        $stats['in_progress'] = $result ? (int)$result->fetch_assoc()['in_progress'] : 0;

        // Not started (no record in materials_progress)
        $stats['not_started'] = max(0, $stats['total_possible'] - $stats['completed'] - $stats['in_progress']);

        // Completion rate
        $stats['completion_rate'] = $stats['total_possible'] > 0
            ? round(($stats['completed'] / $stats['total_possible']) * 100, 1)
            : 0;

        // Users who completed all materials
        $sql = "
            SELECT COUNT(*) as users_completed_all
            FROM (
                SELECT user_id
                FROM materials_progress mp
                JOIN materials m ON mp.material_id = m.id
                WHERE mp.material_selesai = 1
                GROUP BY mp.user_id
                HAVING COUNT(*) = (SELECT COUNT(*) FROM materials)
            ) AS completed_users
        ";
        $result = $this->conn->query($sql);
        $row = $result ? $result->fetch_assoc() : null;
        $stats['users_completed_all'] = $row && isset($row['users_completed_all']) ? (int)$row['users_completed_all'] : 0;

        // Average progress per user
        $sql = "
            SELECT AVG(user_completed) as avg_progress
            FROM (
                SELECT user_id, COUNT(*) as user_completed
                FROM materials_progress
                WHERE material_selesai = 1
                GROUP BY user_id
            ) AS user_stats
        ";
        $result = $this->conn->query($sql);
        $row = $result ? $result->fetch_assoc() : ['avg_progress' => 0];
        $stats['avg_materials_per_user'] = $row['avg_progress'] ? round($row['avg_progress'], 1) : 0;

        return [
            'status' => 'success',
            'data' => $stats
        ];
    }

    /**
     * Get material progress for a specific user
     * Returns detailed progress for each material
     */
    public function getUserMaterialProgress(int $userId): array
    {
        $sql = "
            SELECT 
                m.id,
                m.judul,
                m.deskripsi,
                m.no_urut,
                mp.material_selesai
            FROM materials m
            LEFT JOIN materials_progress mp ON m.id = mp.material_id AND mp.user_id = ?
            ORDER BY m.no_urut ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $progress = [];
        $completedCount = 0;
        $inProgressCount = 0;
        $notStartedCount = 0;

        while ($row = $result->fetch_assoc()) {
            $status = 'not_started';
            $statusLabel = 'Belum Dimulai';
            $statusClass = 'secondary';

            if ($row['material_selesai'] === '1' || $row['material_selesai'] === 1) {
                $status = 'completed';
                $statusLabel = 'Selesai';
                $statusClass = 'success';
                $completedCount++;
            } elseif ($row['material_selesai'] === '0' || $row['material_selesai'] === 0) {
                $status = 'in_progress';
                $statusLabel = 'Sedang Dipelajari';
                $statusClass = 'warning';
                $inProgressCount++;
            } else {
                $notStartedCount++;
            }

            $progress[] = [
                'id' => (int)$row['id'],
                'judul' => $row['judul'],
                'deskripsi' => $row['deskripsi'],
                'no_urut' => (int)$row['no_urut'],
                'status' => $status,
                'status_label' => $statusLabel,
                'status_class' => $statusClass,
                'updated_at' => null
            ];
        }

        $totalMaterials = count($progress);
        $completionRate = $totalMaterials > 0 ? round(($completedCount / $totalMaterials) * 100, 1) : 0;

        return [
            'status' => 'success',
            'data' => [
                'progress' => $progress,
                'summary' => [
                    'total' => $totalMaterials,
                    'completed' => $completedCount,
                    'in_progress' => $inProgressCount,
                    'not_started' => $notStartedCount,
                    'completion_rate' => $completionRate
                ]
            ]
        ];
    }
}
