<?php
$name = $_SESSION['username'];
require_once __DIR__ . '/../../src/classes/notifikasi.php';
require_once __DIR__ . '/../../src/classes/profile.php';

$notifikasiData = ['count' => 0, 'items' => []];
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
   $notiClass = new Notifikasi();
   $notifikasiData = $notiClass->getNotifikasiRole($_SESSION['role'], $_SESSION['id']);
}
$notiCount = $notifikasiData['count'];
$notiItems = $notifikasiData['items'];

$profile = new Profile();
$dataProfile = $profile->getProfile($_SESSION['id']);
?>

<header id="page-topbar">
   <div class="navbar-header">
      <div class="d-flex">
         <!-- LOGO -->
         <div class="navbar-brand-box">
            <a class="logo logo-dark" href="index.php">
               <span class="logo-sm">
                  <img alt="" height="24" src="/assets\images\logos\logo.webp" />
               </span>
               <span class="logo-lg">
                  <img alt="" height="24" src="/assets\images\logos\logo.webp" />
                  <span class="logo-txt">
                     Remaja Tumbuh
                  </span>
               </span>
            </a>
            <a class="logo logo-light" href="index.php">
               <span class="logo-sm">
                  <img alt="" height="24" src="/assets/images/logo-sm.svg" />
               </span>
               <span class="logo-lg">
                  <img alt="" height="24" src="/assets/images/logo-sm.svg" />
                  <span class="logo-txt">
                     Remaja Tumbuh
                  </span>
               </span>
            </a>
         </div>
         <button class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn" type="button">
            <i class="fa fa-fw fa-bars">
            </i>
         </button>
      </div>
      <div class="d-flex">
         <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin'): ?>
         <div class="d-sm-inline-block ms-2">
            <a href="chat.php" class="btn header-item d-flex align-items-center justify-content-center">
               <i class="icon-lg" data-feather="message-square"></i>
            </a>
         </div>
         <?php endif; ?>
         <div class="dropdown d-inline-block me-2">
            <button aria-expanded="false" aria-haspopup="true" class="btn header-item noti-icon position-relative" data-bs-toggle="dropdown" id="page-header-notifications-dropdown" type="button">
               <i class="icon-lg" data-feather="bell">
               </i>
               <span id="noti-badge" class="badge bg-danger rounded-pill">
                  <?php echo $notiCount; ?>
               </span>
            </button>
            <div aria-labelledby="page-header-notifications-dropdown" class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
               <div class="p-3">
                  <div class="row align-items-center">
                     <div class="col">
                        <h6 class="m-0">
                           Notifications
                        </h6>
                     </div>
                  </div>
               </div>
               <div data-simplebar="" style="max-height: 230px; overflow-y: auto;" id="noti-dropdown">
                  <?php if (empty($notiItems)): ?>
                     <div class="text-center p-3 text-muted">
                        Tidak ada notifikasi
                     </div>
                  <?php else: ?>
                     <?php foreach ($notiItems as $item): ?>
                        <a class="text-reset notification-item" href="#!">
                           <div class="d-flex">
                              <div class="flex-shrink-0 me-3">
                                 <i class="icon-lg text-primary" data-feather="<?= htmlspecialchars($item['icon'] ?? 'message-square') ?>"></i>
                              </div>
                              <div class="flex-grow-1">
                                 <h6 class="mb-1">
                                    <?php echo htmlspecialchars($item['message']); ?>
                                 </h6>
                                 <p class="mb-0 font-size-13 text-muted">
                                    <?php echo htmlspecialchars($item['text']); ?>
                                 </p>
                              </div>
                           </div>
                        </a>
                     <?php endforeach; ?>
                  <?php endif; ?>
               </div>
            </div>
         </div>
         <div class="dropdown">
            <button aria-expanded="false" aria-haspopup="true" class="btn header-item bg-light-subtle border-start border-end page-header-user-dropdown" data-bs-toggle="dropdown" id="page-header-user-dropdown" type="button">
               <img alt="Header Avatar" class="rounded-circle header-profile-user" src="/uploads/profile/<?= (!empty($dataProfile['data']['foto'])) ? $dataProfile['data']['foto'] : 'default.webp'; ?>" />
               <span class="d-none d-xl-inline-block ms-1 fw-medium">
                  <?php echo $name ?>
               </span>
               <i class="mdi mdi-chevron-down d-none d-xl-inline-block">
               </i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
               <!-- item-->
               <a class="dropdown-item" href="profile.php">
                  <i class="mdi mdi mdi-face-man font-size-16 align-middle me-1">
                  </i>
                  Profile
               </a>
               <div class="dropdown-divider">
               </div>
               <button class="dropdown-item" onclick="window.location.href='../../src/actions/proses_auth.php?action=logout'">
                  <i class="mdi mdi-logout font-size-16 align-middle me-1">
                  </i>
                  Logout
               </button>
            </div>
         </div>
      </div>
   </div>
</header>

<script>
   (function() {
      var url = '/src/actions/proses_notifikasi.php';

      function fetchNoti() {
         fetch(url, {
               credentials: 'same-origin'
            })
            .then(function(r) {
               return r.json();
            })
            .then(function(data) {
               var badge = document.getElementById('noti-badge');
               if (badge && data.count !== undefined) badge.textContent = data.count;
               var container = document.getElementById('noti-dropdown');
               if (!container) return;
               if (!data.items || data.items.length === 0) {
                  container.innerHTML = '<div class="text-center p-3 text-muted">Tidak ada notifikasi</div>';
               } else {
                  var html = '';
                  for (var i = 0; i < data.items.length; i++) {
                     var item = data.items[i];
                     var msg = item.message ? item.message.replace(/</g, '<').replace(/>/g, '>') : '';
                     var txt = item.text ? item.text.replace(/</g, '<').replace(/>/g, '>') : '';
                     var icon = (item.icon || 'message-square').replace(/</g, '<').replace(/>/g, '>');
                     html += '<a class="text-reset notification-item" href="#"><div class="d-flex"><div class="flex-shrink-0 me-3"><i class="icon-lg text-primary" data-feather="' + icon + '"></i></div><div class="flex-grow-1"><h6 class="mb-1">' + msg + '</h6><p class="mb-0 font-size-13 text-muted">' + txt + '</p></div></div></a>';
                  }
                  container.innerHTML = html;
                  if (window.feather) feather.replace();
               }
            })
            .catch(function(e) {
               console.error('Notifikasi polling error:', e);
            });
      }
      fetchNoti();
      setInterval(fetchNoti, 5000);
   })();
</script>