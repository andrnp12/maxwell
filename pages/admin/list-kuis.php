<?php
require_once '../../src/classes/auth.php';
$auth = new auth();
$auth->authOrNot();

require_once '../../src/classes/kuis.php';
require_once '../../src/classes/pertanyaan_kuis.php';

$kuis = new Kuis();
$pertanyaanKuis = new PertanyaanKuis();

$kuisId = $_GET['id'] ?? 0;
$kuisData = $kuis->getKuisById((int)$kuisId);
$dataPertanyaanKuis = $pertanyaanKuis->getAllPertanyaanKuis((int)$kuisId);

?>

<!--header start-->
<?php include('../include/header.php'); ?>
<!--header end-->

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- ========== Topbar Start ========== -->
        <?php include('../include/topbar.php'); ?>
        <!-- ========== Topbar End ========== -->
        <!-- ========== Left Sidebar Start ========== -->
        <?php include('../include/sidebar-admin.php'); ?>
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
                            <div class="mb-4">
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="kuis.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 fw-bold">
                                        Daftar Pertanyaan
                                    </h4>
                                    <p class="text-muted mb-0">
                                        Lihat daftar pertanyaan dari kuis.
                                    </p>
                                </div>
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
                            <div class="card shadow-sm" style="border-radius: 1.25rem; overflow: hidden;">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        Daftar Kuis
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered dt-responsive w-100" id="datatable">
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
                                            <?php $i = 1;
                                            foreach ($dataPertanyaanKuis as $row) : ?>
                                                <tr id="row-<?= $row['id'] ?>">
                                                    <td>
                                                        <?= $i++ ?>
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
                                                        <button type="button" data-id="<?= $row['id'] ?>" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus1">Hapus</button>
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
                        <form id="formPertanyaan" method="POST">
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
            <?php include("../include/toast.php"); ?>
            <!-- Footer Start -->
            <?php include("../include/footer.php"); ?>
            <!-- end Footer -->
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <!-- Right Sidebar -->
    <?php include("../include/right-sidebar.php"); ?>
    <!-- /Right-bar -->
    <!-- javascript -->
    <?php include("../include/script.php"); ?>

    <!-- tambah kuis -->
    <script>
        const formPertanyaanKuis = document.getElementById('formPertanyaan');
        const btnSubmit = document.getElementById('btnSubmit');

        const modalElement = document.getElementById('modalTambahKuis');
        const modalEditKuis = document.getElementById('modalEditKuis');
        const elemenModalNotif = document.getElementById('modalNotifikasi');
        const elemenToastNotif = elemenModalNotif ? elemenModalNotif.querySelector('.toast') : null;
        const datatableElement = document.getElementById('datatable');

        const dataTable = window.jQuery && window.jQuery.fn && window.jQuery.fn.dataTable && window.jQuery.fn.dataTable.isDataTable('#datatable') ?
            window.jQuery('#datatable').DataTable() :
            null;

        let modalNotifInstance = null;

        // --- Helper ---
        function escapeHtml(text, attr = false) {
            if (text === null || text === undefined) return '';
            let escaped = String(text)
                .replace(/&/g, '&')
                .replace(/</g, '<')
                .replace(/>/g, '>')
                .replace(/"/g, '"')
                .replace(/'/g, '&#039;');
            return attr ? escaped.replace(/`/g, '&#096;') : escaped;
        }

        // --- Notifikasi Toast ---
        function tampilkanNotif(judul, pesan, status = 'success') {
            if (!elemenToastNotif) return;

            const toastEl = elemenToastNotif;
            const header = toastEl.querySelector('.toast-header');
            const body = toastEl.querySelector('.toast-body');

            ['bg-success', 'bg-danger'].forEach(c => {
                toastEl.classList.remove(c);
                if (header) header.classList.remove(c);
                if (body) body.classList.remove(c);
            });

            if (status === 'success') {
                toastEl.classList.add('bg-success');
                if (header) header.classList.add('bg-success');
                if (body) body.classList.add('bg-success');
            } else {
                toastEl.classList.add('bg-danger');
                if (header) header.classList.add('bg-danger');
                if (body) body.classList.add('bg-danger');
            }

            if (header) header.classList.add('text-white');
            if (body) body.classList.add('text-white');

            document.getElementById('judulNotifikasi').textContent = judul;
            document.getElementById('pesanNotifikasi').textContent = pesan;

            if (!modalNotifInstance) {
                modalNotifInstance = bootstrap.Toast.getOrCreateInstance(toastEl, {
                    autohide: true,
                    delay: 3000
                });
            }

            modalNotifInstance.show();
        }

        // --- Render Row ke Tabel ---
        function buildActions(id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban) {
            return `
                <a href="#" data-id="${id}" data-bs-toggle="modal" data-bs-target="#modalEditKuis" class="btn btn-sm btn-warning">Edit</a>
                <button type="button" data-id="${id}" class="btn btn-delete btn-sm btn-danger">Hapus</button>
            `;
        }

        function getRowNumber() {
            const tbody = document.querySelector('#datatable tbody');
            if (tbody) {
                return tbody.querySelectorAll('tr').length + 1;
            }
            return 1;
        }

        function tambahRow(data) {
            const tbody = document.querySelector('#datatable tbody');
            if (!tbody) return;

            const tr = document.createElement('tr');
            tr.id = 'row-' + data.id;
            tr.innerHTML = `
                <td>${getRowNumber()}</td>
                <td>${escapeHtml(data.pertanyaan)}</td>
                <td>${escapeHtml(data.opsi_a)}</td>
                <td>${escapeHtml(data.opsi_b)}</td>
                <td>${escapeHtml(data.opsi_c)}</td>
                <td>${escapeHtml(data.opsi_d)}</td>
                <td>${escapeHtml(data.jawaban)}</td>
                <td>${buildActions(data.id, data.pertanyaan, data.opsi_a, data.opsi_b, data.opsi_c, data.opsi_d, data.jawaban)}</td>
            `;
            tbody.appendChild(tr);
        }

        function updateRow(data) {
            const row = document.getElementById('row-' + data.id);
            if (!row) {
                console.warn('Row tidak ditemukan: row-' + data.id);
                return;
            }

            const cells = row.querySelectorAll('td');
            if (cells.length >= 7) {
                cells[1].textContent = data.pertanyaan;
                cells[2].textContent = data.opsi_a;
                cells[3].textContent = data.opsi_b;
                cells[4].textContent = data.opsi_c;
                cells[5].textContent = data.opsi_d;
                cells[6].textContent = data.jawaban;
                cells[7].innerHTML = buildActions(data.id, data.pertanyaan, data.opsi_a, data.opsi_b, data.opsi_c, data.opsi_d, data.jawaban);
            }
        }

        function deleteRow(id) {
            const row = document.getElementById('row-' + id);
            if (row) {
                row.remove();
            }
        }

        // --- Kirim Form (Tambah) ---
        async function kirimForm(formElement, submitButton) {
            if (!formElement || !submitButton) return;

            submitButton.disabled = true;
            submitButton.innerText = 'Memproses...';

            const formData = new FormData(formElement);
            formData.append('action', 'save');

            try {
                const response = await fetch('../../src/actions/proses_pertanyaan_kuis.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    tampilkanNotif('Berhasil', result.message, 'success');
                    formElement.reset();
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                    tambahRow({
                        id: result.id,
                        pertanyaan: result.pertanyaan,
                        opsi_a: result.opsi_a,
                        opsi_b: result.opsi_b,
                        opsi_c: result.opsi_c,
                        opsi_d: result.opsi_d,
                        jawaban: result.jawaban
                    });
                } else {
                    tampilkanNotif('Gagal', result.message, 'error');
                }
            } catch (error) {
                tampilkanNotif('Koneksi Gagal', 'Terjadi kesalahan koneksi jaringan.', 'error');
                console.error(error);
            } finally {
                submitButton.disabled = false;
                submitButton.innerText = 'Simpan Pertanyaan';
            }
        }

        // --- Event Listener: Tambah Data ---
        if (formPertanyaanKuis) {
            formPertanyaanKuis.addEventListener('submit', function(e) {
                e.preventDefault();
                kirimForm(formPertanyaanKuis, btnSubmit);
            });
        }

        // --- Event Listener: Buka Modal Edit ---
        if (modalEditKuis) {
            modalEditKuis.addEventListener('show.bs.modal', async function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                try {
                    const response = await fetch(`../../src/actions/proses_pertanyaan_kuis.php?id=${id}`);
                    const res = await response.json();
                    if (res.status === "success") {
                        document.getElementById('edit_id').value = res.data.id;
                        document.getElementById('edit_pertanyaan').value = res.data.pertanyaan;
                        document.getElementById('edit_opsi_a').value = res.data.opsi_a;
                        document.getElementById('edit_opsi_b').value = res.data.opsi_b;
                        document.getElementById('edit_opsi_c').value = res.data.opsi_c;
                        document.getElementById('edit_opsi_d').value = res.data.opsi_d;
                        document.getElementById('edit_jawaban').value = res.data.jawaban;
                    } else {
                        tampilkanNotif('Gagal', 'Error: ' + res.message, 'error');
                    }
                } catch (error) {
                    tampilkanNotif('Gagal', 'Error: ' + error, 'error');
                }
            });
        }

        // --- Event Listener: Edit Data ---
        document.addEventListener('DOMContentLoaded', function() {
            const formEdit = document.getElementById('formEditPertanyaanKuis');

            formEdit.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(formEdit);

                try {
                    const response = await fetch('../../src/actions/proses_pertanyaan_kuis.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.status === 'success') {
                        tampilkanNotif('Berhasil', result.message, 'success');
                        formEdit.reset();
                        updateRow({
                            id: result.id,
                            pertanyaan: result.pertanyaan,
                            opsi_a: result.opsi_a,
                            opsi_b: result.opsi_b,
                            opsi_c: result.opsi_c,
                            opsi_d: result.opsi_d,
                            jawaban: result.jawaban
                        });
                        const modalInstance = bootstrap.Modal.getInstance(modalEditKuis);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    } else {
                        tampilkanNotif('Gagal', result.message, 'error');
                    }
                } catch (error) {
                    tampilkanNotif('Gagal', 'Terjadi kesalahan koneksi jaringan.', 'error');
                }
            });
        });

        // --- Variabel untuk menyimpan data hapus ---
        let deleteId = null;
        let deleteRowElement = null;

        // --- Event Listener: Delegasi Hapus dari Tabel (Tahap 1: Buka Modal Konfirmasi Pertama) ---
        if (datatableElement) {
            datatableElement.addEventListener('click', function(event) {
                // Tangani tombol Hapus - Tampilkan konfirmasi tahap 1
                const deleteButton = event.target.closest('.btn-delete');
                if (!deleteButton) return;

                const id = deleteButton.getAttribute('data-id');
                if (!id) return;

                deleteId = id;
                deleteRowElement = document.getElementById('row-' + id);

                const modalDelete1 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus1'));
                modalDelete1.show();
            });
        }

        // --- Event Listener: Lanjutkan Hapus (Tahap 1 ke Tahap 2) ---
        const btnLanjutkanHapus = document.getElementById('btnLanjutkanHapus');
        if (btnLanjutkanHapus) {
            btnLanjutkanHapus.addEventListener('click', function() {
                // Hide first confirmation modal
                const modalDelete1 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus1'));
                modalDelete1.hide();

                // Show second confirmation modal
                const modalDelete2 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus2'));
                modalDelete2.show();
            });
        }

        // --- Eksekusi Hapus (Tahap 2) ---
        const btnEksekusiHapus = document.getElementById('btnEksekusiHapus');
        if (btnEksekusiHapus) {
            btnEksekusiHapus.addEventListener('click', async function() {
                if (!deleteId) {
                    tampilkanNotif('Error', 'Tidak dapat mengidentifikasi item yang akan dihapus', 'error');
                    return;
                }

                const button = this;
                const originalText = button.innerHTML;
                button.disabled = true;
                button.innerHTML = 'Menghapus...';

                try {
                    const response = await fetch(`../../src/actions/proses_pertanyaan_kuis.php?id=${deleteId}`, {
                        method: 'DELETE'
                    });
                    const res = await response.json();

                    // Hide both modals
                    const modalDelete1 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus1'));
                    const modalDelete2 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus2'));
                    modalDelete1.hide();
                    modalDelete2.hide();

                    if (res.status === 'success') {
                        if (deleteRowElement) {
                            if (dataTable) {
                                dataTable.row(deleteRowElement).remove().draw(false);
                            } else {
                                deleteRowElement.remove();
                            }
                        }
                        tampilkanNotif('Berhasil', 'Data berhasil dihapus!', 'success');
                    } else {
                        tampilkanNotif('Gagal', 'Gagal menghapus: ' + res.message, 'error');
                    }
                } catch (error) {
                    tampilkanNotif('Gagal', 'Terjadi kesalahan koneksi jaringan.', 'error');
                    console.error(error);
                } finally {
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            });
        }
    </script>

    <!-- end javascript -->

</body>