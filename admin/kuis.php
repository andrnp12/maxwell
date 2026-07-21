<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../classes/kuis.php';

$kuis = new Kuis();
$dataKuis = $kuis->getAllKuis();
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
                                    Lihat Semua Kuis
                                </h4>
                                <p class="text-muted">
                                    Kustomisasi kuis edukasi sesuai kebutuhan Anda!
                                </p>
                            </div>
                            <div>
                                <a class="btn btn-primary btn-rounded waves-effect mb-2" href="#" data-bs-toggle="modal" data-bs-target="#modalTambahKuis">
                                    <span>
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    Tambah Kuis
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
                                        Daftar Kuis
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>
                                                    No.
                                                </th>
                                                <th>
                                                    Judul Kuis
                                                </th>
                                                <th>
                                                    Materi Kuis
                                                </th>
                                                <th>
                                                    Passing grade
                                                </th>
                                                <th>
                                                    Aksi
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($dataKuis as $kuis) : ?>
                                                <tr id="baris-<?= $kuis['id_kuis'] ?>">
                                                    <td>

                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($kuis['judul_materi']) ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($kuis['judul_kuis']) ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($kuis['passing_grade']) ?>
                                                    </td>
                                                    <td>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditKuis" class="btn btn-sm btn-warning">Edit</a>
                                                        <button type="button" data-id="<?= $kuis['id_kuis'] ?>" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus">Hapus</button>
                                                        <a href="list-kuis.php" class="btn btn-sm btn-info">Lihat Pertanyaan</a>
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
            <!-- ========================================================= -->
            <!-- Bagian Pop-up tambah kuis (Modal) -->
            <!-- ========================================================= -->
            <div class="modal fade" id="modalTambahKuis" tabindex="-1" aria-labelledby="modalTambahKuisLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Header Modal -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahKuisLabel">Form Tambah Kuis</h5>
                            <!-- Tombol silang untuk menutup modal -->
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                        <form action="" method="POST">
                            <div class="modal-body">
                                <!-- Contoh Inputan -->
                                <div class="mb-3">
                                    <label for="pertanyaan" class="form-label">Pertanyaan</label>
                                    <input type="text" class="form-control" id="pertanyaan" name="pertanyaan" required>
                                </div>

                                <div class="mb-3">
                                    <label for="opsiA" class="form-label">Opsi A</label>
                                    <input type="text" class="form-control" id="opsiA" name="opsiA" required>
                                </div>
                                <div class="mb-3">
                                    <label for="opsiB" class="form-label">Opsi B</label>
                                    <input type="text" class="form-control" id="opsiB" name="opsiB" required>
                                </div>
                                <div class="mb-3">
                                    <label for="opsiC" class="form-label">Opsi C</label>
                                    <input type="text" class="form-control" id="opsiC" name="opsiC" required>
                                </div>
                                <div class="mb-3">
                                    <label for="opsiD" class="form-label">Opsi D</label>
                                    <input type="text" class="form-control" id="opsiD" name="opsiD" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        Jawaban
                                    </label>
                                    <select class="form-select" aria-label="Default select example" required>
                                        <option>
                                            A
                                        </option>
                                        <option>
                                            B
                                        </option>
                                        <option>
                                            C
                                        </option>
                                        <option>
                                            D
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Footer Modal (Tombol Aksi) -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" name="simpan_kuis">Simpan Kuis</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- Bagian Pop-up edit kuis (Modal) -->
            <!-- ========================================================= -->
            <div class="modal fade" id="modalEditKuis" z-index="999" aria-labelledby="modalEditKuisLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Header Modal -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditKuisLabel">Form Edit Kuis</h5>
                            <!-- Tombol silang untuk menutup modal -->
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                        <form action="" method="POST">
                            <div class="modal-body">
                                <!-- Contoh Inputan -->
                                <div class="mb-3">
                                    <label for="pertanyaan" class="form-label">Pertanyaan</label>
                                    <input type="text" class="form-control" id="pertanyaan" name="pertanyaan" required>
                                </div>

                                <div class="mb-3">
                                    <label for="opsiA" class="form-label">Opsi A</label>
                                    <input type="text" class="form-control" id="opsiA" name="opsiA" required>
                                </div>
                                <div class="mb-3">
                                    <label for="opsiB" class="form-label">Opsi B</label>
                                    <input type="text" class="form-control" id="opsiB" name="opsiB" required>
                                </div>
                                <div class="mb-3">
                                    <label for="opsiC" class="form-label">Opsi C</label>
                                    <input type="text" class="form-control" id="opsiC" name="opsiC" required>
                                </div>
                                <div class="mb-3">
                                    <label for="opsiD" class="form-label">Opsi D</label>
                                    <input type="text" class="form-control" id="opsiD" name="opsiD" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        Jawaban
                                    </label>
                                    <select class="form-select" aria-label="Default select example" required>
                                        <option>
                                            A
                                        </option>
                                        <option>
                                            B
                                        </option>
                                        <option>
                                            C
                                        </option>
                                        <option>
                                            D
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Footer Modal (Tombol Aksi) -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" name="simpan_kuis">Simpan Kuis</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <!-- End Page-content -->
            <?php include("component/toast.php"); ?>
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
        const elemenToastNotif = elemenModalNotif ? elemenModalNotif.querySelector('.toast') : null;

        function tampilkanNotif(judul, pesan, status = 'success') {
            if (!elemenToastNotif) return;

            const toastEl = elemenToastNotif;
            const header = toastEl.querySelector('.toast-header');
            const body = toastEl.querySelector('.toast-body');

            // remove previous status classes
            ['bg-success', 'bg-danger'].forEach(c => {
                toastEl.classList.remove(c);
                if (header) header.classList.remove(c);
                if (body) body.classList.remove(c);
            });

            // apply new status
            if (status === 'success') {
                toastEl.classList.add('bg-success');
                if (header) header.classList.add('bg-success');
                if (body) body.classList.add('bg-success');
            } else {
                toastEl.classList.add('bg-danger');
                if (header) header.classList.add('bg-danger');
                if (body) body.classList.add('bg-danger');
            }

            // ensure white text
            if (header) header.classList.add('text-white');
            if (body) body.classList.add('text-white');

            document.getElementById('judulNotifikasi').textContent = judul;
            document.getElementById('pesanNotifikasi').textContent = pesan;
            document.getElementById('toastBody').textContent = pesan;

            if (!modalNotifInstance) {
                modalNotifInstance = bootstrap.Toast.getOrCreateInstance(toastEl, {
                    autohide: true,
                    delay: 3000
                });
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
                            tampilkanNotif('Sukses', data.message || 'Materi berhasil dihapus.', 'success');

                        } else {
                            tampilkanNotif('Error', data.message || 'Gagal menghapus materi.', 'error');
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
                        tampilkanNotif('Error', 'Terjadi kesalahan saat menghapus materi.', 'error');
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