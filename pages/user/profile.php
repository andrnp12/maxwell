<?php
require_once '../../src/classes/profile.php';
require_once '../../src/classes/auth.php';
require_once '../../src/classes/user.php';

$auth = new auth();
$auth->authOrNot();

$userId = $_SESSION['id'];

// Handle certificate download action
if (isset($_GET['action']) && $_GET['action'] === 'download_certificate') {
    header('Content-Type: application/json');

    $userModel = new User();
    $eligibility = $userModel->canDownloadCertificate($userId);

    if (!$eligibility['eligible']) {
        http_response_code(403);
        echo json_encode([
            'status' => 'locked',
            'message' => 'Progress requirement not met',
            'progress_percent' => $eligibility['progress_percent'],
            'missing_requirements' => $eligibility['missing_requirements']
        ]);
        exit;
    }

    // Generate PDF certificate
    try {
        require_once '../../src/classes/certificate.php';

        // Enable debug mode with ?debug=1 parameter
        $debugMode = isset($_GET['debug']) && $_GET['debug'] === '1';
        $certGenerator = new CertificateGenerator($debugMode);

        $profileModel = new Profile();
        $userData = $profileModel->getProfileById($userId);

        if (!$userData) {
            throw new Exception('User data not found');
        }

        // Background image for certificate (optional)
        $bgImagePath = __DIR__ . '/../../uploads/sertifikat/bg.webp'; // Change to your preferred background
        // $bgImagePath = null; // Uncomment to disable background

        // Custom logo(s) for certificate (optional)
        // Supports single logo (string) or multiple logos (array)
        // Each logo can be: string (path/URL) OR array with 'src' and optional 'position'
        // Position options: 'top-center' (default), 'top-left', 'top-right', 'bottom-center', 'bottom-left', 'bottom-right'

        // Example 1: Single logo (backward compatible)
        // $customLogo = __DIR__ . '/../../assets/icon/mortarboard.webp';

        // Example 2: Multiple logos with positions
        $customLogo = [
            [
                'src' => __DIR__ . '/../../assets/icon/saintek.webp',
                'position' => 'top-center'
            ],
            [
                'src' => __DIR__ . '/../../assets/icon/tut.webp',
                'position' => 'top-left'
            ],
            [
                'src' => __DIR__ . '/../../assets/icon/unisnu.webp',
                'position' => 'top-right'
            ],
            // URL example:
            // [
            //     'src' => 'https://example.com/logo.png',
            //     'position' => 'bottom-right'
            // ],
        ];
        // $customLogo = null; // Uncomment to disable custom logos

        // Signature images for certificate footer (optional)
        $signatures = [
            [
                'src' => __DIR__ . '/../../assets/icon/unisnu.webp',
                'label' => 'Ketua Panitia',
                'position' => 'left'
            ],
            [
                'src' => __DIR__ . '/../../assets/icon/tut.webp',
                'label' => 'Sekretaris',
                'position' => 'right'
            ],
            // URL example:
            // [
            //     'src' => 'https://example.com/signature.png',
            //     'label' => 'Manager',
            //     'position' => 'left'
            // ],
        ];
        // $signatures = null; // Uncomment to disable signatures

        // Use minimal template with ?minimal=1 for layout debugging
        if (isset($_GET['minimal']) && $_GET['minimal'] === '1') {
            $pdfContent = $certGenerator->generateMinimal($userData);
        } else {
            $pdfContent = $certGenerator->generate($userData, $eligibility['details'], $bgImagePath, $customLogo, $signatures);
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="sertifikat_' . $userId . '_' . date('Ymd') . '.pdf"');
        header('Content-Length: ' . strlen($pdfContent));
        echo $pdfContent;
        exit;
    } catch (Exception $e) {
        error_log("Certificate generation failed for user $userId: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal generate sertifikat. Silakan coba lagi nanti.'
        ]);
        exit;
    }
}

// Normal profile logic
$profile = new Profile();
$dataProfile = $profile->getProfile($userId);

