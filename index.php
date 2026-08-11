<?php
require_once 'src/classes/auth.php';

$auth = new auth();

// Jika belum login, tetap ke halaman login
$auth->authOrNot();
?>