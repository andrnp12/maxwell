<?php include 'component/header.php'; ?>

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php include 'component/topbar.php'; ?>
        <!-- ========== Left Sidebar Start ========== -->
        <?php include 'component/sidebar.php'; ?>
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
                                    Akun Saya
                                </h4>
                                <p class="text-muted">
                                    Kelola pengaturan akun dan informasi pribadi Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-xl-9 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm order-2 order-sm-1">
                                            <div class="d-flex align-items-center mt-3 mt-sm-0">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-xl me-3">
                                                        <img alt="" class="img-fluid rounded-circle d-block" src="../assets/images/users/avatar-2.jpg" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div>
                                                        <h5 class="mb-1">
                                                            Davied Indra Kurniawan
                                                        </h5>
                                                        <p class="text-muted mb-0">
                                                            Full Stack Developer
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-auto order-1 order-sm-2">
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                <div>
                                                    <a class="mdi mdi-pencil-box-outline text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" href="edit-profile.php">
                                                        Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                            <div class="tab-content">
                                <div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                Tentang Saya
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div>
                                                <div class="pb-3">
                                                    <div class="row">
                                                        <div class="col-xl">
                                                            <div class="text-muted">
                                                                <p class="mb-2">
                                                                    Hi I'm Phyllis Gatlin, Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end card body -->
                                    </div>
                                    <!-- end card -->
                                </div>
                                <!-- end tab pane -->
                            </div>
                            <!-- end tab content -->
                        </div>
                        <!-- end col -->
                        <div class="col-xl-3 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        Pengaturan Akun
                                    </h5>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <a class="pb-2 d-block text-muted" href="#">
                                            <i class="mdi mdi-note-text-outline text-primary me-1">
                                            </i>
                                            Ubah Profil
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a class="pb-2 d-block text-muted" href="#">
                                            <i class="mdi mdi-note-text-outline text-primary me-1">
                                            </i>
                                            Logout
                                        </a>
                                    </li>
                                </ul>
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
        <?php include 'component/footer.php'; ?>
    </div>
    <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <!-- Right Sidebar -->
    <?php include 'component/right-sidebar.php'; ?>
    <!-- /Right-bar -->
    <!-- Right bar overlay-->
    <div class="rightbar-overlay">
    </div>
    <!-- JAVASCRIPT -->
    <?php include 'component/script.php'; ?>
</body>

</html>