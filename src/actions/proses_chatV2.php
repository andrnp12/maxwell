<?php

session_start();

header('Content-Type: application/json');

require_once '../classes/chatV2.php';

$chat = new ChatV2();

function request(string $key, $default = null)
{
    return $_POST[$key]
        ?? $_GET[$key]
        ?? $default;
}

try {

    if (!isset($_SESSION['id'], $_SESSION['role'])) {
        throw new Exception("Silakan login terlebih dahulu.");
    }

    $loginId = (int) $_SESSION['id'];
    $role    = $_SESSION['role'];

    $action   = request('action');
    $chatType = request('chat_type');

    $targetId = (int) request('target_id', 0);
    $message  = trim(request('message', ''));
    $lastId   = (int) request('last_id', 0);

    if (!in_array($chatType, ['personal', 'group'], true)) {
        throw new Exception("Chat type tidak valid.");
    }


    switch ($action) {

        case 'send_message':

            $conversation = $chat->resolveChatTarget(
                $chatType,
                $loginId,
                $targetId,
                $role
            );

            if ($conversation['status'] !== 'success') {
                throw new Exception($conversation['message']);
            }

            $idUser = $conversation['data']['id_user'];
            $target = $conversation['data']['target'];

            if ($targetId <= 0) {
                throw new Exception("Target tidak valid.");
            }

            if ($message === '') {
                throw new Exception("Pesan tidak boleh kosong.");
            }

            $result = $chat->sendMessage(
                $chatType,
                $loginId,
                $idUser,
                $target,
                $message
            );

            break;

        case 'get_messages':


            $conversation = $chat->resolveChatTarget(
                $chatType,
                $loginId,
                $targetId,
                $role
            );

            if ($conversation['status'] !== 'success') {
                throw new Exception($conversation['message']);
            }

            $idUser = $conversation['data']['id_user'];
            $target = $conversation['data']['target'];

            if ($targetId <= 0) {
                throw new Exception("Target tidak valid.");
            }

            $result = $chat->getMessages(
                $chatType,
                $idUser,
                $target,
                $lastId
            );

            break;

        case 'get_conversations':

            $result = $chat->getConversationList(
                $chatType,
                $loginId,
                $role
            );

            break;

        case 'open_room':

            if ($targetId <= 0) {
                throw new Exception("Target tidak valid.");
            }

            $result = $chat->getRoomInfo(
                $chatType,
                $targetId
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
