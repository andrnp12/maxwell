<?php
require_once '../../src/classes/auth.php';
$auth = new auth();
$auth->authOrNot();

require_once '../../src/classes/kuis.php';
require_once '../../src/classes/materi.php';

$kuis = new Kuis();
$materi = new Materi();

$dataKuis = $kuis->getAllKuis();
$dataMateri = $materi->getMateriNonKuis();
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
                                    <table class="table table-bordered dt-responsive w-100" id="datatable">
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
                                                    Jenis Kuis
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
                                            <?php $i = 1;
                                            foreach ($dataKuis as $kuis) : ?>
                                                <tr id="row-<?= $kuis['id_kuis'] ?>">
                                                    <td>
                                                        <?= $i++ ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($kuis['judul_kuis']) ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($kuis['judul_materi'] ?? '-') ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($kuis['jenis'] ?? 'kuis') ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($kuis['passing_grade']) ?>
                                                    </td>
                                                    <td>
                                                        <a href="#"
                                                            data-id="<?= $kuis['id_kuis'] ?>"
                                                            data-id_materi="<?= $kuis['material_id'] ?>"
                                                            data-material_title="<?= htmlspecialchars($kuis['judul_materi']) ?>"
                                                            data-judul_kuis="<?= htmlspecialchars($kuis['judul_kuis']) ?>"
                                                            data-passing_grade="<?= htmlspecialchars($kuis['passing_grade']) ?>"
                                                            data-jenis_kuis="<?= htmlspecialchars($kuis['jenis'] ?? 'kuis') ?>"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditKuis"
                                                            class="btn btn-sm btn-warning btn-edit">Edit</a>
                                                        <button type="button" data-id="<?= $kuis['id_kuis'] ?>" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus1">Hapus</button>
                                                        <a href="list-kuis.php?id=<?= $kuis['id_kuis'] ?>" class="btn btn-sm btn-info">Lihat</a>
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
                        <form id="formKuis" method="POST">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="id_materi_tambah" class="form-label">Pilih Materi</label>
                                    <select id="id_materi_tambah" name="id_materi" class="form-select">
                                        <option value="" disabled selected>-- Pilih Materi --</option>
                                        <?php foreach ($dataMateri as $materi) : ?>
                                            <option value="<?= $materi['id'] ?>">
                                                <?= htmlspecialchars($materi['judul']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="judul_kuis_tambah" class="form-label">Judul Kuis</label>
                                    <input class="form-control" name="judul_kuis" id="judul_kuis_tambah" type="text" required>
                                </div>
                                <div class="mb-3">
                                    <label for="passing_grade_tambah" class="form-label">Passing Grade</label>
                                    <input class="form-control" name="passing_grade" id="passing_grade_tambah" type="number" min="0" max="100" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jenis_kuis_tambah" class="form-label">Jenis Kuis</label>
                                    <select id="jenis_kuis_tambah" name="jenis_kuis" class="form-select" required>
                                        <option value="kuis" selected>Kuis</option>
                                        <option value="pre">Pretest</option>
                                        <option value="post">Posttest</option>
                                    </select>
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
                                <input type="hidden" name="id" id="edit_id">
                                <div class="mb-3">
                                    <label for="id_materi" class="form-label">Pilih Materi</label>
                                    <select id="id_materi" name="id_materi" class="form-select">
                                        <option value="" disabled>-- Pilih Materi --</option>
                                        <?php foreach ($dataMateri as $materi) : ?>
                                            <option value="<?= $materi['id'] ?>">
                                                <?= htmlspecialchars($materi['judul']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="judul_kuis" class="form-label">Judul Kuis</label>
                                    <input class="form-control" name="judul_kuis" id="judul_kuis" type="text" required>
                                </div>
                                <div class="mb-3">
                                    <label for="passing_grade" class="form-label">Passing Grade</label>
                                    <input class="form-control" name="passing_grade" id="passing_grade" type="number" min="0" max="100" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jenis_kuis" class="form-label">Jenis Kuis</label>
                                    <select id="jenis_kuis" name="jenis_kuis" class="form-select" required>
                                        <option value="kuis">Kuis</option>
                                        <option value="pre">Pretest</option>
                                        <option value="post">Posttest</option>
                                    </select>
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
        const formKuis = document.getElementById('formKuis');
        const formEditKuis = document.getElementById('formEditKuis');
        const btnTambah = document.getElementById('btnTambah');
        const btnEdit = document.getElementById('btnEdit');
        const modalEditKuis = document.getElementById('modalEditKuis');
        const elemenModalNotif = document.getElementById('modalNotifikasi');
        const elemenToastNotif = elemenModalNotif ? elemenModalNotif.querySelector('.toast') : null;
        const datatableElement = document.getElementById('datatable');

        const dataTable = window.jQuery && window.jQuery.fn && window.jQuery.fn.dataTable && window.jQuery.fn.dataTable.isDataTable('#datatable') ?
            window.jQuery('#datatable').DataTable() :
            null;

        let modalNotifInstance = null;
        let kuisIdToDelete = null;
        let kuisRowToDelete = null;

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

        // --- Kirim Form (Tambah & Edit) ---
        async function kirimForm(formElement, submitButton) {
            if (!formElement || !submitButton) return;

            submitButton.disabled = true;
            submitButton.innerText = 'Memproses...';

            const formData = new FormData(formElement);
            formData.append('action', 'save');

            try {
                const response = await fetch('../../src/actions/proses_kuis.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    // Ambil nilai dari form sebelum di-reset
                    const id = result.id || formElement.querySelector('[name="id"]')?.value;
                    const judul_kuis = formElement.querySelector('[name="judul_kuis"]')?.value || '';
                    const passing_grade = formElement.querySelector('[name="passing_grade"]')?.value || '';
                    const id_materi = formElement.querySelector('[name="id_materi"]')?.value || '';
                    const jenis_kuis = formElement.querySelector('[name="jenis_kuis"]')?.value || 'tryout';

                    // Ambil material_title dari response server, atau dari text option dropdown sebelum di-reset
                    let material_title = result.material_title || '';
                    if (!material_title && id_materi) {
                        const selectMateri = formElement.querySelector('[name="id_materi"]');
                        if (selectMateri) {
                            const selectedOption = selectMateri.options[selectMateri.selectedIndex];
                            material_title = selectedOption ? selectedOption.text : 'Materi Terpilih';
                        }
                    }
                    if (!material_title) material_title = 'Materi Terpilih';

                    // Tutup Modal
                    if (formElement === formKuis) {
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahKuis')).hide();
                    } else if (formElement === formEditKuis) {
                        bootstrap.Modal.getOrCreateInstance(modalEditKuis).hide();
                    }

                    // Reset Form
                    formElement.reset();

                    // Update atau Tambah row tabel
                    if (formElement === formKuis) {
                        tambahRow({
                            id,
                            judul_kuis,
                            passing_grade,
                            id_materi,
                            material_title,
                            jenis_kuis
                        });
                    } else {
                        updateRow({
                            id,
                            judul_kuis,
                            passing_grade,
                            id_materi,
                            material_title,
                            jenis_kuis
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
                submitButton.innerText = formElement === formKuis ? 'Simpan' : 'Simpan Perubahan';
            }
        }

        // --- Render Row ke Tabel ---
        function buildActions(id, judul_kuis, passing_grade, id_materi, material_title, jenis_kuis) {
            return `
                <a href="#"
                   data-id="${id}"
                   data-id_materi="${escapeHtml(id_materi, true)}"
                   data-material_title="${escapeHtml(material_title, true)}"
                   data-judul_kuis="${escapeHtml(judul_kuis, true)}"
                   data-passing_grade="${escapeHtml(passing_grade, true)}"
                   data-jenis_kuis="${escapeHtml(jenis_kuis, true)}"
                   data-bs-toggle="modal"
                   data-bs-target="#modalEditKuis"
                   class="btn btn-sm btn-warning btn-edit">Edit</a>
                <button type="button" data-id="${id}" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus">Hapus</button>
                <a href="list-kuis.php?id=${encodeURIComponent(id)}" class="btn btn-sm btn-info">Lihat</a>
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
                escapeHtml(data.judul_kuis),
                escapeHtml(data.material_title),
                escapeHtml(data.jenis_kuis || 'tryout'),
                escapeHtml(data.passing_grade),
                buildActions(data.id, data.judul_kuis, data.passing_grade, data.id_materi, data.material_title, data.jenis_kuis || 'tryout')
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
                <td>${escapeHtml(data.judul_kuis)}</td>
                <td>${escapeHtml(data.material_title)}</td>
                <td>${escapeHtml(data.jenis_kuis || 'tryout')}</td>
                <td>${escapeHtml(data.passing_grade)}</td>
                <td>${buildActions(data.id, data.judul_kuis, data.passing_grade, data.id_materi, data.material_title, data.jenis_kuis || 'tryout')}</td>
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
                        escapeHtml(data.judul_kuis),
                        escapeHtml(data.material_title),
                        escapeHtml(data.jenis_kuis || 'tryout'),
                        escapeHtml(data.passing_grade),
                        buildActions(data.id, data.judul_kuis, data.passing_grade, data.id_materi, data.material_title, data.jenis_kuis || 'tryout')
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
            if (cells.length >= 5) {
                cells[1].textContent = data.judul_kuis;
                cells[2].textContent = data.material_title;
                cells[3].textContent = data.jenis_kuis || 'tryout';
                cells[4].textContent = data.passing_grade;
                cells[5].innerHTML = buildActions(data.id, data.judul_kuis, data.passing_grade, data.id_materi, data.material_title, data.jenis_kuis || 'tryout');
            }
        }


        // --- Event Listener: Tambah Data ---
        if (formKuis) {
            formKuis.addEventListener('submit', function(e) {
                e.preventDefault();
                kirimForm(formKuis, btnTambah);
            });
        }

        // --- Event Listener: Edit Data ---
        if (formEditKuis) {
            formEditKuis.addEventListener('submit', function(e) {
                e.preventDefault();
                kirimForm(formEditKuis, btnEdit);
            });
        }

        // --- Fungsi untuk toggle required pada materi berdasarkan jenis kuis ---
        function toggleMateriRequired(jenisKuisSelect, materiSelect) {
            const jenisKuis = jenisKuisSelect.value;
            const isOptional = ['pre', 'post'].includes(jenisKuis);

            if (isOptional) {
                materiSelect.removeAttribute('required');
                // Add empty option for optional selection
                if (!materiSelect.querySelector('option[value=""]')) {
                    const emptyOption = new Option('-- Pilih Materi (Opsional) --', '');
                    emptyOption.disabled = false;
                    materiSelect.insertBefore(emptyOption, materiSelect.firstChild);
                }
            } else {
                materiSelect.setAttribute('required', 'required');
                // Remove empty option if exists
                const emptyOption = materiSelect.querySelector('option[value=""]');
                if (emptyOption) {
                    emptyOption.remove();
                }
            }
        }

        // --- Fungsi Load Materi Options ---
        async function loadMateriOptions(selectElement, currentId, selectedMaterialId, jenisKuisSelect) {
            if (!selectElement) return;

            // Tampilkan loading
            selectElement.disabled = true;
            selectElement.innerHTML = '<option value="" disabled>Memuat...</option>';

            try {
                const formData = new FormData();
                formData.append('action', 'get_materi');
                formData.append('current_id', currentId || '');

                const response = await fetch('../../src/actions/proses_kuis.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    selectElement.innerHTML = '';

                    // Check if materi is optional based on jenis kuis
                    const jenisKuis = jenisKuisSelect ? jenisKuisSelect.value : 'tryout';
                    const isOptional = ['pre', 'post'].includes(jenisKuis);

                    if (isOptional) {
                        selectElement.innerHTML = '<option value="" disabled>-- Pilih Materi (Opsional) --</option>';
                    } else {
                        selectElement.innerHTML = '<option value="" disabled selected>-- Pilih Materi --</option>';
                    }

                    result.data.forEach(materi => {
                        const option = new Option(materi.judul, materi.id);
                        selectElement.add(option);
                    });

                    // Pilih materi yang sedang digunakan
                    if (selectedMaterialId) {
                        selectElement.value = selectedMaterialId;
                    }

                    // Update required attribute
                    if (jenisKuisSelect) {
                        toggleMateriRequired(jenisKuisSelect, selectElement);
                    }
                } else {
                    selectElement.innerHTML = '<option value="" disabled>Gagal memuat materi</option>';
                }
            } catch (error) {
                console.error('Error loading materi:', error);
                selectElement.innerHTML = '<option value="" disabled>Error memuat data</option>';
            } finally {
                selectElement.disabled = false;
            }
        }

        // --- Event Listener: Buka Modal Edit ---
        if (modalEditKuis) {
            modalEditKuis.addEventListener('show.bs.modal', async function(event) {
                const button = event.relatedTarget;
                if (!button) return;

                const id = button.getAttribute('data-id');
                const judul_kuis = button.getAttribute('data-judul_kuis') || '';
                const passing_grade = button.getAttribute('data-passing_grade') || '';
                const id_materi = button.getAttribute('data-id_materi') || '';
                const jenis_kuis = button.getAttribute('data-jenis_kuis') || 'tryout';

                document.getElementById('edit_id').value = id;
                document.getElementById('judul_kuis').value = judul_kuis;
                document.getElementById('passing_grade').value = passing_grade;
                document.getElementById('jenis_kuis').value = jenis_kuis;

                // Load materi options secara dinamis
                const selectMateri = document.getElementById('id_materi');
                const jenisKuisSelect = document.getElementById('jenis_kuis');
                await loadMateriOptions(selectMateri, id, id_materi, jenisKuisSelect);
            });
        }

        // --- Event Listener: Jenis Kuis Change (Tambah) ---
        const jenisKuisTambah = document.getElementById('jenis_kuis_tambah');
        const materiTambah = document.getElementById('id_materi_tambah');
        if (jenisKuisTambah && materiTambah) {
            // Initial check on load
            toggleMateriRequired(jenisKuisTambah, materiTambah);
            jenisKuisTambah.addEventListener('change', function() {
                toggleMateriRequired(this, materiTambah);
            });
        }

        // --- Event Listener: Jenis Kuis Change (Edit) ---
        const jenisKuisEdit = document.getElementById('jenis_kuis');
        const materiEdit = document.getElementById('id_materi');
        if (jenisKuisEdit && materiEdit) {
            jenisKuisEdit.addEventListener('change', function() {
                toggleMateriRequired(this, materiEdit);
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

                    const judul_kuis = editButton.getAttribute('data-judul_kuis') || '';
                    const passing_grade = editButton.getAttribute('data-passing_grade') || '';
                    const id_materi = editButton.getAttribute('data-id_materi') || '';
                    const jenis_kuis = editButton.getAttribute('data-jenis_kuis') || 'tryout';

                    // Isi form edit
                    document.getElementById('edit_id').value = id;
                    document.getElementById('judul_kuis').value = judul_kuis;
                    document.getElementById('passing_grade').value = passing_grade;
                    document.getElementById('jenis_kuis').value = jenis_kuis;

                    // Load materi options secara dinamis
                    const selectMateri = document.getElementById('id_materi');
                    const jenisKuisSelect = document.getElementById('jenis_kuis');
                    await loadMateriOptions(selectMateri, id, id_materi, jenisKuisSelect);

                    const editModal = bootstrap.Modal.getOrCreateInstance(modalEditKuis);
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
                    const response = await fetch('../../src/actions/proses_kuis.php', {
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