<?php
require_once '../../src/classes/auth.php';
$auth = new auth();
$auth->authOrNot();

require_once '../../src/classes/materi.php';

$materi = new Materi();

$dataMateri = $materi->getAllMateri();
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
                                    Lihat Semua Materi
                                </h4>
                                <p class="text-muted">
                                    Kustomisasi materi edukasi sesuai kebutuhan Anda!
                                </p>
                            </div>
                            <div>
                                <a class="btn btn-primary btn-rounded waves-effect mb-2" href="#" data-bs-toggle="modal" data-bs-target="#modalTambahMateri">
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
                                <div class="card-body">
                                    <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Materi</th>
                                                <th>Deskripsi Materi</th>
                                                <th>File Materi</th>
                                                <th>Link Video</th>
                                                <th>No Urut</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($dataMateri as $materi) : ?>
                                                <tr id="baris-<?= $materi['id'] ?>">
                                                    <td><?= $i++ ?></td>
                                                    <td><?= htmlspecialchars($materi['judul']) ?></td>
                                                    <td><?= htmlspecialchars($materi['deskripsi']) ?></td>
                                                    <td><?= htmlspecialchars($materi['file']) ?></td>
                                                    <td><a href="<?= htmlspecialchars($materi['video_url']) ?>" target="_blank"><?= htmlspecialchars($materi['video_url']) ?></a></td>
                                                    <td><?= htmlspecialchars($materi['no_urut']) ?></td>
                                                    <td>
                                                        <a href="#"
                                                            data-id="<?= $materi['id'] ?>"
                                                            data-judul="<?= htmlspecialchars($materi['judul']) ?>"
                                                            data-deskripsi="<?= htmlspecialchars($materi['deskripsi']) ?>"
                                                            data-video_url="<?= htmlspecialchars($materi['video_url']) ?>"
                                                            data-no_urut="<?= htmlspecialchars($materi['no_urut']) ?>"
                                                            data-file="<?= htmlspecialchars($materi['file']) ?>"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditMateri"
                                                            class="btn btn-sm btn-warning btn-edit">Edit</a>
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
    <!-- Bagian Pop-up Tambah materi (Modal) -->
    <!-- ========================================================= -->
    <div class="modal fade" id="modalTambahMateri" tabindex="-1" aria-labelledby="modalTambahMateriLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Header Modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahMateriLabel">Form Tambah Materi</h5>
                    <!-- Tombol silang untuk menutup modal -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                <form id="formMateri" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="judul_tambah" class="form-label">Judul Materi</label>
                            <input class="form-control" name="judul" id="judul_tambah" type="text" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi_tambah" class="form-label">Deskripsi Materi</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi_tambah" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="video_url_tambah" class="form-label">Link Video (Opsional)</label>
                            <input class="form-control" name="video_url" id="video_url_tambah" type="url">
                        </div>
                        <div class="mb-3">
                            <label for="no_urut_tambah" class="form-label">No Urut</label>
                            <input class="form-control" name="no_urut" id="no_urut_tambah" type="number" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="file_materi_tambah" class="form-label">File Materi (PDF, maks 10MB)</label>
                            <input class="form-control" type="file" id="file_materi_tambah" name="file_materi" accept=".pdf" required>
                            <small class="text-muted">File harus berekstensi PDF dengan maksimal ukuran 10MB</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnTambahMateri" class="btn btn-primary">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- ========================================================= -->
    <!-- Bagian Pop-up Edit materi (Modal) -->
    <!-- ========================================================= -->
    <div class="modal fade" id="modalEditMateri" tabindex="-1" aria-labelledby="modalEditMateriLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Header Modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditMateriLabel">Form Edit Materi</h5>
                    <!-- Tombol silang untuk menutup modal -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                <form id="formEditMateri" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label for="judul_edit" class="form-label">Judul Materi</label>
                            <input class="form-control" name="judul" id="judul_edit" type="text" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi_edit" class="form-label">Deskripsi Materi</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi_edit" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="video_url_edit" class="form-label">Link Video (Opsional)</label>
                            <input class="form-control" name="video_url" id="video_url_edit" type="url">
                        </div>
                        <div class="mb-3">
                            <label for="no_urut_edit" class="form-label">No Urut</label>
                            <input class="form-control" name="no_urut" id="no_urut_edit" type="number" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="file_materi_edit" class="form-label">File Materi (PDF, maks 10MB - Kosongkan jika tidak diubah)</label>
                            <input class="form-control" type="file" id="file_materi_edit" name="file_materi" accept=".pdf">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file</small>
                        </div>
                        <div class="mb-3">
                            <label for="file_lama_edit" class="form-label">File Saat Ini</label>
                            <p class="form-control-plaintext" id="file_lama_edit"></p>
                            <input type="hidden" name="file_lama" id="file_lama">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnEditMateri" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Toast & Modal Konfirmasi Hapus -->
    <?php include("../include/toast.php"); ?>

    <script>
        // --- Variabel Global ---
        const formMateri = document.getElementById('formMateri');
        const formEditMateri = document.getElementById('formEditMateri');
        const btnTambahMateri = document.getElementById('btnTambahMateri');
        const btnEditMateri = document.getElementById('btnEditMateri');
        const modalEditMateri = document.getElementById('modalEditMateri');
        const elemenModalNotif = document.getElementById('modalNotifikasi');
        const elemenToastNotif = elemenModalNotif ? elemenModalNotif.querySelector('.toast') : null;
        const datatableElement = document.getElementById('datatable');

        const dataTable = window.jQuery && window.jQuery.fn && window.jQuery.fn.dataTable && window.jQuery.fn.dataTable.isDataTable('#datatable') ?
            window.jQuery('#datatable').DataTable() :
            null;

        let modalNotifInstance = null;
        let materiIdToDelete = null;
        let materiRowToDelete = null;

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

        // --- Kirim Form (Tambah & Edit) ---
        async function kirimForm(formElement, submitButton) {
            if (!formElement || !submitButton) return;

            submitButton.disabled = true;
            submitButton.innerText = 'Memproses...';

            const formData = new FormData(formElement);
            formData.append('action', 'save');

            try {
                const response = await fetch('../../src/actions/proses_materi.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    // Ambil nilai dari respons server
                    const id = result.id;
                    const judul = result.judul;
                    const deskripsi = result.deskripsi;
                    const videoUrl = result.video_url;
                    const noUrut = result.no_urut;
                    const fileNama = result.file || ''; // Ambil file dari respons server

                    // Tutup Modal
                    if (formElement === formMateri) {
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahMateri')).hide();
                    } else if (formElement === formEditMateri) {
                        bootstrap.Modal.getOrCreateInstance(modalEditMateri).hide();
                    }

                    // Reset Form
                    formElement.reset();

                    // Update atau Tambah row tabel
                    if (formElement === formMateri) {
                        tambahRow({
                            id,
                            judul,
                            deskripsi,
                            videoUrl,
                            noUrut,
                            file: fileNama
                        });
                    } else {
                        updateRow({
                            id,
                            judul,
                            deskripsi,
                            videoUrl,
                            noUrut,
                            file: fileNama
                        });
                    }

                    // Renumber all rows after operation
                    renumberRows();

                    tampilkanNotif('Berhasil', result.message, 'success');
                } else {
                    tampilkanNotif('Gagal', result.message, 'error');
                }
            } catch (error) {
                tampilkanNotif('Koneksi Gagal', 'Terjadi kesalahan koneksi jaringan.', 'error');
                console.error(error);
            } finally {
                submitButton.disabled = false;
                submitButton.innerText = formElement === formMateri ? 'Simpan' : 'Simpan Perubahan';
            }
        }

        // --- Render Row ke Tabel ---
        function buildActions(id, judul, deskripsi, videoUrl, noUrut, file) {
            return `
                <a href="#"
                    data-id="${id}"
                    data-judul="${escapeHtml(judul, true)}"
                    data-deskripsi="${escapeHtml(deskripsi, true)}"
                    data-video_url="${escapeHtml(videoUrl, true)}"
                    data-no_urut="${escapeHtml(noUrut, true)}"
                    data-file="${escapeHtml(file, true)}"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditMateri"
                    class="btn btn-sm btn-warning btn-edit">Edit</a>
                <button type="button" data-id="${id}" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus">Hapus</button>
            `;
        }

        function getRowNumber() {
            if (dataTable) {
                return dataTable.rows().count() + 1;
            }
            const tbody = datatableElement?.querySelector('tbody');
            if (tbody) {
                return tbody.querySelectorAll('tr').length + 1;
            }
            return 1;
        }

        function tambahRow(data) {
            const rowData = [
                getRowNumber(),
                escapeHtml(data.judul),
                escapeHtml(data.deskripsi),
                escapeHtml(data.file),
                escapeHtml(data.videoUrl),
                escapeHtml(data.noUrut),
                buildActions(data.id, data.judul, data.deskripsi, data.videoUrl, data.noUrut, data.file)
            ];

            if (dataTable) {
                const row = dataTable.row.add(rowData).draw(false).node();
                if (row) row.id = 'baris-' + data.id;
                return;
            }

            const tbody = datatableElement?.querySelector('tbody');
            if (!tbody) return;

            const tr = document.createElement('tr');
            tr.id = 'baris-' + data.id;
            tr.innerHTML = `
                <td>${getRowNumber()}</td>
                <td>${escapeHtml(data.judul)}</td>
                <td>${escapeHtml(data.deskripsi)}</td>
                <td>${escapeHtml(data.file)}</td>
                <td>${escapeHtml(data.videoUrl)}</td>
                <td>${escapeHtml(data.noUrut)}</td>
                <td>${buildActions(data.id, data.judul, data.deskripsi, data.videoUrl, data.noUrut, data.file)}</td>
            `;
            tbody.appendChild(tr);
        }

        function updateRow(data) {
            if (dataTable) {
                const row = dataTable.row('#baris-' + data.id);
                if (row.length) {
                    const existingData = row.data();
                    const rowNumber = existingData ? existingData[0] : getRowNumber();
                    const rowData = [
                        rowNumber,
                        escapeHtml(data.judul),
                        escapeHtml(data.deskripsi),
                        escapeHtml(data.file),
                        escapeHtml(data.videoUrl),
                        escapeHtml(data.noUrut),
                        buildActions(data.id, data.judul, data.deskripsi, data.videoUrl, data.noUrut, data.file)
                    ];
                    row.data(rowData).draw(false);
                    const node = row.node();
                    if (node) node.id = 'baris-' + data.id;
                }
                return;
            }

            // Selalu gunakan pendekatan DOM langsung jika datatable tidak aktif
            const row = document.getElementById('baris-' + data.id);
            if (!row) {
                console.warn('Row tidak ditemukan: baris-' + data.id);
                return;
            }

            // Update setiap sel secara individual
            const cells = row.querySelectorAll('td');
            if (cells.length >= 6) {
                cells[1].textContent = data.judul;
                cells[2].textContent = data.deskripsi;
                cells[3].textContent = data.file;
                cells[4].textContent = data.videoUrl;
                cells[5].textContent = data.noUrut;
                cells[6].innerHTML = buildActions(data.id, data.judul, data.deskripsi, data.videoUrl, data.noUrut, data.file);
            }
        }

        // --- Event Listener: Tambah Data ---
        if (formMateri) {
            formMateri.addEventListener('submit', function(e) {
                e.preventDefault();
                kirimForm(formMateri, btnTambahMateri);
            });
        }

        // --- Event Listener: Edit Data ---
        if (formEditMateri) {
            formEditMateri.addEventListener('submit', function(e) {
                e.preventDefault();
                kirimForm(formEditMateri, btnEditMateri);
            });
        }

                // --- Event Listener: Buka Modal Edit ---
        if (modalEditMateri) {
            modalEditMateri.addEventListener('show.bs.modal', async function(event) {
                const button = event.relatedTarget;
                if (!button) return;

                const id = button.getAttribute('data-id');
                const judul = button.getAttribute('data-judul') || '';
                const deskripsi = button.getAttribute('data-deskripsi') || '';
                const videoUrl = button.getAttribute('data-video_url') || '';
                const noUrut = button.getAttribute('data-no_urut') || '';
                const fileNama = button.getAttribute('data-file') || '';

                document.getElementById('edit_id').value = id;
                document.getElementById('judul_edit').value = judul;
                document.getElementById('deskripsi_edit').value = deskripsi;
                document.getElementById('video_url_edit').value = videoUrl;
                document.getElementById('no_urut_edit').value = noUrut;
                document.getElementById('file_lama').value = fileNama;
                document.getElementById('file_lama_edit').textContent = fileNama;
                
                // Store file value in form element for later retrieval
                if (formEditMateri) {
                    formEditMateri.setAttribute('data-file-value', fileNama);
                }
            });
        }

        // --- Event Listener: Delegasi Edit/Hapus dari Tabel ---
        if (datatableElement) {
            datatableElement.addEventListener('click', async function(event) {
                // Tangani tombol Edit
                const editButton = event.target.closest('.btn-edit');
                if (editButton) {
                    event.preventDefault();
                    const id = editButton.getAttribute('data-id');
                    if (!id) return;

                    const judul = editButton.getAttribute('data-judul') || '';
                    const deskripsi = editButton.getAttribute('data-deskripsi') || '';
                    const videoUrl = editButton.getAttribute('data-video_url') || '';
                    const noUrut = editButton.getAttribute('data-no_urut') || '';

                    // Isi form edit
                    document.getElementById('edit_id').value = id;
                    document.getElementById('judul_edit').value = judul;
                    document.getElementById('deskripsi_edit').value = deskripsi;
                    document.getElementById('video_url_edit').value = videoUrl;
                    document.getElementById('no_urut_edit').value = noUrut;

                    const editModal = bootstrap.Modal.getOrCreateInstance(modalEditMateri);
                    editModal.show();
                    return;
                }

                // Tangani tombol Hapus - Tampilkan konfirmasi tahap 1
                const deleteButton = event.target.closest('.btn-delete');
                if (!deleteButton) return;

                const id = deleteButton.getAttribute('data-id');
                if (!id) return;

                materiIdToDelete = id;
                materiRowToDelete = document.getElementById('baris-' + id);

                const modalDelete1 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus2'));
                modalDelete1.show();
            });
        }

        // --- Eksekusi Hapus ---
        const btnEksekusiHapus = document.getElementById('btnEksekusiHapus');
        if (btnEksekusiHapus) {
            btnEksekusiHapus.addEventListener('click', async function() {
                if (!materiIdToDelete) return;

                const button = this;
                const originalText = button.innerHTML;
                button.disabled = true;
                button.innerHTML = 'Menghapus...';

                try {
                    // Updated: Use the combined endpoint for deletion
                    const response = await fetch('../../src/actions/proses_materi.php', {
                        method: 'POST', // Using POST since it's more widely supported
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `action=delete&id=${encodeURIComponent(materiIdToDelete)}`
                    });

                    const result = await response.json();

                    // Hide modal
                    const modalDelete1 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus2'));
                    modalDelete1.hide();

                    if (result.status === 'success') {
                        if (materiRowToDelete) {
                            if (dataTable) {
                                dataTable.row(materiRowToDelete).remove().draw(false);
                            } else {
                                materiRowToDelete.remove();
                            }
                            // Renumber all rows after deletion
                            renumberRows();
                        }
                        tampilkanNotif('Berhasil', result.message, 'success');
                    } else {
                        tampilkanNotif('Gagal', result.message, 'error');
                    }
                } catch (error) {
                    tampilkanNotif('Gagal', 'Materi sedang digunakan oleh kuis atau pertanyaan.', 'error');
                    console.error(error);
                } finally {
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            });
        }
    </script>
</body>
</html>