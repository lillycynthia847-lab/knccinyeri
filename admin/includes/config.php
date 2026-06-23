<?php
// ─── Database ──────────────────────────────────────────────────────────────
$host   = '127.0.0.1';
$dbname = 'kncci_db';
$user   = 'root';
$pass   = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// ─── Site / Mail Config ────────────────────────────────────────────────────
define('SITE_NAME',     'KNCCI Nyeri Admin');
define('SITE_MAIN_URL', 'http://localhost/kncci_nyeri'); // Update to https://knccinyeri.co.ke on launch
define('SITE_URL',      'http://localhost/kncci_nyeri/admin'); // Update to https://admin.knccinyeri.co.ke on launch
define('ADMIN_EMAIL',   'admin@knccinyeri.co.ke');

// ─── Session & Auth Guard ──────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pages that do NOT require authentication
$publicPages = ['login.php', 'forgot-password.php', 'reset-password.php'];
$currentFile = basename($_SERVER['PHP_SELF']);

// Only run the auth guard if the user is actively inside the /admin/ folder
if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    if (!in_array($currentFile, $publicPages) && empty($_SESSION['admin_logged_in'])) {
        header('Location: ' . SITE_URL . '/login.php');
        exit;
    }
}
?>
