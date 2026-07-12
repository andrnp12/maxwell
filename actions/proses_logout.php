<?php

require_once '../classes/auth.php';

$auth = new auth();

$auth->logout();

header('Location: ../login.php');
exit;
