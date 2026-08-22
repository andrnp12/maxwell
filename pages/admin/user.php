<?php
require_once '../../src/classes/auth.php';
require_once '../../src/classes/user.php';

$auth = new auth();
$auth->authOrNot();

$userModel = new User();
$userResult = $userModel->getAllUsersWithQuizResults();
$users = $userResult['data'] ?? [];
?>

<!--header start-->
<?php include('../include/header.php'); ?>
<!--header end-->

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- ========== Topbar Start ========== -->
        <?php include('../include/topbar.php'); ?>
        <!-- ========== Topbar End ========== -->
        <!-- ========== Left Sidebar Start ========== -->
        <?php include('../include/sidebar-admin.php'); ?>
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
                                <h4 class="mb-sm-0 fw-bold mb-1">
                                    Lihat Ringkasan Pengguna
                                </h4>
                                <p class="text-muted">
                                    Kelola pengguna sistem anda.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm" style="border-radius: 1.25rem; overflow: hidden;">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        Ringkasan Pengguna
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered dt-responsive w-100" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>
                                                    No
                                                </th>
                                                <th>
                                                    Nama
                                                </th>
                                                <th>
                                                    Pretest
                                                </th>
                                                <th>
                                                    Kuis Rata2
                                                </th>
                                                <th>
                                                    Posttest
                                                </th>
                                                <th>
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php foreach ($users as $user): ?>
                                                <tr id="user-row-<?= $user['id'] ?>">
                                                    <td>
                                                        <?= $no++ ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($user['name'] ?? '-') ?>
                                                    </td>
                                                    <td>
                                                        <?= $user['pretest_nilai'] ?>
                                                    </td>
                                                    <td>
                                                        <?= $user['kuis_rata2'] ?>
                                                    </td>
                                                    <td>
                                                        <?= $user['posttest_nilai'] ?>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <a class="btn btn-primary btn-sm" href="detail-user.php?id=<?= $user['id'] ?>">
                                                                Lihat Detail
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($users)): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">Tidak ada data pengguna.</td>
                                                </tr>
                                            <?php endif; ?>
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