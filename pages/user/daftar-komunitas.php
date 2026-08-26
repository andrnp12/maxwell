<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../../src/classes/auth.php';
require_once '../../src/classes/komunitas.php';

$dataKomunitas = new Komunitas();
$auth = new auth();
$auth->authOrNot();

$komunitas = $dataKomunitas->getAllKomunitas((int) $_SESSION['id']);
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
                            <div class="mb-4">
                                <!-- <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="komunitas.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a> -->
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Temukan Komunitas Kamu
                                    </h4>
                                    <p class="text-muted mb-0">
                                        Temukan komunitas yang sesuai dengan minat Anda.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- end row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between pb-0">
                                <!-- <h5 class="mb-sm-0">
                                    Daftar Komunitas
                                </h5> -->
                                <!-- <div class="page-title-right">
                                    <div class="btn-group">
                                        <button aria-expanded="false" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" type="button">
                                            Filter Kategori
                                            <i class="mdi mdi-chevron-down">
                                            </i>
                                        </button>
                                        <div class="dropdown-menu dropdownmenu-primary">
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
                                </div> -->
                            </div>
                        </div>
                        <?php foreach ($komunitas as $index => $kom) : ?>
                            <div class="col-12 col-xl-6 col-md-6">
                                <?php if ($kom['is_member']) : ?>
                                    <!-- Jika sudah anggota: background success gradient, text white -->
                                    <a class="text-decoration-none" href="chat.php?id=<?= $kom['id'] ?>&type=group">
                                        <div class="card mb-3 text-white border-white shadow-lg" style="border-radius: 1.25rem; background-image: radial-gradient( circle farthest-corner at 10% 20%,  rgba(0,0,70,1) 0.3%, rgba(28,181,224,1) 90.2% );">
                                            <div class="row g-0 align-items-center">
                                                <div class="col-3 text-center">
                                                    <div style="width: 56px; height: 56px; border-radius: 50%; background-color: rgba(233, 236, 239, 0.5); display: inline-flex; align-items: center; justify-content: center;">
                                                        <img src="/assets/icon/focus-group.webp" alt="icon" style="width: 32px; height: 32px;" />
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="card-body" style="padding-left: 0px;">
                                                        <h5 class="card-title mb-0 font-weight-bold">
                                                            <?= $kom['nama_komunitas'] ?>
                                                        </h5>
                                                        <p class="card-text mb-0">
                                                            <small class="text-truncate d-block" style="max-width: 100%; color: rgba(255,255,255,0.8);">
                                                                <?= $kom['deskripsi'] ?>
                                                            </small>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <button
                                                        class="btn btn-light btn-join px-2 py-1"
                                                        style="border-radius: 1.25rem;"
                                                        data-id="<?= $kom['id'] ?>">
                                                        Buka Chat
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php else : ?>
                                    <!-- Jika belum anggota: background putih, border biasa -->
                                    <div class="card mb-3 border shadow-sm" style="border-radius: 1.25rem;">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-3 text-center">
                                                <!-- Icon circle untuk belum anggota: background abu-abu -->
                                                <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items: center; justify-content: center;">
                                                    <img src="/assets/icon/focus-group.webp" alt="icon" style="width: 32px; height: 32px;" />
                                                </div>
                                            </div>
                                            <div class="flex col-5">
                                                <div class="card-body" style="padding-left: 0px;">
                                                    <h5 class="card-title mb-0 font-weight-bold">
                                                        <?= $kom['nama_komunitas'] ?>
                                                    </h5>
                                                    <p class="card-text mb-0">
                                                        <small class="text-muted text-truncate d-block" style="max-width: 100%;">
                                                            <?= $kom['deskripsi'] ?>
                                                        </small>
                                                    </p>
                                                </div>
                                            </div>
                                            <!-- Tombol Gabung -->
                                            <div class="col-4">
                                                <button
                                                    class="btn btn-info btn-join px-2 py-1"
                                                    style="border-radius: 1.25rem; margin-left: 15px;"
                                                    data-id="<?= $kom['id'] ?>">
                                                    Gabung
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
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
                btn.innerHTML = "Gabung";

                const formData = new FormData();

                formData.append("action", "join_group");
                formData.append("id_komunitas", idKomunitas);

                const response = await fetch(
                    "/src/actions/proses_komunitas.php", {
                        method: "POST",
                        body: formData
                    }
                );

                const result = await response.json();

                if (result.status != "success") {
                    alert(result.message);
                } else {
                    location.href = `chat.php?id=${idKomunitas}&type=group`;
                }
            });

        });
    </script>
</body>



</html>