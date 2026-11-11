<?php
/**
 * NOVA Creative Digital Agency
 * Global Application Configuration
 */

// Paths
define('ROOT_PATH', dirname(__DIR__));
define('UPLOADS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'uploads');
define('PROJECT_UPLOADS_PATH', UPLOADS_PATH . DIRECTORY_SEPARATOR . 'projects');
define('ARTICLE_UPLOADS_PATH', UPLOADS_PATH . DIRECTORY_SEPARATOR . 'articles');
define('CV_UPLOADS_PATH', UPLOADS_PATH . DIRECTORY_SEPARATOR . 'cv');

// Session configuration
$sessionPath = ROOT_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'sessions';
if (!is_dir($sessionPath)) {
    @mkdir($sessionPath, 0777, true);
}
if (is_dir($sessionPath) && is_writable($sessionPath)) {
    ini_set('session.save_path', $sessionPath);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE && php_sapi_name() !== 'cli') {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    session_start();
}

// Environment & Debugging
define('APP_ENV', 'development'); // 'development' or 'production'
define('APP_DEBUG', true);

if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// App Information
define('APP_NAME', 'NOVA®');
define('APP_TAGLINE', 'We Build Brands. We Create Experiences.');
define('APP_VERSION', '1.0.0');

// Dynamic Base URL detection
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$subDir = trim($scriptDir, '/');

// Base URL handling for root or subdirectory in XAMPP
if (empty($subDir) || $subDir === 'admin') {
    $baseUrl = $protocol . $host . '/';
} else {
    // If in /Nova Agency/admin, get parent
    $parts = explode('/', $subDir);
    if (end($parts) === 'admin') {
        array_pop($parts);
    }
    $baseUrl = $protocol . $host . '/' . (count($parts) ? implode('/', $parts) . '/' : '');
}
define('BASE_URL', $baseUrl);

// Database Driver configuration: 'auto', 'mysql', or 'sqlite'
// 'auto': tries MySQL first; if unavailable, seamlessly uses SQLite.
define('DB_DRIVER', 'auto');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'nova_agency');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
define('SQLITE_FILE', ROOT_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'nova.sqlite');

// Upload limits
define('MAX_IMAGE_SIZE', 5 * 1024 * 1024); // 5 MB
define('MAX_CV_SIZE', 10 * 1024 * 1024);   // 10 MB
define('ALLOWED_IMAGE_EXT', ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
define('ALLOWED_CV_EXT', ['pdf', 'doc', 'docx']);

// Timezone
date_default_timezone_set('Asia/Jakarta');
