<?php include 'component/header.php'; ?>

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php include 'component/topbar.php'; ?>
        <!-- ========== Left Sidebar Start ========== -->
        <?php include 'component/sidebar.php'; ?>
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                   <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-4">
                                <a class="btn btn-outline-light btn-rounded btn-sm waves-effect mb-2" href="user.php">
                                    <span>
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                    Kembali
                                </a>
                                <div class="row align-items-center mb-2">
                                    <h4 class="mb-0 font-weight-bold">
                                        Detail Ringkasan Pengguna
                                    </h4>
                                    <p class="text-muted mb-0">
                                        Lihat detail ringkasan web dari pengguna.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm order-2 order-sm-1 align-self-center">
                                            <div class="d-flex align-items-center mt-3 mt-sm-0">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-xl me-3">
                                                        <img alt="" class="img-fluid rounded-circle d-block" src="../assets/images/users/avatar-2.jpg" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div>
                                                        <h5 class="mb-1">
                                                            Davied Indra Kurniawan
                                                        </h5>
                                                        <p class="text-muted mb-0">
                                                            Full Stack Developer
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div>
                                                    <h6 class="pt-3">
                                                        Tentang Pengguna
                                                    </h6>
                                                </div>
                                                <div>
                                                    <div>
                                                        <div class="py-1">
                                                            <div class="row">
                                                                <div class="col-xl">
                                                                    <div class="text-muted">
                                                                        <p class="mb-2">
                                                                            Hi I'm Phyllis Gatlin, Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        <div class="col-sm-auto order-1 order-sm-2">
                                            <div class="col-sm">
                                                <div class="apex-charts" data-colors='["#5156be", "#34c38f"]' id="invested-overview">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                            <div class="tab-content">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            Riwayat Pengguna
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row d-flex">
                                            <div class="col-lg-6 flex-wrap mb-2">
                                                <h6 class="mb-0">
                                                    Pembelajaran
                                                </h6>
                                                <span class="badge bg-primary rounded-pill my-2 py-1">
                                                    <i class="mdi mdi-check-circle"></i> 3 Selesai / 5 Materi
                                                </span>
                                                <h6 class="mb-0 text-muted">
                                                    Materi terakhir yang dipelajari
                                                </h6>
                                                <div class="card mt-2">
                                                    <div class="row g-0 align-items-center">
                                                        <div class="col-3 text-center">
                                                            <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items: center; justify-content: center;">
                                                                <img src="../assets/icon/focus-group.webp" alt="icon" style="width: 40px; height: 40px;">
                                                            </div>
                                                        </div>
                                                        <div class="col-9">
                                                            <div class="card-body">
                                                                <h5 class="card-title mb-0 font-weight-bold">
                                                                    Anti Nikah Dini
                                                                </h5>
                                                                <p class="card-text">
                                                                    <small class="text-muted">
                                                                        Perkumpulan tumbuh bersama.
                                                                    </small>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 flex-wrap mb-2">
                                                <h6 class="mb-0">
                                                    Kuis Skill
                                                </h6>
                                                <span class="badge bg-primary rounded-pill my-2 py-1">
                                                    <i class="mdi mdi-check-circle"></i> 3 Selesai / 5 Skill
                                                </span>
                                                <h6 class="mb-0 text-muted">
                                                    Skill terakhir yang dikerjakan
                                                </h6>
                                                <div class="card mt-2">
                                                    <div class="row g-0 align-items-center">
                                                        <div class="col-3 text-center">
                                                            <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #e9ecef; display: inline-flex; align-items: center; justify-content: center;">
                                                                <img src="../assets/icon/focus-group.webp" alt="icon" style="width: 40px; height: 40px;">
                                                            </div>
                                                        </div>
                                                        <div class="col-9">
                                                            <div class="card-body">
                                                                <h5 class="card-title mb-0 font-weight-bold">
                                                                    Anti Nikah Dini
                                                                </h5>
                                                                <p class="card-text">
                                                                    <small class="text-muted">
                                                                        Perkumpulan tumbuh bersama.
                                                                    </small>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 flex-wrap mb-4">
                                                <div>
                                                    <h6 class="mb-0">
                                                        Pre-Test
                                                    </h6>
                                                    <small class="text-muted mb-0">
                                                        Evaluasi yang dilakukan pertama kali user masuk aplikasi untuk menilai pengetahuannya.
                                                    </small>
                                                </div>
                                                <span class="badge bg-success rounded-pill my-2 py-1">
                                                    <i class="mdi mdi-check-circle"></i> Pretest Selesai Dikerjakan
                                                </span>
                                            </div>
                                            <div class="col-lg-6 flex-wrap mb-2">
                                                <div>
                                                    <h6 class="mb-0">
                                                        Post-Test
                                                    </h6>
                                                    <small class="text-muted mb-0">
                                                        Evaluasi yang dilakukan Terakhir kali user dalam aplikasi untuk menilai pengetahuannya.
                                                    </small>
                                                </div>
                                                <span class="badge bg-danger rounded-pill my-2 py-1">
                                                    <i class="mdi mdi-cancel"></i> Posttest Belum Dikerjakan
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end tab content -->
                        </div>
                        <!-- end col -->
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">
                                        Daftar Nilai Pengguna
                                    </h4>
                                    <div class="flex-shrink-0">
                                        <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#home2" role="tab" aria-selected="true">
                                                    <span class="d-block d-sm-none">
                                                        <i class="fas fa-home">
                                                        </i>
                                                    </span>
                                                    <span class="d-none d-sm-block">
                                                        Pre-Test
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" data-bs-toggle="tab" href="#profile2" role="tab" aria-selected="false" tabindex="-1">
                                                    <span class="d-block d-sm-none">
                                                        <i class="far fa-user">
                                                        </i>
                                                    </span>
                                                    <span class="d-none d-sm-block">
                                                        Skill
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" data-bs-toggle="tab" href="#messages2" role="tab" aria-selected="false" tabindex="-1">
                                                    <span class="d-block d-sm-none">
                                                        <i class="far fa-envelope">
                                                        </i>
                                                    </span>
                                                    <span class="d-none d-sm-block">
                                                        Post-Test
                                                    </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- end card header -->
                                <div class="card-body">
                                    <!-- Tab panes -->
                                    <div class="tab-content text-muted">
                                        <div class="tab-pane active show" id="home2" role="tabpanel">
                                            <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Name
                                                        </th>
                                                        <th>
                                                            Position
                                                        </th>
                                                        <th>
                                                            Office
                                                        </th>
                                                        <th>
                                                            Age
                                                        </th>
                                                        <th>
                                                            Start date
                                                        </th>
                                                        <th>
                                                            Aksi
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            Tiger Nixon
                                                        </td>
                                                        <td>
                                                            System Architect
                                                        </td>
                                                        <td>
                                                            Edinburgh
                                                        </td>
                                                        <td>
                                                            61
                                                        </td>
                                                        <td>
                                                            2011/04/25
                                                        </td>
                                                        <td>
                                                            $120,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Garrett Winters
                                                        </td>
                                                        <td>
                                                            Accountant
                                                        </td>
                                                        <td>
                                                            Tokyo
                                                        </td>
                                                        <td>
                                                            63
                                                        </td>
                                                        <td>
                                                            2011/07/25
                                                        </td>
                                                        <td>
                                                            $170,750
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Ashton Cox
                                                        </td>
                                                        <td>
                                                            Junior Technical Author
                                                        </td>
                                                        <td>
                                                            San Francisco
                                                        </td>
                                                        <td>
                                                            66
                                                        </td>
                                                        <td>
                                                            2009/01/12
                                                        </td>
                                                        <td>
                                                            $86,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Cedric Kelly
                                                        </td>
                                                        <td>
                                                            Senior Javascript Developer
                                                        </td>
                                                        <td>
                                                            Edinburgh
                                                        </td>
                                                        <td>
                                                            22
                                                        </td>
                                                        <td>
                                                            2012/03/29
                                                        </td>
                                                        <td>
                                                            $433,060
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Airi Satou
                                                        </td>
                                                        <td>
                                                            Accountant
                                                        </td>
                                                        <td>
                                                            Tokyo
                                                        </td>
                                                        <td>
                                                            33
                                                        </td>
                                                        <td>
                                                            2008/11/28
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <a class="btn btn-primary btn-sm" href="detail-user.php">
                                                                    <i class="fas fa-eye"></i> Detail
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Brielle Williamson
                                                        </td>
                                                        <td>
                                                            Integration Specialist
                                                        </td>
                                                        <td>
                                                            New York
                                                        </td>
                                                        <td>
                                                            61
                                                        </td>
                                                        <td>
                                                            2012/12/02
                                                        </td>
                                                        <td>
                                                            $372,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Herrod Chandler
                                                        </td>
                                                        <td>
                                                            Sales Assistant
                                                        </td>
                                                        <td>
                                                            San Francisco
                                                        </td>
                                                        <td>
                                                            59
                                                        </td>
                                                        <td>
                                                            2012/08/06
                                                        </td>
                                                        <td>
                                                            $137,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Rhona Davidson
                                                        </td>
                                                        <td>
                                                            Integration Specialist
                                                        </td>
                                                        <td>
                                                            Tokyo
                                                        </td>
                                                        <td>
                                                            55
                                                        </td>
                                                        <td>
                                                            2010/10/14
                                                        </td>
                                                        <td>
                                                            $327,900
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Colleen Hurst
                                                        </td>
                                                        <td>
                                                            Javascript Developer
                                                        </td>
                                                        <td>
                                                            San Francisco
                                                        </td>
                                                        <td>
                                                            39
                                                        </td>
                                                        <td>
                                                            2009/09/15
                                                        </td>
                                                        <td>
                                                            $205,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Sonya Frost
                                                        </td>
                                                        <td>
                                                            Software Engineer
                                                        </td>
                                                        <td>
                                                            Edinburgh
                                                        </td>
                                                        <td>
                                                            23
                                                        </td>
                                                        <td>
                                                            2008/12/13
                                                        </td>
                                                        <td>
                                                            $103,600
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Jena Gaines
                                                        </td>
                                                        <td>
                                                            Office Manager
                                                        </td>
                                                        <td>
                                                            London
                                                        </td>
                                                        <td>
                                                            30
                                                        </td>
                                                        <td>
                                                            2008/12/19
                                                        </td>
                                                        <td>
                                                            $90,560
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Quinn Flynn
                                                        </td>
                                                        <td>
                                                            Support Lead
                                                        </td>
                                                        <td>
                                                            Edinburgh
                                                        </td>
                                                        <td>
                                                            22
                                                        </td>
                                                        <td>
                                                            2013/03/03
                                                        </td>
                                                        <td>
                                                            $342,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Charde Marshall
                                                        </td>
                                                        <td>
                                                            Regional Director
                                                        </td>
                                                        <td>
                                                            San Francisco
                                                        </td>
                                                        <td>
                                                            36
                                                        </td>
                                                        <td>
                                                            2008/10/16
                                                        </td>
                                                        <td>
                                                            $470,600
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Haley Kennedy
                                                        </td>
                                                        <td>
                                                            Senior Marketing Designer
                                                        </td>
                                                        <td>
                                                            London
                                                        </td>
                                                        <td>
                                                            43
                                                        </td>
                                                        <td>
                                                            2012/12/18
                                                        </td>
                                                        <td>
                                                            $313,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Tatyana Fitzpatrick
                                                        </td>
                                                        <td>
                                                            Regional Director
                                                        </td>
                                                        <td>
                                                            London
                                                        </td>
                                                        <td>
                                                            19
                                                        </td>
                                                        <td>
                                                            2010/03/17
                                                        </td>
                                                        <td>
                                                            $385,750
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Michael Silva
                                                        </td>
                                                        <td>
                                                            Marketing Designer
                                                        </td>
                                                        <td>
                                                            London
                                                        </td>
                                                        <td>
                                                            66
                                                        </td>
                                                        <td>
                                                            2012/11/27
                                                        </td>
                                                        <td>
                                                            $198,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Paul Byrd
                                                        </td>
                                                        <td>
                                                            Chief Financial Officer (CFO)
                                                        </td>
                                                        <td>
                                                            New York
                                                        </td>
                                                        <td>
                                                            64
                                                        </td>
                                                        <td>
                                                            2010/06/09
                                                        </td>
                                                        <td>
                                                            $725,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Gloria Little
                                                        </td>
                                                        <td>
                                                            Systems Administrator
                                                        </td>
                                                        <td>
                                                            New York
                                                        </td>
                                                        <td>
                                                            59
                                                        </td>
                                                        <td>
                                                            2009/04/10
                                                        </td>
                                                        <td>
                                                            $237,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Bradley Greer
                                                        </td>
                                                        <td>
                                                            Software Engineer
                                                        </td>
                                                        <td>
                                                            London
                                                        </td>
                                                        <td>
                                                            41
                                                        </td>
                                                        <td>
                                                            2012/10/13
                                                        </td>
                                                        <td>
                                                            $132,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Dai Rios
                                                        </td>
                                                        <td>
                                                            Personnel Lead
                                                        </td>
                                                        <td>
                                                            Edinburgh
                                                        </td>
                                                        <td>
                                                            35
                                                        </td>
                                                        <td>
                                                            2012/09/26
                                                        </td>
                                                        <td>
                                                            $217,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Jenette Caldwell
                                                        </td>
                                                        <td>
                                                            Development Lead
                                                        </td>
                                                        <td>
                                                            New York
                                                        </td>
                                                        <td>
                                                            30
                                                        </td>
                                                        <td>
                                                            2011/09/03
                                                        </td>
                                                        <td>
                                                            $345,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Yuri Berry
                                                        </td>
                                                        <td>
                                                            Chief Marketing Officer (CMO)
                                                        </td>
                                                        <td>
                                                            New York
                                                        </td>
                                                        <td>
                                                            40
                                                        </td>
                                                        <td>
                                                            2009/06/25
                                                        </td>
                                                        <td>
                                                            $675,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Caesar Vance
                                                        </td>
                                                        <td>
                                                            Pre-Sales Support
                                                        </td>
                                                        <td>
                                                            New York
                                                        </td>
                                                        <td>
                                                            21
                                                        </td>
                                                        <td>
                                                            2011/12/12
                                                        </td>
                                                        <td>
                                                            $106,450
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Doris Wilder
                                                        </td>
                                                        <td>
                                                            Sales Assistant
                                                        </td>
                                                        <td>
                                                            Sidney
                                                        </td>
                                                        <td>
                                                            23
                                                        </td>
                                                        <td>
                                                            2010/09/20
                                                        </td>
                                                        <td>
                                                            $85,600
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Angelica Ramos
                                                        </td>
                                                        <td>
                                                            Chief Executive Officer (CEO)
                                                        </td>
                                                        <td>
                                                            London
                                                        </td>
                                                        <td>
                                                            47
                                                        </td>
                                                        <td>
                                                            2009/10/09
                                                        </td>
                                                        <td>
                                                            $1,200,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Gavin Joyce
                                                        </td>
                                                        <td>
                                                            Developer
                                                        </td>
                                                        <td>
                                                            Edinburgh
                                                        </td>
                                                        <td>
                                                            42
                                                        </td>
                                                        <td>
                                                            2010/12/22
                                                        </td>
                                                        <td>
                                                            $92,575
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Jennifer Chang
                                                        </td>
                                                        <td>
                                                            Regional Director
                                                        </td>
                                                        <td>
                                                            Singapore
                                                        </td>
                                                        <td>
                                                            28
                                                        </td>
                                                        <td>
                                                            2010/11/14
                                                        </td>
                                                        <td>
                                                            $357,650
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Brenden Wagner
                                                        </td>
                                                        <td>
                                                            Software Engineer
                                                        </td>
                                                        <td>
                                                            San Francisco
                                                        </td>
                                                        <td>
                                                            28
                                                        </td>
                                                        <td>
                                                            2011/06/07
                                                        </td>
                                                        <td>
                                                            $206,850
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Fiona Green
                                                        </td>
                                                        <td>
                                                            Chief Operating Officer (COO)
                                                        </td>
                                                        <td>
                                                            San Francisco
                                                        </td>
                                                        <td>
                                                            48
                                                        </td>
                                                        <td>
                                                            2010/03/11
                                                        </td>
                                                        <td>
                                                            $850,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Shou Itou
                                                        </td>
                                                        <td>
                                                            Regional Marketing
                                                        </td>
                                                        <td>
                                                            Tokyo
                                                        </td>
                                                        <td>
                                                            20
                                                        </td>
                                                        <td>
                                                            2011/08/14
                                                        </td>
                                                        <td>
                                                            $163,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Michelle House
                                                        </td>
                                                        <td>
                                                            Integration Specialist
                                                        </td>
                                                        <td>
                                                            Sidney
                                                        </td>
                                                        <td>
                                                            37
                                                        </td>
                                                        <td>
                                                            2011/06/02
                                                        </td>
                                                        <td>
                                                            $95,400
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Suki Burks
                                                        </td>
                                                        <td>
                                                            Developer
                                                        </td>
                                                        <td>
                                                            London
                                                        </td>
                                                        <td>
                                                            53
                                                        </td>
                                                        <td>
                                                            2009/10/22
                                                        </td>
                                                        <td>
                                                            $114,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Prescott Bartlett
                                                        </td>
                                                        <td>
                                                            Technical Author
                                                        </td>
                                                        <td>
                                                            London
                                                        </td>
                                                        <td>
                                                            27
                                                        </td>
                                                        <td>
                                                            2011/05/07
                                                        </td>
                                                        <td>
                                                            $145,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Gavin Cortez
                                                        </td>
                                                        <td>
                                                            Team Leader
                                                        </td>
                                                        <td>
                                                            San Francisco
                                                        </td>
                                                        <td>
                                                            22
                                                        </td>
                                                        <td>
                                                            2008/10/26
                                                        </td>
                                                        <td>
                                                            $235,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Martena Mccray
                                                        </td>
                                                        <td>
                                                            Post-Sales support
                                                        </td>
                                                        <td>
                                                            Edinburgh
                                                        </td>
                                                        <td>
                                                            46
                                                        </td>
                                                        <td>
                                                            2011/03/09
                                                        </td>
                                                        <td>
                                                            $324,050
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Unity Butler
                                                        </td>
                                                        <td>
                                                            Marketing Designer
                                                        </td>
                                                        <td>
                                                            San Francisco
                                                        </td>
                                                        <td>
                                                            47
                                                        </td>
                                                        <td>
                                                            2009/12/09
                                                        </td>
                                                        <td>
                                                            $85,675
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Howard Hatfield
                                                        </td>
                                                        <td>
                                                            Office Manager
                                                        </td>
                                                        <td>
                                                            San Francisco
                                                        </td>
                                                        <td>
                                                            51
                                                        </td>
                                                        <td>
                                                            2008/12/16
                                                        </td>
                                                        <td>
                                                            $164,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Hope Fuentes
                                                        </td>
                                                        <td>
                                                            Secretary
                                                        </td>
                                                        <td>
                                                            San Francisco
                                                        </td>
                                                        <td>
                                                            41
                                                        </td>
                                                        <td>
                                                            2010/02/12
                                                        </td>
                                                        <td>
                                                            $109,850
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Vivian Harrell
                                                        </td>
                                                        <td>
                                                            Financial Controller
                                                        </td>
                                                        <td>
                                                            San Francisco
                                                        </td>
                                                        <td>
                                                            62
                                                        </td>
                                                        <td>
                                                            2009/02/14
                                                        </td>
                                                        <td>
                                                            $452,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Timothy Mooney
                                                        </td>
                                                        <td>
                                                            Office Manager
                                                        </td>
                                                        <td>
                                                            London
                                                        </td>
                                                        <td>
                                                            37
                                                        </td>
                                                        <td>
                                                            2008/12/11
                                                        </td>
                                                        <td>
                                                            $136,200
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Jackson Bradshaw
                                                        </td>
                                                        <td>
                                                            Director
                                                        </td>
                                                        <td>
                                                            New York
                                                        </td>
                                                        <td>
                                                            65
                                                        </td>
                                                        <td>
                                                            2008/09/26
                                                        </td>
                                                        <td>
                                                            $645,750
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Olivia Liang
                                                        </td>
                                                        <td>
                                                            Support Engineer
                                                        </td>
                                                        <td>
                                                            Singapore
                                                        </td>
                                                        <td>
                                                            64
                                                        </td>
                                                        <td>
                                                            2011/02/03
                                                        </td>
                                                        <td>
                                                            $234,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Bruno Nash
                                                        </td>
                                                        <td>
                                                            Software Engineer
                                                        </td>
                                                        <td>
                                                            London
                                                        </td>
                                                        <td>
                                                            38
                                                        </td>
                                                        <td>
                                                            2011/05/03
                                                        </td>
                                                        <td>
                                                            $163,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Sakura Yamamoto
                                                        </td>
                                                        <td>
                                                            Support Engineer
                                                        </td>
                                                        <td>
                                                            Tokyo
                                                        </td>
                                                        <td>
                                                            37
                                                        </td>
                                                        <td>
                                                            2009/08/19
                                                        </td>
                                                        <td>
                                                            $139,575
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Thor Walton
                                                        </td>
                                                        <td>
                                                            Developer
                                                        </td>
                                                        <td>
                                                            New York
                                                        </td>
                                                        <td>
                                                            61
                                                        </td>
                                                        <td>
                                                            2013/08/11
                                                        </td>
                                                        <td>
                                                            $98,540
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Finn Camacho
                                                        </td>
                                                        <td>
                                                            Support Engineer
                                                        </td>
                                                        <td>
                                                            San Francisco
                                                        </td>
                                                        <td>
                                                            47
                                                        </td>
                                                        <td>
                                                            2009/07/07
                                                        </td>
                                                        <td>
                                                            $87,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Serge Baldwin
                                                        </td>
                                                        <td>
                                                            Data Coordinator
                                                        </td>
                                                        <td>
                                                            Singapore
                                                        </td>
                                                        <td>
                                                            64
                                                        </td>
                                                        <td>
                                                            2012/04/09
                                                        </td>
                                                        <td>
                                                            $138,575
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Zenaida Frank
                                                        </td>
                                                        <td>
                                                            Software Engineer
                                                        </td>
                                                        <td>
                                                            New York
                                                        </td>
                                                        <td>
                                                            63
                                                        </td>
                                                        <td>
                                                            2010/01/04
                                                        </td>
                                                        <td>
                                                            $125,250
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Zorita Serrano
                                                        </td>
                                                        <td>
                                                            Software Engineer
                                                        </td>
                                                        <td>
                                                            San Francisco
                                                        </td>
                                                        <td>
                                                            56
                                                        </td>
                                                        <td>
                                                            2012/06/01
                                                        </td>
                                                        <td>
                                                            $115,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Jennifer Acosta
                                                        </td>
                                                        <td>
                                                            Junior Javascript Developer
                                                        </td>
                                                        <td>
                                                            Edinburgh
                                                        </td>
                                                        <td>
                                                            43
                                                        </td>
                                                        <td>
                                                            2013/02/01
                                                        </td>
                                                        <td>
                                                            $75,650
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Cara Stevens
                                                        </td>
                                                        <td>
                                                            Sales Assistant
                                                        </td>
                                                        <td>
                                                            New York
                                                        </td>
                                                        <td>
                                                            46
                                                        </td>
                                                        <td>
                                                            2011/12/06
                                                        </td>
                                                        <td>
                                                            $145,600
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Hermione Butler
                                                        </td>
                                                        <td>
                                                            Regional Director
                                                        </td>
                                                        <td>
                                                            London
                                                        </td>
                                                        <td>
                                                            47
                                                        </td>
                                                        <td>
                                                            2011/03/21
                                                        </td>
                                                        <td>
                                                            $356,250
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Lael Greer
                                                        </td>
                                                        <td>
                                                            Systems Administrator
                                                        </td>
                                                        <td>
                                                            London
                                                        </td>
                                                        <td>
                                                            21
                                                        </td>
                                                        <td>
                                                            2009/02/27
                                                        </td>
                                                        <td>
                                                            $103,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Jonas Alexander
                                                        </td>
                                                        <td>
                                                            Developer
                                                        </td>
                                                        <td>
                                                            San Francisco
                                                        </td>
                                                        <td>
                                                            30
                                                        </td>
                                                        <td>
                                                            2010/07/14
                                                        </td>
                                                        <td>
                                                            $86,500
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Shad Decker
                                                        </td>
                                                        <td>
                                                            Regional Director
                                                        </td>
                                                        <td>
                                                            Edinburgh
                                                        </td>
                                                        <td>
                                                            51
                                                        </td>
                                                        <td>
                                                            2008/11/13
                                                        </td>
                                                        <td>
                                                            $183,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Michael Bruce
                                                        </td>
                                                        <td>
                                                            Javascript Developer
                                                        </td>
                                                        <td>
                                                            Singapore
                                                        </td>
                                                        <td>
                                                            29
                                                        </td>
                                                        <td>
                                                            2011/06/27
                                                        </td>
                                                        <td>
                                                            $183,000
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Donna Snider
                                                        </td>
                                                        <td>
                                                            Customer Support
                                                        </td>
                                                        <td>
                                                            New York
                                                        </td>
                                                        <td>
                                                            27
                                                        </td>
                                                        <td>
                                                            2011/01/25
                                                        </td>
                                                        <td>
                                                            $112,000
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane" id="profile2" role="tabpanel">
                                            <p class="mb-0">
                                                Food truck fixie locavore, accusamus mcsweeney's marfa nulla
                                                single-origin coffee squid. Exercitation +1 labore velit, blog
                                                sartorial PBR leggings next level wes anderson artisan four loko
                                                farm-to-table craft beer twee. Qui photo booth letterpress,
                                                commodo enim craft beer mlkshk aliquip jean shorts ullamco ad
                                                vinyl cillum PBR. Homo nostrud organic, assumenda labore
                                                aesthetic magna delectus.
                                            </p>
                                        </div>
                                        <div class="tab-pane" id="messages2" role="tabpanel">
                                            <p class="mb-0">
                                                Etsy mixtape wayfarers, ethical wes anderson tofu before they
                                                sold out mcsweeney's organic lomo retro fanny pack lo-fi
                                                farm-to-table readymade. Messenger bag gentrify pitchfork
                                                tattooed craft beer, iphone skateboard locavore carles etsy
                                                salvia banksy hoodie helvetica. DIY synth PBR banksy irony.
                                                Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh
                                                mi whatever gluten-free carles.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card-body -->
                            </div>
                            <!-- end card -->
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        <?php include 'component/footer.php'; ?>
    </div>
    <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <!-- Right Sidebar -->
    <?php include 'component/right-sidebar.php'; ?>
    <!-- /Right-bar -->
    <!-- Right bar overlay-->
    <div class="rightbar-overlay">
    </div>
    <!-- JAVASCRIPT -->
    <?php include 'component/script.php'; ?>
</body>

</html>