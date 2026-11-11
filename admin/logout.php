<?php
/**
 * Admin Logout (admin/logout.php)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';

// Unset admin session variables
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_role']);

// Regenerate session id
if (session_status() === PHP_SESSION_ACTIVE) {
    session_regenerate_id(true);
}

set_flash('info', 'You have been securely logged out.');
header('Location: ' . url('admin/login.php'));
exit;

