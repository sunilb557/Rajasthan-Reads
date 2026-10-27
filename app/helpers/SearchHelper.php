<?php
/**
 * Search Helper
 * Provides text highlighting and search utilities
 */
class SearchHelper {
    
    /**
     * Highlight search terms in text
     * 
     * @param string $text Text to highlight
     * @param string $query Search query
     * @return string Highlighted text
     */
    public static function highlight($text, $query) {
        if (empty($query)) {
            return $text;
        }
        
        $terms = explode(' ', trim($query));
        $terms = array_filter($terms, function($term) {
            return strlen($term) > 2;
        });
        
        if (empty($terms)) {
            return $text;
        }
        
        $pattern = '/(' . implode('|', array_map('preg_quote', $terms)) . ')/i';
        return preg_replace($pattern, '<mark style="background: #fed7aa; padding: 0 2px;">$1</mark>', $text);
    }
    
    /**
     * Extract excerpt around search term
     * 
     * @param string $text Full text
     * @param string $query Search query
     * @param int $length Excerpt length
     * @return string Excerpt
     */
    public static function excerpt($text, $query, $length = 150) {
        if (empty($query)) {
            return substr($text, 0, $length) . (strlen($text) > $length ? '...' : '');
        }
        
        $text = strip_tags($text);
        $pos = stripos($text, $query);
        
        if ($pos === false) {
            return substr($text, 0, $length) . (strlen($text) > $length ? '...' : '');
        }
        
        $start = max(0, $pos - 50);
        $excerpt = substr($text, $start, $length);
        
        if ($start > 0) {
            $excerpt = '...' . $excerpt;
        }
        if ($start + $length < strlen($text)) {
            $excerpt .= '...';
        }
        
        return $excerpt;
    }
    
    /**
     * Calculate relevance score for sorting
     * 
     * @param array $book Book data
     * @param string $query Search query
     * @return int Score
     */
    public static function score($book, $query) {
        $score = 0;
        $queryLower = mb_strtolower($query);
        
        // Exact title match = highest score
        if (mb_stripos($book['title'], $query) === 0) {
            $score += 100;
        } elseif (mb_stripos($book['title'], $query) !== false) {
            $score += 50;
        }
        
        // Author match
        if (!empty($book['author_name']) && mb_stripos($book['author_name'], $query) !== false) {
            $score += 30;
        }
        
        // Description match
        if (!empty($book['description']) && mb_stripos($book['description'], $query) !== false) {
            $score += 20;
        }
        
        // Featured books get bonus
        if (!empty($book['featured'])) {
            $score += 10;
        }
        
        return $score;
    }
}
?>
