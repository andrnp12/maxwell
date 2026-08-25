<?php
// Redirect jika sudah login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    $role = $_SESSION['role'] ?? '';
    $allowedRoles = ['admin', 'user', 'konsultan', 'ortu'];

    if (in_array($role, $allowedRoles, true)) {
        header("Location: {$role}/index.php");
        exit;
    }

    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>
        Register | REMAJA TUMBUH - Minimal Admin &amp; Dashboard Template
    </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Premium Multipurpose Admin &amp; Dashboard Template" name="description">
    <meta content="admintem.com" name="author" />
    <!-- App favicon -->
    <link href="../assets/images/favicon.ico" rel="shortcut icon" />
    <!-- preloader css -->
    <link href="../assets/css/preloader.min.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap Css -->
    <link href="../assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="../assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    </link>
    </meta>
</head>

<body>
    <!-- <body data-layout="horizontal"> -->
    <div class="auth-page">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-xxl-3 col-lg-4 col-md-5">
                    <div class="auth-full-page-content d-flex p-sm-5 p-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-4 mb-md-5 text-center">
                                    <a class="d-block auth-logo" href="#">
                                        <img alt="" height="28" src="/assets/images/logo-sm.svg" />
                                        <span class="logo-txt">
                                            REMAJA TUMBUH
                                        </span>
                                    </a>
                                </div>
                                <div class="auth-content my-auto">
                                    <div class="text-center">
                                        <h5 class="mb-0">
                                            Pendaftaran Akun
                                        </h5>
                                        <p class="text-muted mt-2">
                                            Daftar akun anda untuk melanjutkan
                                        </p>
                                    </div>
                                    <form id="registerForm" class="mt-4 pt-2">
                                        <div class="mb-3">
                                            <label class="form-label" for="name">
                                                Masukkan Nama Anda
                                            </label>
                                            <input class="form-control" id="name" name="name" placeholder="Masukkan nama anda" type="text" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="username">
                                                Masukkan Username Anda
                                            </label>
                                            <input class="form-control" id="username" name="username" placeholder="Masukkan username anda" type="text" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="no_kk">
                                                Masukkan No. Kartu Keluarga Anda
                                            </label>
                                            <!-- Tambahkan minlength, pattern, dan inputmode -->
                                            <input
                                                class="form-control"
                                                id="no_kk"
                                                name="no_kk"
                                                placeholder="Masukkan 16 digit No. KK"
                                                type="text"
                                                maxlength="16"
                                                minlength="16"
                                                pattern="[0-9]{16}"
                                                inputmode="numeric"
                                                title="Nomor KK harus berupa 16 digit angka"
                                                required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="role">
                                                Pilih Kriteria Anda
                                            </label>
                                            <select class="form-select" id="role" name="role" required>
                                                <option value="" selected disabled>-- Pilih Kriteria --</option>
                                                <option value="ortu">Orang Tua</option>
                                                <option value="user">Siswa</option>
                                            </select>
                                            <small class="form-text text-muted">Pilih kriteria yang sesuai dengan anda</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="userpassword">
                                                Password Anda
                                            </label>
                                            <input class="form-control" id="userpassword" name="password" placeholder="Masukkan password anda" type="password" required />
                                        </div>
                                        <div class="mb-4">
                                            <p class="mb-0">
                                                Dengan mendaftar, anda menyetujui
                                                <a class="text-primary" href="#">
                                                    Syarat dan Ketentuan
                                                </a>
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">
                                                Daftar Akun
                                            </button>
                                        </div>
                                    </form>
                                    <div class="mt-5 text-center">
                                        <p class="text-muted mb-0">
                                            Sudah punya akun ?
                                            <a class="text-primary fw-semibold" href="login.php">
                                                Masuk
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4 mt-md-5 text-center">
                                    <p class="mb-0">
                                        ©
                                        <script>
                                            document.write(new Date().getFullYear())
                                        </script>
                                        REMAJA TUMBUH . Supported by
                                    </p>
                                    <div class="d-flex justify-content-center align-items-center gap-3 mt-3">
                                        <!-- Gunakan style="height: 40px;" untuk membatasi tinggi maksimal logo -->
                                        <img class="img-fluid" style="height: 40px; width: auto;" src="\assets\images\logos\tutwuri.png" alt="Tut Wuri">
                                        <img class="img-fluid" style="height: 40px; width: auto;" src="\assets\images\logos\diktisaintek.png" alt="Diktisaintek">
                                        <img class="img-fluid" style="height: 40px; width: auto;" src="\assets\images\logos\unisnu.png" alt="Unisnu">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end auth full page content -->
                </div>
                <!-- end col -->
                <div class="col-xxl-9 col-lg-8 col-md-7">
                    <div class="auth-bg pt-md-5 p-4 d-flex">
                        <div class="bg-overlay bg-primary">
                        </div>
                        <ul class="bg-bubbles">
                            <li>
                            </li>
                            <li>
                            </li>
                            <li>
                            </li>
                            <li>
                            </li>
                            <li>
                            </li>
                            <li>
                            </li>
                            <li>
                            </li>
                            <li>
                            </li>
                            <li>
                            </li>
                            <li>
                            </li>
                        </ul>
                        <!-- end bubble effect -->
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container fluid -->
    </div>
    <!-- JAVASCRIPT -->
    <?php include("include/script.php"); ?>
    <script>
        const registerForm = document.getElementById('registerForm');
        registerForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(registerForm);
            formData.append('action', 'register');

            fetch('/../src/actions/proses_auth.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    console.log("Response dari PHP:", result);

                    try {
                        const data = JSON.parse(result);

                        if (data.status === 'success') {
                            alert("Sukses: " + data.message);
                            window.location.href = data.redirect;
                        } else {
                            alert("Error: " + data.message);
                        }
                    } catch (error) {
                        console.error("Response bukan JSON:", result);
                        alert("mohon maaf, terjadi kesalahan");
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
        });
    </script>
</body>

</html>