<?php
/**
 * Admin Dashboard Controller
 * Provides statistics and overview for admin panel
 */
class AdminDashboardController extends AdminController {
    private $db;
    
    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Admin dashboard with statistics
     */
    public function index() {
        // Statistics
        $stats = [
            'total_books' => $this->getCount('books'),
            'published_books' => $this->getCount('books', "status = 'published'"),
            'draft_books' => $this->getCount('books', "status = 'draft'"),
            'total_authors' => $this->getCount('authors'),
            'total_categories' => $this->getCount('categories'),
            'total_users' => $this->getCount('users'),
            'admin_users' => $this->getCount('users', "role = 'admin'"),
        ];
        
        // Recent books
        $recentBooks = $this->db->query("
            SELECT b.id, b.title, b.status, b.created_at, a.name as author_name
            FROM books b
            LEFT JOIN authors a ON b.author_id = a.id
            ORDER BY b.created_at DESC
            LIMIT 5
        ")->fetchAll();
        
        // Popular books (by views)
        $popularBooks = $this->db->query("
            SELECT b.id, b.title, b.view_count, b.download_count, a.name as author_name
            FROM books b
            LEFT JOIN authors a ON b.author_id = a.id
            ORDER BY b.view_count DESC
            LIMIT 5
        ")->fetchAll();
        
        // Monthly uploads chart data
        $monthlyData = $this->getMonthlyStats();
        
        // Category distribution
        $categoryStats = $this->db->query("
            SELECT c.name, COUNT(b.id) as count
            FROM categories c
            LEFT JOIN books b ON c.id = b.category_id
            GROUP BY c.id, c.name
            ORDER BY count DESC
            LIMIT 8
        ")->fetchAll();
        
        $this->render('admin/dashboard/index', [
            'title' => 'Dashboard - Admin',
            'stats' => $stats,
            'recentBooks' => $recentBooks,
            'popularBooks' => $popularBooks,
            'monthlyData' => $monthlyData,
            'categoryStats' => $categoryStats,
        ], 'admin');
    }
    
    /**
     * Get count from table with optional condition
     */
    private function getCount($table, $condition = null) {
        $sql = "SELECT COUNT(*) as count FROM {$table}";
        if ($condition) {
            $sql .= " WHERE {$condition}";
        }
        $result = $this->db->query($sql)->fetch();
        return (int)$result['count'];
    }
    
    /**
     * Get monthly upload statistics
     */
    private function getMonthlyStats() {
        $data = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-{$i} months"));
            $labels[] = date('M Y', strtotime("-{$i} months"));
            
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM books 
                WHERE DATE_FORMAT(created_at, '%Y-%m') = ?
            ");
            $stmt->execute([$month]);
            $result = $stmt->fetch();
            $data[] = (int)$result['count'];
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
    
    /**
     * API endpoint for real-time stats
     */
    public function stats() {
        if (!$this->isAjax()) {
            $this->json(['error' => 'Invalid request']);
        }
        
        $stats = [
            'total_books' => $this->getCount('books'),
            'published_books' => $this->getCount('books', "status = 'published'"),
            'total_users' => $this->getCount('users'),
        ];
        
        $this->json($stats);
    }
}
?>
