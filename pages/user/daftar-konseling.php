<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../../src/classes/auth.php';
require_once '../../src/classes/konselor.php';

$data = new Konsultan();
$auth = new auth();
$auth->authOrNot();
$konsultan = $data->getAllKonsultan();
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
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="komunitas.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Daftar Konselor
                                    </h4>
                                    <p class="text-muted mb-0">
                                        Temukan konselor yang sesuai dengan kebutuhan Anda.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- end row -->
                    <div class="row">
                        <?php foreach ($konsultan as $konsul) : ?>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <div class="mx-auto mb-4">
                                            <img alt="" class="avatar-xl rounded-circle img-thumbnail" src="<?= !empty($konsul['foto']) ? '/uploads/profile/' . htmlspecialchars($konsul['foto']) : '/uploads/profile/default.webp' ?>">
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