<?php
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
//     header('Location: login.php');
//     exit;
// }
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
                                                        <button type="button" data-id="<?= $materi['id'] ?>" class="btn btn-delete btn-sm btn-danger">Hapus</button>
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
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const materiId = this.getAttribute('data-id');
                const barisMateri = document.getElementById('baris-' + materiId);

                if (confirm('Yakin ingin menghapus materi ini?')) {
                    // Panggil URL route yang mengarah ke fungsi deleteMateri Anda
                    fetch(`/../actions/proses_delete_materi.php?id=${materiId}`, {
                            method: 'POST', // Gunakan DELETE jika framework Anda mewajibkannya
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                // Hapus baris materi dari tabel
                                barisMateri.remove();
                            } else {
                                alert('Gagal menghapus materi: ' + data.message);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        });
    </script>

</body>