// Compute certificate eligibility for view
$userModel = new User();
$certEligibility = $userModel->canDownloadCertificate($userId);
$currentProgressPercent = $certEligibility['progress_percent'];
$isEligible = $certEligibility['eligible'];
$missingRequirements = $certEligibility['missing_requirements'];
$progressDetails = $certEligibility['details'];
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
                                                        <img alt="" class="h-100 w-100 object-fit-cover rounded-circle" src="/uploads/profile/<?= (!empty($dataProfile['data']['foto'])) ? $dataProfile['data']['foto'] : 'default.webp'; ?>" />
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
                                                        data-token="<?= htmlspecialchars($dataProfile['data']['token'] ?? '', ENT_QUOTES) ?>"
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
                                    <!-- Certificate Download Button -->
                                    <li class="list-group-item">
                                        <a class="pb-2 d-block text-muted" href="#"><i class="mdi mdi-download text-primary me-1"></i>Unduh Sertifikat</a>
                                        <?php if ($isEligible): ?>
                                            <!-- STATE: ELIGIBLE (100%) -->
                                            <a href="profile.php?action=download_certificate"
                                                class="btn btn-certificate btn-success w-100 d-flex align-items-center justify-content-center gap-2"
                                                id="btnDownloadCertificate">
                                                <!-- <i class="mdi mdi-download"></i> -->
                                                <span>Unduh Sekarang</span>
                                            </a>
                                        <?php else: ?>
                                            <!-- STATE: LOCKED (< 100%) -->
                                            <button type="button"
                                                class="btn btn-certificate btn-certificate--locked w-100 d-flex align-items-center justify-content-center gap-2"
                                                id="btnCertificateLocked"
                                                disabled
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="right"
                                                title="<?= htmlspecialchars(implode("\n", $missingRequirements)) ?>">
                                                <i class="mdi mdi-lock icon-lock"></i>
                                                <span>Sertifikat Terkunci</span>
                                                <span class="badge badge-progress bg-warning text-dark">
                                                    Progres: <?= $currentProgressPercent ?>%
                                                </span>
                                            </button>
                                        <?php endif; ?>
                                    </li>
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
                                            <i class="mdi mdi-logout text-primary me-1">
                                            </i>
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- end card -->

                        <div class="col-xl-3 col-lg-4">
                            <p class="text-muted text-center">Supported by</p>
                            <div class="d-flex justify-content-center align-items-center gap-3 mt-3">
                                <!-- Gunakan style="height: 40px;" untuk membatasi tinggi maksimal logo -->
                                <img class="img-fluid" style="height: 40px; width: auto;" src="\assets\images\logos\tutwuri.png" alt="Tut Wuri">
                                <img class="img-fluid" style="height: 40px; width: auto;" src="\assets\images\logos\diktisaintek.png" alt="Diktisaintek">
                                <img class="img-fluid" style="height: 40px; width: auto;" src="\assets\images\logos\unisnu.png" alt="Unisnu">
                            </div>
                        </div>
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
                                <label class="form-label" for="edit_token">
                                    Token (6 Karakter)
                                </label>
                                <div class="input-group">
                                    <input class="form-control" id="edit_token" name="token" type="text" value="" readonly style="background-color: #f8f9fc;">
                                    <button class="btn btn-outline-secondary" type="button" id="btnCopyToken" title="Salin token">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">Token digunakan untuk reset password jika lupa. Simpan dengan aman.</small>
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
            const token = button.getAttribute('data-token') || '';

            // Masukkan ke dalam input form
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_existing_foto').value = foto;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_name').value = nama;
            document.getElementById('edit_nomor').value = nomor;
            document.getElementById('edit_email').value = email;
            // document.getElementById('edit_password').value = password;
            document.getElementById('edit_ringkasan').value = deskripsi;
            document.getElementById('edit_token').value = token;
        });

        // Copy Token Button
        document.getElementById('btnCopyToken')?.addEventListener('click', function() {
            const tokenInput = document.getElementById('edit_token');
            if (tokenInput.value) {
                navigator.clipboard.writeText(tokenInput.value).then(() => {
                    const originalHtml = this.innerHTML;
                    this.innerHTML = '<i class="mdi mdi-check"></i>';
                    this.classList.add('btn-success');
                    this.classList.remove('btn-outline-secondary');
                    setTimeout(() => {
                        this.innerHTML = originalHtml;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-outline-secondary');
                    }, 1500);
                });
            }
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

        // Certificate Download Logic
        document.addEventListener('DOMContentLoaded', function() {
            const btnDownload = document.getElementById('btnDownloadCertificate');
            const btnLocked = document.getElementById('btnCertificateLocked');

            // Re-validation on click (race condition protection)
            if (btnDownload) {
                btnDownload.addEventListener('click', async function(e) {
                    e.preventDefault();

                    // Show loading state
                    const originalHtml = this.innerHTML;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                    this.disabled = true;

                    try {
                        // HEAD request to re-validate eligibility
                        const response = await fetch('profile.php?action=download_certificate', {
                            method: 'HEAD',
                            credentials: 'same-origin'
                        });

                        if (response.status === 403) {
                            // Progress dropped - reload page to show locked state
                            const data = await response.json();
                            tampilkanNotif('Gagal', data.message || 'Progress requirement not met', 'error');
                            setTimeout(() => location.reload(), 1500);
                            return;
                        }

                        if (response.ok || response.status === 501) {
                            // Proceed with actual download (GET)
                            window.location.href = 'profile.php?action=download_certificate';
                        }
                    } catch (error) {
                        console.error('Certificate download error:', error);
                        tampilkanNotif('Koneksi Gagal', 'Terjadi kesalahan koneksi jaringan.', 'error');
                    } finally {
                        this.innerHTML = originalHtml;
                        this.disabled = false;
                    }
                });
            }

            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>

</html>