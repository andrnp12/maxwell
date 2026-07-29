<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];
$id_login = $_SESSION['id'];
$id_lawan = isset($_GET['id_lawan']) ? (int)$_GET['id_lawan'] : 0;

if ($role == 'user') {
    $id_user = $id_login;
    $id_konsultan = $id_lawan;
} else {
    $id_user = $id_lawan;
    $id_konsultan = $id_login;
}

require_once 'classes/chat.php';

$objChat = new chat();
$listChat = $objChat->getListChat($id_login, $role);

$dataChat = [];
if ($id_lawan !== 0) {
    if ($role == 'user') {
        $id_user = $id_login;
        $id_konsultan = $id_lawan;
    } else {
        $id_user = $id_lawan;
        $id_konsultan = $id_login;
    }
    $dataChat = $objChat->getAllMessage($id_user, $id_konsultan);
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
                                    <!-- tab Chat -->
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
                                                            <a href="chat.php?id_lawan=<?= $list['id_lawan'] ?>">
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
                                                    <li>
                                                        <a href="#">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0 avatar-sm me-3">
                                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                                        G
                                                                    </span>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h5 class="font-size-14 mb-0">
                                                                        General
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0 avatar-sm me-3">
                                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                                        R
                                                                    </span>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h5 class="font-size-14 mb-0">
                                                                        Reporting
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
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
                                    <div class="p-3 px-lg-4 border-bottom">
                                        <div class="row">
                                            <div class="col-xl-4 col-7">
                                                <div class="d-flex align-items-center">
                                                    <button type="button" id="btnBackChat" class="btn btn-light btn-sm d-lg-none me-2"><i class="bx bx-arrow-back"></i></button>
                                                    <div class="flex-shrink-0 avatar-sm me-3 d-sm-block d-none">
                                                        <img alt="" class="img-fluid d-block rounded-circle" src="assets/images/users/avatar-2.jpg" />
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h5 class="font-size-14 mb-1 text-truncate">
                                                            <a class="text-dark" href="#">
                                                                Jennie Sherlock
                                                            </a>
                                                        </h5>
                                                        <p class="text-muted text-truncate mb-0">
                                                            Online
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-8 col-5">
                                                <ul class="list-inline user-chat-nav text-end mb-0">
                                                    <li class="list-inline-item">
                                                        <div class="dropdown">
                                                            <button aria-expanded="false" aria-haspopup="true" class="btn nav-btn dropdown-toggle" data-bs-toggle="dropdown" type="button">
                                                                <i class="bx bx-search">
                                                                </i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-2">
                                                                <form class="px-2">
                                                                    <div>
                                                                        <input class="form-control border bg-light-subtle" placeholder="Search..." type="text" />
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <div class="dropdown">
                                                            <button aria-expanded="false" aria-haspopup="true" class="btn nav-btn dropdown-toggle" data-bs-toggle="dropdown" type="button">
                                                                <i class="bx bx-dots-horizontal-rounded">
                                                                </i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">
                                                                    Profile
                                                                </a>
                                                                <a class="dropdown-item" href="#">
                                                                    Archive
                                                                </a>
                                                                <a class="dropdown-item" href="#">
                                                                    Muted
                                                                </a>
                                                                <a class="dropdown-item" href="#">
                                                                    Delete
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
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
                                                <input
                                                    type="hidden"
                                                    name="id_lawan"
                                                    id="idKonsultan"
                                                    value="<?= $id_lawan ?>">
                                                <div class="col">
                                                    <div class="position-relative">
                                                        <input class="form-control border bg-light-subtle" name="isi_chat" id="messageInput" placeholder="Enter Message..." type="text" />
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


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("chatSidebar");
            const chat = document.getElementById("userChat");
            const back = document.getElementById("btnBackChat");

            function mobile() {
                return window.innerWidth < 992;
            }

            function init() {
                const params = new URLSearchParams(window.location.search);
                const hasChat = params.has("id_lawan");

                if (mobile()) {
                    if (hasChat) {
                        sidebar.classList.add("mobile-hide");
                        chat.classList.remove("mobile-hide");
                    } else {
                        sidebar.classList.remove("mobile-hide");
                        chat.classList.add("mobile-hide");
                    }
                } else {
                    sidebar.classList.remove("mobile-hide");
                    chat.classList.remove("mobile-hide");
                }
            }

            init();
            document.querySelectorAll(".chat-list li").forEach(function(li) {
                li.addEventListener("click", function() {
                    if (!mobile()) return;
                    sidebar.classList.add("mobile-hide");
                    chat.classList.remove("mobile-hide");
                });
            });
            if (back) {
                back.addEventListener("click", function() {
                    sidebar.classList.remove("mobile-hide");
                    chat.classList.add("mobile-hide");
                });
            }
            window.addEventListener("resize", init);
        });

        let lastId = 0

        function appendChat(chat) {
            console.log(chat);
            const chatList = document.getElementById("chatMessages");

            const li = document.createElement("li");

            if (chat.pengirim === "<?= $role ?>") {
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

            chatList.appendChild(li);

            lastId = chat.id;

        }

        function renderChat(data) {

            const chatList = document.getElementById("chatMessages");

            chatList.innerHTML = "";

            data.forEach(chat => {

                appendChat(chat);

            });

        }

        const formChat = document.getElementById('formChat');
        const btnKirim = document.getElementById('btnKirim');
        const btnText = document.getElementById('btnText');
        const btnIcon = document.getElementById('btnIcon');
        const messageInput = document.getElementById('messageInput');

        formChat.addEventListener('submit', async function(e) {

            e.preventDefault();

            // Jangan kirim jika pesan hanya berisi spasi
            if (messageInput.value.trim() === '') {
                return;
            }

            // Disable tombol sementara
            btnKirim.disabled = true;

            btnText.textContent = "Mengirim...";
            btnIcon.className = "spinner-border spinner-border-sm";

            // Ambil semua data dari form
            const formData = new FormData(formChat);

            try {

                const response = await fetch(
                    '../actions/proses_chat.php', {
                        method: 'POST',
                        body: formData
                    }
                );

                const result = await response.json();

                if (result.status === 'success') {

                    messageInput.value = '';
                    messageInput.focus();

                    await tarikChatTerbaru();
                    scrollKeBawah();
                } else {

                    alert('Error: ' + result.message);

                }

            } catch (error) {

                alert('Terjadi kesalahan koneksi jaringan.');

                console.error(error);

            } finally {

                btnKirim.disabled = false;
                btnText.textContent = "Send";
                btnIcon.className = "mdi mdi-send float-end";

            }

        });

        function scrollKeBawah() {
            const chatBody = document.getElementById('chatBody');

            const scrollElement = chatBody.querySelector(
                '.simplebar-content-wrapper'
            );

            if (scrollElement) {
                scrollElement.scrollTop = scrollElement.scrollHeight;
            }
        }

        // Fungsi untuk menarik chat terbaru

        async function tarikChatTerbaru() {

            try {

                const idLawan = document.querySelector(
                    'input[name="id_lawan"]'
                ).value;

                const response = await fetch(
                    '../actions/proses_chat.php?id_lawan=' +
                    idLawan +
                    '&last_id=' + lastId
                )

                const result = await response.json();


                const chatBody = document.getElementById('chatBody');

                const scrollElement = chatBody.querySelector(
                    '.simplebar-content-wrapper'
                );

                const isNearBottom =
                    scrollElement.scrollHeight -
                    scrollElement.scrollTop -
                    scrollElement.clientHeight < 50;

                if (lastId === 0) {

                    renderChat(result.data);

                    scrollKeBawah();

                } else {

                    const adaPesanBaru = result.data.length > 0;

                    result.data.forEach(chat => {
                        appendChat(chat);
                    });

                    if (adaPesanBaru && isNearBottom) {
                        scrollKeBawah();
                    }

                }

            } catch (error) {

                console.error(error);

            }

        }

        // Tarik data pertama kali saat halaman dibuka
        tarikChatTerbaru();

        // Jalankan penarikan data secara otomatis setiap 2 detik (2000 ms)
        setInterval(tarikChatTerbaru, 2000);
    </script>
</body>

</html>