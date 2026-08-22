<?php

require_once '../../src/classes/auth.php';
require_once '../../src/classes/tests.php';
require_once '../../src/classes/user.php';
require_once '../../src/classes/kuis.php';
require_once '../../src/classes/tests.php';

$auth = new auth();
$auth->authOrNot();

$testManager = new tests();

$userId = (int) $_SESSION['id'];

// Initialize models
$userModel = new User();
$kuisModel = new Kuis();

// Get user basic info and quiz results
$userResult = $userModel->getUserWithQuizResults($userId);
$user = $userResult['data'] ?? null;

// Get detailed quiz results
$quizDetailResult = $userModel->getUserQuizResultsDetail($userId);
$quizDetail = $quizDetailResult['data'] ?? ['pretest' => [], 'posttest' => [], 'kuis' => []];

// Get user material progress
$materialProgressResult = $userModel->getUserMaterialProgress($userId);
$materialProgress = $materialProgressResult['data'] ?? ['progress' => [], 'summary' => ['total' => 0, 'completed' => 0, 'in_progress' => 0, 'not_started' => 0, 'completion_rate' => 0]];

// Calculate material stats
$matCompleted = $materialProgress['summary']['completed'] ?? 0;
$matInProgress = $materialProgress['summary']['in_progress'] ?? 0;
$matNotStarted = $materialProgress['summary']['not_started'] ?? 0;
$matTotal = $materialProgress['summary']['total'] ?? 0;
$materiCompletion = $matTotal > 0 ? $materialProgress['summary']['completion_rate'] : 0;

// Quiz attempts count
$pretestAttempts = (int)($user['pretest_attempts'] ?? 0);
$kuisAttempts = (int)($user['kuis_attempts'] ?? 0);
$posttestAttempts = (int)($user['posttest_attempts'] ?? 0);

// Get total available activities in system
// For Kuis, use only quiz-type (jenis='kuis'), excluding pretest/posttest
$allKuisOnly = $kuisModel->getAllKuisOnly();
$totalKuis = count($allKuisOnly);
$totalMateri = $matTotal;
$totalPretest = 1;
$totalPosttest = 1;

// Calculate completed activities by user
$completedMateri = $matCompleted + $matInProgress;
$completedPretest = $pretestAttempts > 0 ? 1 : 0;
$completedKuis = count($quizDetail['kuis'] ?? []);  // Only quiz-type from detail
$completedPosttest = $posttestAttempts > 0 ? 1 : 0;

// Calculate completion-based progress (0-100%)
$totalActivities = $totalMateri + $totalPretest + $totalKuis + $totalPosttest;
$completedActivities = $completedMateri + $completedPretest + $completedKuis + $completedPosttest;

$combinedProgress = $totalActivities > 0 ? round(($completedActivities / $totalActivities) * 100, 1) : 0;

// Individual completion rates for breakdown
$pretestCompletion = $pretestAttempts > 0 ? 100 : 0;
$kuisCompletion = $totalKuis > 0 ? round(($completedKuis / $totalKuis) * 100, 1) : 0;
$posttestCompletion = $posttestAttempts > 0 ? 100 : 0;

// Pre/post test flags for existing logic
$sudahPreTest = $pretestAttempts > 0;
$sudahPostTest = $posttestAttempts > 0;

$preQuiz = $testManager->getQuizByJenis('pre');

if (!$preQuiz) {
   die('Pre-test belum tersedia.');
}

$preQuizId = (int) $preQuiz['id'];

$sudahPreTest = $testManager->hasUserTakenTest(
   $userId,
   $preQuizId,
   'pre'
);

$postQuiz = $testManager->getQuizByJenis('post');

$sudahPostTest = false;

if ($postQuiz) {

   $postQuizId = (int) $postQuiz['id'];

   $sudahPostTest = $testManager->hasUserTakenTest(
      $userId,
      $postQuizId,
      'post'
   );
}


if (!$sudahPreTest) {

   header('Location: preposttest.php?type=pre');
   exit;
}

