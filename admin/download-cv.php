<?php
/**
 * Admin Authorized CV Downloader (admin/download-cv.php)
 * Prevents unauthorized public access to applicant CV files.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/includes/auth.php';

$file = trim($_GET['file'] ?? '');

// Prevent path traversal
$file = basename($file);

if (empty($file)) {
    die("Error: No file specified.");
}

$filepath = CV_UPLOADS_PATH . DIRECTORY_SEPARATOR . $file;

if (!file_exists($filepath)) {
    die("Error: The requested CV file does not exist on the server.");
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $filepath);
finfo_close($finfo);

header('Content-Description: File Transfer');
header('Content-Type: ' . ($mimeType ?: 'application/octet-stream'));
header('Content-Disposition: attachment; filename="' . $file . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));
readfile($filepath);
exit;

