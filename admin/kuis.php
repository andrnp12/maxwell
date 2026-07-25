<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../classes/kuis.php';
require_once '../classes/materi.php';

$kuis = new Kuis();
$materi = new Materi();

$dataKuis = $kuis->getAllKuis();
$dataMateri = $materi->getMateriNonKuis();
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
                                                        <a href="#"
                                                            data-id="<?= $kuis['id_kuis'] ?>"
                                                            data-id_materi="<?= $kuis['material_id'] ?>"
                                                            data-judul_materi="<?= htmlspecialchars($kuis['judul_materi']) ?>"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditKuis"
                                                            class="btn btn-sm btn-warning">Edit</a>
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
                        <form id="formEditKuis" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id_kuis" id="edit_id">
                                <div class="mb-3">
                                    <label for="judul" class="form-label">Materi</label>
                                    <select id="id_materi" name="id_materi" class="form-select" required>
                                        <option value="" disabled>Pilih Materi</option>
                                        <?php foreach ($dataMateri as $materi) : ?>
                                            <option value="<?= $materi['id'] ?>">
                                                <?= htmlspecialchars($materi['judul']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="judul_kuis" class="form-label">judul Kuis</label>
                                    <input class="form-control" name="judul_kuis" id="judul_kuis" type='text' rows="3" required></input>
                                </div>
                                <div class="mb-3">
                                    <label for="passing_grade" class="form-label">Passing Grade</label>
                                    <input class="form-control" name="passing_grade" id="passing_grade" rows="3" type="number" min="0" max="100" required></input>
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

    <script>
        //mengambil data yang akan di edit
        document.addEventListener('DOMContentLoaded', function() {
            const modalEditKuis = document.getElementById('modalEditKuis');

            modalEditKuis.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Ambil data dari tombol
                const idKuis = button.getAttribute('data-id');
                const idMateri = button.getAttribute('data-id_materi');
                const judulMateri = button.getAttribute('data-judul_materi');

                const selectMateri = document.getElementById('id_materi');

                // 1. Cek apakah ID materi ini sudah ada di dalam opsi dropdown
                let optionExists = Array.from(selectMateri.options).some(opt => opt.value === idMateri);

                // 2. Jika belum ada, kita tambahkan opsinya secara dinamis via JS
                if (!optionExists && idMateri) {
                    const newOption = new Option(judulMateri, idMateri);
                    selectMateri.add(newOption);
                }

                // 3. Ambil sisa data kuis via fetch seperti biasa
                fetch(`/../actions/proses_kuis.php?id=${idKuis}`)
                    .then(response => response.json())
                    .then(res => {
                        if (res.status === 'success') {
                            document.getElementById('edit_id').value = res.data.id;
                            document.getElementById('judul_kuis').value = res.data.judul;
                            document.getElementById('passing_grade').value = res.data.passing_grade;

                            // Sekarang set valuenya pasti berhasil karena opsinya sudah ada
                            selectMateri.value = res.data.material_id;
                        } else {
                            alert('Error: ' + res.message);
                        }
                    }).catch(error => {
                        alert('Error: ' + error);
                    });
            });
        });

        // menyimpan data edit
        document.addEventListener('DOMContentLoaded', function() {
            const formEdit = document.getElementById('formEditKuis');

            formEdit.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(formEdit);

                try {
                    const response = await fetch('/../actions/proses_kuis.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.status === 'success') {
                        alert('Sukses: ' + result.message);
                        formEdit.reset();
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
                        fetch(`/../actions/proses_kuis.php?id=${id}`, {
                                method: 'DELETE'
                            })
                            .then(response => response.json())
                            .then(res => {
                                if (res.status === 'success') {
                                    alert('Data berhasil dihapus!');
                                    // location.reload();
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