global $sudahPreTest, $sudahPostTest;

// 1. Pre-Test harus sudah selesai
$syaratPretest = $sudahPreTest;

// 2. Semua materi harus selesai
$syaratMateri = (
   $totalMateri > 0 &&
   $completedMateri >= $totalMateri
);

// 3. Semua kuis harus selesai
$syaratKuis = (
   $totalKuis > 0 &&
   $completedKuis >= $totalKuis
);

// Post-Test boleh dikerjakan jika semua syarat terpenuhi
$bolehPostTest = (
   $syaratPretest &&
   $syaratMateri &&
   $syaratKuis
);
?>

<!--header start-->
<?php include('../include/header.php'); ?>
<!--headere end-->

<body>
   <!-- <body data-layout="horizontal"> -->
   <!-- Begin page -->
   <div id="layout-wrapper">
      <!-- ========== Topbar Start ========== -->
      <?php include('../include/topbar.php'); ?>
      <!-- ========== Topbar End ========== -->
      <!-- ========== Left Sidebar Start ========== -->
      <?php include('../include/sidebar.php'); ?>
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
                     <div class="row d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-weight-bold mb-1">
                           Halo, <?= htmlspecialchars($user['name'] ?? 'User') ?>!
                        </h4>
                        <p class="text-muted">
                           Siap untuk belajar lagi hari ini? Ayo mulai!
                        </p>
                     </div>
                  </div>
               </div>
               <!-- end page title -->
               <div class="row">
                  <div class="col-xl-12">
                     <!-- card -->
                     <div class="card card-h-100" id="progress-belajar-card">
                        <!-- card body -->
                        <div class="card-body">
                           <div class="d-flex flex-wrap align-items-center">
                              <h4 class="card-title me-2">
                                 Progress Belajar
                              </h4>
                           </div>
                           <div class="row align-items-center">
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
                     <!-- end card -->
                  </div>
                  <!-- end col -->
               </div>
               <!-- end row-->
               <div class="row">
                  <div class="d-flex flex-wrap align-items-center mb-2">
                     <h5 class="font-weight-bold me-2">
                        Daftar Menu
                     </h5>
                  </div>
                  <div class="col-6 col-xl-3 col-md-6">
                     <!-- card -->
                     <div class="card card-h-100 shadow-sm" style="border-radius: 1.25rem;">
                        <!-- card body -->
                        <a class="card-body" href="belajar.php">
                           <div class="row align-items-center">
                              <div class="col-12">
                                 <h4 class="mb-3">
                                    <img src="/assets/icon/mortarboard.webp" alt="icon" style="width: 40px; height: 40px;" />
                                 </h4>
                                 <span class="text-muted lh-1 d-block text-truncate">
                                    Modul Edukasi
                                 </span>
                              </div>
                           </div>
                        </a>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                  <div class="col-6 col-xl-3 col-md-6">
                     <!-- card -->
                     <div class="card card-h-100 shadow-sm" style="border-radius: 1.25rem;">
                        <!-- card body -->
                        <a class="card-body" href="skill.php">
                           <div class="row align-items-center">
                              <div class="col-12">
                                 <h4 class="mb-3">
                                    <img src="/assets/icon/edition.webp" alt="icon" style="width: 40px; height: 40px;" />
                                 </h4>
                                 <span class="text-muted lh-1 d-block text-truncate">
                                    Kelas Skill
                                 </span>
                              </div>
                           </div>
                        </a>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                  <div class="col-6 col-xl-3 col-md-6">
                     <!-- card -->
                     <div class="card card-h-100 shadow-sm" style="border-radius: 1.25rem;">
                        <!-- card body -->
                        <a class="card-body" href="daftar-komunitas.php">
                           <div class="row align-items-center">
                              <div class="col-12">
                                 <h4 class="mb-3">
                                    <img src="/assets/icon/slack.webp" alt="icon" style="width: 40px; height: 40px;" />
                                 </h4>
                                 <span class="text-muted lh-1 d-block text-truncate">
                                    Kom. Sebaya
                                 </span>
                              </div>
                           </div>
                        </a>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                  <div class="col-6 col-xl-3 col-md-6">
                     <!-- card -->
                     <div class="card card-h-100 shadow-sm" style="border-radius: 1.25rem;">
                        <!-- card body -->
                        <a class="card-body" href="daftar-konseling.php">
                           <div class="row align-items-center">
                              <div class="col-12">
                                 <h4 class="mb-3">
                                    <img src="/assets/icon/whatsapp.webp" alt="icon" style="width: 40px; height: 40px;" />
                                 </h4>
                                 <span class="text-muted lh-1 d-block text-truncate">
                                    Konseling
                                 </span>
                              </div>
                           </div>
                        </a>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                  <!-- end col -->
                  <div class="col-12">
                     <div class="card card-h-100 shadow-sm" style="border-radius: 1.25rem;">
                        <div class="card-body">
                           <div class="row align-items-center">
                              <div class="col-5">
                                 <h4 class="mb-3">
                                    <img
                                       src="/assets/icon/notes.webp"
                                       alt="icon"
                                       style="width: 40px; height: 40px;" />
                                 </h4>
                                 <p class="text-muted lh-1 d-block text-truncate">
                                    Post-Test
                                 </p>
                              </div>
                              <div class="col-7 text-end">
                                 <?php if (!$sudahPreTest): ?>

                                    <span class="text-secondary">
                                       Kerjakan Pre-Test terlebih dahulu
                                    </span>

                                 <?php elseif (!$syaratMateri): ?>

                                    <span class="text-secondary">
                                       Selesaikan seluruh materi terlebih dahulu
                                    </span>

                                 <?php elseif (!$syaratKuis): ?>

                                    <span class="text-secondary">
                                       Selesaikan seluruh kuis terlebih dahulu
                                    </span>

                                 <?php elseif (!$sudahPostTest): ?>

                                    <a
                                       href="preposttest.php?type=post"
                                       class="btn btn-primary btn-rounded">
                                       Mulai Post-Test
                                    </a>

                                 <?php else: ?>

                                    <span class="text-success">
                                       <i class="mdi mdi-checkbox-marked-circle-outline"></i>
                                       Nilai Post-Test Tersimpan
                                    </span>

                                 <?php endif; ?>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- end row-->
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

   <!-- Learning Progress Chart using ApexCharts - Combined Progress (Radial/Gauge) -->
   <script>
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

         // High-contrast color palette for gradient backgrounds (WCAG AA 4.5:1)
         // Bright Amber, Vivid Teal, Hot Pink, Electric Lime
         var VIBRANT_COLORS = {
            amber: '#FFB300',
            teal: '#45FFCA',
            pink: '#FFD93D',
            lime: '#E4FF30',
            // Darker variants for text on light backgrounds
            amberDark: '#CC8F00',
            tealDark: '#00B8A0',
            pinkDark: '#b39418',
            limeDark: '#A3CC00'
         };

         // Detect if we're in dark mode (CSS prefers-color-scheme or data-theme)
         function isDarkMode() {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ||
               document.documentElement.getAttribute('data-theme') === 'dark' ||
               document.body.classList.contains('dark-mode');
         }

         var darkMode = isDarkMode();

         // Select progress color based on completion percentage with high contrast
         // Use amber for low, teal for medium, pink for high, lime for excellent
         var progressColor;
         var progressColorDark;
         if (combinedProgress >= 80) {
            progressColor = VIBRANT_COLORS.lime;
            progressColorDark = VIBRANT_COLORS.limeDark;
         } else if (combinedProgress >= 60) {
            progressColor = VIBRANT_COLORS.pink;
            progressColorDark = VIBRANT_COLORS.pinkDark;
         } else if (combinedProgress >= 40) {
            progressColor = VIBRANT_COLORS.teal;
            progressColorDark = VIBRANT_COLORS.tealDark;
         } else {
            progressColor = VIBRANT_COLORS.amber;
            progressColorDark = VIBRANT_COLORS.amberDark;
         }

         // Use white for all text to be neutral against gradient background
         var chartColor = darkMode ? progressColor : progressColorDark;
         var trackBg = darkMode ? 'rgba(255, 255, 255, 0.15)' : 'rgba(0, 0, 0, 0.12)';
         var labelColor = '#FFFFFF';
         var mutedColor = 'rgba(255, 255, 255, 0.7)';
         var valueColor = '#FFFFFF';

         var chartOptions = {
            series: [combinedProgress],
            chart: {
               type: 'radialBar',
               height: 280,
               offsetY: -10,
               sparkline: {
                  enabled: false
               },
               background: 'transparent'
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
                        blur: 8,
                        opacity: darkMode ? 0.4 : 0.3,
                        color: chartColor
                     }
                  },
                  track: {
                     background: trackBg,
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
                        color: mutedColor,
                        fontSize: '14px',
                        fontWeight: 600
                     },
                     value: {
                        formatter: function(val) {
                           return val.toFixed(1) + '%';
                        },
                        color: valueColor,
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
                  shade: darkMode ? 'light' : 'dark',
                  type: 'horizontal',
                  shadeIntensity: 0.4,
                  gradientToColors: [chartColor],
                  inverseColors: !darkMode,
                  opacityFrom: 1,
                  opacityTo: 1,
                  stops: [0, 100]
               }
            },
            colors: [chartColor],
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

         // Re-render chart on theme change
         var mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
         mediaQuery.addEventListener('change', function(e) {
            location.reload(); // Simple reload to apply new theme colors
         });

         // Add detail breakdown below chart after render
         setTimeout(function() {
            var detailContainer = document.querySelector("#learning-progress-detail");
            if (detailContainer) {
               // Use white for all text to be neutral against gradient background
               var whiteColor = '#FFFFFF';
               var mutedWhite = 'rgba(255, 255, 255, 0.7)';

               var detailHtml = `
               <div class="mt-3 pt-3 border-top" style="font-size: 11px; border-color: rgba(255,255,255,0.2);">
                   <div class="row text-center">
                       <div class="col-3">
                           <div class="fw-bold" style="color: ` + whiteColor + `;"><?= $pretestCompletion ?>%</div>
                           <div style="color: ` + mutedWhite + `;">Pre-Test</div>
                           <div class="small" style="color: ` + mutedWhite + `;"><?= $completedPretest ?>/<?= $totalPretest ?></div>
                       </div>
                       <div class="col-3">
                           <div class="fw-bold" style="color: ` + whiteColor + `;"><?= $kuisCompletion ?>%</div>
                           <div style="color: ` + mutedWhite + `;">Kuis</div>
                           <div class="small" style="color: ` + mutedWhite + `;"><?= $completedKuis ?>/<?= $totalKuis ?></div>
                       </div>
                       <div class="col-3">
                           <div class="fw-bold" style="color: ` + whiteColor + `;"><?= $materiCompletion ?>%</div>
                           <div style="color: ` + mutedWhite + `;">Materi</div>
                           <div class="small" style="color: ` + mutedWhite + `;"><?= $completedMateri ?>/<?= $totalMateri ?></div>
                       </div>
                       <div class="col-3">
                           <div class="fw-bold" style="color: ` + whiteColor + `;"><?= $posttestCompletion ?>%</div>
                           <div style="color: ` + mutedWhite + `;">Post-Test</div>
                           <div class="small" style="color: ` + mutedWhite + `;"><?= $completedPosttest ?>/<?= $totalPosttest ?></div>
                       </div>
                   </div>
                   <div class="text-center mt-2 small" style="color: ` + mutedWhite + `;">
                       <i class="mdi mdi-information-outline"></i>
                       Total: <strong style="color: ` + whiteColor + `;"><?= $completedActivities ?>/<?= $totalActivities ?></strong> aktivitas (<strong style="color: ` + whiteColor + `;"><?= $combinedProgress ?>%</strong>)
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