<?php
/**
 * Comment Controller
 * Handles book comments and reviews with ratings
 */
class CommentController extends Controller {
    
    private $db;
    
    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * API: Post a comment/review
     */
    public function store($bookSlug) {
        if (!$this->isPost()) {
            $this->json(['error' => 'Invalid request']);
        }
        
        $user = $this->getSession('user');
        if (!$user) {
            $this->json(['error' => 'Please login to comment']);
        }
        
        $csrf = new CSRF();
        if (!$csrf->validateToken($this->getPost('csrf_token'))) {
            $this->json(['error' => 'Invalid token']);
        }
        
        // Get book
        $stmt = $this->db->prepare("SELECT id FROM books WHERE slug = ? AND status = 'published'");
        $stmt->execute([$bookSlug]);
        $book = $stmt->fetch();
        
        if (!$book) {
            $this->json(['error' => 'Book not found']);
        }
        
        $content = trim((string)$this->getPost('content'));
        $rating = (int)$this->getPost('rating');
        $parentId = (int)$this->getPost('parent_id') ?: null;
        
        if (strlen($content) < 10) {
            $this->json(['error' => 'Comment must be at least 10 characters']);
        }
        
        if ($rating < 0 || $rating > 5) {
            $rating = null;
        }
        
        // Check for spam (simple rate limiting)
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count FROM comments 
            WHERE user_id = ? AND created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
        ");
        $stmt->execute([$user['id']]);
        $recent = $stmt->fetch();
        
        if ($recent['count'] >= 3) {
            $this->json(['error' => 'Please wait before posting another comment']);
        }
        
        $stmt = $this->db->prepare("
            INSERT INTO comments (book_id, user_id, parent_id, content, rating, status)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([$book['id'], $user['id'], $parentId, $content, $rating]);
        
        $this->json([
            'success' => true,
            'message' => 'Comment submitted and pending approval',
            'comment_id' => (int)$this->db->lastInsertId()
        ]);
    }
    
    /**
     * Get approved comments for a book
     */
    public function getForBook($bookSlug) {
        $stmt = $this->db->prepare("
            SELECT c.*, u.name as user_name
            FROM comments c
            JOIN books b ON c.book_id = b.id
            JOIN users u ON c.user_id = u.id
            WHERE b.slug = ? AND c.status = 'approved' AND c.parent_id IS NULL
            ORDER BY c.created_at DESC
            LIMIT 50
        ");
        $stmt->execute([$bookSlug]);
        $comments = $stmt->fetchAll();
        
        // Get replies
        foreach ($comments as &$comment) {
            $stmt = $this->db->prepare("
                SELECT c.*, u.name as user_name
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.parent_id = ? AND c.status = 'approved'
                ORDER BY c.created_at ASC
            ");
            $stmt->execute([$comment['id']]);
            $comment['replies'] = $stmt->fetchAll();
        }
        
        // Get average rating
        $stmt = $this->db->prepare("
            SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews
            FROM comments
            JOIN books ON comments.book_id = books.id
            WHERE books.slug = ? AND rating IS NOT NULL AND status = 'approved'
        ");
        $stmt->execute([$bookSlug]);
        $rating = $stmt->fetch();
        
        $this->json([
            'comments' => $comments,
            'rating' => [
                'average' => round($rating['avg_rating'], 1),
                'total' => (int)$rating['total_reviews']
            ]
        ]);
    }
    
    /**
     * Admin: List pending comments
     */
    public function adminPending() {
        $this->checkAdmin();
        
        $comments = $this->db->query("
            SELECT c.*, b.title as book_title, b.slug as book_slug, u.name as user_name
            FROM comments c
            JOIN books b ON c.book_id = b.id
            JOIN users u ON c.user_id = u.id
            WHERE c.status = 'pending'
            ORDER BY c.created_at DESC
        ")->fetchAll();
        
        $this->render('admin/comments/pending', [
            'title' => 'Pending Comments - Admin',
            'comments' => $comments,
        ], 'admin');
    }
    
    /**
     * Admin: Approve comment
     */
    public function adminApprove($commentId) {
        $this->checkAdmin();
        
        if (!$this->isPost()) {
            $this->redirect(BASE_URL . '/admin/comments/pending');
        }
        
        $stmt = $this->db->prepare("UPDATE comments SET status = 'approved' WHERE id = ?");
        $stmt->execute([$commentId]);
        
        $this->flash('success', 'Comment approved');
        $this->redirect(BASE_URL . '/admin/comments/pending');
    }
    
    /**
     * Admin: Reject comment
     */
    public function adminReject($commentId) {
        $this->checkAdmin();
        
        if (!$this->isPost()) {
            $this->redirect(BASE_URL . '/admin/comments/pending');
        }
        
        $stmt = $this->db->prepare("UPDATE comments SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$commentId]);
        
        $this->flash('success', 'Comment rejected');
        $this->redirect(BASE_URL . '/admin/comments/pending');
    }
    
    private function checkAdmin() {
        $user = $this->getSession('user');
        if (!$user || $user['role'] !== 'admin') {
            $this->redirect(BASE_URL . '/');
        }
    }
}
?>
