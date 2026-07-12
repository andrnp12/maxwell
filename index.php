<?php
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
   header('Location: login.php');
   exit;
}
?>

<!--header start-->
<?php include('src/include/header.php'); ?>
<!--headere end-->

<body>
   <!-- <body data-layout="horizontal"> -->
   <!-- Begin page -->
   <div id="layout-wrapper">
      <!-- ========== Topbar Start ========== -->
      <?php include('src/include/topbar.php'); ?>
      <!-- ========== Topbar End ========== -->
      <!-- ========== Left Sidebar Start ========== -->
      <?php include('src/include/sidebar.php'); ?>
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
                           Siap untuk belajar lagi hari ini? Ayo mulai!
                        </p>
                     </div>
                  </div>
               </div>
               <!-- end page title -->
               <div class="row">
                  <div class="col-xl-12">
                     <!-- card -->
                     <div class="card card-h-100">
                        <!-- card body -->
                        <div class="card-body">
                           <div class="d-flex flex-wrap align-items-center mb-4">
                              <h4 class="card-title me-2">
                                 Progress Belajar
                              </h4>
                           </div>
                           <div class="row align-items-center">
                              <div class="col-sm">
                                 <div class="apex-charts" data-colors='["#5156be", "#34c38f"]' id="invested-overview">
                                 </div>
                              </div>
                              <div class="col-sm align-self-center">
                                 <div class="mt-4 mt-sm-0">
                                    <div>
                                       <p class="mb-2">
                                          Terus belajar dan kembangkan diri kamu untuk menjadi lebih baik lagi!
                                       </p>
                                       <button class="btn btn-primary btn-rounded btn-sm waves-effect waves-light" type="button">
                                          Lihat Detail
                                          <span>
                                             <i class="fas fa-angle-right"></i>
                                          </span>
                                       </button>
                                    </div>
                                 </div>
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
                  <div class="d-flex flex-wrap align-items-center mb-4">
                     <h5 class="font-weight-bold me-2">
                        Daftar Menu
                     </h5>
                  </div>
                  <div class="col-6 col-xl-3 col-md-6">
                     <!-- card -->
                     <div class="card card-h-100">
                        <!-- card body -->
                        <div class="card-body">
                           <div class="row align-items-center">
                              <div class="col-12">
                                 <h4 class="mb-3">
                                    <img src="assets/icon/mortarboard.webp" alt="icon" style="width: 40px; height: 40px;" />
                                 </h4>
                                 <span class="text-muted lh-1 d-block text-truncate">
                                    Modul Edukasi
                                 </span>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                  <div class="col-6 col-xl-3 col-md-6">
                     <!-- card -->
                     <div class="card card-h-100">
                        <!-- card body -->
                        <div class="card-body">
                           <div class="row align-items-center">
                              <div class="col-12">
                                 <h4 class="mb-3">
                                    <img src="assets/icon/edition.webp" alt="icon" style="width: 40px; height: 40px;" />
                                 </h4>
                                 <span class="text-muted lh-1 d-block text-truncate">
                                    Kelas Skill
                                 </span>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                  <div class="col-6 col-xl-3 col-md-6">
                     <!-- card -->
                     <div class="card card-h-100">
                        <!-- card body -->
                        <div class="card-body">
                           <div class="row align-items-center">
                              <div class="col-12">
                                 <h4 class="mb-3">
                                    <img src="assets/icon/slack.webp" alt="icon" style="width: 40px; height: 40px;" />
                                 </h4>
                                 <span class="text-muted lh-1 d-block text-truncate">
                                    Kom. Sebaya
                                 </span>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                  <div class="col-6 col-xl-3 col-md-6">
                     <!-- card -->
                     <div class="card card-h-100">
                        <!-- card body -->
                        <div class="card-body">
                           <div class="row align-items-center">
                              <div class="col-12">
                                 <h4 class="mb-3">
                                    <img src="assets/icon/like.webp" alt="icon" style="width: 40px; height: 40px;" />
                                 </h4>
                                 <span class="text-muted lh-1 d-block text-truncate">
                                    Kesiapan
                                 </span>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                  <div class="col-6 col-xl-3 col-md-6">
                     <!-- card -->
                     <div class="card card-h-100">
                        <!-- card body -->
                        <div class="card-body">
                           <div class="row align-items-center">
                              <div class="col-12">
                                 <h4 class="mb-3">
                                    <img src="assets/icon/whatsapp.webp" alt="icon" style="width: 40px; height: 40px;" />
                                 </h4>
                                 <span class="text-muted lh-1 d-block text-truncate">
                                    Konseling
                                 </span>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                  <div class="col-6 col-xl-3 col-md-6">
                     <!-- card -->
                     <div class="card card-h-100">
                        <!-- card body -->
                        <div class="card-body">
                           <div class="row align-items-center">
                              <div class="col-12">
                                 <h4 class="mb-3">
                                    <img src="assets/icon/family.webp" alt="icon" style="width: 40px; height: 40px;" />
                                 </h4>
                                 <span class="text-muted lh-1 d-block text-truncate">
                                    Ortu Peduli
                                 </span>
                              </div>
                           </div>
                        </div>
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
         <?php include("src/include/footer.php"); ?>
         <!-- end Footer -->
      </div>
      <!-- end main content-->
   </div>
   <!-- END layout-wrapper -->
   <!-- Right Sidebar -->
   <?php include("src/include/right-sidebar.php"); ?>
   <!-- /Right-bar -->
   <!-- javascript -->
   <?php include("src/include/script.php"); ?>
   <!-- end javascript -->

</body>

</html>
