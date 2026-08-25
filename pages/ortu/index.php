<?php

require_once '../../src/classes/auth.php';
require_once '../../src/classes/profile.php';

$auth = new auth();
$auth->authOrNot();

$userId = (int) $_SESSION['id'];

// Placeholder for future child linking - for now show 0 progress
$combinedProgress = 0;
$pretestCompletion = 0;
$kuisCompletion = 0;
$materiCompletion = 0;
$posttestCompletion = 0;
$completedMateri = 0;
$totalMateri = 0;
$completedKuis = 0;
$totalKuis = 0;
$completedPretest = 0;
$totalPretest = 0;
$completedPosttest = 0;
$totalPosttest = 0;
$completedActivities = 0;
$totalActivities = 0;

$user = ['name' => 'Orang Tua']; // Placeholder, will use session name
$user = new Profile();
$user = $user->getProfile($_SESSION['id']);

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
                           Halo, Davied Indra!
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
                                 Progress Belajar <?= htmlspecialchars($_SESSION['name'] ?? 'Orang Tua') ?>
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
         // Chart data - placeholder 0 values (will be connected via child ID later)
         var combinedProgress = 0;
         var pretestCompletion = 0;
         var kuisCompletion = 0;
         var materiCompletion = 0;
         var posttestCompletion = 0;

         var completedMateri = 0;
         var totalMateri = 0;
         var completedKuis = 0;
         var totalKuis = 0;
         var completedPretest = 0;
         var totalPretest = 0;
         var completedPosttest = 0;
         var totalPosttest = 0;
         var completedActivities = 0;
         var totalActivities = 0;

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
                           <div class="fw-bold" style="color: ` + whiteColor + `;">0%</div>
                           <div style="color: ` + mutedWhite + `;">Materi</div>
                           <div class="small" style="color: ` + mutedWhite + `;">0/0</div>
                       </div>
                       <div class="col-3">
                           <div class="fw-bold" style="color: ` + whiteColor + `;">0%</div>
                           <div style="color: ` + mutedWhite + `;">Kuis</div>
                           <div class="small" style="color: ` + mutedWhite + `;">0/0</div>
                       </div>
                       <div class="col-3">
                           <div class="fw-bold" style="color: ` + whiteColor + `;">0%</div>
                           <div style="color: ` + mutedWhite + `;">Pre-Test</div>
                           <div class="small" style="color: ` + mutedWhite + `;">0/0</div>
                       </div>
                       <div class="col-3">
                           <div class="fw-bold" style="color: ` + whiteColor + `;">0%</div>
                           <div style="color: ` + mutedWhite + `;">Post-Test</div>
                           <div class="small" style="color: ` + mutedWhite + `;">0/0</div>
                       </div>
                   </div>
                   <div class="text-center mt-2 small" style="color: ` + mutedWhite + `;">
                       <i class="mdi mdi-information-outline"></i>
                       Total: <strong style="color: ` + whiteColor + `;">0/0</strong> aktivitas (<strong style="color: ` + whiteColor + `;">0%</strong>)
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