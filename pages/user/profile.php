<?php
require_once '../../src/classes/profile.php';
require_once '../../src/classes/auth.php';

$auth = new auth();
$auth->authOrNot();

$profile = new Profile();
$dataProfile = $profile->getProfile($_SESSION['id']);
?>

<!--header start-->
<?php include('../include/header.php'); ?>
<!--header end-->

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php include '../include/topbar.php'; ?>
        <!-- ========== Left Sidebar Start ========== -->
        <?php include '../include/sidebar.php'; ?>
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="row d-sm-flex align-items-center justify-content-between mb-2">
                                <h4 class="mb-sm-0 font-weight-bold mb-1">
                                    Akun Saya
                                </h4>
                                <p class="text-muted">
                                    Kelola pengaturan akun dan informasi pribadi Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-xl-9 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm order-2 order-sm-1">
                                            <div class="d-flex align-items-center mt-3 mt-sm-0">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-xl me-3">
                                                        <img alt="" class="img-fluid rounded-circle d-block" src="/uploads/profile/<?= $dataProfile['data']['foto']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div>
                                                        <h5 class="mb-1">
                                                            <?= $dataProfile['data']['name']; ?>
                                                        </h5>
                                                        <p class="text-muted mb-0">
                                                            <?= $dataProfile['data']['email']; ?>
                                                        </p>
                                                        <p class="text-muted mb-0">
                                                            <?= $dataProfile['data']['nomor']; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-auto order-1 order-sm-2">
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                <div>
                                                    <a href="#"
                                                        data-id="<?= $dataProfile['data']['id']; ?>"
                                                        data-username="<?= htmlspecialchars($dataProfile['data']['username'], ENT_QUOTES) ?>"
                                                        data-nama="<?= htmlspecialchars($dataProfile['data']['name'], ENT_QUOTES) ?>"
                                                        data-nomor="<?= htmlspecialchars($dataProfile['data']['nomor'], ENT_QUOTES) ?>"
                                                        data-email="<?= htmlspecialchars($dataProfile['data']['email'], ENT_QUOTES) ?>"
                                                        data-deskripsi="<?= htmlspecialchars($dataProfile['data']['deskripsi'], ENT_QUOTES) ?>"
                                                        data-foto="<?= htmlspecialchars($dataProfile['data']['foto'], ENT_QUOTES) ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditProfile"
                                                        class="mdi mdi-pencil-box-outline text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">Edit</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                            <div class="tab-content">
                                <div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                Tentang Saya
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div>
                                                <div class="pb-3">
                                                    <div class="row">
                                                        <div class="col-xl">
                                                            <div class="text-muted">
                                                                <p class="mb-2">
                                                                    <?= $dataProfile['data']['deskripsi']; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end card body -->
                                    </div>
                                    <!-- end card -->
                                </div>
                                <!-- end tab pane -->
                            </div>
                            <!-- end tab content -->
                        </div>
                        <!-- end col -->
                        <div class="col-xl-3 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        Pengaturan Akun
                                    </h5>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <a href="#"
                                            data-id="<?= $dataProfile['data']['id']; ?>"
                                            data-username="<?= htmlspecialchars($dataProfile['data']['username'], ENT_QUOTES) ?>"
                                            data-nama="<?= htmlspecialchars($dataProfile['data']['name'], ENT_QUOTES) ?>"
                                            data-nomor="<?= htmlspecialchars($dataProfile['data']['nomor'], ENT_QUOTES) ?>"
                                            data-email="<?= htmlspecialchars($dataProfile['data']['email'], ENT_QUOTES) ?>"
                                            data-deskripsi="<?= htmlspecialchars($dataProfile['data']['deskripsi'], ENT_QUOTES) ?>"
                                            data-foto="<?= htmlspecialchars($dataProfile['data']['foto'], ENT_QUOTES) ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditProfile"
                                            class="pb-2 d-block text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                            <i class="mdi mdi-note-text-outline text-primary me-1"></i>
                                            Ubah Profil
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a class="pb-2 d-block text-muted" href="../../src/actions/proses_auth.php?action=logout">
                                            <i class="mdi mdi-note-text-outline text-primary me-1">
                                            </i>
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- container-fluid -->
        </div>
        <div class="modal fade" id="modalEditProfile" tabindex="999" aria-labelledby="modalEditProfileLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Header Modal -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditProfileLabel">Form Edit Profile</h5>
                        <!-- Tombol silang untuk menutup modal -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Isi Pop-up (Form Anda masuk ke sini) -->
                    <form id="formEditProfile">
                        <div class="modal-body">
                            <!-- Contoh Inputan -->
                            <input type="hidden" name="id" id="edit_id" value="">
                            <input type="hidden" name="existing_foto" id="edit_existing_foto" value="">

                            <div class="mb-3">
                                <label class="form-label" for="edit_foto">
                                    Ganti Foto Profile
                                </label>
                                <input class="form-control" id="edit_foto" name="foto" type="file">
                                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="edit_username">
                                    Username
                                </label>
                                <input class="form-control" id="edit_username" name="username" type="text" value="">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="edit_name">
                                    Nama Pengguna
                                </label>
                                <input class="form-control" id="edit_name" name="name" type="text" value="">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="edit_nomor">
                                    Nomor Pengguna
                                </label>
                                <input class="form-control" id="edit_nomor" name="nomor" type="text" value="">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="edit_email">
                                    Email Pengguna
                                </label>
                                <input class="form-control" id="edit_email" name="email" type="text" value="">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="edit_password">
                                    Password Pengguna
                                </label>
                                <input class="form-control" id="edit_password" name="password" type="password" value="">
                                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti password.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="edit_ringkasan">
                                    Ringkasan Singkat Pengguna
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
        <?php include("../include/toast.php"); ?>
        <!-- End Page-content -->
        <?php include '../include/footer.php'; ?>
    </div>
    <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <!-- Right Sidebar -->
    <?php include '../include/right-sidebar.php'; ?>
    <!-- /Right-bar -->
    <!-- Right bar overlay-->
    <div class="rightbar-overlay">
    </div>
    <!-- JAVASCRIPT -->
    <?php include '../include/script.php'; ?>

    <script>
        // Element Selectors
        const formEditProfile = document.getElementById('formEditProfile');
        const btnEdit = document.getElementById('btnEdit');
        const modalEditProfile = document.getElementById('modalEditProfile');
        const elemenModalNotif = document.getElementById('modalNotifikasi');
        const elemenToastNotif = elemenModalNotif ? elemenModalNotif.querySelector('.toast') : null;
        let modalNotifInstance = null;

        // Fungsi Notifikasi (Tetap dipertahankan dari kode asli)
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

        // Populate Modal dengan Data dari Atribut Link Edit
        modalEditProfile.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Link yang diklik
            if (!button) return;

            // Ambil data dari atribut data-xxx
            const id = button.getAttribute('data-id');
            const username = button.getAttribute('data-username') || '';
            const nama = button.getAttribute('data-nama') || '';
            const nomor = button.getAttribute('data-nomor') || '';
            const email = button.getAttribute('data-email') || '';
            const deskripsi = button.getAttribute('data-deskripsi') || '';
            // const password = button.getAttribute('data-password') || '';
            const foto = button.getAttribute('data-foto') || '';

            // Masukkan ke dalam input form
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_existing_foto').value = foto;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_name').value = nama;
            document.getElementById('edit_nomor').value = nomor;
            document.getElementById('edit_email').value = email;
            // document.getElementById('edit_password').value = password;
            document.getElementById('edit_ringkasan').value = deskripsi;
        });

        // Handle Submit Form Edit Profile
        formEditProfile.addEventListener('submit', async function(e) {
            e.preventDefault();

            btnEdit.disabled = true;
            btnEdit.innerText = 'Memproses...';

            const formData = new FormData(formEditProfile);
            formData.append('action', 'update_profile'); // Sesuai dengan logika backend Anda

            try {
                // Sesuaikan path file proses dengan backend Anda (contoh: proses_profile.php)
                const response = await fetch('/../src/actions/proses_profile.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    bootstrap.Modal.getOrCreateInstance(modalEditProfile).hide();
                    tampilkanNotif('Berhasil', result.message, 'success');

                    // Reload halaman untuk melihat perubahan data
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    tampilkanNotif('Gagal', result.message, 'error');
                }
            } catch (error) {
                tampilkanNotif('Koneksi Gagal', 'Terjadi kesalahan koneksi jaringan.', 'error');
                console.error(error);
            } finally {
                btnEdit.disabled = false;
                btnEdit.innerText = 'Simpan Perubahan';
            }
        });
    </script>
</body>

</html>