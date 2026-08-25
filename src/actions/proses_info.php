<?php
session_start();

require_once '../classes/informasi.php';

header('Content-Type: application/json');

if (
    !isset($_SESSION['is_logged_in']) ||
    $_SESSION['role'] !== 'admin'
) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak! Hanya admin yang dapat melakukan operasi pada kategori.'
    ]);
    exit;
}

$informasi = new Informasi();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

/*
|--------------------------------------------------------------------------
| GET ALL KATEGORI
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'get_kategori') {

    $dataKategori = $informasi->getAllKategori();

    echo json_encode([
        'status' => 'success',
        'data' => $dataKategori
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| TAMBAH / EDIT KATEGORI
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'save') {

    $id = $_POST['id'] ?? '';
    $judul_kategori = trim($_POST['judul_kategori'] ?? '');

    if ($judul_kategori === '') {
        echo json_encode([
            'status' => 'error',
            'message' => 'judul_kategori kategori tidak boleh kosong.'
        ]);
        exit;
    }

    /*
    | Jika ada ID → UPDATE
    | Jika tidak ada ID → INSERT
    */
    if ($id !== '') {

        $result = $informasi->updateKategori(
            (int) $id,
            $judul_kategori
        );
    } else {

        $result = $informasi->addKategori($judul_kategori);
    }

    echo json_encode($result);

    exit;
}

/*
|--------------------------------------------------------------------------
| HAPUS KATEGORI
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {

    $id = $_POST['id'] ?? '';

    if ($id === '') {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID kategori tidak ditemukan.'
        ]);
        exit;
    }

    $result = $informasi->deleteKategori((int) $id);

    echo json_encode($result);

    exit;
}

/*
|--------------------------------------------------------------------------
| ACTION TIDAK DIKENAL
|--------------------------------------------------------------------------
*/
echo json_encode([
    'status' => 'error',
    'message' => 'Action tidak dikenali.'
]);
exit;
