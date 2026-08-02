<?php
require_once '../../src/classes/auth.php';
$auth = new auth();
$auth->authOrNot();
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
                                <h4 class="mb-sm-0 font-weight-bold mb-1">
                                    Lihat Ringkasan Pengguna
                                </h4>
                                <p class="text-muted">
                                    Kelola pengguna anda.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        Daftar Pengguna
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Name
                                                </th>
                                                <th>
                                                    Position
                                                </th>
                                                <th>
                                                    Office
                                                </th>
                                                <th>
                                                    Age
                                                </th>
                                                <th>
                                                    Start date
                                                </th>
                                                <th>
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Tiger Nixon
                                                </td>
                                                <td>
                                                    System Architect
                                                </td>
                                                <td>
                                                    Edinburgh
                                                </td>
                                                <td>
                                                    61
                                                </td>
                                                <td>
                                                    2011/04/25
                                                </td>
                                                <td>
                                                    $120,000
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Garrett Winters
                                                </td>
                                                <td>
                                                    Accountant
                                                </td>
                                                <td>
                                                    Tokyo
                                                </td>
                                                <td>
                                                    63
                                                </td>
                                                <td>
                                                    2011/07/25
                                                </td>
                                                <td>
                                                    $170,750
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Ashton Cox
                                                </td>
                                                <td>
                                                    Junior Technical Author
                                                </td>
                                                <td>
                                                    San Francisco
                                                </td>
                                                <td>
                                                    66
                                                </td>
                                                <td>
                                                    2009/01/12
                                                </td>
                                                <td>
                                                    $86,000
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Cedric Kelly
                                                </td>
                                                <td>
                                                    Senior Javascript Developer
                                                </td>
                                                <td>
                                                    Edinburgh
                                                </td>
                                                <td>
                                                    22
                                                </td>
                                                <td>
                                                    2012/03/29
                                                </td>
                                                <td>
                                                    $433,060
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Airi Satou
                                                </td>
                                                <td>
                                                    Accountant
                                                </td>
                                                <td>
                                                    Tokyo
                                                </td>
                                                <td>
                                                    33
                                                </td>
                                                <td>
                                                    2008/11/28
                                                </td>
                                                <td>
                                                    <div>
                                                        <a class="btn btn-primary btn-sm" href="detail-user.php">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Brielle Williamson
                                                </td>
                                                <td>
                                                    Integration Specialist
                                                </td>
                                                <td>
                                                    New York
                                                </td>
                                                <td>
                                                    61
                                                </td>
                                                <td>
                                                    2012/12/02
                                                </td>
                                                <td>
                                                    $372,000
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