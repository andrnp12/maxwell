<?php
require_once '../../src/classes/auth.php';
$auth = new auth();
$auth->authOrNot();

$nama = $_SESSION['name'];
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
                           Halo, <?php echo $nama; ?>
                        </h4>
                        <p class="text-muted">
                           Siap untuk membantu lagi hari ini? Ayo mulai!
                        </p>
                     </div>
                  </div>
               </div>
               <!-- end page title -->
               <div class="row">
                  <div class="col-md-6">
                     <!-- card -->
                     <div class="card card-h-100">
                        <!-- card body -->
                        <div class="card-body">
                           <div class="row align-items-center">
                              <div class="col-6">
                                 <span class="text-muted mb-3 lh-1 d-block text-truncate">
                                    Total Pesan
                                 </span>
                                 <h4 class="mb-3">
                                    <span class="counter-value" data-target="520">
                                       0
                                    </span>
                                 </h4>
                              </div>
                              <div class="col-6 text-end">
                                 <i class="mdi mdi-message-outline text-primary display-6"></i>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col -->
                  <div class="col-md-6">
                     <!-- card -->
                     <div class="card card-h-100">
                        <!-- card body -->
                        <div class="card-body">
                           <div class="row align-items-center">
                              <div class="col-6">
                                 <span class="text-muted mb-3 lh-1 d-block text-truncate">
                                    Pesan Baru
                                 </span>
                                 <h4 class="mb-3">
                                    <span class="counter-value" data-target="6258">
                                       0
                                    </span>
                                 </h4>
                              </div>
                              <div class="col-6 text-end">
                                 <i class="mdi mdi-message-outline text-success display-6"></i>
                              </div>
                           </div>
                        </div>
                        <!-- end card body -->
                     </div>
                     <!-- end card -->
                  </div>
                  <!-- end col-->
               </div>
               <div class="row">
                  <div class="d-flex flex-wrap align-items-center mb-2">
                     <h5 class="font-weight-bold me-2">
                        Daftar Menu
                     </h5>
                  </div>
                  <div class="col-12">
                     <!-- card -->
                     <div class="card card-h-100">
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

</body>

</html>