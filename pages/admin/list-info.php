<?php
require_once '../../src/classes/auth.php';
$auth = new auth();
$auth->authOrNot();

require_once '../../src/classes/informasi.php';

$informasi = new Informasi();

$categoryId = (int) ($_GET['id'] ?? 0);

$kategori = $informasi->getKategoriById($categoryId);
$dataContents = $informasi->getContentsByKategori($categoryId);

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
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="informasi.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 fw-bold">
                                        Daftar Informasi
                                    </h4>
                                    <p class="text-muted mb-0">
                                        Lihat daftar Informasi dari kategori
                                        <strong><?= htmlspecialchars($kategori['judul_kategori'] ?? 'Tidak diketahui') ?></strong>.
                                    </p>
                                </div>
                            </div>
                            <div>
                                <a class="btn btn-primary btn-rounded waves-effect mb-2"
                                    href="#"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalTambahInformasi">

                                    <span>
                                        <i class="fas fa-plus"></i>
                                    </span>

                                    Tambah Informasi
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
                                                <th>No.</th>
                                                <th>Judul</th>
                                                <th>Foto</th>
                                                <th>Deskripsi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $i = 1; ?>

                                            <?php foreach ($dataContents as $row) : ?>

                                                <tr id="row-<?= $row['id'] ?>">

                                                    <td>
                                                        <?= $i++ ?>
                                                    </td>

                                                    <td>
                                                        <?= htmlspecialchars($row['judul']) ?>
                                                    </td>

                                                    <td>
                                                        <?php if (!empty($row['foto'])) : ?>

                                                            <img
                                                                src="../../uploads/contents/<?= htmlspecialchars($row['foto']) ?>"
                                                                alt="<?= htmlspecialchars($row['judul']) ?>"
                                                                width="80"
                                                                class="img-thumbnail">

                                                        <?php else : ?>

                                                            <span class="text-muted">
                                                                Tidak ada foto
                                                            </span>

                                                        <?php endif; ?>
                                                    </td>

                                                    <td>
                                                        <?= htmlspecialchars($row['deskripsi']) ?>
                                                    </td>

                                                    <td>

                                                        <a href="#"
                                                            data-id="<?= $row['id'] ?>"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditInformasi"
                                                            class="btn btn-sm btn-warning btn-edit">

                                                            Edit

                                                        </a>

                                                        <button
                                                            type="button"
                                                            data-id="<?= $row['id'] ?>"
                                                            class="btn btn-delete btn-sm btn-danger">

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


            <!-- ========================================================= -->
            <!-- Bagian Pop-up Edit kuis (Modal) -->
            <!-- ========================================================= -->
            <div class="modal fade"
                id="modalTambahInformasi"
                tabindex="-1"
                aria-labelledby="modalTambahInformasiLabel"
                aria-hidden="true">

                <div class="modal-dialog modal-lg">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title" id="modalTambahInformasiLabel">
                                Tambah Informasi
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                            </button>

                        </div>

                        <form id="formInformasi" method="POST" enctype="multipart/form-data">

                            <div class="modal-body">

                                <input
                                    type="hidden"
                                    name="category_id"
                                    value="<?= $categoryId ?>">

                                <div class="mb-3">

                                    <label for="judul" class="form-label">
                                        Judul
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        name="judul"
                                        id="judul"
                                        required>

                                </div>

                                <div class="mb-3">

                                    <label for="foto" class="form-label">
                                        Foto
                                    </label>

                                    <input
                                        type="file"
                                        class="form-control"
                                        name="foto"
                                        id="foto"
                                        accept="image/*">

                                </div>

                                <div class="mb-3">

                                    <label for="deskripsi" class="form-label">
                                        Deskripsi
                                    </label>

                                    <textarea
                                        class="form-control"
                                        name="deskripsi"
                                        id="deskripsi"
                                        rows="6"
                                        required></textarea>

                                </div>

                            </div>

                            <div class="modal-footer">

                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">

                                    Batal

                                </button>

                                <button
                                    type="submit"
                                    id="btnSubmit"
                                    class="btn btn-primary">

                                    Simpan Informasi

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>


            <!-- ========================================================= -->
            <!-- Bagian Pop-up tambah kuis (Modal) -->
            <!-- ========================================================= -->
            <div class="modal fade"
                id="modalEditInformasi"
                tabindex="-1"
                aria-labelledby="modalEditInformasiLabel"
                aria-hidden="true">

                <div class="modal-dialog modal-lg">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title" id="modalEditInformasiLabel">
                                Edit Informasi
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                            </button>

                        </div>

                        <form
                            id="formEditInformasi"
                            method="POST"
                            enctype="multipart/form-data">

                            <div class="modal-body">

                                <input
                                    type="hidden"
                                    name="id"
                                    id="edit_id">

                                <input
                                    type="hidden"
                                    name="category_id"
                                    value="<?= $categoryId ?>">

                                <div class="mb-3">

                                    <label for="edit_judul" class="form-label">
                                        Judul
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        name="judul"
                                        id="edit_judul"
                                        required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Foto Saat Ini
                                    </label>

                                    <div id="edit_foto_preview"></div>

                                </div>

                                <div class="mb-3">

                                    <label for="edit_foto" class="form-label">
                                        Ganti Foto
                                    </label>

                                    <input
                                        type="file"
                                        class="form-control"
                                        name="foto"
                                        id="edit_foto"
                                        accept="image/*">

                                </div>

                                <div class="mb-3">

                                    <label for="edit_deskripsi" class="form-label">
                                        Deskripsi
                                    </label>

                                    <textarea
                                        class="form-control"
                                        name="deskripsi"
                                        id="edit_deskripsi"
                                        rows="6"
                                        required></textarea>

                                </div>

                            </div>

                            <div class="modal-footer">

                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">

                                    Batal

                                </button>

                                <button
                                    type="submit"
                                    id="btnSubmitEdit"
                                    class="btn btn-primary">

                                    Simpan Perubahan

                                </button>

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
        // =========================================================
        // ELEMENT
        // =========================================================

        const formInformasi = document.getElementById('formInformasi');
        const formEditInformasi = document.getElementById('formEditInformasi');

        const modalTambahInformasi = document.getElementById('modalTambahInformasi');
        const modalEditInformasi = document.getElementById('modalEditInformasi');

        const btnSubmit = document.getElementById('btnSubmit');
        const btnSubmitEdit = document.getElementById('btnSubmitEdit');

        const datatableElement = document.getElementById('datatable');

        // ID kategori diambil dari URL
        const urlParams = new URLSearchParams(window.location.search);
        const categoryId = urlParams.get('id') || 0;

        // Endpoint AJAX
        const endpoint = '../../src/actions/proses_content.php';



        const dataTable =
            window.jQuery &&
            window.jQuery.fn &&
            window.jQuery.fn.dataTable &&
            window.jQuery.fn.dataTable.isDataTable('#datatable') ?
            window.jQuery('#datatable').DataTable() :
            null;


        const elemenModalNotif =
            document.getElementById('modalNotifikasi');

        const elemenToastNotif =
            elemenModalNotif ?
            elemenModalNotif.querySelector('.toast') :
            null;

        let modalNotifInstance = null;


        function tampilkanNotif(judul, pesan, status = 'success') {

            if (!elemenToastNotif) {
                return;
            }

            const toastEl = elemenToastNotif;
            const header = toastEl.querySelector('.toast-header');
            const body = toastEl.querySelector('.toast-body');

            // Bersihkan class sebelumnya
            [
                'bg-success',
                'bg-danger',
                'bg-warning'
            ].forEach(className => {

                toastEl.classList.remove(className);

                if (header) {
                    header.classList.remove(className);
                }

                if (body) {
                    body.classList.remove(className);
                }

            });

            const warna =
                status === 'success' ?
                'bg-success' :
                status === 'warning' ?
                'bg-warning' :
                'bg-danger';

            toastEl.classList.add(warna);

            if (header) {
                header.classList.add(warna);
                header.classList.add('text-white');
            }

            if (body) {
                body.classList.add(warna);
                body.classList.add('text-white');
            }

            const judulElement =
                document.getElementById('judulNotifikasi');

            const pesanElement =
                document.getElementById('pesanNotifikasi');

            if (judulElement) {
                judulElement.textContent = judul;
            }

            if (pesanElement) {
                pesanElement.textContent = pesan;
            }

            if (!modalNotifInstance) {

                modalNotifInstance =
                    bootstrap.Toast.getOrCreateInstance(
                        toastEl, {
                            autohide: true,
                            delay: 3000
                        }
                    );

            }

            modalNotifInstance.show();
        }

        function escapeHtml(text) {

            if (text === null || text === undefined) {
                return '';
            }

            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function buildActions(id) {

            return `
            <a
                href="#"
                data-id="${escapeHtml(id)}"
                class="btn btn-sm btn-warning btn-edit"
                data-bs-toggle="modal"
                data-bs-target="#modalEditInformasi"
            >
                Edit
            </a>

            <button
                type="button"
                data-id="${escapeHtml(id)}"
                class="btn btn-delete btn-sm btn-danger"
            >
                Hapus
            </button>
        `;
        }

        function buildFoto(foto, judul = '') {

            if (!foto) {

                return `
                <span class="text-muted">
                    Tidak ada foto
                </span>
            `;

            }

            return `
            <img
                src="../../uploads/contents/${encodeURIComponent(foto)}"
                alt="${escapeHtml(judul)}"
                width="80"
                class="img-thumbnail"
            >
        `;
        }

        function tambahRow(data) {

            if (!data || !data.id) {
                console.error('Data content tidak valid:', data);
                return;
            }

            const rowData = [
                '',
                escapeHtml(data.judul),
                buildFoto(data.foto, data.judul),
                escapeHtml(data.deskripsi),
                buildActions(data.id)
            ];

            if (dataTable) {

                const row =
                    dataTable
                    .row
                    .add(rowData)
                    .draw(false);

                const node = row.node();

                if (node) {
                    node.id = 'row-' + data.id;
                }

            } else {

                const tbody =
                    document.querySelector('#datatable tbody');

                if (!tbody) {
                    return;
                }

                const tr =
                    document.createElement('tr');

                tr.id = 'row-' + data.id;

                tr.innerHTML = `
                <td></td>

                <td>
                    ${escapeHtml(data.judul)}
                </td>

                <td>
                    ${buildFoto(data.foto, data.judul)}
                </td>

                <td>
                    ${escapeHtml(data.deskripsi)}
                </td>

                <td>
                    ${buildActions(data.id)}
                </td>
            `;

                tbody.appendChild(tr);
            }

            updateNomor();
        }


        function updateRow(data) {

            if (!data || !data.id) {
                return;
            }

            const rowElement =
                document.getElementById('row-' + data.id);

            if (!rowElement) {

                console.warn(
                    'Row tidak ditemukan: row-' + data.id
                );

                return;
            }

            const cells =
                rowElement.querySelectorAll('td');

            if (cells.length < 5) {
                return;
            }

            cells[1].textContent =
                data.judul ?? '';

            cells[2].innerHTML =
                buildFoto(data.foto, data.judul);

            cells[3].textContent =
                data.deskripsi ?? '';

            cells[4].innerHTML =
                buildActions(data.id);

        }

        function updateNomor() {

            if (dataTable) {

                dataTable
                    .rows()
                    .every(function(index) {

                        const node = this.node();

                        if (node) {

                            const cell =
                                node.querySelector('td:first-child');

                            if (cell) {
                                cell.textContent = index + 1;
                            }

                        }

                    });

                return;
            }

            const rows =
                document.querySelectorAll(
                    '#datatable tbody tr'
                );

            rows.forEach((row, index) => {

                const cell =
                    row.querySelector('td:first-child');

                if (cell) {
                    cell.textContent = index + 1;
                }

            });

        }


        if (formInformasi) {

            formInformasi.addEventListener(
                'submit',
                async function(event) {

                    event.preventDefault();

                    if (!btnSubmit) {
                        return;
                    }

                    btnSubmit.disabled = true;
                    btnSubmit.innerText = 'Menyimpan...';

                    const formData =
                        new FormData(formInformasi);

                    formData.append('action', 'save');

                    // Pastikan category_id selalu dikirim
                    if (!formData.get('category_id')) {
                        formData.set(
                            'category_id',
                            categoryId
                        );
                    }

                    try {

                        const response =
                            await fetch(
                                endpoint, {
                                    method: 'POST',
                                    body: formData
                                }
                            );

                        const result =
                            await response.json();

                        console.log(
                            'Response tambah:',
                            result
                        );

                        if (result.status === 'success') {

                            tampilkanNotif(
                                'Berhasil',
                                result.message ||
                                'Informasi berhasil ditambahkan.',
                                'success'
                            );

                            formInformasi.reset();

                            // Kembalikan category_id setelah reset
                            const categoryInput =
                                formInformasi.querySelector(
                                    '[name="category_id"]'
                                );

                            if (categoryInput) {
                                categoryInput.value =
                                    categoryId;
                            }

                            const modalInstance =
                                bootstrap.Modal.getInstance(
                                    modalTambahInformasi
                                );

                            if (modalInstance) {
                                modalInstance.hide();
                            }

                            tambahRow({
                                id: result.id,
                                judul: result.judul,
                                foto: result.foto,
                                deskripsi: result.deskripsi
                            });

                        } else {

                            tampilkanNotif(
                                'Gagal',
                                result.message ||
                                'Gagal menambahkan informasi.',
                                'error'
                            );

                        }

                    } catch (error) {

                        console.error(error);

                        tampilkanNotif(
                            'Koneksi Gagal',
                            'Terjadi kesalahan koneksi jaringan.',
                            'error'
                        );

                    } finally {

                        btnSubmit.disabled = false;
                        btnSubmit.innerText =
                            'Simpan Informasi';

                    }

                }
            );

        }


        if (modalEditInformasi) {

            modalEditInformasi.addEventListener(
                'show.bs.modal',
                async function(event) {

                    const button =
                        event.relatedTarget;

                    if (!button) {
                        return;
                    }

                    const id =
                        button.getAttribute('data-id');

                    if (!id) {
                        return;
                    }

                    try {

                        const response =
                            await fetch(
                                `${endpoint}?action=get&id=${encodeURIComponent(id)}`
                            );

                        const result =
                            await response.json();

                        console.log(
                            'Response edit:',
                            result
                        );

                        if (result.status === 'success') {

                            const data =
                                result.data;

                            document.getElementById(
                                'edit_id'
                            ).value = data.id;

                            document.getElementById(
                                'edit_judul'
                            ).value = data.judul || '';

                            document.getElementById(
                                    'edit_deskripsi'
                                ).value =
                                data.deskripsi || '';

                            const preview =
                                document.getElementById(
                                    'edit_foto_preview'
                                );

                            if (preview) {

                                if (data.foto) {

                                    preview.innerHTML =
                                        buildFoto(
                                            data.foto,
                                            data.judul
                                        );

                                } else {

                                    preview.innerHTML =
                                        `<span class="text-muted">
                                        Tidak ada foto
                                    </span>`;

                                }

                            }

                        } else {

                            tampilkanNotif(
                                'Gagal',
                                result.message ||
                                'Data informasi tidak ditemukan.',
                                'error'
                            );

                        }

                    } catch (error) {

                        console.error(error);

                        tampilkanNotif(
                            'Gagal',
                            'Gagal mengambil data informasi.',
                            'error'
                        );

                    }

                }
            );

        }


        if (formEditInformasi) {

            formEditInformasi.addEventListener(
                'submit',
                async function(event) {

                    event.preventDefault();

                    if (!btnSubmitEdit) {
                        return;
                    }

                    btnSubmitEdit.disabled = true;
                    btnSubmitEdit.innerText =
                        'Menyimpan...';

                    const formData =
                        new FormData(formEditInformasi);

                    formData.append('action', 'save');

                    try {

                        const response =
                            await fetch(
                                endpoint, {
                                    method: 'POST',
                                    body: formData
                                }
                            );

                        const result =
                            await response.json();

                        console.log(
                            'Response update:',
                            result
                        );

                        if (result.status === 'success') {

                            tampilkanNotif(
                                'Berhasil',
                                result.message ||
                                'Informasi berhasil diperbarui.',
                                'success'
                            );

                            updateRow({
                                id: result.id,
                                judul: result.judul,
                                foto: result.foto,
                                deskripsi: result.deskripsi
                            });

                            const modalInstance =
                                bootstrap.Modal.getInstance(
                                    modalEditInformasi
                                );

                            if (modalInstance) {
                                modalInstance.hide();
                            }

                        } else {

                            tampilkanNotif(
                                'Gagal',
                                result.message ||
                                'Gagal memperbarui informasi.',
                                'error'
                            );

                        }

                    } catch (error) {

                        console.error(error);

                        tampilkanNotif(
                            'Koneksi Gagal',
                            'Terjadi kesalahan koneksi jaringan.',
                            'error'
                        );

                    } finally {

                        btnSubmitEdit.disabled = false;
                        btnSubmitEdit.innerText =
                            'Simpan Perubahan';

                    }

                }
            );

        }


        let deleteId = null;
        let deleteRowElement = null;


        // Klik tombol Hapus
        if (datatableElement) {

            datatableElement.addEventListener(
                'click',
                function(event) {

                    const deleteButton =
                        event.target.closest('.btn-delete');

                    if (!deleteButton) {
                        return;
                    }

                    const id =
                        deleteButton.getAttribute('data-id');

                    if (!id) {
                        return;
                    }

                    deleteId = id;

                    deleteRowElement =
                        document.getElementById(
                            'row-' + id
                        );

                    const modalDelete1Element =
                        document.getElementById(
                            'modalKonfirmasiHapus1'
                        );

                    if (modalDelete1Element) {

                        const modalDelete1 =
                            bootstrap.Modal.getOrCreateInstance(
                                modalDelete1Element
                            );

                        modalDelete1.show();

                    }

                }
            );

        }

        const btnLanjutkanHapus =
            document.getElementById(
                'btnLanjutkanHapus'
            );

        if (btnLanjutkanHapus) {

            btnLanjutkanHapus.addEventListener(
                'click',
                function() {

                    const modalDelete1Element =
                        document.getElementById(
                            'modalKonfirmasiHapus1'
                        );

                    const modalDelete2Element =
                        document.getElementById(
                            'modalKonfirmasiHapus2'
                        );

                    if (modalDelete1Element) {

                        const modalDelete1 =
                            bootstrap.Modal.getInstance(
                                modalDelete1Element
                            );

                        if (modalDelete1) {
                            modalDelete1.hide();
                        }

                    }

                    if (modalDelete2Element) {

                        const modalDelete2 =
                            bootstrap.Modal.getOrCreateInstance(
                                modalDelete2Element
                            );

                        modalDelete2.show();

                    }

                }
            );

        }

        const btnEksekusiHapus =
            document.getElementById(
                'btnEksekusiHapus'
            );

        if (btnEksekusiHapus) {

            btnEksekusiHapus.addEventListener(
                'click',
                async function() {

                    if (!deleteId) {

                        tampilkanNotif(
                            'Gagal',
                            'ID informasi tidak ditemukan.',
                            'error'
                        );

                        return;
                    }

                    const button = this;

                    const originalText =
                        button.innerHTML;

                    button.disabled = true;
                    button.innerHTML =
                        'Menghapus...';

                    try {

                        const formData =
                            new FormData();

                        formData.append(
                            'action',
                            'delete'
                        );

                        formData.append(
                            'id',
                            deleteId
                        );

                        const response =
                            await fetch(
                                endpoint, {
                                    method: 'POST',
                                    body: formData
                                }
                            );

                        const result =
                            await response.json();

                        console.log(
                            'Response delete:',
                            result
                        );

                        if (result.status === 'success') {

                            if (deleteRowElement) {

                                if (dataTable) {

                                    dataTable
                                        .row(deleteRowElement)
                                        .remove()
                                        .draw(false);

                                } else {

                                    deleteRowElement.remove();

                                }

                            }

                            updateNomor();

                            tampilkanNotif(
                                'Berhasil',
                                result.message ||
                                'Informasi berhasil dihapus.',
                                'success'
                            );

                            deleteId = null;
                            deleteRowElement = null;

                            const modalDelete2Element =
                                document.getElementById(
                                    'modalKonfirmasiHapus2'
                                );

                            if (modalDelete2Element) {

                                const modalDelete2 =
                                    bootstrap.Modal.getInstance(
                                        modalDelete2Element
                                    );

                                if (modalDelete2) {
                                    modalDelete2.hide();
                                }

                            }

                        } else {

                            tampilkanNotif(
                                'Gagal',
                                result.message ||
                                'Gagal menghapus informasi.',
                                'error'
                            );

                        }

                    } catch (error) {

                        console.error(error);

                        tampilkanNotif(
                            'Koneksi Gagal',
                            'Terjadi kesalahan koneksi jaringan.',
                            'error'
                        );

                    } finally {

                        button.disabled = false;
                        button.innerHTML = originalText;

                    }

                }
            );

        }

        if (modalTambahInformasi) {

            modalTambahInformasi.addEventListener(
                'hidden.bs.modal',
                function() {

                    if (formInformasi) {

                        formInformasi.reset();

                        const categoryInput =
                            formInformasi.querySelector(
                                '[name="category_id"]'
                            );

                        if (categoryInput) {
                            categoryInput.value =
                                categoryId;
                        }

                    }

                }
            );

        }


        if (modalEditInformasi) {

            modalEditInformasi.addEventListener(
                'hidden.bs.modal',
                function() {

                    const fotoInput =
                        document.getElementById(
                            'edit_foto'
                        );

                    if (fotoInput) {
                        fotoInput.value = '';
                    }

                }
            );

        }

        document.addEventListener(
            'DOMContentLoaded',
            function() {
                updateNomor();
            }
        );
    </script>
    <!-- end javascript -->

</body>