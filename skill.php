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
                                    Skill Saya
                                </h4>
                                <p class="text-muted">
                                    Kelola kompetensi Anda dengan mudah!
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- end row -->
                    <div class="row">
                        <!-- end card header -->
                        <div class="col-12">
                            <div class="carousel slide pointer-event" data-bs-ride="carousel" id="carouselExampleCaption">
                                <div class="carousel-inner" role="listbox">
                                    <div class="carousel-item">
                                        <img alt="..." class="d-block img-fluid mx-auto w-100" src="assets/images/small/img-7.jpg">
                                        <div class="carousel-caption d-block text-white-50">
                                            <h5 class="text-white">
                                                Komunikasi Asertif
                                            </h5>
                                            <p>
                                                Kemampuan untuk menyampaikan pikiran dan perasaan dengan jelas dan sopan.
                                            </p>
                                            <a class="btn btn-primary btn-sm btn-rounded" href="">
                                                Coba Sekarang
                                            </a>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img alt="..." class="d-block img-fluid mx-auto w-100" src="assets/images/small/img-5.jpg">
                                        <div class="carousel-caption d-block text-white-50">
                                            <h5 class="text-white">
                                                Problem Solving
                                            </h5>
                                            <p>
                                                Kemampuan untuk menyelesaikan masalah secara efektif dan efisien.
                                            </p>
                                            <a class="btn btn-primary btn-sm btn-rounded" href="">
                                                Coba Sekarang
                                            </a>
                                        </div>
                                    </div>
                                    <div class="carousel-item active">
                                        <img alt="..." class="d-block img-fluid mx-auto w-100" src="assets/images/small/img-4.jpg">
                                        <div class="carousel-caption d-block text-white-50">
                                            <h5 class="text-white">
                                                Leadership
                                            </h5>
                                            <p>
                                                Kemampuan untuk memimpin dan menginspirasi orang lain.
                                            </p>
                                            <a class="btn btn-primary btn-sm btn-rounded" href="">
                                                Coba Sekarang
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a class="carousel-control-prev" data-bs-slide="prev" href="#carouselExampleCaption" role="button">
                                    <span aria-hidden="true" class="carousel-control-prev-icon">
                                    </span>
                                    <span class="sr-only">
                                        Previous
                                    </span>
                                </a>
                                <a class="carousel-control-next" data-bs-slide="next" href="#carouselExampleCaption" role="button">
                                    <span aria-hidden="true" class="carousel-control-next-icon">
                                    </span>
                                    <span class="sr-only">
                                        Next
                                    </span>
                                </a>
                            </div>
                            <!-- end carousel -->
                        </div>
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between py-4">
                                <h5 class="mb-sm-0">
                                    Daftar Skill Tes
                                </h5>
                                <div class="page-title-right">
                                    <div class="btn-group">
                                        <button aria-expanded="false" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" type="button">
                                            Filter Skill
                                            <i class="mdi mdi-chevron-down">
                                            </i>
                                        </button>
                                        <div class="dropdown-menu dropdownmenu-primary" style="">
                                            <a class="dropdown-item" href="#">
                                                Semua Materi
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                Terbaru
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                Populer
                                            </a>
                                            <div class="dropdown-divider">
                                            </div>
                                            <a class="dropdown-item" href="#">
                                                Selesai Dibaca
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="col-12 col-xl-6 col-md-6" href="skill-detail.php">
                            <div class="card mb-3 border border-success">
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
                                            <p class="card-text mb-0">
                                                <small class="text-muted">
                                                    Perkumpulan tumbuh bersama.
                                                </small>
                                            </p>
                                            <p class="card-text">
                                                <small class="badge bg-success text-white px-2 py-1 rounded-pill">
                                                    <i class="mdi mdi-check-circle"></i>
                                                    Telah Lulus
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="col-12 col-xl-6 col-md-6">
                            <div class="card mb-3">
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
                            <div class="card mb-3">
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