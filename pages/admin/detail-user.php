<?php
require_once '../../src/classes/auth.php';
require_once '../../src/classes/user.php';
require_once '../../src/classes/kuis.php';

$auth = new auth();
$auth->authOrNot();

$userId = (int)($_GET['id'] ?? 0);

$userModel = new User();
$kuisModel = new Kuis();

// Get user basic info
$userResult = $userModel->getUserWithQuizResults($userId);
$user = $userResult['data'] ?? null;

// Get detailed quiz results
$quizDetailResult = $userModel->getUserQuizResultsDetail($userId);
$quizDetail = $quizDetailResult['data'] ?? ['pretest' => [], 'posttest' => [], 'kuis' => []];

// Get user material progress
$materialProgressResult = $userModel->getUserMaterialProgress($userId);
$materialProgress = $materialProgressResult['data'] ?? ['progress' => [], 'summary' => ['total' => 0, 'completed' => 0, 'in_progress' => 0, 'not_started' => 0, 'completion_rate' => 0]];

// Calculate materi stats for badge
$materiDikerjakan = $materialProgress['summary']['completed'] + $materialProgress['summary']['in_progress'];

// Material progress data
$matCompleted = $materialProgress['summary']['completed'] ?? 0;
$matInProgress = $materialProgress['summary']['in_progress'] ?? 0;
$matNotStarted = $materialProgress['summary']['not_started'] ?? 0;
$matTotal = $materialProgress['summary']['total'] ?? 0;

// Quiz attempts count
$pretestAttempts = (int)($user['pretest_attempts'] ?? 0);
$kuisAttempts = (int)($user['kuis_attempts'] ?? 0);
$posttestAttempts = (int)($user['posttest_attempts'] ?? 0);

// Get total available activities in system
// For Kuis, use only quiz-type (jenis='kuis'), excluding pretest/posttest
$allKuisOnly = $kuisModel->getAllKuisOnly();
$totalKuis = count($allKuisOnly);
$totalMateri = $matTotal;
$totalPretest = 1;  // Assuming 1 pretest exists
$totalPosttest = 1; // Assuming 1 posttest exists

// Calculate completed activities by user
// Materi: completed + in_progress (user has started/finished)
$completedMateri = $matCompleted + $matInProgress;

// Pre-test: 1 if attempted, 0 otherwise
$completedPretest = $pretestAttempts > 0 ? 1 : 0;

// Kuis: number of unique kuis attempted by user (from quizDetail)
$completedKuis = count($quizDetail['kuis'] ?? []);

// Post-test: 1 if attempted, 0 otherwise
$completedPosttest = $posttestAttempts > 0 ? 1 : 0;

// Calculate completion-based progress (0-100%)
$totalActivities = $totalMateri + $totalPretest + $totalKuis + $totalPosttest;
$completedActivities = $completedMateri + $completedPretest + $completedKuis + $completedPosttest;

$combinedProgress = $totalActivities > 0 ? round(($completedActivities / $totalActivities) * 100, 1) : 0;

// Also keep individual completion rates for breakdown
$pretestCompletion = $pretestAttempts > 0 ? 100 : 0;
$kuisCompletion = $totalKuis > 0 ? round(($completedKuis / $totalKuis) * 100, 1) : 0;
$posttestCompletion = $posttestAttempts > 0 ? 100 : 0;
$materiCompletion = $matTotal > 0 ? $materialProgress['summary']['completion_rate'] : 0;

// Determine user status (active if has any activity)
$userStatus = ($pretestAttempts > 0 || $kuisAttempts > 0 || $posttestAttempts > 0 || $materiDikerjakan > 0) ? 'Aktif' : 'Nonaktif';
$userStatusClass = ($userStatus === 'Aktif') ? 'bg-success' : 'bg-secondary';

if (!$user) {
    // Redirect or show error
    header('Location: user.php');
    exit;
}
?>

<!--header start-->
<?php include('../include/header.php'); ?>
<!--header end-->

