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

    $auth = new auth();
    $loginSuccess = $auth->login($username, $password);

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
    $name = $_POST['name'] ?? '';
    $nomor = $_POST['nomor'] ?? '';
    $email = $_POST['email'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $role = $_POST['role'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'name, username, password, dan role harus diisi.'
        ]);
        exit;
    }

    $auth = new auth();
    $registerSuccess = $auth->register($foto, $username, $name, $nomor, $email, $password, $deskripsi, $role);

    if ($registerSuccess) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Registrasi berhasil.',
            'redirect' => 'login.php'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Registrasi gagal.'
        ]);
    }
    exit;

} elseif ($action === 'logout') {

    $auth = new auth();
    $auth->logout();

    header('Location: ../../pages/login.php');
    exit;

} else {

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Aksi tidak valid.'
    ]);
    exit;

}