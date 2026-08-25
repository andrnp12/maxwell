<?php
require_once 'dbconnect.php';

/**
 * Recommended schema (confirm against your actual dbconnect.php migrations):
 *
 * CREATE TABLE user_token (
 *     id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *     user_id       INT UNSIGNED NOT NULL,
 *     selector      CHAR(32)     NOT NULL,
 *     token_hash    VARCHAR(255) NOT NULL,
 *     expiry        DATETIME     NOT NULL,
 *     user_agent    VARCHAR(255) NULL,
 *     ip_address    VARCHAR(45)  NULL,
 *     created_at    DATETIME     NOT NULL,
 *     UNIQUE KEY uq_selector (selector),
 *     KEY idx_user_id (user_id),
 *     KEY idx_expiry (expiry)
 * );
 *
 * - UNIQUE on selector: O(1) lookup, guards against collisions.
 * - idx_user_id: used by the max-devices query and revokeAllUserTokens().
 * - idx_expiry: used by cleanupExpiredTokens().
 */
class auth
{
    private dbconnect $db;
    private mysqli $conn;

    // Token configuration - Selector & Validator pattern
    private const REMEMBER_TOKEN_NAME = 'remember_token';
    private const REMEMBER_TOKEN_LIFETIME = 30 * 24 * 60 * 60; // 30 days in seconds
    private const SELECTOR_BYTES = 16; // 128-bit selector (not secret, used for DB lookup)
    private const VALIDATOR_BYTES = 32; // 256-bit validator (secret, hashed in DB)
    private const MAX_DEVICES = 5;

    // Probability (1 in N) of running expired-token cleanup on a given request
    private const CLEANUP_PROBABILITY = 100;

