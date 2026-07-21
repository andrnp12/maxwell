<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once '../classes/materi.php';

$materi = new Materi();

$dataMateri = $materi->getMateriNonKuis();

?>
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
                            <div class="mb-4">
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="kuis.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Tambah Kuis Skill
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mt-2">
                                        <h5 class="font-weight-bold mb-3">
                                            Detail Informasi
                                        </h5>
                                        <form id="formKuis">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Materi
                                                </label>
                                                <select name="id_materi" class="form-select" aria-label="Default select example" required>
                                                    <?php foreach ($dataMateri as $materi) : ?>
                                                        <option value="<?= $materi['id'] ?>">
                                                            <?= htmlspecialchars($materi['judul']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" class="form-label" for="commentmessage-input">
                                                    Judul Kuis
                                                </label>
                                                <input class="form-control" name="judul" id="commentmessage-input" placeholder="Masukkan judul kuis" type="text" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="commentmessage-input">
                                                    Passing Grade
                                                </label>
                                                <input class="form-control" name="passing_grade" id="commentmessage-input" placeholder="Masukkan judul kuis" type="number" min="0" max="100" required>
                                            </div>
                                            <div class="text-end">
                                                <button class="btn btn-primary btn-sm btn-rounded" id="btnSubmit" type="submit">
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
    <script>
        const formKuis = document.getElementById('formKuis');
        const btnSubmit = document.getElementById('btnSubmit');

        formKuis.addEventListener('submit', async function(e) {
            e.preventDefault();

            btnSubmit.disabled = true;
            btnSubmit.innerText = 'Memproses...';

            const formData = new FormData(formKuis);

            try {
                const response = await fetch('/../actions/proses_kuis.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    alert('Sukses: ' + result.message);
                    formKuis.reset();
                    // window.location.href = 'kuis.php';
                } else {
                    alert('Error: ' + result.message);
                }

            } catch (error) {
                alert('Terjadi kesalahan koneksi jaringan.');
                btnSubmit.disabled = false;
                btnSubmit.innerText = 'simpan';
            }
        });
    </script>
</body>

</html>