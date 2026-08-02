<?php
session_start();
require_once '../classes/chat_komunitas.php';

$idLogin = (int) $_SESSION['id'];
$role = $_SESSION['role'];
$chat = new chatKomunitas();

if (!isset($_SESSION['is_logged_in'])) {
    // Jika request berupa POST (dari fetch form), kembalikan JSON
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.']);
    }
    exit;
}

if ($_SESSION['role'] !== 'user' && $_SESSION['role'] !== 'konsultan') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json');

    $idKomunitas = isset($_POST['id_komunitas']) ? (int) $_POST['id_komunitas'] : 0;
    $isiChat = trim($_POST['isi_chat'] ?? '');

    if ($idKomunitas <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Komunitas tidak valid.']);
        exit;
    }

    if ($isiChat === '') {
        echo json_encode(['status' => 'error', 'message' => 'Pesan tidak boleh kosong.']);
        exit;
    }

    $idUserDb = $idLogin;
    $idKomunitasDb = $idKomunitas;

    $result = $chat->sendUserMessageKomunitas($idUserDb, $idKomunitasDb, $isiChat);
    echo json_encode($result);
    exit;
}
