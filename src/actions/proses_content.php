<?php

session_start();

require_once '../classes/informasi.php';

header('Content-Type: application/json');


// =========================================================
// CEK LOGIN ADMIN
// =========================================================

if (
    !isset($_SESSION['is_logged_in']) ||
    $_SESSION['role'] !== 'admin'
) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak! Hanya admin yang dapat melakukan operasi.'
    ]);
    exit;
}


// =========================================================
// OBJECT
// =========================================================

$informasi = new Informasi();


// =========================================================
// ACTION
// =========================================================

$action = $_POST['action'] ?? $_GET['action'] ?? '';


// =========================================================
// GET SATU CONTENT
// =========================================================

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'get') {

    $id = (int) ($_GET['id'] ?? 0);

    if ($id <= 0) {

        echo json_encode([
            'status' => 'error',
            'message' => 'ID informasi tidak valid.'
        ]);

        exit;
    }

    $data = $informasi->getContentById($id);

    if (!$data) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Informasi tidak ditemukan.'
        ]);

        exit;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $data
    ]);

    exit;
}


// =========================================================
// SAVE
// ADD / UPDATE
// =========================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'save') {

    $id = (int) ($_POST['id'] ?? 0);

    $categoryId = (int) ($_POST['category_id'] ?? 0);

    $judul = trim($_POST['judul'] ?? '');

    $deskripsi = trim($_POST['deskripsi'] ?? '');


    // -----------------------------------------------------
    // VALIDASI
    // -----------------------------------------------------

    if ($categoryId <= 0) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Kategori tidak valid.'
        ]);

        exit;
    }


    if ($judul === '') {

        echo json_encode([
            'status' => 'error',
            'message' => 'Judul informasi tidak boleh kosong.'
        ]);

        exit;
    }


    if ($deskripsi === '') {

        echo json_encode([
            'status' => 'error',
            'message' => 'Deskripsi informasi tidak boleh kosong.'
        ]);

        exit;
    }


    // -----------------------------------------------------
    // UPLOAD FOTO
    // -----------------------------------------------------

    $foto = null;

    if (
        isset($_FILES['foto']) &&
        $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $file = $_FILES['foto'];


        // Cek error upload
        if ($file['error'] !== UPLOAD_ERR_OK) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal mengupload foto.'
            ]);

            exit;
        }


        // Maksimal 5 MB
        $maxSize = 5 * 1024 * 1024;

        if ($file['size'] > $maxSize) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Ukuran foto maksimal 5 MB.'
            ]);

            exit;
        }


        // Validasi MIME type
        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp'
        ];


        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $mimeType =
            finfo_file(
                $finfo,
                $file['tmp_name']
            );

        finfo_close($finfo);


        if (!isset($allowedTypes[$mimeType])) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Format foto harus JPG, PNG, atau WEBP.'
            ]);

            exit;
        }


        // Buat nama file unik
        $extension = $allowedTypes[$mimeType];

        $fileName =
            'content_' .
            time() .
            '_' .
            bin2hex(random_bytes(5)) .
            '.' .
            $extension;


        // Folder upload
        $uploadDir =
            '../../uploads/contents/';


        // Buat folder jika belum ada
        if (!is_dir($uploadDir)) {

            if (!mkdir($uploadDir, 0755, true)) {

                echo json_encode([
                    'status' => 'error',
                    'message' => 'Folder upload tidak dapat dibuat.'
                ]);

                exit;
            }
        }


        $uploadPath =
            $uploadDir . $fileName;


        // Pindahkan file
        if (!move_uploaded_file(
            $file['tmp_name'],
            $uploadPath
        )) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Foto gagal disimpan.'
            ]);

            exit;
        }


        $foto = $fileName;
    }


    // -----------------------------------------------------
    // UPDATE
    // -----------------------------------------------------

    if ($id > 0) {

        /*
         * Jika user tidak upload foto baru,
         * foto lama tetap digunakan.
         */

        if ($foto !== null) {

            $result =
                $informasi->updateContent(
                    $id,
                    $categoryId,
                    $judul,
                    $foto,
                    $deskripsi
                );
        } else {

            $result =
                $informasi->updateContentWithoutFoto(
                    $id,
                    $categoryId,
                    $judul,
                    $deskripsi
                );
        }


        echo json_encode($result);

        exit;
    }


    // -----------------------------------------------------
    // ADD
    // -----------------------------------------------------

    $result =
        $informasi->addContent(
            $categoryId,
            $judul,
            $foto,
            $deskripsi
        );


    echo json_encode($result);

    exit;
}


// =========================================================
// DELETE
// =========================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {

    $id = (int) ($_POST['id'] ?? 0);


    if ($id <= 0) {

        echo json_encode([
            'status' => 'error',
            'message' => 'ID informasi tidak valid.'
        ]);

        exit;
    }


    $result =
        $informasi->deleteContent($id);


    echo json_encode($result);

    exit;
}


// =========================================================
// ACTION TIDAK DIKENALI
// =========================================================

echo json_encode([
    'status' => 'error',
    'message' => 'Action tidak dikenali.'
]);

exit;
