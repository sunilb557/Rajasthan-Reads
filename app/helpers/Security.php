<?php
/**
 * Security Helper
 * Provides rate limiting, CSP headers, and security utilities
 */
class Security {
    
    const RATE_LIMIT_ATTEMPTS = 5;
    const RATE_LIMIT_WINDOW = 300; // 5 minutes
    
    /**
     * Rate limiting for login attempts
     * 
     * @param string $identifier IP address or username
     * @param string $type 'login', 'api', 'upload'
     * @return bool True if allowed, false if rate limited
     */
    public static function checkRateLimit($identifier, $type = 'login') {
        $key = "rate_limit_{$type}_" . md5($identifier);
        $attempts = (int)($_SESSION[$key]['count'] ?? 0);
        $lastAttempt = $_SESSION[$key]['time'] ?? 0;
        
        // Reset if window has passed
        if (time() - $lastAttempt > self::RATE_LIMIT_WINDOW) {
            $_SESSION[$key] = ['count' => 1, 'time' => time()];
            return true;
        }
        
        // Check limit
        if ($attempts >= self::RATE_LIMIT_ATTEMPTS) {
            return false;
        }
        
        // Increment attempts
        $_SESSION[$key]['count'] = $attempts + 1;
        $_SESSION[$key]['time'] = time();
        
        return true;
    }
    
    /**
     * Get remaining attempts before rate limit
     */
    public static function getRemainingAttempts($identifier, $type = 'login') {
        $key = "rate_limit_{$type}_" . md5($identifier);
        $attempts = (int)($_SESSION[$key]['count'] ?? 0);
        $lastAttempt = $_SESSION[$key]['time'] ?? 0;
        
        // Reset if window has passed
        if (time() - $lastAttempt > self::RATE_LIMIT_WINDOW) {
            return self::RATE_LIMIT_ATTEMPTS;
        }
        
        return max(0, self::RATE_LIMIT_ATTEMPTS - $attempts);
    }
    
    /**
     * Clear rate limit for identifier
     */
    public static function clearRateLimit($identifier, $type = 'login') {
        $key = "rate_limit_{$type}_" . md5($identifier);
        unset($_SESSION[$key]);
    }
    
    /**
     * Set security headers
     */
    public static function setSecurityHeaders() {
        // Content Security Policy
        $csp = "default-src 'self'; ";
        $csp .= "script-src 'self' 'unsafe-inline' cdn.jsdelivr.net cdnjs.cloudflare.com; ";
        $csp .= "style-src 'self' 'unsafe-inline' cdn.jsdelivr.net cdnjs.cloudflare.com fonts.googleapis.com; ";
        $csp .= "img-src 'self' data: blob: https://images.unsplash.com; ";
        $csp .= "font-src 'self' cdnjs.cloudflare.com fonts.gstatic.com; ";
        $csp .= "connect-src 'self' ws: wss:; ";
        $csp .= "manifest-src 'self'; ";
        $csp .= "frame-ancestors 'none';";
        
        header("Content-Security-Policy: $csp");
        
        // X-Frame-Options
        header("X-Frame-Options: DENY");
        
        // X-Content-Type-Options
        header("X-Content-Type-Options: nosniff");
        
        // X-XSS-Protection
        header("X-XSS-Protection: 1; mode=block");
        
        // Referrer Policy
        header("Referrer-Policy: strict-origin-when-cross-origin");
        
        // Permissions Policy
        header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
    }
    
    /**
     * Generate nonce for inline scripts
     */
    public static function generateNonce() {
        $nonce = bin2hex(random_bytes(16));
        $_SESSION['csp_nonce'] = $nonce;
        return $nonce;
    }
    
    /**
     * Validate file upload
     * 
     * @param array $file $_FILES entry
     * @param array $allowedTypes MIME types
     * @param int $maxSize Maximum file size in bytes
     * @return array ['valid' => bool, 'error' => string]
     */
    public static function validateUpload($file, $allowedTypes, $maxSize) {
        // Check upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors = [
                UPLOAD_ERR_INI_SIZE => 'File too large (server limit)',
                UPLOAD_ERR_FORM_SIZE => 'File too large (form limit)',
                UPLOAD_ERR_PARTIAL => 'Upload interrupted',
                UPLOAD_ERR_NO_FILE => 'No file uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Server configuration error',
                UPLOAD_ERR_CANT_WRITE => 'Server write error',
            ];
            return ['valid' => false, 'error' => $errors[$file['error']] ?? 'Unknown upload error'];
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            return ['valid' => false, 'error' => 'File too large. Maximum: ' . self::formatBytes($maxSize)];
        }
        
        // Check MIME type using finfo (more secure than $_FILES)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            return ['valid' => false, 'error' => 'Invalid file type. Allowed: ' . implode(', ', $allowedTypes)];
        }
        
        // Verify extension matches MIME
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $extToMime = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
        ];
        
        if (isset($extToMime[$ext]) && $mimeType !== $extToMime[$ext]) {
            return ['valid' => false, 'error' => 'File extension does not match content'];
        }
        
        return ['valid' => true, 'error' => null];
    }
    
    /**
     * Sanitize filename
     */
    public static function sanitizeFilename($filename) {
        // Remove path components
        $filename = basename($filename);
        
        // Remove non-alphanumeric except dash, underscore, dot
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
        
        // Prevent double extensions
        $filename = preg_replace('/\.(php|php3|php4|php5|phtml|pl|py|jsp|asp|sh|cgi)/i', '.txt', $filename);
        
        return $filename;
    }
    
    /**
     * Format bytes to human readable
     */
    private static function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        
        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }
        
        return round($bytes, 2) . ' ' . $units[$unitIndex];
    }
    
    /**
     * Log security event
     */
    public static function logEvent($type, $message, $data = []) {
        $logFile = STORAGE_PATH . 'logs/security.log';
        
        // Ensure log directory exists
        if (!is_dir(dirname($logFile))) {
            @mkdir(dirname($logFile), 0755, true);
        }
        
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'type' => $type,
            'message' => $message,
            'data' => $data,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        ];
        
        file_put_contents($logFile, json_encode($entry) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
?>
