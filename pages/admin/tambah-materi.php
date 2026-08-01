<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<?php include '../include/header.php'; ?>


<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php include("../include/topbar.php"); ?>
        <!-- ========== Left Sidebar Start ========== -->
        <?php include '../include/sidebar-admin.php'; ?>
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
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="materi.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Tambah Materi
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
                                        <form id="formMateri">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="commentname-input">
                                                            Nama Materi
                                                        </label>
                                                        <input class="form-control" name='judul' required id="commentname-input" placeholder="Masukkan nama" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Link URL Video
                                                        </label>
                                                        <input class="form-control" name='video_url' required placeholder="Masukkan link video" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="commentprofile-input">
                                                    Upload PDF Materi
                                                </label>
                                                <input class="form-control" name='file_materi' required id="commentprofile-input" placeholder="Ganti Foto Profil" type="file" accept=".pdf">
                                                <span class="form-text text-muted">
                                                    Hanya file PDF yang diperbolehkan.
                                                </span>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="commentprofile-input">
                                                    No Urut Materi
                                                </label>
                                                <input class="form-control" name='no_urut' id="commentprofile-input" placeholder="Masukkan nomor urut materi" type="number" min="1">
                                                <span class="form-text text-muted">
                                                    Kosongkan jika ingin menambahkan di akhir.
                                                </span>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" required for="commentmessage-input">
                                                    Deskripsi Materi
                                                </label>
                                                <textarea class="form-control" name='deskripsi' id="commentmessage-input" placeholder="Masukkan deskripsi..." rows="5"></textarea>
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
        const formMateri = document.getElementById('formMateri');
        const btnSubmit = document.getElementById('btnSubmit');

        formMateri.addEventListener('submit', async function(e) {
            e.preventDefault();

            btnSubmit.disabled = true;
            btnSubmit.innerText = 'Memproses...';

            const formData = new FormData(formMateri);

            try {
                const response = await fetch('/../actions/proses_materi.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    alert('Sukses: ' + result.message);
                    formMateri.reset();
                    // window.location.href = 'materi.php';
                } else {
                    alert('Error: ' + result.message);
                }

            } catch (error) {
                alert('Terjadi kesalahan koneksi jaringan.');
                console.error(error);
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.innerText = 'Simpan Perubahan';
            }

        })
    </script>
</body>

</html>