<?php

require_once '../../src/classes/auth.php';
require_once '../../src/classes/konselor.php';

$auth = new auth();
$auth->authOrNot();

$data = new Konsultan();

$nama = $_SESSION['username'];
$id = $_SESSION['id'];

$totalPesan = $data->totalPesan($id);
$totalKonsultasi = $data->totalKonsultasi($id);

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
      <?php include('../include/sidebar-konsultan.php'); ?>
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
                           Halo, <?= htmlspecialchars($nama) ?>
                        </h4>
                        <p class="text-muted">
                           Siap untuk membantu lagi hari ini? Ayo mulai!
                        </p>
                     </div>
                  </div>
               </div>
               <!-- end page title -->
               <!-- Stats Cards Row - style like pages/ortu/belajar.php -->
               <div class="row mb-2">
                  <div class="col-12">
                     <div class="row">
                        <!-- Total Pesan Card -->
                        <div class="col-12 col-xl-6 col-md-6">
                           <a class="col-12" href="chat.php">
                              <div class="card mb-3 border-white shadow-sm" style="border-radius: 1.25rem; background-image: radial-gradient( circle farthest-corner at 10% 20%,  rgba(243,218,216,1) 0%, rgba(255,191,205,1) 36.4%, rgba(248,86,172,1) 90% );">
                                 <div class="row g-0 align-items-center">
                                    <div class="col-9">
                                       <div class="card-body">
                                          <h5 class="card-title mb-0 font-weight-bold">
                                             Total Pesan
                                          </h5>
                                          <p class="card-text mb-0">
                                             <small class="text-muted text-truncate d-block" style="max-width: 100%;">
                                                <span class="counter-value text-primary fw-bold" data-target="<?= $totalPesan; ?>" style="font-size: 1.25rem;">0</span> Pesan
                                             </small>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-3 text-center">
                                       <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items: center; justify-content: center;">
                                          <img src="/assets/icon/greeting.webp" alt="icon" style="width: 40px; height: 40px;" />
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </a>
                        </div>
                        <!-- Total Konsultasi Card -->
                        <div class="col-12 col-xl-6 col-md-6">
                           <a class="col-12" href="chat.php">
                              <div class="card mb-3 border-white shadow-sm" style="border-radius: 1.25rem; background-image: linear-gradient( 109.6deg,  rgba(245,239,249,1) 30.1%, rgba(207,211,236,1) 100.2% );">
                                 <div class="row g-0 align-items-center">
                                    <div class="col-9">
                                       <div class="card-body">
                                          <h5 class="card-title mb-0 font-weight-bold">
                                             Total Konsultasi
                                          </h5>
                                          <p class="card-text mb-0">
                                             <small class="text-muted text-truncate d-block" style="max-width: 100%;">
                                                <span class="counter-value text-success fw-bold" data-target="<?= $totalKonsultasi; ?>" style="font-size: 1.25rem;">0</span> Konsultasi
                                             </small>
                                          </p>
                                       </div>
                                    </div>
                                    <div class="col-3 text-center">
                                       <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items: center; justify-content: center;">
                                          <img src="/assets/icon/3d-question-mark.webp" alt="icon" style="width: 40px; height: 40px;" />
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- end stats row -->
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
                        <a class="card-body" href="chat.php">
                           <div class="row align-items-center">
                              <div class="col-12">
                                 <h4 class="mb-3">
                                    <img src="/assets/icon/speech.webp" alt="icon" style="width: 40px; height: 40px;" />
                                 </h4>
                                 <span class="text-muted lh-1 d-block text-truncate">
                                    Menu Chat Pesan
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

   <script>
      // Counter animation for stats cards
      document.addEventListener('DOMContentLoaded', function() {
         const counters = document.querySelectorAll('.counter-value');
         const speed = 200; // Animation speed

         counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const updateCount = () => {
               const count = +counter.innerText;
               const increment = target / speed;

               if (count < target) {
                  counter.innerText = Math.ceil(count + increment);
                  setTimeout(updateCount, 1);
               } else {
                  counter.innerText = target;
               }
            };
            updateCount();
         });
      });
   </script>

</body>

</html>