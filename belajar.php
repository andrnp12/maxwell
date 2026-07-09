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
                            <div class="row d-sm-flex align-items-center justify-content-between mb-2">
                                <h4 class="mb-sm-0 font-weight-bold mb-1">
                                    Modul Edukasi
                                </h4>
                                <p class="text-muted">
                                    Jelajahi berbagai modul edukasi yang tersedia!
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- end row -->
                    <div class="row">
                        <div class="col-12 col-xl-6 col-md-6">
                            <div class="card">
                                <div class="row g-0 align-items-center">
                                    <div class="col-3 text-center">
                                        <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items: center; justify-content: center;">
                                            <img src="assets/icon/focus-group.webp" alt="icon" style="width: 40px; height: 40px;" />
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-body">
                                            <h5 class="card-title mb-0 font-weight-bold">
                                                Sahabat Tumbuh
                                            </h5>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Perkumpulan tumbuh bersama.
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-6 col-md-6">
                            <div class="card">
                                <div class="row g-0 align-items-center">
                                    <div class="col-3 text-center">
                                        <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items: center; justify-content: center;">
                                            <img src="assets/icon/focus-group.webp" alt="icon" style="width: 40px; height: 40px;" />
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-body">
                                            <h5 class="card-title mb-0 font-weight-bold">
                                                Anti Nikah Dini
                                            </h5>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Perkumpulan tumbuh bersama.
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-6 col-md-6">
                            <div class="card">
                                <div class="row g-0 align-items-center">
                                    <div class="col-3 text-center">
                                        <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items: center; justify-content: center;">
                                            <img src="assets/icon/focus-group.webp" alt="icon" style="width: 40px; height: 40px;" />
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="card-body">
                                            <h5 class="card-title mb-0 font-weight-bold">
                                                Remaja Berencana
                                            </h5>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Perkumpulan tumbuh bersama.
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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