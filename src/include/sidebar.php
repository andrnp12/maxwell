<?php
global $sudahPreTest, $sudahPostTest;
?>

<div class="vertical-menu">
   <div class="h-100" data-simplebar="">
      <!--- Sidemenu -->
      <div id="sidebar-menu">
         <!-- Left Menu Start -->
         <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title" data-key="t-menu">
               Menu
            </li>
            <li>
               <a href="index.php">
                  <i data-feather="home">
                  </i>
                  <span data-key="t-dashboard">
                     Beranda
                  </span>
               </a>
            </li>
            <li>
               <a href="belajar.php">
                  <i data-feather="book">
                  </i>
                  <span data-key="t-belajar">
                     Belajar
                  </span>
               </a>
            </li>
            <li>
               <a href="skill.php">
                  <i data-feather="award"></i>
                  <span data-key="t-skill">Skill</span>
               </a>
            </li>
            <li>
               <a href="komunitas.php">
                  <i data-feather="users">
                  </i>
                  <span data-key="t-komunitas">
                     Komunitas
                  </span>
               </a>
            </li>
            <li>
               <a href="profile.php">
                  <i data-feather="user">
                  </i>
                  <span data-key="t-akun">
                     Akun
                  </span>
               </a>
            </li>
            <li>
               <a href="preposttest.php">
                  <i data-feather="user">
                  </i>
                  <span data-key="t-akun">
                     pre post test (trial)
                  </span>
               </a>
            </li>
            <li>
               Pre-Test:
               <?php if (!$sudahPreTest): ?>
                  <a href="preposttest.php?type=pre"><button>Mulai Pre-Test</button></a>
               <?php else: ?>
                  <span style="color: green;">✔ Sudah Dikerjakan</span>
               <?php endif; ?>
            </li>
            <!-- Logika Tombol Post-Test -->
            <li>
               Post-Test:
               <?php if (!$sudahPreTest): ?>
                  <span style="color: gray;">Kerjakan Pre-Test terlebih dahulu</span>
               <?php elseif ($sudahPreTest && !$sudahPostTest): ?>
                  <a href="preposttest.php?type=post"><button>Mulai Post-Test</button></a>
               <?php else: ?>
                  <!-- Tombol berubah jika sudah pernah dikerjakan -->
                  <span style="color: green;">✔ Nilai Resmi Tersimpan</span>
                  <a href="preposttest.php?type=post"><button style="background-color: orange;">Kerjakan Ulang (Latihan)</button></a>
               <?php endif; ?>
            </li>
         </ul>
      </div>
      <!-- Sidebar -->
   </div>
</div>