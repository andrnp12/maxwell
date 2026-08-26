<?php

require_once '../../src/classes/auth.php';
require_once '../../src/classes/profile.php';
require_once '../../src/classes/user.php';
require_once '../../src/classes/kuis.php';

$auth = new auth();
$auth->authOrNot();

$userId = (int) $_SESSION['id'];

$userModel = new User();
$kuisModel = new Kuis();


/*
|--------------------------------------------------------------------------
| Ambil akun ortu
|--------------------------------------------------------------------------
*/

$parentResult = $userModel->getUserById($userId);

if ($parentResult['status'] !== 'success') {
   die('Data akun orang tua tidak ditemukan.');
}

$parent = $parentResult['data'];


/*
|--------------------------------------------------------------------------
| Pastikan role adalah ortu
|--------------------------------------------------------------------------
*/

if (($parent['role'] ?? '') !== 'ortu') {
   die('Anda tidak memiliki akses ke halaman ini.');
}


/*
|--------------------------------------------------------------------------
| Ambil no_kk ortu
|--------------------------------------------------------------------------
*/

$noKK = trim($parent['no_kk'] ?? '');


/*
|--------------------------------------------------------------------------
| Ambil total kuis yang tersedia
|--------------------------------------------------------------------------
*/

$allKuisOnly = $kuisModel->getAllKuisOnly();

$totalKuis = count($allKuisOnly);


/*
|--------------------------------------------------------------------------
| Ambil semua user dengan no_kk yang sama
|--------------------------------------------------------------------------
*/

$children = [];

if ($noKK !== '') {

   $childrenResult = $userModel->getUsersByNoKK($noKK);

   if ($childrenResult['status'] === 'success') {
      $children = $childrenResult['data'];
   }
}


/*
|--------------------------------------------------------------------------
| Ambil progress setiap user/anak
|--------------------------------------------------------------------------
*/

foreach ($children as &$child) {

   $childProgressResult = $userModel->getLearningProgress(
      (int) $child['id'],
      $totalKuis
   );

   if ($childProgressResult['status'] === 'success') {

      $child['progress'] = $childProgressResult['data'];
   } else {

      $child['progress'] = [
         'combinedProgress' => 0,

         'pretestCompletion' => 0,
         'kuisCompletion' => 0,
         'materiCompletion' => 0,
         'posttestCompletion' => 0,

         'completedMateri' => 0,
         'totalMateri' => 0,

         'completedKuis' => 0,
         'totalKuis' => $totalKuis,

         'completedPretest' => 0,
         'totalPretest' => 1,

         'completedPosttest' => 0,
         'totalPosttest' => 1,

         'completedActivities' => 0,
         'totalActivities' => 0
      ];
   }
}

unset($child);
?>



<!--header start-->
<?php include('../include/header.php'); ?>
<!--headere end-->
<style>
   .child-option {
      transition: all 0.2s ease;
   }

   .child-option:hover {
      background: rgba(30, 64, 175, 0.08);
   }

   .child-option.active {
      background: #2563eb !important;
      color: #ffffff !important;
   }

   .child-option.active::before {
      content: "✓";
      margin-right: 8px;
      font-weight: 700;
   }

   #child-selector:hover {
      background-color: rgba(255, 255, 255, 0.20) !important;
   }

   #child-selector:focus {
      box-shadow:
         0 0 0 3px rgba(255, 255, 255, 0.18) !important;
   }
</style>

