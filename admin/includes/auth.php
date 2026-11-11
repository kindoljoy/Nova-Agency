<?php
/**
 * Admin Authentication Guard
 */
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/helpers.php';

if (!is_admin()) {
    set_flash('error', 'Please authenticate to access the administration console.');
    header('Location: ' . url('admin/login.php'));
    exit;
}

