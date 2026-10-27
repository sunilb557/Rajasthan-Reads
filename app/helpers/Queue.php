<?php
/**
 * Queue Helper
 * Database-based job queue for background processing
 * Handles OCR indexing, image processing, notifications
 */
class Queue {
    
    const MAX_ATTEMPTS = 3;
    const BATCH_SIZE = 5;
    
    /**
     * Add a job to the queue
     * 
     * @param string $type Job type: 'ocr_index', 'image_process', 'notification'
     * @param array $payload Job data
     * @param int $delayMinutes Delay before processing
     * @return int Job ID
     */
    public static function push($type, $payload, $delayMinutes = 0) {
        $db = Database::getInstance()->getConnection();
        
        $scheduledAt = date('Y-m-d H:i:s', strtotime("+{$delayMinutes} minutes"));
        
        $stmt = $db->prepare("
            INSERT INTO job_queue (job_type, payload, scheduled_at, max_attempts)
            VALUES (?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $type,
            json_encode($payload),
            $scheduledAt,
            self::MAX_ATTEMPTS
        ]);
        
        return (int)$db->lastInsertId();
    }
    
    /**
     * Get pending jobs
     * 
     * @param string|null $type Filter by type
     * @param int $limit Number of jobs to fetch
     * @return array Jobs
     */
    public static function getPending($type = null, $limit = self::BATCH_SIZE) {
        $db = Database::getInstance()->getConnection();
        
        $sql = "
            SELECT * FROM job_queue
            WHERE status = 'pending'
            AND scheduled_at <= NOW()
            AND attempts < max_attempts
        ";
        
        $params = [];
        if ($type) {
            $sql .= " AND job_type = ?";
            $params[] = $type;
        }
        
        $sql .= " ORDER BY created_at ASC LIMIT ?";
        $stmt = $db->prepare($sql);
        $i = 1;
        foreach ($params as $param) {
            $stmt->bindValue($i++, $param);
        }
        $stmt->bindValue($i++, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Mark job as processing
     */
    public static function startProcessing($jobId) {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            UPDATE job_queue 
            SET status = 'processing', 
                attempts = attempts + 1,
                processed_at = NOW()
            WHERE id = ?
        ");
        
        return $stmt->execute([$jobId]);
    }
    
    /**
     * Mark job as completed
     */
    public static function complete($jobId) {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            UPDATE job_queue 
            SET status = 'completed'
            WHERE id = ?
        ");
        
        return $stmt->execute([$jobId]);
    }
    
    /**
     * Mark job as failed
     */
    public static function fail($jobId, $errorMessage) {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            UPDATE job_queue 
            SET status = 'failed',
                error_message = ?
            WHERE id = ?
        ");
        
        return $stmt->execute([$errorMessage, $jobId]);
    }
    
    /**
     * Process a single job
     * 
     * @param array $job Job data from getPending
     * @return bool Success
     */
    public static function processJob($job) {
        $payload = json_decode($job['payload'], true);
        
        self::startProcessing($job['id']);
        
        try {
            switch ($job['job_type']) {
                case 'ocr_index':
                    return self::processOcrJob($job['id'], $payload);
                    
                case 'image_process':
                    return self::processImageJob($job['id'], $payload);
                    
                case 'notification':
                    return self::processNotificationJob($job['id'], $payload);
                    
                default:
                    throw new Exception("Unknown job type: {$job['job_type']}");
            }
        } catch (Exception $e) {
            self::fail($job['id'], $e->getMessage());
            return false;
        }
    }
    
    /**
     * Process OCR indexing job
     */
    private static function processOcrJob($jobId, $payload) {
        $bookId = $payload['book_id'] ?? null;
        $pdfPath = $payload['pdf_path'] ?? null;
        
        if (!$bookId || !$pdfPath) {
            throw new Exception('Missing book_id or pdf_path in payload');
        }
        
        $result = OCR::indexBook($bookId, $pdfPath);
        
        if ($result['success']) {
            self::complete($jobId);
            Security::logEvent('ocr_complete', "OCR completed for book {$bookId}", [
                'book_id' => $bookId,
                'pages' => $result['pages_indexed']
            ]);
            return true;
        } else {
            throw new Exception($result['error'] ?? 'OCR failed');
        }
    }
    
    /**
     * Process image processing job
     */
    private static function processImageJob($jobId, $payload) {
        $imagePath = $payload['image_path'] ?? null;
        $sizes = $payload['sizes'] ?? ['thumbnail', 'small', 'medium', 'large'];
        
        if (!$imagePath) {
            throw new Exception('Missing image_path in payload');
        }
        
        try {
            // Process each size
            foreach ($sizes as $size) {
                Image::resize($imagePath, $size);
            }
            
            self::complete($jobId);
            return true;
        } catch (Exception $e) {
            throw new Exception('Image processing failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Process notification job
     */
    private static function processNotificationJob($jobId, $payload) {
        // Placeholder for email/push notifications
        // Could integrate with email service, Firebase, etc.
        
        self::complete($jobId);
        return true;
    }
    
    /**
     * Run the worker - process pending jobs
     * Call this via cron job or CLI
     * 
     * @param int $maxJobs Maximum jobs to process
     * @return array Stats
     */
    public static function work($maxJobs = 10) {
        $stats = [
            'processed' => 0,
            'failed' => 0,
            'errors' => []
        ];
        
        $jobs = self::getPending(null, $maxJobs);
        
        foreach ($jobs as $job) {
            try {
                if (self::processJob($job)) {
                    $stats['processed']++;
                } else {
                    $stats['failed']++;
                    $stats['errors'][] = "Job {$job['id']} failed";
                }
            } catch (Exception $e) {
                $stats['failed']++;
                $stats['errors'][] = "Job {$job['id']}: " . $e->getMessage();
            }
        }
        
        return $stats;
    }
    
    /**
     * Get queue statistics
     */
    public static function getStats() {
        $db = Database::getInstance()->getConnection();
        
        $sql = "
            SELECT 
                status,
                job_type,
                COUNT(*) as count
            FROM job_queue
            GROUP BY status, job_type
        ";
        
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Retry failed jobs
     */
    public static function retryFailed($maxAgeHours = 24) {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            UPDATE job_queue
            SET status = 'pending',
                attempts = 0,
                error_message = NULL,
                scheduled_at = NOW()
            WHERE status = 'failed'
            AND created_at > DATE_SUB(NOW(), INTERVAL ? HOUR)
            AND attempts < max_attempts
        ");
        
        $stmt->execute([$maxAgeHours]);
        return $stmt->rowCount();
    }
    
    /**
     * Clean old completed jobs
     */
    public static function cleanOld($days = 7) {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            DELETE FROM job_queue
            WHERE status IN ('completed', 'failed')
            AND created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        
        $stmt->execute([$days]);
        return $stmt->rowCount();
    }
}
?>
