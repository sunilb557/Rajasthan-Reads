<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'rajasthan_reads');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site Configuration
define('SITE_NAME', 'RajasthanReads');
define('SITE_TAGLINE', 'Read the Soul of Rajasthan');

// Dynamic Site URL detection for ngrok/production compatibility
$protocol = 'http';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $protocol = 'https';
} elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $protocol = 'https';
}

$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Get the base directory (e.g., /rajasthan_reads)
// We assume this file is in the project root
$project_root = str_replace('\\', '/', __DIR__);
$server_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$subdir = str_replace($server_root, '', $project_root);
$subdir = '/' . ltrim($subdir, '/');

define('SITE_URL', $protocol . '://' . $host . $subdir);
define('SITE_DESCRIPTION', 'A digital cultural library platform dedicated to Rajasthan heritage, literature, and cultural archives.');

// Routing
define('BASE_URL', rtrim(SITE_URL, '/'));
define('BASE_PATH', $subdir);

// Paths
define('ROOT_PATH', __DIR__ . '/');
define('APP_PATH', ROOT_PATH . 'app/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('STORAGE_PATH', ROOT_PATH . 'storage/');
define('UPLOAD_PATH', PUBLIC_PATH . 'assets/uploads/');

// SEO Defaults
define('DEFAULT_TITLE', SITE_NAME . ' - ' . SITE_TAGLINE);
define('DEFAULT_DESCRIPTION', SITE_DESCRIPTION);
define('DEFAULT_KEYWORDS', 'Rajasthan, culture, heritage, literature, folk stories, historical books, manuscripts, poetry, educational books, cultural archives');

// Pagination
define('ITEMS_PER_PAGE', 12);

// Email Configuration (for future use)
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('FROM_EMAIL', 'noreply@rajasthanreads.com');
define('FROM_NAME', SITE_NAME);

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_LIFETIME', 3600); // 1 hour

// PWA Configuration
define('PWA_NAME', 'RajasthanReads');
define('PWA_SHORT_NAME', 'RajReads');
define('PWA_THEME_COLOR', '#C2410C');
define('PWA_BACKGROUND_COLOR', '#FFF7ED');
?>
