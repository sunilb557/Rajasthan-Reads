<?php
/**
 * Analytics Helper
 * Tracks and reports reading analytics and engagement metrics
 */
class Analytics {
    
    /**
     * Track a reading session start
     */
    public static function startSession($userId, $bookId, $deviceType = 'desktop') {
        $db = Database::getInstance()->getConnection();
        
        // End any existing active session for this user/book
        self::endSession($userId, $bookId);
        
        $stmt = $db->prepare("
            INSERT INTO reading_sessions (user_id, book_id, device_type)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$userId, $bookId, $deviceType]);
        
        return (int)$db->lastInsertId();
    }
    
    /**
     * End a reading session
     */
    public static function endSession($userId, $bookId) {
        $db = Database::getInstance()->getConnection();
        
        // Find active session
        $stmt = $db->prepare("
            SELECT id, session_start FROM reading_sessions
            WHERE user_id = ? AND book_id = ? AND session_end IS NULL
            ORDER BY session_start DESC
            LIMIT 1
        ");
        $stmt->execute([$userId, $bookId]);
        $session = $stmt->fetch();
        
        if ($session) {
            $endTime = time();
            $startTime = strtotime($session['session_start']);
            $duration = max(0, $endTime - $startTime);
            
            // Only count sessions longer than 10 seconds
            if ($duration > 10) {
                $stmt = $db->prepare("
                    UPDATE reading_sessions 
                    SET session_end = NOW(), 
                        duration_seconds = ?
                    WHERE id = ?
                ");
                $stmt->execute([$duration, $session['id']]);
                
                // Update book stats
                self::updateBookStats($bookId, $duration);
            } else {
                // Delete very short sessions (likely accidental)
                $stmt = $db->prepare("DELETE FROM reading_sessions WHERE id = ?");
                $stmt->execute([$session['id']]);
            }
        }
    }
    
    /**
     * Update book reading stats
     */
    private static function updateBookStats($bookId, $sessionDuration) {
        $db = Database::getInstance()->getConnection();
        
        // Update total reading time
        $stmt = $db->prepare("
            UPDATE books 
            SET total_reading_time = total_reading_time + ?
            WHERE id = ?
        ");
        $stmt->execute([$sessionDuration, $bookId]);
        
        // Update unique readers count
        $stmt = $db->prepare("
            UPDATE books b
            SET unique_readers = (
                SELECT COUNT(DISTINCT user_id) 
                FROM reading_sessions 
                WHERE book_id = ? AND user_id IS NOT NULL
            )
            WHERE b.id = ?
        ");
        $stmt->execute([$bookId, $bookId]);
    }
    
    /**
     * Track daily analytics
     */
    public static function trackDaily($type, $value = 1) {
        $db = Database::getInstance()->getConnection();
        $today = date('Y-m-d');
        
        $column = match($type) {
            'view' => 'total_views',
            'download' => 'total_downloads',
            'visitor' => 'unique_visitors',
            'new_book' => 'new_books',
            'new_user' => 'new_users',
            'reading_minutes' => 'total_reading_minutes',
            default => null
        };
        
        if (!$column) return;
        
        // Insert or update
        $stmt = $db->prepare("
            INSERT INTO analytics_daily (date, {$column})
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE {$column} = {$column} + VALUES({$column})
        ");
        $stmt->execute([$today, $value]);
    }
    
    /**
     * Get admin dashboard stats
     */
    public static function getDashboardStats($days = 30) {
        $db = Database::getInstance()->getConnection();
        
        // Daily stats
        $stmt = $db->prepare("
            SELECT 
                date,
                total_views,
                total_downloads,
                unique_visitors,
                new_books,
                new_users,
                total_reading_minutes
            FROM analytics_daily
            WHERE date >= DATE_SUB(NOW(), INTERVAL ? DAY)
            ORDER BY date ASC
        ");
        $stmt->execute([$days]);
        $dailyStats = $stmt->fetchAll();
        
        // Popular books this month
        $stmt = $db->query("
            SELECT 
                b.id, b.title, b.slug, b.view_count, b.download_count,
                a.name as author_name,
                COUNT(rs.id) as reading_sessions,
                SUM(rs.duration_seconds) as total_reading_time
            FROM books b
            LEFT JOIN authors a ON b.author_id = a.id
            LEFT JOIN reading_sessions rs ON b.id = rs.book_id
                AND rs.session_start >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            WHERE b.status = 'published'
            GROUP BY b.id
            ORDER BY b.view_count DESC
            LIMIT 10
        ");
        $popularBooks = $stmt->fetchAll();
        
        // User engagement
        $stmt = $db->query("
            SELECT 
                COUNT(DISTINCT user_id) as active_readers,
                AVG(duration_seconds) as avg_session_duration,
                SUM(duration_seconds) / 60 as total_reading_hours
            FROM reading_sessions
            WHERE session_start >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");
        $engagement = $stmt->fetch();
        
        return [
            'daily' => $dailyStats,
            'popular_books' => $popularBooks,
            'engagement' => [
                'active_readers' => (int)$engagement['active_readers'],
                'avg_session_minutes' => round($engagement['avg_session_duration'] / 60, 1),
                'total_reading_hours' => round($engagement['total_reading_hours'], 1),
            ],
        ];
    }
    
    /**
     * Get book analytics
     */
    public static function getBookStats($bookId) {
        $db = Database::getInstance()->getConnection();
        
        // Overall stats
        $stmt = $db->prepare("
            SELECT 
                b.view_count,
                b.download_count,
                b.total_reading_time,
                b.unique_readers,
                COUNT(rs.id) as total_sessions,
                AVG(rs.duration_seconds) as avg_session_duration,
                MAX(rs.duration_seconds) as longest_session
            FROM books b
            LEFT JOIN reading_sessions rs ON b.id = rs.book_id
            WHERE b.id = ?
            GROUP BY b.id
        ");
        $stmt->execute([$bookId]);
        $stats = $stmt->fetch();
        
        if (!$stats) {
            return null;
        }
        
        // Reading trend (last 30 days)
        $stmt = $db->prepare("
            SELECT 
                DATE(session_start) as date,
                COUNT(*) as sessions,
                SUM(duration_seconds) / 60 as reading_minutes
            FROM reading_sessions
            WHERE book_id = ?
            AND session_start >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(session_start)
            ORDER BY date ASC
        ");
        $stmt->execute([$bookId]);
        $trend = $stmt->fetchAll();
        
        // Completion rate estimate (users who read > 80% of pages)
        $stmt = $db->prepare("
            SELECT 
                COUNT(DISTINCT CASE WHEN rs.duration_seconds > (b.pages * 60 * 0.8) THEN rs.user_id END) as likely_completed,
                COUNT(DISTINCT rs.user_id) as total_readers
            FROM reading_sessions rs
            JOIN books b ON rs.book_id = b.id
            WHERE b.id = ?
        ");
        $stmt->execute([$bookId]);
        $completion = $stmt->fetch();
        
        $completionRate = $completion['total_readers'] > 0 
            ? round(($completion['likely_completed'] / $completion['total_readers']) * 100, 1)
            : 0;
        
        return [
            'overview' => [
                'views' => (int)$stats['view_count'],
                'downloads' => (int)$stats['download_count'],
                'unique_readers' => (int)$stats['unique_readers'],
                'total_sessions' => (int)$stats['total_sessions'],
                'total_reading_hours' => round($stats['total_reading_time'] / 3600, 1),
                'avg_session_minutes' => round($stats['avg_session_duration'] / 60, 1),
                'completion_rate' => $completionRate,
            ],
            'trend' => $trend,
        ];
    }
}
?>
