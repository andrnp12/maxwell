<?php

require_once '../classes/auth.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'login') {

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Metode request tidak valid.'
        ]);
        exit;
    }

    require_once '../classes/dbconnect.php';
    $db = new dbconnect();
    $conn = $db->conn;

    $username = mysqli_escape_string($conn, $_POST['username'] ?? '');
    $password = mysqli_escape_string($conn, $_POST['password'] ?? '');
    $rememberMe = isset($_POST['remember_me']) && $_POST['remember_me'] === '1';

    $auth = new auth();
    $loginSuccess = $auth->login($username, $password, $rememberMe);

    if ($loginSuccess) {
        $roleRedirects = [
            'admin' => 'admin/index.php',
            'ortu'  => 'ortu/index.php',
            'user'  => 'user/index.php',
            'konsultan' => 'konsultan/index.php'
        ];

        $userRole = $_SESSION['role'] ?? '';
        $redirectUrl = $roleRedirects[$userRole] ?? '../index.php';

        echo json_encode([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'redirect' => $redirectUrl
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Username atau password salah.'
        ]);
    }
    exit;
} elseif ($action === 'register') {

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Metode request tidak valid.'
        ]);
        exit;
    }

    $foto = $_POST['foto'] ?? '';
    $username = $_POST['username'] ?? '';
    $no_kk = $_POST['no_kk'] ?? '';
    $name = $_POST['name'] ?? '';
    $nomor = $_POST['nomor'] ?? '';
    $email = $_POST['email'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $role = $_POST['role'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!preg_match('/^[0-9]{16}$/', $no_kk)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error: Format No. KK tidak valid! Harus 16 digit angka.'
        ]);
        exit;
    }

    if (empty($username) || empty($password)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'name, username, no.kk, password, dan role harus diisi.'
        ]);
        exit;
    }

    $auth = new auth();
    $registerResult = $auth->register($foto, $username, $no_kk, $name, $email, $password, $deskripsi, $role);

    if ($registerResult['success']) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Registrasi berhasil. Token Anda: ' . $registerResult['token'],
            'token' => $registerResult['token'],
            'redirect' => 'login.php'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => $registerResult['message']
        ]);
    }
    exit;
} elseif ($action === 'logout') {

    $auth = new auth();
    $auth->logout();

    header('Location: ../../pages/login.php');
    exit;
} elseif ($action === 'forgot_password') {

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Metode request tidak valid.'
        ]);
        exit;
    }

    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($token) || empty($password) || empty($confirmPassword)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Token, password baru, dan konfirmasi password harus diisi.'
        ]);
        exit;
    }

    if (strlen($token) !== 6) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Token harus 6 karakter.'
        ]);
        exit;
    }

    if ($password !== $confirmPassword) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Password dan konfirmasi password tidak cocok.'
        ]);
        exit;
    }

    if (strlen($password) < 6) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Password minimal 6 karakter.'
        ]);
        exit;
    }

    $auth = new auth();
    $result = $auth->forgotPassword($token, $password);

    if ($result['success']) {
        echo json_encode([
            'status' => 'success',
            'message' => $result['message'],
            'redirect' => 'login.php'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => $result['message']
        ]);
    }
    exit;
} else {

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Aksi tidak valid.'
    ]);
    exit;
}
