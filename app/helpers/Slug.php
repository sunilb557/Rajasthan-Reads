<?php
/**
 * Slug Generator Helper
 * Converts text to URL-friendly slugs
 * Handles Hindi/Rajasthani transliteration
 */
class Slug {
    
    /**
     * Transliteration map for Hindi characters
     */
    private static $hindiToLatin = [
        // Vowels
        'अ' => 'a', 'आ' => 'aa', 'इ' => 'i', 'ई' => 'ee', 'उ' => 'u',
        'ऊ' => 'oo', 'ए' => 'e', 'ऐ' => 'ai', 'ओ' => 'o', 'औ' => 'au',
        'अं' => 'an', 'अः' => 'ah',
        
        // Consonants
        'क' => 'k', 'ख' => 'kh', 'ग' => 'g', 'घ' => 'gh', 'ङ' => 'ng',
        'च' => 'ch', 'छ' => 'chh', 'ज' => 'j', 'झ' => 'jh', 'ञ' => 'ny',
        'ट' => 't', 'ठ' => 'th', 'ड' => 'd', 'ढ' => 'dh', 'ण' => 'n',
        'त' => 'ta', 'थ' => 'tha', 'द' => 'da', 'ध' => 'dha', 'न' => 'na',
        'प' => 'p', 'फ' => 'ph', 'ब' => 'b', 'भ' => 'bh', 'म' => 'm',
        'य' => 'y', 'र' => 'r', 'ल' => 'l', 'व' => 'v', 'व' => 'w',
        'श' => 'sh', 'ष' => 'shh', 'स' => 's', 'ह' => 'h',
        'क्ष' => 'ksh', 'त्र' => 'tr', 'ज्ञ' => 'gy', 'श्र' => 'shr',
        
        // Matras (dependent vowels)
        'ा' => 'a', 'ि' => 'i', 'ी' => 'ee', 'ु' => 'u', 'ू' => 'oo',
        'े' => 'e', 'ै' => 'ai', 'ो' => 'o', 'ौ' => 'au', 'ं' => 'n', 'ः' => 'h',
        
        // Numbers
        '०' => '0', '१' => '1', '२' => '2', '३' => '3', '४' => '4',
        '५' => '5', '६' => '6', '७' => '7', '८' => '8', '९' => '9',
    ];
    
    /**
     * Generate slug from text
     * 
     * @param string $text Input text
     * @param int|null $id Optional ID for uniqueness check
     * @param string $table Table name for uniqueness check
     * @return string Generated slug
     */
    public static function generate($text, $id = null, $table = 'books') {
        // Transliterate Hindi/Rajasthani to Latin
        $text = self::transliterate($text);
        
        // Convert to lowercase
        $text = mb_strtolower($text, 'UTF-8');
        
        // Replace non-alphanumeric with hyphens
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        
        // Replace spaces with hyphens
        $text = preg_replace('/\s+/', '-', $text);
        
        // Remove multiple hyphens
        $text = preg_replace('/-+/', '-', $text);
        
        // Trim hyphens from ends
        $text = trim($text, '-');
        
        // Check uniqueness and append number if needed
        $baseSlug = $text;
        $counter = 2;
        
        while (self::exists($text, $id, $table)) {
            $text = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $text;
    }
    
    /**
     * Transliterate Hindi text to Latin characters
     */
    private static function transliterate($text) {
        // Apply transliteration map
        $text = str_replace(
            array_keys(self::$hindiToLatin),
            array_values(self::$hindiToLatin),
            $text
        );
        
        // Handle common Hindi combinations
        $text = preg_replace('/्/', '', $text); // Remove halant
        
        return $text;
    }
    
    /**
     * Check if slug already exists
     */
    private static function exists($slug, $excludeId = null, $table = 'books') {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT id FROM {$table} WHERE slug = ?";
        $params = [$slug];
        
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $sql .= " LIMIT 1";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch() !== false;
    }
    
    /**
     * Quick slug generation without DB check
     */
    public static function quick($text) {
        // Transliterate
        $text = self::transliterate($text);
        
        // Convert to lowercase
        $text = mb_strtolower($text, 'UTF-8');
        
        // Replace non-alphanumeric with hyphens
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        
        // Replace spaces with hyphens
        $text = preg_replace('/\s+/', '-', $text);
        
        // Remove multiple hyphens
        $text = preg_replace('/-+/', '-', $text);
        
        // Trim hyphens from ends
        return trim($text, '-');
    }
}
?>
