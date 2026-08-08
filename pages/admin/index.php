<?php
require_once '../../src/classes/auth.php';
require_once '../../src/classes/user.php';
require_once '../../src/classes/materi.php';
require_once '../../src/classes/kuis.php';
require_once '../../src/classes/konselor.php';
require_once '../../src/classes/komunitas.php';
require_once '../../src/classes/chatV2.php';

$auth = new auth();
$auth->authOrNot();

// Initialize models
$userModel = new User();
$materiModel = new Materi();
$kuisModel = new Kuis();
$konselorModel = new Konsultan();
$komunitasModel = new Komunitas();
$chatModel = new ChatV2();

// Fetch all dashboard data
$dashboardStats = $userModel->getDashboardStats();
$learningFunnel = $userModel->getLearningFunnel();
$scoreDistribution = $userModel->getScoreDistribution();
$chatStats = $chatModel->getAdminDashboardStats();
$materialProgress = $userModel->getMaterialProgressStats();

// NEW: Fetch new metrics
$courseCompletion = $userModel->getCourseCompletionRate();
$unifiedActivity = $userModel->getUnifiedActivityTimeline(20);

// Fetch counts for metric cards
$totalMateri = count($materiModel->getAllMateri());
$totalKuis = count($kuisModel->getAllKuis());
$totalKonselor = count($konselorModel->getAllKonsultan());
$totalKomunitas = count($komunitasModel->getAllKomunitasAdmin());

// Kuis breakdown by type
$kuisByType = ['kuis' => 0, 'pretest' => 0, 'posttest' => 0, 'tryout' => 0];
foreach ($kuisModel->getAllKuis() as $k) {
    $jenis = $k['jenis'] ?? 'kuis';
    if (isset($kuisByType[$jenis])) {
        $kuisByType[$jenis]++;
    } else {
        $kuisByType[$jenis] = 1;
    }
}

$stats = $dashboardStats['data'] ?? [];
$funnel = $learningFunnel['data'] ?? [];
$distribution = $scoreDistribution['data'] ?? [];
$chatData = $chatStats['data'] ?? [];
$matProgress = $materialProgress['data'] ?? [];
$courseData = $courseCompletion['data'] ?? [];
$activities = $unifiedActivity['data'] ?? [];
?>

