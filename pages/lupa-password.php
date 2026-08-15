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
        Lupa Password | REMAJA TUMBUH - Minimal Admin & Dashboard Template
    </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description">
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
                                        <img alt="" height="28" src="/assets/images/logos/logo.webp" />
                                        <span class="logo-txt">
                                            REMAJA TUMBUH
                                        </span>
                                    </a>
                                </div>
                                <div class="auth-content my-auto">
                                    <div class="text-center">
                                        <h5 class="mb-0">
                                            Lupa Password
                                        </h5>
                                        <p class="text-muted mt-2">
                                            Masukkan token dan password baru Anda
                                        </p>
                                    </div>
                                    <form id="forgotPasswordForm" class="mt-4 pt-2">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Token (6 Karakter)
                                            </label>
                                            <input class="form-control" name="token" id="token" placeholder="Masukkan token 6 karakter" type="text" required maxlength="6" style="text-transform: uppercase;" />
                                            <small class="form-text text-muted">Token dapat dilihat di halaman profil Anda</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Password Baru
                                            </label>
                                            <div class="input-group auth-pass-inputgroup">
                                                <input aria-describedby="password-addon" aria-label="Password" class="form-control" placeholder="Masukkan password baru" name="password" type="password" required minlength="6" />
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Konfirmasi Password Baru
                                            </label>
                                            <div class="input-group auth-pass-inputgroup">
                                                <input aria-describedby="confirm-password-addon" aria-label="Confirm Password" class="form-control" placeholder="Konfirmasi password baru" name="confirm_password" type="password" required minlength="6" />
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">
                                                Reset Password
                                            </button>
                                        </div>
                                    </form>
                                    <div class="mt-5 text-center">
                                        <p class="text-muted mb-0">
                                            Kembali ke
                                            <a class="text-primary fw-semibold" href="login.php">
                                                Login
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
    <?php include('include/script.php'); ?>

    <script>
        const form = document.getElementById('forgotPasswordForm');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            formData.append('action', 'forgot_password');

            try {
                const response = await fetch("../src/actions/proses_auth.php", {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();

                if (result.status == 'success') {
                    alert("Sukses: " + result.message);
                    window.location.href = result.redirect;
                } else {
                    alert("Error: " + result.message);
                }
            } catch (error) {
                console.error("Error:", error);
                alert("Terjadi kesalahan saat memproses reset password. Silakan coba lagi.");
            }
        });

        // Auto uppercase token input
        document.getElementById('token').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    </script>
</body>

</html>