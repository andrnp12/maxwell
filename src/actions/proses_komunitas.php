<?php
session_start();
header('Content-Type: application/json');

require_once '../classes/komunitas.php';

$komunitas = new Komunitas();

try {

    if (!isset($_SESSION['id'])) {
        throw new Exception("Silakan login terlebih dahulu.");
    }

    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'join_group':

            $idKomunitas = (int) ($_POST['id_komunitas'] ?? 0);
            $idUser = (int) $_SESSION['id'];

            if ($idKomunitas <= 0) {
                throw new Exception("Komunitas tidak valid.");
            }

            $result = $komunitas->joinGroup(
                $idKomunitas,
                $idUser
            );

            break;

        default:

            throw new Exception("Action tidak dikenali.");
    }

    echo json_encode($result);
} catch (Throwable $e) {

    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]);
}
