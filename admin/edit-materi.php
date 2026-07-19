<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once '../classes/materi.php';

$id = $_GET['id'] ?? 0;

$materi = new Materi();
$dataMateri = $materi->getMateriById((int)$id);

if (!$dataMateri) {
    echo "<h2>Maaf, materi tidak ditemukan.</h2> <a href='materi.php'>Kembali ke daftar materi</a>";
    exit;
}
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
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="materi.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Edit Materi
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
                                        <form id="formEditMateri">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($dataMateri['id']) ?>">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="commentname-input">
                                                            Nama Materi
                                                        </label>
                                                        <input class="form-control" name='judul' required id="commentname-input" placeholder="Masukkan nama" type="text" value="<?= htmlspecialchars($dataMateri['judul']) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Link URL Video
                                                        </label>
                                                        <input class="form-control" name='video_url' required placeholder="Masukkan link video" type="text" value="<?= htmlspecialchars($dataMateri['video_url']) ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="commentprofile-input">
                                                    Upload PDF Materi Baru (Opsional, jika ingin mengganti file lama)
                                                </label>
                                                <input class="form-control" name='file_materi' id="commentprofile-input" placeholder="Ganti Foto Profil" type="file" accept=".pdf">
                                                <span class="form-text text-muted">
                                                    Hanya file PDF yang diperbolehkan.
                                                </span>
                                                <?php if (!empty($dataMateri['file'])): ?>
                                                    <br>
                                                    <span class="info" style="color: blue;">File saat ini: <?= htmlspecialchars($dataMateri['file']) ?></span><br>
                                                    <span class="info">Jika tidak ingin mengubah file, biarkan form upload ini kosong.</span>
                                                <?php else: ?>
                                                    <br><span class="info">Belum ada file untuk materi ini.</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="commentprofile-input">
                                                    No Urut Materi
                                                </label>
                                                <input class="form-control" name='no_urut' id="commentprofile-input" placeholder="Masukkan nomor urut materi" type="number" min="1" value="<?= htmlspecialchars($dataMateri['no_urut']) ?>">
                                                <span class="form-text text-muted">
                                                    Kosongkan jika ingin menambahkan di akhir.
                                                </span>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" required for="commentmessage-input">
                                                    Deskripsi Materi
                                                </label>
                                                <textarea class="form-control" name='deskripsi' id="commentmessage-input" placeholder="Masukkan deskripsi..." rows="5"><?= htmlspecialchars($dataMateri['deskripsi']) ?></textarea>
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
        const formEdit = document.getElementById('formEditMateri');
        const btnSubmit = document.getElementById('btnSubmit');

        formEdit.addEventListener('submit', async function(e) {
            e.preventDefault();
            btnSubmit.disabled = true;
            btnSubmit.innerText = 'Menyimpan...';

            const formData = new FormData(formEdit);

            try {
                const response = await fetch('/../actions/proses_edit_materi.php', {
                    method: 'POST',
                    body: formData
                });

                // Cek dulu apakah response-nya OK (status 200) dan bukan error server
                if (!response.ok) {
                    const textError = await response.text();
                    console.error("Error dari Server:", textError);
                    alert("Terjadi error di server. Cek Inspect Element -> Console.");
                    return; // hentikan proses
                }

                const result = await response.json(); // Baru parse ke JSON

                if (result.status === 'success') {
                    alert('Sukses: ' + result.message);
                    window.location.href = 'materi.php';
                } else {
                    alert('Gagal: ' + result.message);
                }
            } catch (error) {
                // Tampilkan pesan error JS di console
                console.error("Error Javascript/Jaringan:", error);
                alert('Gagal memproses data. Silakan cek Console (F12).');
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.innerText = 'Update Materi';
            }
        });
    </script>
</body>

</html>