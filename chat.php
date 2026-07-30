<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$role      = $_SESSION['role'];
$id_login  = $_SESSION['id'];

$chatType  = $_GET['chat_type'] ?? 'personal';
$id_lawan  = isset($_GET['id_lawan'])
    ? (int) $_GET['id_lawan']
    : 0;

require_once 'classes/ChatV2.php';

$objChat = new ChatV2();

$personal = $objChat->getConversationList(
    'personal',
    $id_login,
    $role
);

// personal
$listChat = $personal['status'] === 'success'
    ? $personal['data']
    : [];

$group = $objChat->getConversationList(
    'group',
    $id_login,
    $role
);

// group
$listGroup = $group['status'] === 'success'
    ? $group['data']
    : [];

// room chat
$dataChat = [];
$roomInfo = [];

if ($id_lawan > 0) {

    $target = $objChat->resolveChatTarget(
        $chatType,
        $id_login,
        $id_lawan,
        $role
    );

    if ($target['status'] === 'success') {

        $room = $objChat->getRoomInfo(
            $chatType,
            $id_lawan
        );

        if ($room['status'] === 'success') {
            $roomInfo = $room['data'];
        }

        $messages = $objChat->getMessages(
            $chatType,
            $target['data']['id_user'],
            $target['data']['target']
        );

        if ($messages['status'] === 'success') {
            $dataChat = $messages['data'];
        }
    }
}

?>

