<?php
require_once '../src/classes/auth.php';

$auth = new auth();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika sudah login, arahkan ke folder sesuai role
if (!empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    $role = $_SESSION['role'] ?? '';
    $allowedRoles = ['admin', 'user', 'konsultan', 'ortu'];

    if (in_array($role, $allowedRoles, true)) {
        header("Location: {$role}/index.php");
        exit;
    }

    // Role tidak dikenali: logout atau ke login
    header('Location: login.php');
    exit;
}

// Jika belum login, tetap ke halaman login
$auth->authOrNot();
?>