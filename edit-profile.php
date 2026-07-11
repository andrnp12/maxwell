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
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="profile.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Edit Profil Saya
                                    </h4>
                                    <p class="text-muted mb-0">
                                        Kelola pengaturan akun dan informasi pribadi Anda.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-xl-9 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mt-2">
                                        <h5 class="font-weight-bold mb-3">
                                            Informasi Akun
                                        </h5>
                                        <form>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="commentprofile-input">
                                                            Ganti Foto Profil
                                                        </label>
                                                        <input class="form-control" id="commentprofile-input" placeholder="Ganti Foto Profil" type="file">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="commentname-input">
                                                            Nama
                                                        </label>
                                                        <input class="form-control" id="commentname-input" placeholder="Masukkan nama" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="commentemail-input">
                                                            Email
                                                        </label>
                                                        <input class="form-control" id="commentemail-input" placeholder="Masukkan email" type="email">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="commentpassword-input">
                                                            Password
                                                        </label>
                                                        <input class="form-control" id="commentpassword-input" placeholder="Masukkan password" type="password">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="commentmessage-input">
                                                    Ringkasan Singkat Anda
                                                </label>
                                                <textarea class="form-control" id="commentmessage-input" placeholder="Ringkasan..." rows="3"></textarea>
                                            </div>
                                            <div class="text-end">
                                                <button class="btn btn-primary btn-sm btn-rounded" type="submit">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
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