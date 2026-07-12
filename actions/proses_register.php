<?php

require_once '../classes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = 'user';

    if (empty($username) || empty($password)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Username, password, dan role harus diisi.'
        ]);
        exit;
    }

    $auth = new auth();

    $registerSuccess = $auth->register($username, $password, $role);

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
}
