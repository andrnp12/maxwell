<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../classes/konselor.php';

$konsultan = new Konsultan();
$dataKonsultan = $konsultan->getAllKonsultan();
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
                                    Lihat Semua Konselor
                                </h4>
                                <p class="text-muted">
                                    Kelola konselor Anda.
                                </p>
                            </div>
                            <div>
                                <a class="btn btn-primary btn-rounded waves-effect mb-2" href="#" data-bs-toggle="modal" data-bs-target="#modalTambahKonselor">
                                    <span>
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    Tambah Konselor
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
                                        Daftar Konselor
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered dt-responsive nowrap w-100 align-middle" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Foto</th>
                                                <th>Nama Konsultan</th>
                                                <th>Nomor WA</th>
                                                <th>Email</th>
                                                <th>Ringkasan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($dataKonsultan as $konsultan) : ?>
                                                <tr id="row-<?= $konsultan['id'] ?>">
                                                    <td><?= $no++ ?></td>
                                                    <td><img src="../uploads/profile/<?= htmlspecialchars($konsultan['foto']) ?>" alt="icon" class="avatar-sm rounded-circle" /></td>
                                                    <td><?= htmlspecialchars($konsultan['nama']) ?></td>
                                                    <td><?= htmlspecialchars($konsultan['nomor']) ?></td>
                                                    <td><?= htmlspecialchars($konsultan['email']) ?></td>
                                                    <td><?= htmlspecialchars($konsultan['deskripsi']) ?></td>
                                                    <td>
                                                        <a href="#"
                                                            data-id="<?= $konsultan['id'] ?>"
                                                            data-nama="<?= htmlspecialchars($konsultan['nama'], ENT_QUOTES) ?>"
                                                            data-nomor="<?= htmlspecialchars($konsultan['nomor'], ENT_QUOTES) ?>"
                                                            data-email="<?= htmlspecialchars($konsultan['email'], ENT_QUOTES) ?>"
                                                            data-deskripsi="<?= htmlspecialchars($konsultan['deskripsi'], ENT_QUOTES) ?>"
                                                            data-foto="<?= htmlspecialchars($konsultan['foto'], ENT_QUOTES) ?>"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditKonselor"
                                                            class="btn btn-sm btn-warning btn-edit">Edit</a>
                                                        <button type="button" data-id="<?= $konsultan['id'] ?>" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus">Hapus</button>
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
            <!-- Bagian Pop-up tambah konselor (Modal) -->
            <!-- ========================================================= -->
            <div class="modal fade" id="modalTambahKonselor" tabindex="-1" aria-labelledby="modalTambahKonselorLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Header Modal -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahKonselorLabel">Form Tambah Konselor</h5>
                            <!-- Tombol silang untuk menutup modal -->
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                        <form id="formKonselor">
                            <div class="modal-body">
                                <!-- Contoh Inputan -->

                                <div class="mb-3">
                                    <label class="form-label" for="foto">
                                        Foto Konselor
                                    </label>
                                    <input class="form-control" id="foto" name="foto" placeholder="Ganti Foto Profil" type="file" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="nama">
                                        Nama Konselor
                                    </label>
                                    <input class="form-control" id="nama" name="nama" placeholder="Masukkan nama" type="text" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="nomor">
                                        Nomor Whatsapp Konselor
                                    </label>
                                    <input class="form-control" id="nomor" name="nomor" placeholder="Masukkan link" type="text" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="email">
                                        Email Konselor
                                    </label>
                                    <input class="form-control" id="email" name="email" placeholder="Masukkan email" type="text" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="ringkasan">
                                        Ringkasan Singkat Konselor
                                    </label>
                                    <textarea class="form-control" id="ringkasan" name="ringkasan" placeholder="Ringkasan..." rows="3" required></textarea>
                                </div>
                            </div>

                            <!-- Footer Modal (Tombol Aksi) -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" id="btnTambah">Simpan Konselor</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <!-- ========================================================= -->
            <!-- Bagian Pop-up edit konselor (Modal) -->
            <!-- ========================================================= -->
            <div class="modal fade" id="modalEditKonselor" tabindex="-1" aria-labelledby="modalEditKonselorLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Header Modal -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahKonselorLabel">Form Edit Konselor</h5>
                            <!-- Tombol silang untuk menutup modal -->
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                        <form id="formEditKonselor">
                            <div class="modal-body">
                                <!-- Contoh Inputan -->
                                <input type="hidden" name="id" id="edit_id" value="">
                                <input type="hidden" name="existing_foto" id="edit_existing_foto" value="">

                                <div class="mb-3">
                                    <label class="form-label" for="edit_foto">
                                        Ganti Foto Konselor
                                    </label>
                                    <input class="form-control" id="edit_foto" name="foto" type="file">
                                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="edit_nama">
                                        Nama Konselor
                                    </label>
                                    <input class="form-control" id="edit_nama" name="nama" type="text" value="">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="edit_nomor">
                                        Nomor Whatsapp Konselor
                                    </label>
                                    <input class="form-control" id="edit_nomor" name="nomor" type="text" value="">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="edit_email">
                                        Email Konselor
                                    </label>
                                    <input class="form-control" id="edit_email" name="email" type="text" value="">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="edit_ringkasan">
                                        Ringkasan Singkat Konselor
                                    </label>
                                    <textarea class="form-control" id="edit_ringkasan" name="ringkasan" rows="3"></textarea>
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
        const formKonselor = document.getElementById('formKonselor');
        const btnTambah = document.getElementById('btnTambah');
        const formEditKonselor = document.getElementById('formEditKonselor');
        const btnEdit = document.getElementById('btnEdit');
        const modalEditKonselor = document.getElementById('modalEditKonselor');
        const elemenModalNotif = document.getElementById('modalNotifikasi');
        const elemenToastNotif = elemenModalNotif ? elemenModalNotif.querySelector('.toast') : null;
        const dataTable = window.jQuery && window.jQuery.fn && window.jQuery.fn.dataTable && window.jQuery.fn.dataTable.isDataTable('#datatable') ?
            window.jQuery('#datatable').DataTable() :
            null;
        let modalNotifInstance = null;

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
            // document.getElementById('toastBody').textContent = pesan;

            if (!modalNotifInstance) {
                modalNotifInstance = bootstrap.Toast.getOrCreateInstance(toastEl, {
                    autohide: true,
                    delay: 3000
                });
            }

            modalNotifInstance.show();
        }

        async function kirimForm(formElement, submitButton, successMessage) {
            if (!formElement || !submitButton) return;

            submitButton.disabled = true;
            submitButton.innerText = 'Memproses...';

            const formData = new FormData(formElement);
            formData.append('action', 'save');

            try {
                const response = await fetch('/../actions/proses_konselor.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {

                    // 1. TUTUP MODAL DULU
                    if (formElement === formKonselor) {
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahKonselor')).hide();
                    } else if (formElement === formEditKonselor) {
                        bootstrap.Modal.getOrCreateInstance(modalEditKonselor).hide();
                    }

                    // 2. RESET FORM
                    if (formElement === formKonselor) {
                        const id = result.id;
                        const foto = result.foto ? result.foto : '';
                        const nomor = formElement.querySelector('[name=nomor]').value;
                        const nama = formElement.querySelector('[name=nama]').value;
                        const email = formElement.querySelector('[name=email]').value;
                        const deskripsi = formElement.querySelector('[name=ringkasan]').value;

                        formKonselor.reset();
                        const tambahModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahKonselor'));
                        tambahModal.hide();

                        tambahRow({
                            id,
                            foto,
                            nama,
                            nomor,
                            email,
                            deskripsi
                        });
                    }

                    if (formElement === formEditKonselor) {
                        const editModal = bootstrap.Modal.getOrCreateInstance(modalEditKonselor);
                        editModal.hide();

                        const id = document.getElementById('edit_id').value;
                        const foto = result.foto ? result.foto : document.getElementById('edit_existing_foto').value;
                        const nomor = formElement.querySelector('[name=nomor]').value;
                        const nama = formElement.querySelector('[name=nama]').value;
                        const email = formElement.querySelector('[name=email]').value;
                        const deskripsi = formElement.querySelector('[name=ringkasan]').value;

                        updateRow({
                            id,
                            foto,
                            nama,
                            nomor,
                            email,
                            deskripsi
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
                submitButton.innerText = formElement === formKonselor ? 'Simpan Konselor' : 'Simpan Kuis';
            }
        }

        function tambahRow(data) {
            const rowData = [
                data.id,
                `<img src="../uploads/profile/${data.foto}" alt="icon" class="avatar-sm rounded-circle" />`,
                escapeHtml(data.nama),
                escapeHtml(data.nomor),
                escapeHtml(data.email),
                escapeHtml(data.deskripsi),
                `<a href="#" \
                       data-id="${data.id}" \
                       data-nama="${escapeHtml(data.nama, true)}" \
                       data-nomor="${escapeHtml(data.nomor, true)}" \
                       data-email="${escapeHtml(data.email, true)}" \
                       data-deskripsi="${escapeHtml(data.deskripsi, true)}" \
                       data-foto="${escapeHtml(data.foto, true)}" \
                       data-bs-toggle="modal" \
                       data-bs-target="#modalEditKonselor" \
                       class="btn btn-sm btn-warning btn-edit">Edit</a>\
                    <button type="button" data-id="${data.id}" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus">Hapus</button>`
            ];

            if (dataTable) {
                const row = dataTable.row.add(rowData).draw(false).node();
                if (row) row.id = 'row-' + data.id;
                return;
            }

            const table = document.getElementById('datatable');
            if (!table) return;
            const tbody = table.querySelector('tbody');
            if (!tbody) return;

            const tr = document.createElement('tr');
            tr.id = 'row-' + data.id;
            tr.innerHTML = `
                <td>${data.id}</td>
                <td><img src="../uploads/profile/${data.foto}" alt="icon" class="avatar-sm rounded-circle" /></td>
                <td>${escapeHtml(data.nama)}</td>
                <td>${escapeHtml(data.nomor)}</td>
                <td>${escapeHtml(data.email)}</td>
                <td>${escapeHtml(data.deskripsi)}</td>
                <td>
                    <a href="#" 
                       data-id="${data.id}"
                       data-nama="${escapeHtml(data.nama, true)}"
                       data-nomor="${escapeHtml(data.nomor, true)}"
                       data-email="${escapeHtml(data.email, true)}"
                       data-deskripsi="${escapeHtml(data.deskripsi, true)}"
                       data-foto="${escapeHtml(data.foto, true)}"
                       data-bs-toggle="modal"
                       data-bs-target="#modalEditKonselor"
                       class="btn btn-sm btn-warning btn-edit">Edit</a>
                    <button type="button" data-id="${data.id}" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus">Hapus</button>
                </td>`;
            tbody.appendChild(tr);
        }

        function updateRow(data) {
            const rowData = [
                data.id,
                `<img src="../uploads/profile/${data.foto}" alt="icon" class="avatar-sm rounded-circle" />`,
                escapeHtml(data.nama),
                escapeHtml(data.nomor),
                escapeHtml(data.email),
                escapeHtml(data.deskripsi),
                `<a href="#" \
                       data-id="${data.id}" \
                       data-nama="${escapeHtml(data.nama, true)}" \
                       data-nomor="${escapeHtml(data.nomor, true)}" \
                       data-email="${escapeHtml(data.email, true)}" \
                       data-deskripsi="${escapeHtml(data.deskripsi, true)}" \
                       data-foto="${escapeHtml(data.foto, true)}" \
                       data-bs-toggle="modal" \
                       data-bs-target="#modalEditKonselor" \
                       class="btn btn-sm btn-warning btn-edit">Edit</a>\
                    <button type="button" data-id="${data.id}" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus">Hapus</button>`
            ];

            if (dataTable) {
                const row = dataTable.row('#row-' + data.id);
                if (row.length) {
                    row.data(rowData).draw(false);
                    const node = row.node();
                    if (node) node.id = 'row-' + data.id;
                }
                return;
            }

            const row = document.getElementById('row-' + data.id);
            if (!row) return;
            row.innerHTML = `
                <td>${data.id}</td>
                <td><img src="../uploads/profile/${data.foto}" alt="icon" class="avatar-sm rounded-circle" /></td>
                <td>${escapeHtml(data.nama)}</td>
                <td>${escapeHtml(data.nomor)}</td>
                <td>${escapeHtml(data.email)}</td>
                <td>${escapeHtml(data.deskripsi)}</td>
                <td>
                    <a href="#" 
                       data-id="${data.id}"
                       data-nama="${escapeHtml(data.nama, true)}"
                       data-nomor="${escapeHtml(data.nomor, true)}"
                       data-email="${escapeHtml(data.email, true)}"
                       data-deskripsi="${escapeHtml(data.deskripsi, true)}"
                       data-foto="${escapeHtml(data.foto, true)}"
                       data-bs-toggle="modal"
                       data-bs-target="#modalEditKonselor"
                       class="btn btn-sm btn-warning btn-edit">Edit</a>
                    <button type="button" data-id="${data.id}" class="btn btn-delete btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus">Hapus</button>
                </td>`;
        }

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

        formKonselor.addEventListener('submit', function(e) {
            e.preventDefault();
            kirimForm(formKonselor, btnTambah, 'Data berhasil ditambahkan');
        });

        if (formEditKonselor) {
            formEditKonselor.addEventListener('submit', function(e) {
                e.preventDefault();
                kirimForm(formEditKonselor, btnEdit, 'Data berhasil diperbarui');
            });
        }

        if (modalEditKonselor) {
            modalEditKonselor.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                if (!button) return;

                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama') || '';
                const nomor = button.getAttribute('data-nomor') || '';
                const email = button.getAttribute('data-email') || '';
                const deskripsi = button.getAttribute('data-deskripsi') || '';
                const foto = button.getAttribute('data-foto') || '';

                document.getElementById('edit_id').value = id;
                document.getElementById('edit_existing_foto').value = foto;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_nomor').value = nomor;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_ringkasan').value = deskripsi;
            });
        }

        let konselorIdToDelete = null;
        let konselorRowToDelete = null;

        const datatableElement = document.getElementById('datatable');
        if (datatableElement) {
            datatableElement.addEventListener('click', function(event) {
                const editButton = event.target.closest('.btn-edit');
                if (editButton) {
                    event.preventDefault();

                    const id = editButton.getAttribute('data-id');
                    if (!id) return;

                    const nama = editButton.getAttribute('data-nama') || '';
                    const nomor = editButton.getAttribute('data-nomor') || '';
                    const email = editButton.getAttribute('data-email') || '';
                    const deskripsi = editButton.getAttribute('data-deskripsi') || '';
                    const foto = editButton.getAttribute('data-foto') || '';

                    document.getElementById('edit_id').value = id;
                    document.getElementById('edit_existing_foto').value = foto;
                    document.getElementById('edit_nama').value = nama;
                    document.getElementById('edit_nomor').value = nomor;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_ringkasan').value = deskripsi;

                    const editModal = bootstrap.Modal.getOrCreateInstance(modalEditKonselor);
                    editModal.show();
                    return;
                }

                const deleteButton = event.target.closest('.btn-delete');
                if (!deleteButton) return;

                const id = deleteButton.getAttribute('data-id');
                if (!id) return;

                konselorIdToDelete = id;
                konselorRowToDelete = document.getElementById('row-' + id);

                const modalDelete = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus'));
                modalDelete.show();
            });
        }

        document.getElementById('btnEksekusiHapus').addEventListener('click', async function() {
            if (!konselorIdToDelete) return;

            const button = this;
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = 'Menghapus...';

            try {
                const response = await fetch('/../actions/proses_konselor.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `action=delete&id=${encodeURIComponent(konselorIdToDelete)}`
                });

                const result = await response.json();

                const modalDelete = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasiHapus'));
                modalDelete.hide();

                if (result.status === 'success') {
                    if (konselorRowToDelete) {
                        if (dataTable) {
                            dataTable.row(konselorRowToDelete).remove().draw(false);
                        } else {
                            konselorRowToDelete.remove();
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
    </script>

</body>