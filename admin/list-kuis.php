<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}



require_once '../classes/pertanyaan_kuis.php';

$pertanyaanKuis = new PertanyaanKuis();

$kuisId = $_GET['id'] ?? 0;

$dataPertanyaanKuis = $pertanyaanKuis->getAllPertanyaanKuis((int)$kuisId);

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
                                    Tambah Pertanyaan
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
                                                    Pertanyaan
                                                </th>
                                                <th>
                                                    Opsi A
                                                </th>
                                                <th>
                                                    Opsi B
                                                </th>
                                                <th>
                                                    Opsi C
                                                </th>
                                                <th>
                                                    Opsi D
                                                </th>
                                                <th>
                                                    Jawaban
                                                </th>
                                                <th>
                                                    aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($dataPertanyaanKuis as $row) : ?>
                                                <tr>
                                                    <td>

                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($row['pertanyaan']) ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($row['opsi_a']) ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($row['opsi_b']) ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($row['opsi_c']) ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($row['opsi_d']) ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($row['jawaban']) ?>
                                                    </td>
                                                    <td>
                                                        <a href="#" data-id="<?= $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#modalEditKuis" class="btn btn-sm btn-warning">Edit</a>
                                                        <button type="button" data-id="<?= $row['id'] ?>" class=" btn btn-delete btn-sm btn-danger">Hapus</button>
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


            <!-- ========================================================= -->
            <!-- Bagian Pop-up Edit kuis (Modal) -->
            <!-- ========================================================= -->
            <div class="modal fade" id="modalEditKuis" tabindex="-1" aria-labelledby="modalEditKuisLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Header Modal -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditKuisLabel">Form Edit Kuis</h5>
                            <!-- Tombol silang untuk menutup modal -->
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                        <form id="formEditPertanyaanKuis" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit_id">
                                <div class="mb-3">
                                    <label for="pertanyaan" class="form-label">Pertanyaan</label>
                                    <textarea class="form-control" name="pertanyaan" id="edit_pertanyaan" rows="3" required></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="opsi_a" class="form-label">Opsi A</label>
                                        <input type="text" class="form-control" name="opsi_a" id="edit_opsi_a" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="opsi_b" class="form-label">Opsi B</label>
                                        <input type="text" class="form-control" name="opsi_b" id="edit_opsi_b" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="opsi_c" class="form-label">Opsi C</label>
                                        <input type="text" class="form-control" name="opsi_c" id="edit_opsi_c" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="opsi_d" class="form-label">Opsi D</label>
                                        <input type="text" class="form-control" name="opsi_d" id="edit_opsi_d" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="jawaban" class="form-label">Kunci Jawaban</label>
                                    <select class="form-select" name="jawaban" id="edit_jawaban" required>
                                        <option value="" selected disabled>-- Pilih Kunci Jawaban --</option>
                                        <!-- Value disesuaikan dengan isi database Anda (bisa A, B, C, D atau teks dari opsi) -->
                                        <option value="A">Opsi A</option>
                                        <option value="B">Opsi B</option>
                                        <option value="C">Opsi C</option>
                                        <option value="D">Opsi D</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" id="btnSubmit" class="btn btn-primary">Simpan Pertanyaan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>


            <!-- ========================================================= -->
            <!-- Bagian Pop-up tambah kuis (Modal) -->
            <!-- ========================================================= -->
            <div class="modal fade" id="modalTambahKuis" z-index="999" aria-labelledby="modalTambahKuisLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Header Modal -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahKuisLabel">Form Tambah Kuis</h5>
                            <!-- Tombol silang untuk menutup modal -->
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                        <form id="formPertanyaan">
                            <div class="modal-body">
                                <input type="hidden" name="kuis_id" value="<?= htmlspecialchars($_GET['id'] ?? 0) ?>">
                                <div class="mb-3">
                                    <label for="pertanyaan" class="form-label">Pertanyaan</label>
                                    <textarea class="form-control" name="pertanyaan" id="pertanyaan" rows="3" required></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="opsi_a" class="form-label">Opsi A</label>
                                        <input type="text" class="form-control" name="opsi_a" id="opsi_a" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="opsi_b" class="form-label">Opsi B</label>
                                        <input type="text" class="form-control" name="opsi_b" id="opsi_b" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="opsi_c" class="form-label">Opsi C</label>
                                        <input type="text" class="form-control" name="opsi_c" id="opsi_c" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="opsi_d" class="form-label">Opsi D</label>
                                        <input type="text" class="form-control" name="opsi_d" id="opsi_d" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="jawaban" class="form-label">Kunci Jawaban</label>
                                    <select class="form-select" name="jawaban" id="jawaban" required>
                                        <option value="" selected disabled>-- Pilih Kunci Jawaban --</option>
                                        <!-- Value disesuaikan dengan isi database Anda (bisa A, B, C, D atau teks dari opsi) -->
                                        <option value="A">Opsi A</option>
                                        <option value="B">Opsi B</option>
                                        <option value="C">Opsi C</option>
                                        <option value="D">Opsi D</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" id="btnSubmit" class="btn btn-primary">Simpan Pertanyaan</button>
                            </div>
                        </form>

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

    <!-- tambah kuis -->
    <script>
        const formPertanyaanKuis = document.getElementById('formPertanyaan');
        const btnSubmit = document.getElementById('btnSubmit');

        const modalElement = document.getElementById('modalTambahKuis');


        formPertanyaanKuis.addEventListener('submit', async function(e) {
            e.preventDefault();

            btnSubmit.disabled = true;
            btnSubmit.innerText = 'Memproses...';

            const formData = new FormData(formPertanyaanKuis);

            try {
                const response = await fetch('/../actions/proses_kuis_pertanyaan.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    alert('Sukses: ' + result.message);
                    formPertanyaanKuis.reset();
                    // window.location.href = 'kuis.php';
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);

                    if (modalInstance) {
                        modalInstance.hide();
                    }
                } else {
                    alert('Error: ' + result.message);
                }

            } catch (error) {
                alert('Terjadi kesalahan koneksi jaringan.');
                btnSubmit.disabled = false;
                btnSubmit.innerText = 'simpan';
            }
        });


        // mengambil data yang akan di edit
        document.addEventListener('DOMContentLoaded', function() {
            const modalEditKuis = document.getElementById('modalEditKuis');

            modalEditKuis.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');

                fetch(`/../actions/proses_edit_pertanyaan_kuis.php?id=${id}`)
                    .then(response => response.json())
                    .then(res => {
                        if (res.status === "success") {
                            document.getElementById('edit_id').value = res.data.id;
                            document.getElementById('edit_pertanyaan').value = res.data.pertanyaan;
                            document.getElementById('edit_opsi_a').value = res.data.opsi_a;
                            document.getElementById('edit_opsi_b').value = res.data.opsi_b;
                            document.getElementById('edit_opsi_c').value = res.data.opsi_c;
                            document.getElementById('edit_opsi_d').value = res.data.opsi_d;
                            document.getElementById('edit_jawaban').value = res.data.jawaban;
                        } else {
                            alert('Error: ' + res.message);
                        }
                    })
                    .catch(error => {
                        alert('Error: ' + error);
                    })
            })
        })

        // menyimpan data yang sudah di edit
        document.addEventListener('DOMContentLoaded', function() {
            const formEdit = document.getElementById('formEditPertanyaanKuis');

            formEdit.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(formEdit);

                try {
                    const response = await fetch('/../actions/proses_edit_pertanyaan_kuis.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.status === 'success') {
                        alert('Sukses: ' + result.message);
                        formPertanyaanKuis.reset();
                        // window.location.href = 'kuis.php';
                    } else {
                        alert('Error: ' + result.message);
                    }

                } catch (error) {
                    alert('Terjadi kesalahan koneksi jaringan.');
                }
            })
        })

        // menghapus data
        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-delete')) {
                    const button = event.target;
                    const id = button.getAttribute('data-id');

                    const konfirmasi = confirm("Apakah Anda yakin ingin menghapus pertanyaan ini?");

                    if (konfirmasi) {
                        // PERHATIKAN: Method diubah jadi 'DELETE' dan ID ditaruh di URL
                        fetch(`/../actions/proses_edit_pertanyaan_kuis.php?id=${id}`, {
                                method: 'DELETE'
                            })
                            .then(response => response.json())
                            .then(res => {
                                if (res.status === 'success') {
                                    alert('Data berhasil dihapus!');
                                    location.reload();
                                } else {
                                    alert('Gagal menghapus: ' + res.message);
                                }
                            })
                            .catch(error => {
                                alert('Terjadi kesalahan: ' + error);
                            });
                    }
                }
            });
        });
    </script>

    <!-- end javascript -->

</body>