<!--header start-->
<?php include('../include/header.php'); ?>
<!--header end-->

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- ========== Topbar Start ========== -->
        <?php include('../include/topbar.php'); ?>
        <!-- ========== Topbar End ========== -->
        <!-- ========== Left Sidebar Start ========== -->
        <?php include('../include/sidebar-admin.php'); ?>
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="row d-sm-flex justify-content-between">
                                <div>
                                    <h4 class="mb-sm-0 font-weight-bold mb-1">
                                        Dashboard Administrator
                                    </h4>
                                    <p class="text-muted">
                                        Ringkasan aktivitas dan statistik platform
                                    </p>
                                </div>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm" onclick="refreshDashboard()">
                                        <i class="mdi mdi-refresh"></i> Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- ============================================================== -->
                    <!-- ROW 1: KEY METRIC CARDS -->
                    <!-- ============================================================== -->
                    <div class="row g-lg-4">
                        <!-- Total Pengguna -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-h-100">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-7">
                                            <span class="text-muted mb-3 lh-1 d-block text-truncate">
                                                Total Pengguna
                                            </span>
                                            <h3 class="mb-1 counter-value" data-target="<?= $stats['total_users'] ?? 0 ?>">
                                                0
                                            </h3>
                                            <span class="badge bg-success">
                                                <i class="mdi mdi-account-check"></i> <?= $stats['active_users'] ?? 0 ?> Aktif
                                            </span>
                                        </div>
                                        <div class="col-5 text-end">
                                            <i class="mdi mdi-account-group text-primary display-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->

                        <!-- Materi & Konten -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-h-100">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-7">
                                            <span class="text-muted mb-3 lh-1 d-block text-truncate">
                                                Materi & Kuis
                                            </span>
                                            <h3 class="mb-1">
                                                <?= $totalMateri ?>
                                            </h3>
                                            <small class="text-muted">
                                                <?= $totalKuis ?> Kuis
                                                (<?= $kuisByType['kuis'] ?? 0 ?> Kuis,
                                                <?= $kuisByType['pretest'] ?? 0 ?> Pretest,
                                                <?= $kuisByType['posttest'] ?? 0 ?> Posttest)
                                            </small>
                                        </div>
                                        <div class="col-5 text-end">
                                            <i class="mdi mdi-book-open-variant text-success display-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->

                        <!-- Konselor & Komunitas -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-h-100">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-7">
                                            <span class="text-muted mb-3 lh-1 d-block text-truncate">
                                                Konselor & Komunitas
                                            </span>
                                            <h3 class="mb-1">
                                                <?= $totalKonselor ?>
                                            </h3>
                                            <small class="text-muted">
                                                <?= $totalKonselor ?> Konselor &nbsp;|&nbsp;
                                                <?= $totalKomunitas ?> Komunitas
                                            </small>
                                        </div>
                                        <div class="col-5 text-end">
                                            <i class="mdi mdi-account-tie text-warning display-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->

                        <!-- Course Completion Rate (replaces Progress Materi) -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-h-100">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-7">
                                            <span class="text-muted mb-3 lh-1 d-block text-truncate">
                                                Course Completion Rate
                                            </span>
                                            <h3 class="mb-1">
                                                <span class="counter-value" data-target="<?= $courseData['avg_completion_rate'] ?? 0 ?>">0</span>
                                                <span class="text-muted fw-normal">%</span>
                                            </h3>
                                            <small class="text-muted">
                                                Rata-rata: <?= $courseData['avg_completion_rate'] ?? 0 ?>% |
                                                <?= $courseData['completed_users'] ?? 0 ?> / <?= $courseData['total_users'] ?? 0 ?> pengguna
                                            </small>
                                        </div>
                                        <div class="col-5 text-end">
                                            <i class="mdi mdi-chart-line text-success display-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                    <!-- ============================================================== -->
                    <!-- ROW 2: LEARNING PROGRESS OVERVIEW -->
                    <!-- ============================================================== -->
                    <div class="row g-4">
                        <!-- Score Distribution Chart -->
                        <div class="col-xl-8">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Distribusi Nilai Pengguna</h4>
                                    <p class="card-title-desc">Perbandingan nilai Pretest, Kuis Rata-rata, dan Posttest</p>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="flex-grow-1">
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <canvas id="scoreDistributionChart" height="250"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row text-center mt-auto g-2">
                                        <div class="col-12 col-md-4">
                                            <div class="badge bg-primary p-2 w-100" style="font-size: 0.8rem;">
                                                <i class="mdi mdi-chart-bar"></i> Pretest (Rata-rata: <?= $stats['avg_pretest'] ?? 0 ?>)
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="badge bg-success p-2 w-100" style="font-size: 0.8rem;">
                                                <i class="mdi mdi-chart-line"></i> Kuis Rata-rata (Rata-rata: <?= $stats['avg_kuis'] ?? 0 ?>)
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="badge bg-info p-2 w-100" style="font-size: 0.8rem;">
                                                <i class="mdi mdi-chart-areaspline"></i> Posttest (Rata-rata: <?= $stats['avg_posttest'] ?? 0 ?>)
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->

                        <!-- Learning Funnel -->
                        <div class="col-xl-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Jalur Pembelajaran (Funnel)</h4>
                                    <p class="card-title-desc">Progres pengguna dari daftar hingga posttest</p>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $registered = $funnel['registered'] ?? 0;
                                    $pretestDone = $funnel['pretest_completed'] ?? 0;
                                    $kuisDone = $funnel['kuis_completed'] ?? 0;
                                    $posttestDone = $funnel['posttest_completed'] ?? 0;
                                    $base = max($registered, 1);
                                    ?>

                                    <div class="funnel-step mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fw-bold">Terdaftar</span>
                                            <span class="text-primary"><?= $registered ?></span>
                                        </div>
                                        <div class="progress" style="height: 12px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                                        </div>
                                    </div>

                                    <div class="funnel-step mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Pretest Selesai</span>
                                            <span class="text-success"><?= $pretestDone ?> (<?= $funnel['pretest_rate'] ?? 0 ?>%)</span>
                                        </div>
                                        <div class="progress" style="height: 12px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $funnel['pretest_rate'] ?? 0 ?>%"></div>
                                        </div>
                                    </div>

                                    <div class="funnel-step mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Kuis Selesai</span>
                                            <span class="text-info"><?= $kuisDone ?> (<?= $funnel['kuis_rate'] ?? 0 ?>%)</span>
                                        </div>
                                        <div class="progress" style="height: 12px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: <?= $funnel['kuis_rate'] ?? 0 ?>%"></div>
                                        </div>
                                    </div>

                                    <div class="funnel-step mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Posttest Selesai</span>
                                            <span class="text-warning"><?= $posttestDone ?> (<?= $funnel['posttest_rate'] ?? 0 ?>%)</span>
                                        </div>
                                        <div class="progress" style="height: 12px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $funnel['posttest_rate'] ?? 0 ?>%"></div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row text-center mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Total Percobaan</small>
                                            <strong><?= $stats['total_attempts'] ?? 0 ?></strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Rata-rata per User</small>
                                            <strong><?= $registered > 0 ? round($stats['total_attempts'] / $registered, 1) : 0 ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                    <!-- ============================================================== -->
                    <!-- ROW 3: ENGAGEMENT & ACTIVITY -->
                    <!-- ============================================================== -->
                    <div class="row mt-1 g-4">
                        <!-- Recent Activity Feed -->
                        <div class="col-xl-8 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex align-items-center justify-content-between sticky-top bg-white border-bottom" style="z-index: 1;">
                                    <h4 class="card-title mb-0">Aktivitas Terbaru</h4>
                                    <a href="user.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                                </div>
                                <div class="card-body p-0 d-flex flex-column">
                                    <div class="activity-list flex-grow-1 overflow-auto" style="max-height: 50vh;">
                                        <?php if (!empty($activities)): ?>
                                            <div class="list-group list-group-flush">
                                                <?php foreach ($activities as $activity): ?>
                                                    <?php
                                                    // New unified activity structure uses 'activity_type' instead of 'type'
                                                    $activityType = $activity['activity_type'] ?? ($activity['type'] ?? '');
                                                    $meta = json_decode($activity['metadata_json'] ?? '{}', true);
                                                    $jenis = $meta['jenis'] ?? $activity['jenis'] ?? '';
                                                    $jenisNormalized = strtolower(trim($jenis));

                                                    // Icon mapping for all activity types
                                                    $iconMap = [
                                                        'registration' => 'mdi-account-plus text-primary',
                                                        'quiz_completion' => match ($jenisNormalized) {
                                                            'pre', 'pretest' => 'mdi-checkbox-marked-circle text-success',
                                                            'post', 'posttest' => 'mdi-flag-checkered text-warning',
                                                            'tryout' => 'mdi-trophy text-danger',
                                                            default => 'mdi-file-check text-info',
                                                        },
                                                        'material_completion' => 'mdi-book-check text-success',
                                                        'chat_personal' => 'mdi-message-text text-primary',
                                                        'chat_group' => 'mdi-account-group text-info',
                                                        'community_join' => 'mdi-account-multiple-plus text-purple',
                                                    ];
                                                    $icon = $iconMap[$activityType] ?? 'mdi-information text-muted';

                                                    // Time ago helper
                                                    $timeAgo = '';
                                                    if (!empty($activity['timestamp'])) {
                                                        $now = new DateTime();
                                                        $then = new DateTime($activity['timestamp']);
                                                        $diff = $now->getTimestamp() - $then->getTimestamp();
                                                        if ($diff < 60) $timeAgo = 'Baru saja';
                                                        elseif ($diff < 3600) $timeAgo = floor($diff / 60) . ' menit lalu';
                                                        elseif ($diff < 86400) $timeAgo = floor($diff / 3600) . ' jam lalu';
                                                        elseif ($diff < 604800) $timeAgo = floor($diff / 86400) . ' hari lalu';
                                                        else $timeAgo = $then->format('d M Y');
                                                    } else {
                                                        $timeAgo = 'Baru saja';
                                                    }
                                                    ?>
                                                    <div class="list-group-item px-3 py-2 border-0 border-bottom">
                                                        <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 gap-md-3">
                                                            <i class="mdi <?= $icon ?> fs-4 flex-shrink-0"></i>
                                                            <div class="flex-grow-1 min-w-0 text-md-start">
                                                                <p class="mb-1 fw-medium small text-truncate d-inline-block w-100"><?= htmlspecialchars($activity['description'] ?? 'Aktivitas') ?></p>
                                                                <small class="text-muted d-flex flex-wrap gap-2 align-items-center">
                                                                    <span class="badge bg-light text-dark text-uppercase"><?= str_replace('_', ' ', $activityType) ?></span>
                                                                    <span><?= $timeAgo ?></span>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center py-4">
                                                <i class="mdi mdi-information-outline display-4 text-muted"></i>
                                                <p class="text-muted mt-2 mb-0">Belum ada aktivitas baru</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->

                        <!-- Chat Stats, Quick Actions & Alerts -->
                        <div class="col-xl-4">
                            <div class="d-flex flex-column h-100 gap-1">
                                <!-- Chat Statistics Card -->
                                <div class="card flex-grow-1">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Statistik Chat</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center mb-3">
                                            <div class="col-6">
                                                <h3 class="text-primary mb-0 counter-value" data-target="<?= $chatData['unique_users_chatted'] ?? 0 ?>">0</h3>
                                                <small class="text-muted">Pengguna (Konseling)</small>
                                            </div>
                                            <div class="col-6">
                                                <h3 class="text-success mb-0 counter-value" data-target="<?= $chatData['unique_counselors_chatted'] ?? 0 ?>">0</h3>
                                                <small class="text-muted">Konselor</small>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row text-center mb-2">
                                            <div class="col-6">
                                                <h4 class="text-info mb-0 counter-value" data-target="<?= $chatData['messages_7d'] ?? 0 ?>">0</h4>
                                                <small class="text-muted">Pesan Konseling 7H</small>
                                            </div>
                                            <div class="col-6">
                                                <h4 class="text-warning mb-0 counter-value" data-target="<?= $chatData['active_conversations_7d'] ?? 0 ?>">0</h4>
                                                <small class="text-muted">Chat Konseling Aktif</small>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <h5 class="text-primary mb-0 counter-value" data-target="<?= $chatData['group_conversations'] ?? 0 ?>">0</h5>
                                                <small class="text-muted">Komunitas Aktif</small>
                                            </div>
                                            <div class="col-6">
                                                <h5 class="text-success mb-0 counter-value" data-target="<?= $chatData['group_messages'] ?? 0 ?>">0</h5>
                                                <small class="text-muted">Pesan Komunitas</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alerts & Suggestions Card -->
                                <?php
                                // Check for alerts
                                $alerts = [];
                                if (($stats['total_users'] ?? 0) > 0 && ($funnel['pretest_rate'] ?? 0) < 50) {
                                    $alerts[] = ['type' => 'warning', 'icon' => 'mdi-alert-circle', 'title' => 'Partisipasi Pretest Rendah', 'message' => 'Hanya ' . ($funnel['pretest_rate'] ?? 0) . '% pengguna yang menyelesaikan pretest. Pertimbangkan pengingat atau insentif.'];
                                }
                                if (($totalMateri ?? 0) > 0) {
                                    $materiWithQuiz = 0;
                                    foreach ($materiModel->getAllMateri() as $m) {
                                        // Check if materi has associated quiz
                                        $check = $kuisModel->getAllKuis();
                                        foreach ($check as $k) {
                                            if (($k['material_id'] ?? null) == $m['id']) {
                                                $materiWithQuiz++;
                                                break;
                                            }
                                        }
                                    }
                                    if ($materiWithQuiz < $totalMateri) {
                                        $alerts[] = ['type' => 'info', 'icon' => 'mdi-book-alert', 'title' => 'Materi Tanpa Kuis', 'message' => ($totalMateri - $materiWithQuiz) . ' dari ' . $totalMateri . ' materi belum memiliki kuis pendamping.'];
                                    }
                                }
                                if (($chatData['active_conversations_7d'] ?? 0) === 0 && ($chatData['total_conversations'] ?? 0) > 0) {
                                    $alerts[] = ['type' => 'danger', 'icon' => 'mdi-message-off', 'title' => 'Tidak Ada Chat Aktif', 'message' => 'Tidak ada percakapan konseling aktif dalam 7 hari terakhir.'];
                                }
                                ?>
                                <?php if (!empty($alerts)): ?>
                                    <div class="card flex-grow-1 border-0 shadow-sm">
                                        <div class="card-header bg-light">
                                            <h4 class="card-title mb-0"><i class="mdi mdi-bell-ring text-warning me-2"></i>Peringatan & Saran</h4>
                                        </div>
                                        <div class="card-body">
                                            <?php foreach ($alerts as $alert): ?>
                                                <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show mb-3" role="alert">
                                                    <div class="d-flex align-items-start">
                                                        <i class="mdi <?= $alert['icon'] ?> me-3 mt-1 fs-4"></i>
                                                        <div>
                                                            <strong><?= $alert['title'] ?></strong>
                                                            <p class="mb-0 mt-1 small"><?= $alert['message'] ?></p>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <!-- Footer Start -->
            <?php include("../include/footer.php"); ?>
            <!-- end Footer -->
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <!-- Right Sidebar -->
    <?php include("../include/right-sidebar.php"); ?>
    <!-- /Right-bar -->
    <!-- javascript -->
    <?php include("../include/script.php"); ?>
    <!-- end javascript -->

    <!-- Chart.js for dashboard charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <script>
        // ============================================
        // COUNTER ANIMATION
        // ============================================
        function animateCounters() {
            document.querySelectorAll('.counter-value').forEach(counter => {
                const target = parseFloat(counter.getAttribute('data-target')) || 0;
                const duration = 1500;
                const step = target / (duration / 16);
                let current = 0;

                const updateCounter = () => {
                    current += step;
                    if (current >= target) {
                        counter.textContent = Number.isInteger(target) ? target : target.toFixed(1);
                    } else {
                        counter.textContent = Number.isInteger(target) ? Math.floor(current) : current.toFixed(1);
                        requestAnimationFrame(updateCounter);
                    }
                };

                // Start animation when element is in viewport
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            updateCounter();
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.5
                });

                observer.observe(counter);
            });
        }

        // ============================================
        // SCORE DISTRIBUTION CHART
        // ============================================
        function initScoreDistributionChart() {
            const ctx = document.getElementById('scoreDistributionChart');
            if (!ctx) return;

            // Prepare data from PHP
            const distributionData = {
                labels: [
                    <?php foreach ($distribution['pretest'] as $i => $d): ?> '<?= $d['range'] ?>'
                        <?= $i < count($distribution['pretest']) - 1 ? ',' : '' ?>
                    <?php endforeach; ?>
                ],
                datasets: [{
                        label: 'Pretest',
                        data: [
                            <?php foreach ($distribution['pretest'] as $i => $d): ?>
                                <?= $d['count'] ?><?= $i < count($distribution['pretest']) - 1 ? ',' : '' ?>
                            <?php endforeach; ?>
                        ],
                        backgroundColor: 'rgba(81, 86, 190, 0.7)',
                        borderColor: 'rgba(81, 86, 190, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Kuis Rata-rata',
                        data: [
                            <?php foreach ($distribution['kuis'] as $i => $d): ?>
                                <?= $d['count'] ?><?= $i < count($distribution['kuis']) - 1 ? ',' : '' ?>
                            <?php endforeach; ?>
                        ],
                        backgroundColor: 'rgba(52, 195, 143, 0.7)',
                        borderColor: 'rgba(52, 195, 143, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Posttest',
                        data: [
                            <?php foreach ($distribution['posttest'] as $i => $d): ?>
                                <?= $d['count'] ?><?= $i < count($distribution['posttest']) - 1 ? ',' : '' ?>
                            <?php endforeach; ?>
                        ],
                        backgroundColor: 'rgba(57, 175, 209, 0.7)',
                        borderColor: 'rgba(57, 175, 209, 1)',
                        borderWidth: 1
                    }
                ]
            };

            new Chart(ctx, {
                type: 'bar',
                data: distributionData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.7)',
                            padding: 10,
                            titleFont: {
                                size: 13
                            },
                            bodyFont: {
                                size: 12
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // ============================================
        // REFRESH DASHBOARD
        // ============================================
        function refreshDashboard() {
            location.reload();
        }

        // ============================================
        // INIT ON DOCUMENT READY
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            animateCounters();
            initScoreDistributionChart();
        });
    </script>

</body>

</html>