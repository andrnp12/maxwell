<?php
require_once 'dbconnect.php';

class auth
{
    private dbconnect $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;
    }

    public function authOrNot(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. CEK APAKAH USER SUDAH LOGIN
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            header('Location: /pages/login.php'); // Sesuaikan path login Anda
            exit;
        }

        // 2. AMBIL ROLE DARI SESSION
        $userRole = $_SESSION['role'] ?? '';
        $allowedRoles = ['admin', 'user', 'konsultan', 'ortu'];

        if (!in_array($userRole, $allowedRoles, true)) {
            // Jika role tidak valid/tidak dikenal, paksa logout
            session_destroy();
            header('Location: /pages/login.php');
            exit;
        }

        // 3. PROTEKSI FOLDER (ROLE-BASED ACCESS CONTROL)
        // Ambil URI saat ini (contoh: /pages/admin/index.php)
        $currentUri = $_SERVER['REQUEST_URI'];

        // Cek apakah user sedang mencoba mengakses folder /pages/
        if (strpos($currentUri, '/pages/') !== false) {

            // Pecah URI menjadi array untuk mendapatkan nama folder setelah /pages/
            // Contoh: /pages/admin/dashboard.php -> ['pages', 'admin', 'dashboard.php']
            $parts = explode('/', trim($currentUri, '/'));
            $pagesIndex = array_search('pages', $parts);

            // Folder role berada tepat setelah index 'pages'
            $accessedRoleFolder = $parts[$pagesIndex + 1] ?? '';

            // Jika user mengakses folder role (admin/user/dll) tapi tidak sesuai dengan role-nya
            if (in_array($accessedRoleFolder, $allowedRoles) && $accessedRoleFolder !== $userRole) {
                // REDIRECT ke dashboard role mereka sendiri agar tidak bisa mengintip
                header("Location: /pages/{$userRole}/index.php");
                exit;
            }
        }
    }

    public function login(string $username, string $password): bool
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");

        if (!$stmt) {
            die("Error pada query: " . $this->conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['is_logged_in'] = true;

                return true;
            }
        }
        return false;
    }

// Fungsi untuk melakukan registrasi perlu di revisi untuk bagian role nya, jadi agar saat register tidak otomatis jadi user
    public function register(string $foto, string $username, string $name, string $nomor, string $email, string $password, string $deskripsi, string $role): bool
    {
        $checkSql = "SELECT id FROM users WHERE username = ?";
        $stmtCheck = $this->conn->prepare($checkSql);
        $stmtCheck->bind_param("s", $username);
        $stmtCheck->execute();

        if ($stmtCheck->get_result()->num_rows > 0) {
            // Username sudah ada, gagalkan registrasi
            return false;
        }
        // Hash password sebelum disimpan
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO users (foto, username, name, nomor, email, password, deskripsi, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            die("Error pada query: " . $this->conn->error);
        }

        $stmt->bind_param("ssssssss", $foto, $username, $name, $nomor, $email, $hashedPassword, $deskripsi, $role);
        return $stmt->execute();
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }
}
