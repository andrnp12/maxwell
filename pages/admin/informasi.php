<?php
require_once '../../src/classes/auth.php';
$auth = new auth();
$auth->authOrNot();

require_once '../../src/classes/informasi.php';

$informasi = new Informasi();

$dataKategori = $informasi->getAllKategori();

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
                                    Lihat Semua Kategory Informasi
                                </h4>
                                <p class="text-muted">
                                    Kustomisasi kategory informasi sesuai kebutuhan Anda!
                                </p>
                            </div>
                            <div>
                                <a class="btn btn-primary btn-rounded waves-effect mb-2" href="#" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                                    <span>
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    Tambah Kategori
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
                                        Daftar Kategori
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
                                                    Judul Kategori
                                                </th>
                                                <th>
                                                    Aksi
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1;
                                            foreach ($dataKategori as $kategori) : ?>
                                                <tr id="row-<?= $kategori['id'] ?>">
                                                    <td>
                                                        <?= $i++ ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($kategori['judul_kategori']) ?>
                                                    </td>
                                                    <td>
                                                        <a href="#"
                                                            data-id="<?= $kategori['id'] ?>"
                                                            data-judul_kategori="<?= htmlspecialchars($kategori['judul_kategori']) ?>"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditKategori"
                                                            class="btn btn-sm btn-warning btn-edit">Edit</a>
                                                        <button type="button" data-id="<?= $kategori['id'] ?>" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus1">Hapus</button>
                                                        <a href="list-info.php?id=<?= $kategori['id'] ?>" class="btn btn-sm btn-info">Lihat</a>
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
            <!-- Bagian Pop-up Tambah kuis (Modal) -->
            <!-- ========================================================= -->
            <div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahKategoriLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Header Modal -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahKategoriLabel">Form Tambah Kategori</h5>
                            <!-- Tombol silang untuk menutup modal -->
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                        <form id="formKategori" method="POST">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="judul_katergori" class="form-label">Judul Kategori</label>
                                    <input class="form-control" name="judul_kategori" id="judul_katergori" type="text" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" id="btnTambah" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <!-- ========================================================= -->
            <!-- Bagian Pop-up Edit Kategori (Modal) -->
            <!-- ========================================================= -->
            <div class="modal fade" id="modalEditKategori" tabindex="-1" aria-labelledby="modalEditKategoriLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Header Modal -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditKategoriLabel">Form Edit Kategori</h5>
                            <!-- Tombol silang untuk menutup modal -->
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                        <form id="formEditKategori" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit_id">
                                <div class="mb-3">
                                    <label for="judul_kategori" class="form-label">Judul Kategori</label>
                                    <input class="form-control" name="judul_kategori" id="judul_kategori" type="text" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" id="btnEdit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <!-- Toast & Modal Konfirmasi Hapus -->
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

    <script>
        // --- Variabel Global ---
        const formKategori = document.getElementById('formKategori');
        const formEditKategori = document.getElementById('formEditKategori');
        const btnTambah = document.getElementById('btnTambah');
        const btnEdit = document.getElementById('btnEdit');
        const modalEditKategori = document.getElementById('modalEditKategori');
        const elemenModalNotif = document.getElementById('modalNotifikasi');
        const elemenToastNotif = elemenModalNotif ? elemenModalNotif.querySelector('.toast') : null;
        const datatableElement = document.getElementById('datatable');

        const dataTable = window.jQuery && window.jQuery.fn && window.jQuery.fn.dataTable && window.jQuery.fn.dataTable.isDataTable('#datatable') ?
            window.jQuery('#datatable').DataTable() :
            null;

        let modalNotifInstance = null;
        let kategoriIdToDelete = null;
        let kategoriRowToDelete = null;

        // --- Helper ---
        function escapeHtml(text, attr = false) {
            if (text === null || text === undefined) return '';

            let escaped = String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
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

        // --- Kirim Form (Tambah & Edit) ---
        async function kirimForm(formElement, submitButton) {
            if (!formElement || !submitButton) return;

            submitButton.disabled = true;
            submitButton.innerText = 'Memproses...';

            const formData = new FormData(formElement);
            formData.append('action', 'save');

            try {
                const response = await fetch('../../src/actions/proses_info.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    // Ambil nilai dari form sebelum di-reset
                    const id = result.id || formElement.querySelector('[name="id"]')?.value;
                    const judul_kategori = formElement.querySelector('[name="judul_kategori"]')?.value || '';

                    // Tutup Modal
                    if (formElement === formKategori) {
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahKategori')).hide();
                    } else if (formElement === formEditKategori) {
                        bootstrap.Modal.getOrCreateInstance(modalEditKategori).hide();
                    }

                    // Reset Form
                    formElement.reset();

                    // Update atau Tambah row tabel
                    if (formElement === formKategori) {
                        tambahRow({
                            id,
                            judul_kategori,
                        });
                    } else {
                        updateRow({
                            id,
                            judul_kategori,
                        });
                    }

                    tampilkanNotif('Berhasil', result.message, 'success');
                } else {
                    tampilkanNotif('Gagal', result.message, 'error');
                }
            } catch (error) {
                tampilkanNotif('Koneksi Gagal', 'Terjadi kesalahan koneksi jaringan.', 'error');
                console.error(error);
            } finally {
                submitButton.disabled = false;
                submitButton.innerText = formElement === formKategori ? 'Simpan' : 'Simpan Perubahan';
            }
        }

        // --- Render Row ke Tabel ---
        function buildActions(id, judul_kategori) {
            return `
        <a href="#"
           data-id="${id}"
           data-judul_kategori="${escapeHtml(judul_kategori, true)}"
           data-bs-toggle="modal"
           data-bs-target="#modalEditKategori"
           class="btn btn-sm btn-warning btn-edit">
            Edit
        </a>

        <button type="button"
            data-id="${id}"
            class="btn btn-delete btn-sm btn-danger"
            data-bs-toggle="modal"
            data-bs-target="#modalKonfirmasiHapus1">
            Hapus
        </button>

        <a href="list-info.php?id=${encodeURIComponent(id)}"
           class="btn btn-sm btn-info">
            Lihat
        </a>
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
                escapeHtml(data.judul_kategori),
                buildActions(data.id, data.judul_kategori)
            ];

            if (dataTable) {
                const row = dataTable.row.add(rowData).draw(false).node();
                if (row) row.id = 'row-' + data.id;
                return;
            }

            const tbody = datatableElement?.querySelector('tbody');
            if (!tbody) return;

            const tr = document.createElement('tr');
            tr.id = 'row-' + data.id;
            tr.innerHTML = `
    <td>${getRowNumber()}</td>
    <td>${escapeHtml(data.judul_kategori)}</td>
    <td>${buildActions(data.id, data.judul_kategori)}</td>
`;
            tbody.appendChild(tr);
        }

        function updateRow(data) {
            if (dataTable) {
                const row = dataTable.row('#row-' + data.id);
                if (row.length) {
                    const existingData = row.data();
                    const rowNumber = existingData ? existingData[0] : getRowNumber();
                    const rowData = [
                        rowNumber,
                        escapeHtml(data.judul_kategori),
                        buildActions(data.id, data.judul_kategori)
                    ];
                    row.data(rowData).draw(false);
                    const node = row.node();
                    if (node) node.id = 'row-' + data.id;
                }
                return;
            }

            // Selalu gunakan pendekatan DOM langsung jika datatable tidak aktif
            const row = document.getElementById('row-' + data.id);
            if (!row) {
                console.warn('Row tidak ditemukan: row-' + data.id);
                return;
            }

            // Update setiap sel secara individual
            const cells = row.querySelectorAll('td');
            if (cells.length >= 2) {
                cells[1].textContent = data.judul_kategori;
                cells[2].innerHTML = buildActions(data.id, data.judul_kategori);
            }
        }


        // --- Event Listener: Tambah Data ---
        if (formKategori) {
            formKategori.addEventListener('submit', function(e) {
                e.preventDefault();
                kirimForm(formKategori, btnTambah);
            });
        }

        // --- Event Listener: Edit Data ---
        if (formEditKategori) {
            formEditKategori.addEventListener('submit', function(e) {
                e.preventDefault();
                kirimForm(formEditKategori, btnEdit);
            });
        }



        // --- Event Listener: Buka Modal Edit ---
        if (modalEditKategori) {
            modalEditKategori.addEventListener('show.bs.modal', async function(event) {
                const button = event.relatedTarget;
                if (!button) return;

                const id = button.getAttribute('data-id');
                const judul_kategori = button.getAttribute('data-judul_kategori') || '';
                const passing_grade = button.getAttribute('data-passing_grade') || '';
                const id_materi = button.getAttribute('data-id_materi') || '';
                const jenis_kuis = button.getAttribute('data-jenis_kuis') || 'tryout';

                document.getElementById('edit_id').value = id;
                document.getElementById('judul_kategori').value = judul_kategori;
                document.getElementById('passing_grade').value = passing_grade;
                document.getElementById('jenis_kuis').value = jenis_kuis;

                // Load materi options secara dinamis
                const selectMateri = document.getElementById('id_materi');
                const jenisKuisSelect = document.getElementById('jenis_kuis');
                await loadMateriOptions(selectMateri, id, id_materi, jenisKuisSelect);
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

                    const judul_kategori = editButton.getAttribute('data-judul_kategori') || '';

                    // Isi form edit
                    document.getElementById('edit_id').value = id;
                    document.getElementById('judul_kategori').value = judul_kategori;

                    const editModal = bootstrap.Modal.getOrCreateInstance(modalEditKategori);
                    editModal.show();
                    return;
                }


                // Tangani tombol Hapus - Tampilkan konfirmasi tahap 1
                const deleteButton = event.target.closest('.btn-delete');
                if (!deleteButton) return;

                const id = deleteButton.getAttribute('data-id');
                if (!id) return;

                kuisIdToDelete = id;
                kuisRowToDelete = document.getElementById('row-' + id);

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
                if (!kuisIdToDelete) return;

                const button = this;
                const originalText = button.innerHTML;
                button.disabled = true;
                button.innerHTML = 'Menghapus...';

                try {
                    const response = await fetch('../../src/actions/proses_info.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `action=delete&id=${encodeURIComponent(kuisIdToDelete)}`
                    });

                    const result = await response.json();

                    // Hide both modals
                    const modalDelete1 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus1'));
                    const modalDelete2 = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus2'));
                    modalDelete1.hide();
                    modalDelete2.hide();

                    if (result.status === 'success') {
                        if (kuisRowToDelete) {
                            if (dataTable) {
                                dataTable.row(kuisRowToDelete).remove().draw(false);
                            } else {
                                kuisRowToDelete.remove();
                            }
                        }
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
                }
            });
        }
    </script>

</body>

</html>