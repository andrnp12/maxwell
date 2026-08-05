<?php
session_start();
require_once '../classes/user.php';

header('Content-Type: application/json');

// Check authentication
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak! Hanya admin yang dapat mengakses data pengguna.'
    ]);
    exit;
}

$userModel = new User();

// Handle GET: retrieve all users with quiz results
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? 'list';
    
    if ($action === 'list') {
        $result = $userModel->getAllUsersWithQuizResults();
        echo json_encode($result);
    } elseif ($action === 'detail' && isset($_GET['id'])) {
        $result = $userModel->getUserWithQuizResults((int)$_GET['id']);
        echo json_encode($result);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Action tidak valid.'
        ]);
    }
    exit;
}

echo json_encode([
    'status' => 'error',
    'message' => 'Method tidak diizinkan.'
]);