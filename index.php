<?php
require_once 'src/classes/auth.php';
$auth = new auth();

// Satu baris ini menggantikan SEMUA logika session,
// pengecekan login, restore remember-me, dan redirect role.
$auth->authOrNot();

// Jika kode sampai di sini, berarti user sudah terautentikasi dan role-nya valid.
// Redirect ke dashboard berdasarkan role user
$userRole = $_SESSION['role'] ?? '';

$dashboardMap = [
    'admin' => '/pages/admin/index.php',
    'user' => '/pages/user/index.php',
    'konsultan' => '/pages/konsultan/index.php',
    'ortu' => '/pages/ortu/index.php',
];

if (isset($dashboardMap[$userRole])) {
    header('Location: ' . $dashboardMap[$userRole]);
    exit;
}

// Fallback jika role tidak dikenali
header('Location: /pages/login.php');
exit;
