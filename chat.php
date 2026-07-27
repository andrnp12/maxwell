<?php
session_start();
include 'src/include/header.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
//     header("Location: login.php");
//     exit;
// }

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

echo "Role Saya: " . $role . "<br>";
echo "ID User di Query: " . $id_user . "<br>";
echo "ID Konsultan di Query: " . $id_konsultan . "<br>";

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

<style>
    body {
        background-color: #f5f7fb;
    }

    .chat-container {
        height: calc(100vh - 40px);
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
    }

    /* =========================
           SIDEBAR
        ========================= */

    .chat-sidebar {
        height: 100%;
        border-right: 1px solid #e9ecef;
    }

    .sidebar-header {
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .consultant-list {
        height: calc(100% - 145px);
        overflow-y: auto;
    }

    .consultant-item {
        padding: 14px 18px;
        cursor: pointer;
        border-bottom: 1px solid #f1f1f1;
        transition: 0.2s;
    }

    .consultant-item:hover {
        background-color: #f8f9fa;
    }

    .consultant-item.active {
        background-color: #eef5ff;
    }

    .avatar {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 50%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 50%;
        background-color: #0d6efd;
        color: #fff;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 20px;
        font-weight: 600;
    }

    .online-dot {
        width: 10px;
        height: 10px;
        background-color: #198754;
        border-radius: 50%;
        display: inline-block;
    }

    /* =========================
           CHAT AREA
        ========================= */

    .chat-area {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .chat-header {
        padding: 14px 20px;
        border-bottom: 1px solid #e9ecef;
        background-color: #fff;
    }

    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 25px;
        background-color: #f8f9fa;
    }

    /* =========================
           MESSAGE
        ========================= */

    .message-row {
        display: flex;
        margin-bottom: 18px;
    }

    .message-row.user {
        justify-content: flex-end;
    }

    .message-row.consultant {
        justify-content: flex-start;
    }

    .message-bubble {
        max-width: 70%;
        padding: 10px 14px;
        border-radius: 16px;
        position: relative;
    }

    .message-row.user .message-bubble {
        background-color: #0d6efd;
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message-row.consultant .message-bubble {
        background-color: white;
        color: #212529;
        border: 1px solid #e9ecef;
        border-bottom-left-radius: 4px;
    }

    .message-time {
        display: block;
        font-size: 11px;
        margin-top: 5px;
        opacity: 0.7;
        text-align: right;
    }

    /* =========================
           CHAT INPUT
        ========================= */

    .chat-footer {
        padding: 15px 20px;
        border-top: 1px solid #e9ecef;
        background-color: #fff;
    }

    .message-input {
        border-radius: 25px;
        padding-left: 18px;
    }

    .send-button {
        width: 45px;
        height: 45px;
        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* =========================
           MOBILE
        ========================= */

    @media (max-width: 767.98px) {
        body {
            background-color: #fff;
        }

        .page-wrapper {
            padding: 0 !important;
        }

        .chat-container {
            height: 100vh;
            border-radius: 0;
        }

        .chat-sidebar {
            display: none;
        }

        .message-bubble {
            max-width: 85%;
        }
    }
</style>

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php include 'src/include/topbar.php'; ?>
        <!-- ========== Left Sidebar Start ========== -->
        <?php include 'src/include/sidebar.php'; ?>
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid p-3 p-lg-4 page-wrapper">

                    <div class="chat-container shadow-sm">

                        <div class="row g-0 h-100">

                            <!-- ========================================
                 SIDEBAR KONSULTAN
            ========================================= -->
                            <div class="col-md-4 col-lg-3 chat-sidebar">

                                <div class="sidebar-header">

                                    <h5 class="fw-bold mb-3">
                                        <i class="bi bi-chat-dots me-2"></i>
                                        Konsultasi
                                    </h5>

                                    <div class="input-group">

                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-search"></i>
                                        </span>

                                        <input
                                            type="text"
                                            class="form-control bg-light border-start-0"
                                            placeholder="Cari konsultan...">

                                    </div>

                                </div>


                                <!-- LIST KONSULTAN -->
                                <div class="consultant-list">
                                    <?php foreach ($listChat as $list) : ?>
                                        <a href="chat.php?id_lawan=<?= $list['id_lawan'] ?>">
                                            <div class="consultant-item">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="position-relative">
                                                        <div class="avatar-placeholder">
                                                            SN
                                                        </div>
                                                        <span
                                                            class="online-dot position-absolute bottom-0 end-0 border border-2 border-white"></span>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <div class="d-flex justify-content-between">
                                                            <h6 class="mb-1 text-truncate">
                                                                Siti Nurhaliza
                                                            </h6>
                                                            <small class="text-muted">
                                                                Kemarin
                                                            </small>
                                                        </div>
                                                        <p class="text-muted small mb-0 text-truncate">
                                                            Terima kasih sudah berkonsultasi.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>


                            <!-- ========================================
                 AREA CHAT
            ========================================= -->
                            <div class="col-md-8 col-lg-9">

                                <div class="chat-area">


                                    <!-- HEADER -->
                                    <div class="chat-header">

                                        <div class="d-flex align-items-center">

                                            <div class="position-relative me-3">

                                                <div class="avatar-placeholder">
                                                    AS
                                                </div>

                                                <span
                                                    class="online-dot position-absolute bottom-0 end-0 border border-2 border-white"></span>

                                            </div>

                                            <div>

                                                <h6 class="mb-0 fw-bold">
                                                    Andi Saputra
                                                </h6>
                                                <small class="text-success">
                                                    <span class="online-dot me-1"></span>
                                                    Online
                                                </small>
                                            </div>
                                            <div class="ms-auto">
                                                <button
                                                    class="btn btn-light rounded-circle"
                                                    type="button">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ========================================
                         ISI CHAT
                    ========================================= -->
                                    <div class="chat-body" id="chatBody">
                                        <!-- Tanggal -->
                                        <div class="text-center mb-4">
                                            <span class="badge text-bg-light border text-muted">
                                                Hari ini
                                            </span>
                                        </div>
                                        <?php foreach ($dataChat as $pesan) : ?>
                                            <?php
                                            // Format waktu dari database (contoh: 2026-07-26 10:30:00 menjadi 10:30)
                                            $waktu = date('H:i', strtotime($pesan['time_stamp']));
                                            $isi_chat = htmlspecialchars($pesan['chat']);
                                            ?>

                                            <?php if ($pesan['pengirim'] == $role) : ?>
                                                <!-- JIKA PESAN SAYA (Tampil di Kanan / pakai class 'user' bawaanmu) -->
                                                <div class="message-row user">
                                                    <div class="message-bubble">
                                                        <?= $isi_chat ?>
                                                        <span class="message-time">
                                                            <?= $waktu ?>
                                                            <i class="bi bi-check2-all ms-1"></i>
                                                        </span>
                                                    </div>
                                                </div>

                                            <?php else : ?>
                                                <!-- JIKA PESAN LAWAN BICARA (Tampil di Kiri / pakai class 'consultant' bawaanmu) -->
                                                <div class="message-row consultant">
                                                    <div class="message-bubble">
                                                        <?= $isi_chat ?>
                                                        <span class="message-time">
                                                            <?= $waktu ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                        <?php endforeach; ?>
                                    </div>


                                    <!-- ========================================
                         INPUT PESAN
                    ========================================= -->
                                    <div class="chat-footer">

                                        <form id="formChat">

                                            <!-- ID konsultan yang sedang diajak chat -->
                                            <input
                                                type="hidden"
                                                name="id_lawan"
                                                id="idKonsultan"
                                                value="<?= $id_lawan ?>">

                                            <div class="d-flex align-items-center gap-2">

                                                <button
                                                    class="btn btn-light rounded-circle"
                                                    type="button"
                                                    title="Lampiran">
                                                    <i class="bi bi-paperclip"></i>
                                                </button>

                                                <input
                                                    type="text"
                                                    class="form-control message-input"
                                                    name="isi_chat"
                                                    id="messageInput"
                                                    placeholder="Ketik pesan..."
                                                    autocomplete="off"
                                                    required>

                                                <button
                                                    class="btn btn-primary send-button"
                                                    id="btnKirim"
                                                    type="submit"
                                                    title="Kirim">
                                                    <i class="bi bi-send-fill"></i>
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <?php include 'src/include/footer.php'; ?>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <!-- Right Sidebar -->
    <?php include 'src/include/right-sidebar.php'; ?>
    <!-- /Right-bar -->
    <!-- Right bar overlay-->
    <div class="rightbar-overlay">
    </div>
    <!-- JAVASCRIPT -->
    <?php include 'src/include/script.php'; ?>

    <!-- script -->
    <script>
        function tambahPesanUser(chat) {

            const chatBody = document.getElementById('chatBody');

            const messageRow = document.createElement('div');

            messageRow.className = 'message-row user';

            const sekarang = new Date();

            const jam = sekarang.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });

            messageRow.innerHTML = `
        <div class="message-bubble">

            <span class="message-text"></span>

            <span class="message-time">
                ${jam}
                <i class="bi bi-check2 ms-1"></i>
            </span>

        </div>
    `;

            // Gunakan textContent agar isi pesan tidak dianggap HTML
            messageRow.querySelector('.message-text').textContent =
                chat.chat;

            chatBody.appendChild(messageRow);

            // Scroll otomatis ke bawah
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        const formChat = document.getElementById('formChat');
        const btnKirim = document.getElementById('btnKirim');
        const messageInput = document.getElementById('messageInput');

        formChat.addEventListener('submit', async function(e) {

            e.preventDefault();

            // Jangan kirim jika pesan hanya berisi spasi
            if (messageInput.value.trim() === '') {
                return;
            }

            // Disable tombol sementara
            btnKirim.disabled = true;

            // Simpan icon awal
            const originalButton = btnKirim.innerHTML;

            // Tampilkan loading
            btnKirim.innerHTML = `
        <span
            class="spinner-border spinner-border-sm"
            role="status"
        ></span>
    `;

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

                    tambahPesanUser(result.data);

                    // Kosongkan input
                    messageInput.value = '';

                    // Fokus kembali ke input
                    messageInput.focus();

                    // console.log(result.message);

                } else {

                    alert('Error: ' + result.message);

                }

            } catch (error) {

                alert('Terjadi kesalahan koneksi jaringan.');

                console.error(error);

            } finally {

                btnKirim.disabled = false;

                btnKirim.innerHTML = originalButton;

            }

        });

        // Fungsi untuk menarik chat terbaru
        async function tarikChatTerbaru() {
            const idLawan = document.querySelector('input[name="id_lawan"]').value;

            try {
                // Arahkan kembali ke proses_chat.php (kali ini Javascript akan mengirim GET request secara default)
                const response = await fetch('actions/proses_chat.php?id_lawan=' + idLawan);
                const html = await response.text();

                const chatBody = document.getElementById('chatBody');
                let isScrolledToBottom = chatBody.scrollHeight - chatBody.clientHeight <= chatBody.scrollTop + 50;

                chatBody.innerHTML = html;

                if (isScrolledToBottom) {
                    chatBody.scrollTop = chatBody.scrollHeight;
                }
            } catch (error) {
                console.error("Gagal menarik pesan terbaru", error);
            }
        }

        // Tarik data pertama kali saat halaman dibuka
        tarikChatTerbaru();

        // Jalankan penarikan data secara otomatis setiap 2 detik (2000 ms)
        setInterval(tarikChatTerbaru, 2000);
    </script>
</body>

</html>