<body>
   <!-- <body data-layout="horizontal"> -->
   <!-- Begin page -->
   <div id="layout-wrapper">
      <!-- ========== Topbar Start ========== -->
      <?php include('../include/topbar.php'); ?>
      <!-- ========== Topbar End ========== -->
      <!-- ========== Left Sidebar Start ========== -->
      <?php include('../include/sidebar-ortu.php'); ?>
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
                           Halo, <?= htmlspecialchars($user['data']['name'] ?? 'Ortu') ?>!
                        </h4>
                        <p class="text-muted">
                           Pantau progres belajar anak Anda.
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
                           <?php if (!empty($children)): ?>

                              <div class="mb-4">
                                 <label for="child-selector" class="d-block mb-2 text-white fw-semibold" style="font-size: 0.9rem;">
                                    <i class="mdi mdi-account-child-outline me-1"></i>
                                    Pilih Anak
                                 </label>

                                 <div class="position-relative dropdown">
                                    <!-- Ikon User -->
                                    <i class="mdi mdi-account-circle-outline position-absolute text-white"
                                       style="left: 15px; top: 50%; transform: translateY(-50%); z-index: 2; font-size: 1.2rem; opacity: 0.85; pointer-events: none;"></i>

                                    <!-- Input tersembunyi untuk menyimpan ID yang dipilih (Pengganti tag Select) -->
                                    <input type="hidden" name="child_id" id="child-id-input" value="<?= (int) $children[0]['id'] ?>">

                                    <!-- Tombol yang terlihat seperti Select -->
                                    <button
                                       class="w-100 text-start text-white border-0 shadow-sm d-flex justify-content-between align-items-center dropdown-toggle"
                                       type="button"
                                       id="child-selector"
                                       data-bs-toggle="dropdown"
                                       aria-expanded="false"
                                       style="
        background-color: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.28) !important;
        border-radius: 12px;
        min-height: 48px;
        padding-left: 45px;
        padding-right: 15px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        font-weight: 500;
        cursor: pointer;
        outline: none;
    ">
                                       <span id="selected-child-text">
                                          <?= htmlspecialchars($children[0]['name']) ?>
                                       </span>
                                    </button>

                                    <!-- Dropdown Menu (Pengganti tag Option yang SEKARANG BISA DI-STYLING) -->
                                    <ul class="dropdown-menu w-100 shadow" style="border-radius: 12px; overflow: hidden; padding: 0; border: 1px solid rgba(0,0,0,0.1);">
                                       <?php foreach ($children as $index => $child): ?>
                                          <li>
                                             <a class="dropdown-item child-option <?= $index === 0 ? 'active text-white' : 'text-dark' ?>"
                                                href="#"
                                                data-value="<?= (int) $child['id'] ?>"
                                                style="padding: 12px 15px; cursor: pointer; transition: 0.2s;">
                                                <?= htmlspecialchars($child['name']) ?>
                                             </a>
                                          </li>
                                       <?php endforeach; ?>
                                    </ul>
                                 </div>

                                 <div class="mt-2 text-white" style="font-size: 0.75rem; opacity: 0.7;">
                                    <i class="mdi mdi-information-outline me-1"></i>
                                    Pilih anggota keluarga untuk melihat progres belajarnya.
                                 </div>
                              </div>

                           <?php else: ?>
                              <!-- Bagian else Anda tetap sama persis seperti sebelumnya -->
                              <div class="p-3 mb-4 text-white" style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px; backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
                                 <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px; border-radius: 50%; background: rgba(255, 255, 255, 0.15); flex-shrink: 0;">
                                       <i class="mdi mdi-account-alert-outline" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div>
                                       <div class="fw-semibold">Belum ada data anak</div>
                                       <div style="font-size: 0.75rem; opacity: 0.7;">
                                          Belum ada user yang terhubung dengan nomor KK <?= htmlspecialchars($noKK ?? '') ?>.
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           <?php endif; ?>
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
                  <div class="col-12">
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
         const options = document.querySelectorAll('.child-option');
         const inputHidden = document.getElementById('child-id-input');
         const selectedText = document.getElementById('selected-child-text');

         options.forEach(option => {
            option.addEventListener('click', function(e) {
               e.preventDefault(); // Mencegah halaman melompat ke atas

               // 1. Ambil data dari item yang diklik
               const value = this.getAttribute('data-value');
               const text = this.innerText;

               // 2. Update value input hidden dan teks tombol
               inputHidden.value = value;
               selectedText.innerText = text;

               // 3. Update styling untuk menandai mana yang aktif
               options.forEach(opt => {
                  opt.classList.remove('active', 'text-white');
                  opt.classList.add('text-dark');
               });
               this.classList.add('active', 'text-white');
               this.classList.remove('text-dark');
            });
         });
         // =========================================================
         // DATA PROGRESS DARI PHP
         // =========================================================

         var childrenProgress = <?= json_encode(
                                    array_map(function ($child) {

                                       $p = $child['progress'] ?? [];

                                       return [
                                          'id' => (int) $child['id'],
                                          'name' => $child['name'],

                                          'combinedProgress' =>
                                          (float) ($p['combinedProgress'] ?? 0),

                                          'pretestCompletion' =>
                                          (float) ($p['pretestCompletion'] ?? 0),

                                          'kuisCompletion' =>
                                          (float) ($p['kuisCompletion'] ?? 0),

                                          'materiCompletion' =>
                                          (float) ($p['materiCompletion'] ?? 0),

                                          'posttestCompletion' =>
                                          (float) ($p['posttestCompletion'] ?? 0),

                                          'completedMateri' =>
                                          (int) ($p['completedMateri'] ?? 0),

                                          'totalMateri' =>
                                          (int) ($p['totalMateri'] ?? 0),

                                          'completedKuis' =>
                                          (int) ($p['completedKuis'] ?? 0),

                                          'totalKuis' =>
                                          (int) ($p['totalKuis'] ?? 0),

                                          'completedPretest' =>
                                          (int) ($p['completedPretest'] ?? 0),

                                          'totalPretest' =>
                                          (int) ($p['totalPretest'] ?? 0),

                                          'completedPosttest' =>
                                          (int) ($p['completedPosttest'] ?? 0),

                                          'totalPosttest' =>
                                          (int) ($p['totalPosttest'] ?? 0),

                                          'completedActivities' =>
                                          (int) ($p['completedActivities'] ?? 0),

                                          'totalActivities' =>
                                          (int) ($p['totalActivities'] ?? 0)
                                       ];
                                    }, $children),
                                    JSON_UNESCAPED_UNICODE
                                 ) ?>;


         // =========================================================
         // CHART INSTANCE
         // =========================================================

         var learningProgressChart = null;


         // =========================================================
         // RENDER CHART
         // =========================================================

         function renderLearningProgress(data) {

            var combinedProgress =
               Number(data.combinedProgress || 0);

            var pretestCompletion =
               Number(data.pretestCompletion || 0);

            var kuisCompletion =
               Number(data.kuisCompletion || 0);

            var materiCompletion =
               Number(data.materiCompletion || 0);

            var posttestCompletion =
               Number(data.posttestCompletion || 0);


            var completedMateri =
               Number(data.completedMateri || 0);

            var totalMateri =
               Number(data.totalMateri || 0);


            var completedKuis =
               Number(data.completedKuis || 0);

            var totalKuis =
               Number(data.totalKuis || 0);


            var completedPretest =
               Number(data.completedPretest || 0);

            var totalPretest =
               Number(data.totalPretest || 0);


            var completedPosttest =
               Number(data.completedPosttest || 0);

            var totalPosttest =
               Number(data.totalPosttest || 0);


            var completedActivities =
               Number(data.completedActivities || 0);

            var totalActivities =
               Number(data.totalActivities || 0);


            // =====================================================
            // DESTROY CHART LAMA
            // =====================================================

            if (learningProgressChart) {
               learningProgressChart.destroy();
               learningProgressChart = null;
            }


            // =====================================================
            // COLORS
            // =====================================================

            var VIBRANT_COLORS = {

               amber: '#FFB300',
               teal: '#45FFCA',
               pink: '#FFD93D',
               lime: '#E4FF30',

               amberDark: '#CC8F00',
               tealDark: '#00B8A0',
               pinkDark: '#b39418',
               limeDark: '#A3CC00'

            };


            // =====================================================
            // DARK MODE
            // =====================================================

            function isDarkMode() {

               return window.matchMedia(
                     '(prefers-color-scheme: dark)'
                  ).matches ||

                  document.documentElement
                  .getAttribute('data-theme') === 'dark' ||

                  document.body
                  .classList
                  .contains('dark-mode');
            }


            var darkMode = isDarkMode();


            // =====================================================
            // PROGRESS COLOR
            // =====================================================

            var progressColor;
            var progressColorDark;


            if (combinedProgress >= 80) {

               progressColor =
                  VIBRANT_COLORS.lime;

               progressColorDark =
                  VIBRANT_COLORS.limeDark;

            } else if (combinedProgress >= 60) {

               progressColor =
                  VIBRANT_COLORS.pink;

               progressColorDark =
                  VIBRANT_COLORS.pinkDark;

            } else if (combinedProgress >= 40) {

               progressColor =
                  VIBRANT_COLORS.teal;

               progressColorDark =
                  VIBRANT_COLORS.tealDark;

            } else {

               progressColor =
                  VIBRANT_COLORS.amber;

               progressColorDark =
                  VIBRANT_COLORS.amberDark;
            }


            var chartColor =
               darkMode ?
               progressColor :
               progressColorDark;


            var trackBg =
               darkMode ?
               'rgba(255, 255, 255, 0.15)' :
               'rgba(0, 0, 0, 0.12)';


            var mutedColor =
               'rgba(255, 255, 255, 0.7)';

            var valueColor =
               '#FFFFFF';


            // =====================================================
            // APEX CHART OPTIONS
            // =====================================================

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

                           opacity: darkMode ?
                              0.4 : 0.3,

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

                     shade: darkMode ?
                        'light' : 'dark',

                     type: 'horizontal',

                     shadeIntensity: 0.4,

                     gradientToColors: [
                        chartColor
                     ],

                     inverseColors:
                        !darkMode,

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


            // =====================================================
            // RENDER
            // =====================================================

            learningProgressChart =
               new ApexCharts(
                  document.querySelector(
                     "#learning-progress-chart"
                  ),
                  chartOptions
               );


            learningProgressChart.render();


            // =====================================================
            // DETAIL
            // =====================================================

            var detailContainer =
               document.querySelector(
                  "#learning-progress-detail"
               );


            if (detailContainer) {

               var whiteColor =
                  '#FFFFFF';

               var mutedWhite =
                  'rgba(255, 255, 255, 0.7)';


               var detailHtml = `

                <div
                    class="mt-3 pt-3 border-top"
                    style="
                        font-size: 11px;
                        border-color:
                        rgba(255,255,255,0.2);
                    "
                >

                    <div class="row text-center">


                        <div class="col-3">

                            <div
                                class="fw-bold"
                                style="
                                    color: ${whiteColor};
                                "
                            >
                                ${pretestCompletion}%
                            </div>

                            <div
                                style="
                                    color: ${mutedWhite};
                                "
                            >
                                Pre-Test
                            </div>

                            <div
                                class="small"
                                style="
                                    color: ${mutedWhite};
                                "
                            >
                                ${completedPretest}/${totalPretest}
                            </div>

                        </div>


                        <div class="col-3">

                            <div
                                class="fw-bold"
                                style="
                                    color: ${whiteColor};
                                "
                            >
                                ${kuisCompletion}%
                            </div>

                            <div
                                style="
                                    color: ${mutedWhite};
                                "
                            >
                                Kuis
                            </div>

                            <div
                                class="small"
                                style="
                                    color: ${mutedWhite};
                                "
                            >
                                ${completedKuis}/${totalKuis}
                            </div>

                        </div>


                        <div class="col-3">

                            <div
                                class="fw-bold"
                                style="
                                    color: ${whiteColor};
                                "
                            >
                                ${materiCompletion}%
                            </div>

                            <div
                                style="
                                    color: ${mutedWhite};
                                "
                            >
                                Materi
                            </div>

                            <div
                                class="small"
                                style="
                                    color: ${mutedWhite};
                                "
                            >
                                ${completedMateri}/${totalMateri}
                            </div>

                        </div>


                        <div class="col-3">

                            <div
                                class="fw-bold"
                                style="
                                    color: ${whiteColor};
                                "
                            >
                                ${posttestCompletion}%
                            </div>

                            <div
                                style="
                                    color: ${mutedWhite};
                                "
                            >
                                Post-Test
                            </div>

                            <div
                                class="small"
                                style="
                                    color: ${mutedWhite};
                                "
                            >
                                ${completedPosttest}/${totalPosttest}
                            </div>

                        </div>

                    </div>


                    <div
                        class="text-center mt-2 small"
                        style="
                            color: ${mutedWhite};
                        "
                    >

                        <i
                            class="
                                mdi
                                mdi-information-outline
                            "
                        ></i>

                        Total:

                        <strong
                            style="
                                color: ${whiteColor};
                            "
                        >
                            ${completedActivities}/${totalActivities}
                        </strong>

                        aktivitas

                        (

                        <strong
                            style="
                                color: ${whiteColor};
                            "
                        >
                            ${combinedProgress}%
                        </strong>

                        )

                    </div>

                </div>

            `;


               detailContainer.innerHTML =
                  detailHtml;
            }
         }


         // =========================================================
         // CHILD SELECTOR - CUSTOM DROPDOWN
         // =========================================================

         var childSelector =
            document.getElementById('child-selector');

         var childIdInput =
            document.getElementById('child-id-input');

         var selectedChildText =
            document.getElementById('selected-child-text');

         var childOptions =
            document.querySelectorAll('.child-option');


         if (
            childSelector &&
            childIdInput &&
            childrenProgress.length > 0
         ) {

            // =====================================================
            // LOAD SELECTED CHILD
            // =====================================================

            function loadSelectedChild(selectedId) {

               selectedId = Number(selectedId);

               var selectedChild =
                  childrenProgress.find(
                     function(child) {
                        return Number(child.id) === selectedId;
                     }
                  );


               // Anak tidak ditemukan
               if (!selectedChild) {
                  console.warn(
                     'Data progres anak tidak ditemukan:',
                     selectedId
                  );

                  return;
               }


               // =================================================
               // UPDATE TEXT BUTTON
               // =================================================

               if (selectedChildText) {

                  selectedChildText.textContent =
                     selectedChild.name;
               }


               // =================================================
               // UPDATE HIDDEN INPUT
               // =================================================

               childIdInput.value =
                  selectedChild.id;


               // =================================================
               // UPDATE ACTIVE MENU
               // =================================================

               childOptions.forEach(
                  function(option) {

                     var optionId =
                        Number(option.dataset.value);

                     if (
                        optionId ===
                        Number(selectedChild.id)
                     ) {

                        option.classList.add('active');
                        option.classList.add('text-white');

                     } else {

                        option.classList.remove('active');
                        option.classList.remove('text-white');
                     }

                  }
               );


               // =================================================
               // UPDATE TITLE
               // =================================================

               var title =
                  document.querySelector(
                     '#progress-belajar-card .card-title'
                  );


               if (title) {

                  title.innerHTML =
                     'Progress Belajar ' +
                     escapeHtml(selectedChild.name);
               }


               // =================================================
               // RENDER PROGRESS
               // =================================================

               renderLearningProgress(
                  selectedChild
               );
            }


            // =====================================================
            // CLICK CHILD OPTION
            // =====================================================

            childOptions.forEach(
               function(option) {

                  option.addEventListener(
                     'click',
                     function(event) {

                        event.preventDefault();

                        var selectedId =
                           this.dataset.value;

                        loadSelectedChild(
                           selectedId
                        );

                     }
                  );

               }
            );


            // =====================================================
            // LOAD FIRST CHILD
            // =====================================================

            loadSelectedChild(
               childIdInput.value
            );
         }

         // =========================================================
         // ESCAPE HTML
         // =========================================================

         function escapeHtml(value) {

            var div =
               document.createElement('div');

            div.textContent =
               value ?? '';

            return div.innerHTML;
         }


         // =========================================================
         // THEME CHANGE
         // =========================================================

         var mediaQuery =
            window.matchMedia(
               '(prefers-color-scheme: dark)'
            );


         mediaQuery.addEventListener(
            'change',
            function() {

               location.reload();

            }
         );

      });
   </script>

</body>

</html>