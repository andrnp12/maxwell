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
     * Includes passing_grade from quizzes table for status computation
     */
    public function getUserQuizResultsDetail(int $userId): array
    {
        // Get pretest attempts (latest first)
        $pretestSql = "
            SELECT qr.*, q.judul AS kuis_judul, q.passing_grade
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
            SELECT qr.*, q.judul AS kuis_judul, q.passing_grade
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
            SELECT qr.*, q.judul AS kuis_judul, q.jenis AS kuis_jenis, q.passing_grade
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
                    'passing_grade' => $attempt['passing_grade'] ?? 0,
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
    /**
     * Get average course completion rate across all materials
     * Returns overall completion rate and per-material breakdown
     */
    public function getCourseCompletionRate(): array
    {
        // Overall completion rate (same logic as getMaterialProgressStats but focused)
        $sql = "
            SELECT
                COUNT(DISTINCT mp.user_id) as completed_users,
                (SELECT COUNT(*) FROM users WHERE role = 'user') as total_users,
                ROUND(
                    COUNT(DISTINCT mp.user_id) /
                    NULLIF((SELECT COUNT(*) FROM users WHERE role = 'user'), 0) * 100, 1
                ) as overall_completion_rate
            FROM materials_progress mp
            WHERE mp.material_selesai = 1
        ";
        $result = $this->conn->query($sql);
        $overall = $result ? $result->fetch_assoc() : [
            'completed_users' => 0,
            'total_users' => 0,
            'overall_completion_rate' => 0
        ];

        // Per-material completion rates
        $sql = "
            SELECT
                m.id,
                m.judul,
                m.no_urut,
                COUNT(DISTINCT mp.user_id) as completed_users,
                (SELECT COUNT(*) FROM users WHERE role = 'user') as total_users,
                ROUND(
                    COUNT(DISTINCT mp.user_id) /
                    NULLIF((SELECT COUNT(*) FROM users WHERE role = 'user'), 0) * 100, 1
                ) as completion_rate_pct
            FROM materials m
            LEFT JOIN materials_progress mp ON m.id = mp.material_id AND mp.material_selesai = 1
            GROUP BY m.id, m.judul, m.no_urut
            ORDER BY m.no_urut ASC
        ";
        $result = $this->conn->query($sql);
        $byMaterial = [];
        while ($row = $result->fetch_assoc()) {
            $byMaterial[] = $row;
        }

        // Average completion rate across materials
        $avgRate = 0;
        if (!empty($byMaterial)) {
            $sum = array_sum(array_column($byMaterial, 'completion_rate_pct'));
            $avgRate = round($sum / count($byMaterial), 1);
        }

        return [
            'status' => 'success',
            'data' => [
                'overall_completion_rate' => (float)$overall['overall_completion_rate'],
                'avg_completion_rate' => $avgRate,
                'completed_users' => (int)$overall['completed_users'],
                'total_users' => (int)$overall['total_users'],
                'by_material' => $byMaterial
            ]
        ];
    }

    /**
     * Get active learners count for 7 days and 30 days
     * Uses chat timestamps (most reliable) and ID-based proxies for quiz/materials
     */
    public function getActiveLearners(): array
    {
        // Active in last 7 days - based on chat activity (has real timestamps)
        $sql7d = "
            SELECT COUNT(DISTINCT u.id) as active_7d
            FROM users u
            WHERE u.role = 'user'
            AND (
                -- Personal chat in last 7 days
                EXISTS (
                    SELECT 1 FROM chat_konsultan ck
                    WHERE ck.id_user = u.id
                    AND ck.time_stamp >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                )
                OR
                -- Group chat in last 7 days
                EXISTS (
                    SELECT 1 FROM chat_komunitas ck
                    JOIN anggota_komunitas ak ON ck.id_komunitas = ak.id_komunitas
                    WHERE ak.id_user = u.id
                    AND ck.time_stamp >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                )
            )
        ";
        $result = $this->conn->query($sql7d);
        $active7d = $result ? (int)$result->fetch_assoc()['active_7d'] : 0;

        // Active in last 30 days
        $sql30d = "
            SELECT COUNT(DISTINCT u.id) as active_30d
            FROM users u
            WHERE u.role = 'user'
            AND (
                -- Personal chat in last 30 days
                EXISTS (
                    SELECT 1 FROM chat_konsultan ck
                    WHERE ck.id_user = u.id
                    AND ck.time_stamp >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                )
                OR
                -- Group chat in last 30 days
                EXISTS (
                    SELECT 1 FROM chat_komunitas ck
                    JOIN anggota_komunitas ak ON ck.id_komunitas = ak.id_komunitas
                    WHERE ak.id_user = u.id
                    AND ck.time_stamp >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                )
            )
        ";
        $result = $this->conn->query($sql30d);
        $active30d = $result ? (int)$result->fetch_assoc()['active_30d'] : 0;

        // Total users for context
        $totalUsers = (int)$this->conn->query("SELECT COUNT(*) as c FROM users WHERE role = 'user'")->fetch_assoc()['c'];

        return [
            'status' => 'success',
            'data' => [
                'active_7d' => $active7d,
                'active_30d' => $active30d,
                'total_users' => $totalUsers,
                'active_7d_pct' => $totalUsers > 0 ? round($active7d / $totalUsers * 100, 1) : 0,
                'active_30d_pct' => $totalUsers > 0 ? round($active30d / $totalUsers * 100, 1) : 0
            ]
        ];
    }

    /**
     * Get funnel completion rate with drop-off analysis
     * Returns overall funnel completion and biggest drop-off point
     */
    public function getFunnelCompletionRate(): array
    {
        $sql = "
            SELECT
                'Registered' as stage,
                COUNT(*) as users_count,
                100.0 as rate_pct,
                0 as dropoff_from_prev,
                1 as stage_order
            FROM users WHERE role = 'user'

            UNION ALL

            SELECT
                'Pretest Completed' as stage,
                COUNT(DISTINCT qr.user_id) as users_count,
                ROUND(COUNT(DISTINCT qr.user_id) / (SELECT COUNT(*) FROM users WHERE role = 'user') * 100, 1) as rate_pct,
                ROUND((1 - COUNT(DISTINCT qr.user_id) / NULLIF((SELECT COUNT(*) FROM users WHERE role = 'user'), 0)) * 100, 1) as dropoff_from_prev,
                2 as stage_order
            FROM quiz_results qr
            JOIN users u ON qr.user_id = u.id
            WHERE u.role = 'user' AND qr.jenis = 'pre' AND qr.nilai > 0

            UNION ALL

            SELECT
                'At Least 1 Kuis Completed' as stage,
                COUNT(DISTINCT qr.user_id) as users_count,
                ROUND(COUNT(DISTINCT qr.user_id) / (SELECT COUNT(*) FROM users WHERE role = 'user') * 100, 1) as rate_pct,
                ROUND((
                    1 - COUNT(DISTINCT qr.user_id) / NULLIF((
                        SELECT COUNT(DISTINCT qr2.user_id)
                        FROM quiz_results qr2
                        JOIN users u2 ON qr2.user_id = u2.id
                        WHERE u2.role = 'user' AND qr2.jenis = 'pre' AND qr2.nilai > 0
                    ), 0)) * 100, 1) as dropoff_from_prev,
                3 as stage_order
            FROM quiz_results qr
            JOIN users u ON qr.user_id = u.id
            WHERE u.role = 'user' AND qr.jenis = 'kuis' AND qr.nilai > 0

            UNION ALL

            SELECT
                'Posttest Completed' as stage,
                COUNT(DISTINCT qr.user_id) as users_count,
                ROUND(COUNT(DISTINCT qr.user_id) / (SELECT COUNT(*) FROM users WHERE role = 'user') * 100, 1) as rate_pct,
                ROUND((
                    1 - COUNT(DISTINCT qr.user_id) / NULLIF((
                        SELECT COUNT(DISTINCT qr2.user_id)
                        FROM quiz_results qr2
                        JOIN users u2 ON qr2.user_id = u2.id
                        WHERE u2.role = 'user' AND qr2.jenis = 'kuis' AND qr2.nilai > 0
                    ), 0)) * 100, 1) as dropoff_from_prev,
                4 as stage_order
            FROM quiz_results qr
            JOIN users u ON qr.user_id = u.id
            WHERE u.role = 'user' AND qr.jenis = 'post' AND qr.nilai > 0

            ORDER BY stage_order
        ";
        $result = $this->conn->query($sql);
        $funnel = [];
        while ($row = $result->fetch_assoc()) {
            $funnel[] = $row;
        }

        // Overall funnel completion rate (Posttest / Registered)
        $registered = (int)$this->conn->query("SELECT COUNT(*) as c FROM users WHERE role = 'user'")->fetch_assoc()['c'];
        $posttestCompleted = 0;
        foreach ($funnel as $stage) {
            if ($stage['stage'] === 'Posttest Completed') {
                $posttestCompleted = (int)$stage['users_count'];
                break;
            }
        }
        $overallRate = $registered > 0 ? round($posttestCompleted / $registered * 100, 1) : 0;

        // Find biggest drop-off
        $biggestDropoff = ['stage' => '', 'dropoff' => 0];
        foreach ($funnel as $stage) {
            if ($stage['dropoff_from_prev'] > $biggestDropoff['dropoff']) {
                $biggestDropoff = [
                    'stage' => $stage['stage'],
                    'dropoff' => (float)$stage['dropoff_from_prev']
                ];
            }
        }

        return [
            'status' => 'success',
            'data' => [
                'overall_completion_rate' => $overallRate,
                'registered' => $registered,
                'posttest_completed' => $posttestCompleted,
                'funnel_stages' => $funnel,
                'biggest_dropoff' => $biggestDropoff
            ]
        ];
    }

    /**
     * Get unified chronological activity timeline for admin dashboard
     * Merges: registrations, quiz completions, material completions, chat messages, community joins
     * Returns newest first, limited to $limit items
     */
    public function getUnifiedActivityTimeline(int $limit = 20): array
    {
        $activities = [];
        $perSourceLimit = max(50, $limit * 3); // Fetch more from each source to ensure good merge

        // 1. User Registrations (ID as timestamp proxy)
        $sql = "
            SELECT
                'registration' as activity_type,
                u.id as user_id,
                u.name as user_name,
                u.foto as user_avatar,
                u.id as reference_id,
                CONCAT('Pengguna baru mendaftar: ', u.name) as description,
                JSON_OBJECT() as metadata_json,
                u.id as sort_key,
                NULL as created_at
            FROM users u
            WHERE u.role = 'user'
            ORDER BY u.id DESC
            LIMIT ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $perSourceLimit);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }

        // 2. Quiz Completions (pre, kuis, post, tryout)
        $sql = "
            SELECT
                'quiz_completion' as activity_type,
                u.id as user_id,
                u.name as user_name,
                u.foto as user_avatar,
                qr.id as reference_id,
                CONCAT(u.name, ' menyelesaikan ',
                    CASE qr.jenis
                        WHEN 'pre' THEN 'Pretest'
                        WHEN 'post' THEN 'Posttest'
                        WHEN 'tryout' THEN 'Tryout'
                        ELSE 'Kuis'
                    END,
                    ' ', COALESCE(q.judul, ''),
                    ' dengan nilai ', qr.nilai
                ) as description,
                JSON_OBJECT(
                    'jenis', qr.jenis,
                    'nilai', qr.nilai,
                    'kuis_id', qr.kuis_id,
                    'kuis_judul', q.judul
                ) as metadata_json,
                qr.id as sort_key,
                NULL as created_at
            FROM quiz_results qr
            JOIN users u ON qr.user_id = u.id
            LEFT JOIN quizzes q ON qr.kuis_id = q.id
            WHERE u.role = 'user'
            ORDER BY qr.id DESC
            LIMIT ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $perSourceLimit);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }

        // 3. Material Completions
        $sql = "
            SELECT
                'material_completion' as activity_type,
                u.id as user_id,
                u.name as user_name,
                u.foto as user_avatar,
                mp.id as reference_id,
                CONCAT(u.name, ' menyelesaikan materi: ', m.judul) as description,
                JSON_OBJECT(
                    'material_id', m.id,
                    'material_judul', m.judul,
                    'no_urut', m.no_urut
                ) as metadata_json,
                mp.id as sort_key,
                NULL as created_at
            FROM materials_progress mp
            JOIN users u ON mp.user_id = u.id
            JOIN materials m ON mp.material_id = m.id
            WHERE u.role = 'user' AND mp.material_selesai = 1
            ORDER BY mp.id DESC
            LIMIT ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $perSourceLimit);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }

        // 6. Community Joins
        $sql = "
            SELECT
                'community_join' as activity_type,
                u.id as user_id,
                u.name as user_name,
                u.foto as user_avatar,
                ak.id as reference_id,
                CONCAT(u.name, ' bergabung ke komunitas ', ko.nama_komunitas) as description,
                JSON_OBJECT(
                    'komunitas_id', ko.id,
                    'komunitas_name', ko.nama_komunitas
                ) as metadata_json,
                UNIX_TIMESTAMP(ak.joined_at) as sort_key,
                ak.joined_at as created_at
            FROM anggota_komunitas ak
            JOIN users u ON ak.id_user = u.id
            JOIN komunitas ko ON ak.id_komunitas = ko.id
            WHERE u.role = 'user'
            ORDER BY ak.joined_at DESC
            LIMIT ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $perSourceLimit);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }

        // Sort all activities by sort_key descending (newest first)
        usort($activities, function ($a, $b) {
            return (int)$b['sort_key'] - (int)$a['sort_key'];
        });

        // Limit to requested amount
        $activities = array_slice($activities, 0, $limit);

        // Normalize timestamp for display
        foreach ($activities as &$activity) {
            if ($activity['created_at']) {
                $activity['timestamp'] = $activity['created_at'];
            } else {
                // For ID-based activities, use a placeholder - will show as "Baru saja" in UI
                $activity['timestamp'] = date('Y-m-d H:i:s');
            }
        }

        return [
            'status' => 'success',
            'data' => $activities
        ];
    }

    /**
     * Compute attempt status based on score, passing grade, and completion
     *
     * @param float|null $score The user's score (null if not submitted)
     * @param int $passingGrade The passing grade (KKM) threshold
     * @param bool $isCompleted Whether the attempt was completed/submitted
     * @return array ['label' => string, 'class' => string] Badge label and CSS class
     */
    public static function getAttemptStatus(?float $score, int $passingGrade, bool $isCompleted): array
    {
        // Default passing grade to 0 if null
        $passingGrade = $passingGrade ?? 0;

        if (!$isCompleted || $score === null) {
            return [
                'label' => 'Belum Selesai',
                'class' => 'bg-secondary'
            ];
        }

        if ($score >= $passingGrade) {
            return [
                'label' => 'Lulus',
                'class' => 'bg-success'
            ];
        }

        return [
            'label' => 'Gagal',
            'class' => 'bg-danger'
        ];
    }

    /**
     * Check if user is eligible to download certificate
     * Replicates exact logic from pages/user/index.php for progress calculation
     *
     * @param int $userId
     * @return array [
     *     'eligible' => bool,
     *     'progress_percent' => float,
     *     'missing_requirements' => array<string>,
     *     'details' => array (component breakdown)
     * ]
     */
    public function canDownloadCertificate(int $userId): array
    {
        // Reuse existing methods to avoid code duplication
        $userResult = $this->getUserWithQuizResults($userId);
        $quizDetail = $this->getUserQuizResultsDetail($userId);
        $materialProgress = $this->getUserMaterialProgress($userId);

        $user = $userResult['data'] ?? null;
        if (!$user) {
            return [
                'eligible' => false,
                'progress_percent' => 0,
                'missing_requirements' => ['User not found'],
                'details' => []
            ];
        }

        // Extract counts (mirroring index.php logic)
        $pretestAttempts  = (int)($user['pretest_attempts'] ?? 0);
        $kuisAttempts     = (int)($user['kuis_attempts'] ?? 0);
        $posttestAttempts = (int)($user['posttest_attempts'] ?? 0);

        $matCompleted    = $materialProgress['data']['summary']['completed'] ?? 0;
        $matInProgress   = $materialProgress['data']['summary']['in_progress'] ?? 0;
        $matTotal        = $materialProgress['data']['summary']['total'] ?? 0;

        // Get total kuis from database
        $allKuisResult = $this->conn->query("SELECT id FROM quizzes WHERE jenis = 'kuis'");
        $allKuis = $allKuisResult ? $allKuisResult->fetch_all(MYSQLI_ASSOC) : [];
        $totalKuis = count($allKuis);
        $completedKuis = count($quizDetail['data']['kuis'] ?? []);

        // Compute totals
        $totalActivities  = $matTotal + 1 + $totalKuis + 1;
        $completedActivities = ($matCompleted + $matInProgress)
            + ($pretestAttempts > 0 ? 1 : 0)
            + $completedKuis
            + ($posttestAttempts > 0 ? 1 : 0);

        $progressPercent = $totalActivities > 0
            ? round(($completedActivities / $totalActivities) * 100, 1)
            : 0;

        // Identify missing requirements
        $missing = [];
        if ($matCompleted + $matInProgress < $matTotal) {
            $missing[] = "Materi: " . ($matTotal - $matCompleted - $matInProgress) . " modul belum dimulai";
        }
        if ($pretestAttempts === 0) {
            $missing[] = "Pre-Test: Belum dikerjakan";
        }
        if ($completedKuis < $totalKuis) {
            $missing[] = "Kuis: " . ($totalKuis - $completedKuis) . " kuis belum selesai";
        }
        if ($posttestAttempts === 0) {
            $missing[] = "Post-Test: Belum dikerjakan";
        }

        return [
            'eligible' => $progressPercent >= 100.0,
            'progress_percent' => $progressPercent,
            'missing_requirements' => $missing,
            'details' => [
                'materi' => ['completed' => $matCompleted + $matInProgress, 'total' => $matTotal],
                'pretest' => ['completed' => $pretestAttempts > 0 ? 1 : 0, 'total' => 1],
                'kuis' => ['completed' => $completedKuis, 'total' => $totalKuis],
                'posttest' => ['completed' => $posttestAttempts > 0 ? 1 : 0, 'total' => 1],
            ]
        ];
    }
}
