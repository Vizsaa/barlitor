<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database credentials
$db_host = "localhost";
$db_port = "3306";
$db_username = "root";
$db_passwd = "";
$db_name = "db_sample";

// Create connection
$conn = mysqli_connect($db_host . ":" . $db_port, $db_username, $db_passwd, $db_name);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8mb4");

// ------------------------------------------------------------
// Helper Functions (defined only if not already defined)
// ------------------------------------------------------------

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin() {
        return (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
    }
}

if (!function_exists('redirect')) {
    function redirect($path) {
        header("Location: " . $path);
        exit();
    }
}

if (!function_exists('getCurrentUserId')) {
    function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
}

if (!function_exists('getCurrentUserName')) {
    function getCurrentUserName() {
        return $_SESSION['name'] ?? 'Guest';
    }
}

if (!function_exists('getCurrentUserAvatar')) {
    function getCurrentUserAvatar() {
        if (!empty($_SESSION['avatar'])) {
            $avatar = $_SESSION['avatar'];
            // Always prefix /shop/ if not already
            if (strpos($avatar, '/shop/') === 0) {
                return $avatar; // already absolute
            }
            return '/shop/' . ltrim($avatar, '/');
        }
        // Default placeholder
        return 'https://bootdey.com/img/Content/avatar/avatar1.png';
    }
}

?>
