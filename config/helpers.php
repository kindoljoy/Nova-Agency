<?php
/**
 * NOVA Creative Digital Agency
 * Global Helper Functions & Security Utilities
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

/**
 * Escape HTML output to prevent XSS
 */
function e(?string $string): string {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate or get CSRF token
 */
function csrf_token(): string {
    if (!isset($_SESSION)) {
        $_SESSION = [];
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Render CSRF input tag
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Verify CSRF token
 */
function verify_csrf(): bool {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }
    }
    return true;
}

/**
 * Flash messaging system
 */
function set_flash(string $type, string $message): void {
    if (!isset($_SESSION)) {
        $_SESSION = [];
    }
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function get_flashes(): array {
    if (!isset($_SESSION['flash'])) {
        return [];
    }
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

function has_flashes(): bool {
    return isset($_SESSION['flash']) && !empty($_SESSION['flash']);
}

/**
 * Slugify text
 */
function slugify(string $text): string {
    // Replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim
    $text = trim($text, '-');
    // Remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // Lowercase
    $text = strtolower($text);
    return empty($text) ? 'n-a-' . time() : $text;
}

/**
 * Secure file uploader
 */
function handle_file_upload(array $file, string $targetDir, array $allowedExts, int $maxBytes): array {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'error' => 'Invalid file upload parameters.'];
    }

    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'error' => 'No file was uploaded.'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'File upload error code: ' . $file['error']];
    }

    if ($file['size'] > $maxBytes) {
        $mb = round($maxBytes / (1024 * 1024), 1);
        return ['success' => false, 'error' => "File size exceeds the {$mb}MB limit."];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExts, true)) {
        return ['success' => false, 'error' => 'File format .' . $ext . ' is not permitted. Allowed: ' . implode(', ', $allowedExts)];
    }

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Generate cryptographically unique safe filename
    $newFileName = bin2hex(random_bytes(16)) . '.' . $ext;
    $destination = rtrim($targetDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newFileName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'error' => 'Failed to save uploaded file. Check directory permissions.'];
    }

    return [
        'success'   => true,
        'filename'  => $newFileName,
        'path'      => $destination,
        'orig_name' => $file['name'],
        'size'      => $file['size'],
        'ext'       => $ext
    ];
}

/**
 * Authentication checks
 */
function is_admin(): bool {
    return !empty($_SESSION['admin_id']);
}

function current_admin(): ?array {
    if (!is_admin()) {
        return null;
    }
    return [
        'id'        => $_SESSION['admin_id'],
        'username'  => $_SESSION['admin_username'] ?? 'admin',
        'full_name' => $_SESSION['admin_name'] ?? 'Administrator',
        'email'     => $_SESSION['admin_email'] ?? '',
        'role'      => $_SESSION['admin_role'] ?? 'admin'
    ];
}

function require_admin_auth(): void {
    if (!is_admin()) {
        set_flash('error', 'You must be logged in to access the administration dashboard.');
        header('Location: ' . url('admin/login.php'));
        exit;
    }
}

/**
 * URL and asset helpers
 */
function url(string $path = ''): string {
    $path = ltrim($path, '/');
    return BASE_URL . $path;
}

function asset(string $path): string {
    return url('assets/' . ltrim($path, '/'));
}

function upload_url(string $type, ?string $filename): string {
    if (empty($filename)) {
        return asset('images/placeholder.svg');
    }
    return url('uploads/' . trim($type, '/') . '/' . ltrim($filename, '/'));
}

/**
 * Active navigation helper
 */
function is_active(string $page): string {
    $current = basename($_SERVER['PHP_SELF'] ?? '');
    return ($current === $page) ? 'text-accent border-accent font-semibold' : 'text-offwhite/80 hover:text-white border-transparent';
}

/**
 * Fetch a setting from site_settings table
 */
function get_setting(string $key, string $default = ''): string {
    static $settingsCache = null;
    if ($settingsCache === null) {
        $settingsCache = [];
        try {
            $db = getDB();
            $stmt = $db->query("SELECT setting_key, setting_value FROM site_settings");
            while ($row = $stmt->fetch()) {
                $settingsCache[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            // Table may not exist yet during setup
        }
    }
    return $settingsCache[$key] ?? $default;
}