<body>
    <!-- <body data-layout="horizontal"> -->
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
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-4">
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="user.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Detail Ringkasan Pengguna
                                    </h4>
                                    <p class="text-muted mb-0">
                                        Lihat detail ringkasan web dari pengguna.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Row 1: User Detail Card + Progress Chart Card -->
                    <div class="row align-items-stretch">
                        <!-- User Detail Card -->
                        <div class="col-lg-7">
                            <div class="card h-100">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title mb-0">Detail Pengguna</h5>
                                    <span class="badge <?= $userStatusClass ?>"><?= $userStatus ?></span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <!-- Profile Header: Avatar above name/email vertically -->
                                    <div class="d-flex flex-column align-items-center text-center mb-4">
                                        <div class="avatar-xxl mb-3">
                                            <img alt="" class="img-fluid rounded-circle d-block"
                                                src="<?= !empty($user['foto']) ? '/uploads/profile/' . htmlspecialchars($user['foto']) : '/assets/images/users/avatar-2.jpg' ?>"
                                                style="width: 120px; height: 120px; object-fit: cover;" />
                                        </div>
                                        <div>
                                            <h4 class="mb-1"><?= htmlspecialchars($user['name'] ?? '-') ?></h4>
                                            <p class="text-muted small mb-0"><?= htmlspecialchars($user['email'] ?? '-') ?></p>
                                        </div>
                                    </div>

                                    <!-- About User -->
                                    <div class="mt-auto">
                                        <h6 class="pt-2">Tentang Pengguna</h6>
                                        <div class="text-muted mt-2">
                                            <p class="mb-0"><?= htmlspecialchars($user['deskripsi'] ?? 'Tidak ada deskripsi.') ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Chart Card -->
                        <div class="col-lg-5 mt-4 mt-lg-0">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Progres Belajar</h5>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="apex-charts w-100" id="learning-progress-chart">
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3" id="learning-progress-detail">
                                            <!-- Detail breakdown injected here by JavaScript -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Riwayat Pengguna Card -->
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        Riwayat Pengguna
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row d-flex">
                                        <div class="col-lg-6 flex-wrap mb-2">
                                            <h6 class="mb-0">
                                                Pembelajaran
                                            </h6>
                                            <span class="badge bg-primary rounded-pill my-2 py-1">
                                                <i class="mdi mdi-check-circle"></i>
                                                <?= $materiDikerjakan ?> Materi dikerjakan
                                            </span>
                                            <h6 class="mb-0 text-muted">
                                                Materi terakhir yang dipelajari
                                            </h6>
                                            <div class="card mt-2">
                                                <div class="row g-0 align-items-center">
                                                    <div class="col-3 text-center">
                                                        <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items:center; justify-content: center;">
                                                            <img src="/assets/icon/focus-group.webp" alt="icon" style="width: 40px; height: 40px;">
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="card-body">
                                                            <h5 class="card-title mb-0 font-weight-bold">
                                                                <?php
                                                                $lastStudied = null;
                                                                if (!empty($materialProgress['progress'])) {
                                                                    foreach ($materialProgress['progress'] as $mat) {
                                                                        if ($mat['status'] === 'completed' || $mat['status'] === 'in_progress') {
                                                                            $lastStudied = $mat;
                                                                            break;
                                                                        }
                                                                    }
                                                                    echo $lastStudied ? htmlspecialchars($lastStudied['judul']) : 'Belum ada materi dipelajari';
                                                                } else {
                                                                    echo 'Belum ada data';
                                                                }
                                                                ?>
                                                            </h5>
                                                            <p class="card-text">
                                                                <small class="text-muted">
                                                                    <?php if ($lastStudied): ?>
                                                                        Status: <span class="badge bg-<?= $lastStudied['status_class'] ?>"><?= $lastStudied['status_label'] ?></span>
                                                                    <?php else: ?>
                                                                        Belum ada riwayat materi
                                                                    <?php endif; ?>
                                                                </small>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 flex-wrap mb-2">
                                            <h6 class="mb-0">
                                                Kuis
                                            </h6>
                                            <span class="badge bg-primary rounded-pill my-2 py-1">
                                                <i class="mdi mdi-check-circle"></i>
                                                <?= count($quizDetail['kuis']) ?> Kuis dikerjakan
                                            </span>
                                            <h6 class="mb-0 text-muted">
                                                Kuis terakhir yang dikerjakan
                                            </h6>
                                            <div class="card mt-2">
                                                <div class="row g-0 align-items-center">
                                                    <div class="col-3 text-center">
                                                        <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items:center; justify-content: center;">
                                                            <img src="/assets/icon/focus-group.webp" alt="icon" style="width: 40px; height: 40px;">
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="card-body">
                                                            <h5 class="card-title mb-0 font-weight-bold">
                                                                <?php if (!empty($quizDetail['kuis'])): ?>
                                                                    <?= htmlspecialchars($quizDetail['kuis'][0]['kuis_judul'] ?? 'Kuis') ?>
                                                                <?php else: ?>
                                                                    Belum ada kuis dikerjakan
                                                                <?php endif; ?>
                                                            </h5>
                                                            <p class="card-text">
                                                                <small class="text-muted">
                                                                    <?php if (!empty($quizDetail['kuis'])): ?>
                                                                        <?php
                                                                        $firstKuis = $quizDetail['kuis'][0];
                                                                        $bestAttempt = $firstKuis['attempts'][0] ?? null;
                                                                        $score = $bestAttempt ? ($bestAttempt['nilai'] ?? null) : null;
                                                                        $passingGrade = $firstKuis['passing_grade'] ?? 0;
                                                                        $isCompleted = $bestAttempt !== null;
                                                                        $status = User::getAttemptStatus($score, $passingGrade, $isCompleted);
                                                                        ?>
                                                                        Status: <span class="badge <?= $status['class'] ?>"><?= $status['label'] ?></span>
                                                                        <?php if ($bestAttempt): ?>
                                                                            (<?= htmlspecialchars($bestAttempt['nilai'] ?? '-') ?>/100 <?php if ($passingGrade > 0): ?>KKM: <?= $passingGrade ?> <?php endif; ?>)
                                                                        <?php endif; ?>
                                                                    <?php else: ?>
                                                                        Belum ada riwayat kuis
                                                                    <?php endif; ?>
                                                                </small>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 flex-wrap mb-4">
                                            <div>
                                                <h6 class="mb-0">
                                                    Pre-Test
                                                </h6>
                                                <small class="text-muted mb-0">
                                                    Evaluasi yang dilakukan pertama kali user masuk aplikasi untuk menilai pengetahuannya.
                                                </small>
                                            </div>
                                            <?php if (!empty($quizDetail['pretest'])): ?>
                                                <?php
                                                $attempt = $quizDetail['pretest'][0];
                                                $score = $attempt['nilai'] ?? null;
                                                $passingGrade = $attempt['passing_grade'] ?? 0;
                                                $isCompleted = true; // pretest attempts are always completed
                                                $status = User::getAttemptStatus($score, $passingGrade, $isCompleted);
                                                ?>
                                                <span class="badge <?= $status['class'] ?> rounded-pill my-2 py-1">
                                                    <i class="mdi mdi-<?= $status['label'] === 'Lulus' ? 'check-circle' : ($status['label'] === 'Gagal' ? 'close-circle' : 'clock-outline') ?>"></i>
                                                    Pretest: <?= $status['label'] ?>
                                                    <?php if ($score !== null): ?>
                                                        (<?= htmlspecialchars($score) ?>/100 <?php if ($passingGrade > 0): ?>KKM: <?= $passingGrade ?> <?php endif; ?>)
                                                    <?php endif; ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger rounded-pill my-2 py-1">
                                                    <i class="mdi mdi-cancel"></i>
                                                    Pretest Belum Dikerjakan
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-lg-6 flex-wrap mb-2">
                                            <div>
                                                <h6 class="mb-0">
                                                    Post-Test
                                                </h6>
                                                <small class="text-muted mb-0">
                                                    Evaluasi yang dilakukan Terakhir kali user dalam aplikasi untuk menilai pengetahuannya.
                                                </small>
                                            </div>
                                            <?php if (!empty($quizDetail['posttest'])): ?>
                                                <?php
                                                $attempt = $quizDetail['posttest'][0];
                                                $score = $attempt['nilai'] ?? null;
                                                $passingGrade = $attempt['passing_grade'] ?? 0;
                                                $isCompleted = true; // posttest attempts are always completed
                                                $status = User::getAttemptStatus($score, $passingGrade, $isCompleted);
                                                ?>
                                                <span class="badge <?= $status['class'] ?> rounded-pill my-2 py-1">
                                                    <i class="mdi mdi-<?= $status['label'] === 'Lulus' ? 'check-circle' : ($status['label'] === 'Gagal' ? 'close-circle' : 'clock-outline') ?>"></i>
                                                    Posttest: <?= $status['label'] ?>
                                                    <?php if ($score !== null): ?>
                                                        (<?= htmlspecialchars($score) ?>/100 <?php if ($passingGrade > 0): ?>KKM: <?= $passingGrade ?> <?php endif; ?>)
                                                    <?php endif; ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger rounded-pill my-2 py-1">
                                                    <i class="mdi mdi-cancel"></i>
                                                    Posttest Belum Dikerjakan
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 3: Daftar Nilai Card -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">
                                        Daftar Nilai
                                    </h4>
                                    <div class="flex-shrink-0">
                                        <!-- Desktop: Tabs -->
                                        <div class="d-none d-md-flex">
                                            <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs" role="tablist" id="scoreTabs">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" data-bs-toggle="tab" href="#home2" role="tab" aria-selected="true">
                                                        <i class="far fa-file-alt me-1"></i> Pre-Test
                                                    </a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#profile2" role="tab" aria-selected="false">
                                                        <i class="far fa-file-excel me-1"></i> Kuis
                                                    </a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#messages2" role="tab" aria-selected="false">
                                                        <i class="far fa-file-powerpoint me-1"></i> Post-Test
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- Mobile: Dropdown Select -->
                                        <div class="d-md-none">
                                            <select class="form-select form-select-sm" id="scoreTypeSelect" style="width: auto; min-width: 180px;">
                                                <option value="home2" selected>Pre-Test</option>
                                                <option value="profile2">Kuis</option>
                                                <option value="messages2">Post-Test</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card header -->
                                <div class="card-body">
                                    <!-- Tab panes -->
                                    <div class="tab-content text-muted" id="scoreTabContent">
                                        <!-- Pre-Test Tab -->
                                        <div class="tab-pane active show" id="home2" role="tabpanel">
                                            <table class="table table-bordered dt-responsive w-100" id="datatable-pretest">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Judul Kuis</th>
                                                        <th>Nilai</th>
                                                        <th>Benar</th>
                                                        <th>Salah</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($quizDetail['pretest'])): ?>
                                                        <?php $no = 1; ?>
                                                        <?php foreach ($quizDetail['pretest'] as $attempt): ?>
                                                            <?php
                                                            $score = $attempt['nilai'] ?? null;
                                                            $passingGrade = $attempt['passing_grade'] ?? 0;
                                                            $isCompleted = true;
                                                            $status = User::getAttemptStatus($score, $passingGrade, $isCompleted);
                                                            ?>
                                                            <tr>
                                                                <td><?= $no++ ?></td>
                                                                <td><?= htmlspecialchars($attempt['kuis_judul'] ?? 'Pretest') ?></td>
                                                                <td>
                                                                    <?= htmlspecialchars($attempt['nilai'] ?? '-') ?>
                                                                    <?php if ($passingGrade > 0 && $score !== null): ?>
                                                                        <br><small class="text-muted">KKM: <?= $passingGrade ?></small>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><?= htmlspecialchars($attempt['jumlah_benar'] ?? '-') ?></td>
                                                                <td><?= htmlspecialchars($attempt['jumlah_salah'] ?? '-') ?></td>
                                                                <td>
                                                                    <span class="badge <?= $status['class'] ?>"><?= $status['label'] ?></span>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- Kuis Tab -->
                                        <div class="tab-pane" id="profile2" role="tabpanel">
                                            <table class="table table-bordered dt-responsive w-100" id="datatable-kuis">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Judul Kuis</th>
                                                        <th>Jenis</th>
                                                        <th>Percobaan Ke-</th>
                                                        <th>Nilai</th>
                                                        <th>Benar</th>
                                                        <th>Salah</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($quizDetail['kuis'])): ?>
                                                        <?php $no = 1; ?>
                                                        <?php foreach ($quizDetail['kuis'] as $kuis): ?>
                                                            <?php
                                                            $passingGrade = $kuis['passing_grade'] ?? 0;
                                                            $attemptCount = count($kuis['attempts']);
                                                            ?>
                                                            <?php foreach ($kuis['attempts'] as $attemptIndex => $attempt): ?>
                                                                <?php
                                                                $score = $attempt['nilai'] ?? null;
                                                                $isCompleted = true;
                                                                $status = User::getAttemptStatus($score, $passingGrade, $isCompleted);
                                                                ?>
                                                                <tr>
                                                                    <td><?= $no++ ?></td>
                                                                    <td><?= $attemptIndex === 0 ? htmlspecialchars($kuis['kuis_judul'] ?? 'Kuis') : '' ?></td>
                                                                    <td><?= $attemptIndex === 0 ? htmlspecialchars($kuis['kuis_jenis'] ?? 'kuis') : '' ?></td>
                                                                    <td><?= $attempt['percobaan'] ?? ($attemptIndex + 1) ?></td>
                                                                    <td>
                                                                        <?= htmlspecialchars($attempt['nilai'] ?? '-') ?>
                                                                        <?php if ($passingGrade > 0 && $score !== null): ?>
                                                                            <br><small class="text-muted">KKM: <?= $passingGrade ?></small>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td><?= htmlspecialchars($attempt['jumlah_benar'] ?? '-') ?></td>
                                                                    <td><?= htmlspecialchars($attempt['jumlah_salah'] ?? '-') ?></td>
                                                                    <td>
                                                                        <span class="badge <?= $status['class'] ?>"><?= $status['label'] ?></span>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- Post-Test Tab -->
                                        <div class="tab-pane" id="messages2" role="tabpanel">
                                            <table class="table table-bordered dt-responsive w-100" id="datatable-posttest">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Judul Kuis</th>
                                                        <th>Nilai</th>
                                                        <th>Benar</th>
                                                        <th>Salah</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($quizDetail['posttest'])): ?>
                                                        <?php $no = 1; ?>
                                                        <?php foreach ($quizDetail['posttest'] as $attempt): ?>
                                                            <?php
                                                            $score = $attempt['nilai'] ?? null;
                                                            $passingGrade = $attempt['passing_grade'] ?? 0;
                                                            $isCompleted = true;
                                                            $status = User::getAttemptStatus($score, $passingGrade, $isCompleted);
                                                            ?>
                                                            <tr>
                                                                <td><?= $no++ ?></td>
                                                                <td><?= htmlspecialchars($attempt['kuis_judul'] ?? 'Posttest') ?></td>
                                                                <td>
                                                                    <?= htmlspecialchars($attempt['nilai'] ?? '-') ?>
                                                                    <?php if ($passingGrade > 0 && $score !== null): ?>
                                                                        <br><small class="text-muted">KKM: <?= $passingGrade ?></small>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><?= htmlspecialchars($attempt['jumlah_benar'] ?? '-') ?></td>
                                                                <td><?= htmlspecialchars($attempt['jumlah_salah'] ?? '-') ?></td>
                                                                <td>
                                                                    <span class="badge <?= $status['class'] ?>"><?= $status['label'] ?></span>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card-body -->
                            </div>
                            <!-- end card -->
                        </div>
                    </div>
                    <!-- end row -->
                </div>
                <!-- container-fluid -->
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

        <!-- DataTables initialization for detail tabs -->
        <script>
            $(document).ready(function() {
                // Custom renderer function untuk child row stacked layout di mobile
                function stackedChildRowRenderer(api, rowIdx, columns) {
                    var data = $.map(columns, function(col) {
                        if (col.hidden) {
                            // Skip kolom "No" (index 0)
                            if (col.columnIndex === 0) return null;

                            return '<li data-dtr-index="' + col.columnIndex + '" data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                                '<span class="dtr-title">' + col.title + '</span>' +
                                '<span class="dtr-data">' + col.data + '</span>' +
                                '</li>';
                        }
                    }).join('');

                    return data ?
                        $('<ul class="dtr-details"/>').append(data) :
                        false;
                }

                // Common DataTable config
                var dtConfig = {
                    order: [],
                    pagingType: "simple_numbers",
                    responsive: {
                        details: {
                            type: 'inline',
                            renderer: stackedChildRowRenderer
                        }
                    },
                    language: {
                        emptyTable: "Tidak ada data tersedia"
                    }
                };

                // Initialize DataTables for each tab
                var pretestTable = $("#datatable-pretest").DataTable(dtConfig);

                var kuisTable = $("#datatable-kuis").DataTable(dtConfig);

                var posttestTable = $("#datatable-posttest").DataTable(dtConfig);

                // Fix responsive recalculation when tab is shown
                $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                    $.fn.dataTable.tables({
                        visible: true,
                        api: true
                    }).columns.adjust().responsive.recalc();
                });

                $(".dataTables_length select").addClass("form-select form-select-sm");

                // Mobile: Sync dropdown select with tabs (one-way: dropdown -> tab)
                // Only needed on mobile where dropdown is visible and tabs are hidden
                $('#scoreTypeSelect').on('change', function() {
                    var target = $(this).val();
                    $('#scoreTabs a[href="#' + target + '"]').tab('show');
                });

                // Handle tab shown for DataTables responsive recalc (both desktop tabs and mobile dropdown-triggered)
                $('#scoreTabs a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                    $.fn.dataTable.tables({
                        visible: true,
                        api: true
                    }).columns.adjust().responsive.recalc();
                });
            });

            // Learning Progress Chart using ApexCharts - Combined Progress (Radial/Gauge)
            document.addEventListener('DOMContentLoaded', function() {
                // Chart data from PHP - Completion-based progress
                var combinedProgress = <?= json_encode($combinedProgress) ?>;

                var pretestCompletion = <?= json_encode($pretestCompletion) ?>;
                var kuisCompletion = <?= json_encode($kuisCompletion) ?>;
                var posttestCompletion = <?= json_encode($posttestCompletion) ?>;
                var materiCompletion = <?= json_encode($materiCompletion) ?>;

                var completedMateri = <?= json_encode($completedMateri) ?>;
                var totalMateri = <?= json_encode($totalMateri) ?>;
                var completedKuis = <?= json_encode($completedKuis) ?>;
                var totalKuis = <?= json_encode($totalKuis) ?>;
                var completedPretest = <?= json_encode($completedPretest) ?>;
                var totalPretest = <?= json_encode($totalPretest) ?>;
                var completedPosttest = <?= json_encode($completedPosttest) ?>;
                var totalPosttest = <?= json_encode($totalPosttest) ?>;
                var completedActivities = <?= json_encode($completedActivities) ?>;
                var totalActivities = <?= json_encode($totalActivities) ?>;

                // ============================================
                // CHART: Combined Learning Progress (Radial Bar / Gauge)
                // Shows single 0-100% progress from completion rate
                // ============================================

                // Determine color based on progress
                var progressColor = combinedProgress >= 80 ? '#34c38f' : (combinedProgress >= 60 ? '#f1b44c' : (combinedProgress >= 40 ? '#f46a6a' : '#5156be'));

                var chartOptions = {
                    series: [combinedProgress],
                    chart: {
                        type: 'radialBar',
                        height: 280,
                        offsetY: -10,
                        sparkline: {
                            enabled: false
                        }
                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: -135,
                            endAngle: 135,
                            hollow: {
                                margin: 20,
                                size: '65%',
                                background: 'transparent',
                                image: undefined,
                                imageOffsetX: 0,
                                imageOffsetY: 0,
                                position: 'front',
                                dropShadow: {
                                    enabled: true,
                                    top: 3,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.24
                                }
                            },
                            track: {
                                background: '#e8e8e8',
                                strokeWidth: '97%',
                                margin: 5,
                                dropShadow: {
                                    enabled: false,
                                    top: -3,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.35
                                }
                            },
                            dataLabels: {
                                show: true,
                                name: {
                                    offsetY: -15,
                                    show: true,
                                    color: '#888',
                                    fontSize: '14px',
                                    fontWeight: 500
                                },
                                value: {
                                    formatter: function(val) {
                                        return val.toFixed(1) + '%';
                                    },
                                    color: '#333',
                                    fontSize: '36px',
                                    show: true,
                                    fontWeight: 'bold',
                                    offsetY: 10
                                },
                                total: {
                                    show: false
                                }
                            }
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: 'horizontal',
                            shadeIntensity: 0.5,
                            gradientToColors: [progressColor],
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100]
                        }
                    },
                    colors: [progressColor],
                    stroke: {
                        lineCap: 'round'
                    },
                    labels: [''],
                    title: {
                        show: false
                    },
                    subtitle: {
                        show: false
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                height: 240
                            },
                            plotOptions: {
                                radialBar: {
                                    hollow: {
                                        size: '55%'
                                    },
                                    dataLabels: {
                                        value: {
                                            fontSize: '28px'
                                        }
                                    }
                                }
                            }
                        }
                    }]
                };

                var chart = new ApexCharts(document.querySelector("#learning-progress-chart"), chartOptions);
                chart.render();

                // Add detail breakdown below chart after render
                setTimeout(function() {
                    var detailContainer = document.querySelector("#learning-progress-detail");
                    if (detailContainer) {
                        var detailHtml = `
                    <div class="mt-3 pt-3 border-top" style="font-size: 11px;">
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="fw-bold text-primary"><?= $pretestCompletion ?>%</div>
                                <div class="text-muted">Pre-Test</div>
                                <div class="text-muted small"><?= $completedPretest ?>/<?= $totalPretest ?></div>
                            </div>
                            <div class="col-3">
                                <div class="fw-bold text-info"><?= $kuisCompletion ?>%</div>
                                <div class="text-muted">Kuis</div>
                                <div class="text-muted small"><?= $completedKuis ?>/<?= $totalKuis ?></div>
                            </div>
                            <div class="col-3">
                                <div class="fw-bold text-warning"><?= $posttestCompletion ?>%</div>
                                <div class="text-muted">Post-Test</div>
                                <div class="text-muted small"><?= $completedPosttest ?>/<?= $totalPosttest ?></div>
                            </div>
                            <div class="col-3">
                                <div class="fw-bold text-success"><?= $materiCompletion ?>%</div>
                                <div class="text-muted">Materi</div>
                                <div class="text-muted small"><?= $completedMateri ?>/<?= $totalMateri ?></div>
                            </div>
                        </div>
                        <div class="text-center mt-2 text-muted small">
                            <i class="mdi mdi-information-outline"></i> 
                            Total: <strong><?= $completedActivities ?>/<?= $totalActivities ?></strong> aktivitas (<strong><?= $combinedProgress ?>%</strong>)
                        </div>
                    </div>
                `;
                        detailContainer.innerHTML = detailHtml;
                    }
                }, 500);
            });
        </script>

</body>

</html>