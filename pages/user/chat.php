<?php
require_once '../../src/classes/auth.php';
$auth = new auth();
$auth->authOrNot();

$role      = $_SESSION['role'];
$id_login  = $_SESSION['id'];

$chatType  = $_GET['chat_type'] ?? 'personal';
$id_lawan  = isset($_GET['id_lawan'])
    ? (int) $_GET['id_lawan']
    : 0;

require_once '../../src/classes/ChatV2.php';

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
<?php include('../include/header.php'); ?>
<!--headere end-->

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- ========== Topbar Start ========== -->
        <?php include('../include/topbar.php'); ?>
        <!-- ========== Topbar End ========== -->
        <!-- ========== Left Sidebar Start ========== -->
        <?php include('../include/sidebar.php'); ?>
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
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
                                                            <!-- <a href="chat.php?chat_type=personal&id_lawan=<?= $list['id_lawan'] ?>"> -->
                                                            <a href="#"
                                                                class="chat-user"
                                                                data-id="<?= $list['id_lawan'] ?>"
                                                                data-type="personal">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="flex-shrink-0 user-img online align-self-center me-3">
                                                                        <img alt="" class="rounded-circle avatar-sm" src="assets/images/users/avatar-2.jpg" />
                                                                        <span class="user-status">
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex-grow-1 overflow-hidden">
                                                                        <h5 class="text-truncate font-size-14 mb-1">
                                                                            <?= htmlspecialchars($list['name']) ?>
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
                                                            <a href="#"
                                                                class="chat-user"
                                                                data-id="<?= $group['id_lawan'] ?>"
                                                                data-type="group">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0 avatar-sm me-3">
                                                                        <?php if ($group['foto']) : ?>
                                                                            <img alt="" class="avatar-sm rounded-circle" src="../../uploads/komunitas/<?= $group['foto'] ?>" />
                                                                        <?php else : ?>
                                                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                                                P
                                                                            </span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h5 class="font-size-14 mb-0">
                                                                            <?= htmlspecialchars($group['name']) ?>
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
                                </div>
                            </div>
                        </div>
                        <!-- end chat-leftsidebar -->
                        <div id="userChat" class="w-100 user-chat mt-sm-0 ms-lg-1">

                            <div class="card h-100">

                                <div id="chatHeader">

                                    <div class="text-center p-4 text-muted">

                                        Pilih percakapan

                                    </div>

                                </div>

                                <div
                                    id="chatBody"
                                    class="chat-conversation p-3 px-2"
                                    data-simplebar>

                                    <ul
                                        id="chatMessages"
                                        class="list-unstyled mb-0">
                                    </ul>

                                </div>

                                <div id="chatFooter"></div>

                            </div>

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
        <?php include("../include/footer.php"); ?>
    </div>
    <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <!-- Right Sidebar -->
    <?php include("../include/right-sidebar.php"); ?>
    <!-- /Right-bar -->
    <!-- javascript -->
    <?php include("../include/script.php"); ?>
    <!-- end javascript -->

    <!-- script chat -->
    <script>
        const LOGIN_ID = <?= (int)$id_login ?>;

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


                this.info = {

                    loginId: LOGIN_ID,
                    chatType: null,
                    targetId: null

                };

                this.polling = null;

                // this.polling = setInterval(() => {
                //     this.loadMessages();
                // }, 2000);

            },

            getInfo() {

                return {

                    loginId: Number(
                        document.querySelector('[name="login_id"]').value
                    ),

                    chatType: null,
                    targetId: null

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

                    const response = await fetch("/src/actions/proses_chatV2.php", {
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
            async loadRoom() {

                if (!this.info.targetId) {

                    return;

                }

                const formData = new FormData();

                formData.append("action", "open_room");
                formData.append("chat_type", this.info.chatType);
                formData.append("target_id", this.info.targetId);

                const response = await fetch(
                    "/src/actions/proses_chatV2.php", {
                        method: "POST",
                        body: formData
                    }
                );

                const result = await response.json();

                if (result.status != "success") {

                    throw new Error(result.message);

                }

                return result.data;

            },

            async openRoom(id, type) {

                this.lastId = 0;

                this.info.targetId = id;
                this.info.chatType = type;

                const room = await this.loadRoom();

                console.log(room);

                this.renderHeader(room);
                this.renderFooter();

                this.chatList = document.getElementById("chatMessages");

                await this.loadMessages();

                if (this.polling) {
                    clearInterval(this.polling);
                }

                this.polling = setInterval(() => {
                    this.loadMessages();
                }, 2000);
            },

            scrollBottom() {

                if (!this.chatBody) return;

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
                    <h5 class="conversation-name gap-3">
                        <span class="user-name">${chat.name}</span>
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

            renderHeader(room) {

                document.getElementById("chatHeader").innerHTML = ` 
                <div class="p-3 px-lg-4 border-bottom bg-white"> 
                <div class="d-flex align-items-center"> 
                <!-- Tombol Kembali --> 
                <div class="flex-shrink-0 me-3 ms-lg-0 ms-xl-3"> 
                <a href="chat.php" class="btn btn-light rounded-circle d-flex align-items-center justify-content-center shadow-sm chat-back-btn" aria-label="Kembali">
                 <i class="bx bx-left-arrow-alt fs-3"></i> </a> 
                 </div> <!-- Foto Group --> <div class="flex-shrink-0 avatar-sm me-3"> 
                 <img src="../../uploads/komunitas/${room.foto}" class="rounded-circle avatar-sm" alt="Group Foto"> </div> 
                 <!-- Informasi Group --> 
                 <div class="overflow-hidden"> 
                 <h5 class="mb-1 text-truncate">${room.name}</h5> 
                 <p class="text-muted mb-0 text-truncate"> ${room.subtitle} </p>
                  </div> </div> </div> 
                  <style> 
                  .chat-back-btn { width: 42px; height: 42px; padding: 0; border: 1px solid #e9ecef; transition: all 0.2s ease; } 
                  .chat-back-btn i { font-size: 22px; transition: transform 0.2s ease; } 
                  .chat-back-btn:hover { background-color: #f1f3f5; border-color: #dee2e6; transform: translateX(-2px); } 
                  .chat-back-btn:hover i { transform: translateX(-2px); } .chat-back-btn:active { transform: scale(0.94); } 
                  </style> `;

            },

            renderFooter() {

                document.getElementById("chatFooter").innerHTML = `

<div class="p-3 border-top">

<form id="formChat">

<input
type="hidden"
name="action"
value="send_message">

<input
type="hidden"
name="chat_type"
value="${this.info.chatType}">

<input
type="hidden"
name="target_id"
value="${this.info.targetId}">

<input
type="hidden"
name="login_id"
value="${this.info.loginId}">

<div class="row">

<div class="col">

<input

id="messageInput"

name="message"

class="form-control"

placeholder="Tulis pesan">

</div>

<div class="col-auto">

<button

id="btnKirim"

class="btn btn-primary">

Send

</button>

</div>

</div>

</form>

</div>

`;

                this.form = document.getElementById("formChat");
                this.input = document.getElementById("messageInput");
                this.btn = document.getElementById("btnKirim");

                this.bindEvents();

            },

            renderMessages(data) {

                this.chatList.innerHTML = "";

                data.forEach(chat => {
                    this.appendMessage(chat);
                });

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        this.scrollBottom();
                    });
                });

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

                this.btn.innerHTML = `
    <span class="spinner-border spinner-border-sm me-2"></span>
    Mengirim...
`;

                // Ambil semua data dari form
                const formData = new FormData(this.form);

                try {

                    const response = await fetch(
                        '/src/actions/proses_chatV2.php', {
                            method: 'POST',
                            body: formData
                        }
                    );

                    const result = await response.json();

                    if (result.status === 'success') {

                        this.input.value = '';
                        this.input.focus();

                        // await this.loadMessages();
                        this.scrollBottom();
                    } else {

                        alert('Error: ' + result.message);

                    }

                } catch (error) {

                    alert('Terjadi kesalahan koneksi jaringan.');

                    console.error(error);

                } finally {

                    this.btn.disabled = false;
                    this.btn.innerHTML = "Send";

                }

            },

        };

        // document.addEventListener("DOMContentLoaded", () => {

        //     Chat.init();

        // });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", async () => {

            const sidebar = document.getElementById("chatSidebar");
            const chat = document.getElementById("userChat");
            const back = document.getElementById("btnBackChat");

            const mobileQuery = window.matchMedia("(max-width: 991.98px)");

            function isMobile() {
                return mobileQuery.matches;
            }

            function toggleLayout() {

                if (isMobile()) {

                    // Hanya atur kondisi awal mobile
                    if (!chat.dataset.roomOpen) {
                        chat.classList.add("mobile-hide");
                        sidebar.classList.remove("mobile-hide");
                    }

                } else {

                    chat.classList.remove("mobile-hide");
                    sidebar.classList.remove("mobile-hide");

                }
            }

            async function openChat(id, type) {

                await Chat.openRoom(id, type);

                if (isMobile()) {

                    sidebar.classList.add("mobile-hide");
                    chat.classList.remove("mobile-hide");

                    // Tandai bahwa room sedang terbuka
                    chat.dataset.roomOpen = "true";
                }
            }

            // Inisialisasi tampilan
            toggleLayout();

            // Inisialisasi Chat
            Chat.init();

            // Auto open room jika berasal dari halaman lain
            const params = new URLSearchParams(window.location.search);

            const id = params.get("id");
            const type = params.get("type");

            if (id && type) {

                await openChat(id, type);

                history.replaceState({}, "", "chat.php");
            }

            // Klik room dari sidebar
            document.querySelectorAll(".chat-user").forEach(item => {

                item.addEventListener("click", async (e) => {

                    e.preventDefault();

                    await openChat(
                        item.dataset.id,
                        item.dataset.type
                    );

                });

            });

            // Tombol kembali
            if (back) {

                back.addEventListener("click", () => {

                    sidebar.classList.remove("mobile-hide");
                    chat.classList.add("mobile-hide");

                    // Tandai room sudah ditutup
                    delete chat.dataset.roomOpen;

                });

            }

            // Responsive hanya ketika breakpoint berubah
            mobileQuery.addEventListener("change", () => {

                if (isMobile()) {

                    // Jangan tutup room yang sedang terbuka
                    if (!chat.dataset.roomOpen) {
                        chat.classList.add("mobile-hide");
                        sidebar.classList.remove("mobile-hide");
                    }

                } else {

                    chat.classList.remove("mobile-hide");
                    sidebar.classList.remove("mobile-hide");

                }

            });

        });
    </script>
</body>

</html>