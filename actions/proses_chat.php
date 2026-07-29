<?php
session_start();
require_once '../classes/chat.php';

// ==========================================
// CEK LOGIN & ROLE (Berlaku untuk GET & POST)
// ==========================================
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

$idLogin = (int) $_SESSION['id'];
$role = $_SESSION['role'];
$chat = new Chat();


// ==========================================
// KAMAR 1: PROSES KIRIM PESAN (Method POST)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Wajib di set JSON hanya untuk POST
    header('Content-Type: application/json');

    $idLawan = isset($_POST['id_lawan']) ? (int) $_POST['id_lawan'] : 0;
    $isiChat = trim($_POST['isi_chat'] ?? '');

    if ($idLawan <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Lawan bicara tidak valid.']);
        exit;
    }

    if ($isiChat === '') {
        echo json_encode(['status' => 'error', 'message' => 'Pesan tidak boleh kosong.']);
        exit;
    }

    if ($role === 'user') {
        $idUserDb = $idLogin;
        $idKonselorDb = $idLawan;
    } else {
        $idUserDb = $idLawan;
        $idKonselorDb = $idLogin;
    }

    $result = $chat->sendUserMessage($idUserDb, $idKonselorDb, $role, $isiChat);
    echo json_encode($result);
    exit;
}

// ==========================================
// KAMAR 2: PROSES TARIK PESAN (Method GET)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Tangkap id_lawan dari parameter URL Javascript
    $idLawan = isset($_GET['id_lawan']) ? (int)$_GET['id_lawan'] : 0;

    $lastId = isset($_GET['last_id'])
        ? (int)$_GET['last_id']
        : 0;


    if ($idLawan === 0) exit;

    if ($role == 'user') {
        $idUserDb = $idLogin;
        $idKonselorDb = $idLawan;
    } else {
        $idUserDb = $idLawan;
        $idKonselorDb = $idLogin;
    }

    $dataChat = $chat->getAllMessage(
        $idUserDb,
        $idKonselorDb,
        $lastId
    );

    echo json_encode([
        'status' => 'success',
        'data' => $dataChat
    ]);

    // Render menjadi HTML
    // foreach ($dataChat as $pesan) {
    //     $waktu = date('H:i', strtotime($pesan['time_stamp']));
    //     $isiChatRender = htmlspecialchars($pesan['chat']);

    //     if ($pesan['pengirim'] == $role) {

    //         echo '
    // <li class="right">
    //     <div class="conversation-list">
    //         <div class="ctext-wrap">
    //             <div class="ctext-wrap-content">
    //                 <h5 class="conversation-name">
    //                     <a class="user-name" href="#">Jennie Sherlock</a>
    //                     <span class="time">' . $waktu . '</span>
    //                 </h5>
    //                 <p class="mb-0">' . $isiChatRender . '</p>
    //             </div>
    //         </div>
    //     </div>
    // </li>';
    //     } else {

    //         echo '
    // <li>
    //     <div class="conversation-list">
    //         <div class="ctext-wrap">
    //             <div class="ctext-wrap-content">
    //                 <h5 class="conversation-name">
    //                     <a class="user-name" href="#">Shawn</a>
    //                     <span class="time">' . $waktu . '</span>
    //                 </h5>
    //                 <p class="mb-0">' . $isiChatRender . '</p>
    //             </div>
    //         </div>
    //     </div>
    // </li>';
    //     }
    // }
    exit;
}
