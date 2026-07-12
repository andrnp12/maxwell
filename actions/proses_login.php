<?php

require_once '../classes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Username dan password harus diisi.'
        ]);
        exit;
    }

    $auth = new auth();

    $loginSuccess = $auth->login($username, $password);

    if ($loginSuccess) {
        $redirectUrl = ($_SESSION['role'] === "admin") ? 'admin/index.php' : 'index.php';

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
