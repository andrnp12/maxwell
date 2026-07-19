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

$dataMateri = $materi->getAllMateri();

// if (!$dataMateri) {
//     echo "<h2>Maaf, materi belum tersedia.</h2>";
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
                                    Lihat Semua Materi
                                </h4>
                                <p class="text-muted">
                                    Kustomisasi materi edukasi sesuai kebutuhan Anda!
                                </p>
                            </div>
                            <div>
                                <a class="btn btn-primary btn-rounded waves-effect mb-2" href="tambah-materi.php">
                                    <span>
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    Tambah Materi
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
                                        Daftar Materi
                                    </h4>
                                </div>

                                <?php
                                foreach ($dataMateri as $materi) ?>
                                <div class="card-body">
                                    <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>Nama Materi</th>
                                                <th>Deskripsi Materi</th>
                                                <th>File Materi</th>
                                                <th>Link Video</th>
                                                <th>No Urut</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($dataMateri as $materi) : ?>
                                                <tr id="baris-<?= $materi['id'] ?>">
                                                    <td><?= htmlspecialchars($materi['judul']) ?></td>
                                                    <td><?= htmlspecialchars($materi['deskripsi']) ?></td>
                                                    <td><?= htmlspecialchars($materi['file']) ?></td>
                                                    <td><a href="<?= htmlspecialchars($materi['video_url']) ?>" target="_blank"><?= htmlspecialchars($materi['video_url']) ?></a></td>
                                                    <td><?= htmlspecialchars($materi['no_urut']) ?></td>
                                                    <td>
                                                        <a href="edit-materi.php?id=<?= $materi['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                        <button type="button" data-id="<?= $materi['id'] ?>" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus">Hapus</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
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

            <!-- Modal Konfirmasi Hapus -->
            <div class="modal fade" id="modalKonfirmasiHapus" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus materi ini? Data yang dihapus tidak dapat dikembalikan.
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <!-- Tombol aksi hapus di dalam modal -->
                            <button type="button" class="btn btn-danger" id="btnEksekusiHapus">Ya, Hapus</button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal sukses dan gagal -->
            <div class="modal fade" id="modalNotifikasi" tabindex="-1" aria-labelledby="modalNotifikasiLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm"> <!-- modal-sm agar ukurannya kecil rapi -->
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="judulNotifikasi">Notifikasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body text-center" id="pesanNotifikasi">
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                        </div>

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

    <script>
        let materiIdToDelete = null;
        let barisMateriToDelete = null;
        let modalHapusInstance = null;
        let modalNotifInstance = null;

        const elemenModalHapus = document.getElementById('modalKonfirmasiHapus');
        const elemenModalNotif = document.getElementById('modalNotifikasi');

        function tampilkanNotif(judul, pesan) {
            document.getElementById('judulNotifikasi').innerHTML = judul;
            document.getElementById('pesanNotifikasi').innerHTML = pesan;

            if (!modalNotifInstance) {
                modalNotifInstance = bootstrap.Modal.getOrCreateInstance(elemenModalNotif);
            }
            modalNotifInstance.show();
        }


        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                materiIdToDelete = this.getAttribute('data-id');

                // PERBAIKAN 1: Ganti materiId menjadi materiIdToDelete
                barisMateriToDelete = document.getElementById('baris-' + materiIdToDelete);

                modalHapusInstance = bootstrap.Modal.getOrCreateInstance(elemenModalHapus);
                modalHapusInstance.show();
            });
        });

        document.getElementById('btnEksekusiHapus').addEventListener('click', function() {
            if (!materiIdToDelete) return;

            const btnEksekusi = this;
            const teksAsli = btnEksekusi.innerHTML;
            btnEksekusi.innerHTML = 'Menghapus...';
            btnEksekusi.disabled = true;

            // fetch(`/../actions/proses_delete_materi.php?id=${materiIdToDelete}`, {
            //         method: 'POST',
            //     })
            //     .then(response => response.json())
            //     .then(data => {
            setTimeout(() => {
                    const data = {
                        status: 'success',
                        message: 'Data berhasil dihapus'
                    }

                    // Tutup modal konfirmasi
                    const modalHapusInstance = bootstrap.Modal.getOrCreateInstance(elemenModalHapus);
                    modalHapusInstance.hide();

                    bersihkanBackdrop();

                    setTimeout(() => {
                        if (data.status === 'success') {
                            if (barisMateriToDelete) {
                                barisMateriToDelete.remove();
                            }
                            tampilkanNotif('Sukses', 'Materi berhasil dihapus.');

                        } else {
                            tampilkanNotif('Error', 'Gagal menghapus materi.');
                        }
                    }, 500); // 500 milidetik

                })
                .catch(error => {
                    const modalHapusInstance = bootstrap.Modal.getOrCreateInstance(elemenModalHapus);
                    modalHapusInstance.hide();
                    bersihkanBackdrop();

                    console.error('Error:', error);

                    // Tambahkan jeda juga untuk notifikasi error
                    setTimeout(() => {
                        tampilkanNotif('Error', 'Terjadi kesalahan saat menghapus materi.');
                    }, 500);

                }).finally(() => {
                    btnEksekusi.innerHTML = teksAsli;
                    btnEksekusi.disabled = false;
                });
        })

        function bersihkanBackdrop() {
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';

            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
        }
    </script>

</body>