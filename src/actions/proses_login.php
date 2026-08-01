<?php
require_once '../classes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once '../classes/dbconnect.php';
    $db = new dbconnect();
    $conn = $db->conn;

    $username = mysqli_escape_string($conn, $_POST['username'] ?? '');
    $password = mysqli_escape_string($conn, $_POST['password'] ?? '');

    $auth = new auth();
    $loginSuccess = $auth->login($username, $password);

    if ($loginSuccess) {
        // 1. Definisikan mapping role ke halaman tujuan
        $roleRedirects = [
            'admin' => 'admin/index.php',
            'ortu'  => 'ortu/index.php',
            'user'  => 'user/index.php',
            // Tambahkan role lain di sini jika ada, contoh:
            // 'konselor' => 'konselor/index.php',
        ];

        // 2. Ambil role dari session
        $userRole = $_SESSION['role'] ?? '';

        // 3. Tentukan URL redirect berdasarkan role. 
        // Jika role tidak terdaftar di array, maka default ke 'index.php'
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
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak valid.'
    ]);
}
