<?php
require_once 'classes/dbconnect.php';

echo "Mencoba menghubungkan ke database...<br>";

$db = new dbconnect();

if ($db->conn) {
    echo "✅ Koneksi Berhasil!<br>";
    echo "Versi MySQL Server: " . $db->conn->server_info;
}
