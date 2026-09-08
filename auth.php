<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

/**
 * Enforces admin authentication on protected pages.
 * Redirects to the login page if not authenticated.
 */
function requireAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: admin_login.php');
        exit;
    }
}

/**
 * Validates admin credentials against the database.
 * 
 * @param string $username Username or Email
 * @param string $password Plaintext password
 * @param PDO $pdo Active PDO instance
 * @return bool|string True on success, or error string on failure
 */
function attemptAdminLogin($username, $password, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :identifier OR email = :identifier LIMIT 1");
        $stmt->execute(['identifier' => $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            return true;
        }

        return "Invalid username or password.";
    } catch (PDOException $e) {
        return "Authentication error: " . $e->getMessage();
    }
}

/**
 * Destroys active admin session and logs out user.
 */
function logoutAdmin() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header('Location: admin_login.php');
    exit;
}