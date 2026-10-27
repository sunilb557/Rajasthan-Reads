<?php
/**
 * OCR Helper
 * Handles PDF text extraction using Tesseract.js CLI or pdftotext
 * Falls back to database storage for search indexing
 */
class OCR {
    
    /**
     * Extract text from PDF using available tools
     * Priority: pdftotext (fastest) > Tesseract OCR > null
     * 
     * @param string $pdfPath Path to PDF file
     * @return array ['success' => bool, 'pages' => array, 'error' => string]
     */
    public static function extractText($pdfPath) {
        if (!file_exists($pdfPath)) {
            return ['success' => false, 'pages' => [], 'error' => 'PDF file not found'];
        }
        
        // Try pdftotext first (fastest, works with text-based PDFs)
        $result = self::extractWithPdftotext($pdfPath);
        if ($result['success']) {
            return $result;
        }
        
        // If pdftotext returns minimal text, it might be scanned/image PDF
        // Return the pdftotext result anyway - Tesseract would be too slow for web
        return $result;
    }
    
    /**
     * Extract text using pdftotext command
     */
    private static function extractWithPdftotext($pdfPath) {
        $output = [];
        $returnVar = 0;
        
        // Check if pdftotext is available
        exec('which pdftotext 2>/dev/null || where pdftotext 2>nul', $output, $returnVar);
        $pdftotextAvailable = !empty($output[0]);
        
        if (!$pdftotextAvailable) {
            // Try to find in common locations on Windows
            $possiblePaths = [
                'C:\\Program Files\\Xpdf\\bin32\\pdftotext.exe',
                'C:\\Program Files\\Xpdf\\bin64\\pdftotext.exe',
                'C:\\xpdf\\pdftotext.exe',
            ];
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $pdftotextAvailable = true;
                    break;
                }
            }
        }
        
        if (!$pdftotextAvailable) {
            return ['success' => false, 'pages' => [], 'error' => 'pdftotext not found. Install Xpdf tools.'];
        }
        
        // Extract text page by page
        $pages = [];
        $pageNum = 1;
        $maxPages = 500; // Safety limit
        
        while ($pageNum <= $maxPages) {
            $tempFile = tempnam(sys_get_temp_dir(), 'pdf_text_');
            
            $cmd = sprintf(
                'pdftotext -f %d -l %d -layout "%s" "%s" 2>&1',
                $pageNum,
                $pageNum,
                escapeshellarg($pdfPath),
                escapeshellarg($tempFile)
            );
            
            exec($cmd, $output, $returnVar);
            
            if ($returnVar !== 0 || !file_exists($tempFile)) {
                @unlink($tempFile);
                break; // No more pages or error
            }
            
            $text = file_get_contents($tempFile);
            @unlink($tempFile);
            
            if (empty(trim($text))) {
                break; // Empty page indicates end
            }
            
            $pages[$pageNum] = self::cleanText($text);
            $pageNum++;
        }
        
        return [
            'success' => !empty($pages),
            'pages' => $pages,
            'error' => empty($pages) ? 'No text extracted' : null,
        ];
    }
    
    /**
     * Clean extracted text
     */
    private static function cleanText($text) {
        // Remove excessive whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        // Remove non-printable characters
        $text = preg_replace('/[^\x20-\x7E\x{0900}-\x{097F}\s]/u', '', $text);
        return trim($text);
    }
    
    /**
     * Index book content for search
     * 
     * @param int $bookId Book ID
     * @param string $pdfPath Path to PDF
     * @return array ['success' => bool, 'pages_indexed' => int]
     */
    public static function indexBook($bookId, $pdfPath) {
        $db = Database::getInstance()->getConnection();
        
        // Update status to processing
        $stmt = $db->prepare("UPDATE books SET ocr_status = 'processing' WHERE id = ?");
        $stmt->execute([$bookId]);
        
        try {
            $result = self::extractText($pdfPath);
            
            if (!$result['success']) {
                $stmt = $db->prepare("UPDATE books SET ocr_status = 'failed' WHERE id = ?");
                $stmt->execute([$bookId]);
                return ['success' => false, 'pages_indexed' => 0, 'error' => $result['error']];
            }
            
            // Clear existing index
            $stmt = $db->prepare("DELETE FROM book_search_index WHERE book_id = ?");
            $stmt->execute([$bookId]);
            
            // Insert new index entries
            $pagesIndexed = 0;
            $insertStmt = $db->prepare("
                INSERT INTO book_search_index (book_id, page_number, content, content_hash)
                VALUES (?, ?, ?, ?)
            ");
            
            foreach ($result['pages'] as $pageNum => $content) {
                if (strlen($content) > 50) { // Only index pages with substantial content
                    $hash = hash('sha256', $content);
                    $insertStmt->execute([$bookId, $pageNum, $content, $hash]);
                    $pagesIndexed++;
                }
            }
            
            // Update book status
            $stmt = $db->prepare("
                UPDATE books 
                SET ocr_status = 'completed', 
                    ocr_indexed_at = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$bookId]);
            
            return [
                'success' => true,
                'pages_indexed' => $pagesIndexed,
            ];
            
        } catch (Exception $e) {
            $stmt = $db->prepare("UPDATE books SET ocr_status = 'failed' WHERE id = ?");
            $stmt->execute([$bookId]);
            
            return [
                'success' => false,
                'pages_indexed' => 0,
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Search within book content
     * 
     * @param string $query Search query
     * @param int|null $bookId Optional specific book
     * @return array Search results
     */
    public static function search($query, $bookId = null) {
        $db = Database::getInstance()->getConnection();
        
        $sql = "
            SELECT 
                bsi.book_id,
                b.title as book_title,
                b.slug as book_slug,
                bsi.page_number,
                bsi.content,
                MATCH(bsi.content) AGAINST(? IN BOOLEAN MODE) as relevance
            FROM book_search_index bsi
            JOIN books b ON bsi.book_id = b.id
            WHERE MATCH(bsi.content) AGAINST(? IN BOOLEAN MODE)
            AND b.status = 'published'
        ";
        
        $params = [$query, $query];
        
        if ($bookId) {
            $sql .= " AND bsi.book_id = ?";
            $params[] = $bookId;
        }
        
        $sql .= " ORDER BY relevance DESC, bsi.page_number ASC LIMIT 50";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        $results = $stmt->fetchAll();
        
        // Highlight search terms in results
        foreach ($results as &$result) {
            $result['excerpt'] = SearchHelper::highlight($result['content'], $query);
            $result['excerpt'] = SearchHelper::excerpt($result['excerpt'], 200);
        }
        
        return $results;
    }
}
?>
