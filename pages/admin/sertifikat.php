<?php
require_once '../../src/classes/auth.php';
$auth = new auth();
$auth->authOrNot();

require_once '../../src/classes/Sertifikat.php';

$sertifikat = new Sertifikat();
$dataSertifikat = $sertifikat->getAll();
?>
<!--header start-->
<?php include('../include/header.php'); ?>
<!--header end-->

<body>
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
                                    Lihat Semua Sertifikat
                                </h4>
                                <p class="text-muted">
                                    Kelola data sertifikat pengguna dengan mudah!
                                </p>
                            </div>
                            <div>
                                <a class="btn btn-primary btn-rounded waves-effect mb-2" href="#" data-bs-toggle="modal" data-bs-target="#modalTambahSertifikat">
                                    <span>
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    Tambah Sertifikat
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
                                        Daftar Sertifikat
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered dt-responsive w-100" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul</th>
                                                <th>File</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($dataSertifikat as $sertifikat) : ?>
                                                <tr id="baris-<?= $sertifikat['id'] ?>">
                                                    <td><?= $i++ ?></td>
                                                    <td><?= htmlspecialchars($sertifikat['judul']) ?></td>
                                                    <td>
                                                        <?php if (!empty($sertifikat['file'])): ?>
                                                            <?= htmlspecialchars($sertifikat['file']) ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="#"
                                                            data-id="<?= $sertifikat['id'] ?>"
                                                            data-judul="<?= htmlspecialchars($sertifikat['judul'], ENT_QUOTES) ?>"
                                                            data-file="<?= htmlspecialchars($sertifikat['file'] ?? '', ENT_QUOTES) ?>"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditSertifikat"
                                                            class="btn btn-sm btn-warning btn-edit me-1">
                                                            Edit
                                                        </a>
                                                        <button type="button"
                                                            data-id="<?= $sertifikat['id'] ?>"
                                                            data-file="<?= htmlspecialchars($sertifikat['file'] ?? '', ENT_QUOTES) ?>"
                                                            class="btn btn-sm btn-danger btn-delete"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalKonfirmasiHapus1">
                                                            Hapus
                                                        </button>
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
    <!-- Bagian Pop-up Tambah Sertifikat (Modal) -->
    <!-- ========================================================= -->
    <div class="modal fade" id="modalTambahSertifikat" tabindex="-1" aria-labelledby="modalTambahSertifikatLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Header Modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahSertifikatLabel">Form Tambah Sertifikat</h5>
                    <!-- Tombol silang untuk menutup modal -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                <form id="formTambahSertifikat" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="judul_tambah" class="form-label">Judul Sertifikat <span class="text-danger">*</span></label>
                            <input class="form-control" name="judul" id="judul_tambah" type="text" required maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label for="file_sertifikat_tambah" class="form-label">File Sertifikat <span class="text-danger">*</span></label>
                            <input class="form-control" type="file" id="file_sertifikat_tambah" name="file_sertifikat" accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 5MB.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnTambahSertifikat" class="btn btn-primary">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- ========================================================= -->
    <!-- Bagian Pop-up Edit Sertifikat (Modal) -->
    <!-- ========================================================= -->
    <div class="modal fade" id="modalEditSertifikat" tabindex="-1" aria-labelledby="modalEditSertifikatLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Header Modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditSertifikatLabel">Form Edit Sertifikat</h5>
                    <!-- Tombol silang untuk menutup modal -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                <form id="formEditSertifikat" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <input type="hidden" name="file_lama" id="file_lama">
                        <div class="mb-3">
                            <label for="judul_edit" class="form-label">Judul Sertifikat <span class="text-danger">*</span></label>
                            <input class="form-control" name="judul" id="judul_edit" type="text" required maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label for="file_sertifikat_edit" class="form-label">File Sertifikat (Kosongkan jika tidak diubah)</label>
                            <input class="form-control" type="file" id="file_sertifikat_edit" name="file_sertifikat" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 5MB. Biarkan kosong jika tidak ingin mengubah file.</small>
                        </div>
                        <div class="mb-3">
                            <label for="file_lama_edit" class="form-label">File Saat Ini</label>
                            <p class="form-control-plaintext" id="file_lama_edit"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnEditSertifikat" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Toast & Modal Konfirmasi Hapus -->
    <?php include("../include/toast.php"); ?>

    <script>
        // --- Variabel Global ---
        const formTambahSertifikat = document.getElementById('formTambahSertifikat');
        const formEditSertifikat = document.getElementById('formEditSertifikat');
        const btnTambahSertifikat = document.getElementById('btnTambahSertifikat');
        const btnEditSertifikat = document.getElementById('btnEditSertifikat');
        const modalEditSertifikat = document.getElementById('modalEditSertifikat');
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

        // --- Renumber all rows in the table ---
        function renumberRows() {
            if (dataTable) {
                // For DataTables, we need to update the first column of each row
                const rows = dataTable.rows().nodes();
                $(rows).each(function(index, row) {
                    $('td:first', this).text(index + 1);
                });
            } else {
                // For regular table
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

        // --- Kirim Form (Tambah) ---
        async function kirimFormTambah(formElement, submitButton) {
            if (!formElement || !submitButton) return;

            submitButton.disabled = true;
            submitButton.innerText = 'Memproses...';

            const formData = new FormData(formElement);
            formData.append('action', 'add');

            try {
                const response = await fetch('../../src/actions/proses_sertifikat.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Tutup Modal
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahSertifikat')).hide();

                    // Reset Form
                    formElement.reset();

                    // Refresh entire table from server (like materi.php)
                    await refreshTable();

                    tampilkanNotif('Berhasil', result.message, 'success');
                } else {
                    tampilkanNotif('Gagal', result.message, 'error');
                    if (result.errors) {
                        // Tampilkan error per field
                        Object.keys(result.errors).forEach(field => {
                            const input = document.getElementById(field + '_tambah');
                            if (input) input.classList.add('is-invalid');
                        });
                    }
                }
            } catch (error) {
                tampilkanNotif('Koneksi Gagal', 'Terjadi kesalahan koneksi jaringan.', 'error');
                console.error(error);
            } finally {
                submitButton.disabled = false;
                submitButton.innerText = 'Simpan';
            }
        }

        // --- Kirim Form (Edit) ---
        async function kirimFormEdit(formElement, submitButton) {
            if (!formElement || !submitButton) return;

            submitButton.disabled = true;
            submitButton.innerText = 'Memproses...';

            const formData = new FormData(formElement);
            formData.append('action', 'edit');

            try {
                const response = await fetch('../../src/actions/proses_sertifikat.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Tutup Modal
                    bootstrap.Modal.getOrCreateInstance(modalEditSertifikat).hide();

                    // Reset Form
                    formElement.reset();

                    // Refresh entire table from server (like materi.php)
                    await refreshTable();

                    tampilkanNotif('Berhasil', result.message, 'success');
                } else {
                    tampilkanNotif('Gagal', result.message, 'error');
                    if (result.errors) {
                        // Tampilkan error per field
                        Object.keys(result.errors).forEach(field => {
                            const input = document.getElementById(field + '_edit');
                            if (input) input.classList.add('is-invalid');
                        });
                    }
                }
            } catch (error) {
                tampilkanNotif('Koneksi Gagal', 'Terjadi kesalahan koneksi jaringan.', 'error');
                console.error(error);
            } finally {
                submitButton.disabled = false;
                submitButton.innerText = 'Simpan Perubahan';
            }
        }

        // --- Refresh entire table from server (like materi.php) ---
        async function refreshTable() {
            try {
                const formData = new FormData();
                formData.append('action', 'get_sertifikat');

                const response = await fetch('../../src/actions/proses_sertifikat.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success && result.data) {
                    // Clear and rebuild table
                    if (dataTable) {
                        dataTable.clear().draw();
                        result.data.forEach((sertifikat, index) => {
                            const rowData = [
                                index + 1,
                                escapeHtml(sertifikat.judul),
                                sertifikat.file ?
                                '<a href="../../uploads/sertifikat/' + escapeHtml(sertifikat.file) + '" target="_blank" class="text-primary">' + escapeHtml(sertifikat.file) + '</a>' :
                                '<span class="text-muted">-</span>',
                                buildActions(sertifikat.id, sertifikat.judul, sertifikat.file)
                            ];
                            const row = dataTable.row.add(rowData).draw(false).node();
                            if (row) row.id = 'baris-' + sertifikat.id;
                        });
                    } else {
                        // For regular table
                        const tbody = datatableElement?.querySelector('tbody');
                        if (tbody) {
                            tbody.innerHTML = '';
                            result.data.forEach((sertifikat, index) => {
                                const tr = document.createElement('tr');
                                tr.id = 'baris-' + sertifikat.id;
                                tr.innerHTML = `
                                    <td>${index + 1}</td>
                                    <td>${escapeHtml(sertifikat.judul)}</td>
                                    <td>${sertifikat.file
                                        ? '<a href="../../uploads/sertifikat/' + escapeHtml(sertifikat.file) + '" target="_blank" class="text-primary">' + escapeHtml(sertifikat.file) + '</a>'
                                        : '<span class="text-muted">-</span>'}</td>
                                    <td>${buildActions(sertifikat.id, sertifikat.judul, sertifikat.file)}</td>
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

        // --- Render Row Actions ---
        function buildActions(id, judul, file) {
            return `
                <a href="#"
                    data-id="${id}"
                    data-judul="${escapeHtml(judul, true)}"
                    data-file="${escapeHtml(file ?? '', true)}"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditSertifikat"
                    class="btn btn-sm btn-warning btn-edit me-1">
                    Edit
                </a>
                <button type="button"
                    data-id="${id}"
                    data-file="${escapeHtml(file ?? '', true)}"
                    class="btn btn-sm btn-danger btn-delete"
                    data-bs-toggle="modal"
                    data-bs-target="#modalKonfirmasiHapus1">
                    Hapus
                </button>`;
        }

        // --- Update Row di Tabel ---
        function updateRow(data) {
            if (dataTable) {
                const row = dataTable.row('#baris-' + data.id);
                if (row.length) {
                    const existingData = row.data();
                    const rowNumber = existingData ? existingData[0] : dataTable.rows().count();
                    const rowData = [
                        rowNumber,
                        escapeHtml(data.judul),
                        data.file ?
                        '<a href="../../uploads/sertifikat/' + escapeHtml(data.file) + '" target="_blank" class="text-primary">' + escapeHtml(data.file) + '</a>' :
                        '<span class="text-muted">-</span>',
                        buildActions(data.id, data.judul, data.file)
                    ];
                    row.data(rowData).draw(false);
                    const node = row.node();
                    if (node) node.id = 'baris-' + data.id;
                }
                return;
            }

            // DOM fallback
            const row = document.getElementById('baris-' + data.id);
            if (!row) {
                console.warn('Row tidak ditemukan: baris-' + data.id);
                return;
            }

            const cells = row.querySelectorAll('td');
            if (cells.length >= 3) {
                cells[1].textContent = data.judul;
                cells[2].innerHTML = data.file ?
                    '<a href="../../uploads/sertifikat/' + escapeHtml(data.file) + '" target="_blank" class="text-primary">' + escapeHtml(data.file) + '</a>' :
                    '<span class="text-muted">-</span>';
                cells[3].innerHTML = buildActions(data.id, data.judul, data.file);
            }
        }

        // --- Event Listener: Tambah Data ---
        if (formTambahSertifikat) {
            formTambahSertifikat.addEventListener('submit', function(e) {
                e.preventDefault();
                // Hapus class is-invalid saat submit baru
                formTambahSertifikat.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                kirimFormTambah(formTambahSertifikat, btnTambahSertifikat);
            });
        }

        // --- Event Listener: Edit Data ---
        if (formEditSertifikat) {
            formEditSertifikat.addEventListener('submit', function(e) {
                e.preventDefault();
                // Hapus class is-invalid saat submit baru
                formEditSertifikat.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                kirimFormEdit(formEditSertifikat, btnEditSertifikat);
            });
        }

        // --- Event Listener: Buka Modal Edit ---
        if (modalEditSertifikat) {
            modalEditSertifikat.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                if (!button) return;

                const id = button.getAttribute('data-id');
                const judul = button.getAttribute('data-judul') || '';
                const file = button.getAttribute('data-file') || '';

                document.getElementById('edit_id').value = id;
                document.getElementById('judul_edit').value = judul;
                document.getElementById('file_lama').value = file;
                document.getElementById('file_lama_edit').textContent = file ? 'File saat ini: ' + file : 'Tidak ada file';
            });
        }

        // --- Event Listener: Reset form Tambah saat modal dibuka ---
        const modalTambahSertifikat = document.getElementById('modalTambahSertifikat');
        if (modalTambahSertifikat) {
            modalTambahSertifikat.addEventListener('show.bs.modal', function() {
                formTambahSertifikat.reset();
                formTambahSertifikat.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
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

                    const judul = editButton.getAttribute('data-judul') || '';
                    const file = editButton.getAttribute('data-file') || '';

                    // Isi form edit
                    document.getElementById('edit_id').value = id;
                    document.getElementById('judul_edit').value = judul;
                    document.getElementById('file_lama').value = file;
                    document.getElementById('file_lama_edit').textContent = file ? 'File saat ini: ' + file : 'Tidak ada file';

                    const editModal = bootstrap.Modal.getOrCreateInstance(modalEditSertifikat);
                    editModal.show();
                    return;
                }

                // Tangani tombol Hapus - Tampilkan konfirmasi tahap 1
                const deleteButton = event.target.closest('.btn-delete');
                if (!deleteButton) return;

                const id = deleteButton.getAttribute('data-id');
                if (!id) return;

                // Store ID untuk hapus
                window.sertifikatIdToDelete = id;

                const modalDelete1 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus1'));
                modalDelete1.show();
            });
        }

        // --- Lanjutkan Hapus (dari modal konfirmasi tahap 1 ke tahap 2) ---
        const btnLanjutkanHapus = document.getElementById('btnLanjutkanHapus');
        if (btnLanjutkanHapus) {
            btnLanjutkanHapus.addEventListener('click', function() {
                // Hide modal 1
                const modalDelete1 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus1'));
                modalDelete1.hide();

                // Show modal 2
                const modalDelete2 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus2'));
                modalDelete2.show();
            });
        }

        // --- Eksekusi Hapus (dari modal konfirmasi tahap 2) ---
        const btnEksekusiHapus = document.getElementById('btnEksekusiHapus');
        if (btnEksekusiHapus) {
            btnEksekusiHapus.addEventListener('click', async function() {
                const id = window.sertifikatIdToDelete;
                if (!id) return;

                const button = this;
                const originalText = button.innerHTML;
                button.disabled = true;
                button.innerHTML = 'Menghapus...';

                try {
                    const response = await fetch('../../src/actions/proses_sertifikat.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `action=delete&id=${encodeURIComponent(id)}`
                    });

                    const result = await response.json();

                    // Hide modal 2 (the one currently shown)
                    const modalDelete2 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus2'));
                    modalDelete2.hide();

                    if (result.success) {
                        // Refresh entire table from server (like materi.php)
                        await refreshTable();

                        tampilkanNotif('Berhasil', result.message, 'success');
                    } else {
                        tampilkanNotif('Gagal', result.message, 'error');
                    }
                } catch (error) {
                    tampilkanNotif('Gagal', 'Terjadi kesalahan saat menghapus data.', 'error');
                    console.error(error);
                } finally {
                    button.disabled = false;
                    button.innerHTML = originalText;
                    window.sertifikatIdToDelete = null;
                }
            });
        }
    </script>
</body>

</html>