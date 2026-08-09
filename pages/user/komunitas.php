<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../../src/classes/konselor.php';
require_once '../../src/classes/komunitas.php';
require_once '../../src/classes/auth.php';

$auth = new auth();
$data = new Konsultan();
$dataKomunitas = new Komunitas();

$auth->authOrNot();
$komunitas = $dataKomunitas->getTopKomunitas((int) $_SESSION['id']);
$konsultan = $data->getTopKonsultan();

include '../include/header.php';
?>


<?php include '../include/header.php'; ?>

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php include '../include/topbar.php'; ?>
        <!-- ========== Left Sidebar Start ========== -->
        <?php include '../include/sidebar.php'; ?>
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
                                    Forum
                                </h4>
                                <p class="text-muted">
                                    Bergabunglah dengan komunitas dan berinteraksi dengan sesama!
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- end row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between pb-4">
                                <h5 class="mb-sm-0">
                                    Daftar Komunitas
                                </h5>
                                <div class="page-title-right">
                                    <a class="btn btn-primary btn-rounded btn-sm waves-effect waves-light" href="daftar-komunitas.php">
                                        Lihat Semua
                                        <span>
                                            <i class="fas fa-angle-right"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php foreach ($komunitas as $kom) : ?>
                            <div class="col-12 col-xl-6 col-md-6">
                                <div class="card mb-3">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-3 text-center">
                                            <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items: center; justify-content: center;">
                                                <img src="/assets/icon/focus-group.webp" alt="icon" style="width: 40px; height: 40px;" />
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="card-body">
                                                <h5 class="card-title mb-0 font-weight-bold">
                                                    <?= $kom['nama_komunitas'] ?>
                                                </h5>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        <?= $kom['deskripsi'] ?>
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="btn-group" role="group">

                                            <?php if ($kom['is_member']) : ?>

                                                <a href="chat.php?id=<?= $kom['id'] ?>&type=group"
                                                    class="btn btn-primary">
                                                    <i class="uil uil-comments me-1"></i>
                                                    Buka Chat
                                                </a>

                                            <?php else : ?>

                                                <button
                                                    class="btn btn-success btn-join"
                                                    data-id="<?= $kom['id'] ?>">
                                                    <i class="uil uil-users-alt me-1"></i>
                                                    Gabung Komunitas
                                                </button>

                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- end col -->
                    </div>
                    <!-- end row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between pb-4">
                                <h5 class="mb-sm-0">
                                    Hubungi Konseling
                                </h5>
                                <div class="page-title-right">
                                    <a class="btn btn-primary btn-rounded btn-sm waves-effect waves-light" href="daftar-konseling.php">
                                        Lihat Semua
                                        <span>
                                            <i class="fas fa-angle-right"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php foreach ($konsultan as $konsul) : ?>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <div class="mx-auto mb-4">
                                            <img alt="" class="avatar-xl rounded-circle img-thumbnail" src="/assets/images/users/avatar-2.jpg">
                                        </div>
                                        <h5 class="font-size-16 mb-1">
                                            <a class="text-body" href="#">
                                                <?= $konsul['name'] ?>
                                            </a>
                                        </h5>
                                        <p class="text-muted mb-2">
                                            <?= $konsul['deskripsi'] ?>
                                        </p>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-secondary text-truncate" type="button">
                                            <i class="uil uil-user me-1">
                                            </i>
                                            Profile
                                        </button>
                                        <button class="btn btn-primary text-truncate" type="button">
                                            <a href="chat.php?id=<?= $konsul['id'] ?>&type=personal"
                                                class="text-decoration-none text-white">
                                                <i class="uil uil-envelope-alt me-1"></i>
                                                Message
                                            </a>
                                        </button>
                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                        <?php endforeach; ?>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <?php include '../include/footer.php'; ?>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <!-- Right Sidebar -->
    <?php include '../include/right-sidebar.php'; ?>
    <!-- /Right-bar -->
    <!-- Right bar overlay-->
    <div class="rightbar-overlay">
    </div>
    <!-- JAVASCRIPT -->
    <?php include '../include/script.php'; ?>

    <script>
        document.querySelectorAll(".btn-join").forEach(button => {

            button.addEventListener("click", async function(e) {

                e.preventDefault();

                const btn = e.currentTarget;
                const idKomunitas = btn.dataset.id;

                btn.disabled = true;
                btn.innerHTML = "Menggabungkan...";

                const formData = new FormData();

                formData.append("action", "join_group");
                formData.append("id_komunitas", idKomunitas);

                try {

                    const response = await fetch(
                        "/src/actions/proses_komunitas.php", {
                            method: "POST",
                            body: formData
                        }
                    );

                    const result = await response.json();

                    if (result.status === "success") {

                        btn.outerHTML = `
                    <a href="chat.php?id=${idKomunitas}&type=group"
                       class="btn btn-primary">
                        <i class="uil uil-comments me-1"></i>
                        Buka Chat
                    </a>
                `;

                    } else {

                        alert(result.message);

                        btn.disabled = false;
                        btn.innerHTML = "Gabung Komunitas";

                    }

                } catch (err) {

                    console.error(err);

                    btn.disabled = false;
                    btn.innerHTML = "Gabung Komunitas";

                }

            });

        });
    </script>
</body>

</html>