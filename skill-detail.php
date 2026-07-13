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
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="skill.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Detail Pelatihan Skill
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm mb-4">
                                            <div class="d-flex align-items-center mt-3 mt-sm-0">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-xl me-3">
                                                        <img alt="" class="img-fluid" src="assets/icon/focus-group.webp" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div>
                                                        <h5 class="mb-1">
                                                            Tumbuh Bersama Pelatihan Kepemimpinan
                                                        </h5>
                                                        <p class="badge bg-light text-dark rounded-pill mb-0">
                                                            5 Soal pilihan ganda
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
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">
                                            Soal Pertanyaan
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="twitter-bs-wizard" id="basic-pills-wizard">
                                            <ul class="twitter-bs-wizard-nav nav nav-pills nav-justified" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" data-toggle="tab" href="#pertanyaan1" aria-selected="false" role="tab" tabindex="-1">
                                                        <div class="step-icon" data-bs-placement="top" data-bs-toggle="tooltip" aria-label="Seller Details" data-bs-original-title="Seller Details">
                                                            1
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" data-toggle="tab" href="#pertanyaan2" aria-selected="false" role="tab" tabindex="-1">
                                                        <div class="step-icon" data-bs-placement="top" data-bs-toggle="tooltip" aria-label="Company Document" data-bs-original-title="Company Document">
                                                            2
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" data-toggle="tab" href="#pertanyaan3" aria-selected="true" role="tab">
                                                        <div class="step-icon" data-bs-placement="top" data-bs-toggle="tooltip" aria-label="Bank Details" data-bs-original-title="Bank Details">
                                                            3
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                            <!-- wizard-nav -->
                                            <div class="tab-content twitter-bs-wizard-tab-content">
                                                <div class="tab-pane" id="pertanyaan1" role="tabpanel">
                                                    <div class="text-start mb-4">
                                                        <h5>
                                                            Pertanyaan 1
                                                        </h5>
                                                        <p class="card-title-desc">
                                                            Seleksi jawaban yang paling tepat dari pertanyaan berikut ini.
                                                        </p>
                                                    </div>
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
                                                    <div class="d-grid gap-2">
                                                        <div class="form-check border rounded p-3">
                                                            <input class="form-check-input" type="radio" name="jawaban1" id="jawaban1_1" value="A">
                                                            <label class="form-check-label w-100" for="jawaban1_1">
                                                                A. Jawaban Benar
                                                            </label>
                                                        </div>
                                                        <div class="form-check border rounded p-3">
                                                            <input class="form-check-input" type="radio" name="jawaban1" id="jawaban1_2" value="B">
                                                            <label class="form-check-label w-100" for="jawaban1_2">
                                                                B. Jawaban Salah
                                                            </label>
                                                        </div>
                                                        <div class="form-check border rounded p-3">
                                                            <input class="form-check-input" type="radio" name="jawaban1" id="jawaban1_3" value="C">
                                                            <label class="form-check-label w-100" for="jawaban1_3">
                                                                C. Jawaban Tidak Salah Tidak Benar
                                                            </label>
                                                        </div>
                                                        <div class="form-check border rounded p-3">
                                                            <input class="form-check-input" type="radio" name="jawaban1" id="jawaban1_4" value="D">
                                                            <label class="form-check-label w-100" for="jawaban1_4">
                                                                D. Tidak Ada Jawaban Yang Benar
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <ul class="pager wizard twitter-bs-wizard-pager-link">
                                                        <li class="next disabled">
                                                            <a class="btn btn-primary btn-sm btn-rounded" href="javascript: void(0);">
                                                                Soal Selanjutnya
                                                                <i class="bx bx-chevron-right ms-1">
                                                                </i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!-- tab pane -->
                                                <div class="tab-pane" id="pertanyaan2" role="tabpanel">
                                                    <div>
                                                        <div class="text-start mb-4">
                                                            <h5>
                                                                Pertanyaan 2
                                                            </h5>
                                                            <p class="card-title-desc">
                                                                Seleksi jawaban yang paling tepat dari pertanyaan berikut ini.
                                                            </p>
                                                        </div>
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
                                                        <div class="d-grid gap-2">
                                                            <div class="form-check border rounded p-3">
                                                                <input class="form-check-input" type="radio" name="jawaban2" id="jawaban2_1" value="A">
                                                                <label class="form-check-label w-100" for="jawaban2_1">
                                                                    A. Jawaban Benar
                                                                </label>
                                                            </div>
                                                            <div class="form-check border rounded p-3">
                                                                <input class="form-check-input" type="radio" name="jawaban2" id="jawaban2_2" value="B">
                                                                <label class="form-check-label w-100" for="jawaban2_2">
                                                                    B. Jawaban Salah
                                                                </label>
                                                            </div>
                                                            <div class="form-check border rounded p-3">
                                                                <input class="form-check-input" type="radio" name="jawaban2" id="jawaban2_3" value="C">
                                                                <label class="form-check-label w-100" for="jawaban2_3">
                                                                    C. Jawaban Tidak Salah Tidak Benar
                                                                </label>
                                                            </div>
                                                            <div class="form-check border rounded p-3">
                                                                <input class="form-check-input" type="radio" name="jawaban2" id="jawaban2_4" value="D">
                                                                <label class="form-check-label w-100" for="jawaban2_4">
                                                                    D. Tidak Ada Jawaban Yang Benar
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <ul class="pager wizard twitter-bs-wizard-pager-link">
                                                            <li class="previous">
                                                                <a class="btn btn-outline-light btn-sm btn-rounded" href="javascript: void(0);">
                                                                    <i class="bx bx-chevron-left me-1">
                                                                    </i>
                                                                    Sebelumnya
                                                                </a>
                                                            </li>
                                                            <li class="next disabled">
                                                                <a class="btn btn-primary btn-sm btn-rounded" href="javascript: void(0);">
                                                                    Soal Selanjutnya
                                                                    <i class="bx bx-chevron-right ms-1">
                                                                    </i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- tab pane -->
                                                <div class="tab-pane active show" id="pertanyaan3" role="tabpanel">
                                                    <div>
                                                        <div class="text-start mb-4">
                                                            <h5>
                                                                Pertanyaan 3
                                                            </h5>
                                                            <p class="card-title-desc">
                                                                Seleksi jawaban yang paling tepat dari pertanyaan berikut ini.
                                                            </p>
                                                        </div>
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
                                                        <div class="d-grid gap-2">
                                                            <div class="form-check border rounded p-3">
                                                                <input class="form-check-input" type="radio" name="jawaban3" id="jawaban3_1" value="A">
                                                                <label class="form-check-label w-100" for="jawaban3_1">
                                                                    A. Jawaban Benar
                                                                </label>
                                                            </div>
                                                            <div class="form-check border rounded p-3">
                                                                <input class="form-check-input" type="radio" name="jawaban3" id="jawaban3_2" value="B">
                                                                <label class="form-check-label w-100" for="jawaban3_2">
                                                                    B. Jawaban Salah
                                                                </label>
                                                            </div>
                                                            <div class="form-check border rounded p-3">
                                                                <input class="form-check-input" type="radio" name="jawaban3" id="jawaban3_3" value="C">
                                                                <label class="form-check-label w-100" for="jawaban3_3">
                                                                    C. Jawaban Tidak Salah Tidak Benar
                                                                </label>
                                                            </div>
                                                            <div class="form-check border rounded p-3">
                                                                <input class="form-check-input" type="radio" name="jawaban3" id="jawaban3_4" value="D">
                                                                <label class="form-check-label w-100" for="jawaban3_4">
                                                                    D. Tidak Ada Jawaban Yang Benar
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <ul class="pager wizard twitter-bs-wizard-pager-link">
                                                            <li class="previous">
                                                                <a class="btn btn-outline-light btn-sm btn-rounded" href="javascript: void(0);">
                                                                    <i class="bx bx-chevron-left me-1">
                                                                    </i>
                                                                    Sebelumnya
                                                                </a>
                                                            </li>
                                                            <li class="float-end">
                                                                <a class="btn btn-primary btn-sm btn-rounded" data-bs-target=".confirmModal" data-bs-toggle="modal" href="javascript: void(0);">
                                                                    Simpan Jawaban
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- tab pane -->
                                            </div>
                                            <!-- end tab content -->
                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                            </div>
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