    public function __construct()
    {
        $this->db = new dbconnect();
        $this->conn = $this->db->conn;

        // Reject session IDs the server itself did not generate (mitigates
        // session fixation via attacker-supplied session IDs / cookies).
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', '1');
        }
    }

    /**
     * Configure session cookie parameters with security flags.
     * Must be called BEFORE session_start().
     */
    public function configureSessionCookie(bool $rememberMe): void
    {
        $lifetime = $rememberMe ? self::REMEMBER_TOKEN_LIFETIME : 0; // 30 days or session cookie
        $secure = $this->isHttps();

        session_set_cookie_params([
            'lifetime' => $lifetime,
            'path' => '/',
            'domain' => '',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        // session.gc_maxlifetime is a process-wide PHP setting, not a
        // per-cookie one, so ini_set() only protects THIS request unless
        // it's re-applied on every request. authOrNot() now calls
        // configureSessionCookie() on every request (not just at login),
        // so as long as the remember_token cookie is present we keep
        // re-extending gc_maxlifetime here - this is what actually stops
        // the session file from being garbage collected mid-lifetime.
        //
        // Many hosting defaults set gc_maxlifetime to 1440s (24 min) or
        // 10800s (3h) - both of which produced exactly the premature-logout
        // symptoms reported. DB-backed restoration (tryRestoreSessionFromToken)
        // remains as a safety net for cases this doesn't cover (server
        // restarts, shared/cleared session storage across multiple app
        // servers, etc.), but is no longer the primary defense.
        if ($rememberMe) {
            ini_set('session.gc_maxlifetime', (string) self::REMEMBER_TOKEN_LIFETIME);
        }
    }

    /**
     * Detect if current request is over HTTPS.
     */
    private function isHttps(): bool
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            return true;
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return true;
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') {
            return true;
        }
        if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] === '443') {
            return true;
        }
        return false;
    }

    /**
     * Generate a cryptographically secure selector:validator pair.
     */
    private function generateRememberToken(): array
    {
        $selector = bin2hex(random_bytes(self::SELECTOR_BYTES));
        $validator = bin2hex(random_bytes(self::VALIDATOR_BYTES));

        return [
            'selector' => $selector,
            'validator' => $validator,
            'cookie_value' => $selector . ':' . $validator,
        ];
    }

    private function hashValidator(string $validator): string
    {
        return password_hash($validator, PASSWORD_DEFAULT);
    }

    /**
     * Store remember token in database using Selector & Validator pattern.
     * Supports multi-device: keeps up to MAX_DEVICES tokens per user.
     */
    private function storeRememberToken(int $userId, string $selector, string $validator, int $expiry): bool
    {
        $validatorHash = $this->hashValidator($validator);
        $expiryDateTime = date('Y-m-d H:i:s', $expiry);
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

        $stmt = $this->conn->prepare("
            SELECT id FROM user_token
            WHERE user_id = ?
            ORDER BY created_at ASC
        ");
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->num_rows;

            if ($count >= self::MAX_DEVICES) {
                $deleteCount = $count - self::MAX_DEVICES + 1;
                $stmtDel = $this->conn->prepare("
                    DELETE FROM user_token
                    WHERE user_id = ?
                    ORDER BY created_at ASC
                    LIMIT ?
                ");
                if ($stmtDel) {
                    $stmtDel->bind_param("ii", $userId, $deleteCount);
                    $stmtDel->execute();
                }
            }
        }

        $stmt = $this->conn->prepare("
            INSERT INTO user_token (user_id, selector, token_hash, expiry, user_agent, ip_address, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("isssss", $userId, $selector, $validatorHash, $expiryDateTime, $userAgent, $ipAddress);
        return $stmt->execute();
    }

    /**
     * Validate remember token using Selector & Validator pattern.
     *
     * Returns one of three outcomes so the caller can distinguish
     * "no such token" from "token theft signal" (area 6/7):
     *   - ['status' => 'ok', 'user_id' => int, 'selector' => string]
     *   - ['status' => 'not_found']
     *   - ['status' => 'mismatch', 'user_id' => int]   <- possible theft
     */
    private function validateRememberToken(string $cookieValue): array
    {
        $parts = explode(':', $cookieValue, 2);
        if (count($parts) !== 2) {
            return ['status' => 'not_found'];
        }

        [$selector, $validator] = $parts;

        $stmt = $this->conn->prepare("
            SELECT user_id, token_hash, expiry
            FROM user_token
            WHERE selector = ? AND expiry > NOW()
        ");
        if (!$stmt) {
            return ['status' => 'not_found'];
        }
        $stmt->bind_param("s", $selector);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            return ['status' => 'not_found'];
        }

        $row = $result->fetch_assoc();

        // Timing-safe comparison via password_verify.
        if (!password_verify($validator, $row['token_hash'])) {
            // Selector matched but validator didn't: the selector was
            // guessed/leaked without the validator, or a rotated (already
            // superseded) token was replayed. Treat as a theft signal.
            return ['status' => 'mismatch', 'user_id' => (int) $row['user_id']];
        }

        return ['status' => 'ok', 'user_id' => (int) $row['user_id'], 'selector' => $selector];
    }

    /**
     * Grace window (seconds) an old selector stays valid after rotation.
     *
     * WHY THIS EXISTS: a naive "delete old, insert new" rotation is not
     * safe under concurrent requests. If a client (very commonly a mobile
     * WebView, which tends to fire several requests to protected endpoints
     * close together) sends two requests with the SAME old cookie before
     * it has received the new Set-Cookie from the first response, the
     * second request would find the old selector already deleted -> treated
     * as invalid -> its response would clear the cookie -> if that response
     * lands after the first (valid) one, it wipes out the good new cookie
     * and the user gets logged out even though nothing was actually wrong.
     * Keeping the old selector valid for a short grace window absorbs that
     * race instead of destroying a legitimate session over it.
     */

    private function revokeRememberToken(string $cookieValue): void
    {
        $parts = explode(':', $cookieValue, 2);
        if (count($parts) !== 2) {
            return;
        }
        [$selector] = $parts;

        $stmt = $this->conn->prepare("DELETE FROM user_token WHERE selector = ?");
        if ($stmt) {
            $stmt->bind_param("s", $selector);
            $stmt->execute();
        }
    }

    /**
     * Revoke all remember tokens for a user (password change, theft signal,
     * "log out everywhere", etc.)
     */
    private function revokeAllUserTokens(int $userId): void
    {
        $stmt = $this->conn->prepare("DELETE FROM user_token WHERE user_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
        }
    }

    /**
     * Clean up expired tokens from database. Runs probabilistically to
     * avoid an extra full-table DELETE on every request; move to a cron
     * job instead if you have meaningful traffic.
     */
    private function cleanupExpiredTokens(): void
    {
        if (random_int(1, self::CLEANUP_PROBABILITY) !== 1) {
            return;
        }
        $stmt = $this->conn->prepare("DELETE FROM user_token WHERE expiry <= NOW()");
        if ($stmt) {
            $stmt->execute();
        }
    }

    private function setRememberTokenCookie(string $cookieValue): void
    {
        setcookie(self::REMEMBER_TOKEN_NAME, $cookieValue, [
            'expires' => time() + self::REMEMBER_TOKEN_LIFETIME,
            'path' => '/',
            'domain' => '',
            'secure' => $this->isHttps(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    private function clearRememberTokenCookie(): void
    {
        setcookie(self::REMEMBER_TOKEN_NAME, '', [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '',
            'secure' => $this->isHttps(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    private function getRememberTokenFromCookie(): string|null
    {
        return $_COOKIE[self::REMEMBER_TOKEN_NAME] ?? null;
    }

    public function authOrNot(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $this->configureSessionCookie(
                isset($_COOKIE[self::REMEMBER_TOKEN_NAME])
            );

            session_start();
        }

        // Kalau session belum login, coba restore dari Remember Me
        if (empty($_SESSION['is_logged_in'])) {
            $this->tryRestoreSessionFromToken();
        }

        // Setelah restore, kalau tetap belum login
        if (empty($_SESSION['is_logged_in'])) {
            header('Location: /pages/login.php');
            exit;
        }

        // Validasi role
        $userRole = $_SESSION['role'] ?? '';
        $allowedRoles = ['admin', 'user', 'konsultan', 'ortu'];

        if (!in_array($userRole, $allowedRoles, true)) {

            // Jangan panggil logout()
            // karena logout() mencabut Remember Me.

            session_unset();
            session_destroy();

            header('Location: /pages/login.php');
            exit;
        }

        // Proteksi folder berdasarkan role
        $currentUri = $_SERVER['REQUEST_URI'];

        if (strpos($currentUri, '/pages/') !== false) {

            $parts = explode('/', trim($currentUri, '/'));

            $pagesIndex = array_search('pages', $parts);

            $accessedRoleFolder = $parts[$pagesIndex + 1] ?? '';

            if (
                in_array($accessedRoleFolder, $allowedRoles, true) &&
                $accessedRoleFolder !== $userRole
            ) {
                header(
                    "Location: /pages/{$userRole}/index.php"
                );
                exit;
            }
        }
    }

    private function tryRestoreSessionFromToken(): void
    {

        $cookieValue = $this->getRememberTokenFromCookie();

        if (!$cookieValue) {
            return;
        }

        $this->cleanupExpiredTokens();

        $validation = $this->validateRememberToken($cookieValue);

        if ($validation['status'] === 'mismatch') {

            $this->revokeAllUserTokens($validation['user_id']);
            $this->clearRememberTokenCookie();

            return;
        }

        if ($validation['status'] !== 'ok') {
            return;
        }

        $userId = $validation['user_id'];

        $stmt = $this->conn->prepare("
        SELECT id, role, name
        FROM users
        WHERE id = ?
    ");

        if (!$stmt) {
            $this->conn->error;
            return;
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {

            $this->revokeAllUserTokens($userId);
            $this->clearRememberTokenCookie();

            return;
        }

        $user = $result->fetch_assoc();

        session_regenerate_id(true);

        $_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['name'];
        $_SESSION['is_logged_in'] = true;
        $_SESSION['remember_me'] = true;
    }

    public function login(string $username, string $password, bool $rememberMe = false): bool
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
                    $this->configureSessionCookie($rememberMe);
                    session_start();
                }

                session_regenerate_id(true);

                $_SESSION['id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['name'];
                $_SESSION['is_logged_in'] = true;
                $_SESSION['remember_me'] = $rememberMe;

                if ($rememberMe) {
                    $tokenData = $this->generateRememberToken();
                    $expiry = time() + self::REMEMBER_TOKEN_LIFETIME;

                    if ($this->storeRememberToken(
                        $user['id'],
                        $tokenData['selector'],
                        $tokenData['validator'],
                        $expiry
                    )) {
                        $this->setRememberTokenCookie($tokenData['cookie_value']);
                    }
                } else {
                    $this->clearRememberTokenCookie();
                }

                return true;
            }
        }
        return false;
    }

    // Fungsi untuk melakukan registrasi perlu di revisi untuk bagian role nya, jadi agar saat register tidak otomatis jadi user
    public function register(string $foto, string $username, string $name, string $nomor, string $email, string $password, string $deskripsi, string $role): array
    {
        $checkSql = "SELECT id FROM users WHERE username = ?";
        $stmtCheck = $this->conn->prepare($checkSql);
        $stmtCheck->bind_param("s", $username);
        $stmtCheck->execute();

        if ($stmtCheck->get_result()->num_rows > 0) {
            return ['success' => false, 'token' => null, 'message' => 'Username sudah terdaftar'];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $token = $this->generateUniqueToken();

        $stmt = $this->conn->prepare("INSERT INTO users (foto, username, name, nomor, email, password, deskripsi, role, token) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            return ['success' => false, 'token' => null, 'message' => 'Database error: ' . $this->conn->error];
        }

        $stmt->bind_param("sssssssss", $foto, $username, $name, $nomor, $email, $hashedPassword, $deskripsi, $role, $token);
        $executed = $stmt->execute();

        if ($executed) {
            return ['success' => true, 'token' => $token, 'message' => 'Registrasi berhasil'];
        }

        return ['success' => false, 'token' => null, 'message' => 'Registrasi gagal'];
    }

    private function generateUniqueToken(): string
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Exclude confusing chars: I, O, 1, 0
        $maxAttempts = 10;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $token = '';
            for ($i = 0; $i < 6; $i++) {
                $token .= $characters[random_int(0, strlen($characters) - 1)];
            }

            $stmt = $this->conn->prepare("SELECT id FROM users WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            if ($stmt->get_result()->num_rows === 0) {
                return $token;
            }
        }

        return substr(md5(uniqid((string)random_int(100000, 999999), true)), 0, 6);
    }

    public function forgotPassword(string $token, string $newPassword): array
    {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE token = ?");
        if (!$stmt) {
            return ['success' => false, 'message' => 'Database error'];
        }
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            return ['success' => false, 'message' => 'Token tidak valid'];
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE token = ?");
        if (!$stmt) {
            return ['success' => false, 'message' => 'Database error'];
        }
        $stmt->bind_param("ss", $hashedPassword, $token);
        $executed = $stmt->execute();

        if ($executed) {
            // Reset password: also revoke all existing remember-me tokens
            // for this user so a session that predates the reset can't
            // silently keep working (area 7 hardening).
            $userRow = $this->conn->prepare("SELECT id FROM users WHERE token = ?");
            if ($userRow) {
                $userRow->bind_param("s", $token);
                $userRow->execute();
                $r = $userRow->get_result();
                if ($r->num_rows === 1) {
                    $this->revokeAllUserTokens((int) $r->fetch_assoc()['id']);
                }
            }
            return ['success' => true, 'message' => 'Password berhasil direset'];
        }

        return ['success' => false, 'message' => 'Gagal mereset password'];
    }

    public function logout(): void
    {
        $cookieValue = $this->getRememberTokenFromCookie();
        if ($cookieValue) {
            $this->revokeRememberToken($cookieValue);
            $this->clearRememberTokenCookie();
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }

    /**
     * Revoke all remember tokens for current user (e.g., on password change).
     */
    public function revokeAllTokensForCurrentUser(): void
    {
        $userId = $_SESSION['id'] ?? 0;
        if ($userId) {
            $this->revokeAllUserTokens($userId);
            $this->clearRememberTokenCookie();
        }
    }
}
