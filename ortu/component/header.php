<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8" />
   <title>
      Dashboard | Admin &amp; Dashboard Template
   </title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="Premium Multipurpose Admin &amp; Dashboard Template" name="description">
   <meta content="admintem.com" name="author" />
   <!-- App favicon -->
   <link href="../../assets/images/favicon.ico" rel="shortcut icon" />
   <!-- plugin css -->
   <link href="../../assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css">
   <!-- DataTables -->
   <link href="../../assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
   <link href="../../assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
   <!-- Responsive datatable examples -->
   <link href="../../assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
   <!-- preloader css -->
   <link href="../../assets/css/preloader.min.css" rel="stylesheet" type="text/css" />
   <!-- Bootstrap Css -->
   <link href="../../assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
   <!-- Icons Css -->
   <link href="../../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
   <!-- App Css-->
   <link href="../../assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

   <!-- CUSTOM CSS UNTUK MOBILE BOTTOM NAV START -->
   <style>
      @media (max-width: 991px) {

         /* 0. SEMBUNYIKAN TOMBOL HAMBURGER */
         /* Menghapus tombol toggle menu di navbar */
         .vertical-menu-btn,
         .navbar-toggler,
         #vertical-menu-btn {
            display: none !important;
         }

         /* 1. Paksa menu pindah ke bawah dan lebarnya penuh */
         .vertical-menu {
            display: block !important;
            width: 100% !important;
            height: 65px !important;
            position: fixed !important;
            bottom: 0 !important;
            top: auto !important;
            left: 0 !important;
            right: 0 !important;
            /* background: #ffffff !important; */
            /* Ganti ke warna tema Anda jika ingin gelap */
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1) !important;
            z-index: 9999 !important;
            transition: none !important;
            border-radius: 0 !important;
         }

         /* 2. Reset wrapper SimpleBar agar tidak mengganggu layout flex */
         .vertical-menu .h-100,
         .vertical-menu [data-simplebar] {
            height: 100% !important;
            overflow: visible !important;
         }

         /* 3. Ubah list menu menjadi baris horizontal */
         #side-menu {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-around !important;
            align-items: center !important;
            padding: 0 !important;
            margin: 0 !important;
            height: 100% !important;
            list-style: none !important;
         }

         #sidebar-menu {
            padding: 10px 0 0 0 !important;
         }

         /* 4. Sembunyikan teks judul menu (seperti "MENU", "LAYOUTS") */
         .vertical-menu .menu-title {
            display: none !important;
         }

         /* 5. Atur setiap item menu */
         #side-menu li {
            flex: 1 !important;
            text-align: center !important;
            margin: 0 !important;
            padding: 0 !important;
         }

         /* 6. Ubah link agar Icon di atas dan Teks di bawah */
         #side-menu li a {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 10px 0 !important;
            /* color: #6c757d !important; */
            /* Warna teks default mobile */
            font-size: 11px !important;
            text-decoration: none !important;
            background: transparent !important;
         }

         /* 7. Penyesuaian Icon Feather */
         #side-menu li a i {
            margin-right: 0 !important;
            margin-bottom: 4px !important;
            width: 22px !important;
            height: 22px !important;
            stroke-width: 2px;
         }

         /* 8. Penyesuaian teks span */
         #side-menu li a span {
            display: block !important;
            font-size: 10px !important;
            font-weight: 500 !important;
            line-height: 1.2 !important;
         }

         /* 9. Warna saat menu Aktif */
         #side-menu li.mm-active>a,
         #side-menu li a:hover {
            /* color: #405189 !important; */
            /* Sesuaikan dengan warna primary template Anda */
            background: transparent !important;
         }

         /* 10. Memberi ruang agar konten utama tidak tertutup navigasi bawah */
         .main-content,
         #wrapper,
         .content-page {
            padding-bottom: 70px !important;
         }

         /* Sembunyikan elemen sidebar lain yang mengganggu di mobile */
         .vertical-menu .logo-box {
            display: none !important;
         }

         /* 11. Sembunyikan sub-menu dan arrow di mobile */
         #side-menu li a.has-arrow::after {
            display: none !important;
         }

         #side-menu li a.has-arrow {
            cursor: pointer;
         }

         #side-menu .sub-menu {
            display: none !important;
         }

         /* 12. MODAL SUBMENU STYLING */
         .modal-bottom .modal-dialog {
            margin: 0;
            margin-top: auto;
         }

         .modal-bottom .modal-content {
            border-radius: 15px 15px 15px 15px;
         }

         #submenuContent {
            max-height: 60vh;
            overflow-y: auto;
         }

         #submenuContent a {
            display: block;
            /* padding: 12px 15px; */
            color: #333;
            text-decoration: none;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 500;
         }

         #submenuContent a:last-child {
            border-bottom: none;
         }

         #submenuContent a:hover {
            background-color: #f8f9fa;
            color: #405189;
         }
      }
   </style>
   <!-- CUSTOM CSS UNTUK MOBILE BOTTOM NAV END -->

   </meta>
</head>

<!-- MODAL UNTUK SUBMENU MOBILE -->
<div class="modal fade" id="mobileSubmenuModal" tabindex="-1" aria-labelledby="mobileSubmenuLabel" aria-hidden="true">
   <div class="modal-dialog modal-bottom">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="mobileSubmenuLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body pt-0" id="submenuContent">
            <!-- Submenu items akan di-load di sini via JavaScript -->
         </div>
      </div>
   </div>
</div>
<!-- END MODAL SUBMENU MOBILE -->