<!--header start-->
<?php include('src/include/header.php'); ?>
<!--headere end-->

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- ========== Topbar Start ========== -->
        <?php include('src/include/topbar.php'); ?>
        <!-- ========== Topbar End ========== -->
        <!-- ========== Left Sidebar Start ========== -->
        <?php include('src/include/sidebar.php'); ?>
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <!-- <div class="row">
                  <div class="col-12">
                     <div class="row d-sm-flex align-items-center justify-content-between mb-2">
                        <h4 class="mb-sm-0 font-weight-bold mb-1">
                           Chat
                        </h4>
                        <p class="text-muted">
                           Hubungi sesama dan dapatkan konsultasi!
                        </p>
                     </div>
                  </div>
               </div> -->
                    <!-- end page title -->
                    <div class="d-lg-flex">
                        <div id="chatSidebar" class="chat-leftsidebar card">
                            <div class="p-3 px-4 border-bottom">
                                <div class="d-flex align-items-start">
                                    <!-- <div class="flex-shrink-0 me-3 align-self-center">
                              <img alt="" class="avatar-sm rounded-circle" src="assets/images/users/avatar-1.jpg" />
                           </div> -->
                                    <div class="flex-grow-1">
                                        <h5 class="font-size-16 mb-1">
                                            <a class="text-dark" href="#">
                                                Perpesanan
                                                <i class="mdi mdi-circle text-success align-middle font-size-10 ms-1">
                                                </i>
                                            </a>
                                        </h5>
                                        <p class="text-muted mb-0">
                                            Daftar perpesanan anda
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="dropdown chat-noti-dropdown">
                                            <button aria-expanded="false" aria-haspopup="true" class="btn dropdown-toggle p-0" data-bs-toggle="dropdown" type="button">
                                                <i class="bx bx-dots-horizontal-rounded">
                                                </i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">
                                                    Profile
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    Edit
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    Add Contact
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    Setting
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <div class="search-box position-relative">
                                    <input class="form-control rounded border" placeholder="Search..." type="text" />
                                    <i class="bx bx-search search-icon">
                                    </i>
                                </div>
                            </div>
                            <div class="chat-leftsidebar-nav">
                                <ul class="nav nav-pills nav-justified bg-light-subtle p-1">
                                    <li class="nav-item">
                                        <a aria-expanded="true" class="nav-link active" data-bs-toggle="tab" href="#chat">
                                            <i class="bx bx-chat font-size-20 d-sm-none">
                                            </i>
                                            <span class="d-none d-sm-block">
                                                Chat
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#groups">
                                            <i class="bx bx-group font-size-20 d-sm-none">
                                            </i>
                                            <span class="d-none d-sm-block">
                                                Groups
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#contacts">
                                            <i class="bx bx-book-content font-size-20 d-sm-none">
                                            </i>
                                            <span class="d-none d-sm-block">
                                                Contacts
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <!-- tab Chat personel -->
                                    <div class="tab-pane show active" id="chat">
                                        <div class="chat-message-list" data-simplebar="">
                                            <div class="pt-3">
                                                <div class="px-3">
                                                    <h5 class="font-size-14 mb-3">
                                                        Recent
                                                    </h5>
                                                </div>

                                                <!-- user chat list -->
                                                <ul class="list-unstyled chat-list">
                                                    <?php foreach ($listChat as $list) : ?>
                                                        <li class="<?= $id_lawan == $list['id_lawan'] ? 'active' : '' ?>">
                                                            <a href="chat.php?chat_type=personal&id_lawan=<?= $list['id_lawan'] ?>">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="flex-shrink-0 user-img online align-self-center me-3">
                                                                        <img alt="" class="rounded-circle avatar-sm" src="assets/images/users/avatar-2.jpg" />
                                                                        <span class="user-status">
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex-grow-1 overflow-hidden">
                                                                        <h5 class="text-truncate font-size-14 mb-1">
                                                                            <?= htmlspecialchars($list['nama']) ?>
                                                                        </h5>
                                                                        <p class="text-truncate mb-0">
                                                                            <?= htmlspecialchars(mb_strimwidth($list['pesan_terakhir'], 0, 40, '...')) ?>
                                                                        </p>
                                                                    </div>
                                                                    <div class="flex-shrink-0">
                                                                        <div class="font-size-11">
                                                                            <?= date('H:i', strtotime($list['time_stamp'])) ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>

                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- tab group -->
                                    <div class="tab-pane" id="groups">
                                        <div class="chat-message-list" data-simplebar="">
                                            <div class="pt-3">
                                                <div class="px-3">
                                                    <h5 class="font-size-14 mb-3">
                                                        Groups
                                                    </h5>
                                                </div>
                                                <ul class="list-unstyled chat-list">
                                                    <?php foreach ($listGroup as $group) : ?>
                                                        <li>
                                                            <a href="chat.php?chat_type=group&id_lawan=<?= $group['id_lawan'] ?>">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0 avatar-sm me-3">
                                                                        <?php if ($group['foto']) : ?>
                                                                            <img alt="" class="avatar-sm rounded-circle" src="assets/images/groups/<?= $group['foto'] ?>" />
                                                                        <?php else : ?>
                                                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                                                P
                                                                            </span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h5 class="font-size-14 mb-0">
                                                                            <?= htmlspecialchars($group['nama']) ?>
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- tab contact -->
                                    <div class="tab-pane" id="contacts">
                                        <div class="chat-message-list" data-simplebar="">
                                            <div class="pt-3">
                                                <div class="px-3">
                                                    <h5 class="font-size-14 mb-3">
                                                        Contacts
                                                    </h5>
                                                </div>
                                                <div>
                                                    <div>
                                                        <div class="px-3 contact-list">
                                                            A
                                                        </div>
                                                        <ul class="list-unstyled chat-list">
                                                            <li>
                                                                <a href="#">
                                                                    <h5 class="font-size-14 mb-0">
                                                                        Adam Miller
                                                                    </h5>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#">
                                                                    <h5 class="font-size-14 mb-0">
                                                                        Alfonso Fisher
                                                                    </h5>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end chat-leftsidebar -->
                        <div id="userChat" class="w-100 user-chat mt-sm-0 ms-lg-1">
                            <div class="card h-100">
                                <?php if ($id_lawan > 0) : ?>
                                    <!-- header profil chat -->
                                    <div class="p-3 border-bottom">
                                        <div class="row align-items-center">

                                            <div class="col">

                                                <div class="d-flex align-items-center">

                                                    <img
                                                        src="assets/images/groups/<?= htmlspecialchars($roomInfo['foto']) ?>"
                                                        class="rounded-circle avatar-sm me-3">

                                                    <div>

                                                        <h5 class="mb-1">
                                                            <?= htmlspecialchars($roomInfo['nama']) ?>
                                                        </h5>

                                                        <small class="text-muted">
                                                            <?= htmlspecialchars($roomInfo['subtitle']) ?>
                                                        </small>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <!-- isi chat -->
                                    <div class="chat-conversation p-3 px-2" id="chatBody" data-simplebar="">
                                        <ul class="list-unstyled mb-0" id="chatMessages">

                                        </ul>
                                    </div>

                                    <!-- form input chat -->
                                    <div class="p-3 border-top">
                                        <div class="row">
                                            <form id="formChat">
                                                <input type="hidden" name="action" value="send_message">

                                                <input
                                                    type="hidden"
                                                    name="chat_type"
                                                    value="<?= htmlspecialchars($chatType) ?>">

                                                <input
                                                    type="hidden"
                                                    name="target_id"
                                                    value="<?= $id_lawan ?>">

                                                <input
                                                    type="hidden"
                                                    name="login_id"
                                                    value="<?= $id_login ?>">
                                                <div class="col">
                                                    <div class="position-relative">
                                                        <input
                                                            class="form-control border bg-light-subtle"
                                                            name="message"
                                                            id="messageInput"
                                                            placeholder="Enter Message..."
                                                            type="text">
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <button class="btn btn-primary chat-send w-md waves-effect waves-light" id="btnKirim" type="submit">
                                                        <span class="d-none d-sm-inline-block me-2" id="btnText">
                                                            Send
                                                        </span>
                                                        <i class="mdi mdi-send float-end" id="btnIcon">
                                                        </i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="d-flex h-100 align-items-center justify-content-center text-center">

                                        <div>
                                            <i class="bx bx-message-rounded-dots display-3 text-muted"></i>

                                            <h5 class="mt-3">
                                                Belum ada percakapan yang dipilih
                                            </h5>

                                            <p class="text-muted mb-0">
                                                Pilih salah satu kontak di sebelah kiri untuk mulai mengobrol.
                                            </p>
                                        </div>

                                    </div>

                                <?php endif; ?>
                            </div>
                            <!-- <div class="card"></div> -->
                        </div>
                        <!-- end user chat -->
                    </div>
                    <!-- End d-lg-flex  -->
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <?php include("src/include/footer.php"); ?>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <!-- Right Sidebar -->
    <?php include("src/include/right-sidebar.php"); ?>
    <!-- /Right-bar -->
    <!-- javascript -->
    <?php include("src/include/script.php"); ?>
    <!-- end javascript -->

    <!-- script chat -->
    <script>
        const Chat = {

            lastId: 0,
            isLoading: false,

            form: null,
            btn: null,
            btnText: null,
            btnIcon: null,
            input: null,

            chatList: null,
            chatBody: null,

            info: {},
            polling: null,

            init() {

                this.form = document.getElementById("formChat");
                this.btn = document.getElementById("btnKirim");
                this.btnText = document.getElementById("btnText");
                this.btnIcon = document.getElementById("btnIcon");
                this.input = document.getElementById("messageInput");

                this.chatList = document.getElementById("chatMessages");
                this.chatBody = document.getElementById("chatBody");

                this.info = this.getInfo();

                this.bindEvents();

                this.loadMessages();

                this.polling = setInterval(() => {

                    this.loadMessages();

                }, 2000);

            },

            getInfo() {

                const login = document.querySelector('[name="login_id"]');
                const chatType = document.querySelector('[name="chat_type"]');
                const target = document.querySelector('[name="target_id"]');

                return {

                    loginId: Number(login.value),
                    chatType: chatType.value,
                    targetId: Number(target.value)

                };

            },

            async loadMessages() {
                const formData = new FormData();

                const before = this.lastId;

                formData.append("action", "get_messages");
                formData.append("chat_type", this.info.chatType);
                formData.append("target_id", this.info.targetId);
                // formData.append("id_user", this.info.idUser);
                formData.append("last_id", this.lastId);

                if (this.isLoading) return;

                this.isLoading = true;

                try {

                    const response = await fetch("../actions/proses_chatV2.php", {
                        method: "POST",
                        body: formData
                    });

                    const result = await response.json();

                    if (result.status === "success") {

                        if (this.lastId === 0) {

                            this.renderMessages(result.data);

                        } else {

                            result.data.forEach(chat => {
                                this.appendMessage(chat);
                            });

                        }

                        if (result.data.length > 0) {
                            this.scrollBottom();
                        }

                    }

                } catch (err) {

                    console.error(err);

                } finally {

                    this.isLoading = false;

                }
            },

            scrollBottom() {

                const scrollEl = this.chatBody.querySelector('.simplebar-content-wrapper');

                if (scrollEl) {
                    scrollEl.scrollTop = scrollEl.scrollHeight;
                }

            },

            appendMessage(chat) {

                const li = document.createElement("li");

                if (chat.sender_id == this.info.loginId) {

                    li.className = "right";

                }

                const waktu = chat.time_stamp.substring(11, 16);

                li.innerHTML = `
        <div class="conversation-list">
            <div class="ctext-wrap">
                <div class="ctext-wrap-content">
                    <h5 class="conversation-name">
                        <span class="time">${waktu}</span>
                    </h5>

                    <p class="mb-0"></p>

                </div>
            </div>
        </div>
    `;

                li.querySelector("p").textContent = chat.chat;

                this.chatList.appendChild(li);

                this.lastId = chat.id;

            },

            renderMessages(data) {

                this.chatList.innerHTML = "";

                data.forEach(chat => {
                    this.appendMessage(chat);
                });

                // Setelah semua bubble selesai dibuat
                this.scrollBottom();

            },

            bindEvents() {

                this.form.addEventListener(
                    "submit",
                    (e) => this.sendMessage(e)
                );

            },

            async sendMessage(e) {

                e.preventDefault();

                // Jangan kirim jika pesan hanya berisi spasi
                if (this.input.value.trim() === '') {
                    return;
                }

                // Disable tombol sementara
                this.btn.disabled = true;

                this.btnText.textContent = "Mengirim...";
                this.btnIcon.className = "spinner-border spinner-border-sm";

                // Ambil semua data dari form
                const formData = new FormData(this.form);

                try {

                    const response = await fetch(
                        '../actions/proses_chatV2.php', {
                            method: 'POST',
                            body: formData
                        }
                    );

                    const result = await response.json();

                    if (result.status === 'success') {

                        this.input.value = '';
                        this.input.focus();

                        await this.loadMessages();
                        this.scrollBottom();
                    } else {

                        alert('Error: ' + result.message);

                    }

                } catch (error) {

                    alert('Terjadi kesalahan koneksi jaringan.');

                    console.error(error);

                } finally {

                    this.btn.disabled = false;
                    this.btnText.textContent = "Send";
                    this.btnIcon.className = "mdi mdi-send float-end";

                }

            },

        };

        document.addEventListener("DOMContentLoaded", () => {

            Chat.init();

        });
    </script>
</body>

</html>