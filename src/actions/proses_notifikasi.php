<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../classes/notifikasi.php';

if (!isset($_SESSION['role']) || !isset($_SESSION['id'])) {
    echo json_encode(['count' => 0, 'items' => []]);
    exit;
}

$notiClass = new Notifikasi();
$data = $notiClass->getNotifikasiRole($_SESSION['role'], $_SESSION['id']);
echo json_encode($data);
