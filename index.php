<?php
// Redirect jika sudah login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    $role = $_SESSION['role'] ?? '';
    $allowedRoles = ['admin', 'user', 'konsultan', 'ortu'];

    if (in_array($role, $allowedRoles, true)) {
        header("Location: pages/{$role}/index.php");
        exit;
    }

    header('Location: pages/index.php');
    exit;
}

// Jika belum login, tetap ke halaman login
header('Location: pages/login.php');
exit;
