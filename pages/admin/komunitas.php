<?php
require_once '../../src/classes/auth.php';
require_once '../../src/classes/komunitas.php';

$auth = new auth();
$auth->authOrNot();

$komunitasModel = new Komunitas();
$komunitasData = $komunitasModel->getAllKomunitasAdmin();
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
                            <div class="row">
                                <h4 class="mb-sm-0 font-weight-bold mb-1">
                                    Lihat Semua Komunitas
                                </h4>
                                <p class="text-muted">
                                    Kelola komunitas anda.
                                </p>
                            </div>
                            <div>
                                <a class="btn btn-primary btn-rounded waves-effect mb-2" href="#" data-bs-toggle="modal" data-bs-target="#modalTambahKomunitas">
                                    <span>
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    Tambah Komunitas
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
                                        Daftar Komunitas
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered dt-responsive w-100 align-middle" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Foto</th>
                                                <th>Nama Komunitas</th>
                                                <th>Deskripsi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php foreach ($komunitasData as $komunitas) : ?>
                                                <tr id="row-<?= $komunitas['id'] ?>">
                                                    <td><?= $no++ ?></td>
                                                    <td>
                                                        <img src="../../uploads/komunitas/<?= htmlspecialchars($komunitas['foto']) ?>"
                                                             alt="Foto Komunitas"
                                                             class="avatar-sm rounded-circle"
                                                             onerror="this.src='../../assets/images/users/avatar-1.jpg'">
                                                    </td>
                                                    <td><?= htmlspecialchars($komunitas['nama_komunitas']) ?></td>
                                                    <td>
                                                        <?= htmlspecialchars($komunitas['deskripsi']) ?>
                                                    </td>
                                                    <td>
                                                        <a href="#"
                                                            data-id="<?= $komunitas['id'] ?>"
                                                            data-nama="<?= htmlspecialchars($komunitas['nama_komunitas'], ENT_QUOTES) ?>"
                                                            data-deskripsi="<?= htmlspecialchars($komunitas['deskripsi'], ENT_QUOTES) ?>"
                                                            data-foto="<?= htmlspecialchars($komunitas['foto'], ENT_QUOTES) ?>"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditKomunitas"
                                                            class="btn btn-sm btn-warning btn-edit">Edit</a>
                                                        <button type="button" data-id="<?= $komunitas['id'] ?>" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus">Hapus</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($komunitasData)): ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">Tidak ada data komunitas.</td>
                                                </tr>
                                            <?php endif; ?>
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
    <!-- end javascript -->

    <!-- ========================================================= -->
    <!-- Bagian Pop-up Tambah Komunitas (Modal) -->
    <!-- ========================================================= -->
    <div class="modal fade" id="modalTambahKomunitas" tabindex="-1" aria-labelledby="modalTambahKomunitasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Header Modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahKomunitasLabel">Form Tambah Komunitas</h5>
                    <!-- Tombol silang untuk menutup modal -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                <form id="formKomunitas" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="foto_tambah">
                                Foto Komunitas
                            </label>
                            <input class="form-control" id="foto_tambah" name="foto" type="file" accept="image/*" required>
                            <small class="form-text text-muted">Format: JPG, PNG, WebP. Maksimal 5MB.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nama_komunitas_tambah">
                                Nama Komunitas
                            </label>
                            <input class="form-control" id="nama_komunitas_tambah" name="nama_komunitas" placeholder="Masukkan nama komunitas" type="text" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="deskripsi_tambah">
                                Deskripsi
                            </label>
                            <textarea class="form-control" id="deskripsi_tambah" name="deskripsi" placeholder="Masukkan deskripsi komunitas" rows="4" required></textarea>
                        </div>
                    </div>

                    <!-- Footer Modal (Tombol Aksi) -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnTambah">Simpan Komunitas</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- Bagian Pop-up Edit Komunitas (Modal) -->
    <!-- ========================================================= -->
    <div class="modal fade" id="modalEditKomunitas" tabindex="-1" aria-labelledby="modalEditKomunitasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Header Modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditKomunitasLabel">Form Edit Komunitas</h5>
                    <!-- Tombol silang untuk menutup modal -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                <form id="formEditKomunitas" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id" value="">
                        <input type="hidden" name="existing_foto" id="edit_existing_foto" value="">

                        <div class="mb-3">
                            <label class="form-label" for="edit_foto">
                                Ganti Foto Komunitas
                            </label>
                            <input class="form-control" id="edit_foto" name="foto" type="file" accept="image/*">
                            <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti foto. Format: JPG, PNG, WebP. Maksimal 5MB.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit_nama_komunitas">
                                Nama Komunitas
                            </label>
                            <input class="form-control" id="edit_nama_komunitas" name="nama_komunitas" placeholder="Masukkan nama komunitas" type="text" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit_deskripsi">
                                Deskripsi
                            </label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" placeholder="Masukkan deskripsi komunitas" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="foto_lama_edit">Foto Saat Ini</label>
                            <div class="d-flex align-items-center">
                                <img id="preview_foto_lama" src="" alt="Foto Komunitas" class="avatar-sm rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                <span id="nama_foto_lama" class="form-control-plaintext"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Modal (Tombol Aksi) -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnEdit">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        // --- Variabel Global ---
        const formKomunitas = document.getElementById('formKomunitas');
        const formEditKomunitas = document.getElementById('formEditKomunitas');
        const btnTambah = document.getElementById('btnTambah');
        const btnEdit = document.getElementById('btnEdit');
        const modalEditKomunitas = document.getElementById('modalEditKomunitas');
        const elemenModalNotif = document.getElementById('modalNotifikasi');
        const elemenToastNotif = elemenModalNotif ? elemenModalNotif.querySelector('.toast') : null;
        const datatableElement = document.getElementById('datatable');

        const dataTable = window.jQuery && window.jQuery.fn && window.jQuery.fn.dataTable && window.jQuery.fn.dataTable.isDataTable('#datatable') ?
            window.jQuery('#datatable').DataTable() :
            null;

        let modalNotifInstance = null;
        let komunitasIdToDelete = null;
        let komunitasRowToDelete = null;

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

        // --- Renumber all rows in the table ---
        function renumberRows() {
            if (dataTable) {
                dataTable.rows().every(function(rowIdx, tableLoop, rowLoop) {
                    this.node().querySelector('td:first-child').textContent = rowIdx + 1;
                });
            } else {
                const tbody = datatableElement?.querySelector('tbody');
                if (tbody) {
                    const rows = tbody.querySelectorAll('tr');
                    rows.forEach((row, index) => {
                        const firstCell = row.querySelector('td');
                        if (firstCell) {
                            firstCell.textContent = index + 1;
                        }
                    });
                }
            }
        }

        // --- Kirim Form (Tambah & Edit) ---
        async function kirimForm(formElement, submitButton, isEdit = false) {
            if (!formElement || !submitButton) return;

            submitButton.disabled = true;
            submitButton.innerText = 'Memproses...';

            const formData = new FormData(formElement);
            formData.append('action', isEdit ? 'update' : 'save');

            try {
                const response = await fetch('../../src/actions/proses_komunitas.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    // 1. TUTUP MODAL DULU
                    if (formElement === formKomunitas) {
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahKomunitas')).hide();
                    } else if (formElement === formEditKomunitas) {
                        bootstrap.Modal.getOrCreateInstance(modalEditKomunitas).hide();
                    }

                    // 2. RESET FORM
                    formElement.reset();

                    // 3. REFRESH TABLE DARI SERVER
                    await refreshTable();

                    tampilkanNotif('Berhasil', result.message, 'success');
                } else {
                    tampilkanNotif('Gagal', result.message, 'error');
                }
            } catch (error) {
                tampilkanNotif('Koneksi Gagal', 'Terjadi kesalahan koneksi jaringan.', 'error');
                console.error(error);
            } finally {
                submitButton.disabled = false;
                submitButton.innerText = formElement === formKomunitas ? 'Simpan Komunitas' : 'Simpan Perubahan';
            }
        }

        // --- Refresh entire table from server ---
        async function refreshTable() {
            try {
                const formData = new FormData();
                formData.append('action', 'get_all');

                const response = await fetch('../../src/actions/proses_komunitas.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success' && result.data) {
                    if (dataTable) {
                        dataTable.clear().draw();
                        result.data.forEach((komunitas, index) => {
                            const rowData = [
                                index + 1,
                                `<img src="../../uploads/komunitas/${escapeHtml(komunitas.foto)}" alt="Foto" class="avatar-sm rounded-circle" onerror="this.src='../../assets/images/users/avatar-1.jpg'">`,
                                escapeHtml(komunitas.nama_komunitas),
                                `<div>${escapeHtml(komunitas.deskripsi)}</div>`,
                                buildActions(komunitas.id, komunitas.nama_komunitas, komunitas.deskripsi, komunitas.foto)
                            ];
                            const row = dataTable.row.add(rowData).draw(false).node();
                            if (row) row.id = 'row-' + komunitas.id;
                        });
                    } else {
                        const tbody = datatableElement?.querySelector('tbody');
                        if (tbody) {
                            tbody.innerHTML = '';
                            result.data.forEach((komunitas, index) => {
                                const tr = document.createElement('tr');
                                tr.id = 'row-' + komunitas.id;
                                tr.innerHTML = `
                                    <td>${index + 1}</td>
                                    <td><img src="../../uploads/komunitas/${escapeHtml(komunitas.foto)}" alt="Foto" class="avatar-sm rounded-circle" onerror="this.src='../../assets/images/users/avatar-1.jpg'"></td>
                                    <td>${escapeHtml(komunitas.nama_komunitas)}</td>
                                    <td>${escapeHtml(komunitas.deskripsi)}</td>
                                    <td>${buildActions(komunitas.id, komunitas.nama_komunitas, komunitas.deskripsi, komunitas.foto)}</td>
                                `;
                                tbody.appendChild(tr);
                            });
                        }
                    }
                }
            } catch (error) {
                console.error('Error refreshing table:', error);
            }
        }

        // --- Build Actions HTML ---
        function buildActions(id, nama, deskripsi, foto) {
            return `
                <a href="#"
                    data-id="${id}"
                    data-nama="${escapeHtml(nama, true)}"
                    data-deskripsi="${escapeHtml(deskripsi, true)}"
                    data-foto="${escapeHtml(foto, true)}"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditKomunitas"
                    class="btn btn-sm btn-warning btn-edit">Edit</a>
                <button type="button" data-id="${id}" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus">Hapus</button>
            `;
        }

        // --- Event Listener: Tambah Data ---
        if (formKomunitas) {
            formKomunitas.addEventListener('submit', function(e) {
                e.preventDefault();
                kirimForm(formKomunitas, btnTambah, false);
            });
        }

        // --- Event Listener: Edit Data ---
        if (formEditKomunitas) {
            formEditKomunitas.addEventListener('submit', function(e) {
                e.preventDefault();
                kirimForm(formEditKomunitas, btnEdit, true);
            });
        }

        // --- Event Listener: Buka Modal Edit ---
        if (modalEditKomunitas) {
            modalEditKomunitas.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                if (!button) return;

                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama') || '';
                const deskripsi = button.getAttribute('data-deskripsi') || '';
                const foto = button.getAttribute('data-foto') || '';

                document.getElementById('edit_id').value = id;
                document.getElementById('edit_existing_foto').value = foto;
                document.getElementById('edit_nama_komunitas').value = nama;
                document.getElementById('edit_deskripsi').value = deskripsi;
                document.getElementById('edit_foto').value = '';

                // Preview foto lama
                document.getElementById('preview_foto_lama').src = '../../uploads/komunitas/' + foto;
                document.getElementById('preview_foto_lama').onerror = function() {
                    this.src = '../../assets/images/users/avatar-1.jpg';
                };
                document.getElementById('nama_foto_lama').textContent = foto;
            });
        }

        // --- Event Listener: Delegasi Edit/Hapus dari Tabel ---
        if (datatableElement) {
            datatableElement.addEventListener('click', function(event) {
                // Tangani tombol Edit
                const editButton = event.target.closest('.btn-edit');
                if (editButton) {
                    event.preventDefault();
                    const id = editButton.getAttribute('data-id');
                    if (!id) return;

                    const nama = editButton.getAttribute('data-nama') || '';
                    const deskripsi = editButton.getAttribute('data-deskripsi') || '';
                    const foto = editButton.getAttribute('data-foto') || '';

                    // Isi form edit
                    document.getElementById('edit_id').value = id;
                    document.getElementById('edit_existing_foto').value = foto;
                    document.getElementById('edit_nama_komunitas').value = nama;
                    document.getElementById('edit_deskripsi').value = deskripsi;
                    document.getElementById('edit_foto').value = '';

                    // Preview foto lama
                    document.getElementById('preview_foto_lama').src = '../../uploads/komunitas/' + foto;
                    document.getElementById('preview_foto_lama').onerror = function() {
                        this.src = '../../assets/images/users/avatar-1.jpg';
                    };
                    document.getElementById('nama_foto_lama').textContent = foto;

                    const editModal = bootstrap.Modal.getOrCreateInstance(modalEditKomunitas);
                    editModal.show();
                    return;
                }

                // Tangani tombol Hapus
                const deleteButton = event.target.closest('.btn-delete');
                if (!deleteButton) return;

                const id = deleteButton.getAttribute('data-id');
                if (!id) return;

                komunitasIdToDelete = id;
                komunitasRowToDelete = document.getElementById('row-' + id);

                // Show first confirmation modal (Tahap 1)
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
                if (!komunitasIdToDelete) return;

                const button = this;
                const originalText = button.innerHTML;
                button.disabled = true;
                button.innerHTML = 'Menghapus...';

                try {
                    const formData = new FormData();
                    formData.append('action', 'delete');
                    formData.append('id', komunitasIdToDelete);

                    const response = await fetch('../../src/actions/proses_komunitas.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    // Hide both modals
                    const modalDelete1 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus1'));
                    const modalDelete2 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus2'));
                    modalDelete1.hide();
                    modalDelete2.hide();

                    if (result.status === 'success') {
                        // Refresh table from server
                        await refreshTable();
                        tampilkanNotif('Berhasil', result.message, 'success');
                    } else {
                        tampilkanNotif('Gagal', result.message, 'error');
                    }
                } catch (error) {
                    tampilkanNotif('Gagal', 'Terjadi kesalahan koneksi jaringan.', 'error');
                    console.error(error);
                } finally {
                    button.disabled = false;
                    button.innerHTML = originalText;
                    komunitasIdToDelete = null;
                    komunitasRowToDelete = null;
                }
            });
        }
    </script>
</body>

</html>