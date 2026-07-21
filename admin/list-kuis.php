<?php
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
//     header('Location: login.php');
//     exit;
// }
?>

<!--header start-->
<?php include('component/header.php'); ?>
<!--header end-->

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- ========== Topbar Start ========== -->
        <?php include('component/topbar.php'); ?>
        <!-- ========== Topbar End ========== -->
        <!-- ========== Left Sidebar Start ========== -->
        <?php include('component/sidebar.php'); ?>
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12 d-sm-flex align-items-center justify-content-between mb-2">
                            <div class="row">
                                <h4 class="mb-sm-0 font-weight-bold mb-1">
                                    Lihat Semua Kuis
                                </h4>
                                <p class="text-muted">
                                    Kustomisasi kuis edukasi sesuai kebutuhan Anda!
                                </p>
                            </div>
                            <div>
                                <a class="btn btn-primary btn-rounded waves-effect mb-2" href="#" data-bs-toggle="modal" data-bs-target="#modalTambahKuis">
                                    <span>
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    Tambah Kuis
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        Daftar Kuis
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>
                                                    No.
                                                </th>
                                                <th>
                                                    Pertanyaan
                                                </th>
                                                <th>
                                                    Opsi A
                                                </th>
                                                <th>
                                                    Opsi B
                                                </th>
                                                <th>
                                                    Opsi C
                                                </th>
                                                <th>
                                                    Opsi D
                                                </th>
                                                <th>
                                                    Jawaban
                                                </th>
                                                <th>
                                                    aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    1
                                                </td>
                                                <td>
                                                    siapakah presiden kita
                                                </td>
                                                <td>
                                                    pak wowi
                                                </td>
                                                <td>
                                                    pak wowo
                                                </td>
                                                <td>
                                                    pak fufu
                                                </td>
                                                <td>
                                                    pak janjar
                                                </td>
                                                <td>
                                                    B
                                                </td>
                                                <td>
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditKuis" class="btn btn-sm btn-warning">Edit</a>
                                                    <button type="button" class=" btn btn-delete btn-sm btn-danger">Hapus</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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

            <!-- ========================================================= -->
            <!-- Bagian Pop-up tambah kuis (Modal) -->
            <!-- ========================================================= -->
            <div class="modal fade" id="modalTambahKuis" tabindex="-1" aria-labelledby="modalTambahKuisLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Header Modal -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahKuisLabel">Form Tambah Kuis</h5>
                            <!-- Tombol silang untuk menutup modal -->
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                        <form action="" method="POST">
                            <div class="modal-body">
                                <!-- Contoh Inputan -->
                                <div class="mb-3">
                                    <label for="pertanyaan" class="form-label">Pertanyaan</label>
                                    <input type="text" class="form-control" id="pertanyaan" name="pertanyaan" required>
                                </div>

                                <div class="mb-3">
                                    <label for="opsiA" class="form-label">Opsi A</label>
                                    <input type="text" class="form-control" id="opsiA" name="opsiA" required>
                                </div>
                                <div class="mb-3">
                                    <label for="opsiB" class="form-label">Opsi B</label>
                                    <input type="text" class="form-control" id="opsiB" name="opsiB" required>
                                </div>
                                <div class="mb-3">
                                    <label for="opsiC" class="form-label">Opsi C</label>
                                    <input type="text" class="form-control" id="opsiC" name="opsiC" required>
                                </div>
                                <div class="mb-3">
                                    <label for="opsiD" class="form-label">Opsi D</label>
                                    <input type="text" class="form-control" id="opsiD" name="opsiD" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        Jawaban
                                    </label>
                                    <select class="form-select" aria-label="Default select example" required>
                                        <option>
                                            A
                                        </option>
                                        <option>
                                            B
                                        </option>
                                        <option>
                                            C
                                        </option>
                                        <option>
                                            D
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Footer Modal (Tombol Aksi) -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" name="simpan_kuis">Simpan Kuis</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- Bagian Pop-up edit kuis (Modal) -->
            <!-- ========================================================= -->
            <div class="modal fade" id="modalEditKuis" z-index="999" aria-labelledby="modalEditKuisLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Header Modal -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditKuisLabel">Form Edit Kuis</h5>
                            <!-- Tombol silang untuk menutup modal -->
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                        <form action="" method="POST">
                            <div class="modal-body">
                                <!-- Contoh Inputan -->
                                <div class="mb-3">
                                    <label for="pertanyaan" class="form-label">Pertanyaan</label>
                                    <input type="text" class="form-control" id="pertanyaan" name="pertanyaan" required>
                                </div>

                                <div class="mb-3">
                                    <label for="opsiA" class="form-label">Opsi A</label>
                                    <input type="text" class="form-control" id="opsiA" name="opsiA" required>
                                </div>
                                <div class="mb-3">
                                    <label for="opsiB" class="form-label">Opsi B</label>
                                    <input type="text" class="form-control" id="opsiB" name="opsiB" required>
                                </div>
                                <div class="mb-3">
                                    <label for="opsiC" class="form-label">Opsi C</label>
                                    <input type="text" class="form-control" id="opsiC" name="opsiC" required>
                                </div>
                                <div class="mb-3">
                                    <label for="opsiD" class="form-label">Opsi D</label>
                                    <input type="text" class="form-control" id="opsiD" name="opsiD" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        Jawaban
                                    </label>
                                    <select class="form-select" aria-label="Default select example" required>
                                        <option>
                                            A
                                        </option>
                                        <option>
                                            B
                                        </option>
                                        <option>
                                            C
                                        </option>
                                        <option>
                                            D
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Footer Modal (Tombol Aksi) -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" name="simpan_kuis">Simpan Kuis</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <!-- Footer Start -->
            <?php include("component/footer.php"); ?>
            <!-- end Footer -->
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <!-- Right Sidebar -->
    <?php include("component/right-sidebar.php"); ?>
    <!-- /Right-bar -->
    <!-- javascript -->
    <?php include("component/script.php"); ?>
    <!-- end javascript -->

</body>