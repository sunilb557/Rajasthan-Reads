<?php
/**
 * Image Helper
 * Handles image uploads, resizing, and thumbnail generation
 */
class Image {
    
    const UPLOAD_DIR = 'books/covers/';
    const SIZES = [
        'thumbnail' => ['w' => 150, 'h' => 225],
        'small' => ['w' => 200, 'h' => 300],
        'medium' => ['w' => 300, 'h' => 450],
        'large' => ['w' => 400, 'h' => 600],
    ];
    
    /**
     * Upload and process cover image
     * 
     * @param array $file $_FILES['cover_image']
     * @return string|false Path to uploaded image or false on failure
     */
    public static function uploadCover($file) {
        if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($mimeType, $allowedTypes)) {
            return false;
        }
        
        // Create upload directory
        $uploadPath = UPLOAD_PATH . self::UPLOAD_DIR;
        foreach (array_keys(self::SIZES) as $size) {
            $dir = $uploadPath . $size . '/';
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
        }
        
        // Generate filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        
        // Get image info
        $srcImage = self::createImageFromFile($file['tmp_name'], $mimeType);
        if (!$srcImage) {
            return false;
        }
        
        $origWidth = imagesx($srcImage);
        $origHeight = imagesy($srcImage);
        
        // Generate thumbnails
        foreach (self::SIZES as $sizeName => $dimensions) {
            $resized = self::resizeImage(
                $srcImage, 
                $origWidth, 
                $origHeight, 
                $dimensions['w'], 
                $dimensions['h']
            );
            
            $destPath = $uploadPath . $sizeName . '/' . $filename;
            self::saveImage($resized, $destPath, $mimeType);
            imagedestroy($resized);
        }
        
        imagedestroy($srcImage);
        
        // Return path relative to public
        return 'assets/uploads/' . self::UPLOAD_DIR . 'large/' . $filename;
    }
    
    /**
     * Create image resource from file
     */
    private static function createImageFromFile($path, $mimeType) {
        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($path);
            case 'image/png':
                return imagecreatefrompng($path);
            case 'image/webp':
                return imagecreatefromwebp($path);
            default:
                return false;
        }
    }
    
    /**
     * Resize image maintaining aspect ratio
     */
    private static function resizeImage($src, $srcW, $srcH, $maxW, $maxH) {
        // Calculate new dimensions
        $ratio = min($maxW / $srcW, $maxH / $srcH);
        $newW = (int)($srcW * $ratio);
        $newH = (int)($srcH * $ratio);
        
        // Create new image
        $dst = imagecreatetruecolor($newW, $newH);
        
        // Handle transparency for PNG
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        
        // Resize
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);
        
        return $dst;
    }
    
    /**
     * Save image to file
     */
    private static function saveImage($image, $path, $mimeType) {
        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg($image, $path, 90);
                break;
            case 'image/png':
                imagepng($image, $path, 8);
                break;
            case 'image/webp':
                imagewebp($image, $path, 90);
                break;
        }
    }
    
    /**
     * Get image URL for a specific size
     */
    public static function url($path, $size = 'medium') {
        if (empty($path)) {
            return BASE_URL . '/assets/images/book-placeholder.jpg';
        }
        
        // Remove public/ from start if it exists
        $path = preg_replace('/^public\//', '', ltrim($path, '/'));
        
        // Replace size in path
        $path = str_replace('/large/', '/' . $size . '/', $path);
        
        return BASE_URL . '/' . $path;
    }
    
    /**
     * Delete all sizes of an image
     */
    public static function delete($path) {
        if (empty($path)) {
            return;
        }
        
        $basePath = str_replace('/large/', '/', $path);
        $uploadPath = UPLOAD_PATH . self::UPLOAD_DIR;
        $filename = basename($path);
        
        foreach (array_keys(self::SIZES) as $size) {
            $file = $uploadPath . $size . '/' . $filename;
            if (file_exists($file)) {
                @unlink($file);
            }
        }
    }
}
?>
