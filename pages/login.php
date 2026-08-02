<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>
        Login | StarCode Kh - Minimal Admin &amp; Dashboard Template
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
                                        <img alt="" height="28" src="../assets/images/logo-sm.svg" />
                                        <span class="logo-txt">
                                            StarCode Kh
                                        </span>
                                    </a>
                                </div>
                                <div class="auth-content my-auto">
                                    <div class="text-center">
                                        <h5 class="mb-0">
                                            Selamat Datang, Pengguna!
                                        </h5>
                                        <p class="text-muted mt-2">
                                            Masuk untuk melanjutkan.
                                        </p>
                                    </div>
                                    <form id="loginForm" class="mt-4 pt-2">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Username
                                            </label>
                                            <input class="form-control" name="username" id="username" placeholder="Enter username" type="text" required />
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <label class="form-label">
                                                        Password
                                                    </label>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="">
                                                        <a class="text-muted" href="auth-recoverpw.html">
                                                            Lupa Password?
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-group auth-pass-inputgroup">
                                                <input aria-describedby="password-addon" aria-label="Password" class="form-control" placeholder="Enter password" name="password" type="password" required />
                                                <button class="btn btn-light shadow-none ms-0" id="password-addon" type="button">
                                                    <i class="mdi mdi-eye-outline">
                                                    </i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="remember-check" type="checkbox" />
                                                    <label class="form-check-label" for="remember-check">
                                                        Ingat Login Saya
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">
                                                Masuk
                                            </button>
                                        </div>
                                    </form>
                                    <div class="mt-5 text-center">
                                        <p class="text-muted mb-0">
                                            Belum memiliki akun ?
                                            <a class="text-primary fw-semibold" href="register.php">
                                                Daftar sekarang
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
                                        StarCode Kh . Crafted with
                                        <i class="mdi mdi-heart text-danger">
                                        </i>
                                        by davied&njah
                                    </p>
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
    <?php include('include/script.php'); ?>

    <script>
        const form = document.getElementById('loginForm');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            formData.append('action', 'login');

            try {
                const response = await fetch("../src/actions/proses_auth.php", {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();

                if (result.status == 'success') {
                    window.location.href = result.redirect;
                } else {
                    alert("Error: " + result.message);
                }
            } catch (error) {
                console.error("Error:", error);
                alert("Terjadi kesalahan saat memproses login. Silakan coba lagi.");
            }
        });
    </script>
</body>

</html>