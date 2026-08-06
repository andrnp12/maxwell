<?php

session_start();

require_once '../classes/auth.php';
require_once '../classes/progress_user.php';

$auth = new Auth();
$auth->authOrNot();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method tidak diizinkan.'
    ]);

    exit;
}

$materialId = filter_input(INPUT_POST, 'material_id', FILTER_VALIDATE_INT);

if (!$materialId) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Material tidak valid.'
    ]);

    exit;
}

// Ambil user id dari session
$userId = (int) $_SESSION['id'];

$progress = new ProgressUser();

$success = $progress->finishMaterial($userId, $materialId);

echo json_encode([
    'success' => $success
]);

exit;
