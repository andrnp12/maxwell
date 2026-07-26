<?php
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
   header('Location: login.php');
   exit;
}

require_once __DIR__ . '/classes/tests.php';

$testManager = new tests();
$userId = $_SESSION['id'];

$idAktif = $_SESSION['id'];
$sudahPreTest = $testManager->hasUserTakenTest($userId, $idAktif, 'pre');
$sudahPostTest = $testManager->hasUserTakenTest($userId, $idAktif, 'post');

if (!$sudahPreTest) {
   header('Location: preposttest.php?type=pre');
   exit;
}

global $sudahPreTest, $sudahPostTest;
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
                           <div class="tab-pane show active" id="chat">
                              <div class="chat-message-list" data-simplebar="">
                                 <div class="pt-3">
                                    <div class="px-3">
                                       <h5 class="font-size-14 mb-3">
                                          Recent
                                       </h5>
                                    </div>
                                    <ul class="list-unstyled chat-list">
                                       <li class="active">
                                          <a href="#">
                                             <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0 user-img online align-self-center me-3">
                                                   <img alt="" class="rounded-circle avatar-sm" src="assets/images/users/avatar-2.jpg" />
                                                   <span class="user-status">
                                                   </span>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                   <h5 class="text-truncate font-size-14 mb-1">
                                                      Jennie Sherlock
                                                   </h5>
                                                   <p class="text-truncate mb-0">
                                                      Hey! there I'm available
                                                   </p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                   <div class="font-size-11">
                                                      02 min
                                                   </div>
                                                </div>
                                             </div>
                                          </a>
                                       </li>
                                       <li class="unread">
                                          <a href="#">
                                             <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0 user-img online align-self-center me-3">
                                                   <div class="avatar-sm align-self-center">
                                                      <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                         S
                                                      </span>
                                                   </div>
                                                   <span class="user-status">
                                                   </span>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                   <h5 class="text-truncate font-size-14 mb-1">
                                                      Stacie Dube
                                                   </h5>
                                                   <p class="text-truncate mb-0">
                                                      I've finished it! See you so
                                                   </p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                   <div class="font-size-11">
                                                      10 min
                                                   </div>
                                                </div>
                                                <div class="unread-message">
                                                   <span class="badge bg-danger rounded-pill">
                                                      1
                                                   </span>
                                                </div>
                                             </div>
                                          </a>
                                       </li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
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
                     <div class="card">
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
                        <div class="chat-conversation p-3 px-2" data-simplebar="">
                           <ul class="list-unstyled mb-0">
                              <li class="chat-day-title">
                                 <span class="title">
                                    Today
                                 </span>
                              </li>
                              <li>
                                 <div class="conversation-list">
                                    <div class="ctext-wrap">
                                       <div class="ctext-wrap-content">
                                          <h5 class="conversation-name">
                                             <a class="user-name" href="#">
                                                Jennie Sherlock
                                             </a>
                                             <span class="time">
                                                10:00
                                             </span>
                                          </h5>
                                          <p class="mb-0">
                                             Good morning !
                                          </p>
                                       </div>
                                       <div class="dropdown align-self-start">
                                          <a aria-expanded="false" aria-haspopup="true" class="dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                                             <i class="bx bx-dots-vertical-rounded">
                                             </i>
                                          </a>
                                          <div class="dropdown-menu">
                                             <a class="dropdown-item" href="#">
                                                Copy
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Save
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Forward
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Delete
                                             </a>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li class="right">
                                 <div class="conversation-list">
                                    <div class="ctext-wrap">
                                       <div class="ctext-wrap-content">
                                          <h5 class="conversation-name">
                                             <a class="user-name" href="#">
                                                Shawn
                                             </a>
                                             <span class="time">
                                                10:02
                                             </span>
                                          </h5>
                                          <p class="mb-0">
                                             Good morning
                                          </p>
                                       </div>
                                       <div class="dropdown align-self-start">
                                          <a aria-expanded="false" aria-haspopup="true" class="dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                                             <i class="bx bx-dots-vertical-rounded">
                                             </i>
                                          </a>
                                          <div class="dropdown-menu">
                                             <a class="dropdown-item" href="#">
                                                Copy
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Save
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Forward
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Delete
                                             </a>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li>
                                 <div class="conversation-list">
                                    <div class="ctext-wrap">
                                       <div class="ctext-wrap-content">
                                          <h5 class="conversation-name">
                                             <a class="user-name" href="#">
                                                Jennie Sherlock
                                             </a>
                                             <span class="time">
                                                10:04
                                             </span>
                                          </h5>
                                          <p class="mb-0">
                                             Hello!
                                          </p>
                                       </div>
                                       <div class="dropdown align-self-start">
                                          <a aria-expanded="false" aria-haspopup="true" class="dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                                             <i class="bx bx-dots-vertical-rounded">
                                             </i>
                                          </a>
                                          <div class="dropdown-menu">
                                             <a class="dropdown-item" href="#">
                                                Copy
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Save
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Forward
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Delete
                                             </a>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li class="right">
                                 <div class="conversation-list">
                                    <div class="ctext-wrap">
                                       <div class="ctext-wrap-content">
                                          <h5 class="conversation-name">
                                             <a class="user-name" href="#">
                                                Shawn
                                             </a>
                                             <span class="time">
                                                10:08
                                             </span>
                                          </h5>
                                          <p class="mb-0">
                                             Wow that's great
                                          </p>
                                       </div>
                                       <div class="dropdown align-self-start">
                                          <a aria-expanded="false" aria-haspopup="true" class="dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                                             <i class="bx bx-dots-vertical-rounded">
                                             </i>
                                          </a>
                                          <div class="dropdown-menu">
                                             <a class="dropdown-item" href="#">
                                                Copy
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Save
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Forward
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Delete
                                             </a>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                              <li>
                                 <div class="conversation-list">
                                    <div class="ctext-wrap">
                                       <div class="ctext-wrap-content">
                                          <h5 class="conversation-name">
                                             <a class="user-name" href="#">
                                                Jennie Sherlock
                                             </a>
                                             <span class="time">
                                                10:09
                                             </span>
                                          </h5>
                                          <p class="mb-0">
                                             img-1.jpg &amp; img-2.jpg images for a New Projects
                                          </p>
                                          <ul class="list-inline message-img mt-3 mb-0">
                                             <li class="list-inline-item message-img-list">
                                                <a class="d-inline-block m-1" href="">
                                                   <img alt="" class="rounded img-thumbnail" src="assets/images/small/img-1.jpg" />
                                                </a>
                                             </li>
                                             <li class="list-inline-item message-img-list">
                                                <a class="d-inline-block m-1" href="">
                                                   <img alt="" class="rounded img-thumbnail" src="assets/images/small/img-2.jpg" />
                                                </a>
                                             </li>
                                          </ul>
                                       </div>
                                       <div class="dropdown align-self-start">
                                          <a aria-expanded="false" aria-haspopup="true" class="dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                                             <i class="bx bx-dots-vertical-rounded">
                                             </i>
                                          </a>
                                          <div class="dropdown-menu">
                                             <a class="dropdown-item" href="#">
                                                Copy
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Save
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Forward
                                             </a>
                                             <a class="dropdown-item" href="#">
                                                Delete
                                             </a>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </li>
                           </ul>
                        </div>
                        <div class="p-3 border-top">
                           <div class="row">
                              <div class="col">
                                 <div class="position-relative">
                                    <input class="form-control border bg-light-subtle" placeholder="Enter Message..." type="text" />
                                 </div>
                              </div>
                              <div class="col-auto">
                                 <button class="btn btn-primary chat-send w-md waves-effect waves-light" type="submit">
                                    <span class="d-none d-sm-inline-block me-2">
                                       Send
                                    </span>
                                    <i class="mdi mdi-send float-end">
                                    </i>
                                 </button>
                              </div>
                           </div>
                        </div>
                     </div>
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
            if (mobile()) {
               chat.classList.add("mobile-hide");
               sidebar.classList.remove("mobile-hide");
            } else {
               chat.classList.remove("mobile-hide");
               sidebar.classList.remove("mobile-hide");
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
   </script>
</body>

</html>