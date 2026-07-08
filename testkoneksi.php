<?php
// Panggil file class DBConnection kamu
// (Ubah 'DBConnection.php' sesuai dengan nama file kamu sebenarnya)
require_once 'function/dbconnect.php';

echo "Mencoba menghubungkan ke database...<br>";

// Membuat instance/object baru
// Saat baris ini dijalankan, __construct() otomatis berjalan dan mencoba koneksi
$db = new DBConnection();

// Jika berhasil melewati baris di atas tanpa error, berarti koneksi sukses!
if ($db->conn) {
    echo "✅ Koneksi Berhasil!<br>";
    // Opsional: Menampilkan versi server database untuk memastikan
    echo "Versi MySQL Server: " . $db->conn->server_info;
}
