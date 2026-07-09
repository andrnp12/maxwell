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
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['is_logged_in'] = true;

                return true;
            }
        }
        return false;
    }

    public function register(string $username, string $password, string $role = 'user'): bool
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

        $stmt = $this->conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");

        if (!$stmt) {
            die("Error pada query: " . $this->conn->error);
        }

        $stmt->bind_param("sss", $username, $hashedPassword, $role);
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
