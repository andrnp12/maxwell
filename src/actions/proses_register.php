<?php

require_once '../classes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            'message' => 'Username, password, dan role harus diisi.'
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
}
