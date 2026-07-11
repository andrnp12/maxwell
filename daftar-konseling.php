<?php include 'src/include/header.php'; ?>

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php include 'src/include/topbar.php'; ?>
        <!-- ========== Left Sidebar Start ========== -->
        <?php include 'src/include/sidebar.php'; ?>
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
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="komunitas.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Daftar Konselor
                                    </h4>
                                    <p class="text-muted mb-0">
                                        Temukan konselor yang sesuai dengan kebutuhan Anda.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- end row -->
                    <div class="row">
                        <div class="col-xl-3 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="mx-auto mb-4">
                                        <img alt="" class="avatar-xl rounded-circle img-thumbnail" src="assets/images/users/avatar-2.jpg">
                                    </div>
                                    <h5 class="font-size-16 mb-1">
                                        <a class="text-body" href="#">
                                            Phyllis Gatlin
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-2">
                                        Full Stack Developer
                                    </p>
                                </div>
                                <div class="btn-group" role="group">
                                    <a class="btn btn-outline-light text-truncate" href="profil-konseling.php">
                                        <i class="uil uil-user me-1">
                                        </i>
                                        Profile
                                    </a>
                                    <button class="btn btn-outline-light text-truncate" type="button">
                                        <i class="uil uil-envelope-alt me-1">
                                        </i>
                                        Message
                                    </button>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                        <div class="col-xl-3 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="mx-auto mb-4">
                                        <img alt="" class="avatar-xl rounded-circle img-thumbnail" src="assets/images/users/avatar-1.jpg">
                                    </div>
                                    <h5 class="font-size-16 mb-1">
                                        <a class="text-body" href="#">
                                            James Nix
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-2">
                                        Full Stack Developer
                                    </p>
                                </div>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-light text-truncate" type="button">
                                        <i class="uil uil-user me-1">
                                        </i>
                                        Profile
                                    </button>
                                    <button class="btn btn-outline-light text-truncate" type="button">
                                        <i class="uil uil-envelope-alt me-1">
                                        </i>
                                        Message
                                    </button>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                        <div class="col-xl-3 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="mx-auto mb-4">
                                        <img alt="" class="avatar-xl rounded-circle img-thumbnail" src="assets/images/users/avatar-3.jpg">
                                    </div>
                                    <h5 class="font-size-16 mb-1">
                                        <a class="text-body" href="#">
                                            Darlene Smith
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-2">
                                        UI/UX Designer
                                    </p>
                                </div>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-light text-truncate" type="button">
                                        <i class="uil uil-user me-1">
                                        </i>
                                        Profile
                                    </button>
                                    <button class="btn btn-outline-light text-truncate" type="button">
                                        <i class="uil uil-envelope-alt me-1">
                                        </i>
                                        Message
                                    </button>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <?php include 'src/include/footer.php'; ?>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <!-- Right Sidebar -->
    <?php include 'src/include/right-sidebar.php'; ?>
    <!-- /Right-bar -->
    <!-- Right bar overlay-->
    <div class="rightbar-overlay">
    </div>
    <!-- JAVASCRIPT -->
    <?php include 'src/include/script.php'; ?>
</body>

</html>