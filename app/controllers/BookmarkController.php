<?php
/**
 * Bookmark Controller
 * Handles user bookmarks for books with page numbers and notes
 */
class BookmarkController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->checkAuth();
    }
    
    /**
     * List user's bookmarks
     */
    public function index() {
        $userId = $this->getSession('user')['id'];
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            SELECT b.*, bk.title as book_title, bk.slug as book_slug, 
                   bk.cover_image, a.name as author_name
            FROM bookmarks b
            JOIN books bk ON b.book_id = bk.id
            LEFT JOIN authors a ON bk.author_id = a.id
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$userId]);
        $bookmarks = $stmt->fetchAll();
        
        $this->render('bookmarks/index', [
            'title' => 'My Bookmarks - ' . SITE_NAME,
            'bookmarks' => $bookmarks,
        ]);
    }
    
    /**
     * API: Create or update bookmark
     */
    public function store($bookSlug) {
        if (!$this->isPost()) {
            $this->json(['error' => 'Invalid request']);
        }
        
        $csrf = new CSRF();
        if (!$csrf->validateToken($this->getPost('csrf_token'))) {
            $this->json(['error' => 'Invalid token']);
        }
        
        $userId = $this->getSession('user')['id'];
        $db = Database::getInstance()->getConnection();
        
        // Get book ID from slug
        $stmt = $db->prepare("SELECT id FROM books WHERE slug = ?");
        $stmt->execute([$bookSlug]);
        $book = $stmt->fetch();
        
        if (!$book) {
            $this->json(['error' => 'Book not found']);
        }
        
        $bookId = $book['id'];
        $pageNumber = (int)$this->getPost('page');
        $note = trim((string)$this->getPost('note'));
        
        if ($pageNumber < 1) {
            $this->json(['error' => 'Invalid page number']);
        }
        
        // Check if bookmark exists
        $stmt = $db->prepare("
            SELECT id FROM bookmarks 
            WHERE user_id = ? AND book_id = ? AND page_number = ?
        ");
        $stmt->execute([$userId, $bookId, $pageNumber]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // Update existing
            $stmt = $db->prepare("
                UPDATE bookmarks 
                SET note = ?, created_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$note, $existing['id']]);
            $message = 'Bookmark updated';
        } else {
            // Create new
            $stmt = $db->prepare("
                INSERT INTO bookmarks (user_id, book_id, page_number, note)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$userId, $bookId, $pageNumber, $note]);
            $message = 'Bookmark saved';
        }
        
        $this->json([
            'success' => true,
            'message' => $message,
            'bookmark_id' => $existing ? $existing['id'] : (int)$db->lastInsertId()
        ]);
    }
    
    /**
     * API: Get bookmarks for a book
     */
    public function getForBook($bookSlug) {
        $userId = $this->getSession('user')['id'] ?? null;
        
        if (!$userId) {
            $this->json(['bookmarks' => []]);
        }
        
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            SELECT b.id, b.page_number, b.note, b.created_at
            FROM bookmarks b
            JOIN books bk ON b.book_id = bk.id
            WHERE bk.slug = ? AND b.user_id = ?
            ORDER BY b.page_number ASC
        ");
        $stmt->execute([$bookSlug, $userId]);
        
        $this->json(['bookmarks' => $stmt->fetchAll()]);
    }
    
    /**
     * API: Delete bookmark
     */
    public function delete($bookmarkId) {
        if (!$this->isPost()) {
            $this->json(['error' => 'Invalid request']);
        }
        
        $csrf = new CSRF();
        if (!$csrf->validateToken($this->getPost('csrf_token'))) {
            $this->json(['error' => 'Invalid token']);
        }
        
        $userId = $this->getSession('user')['id'];
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("DELETE FROM bookmarks WHERE id = ? AND user_id = ?");
        $stmt->execute([$bookmarkId, $userId]);
        
        if ($stmt->rowCount() > 0) {
            $this->json(['success' => true, 'message' => 'Bookmark deleted']);
        } else {
            $this->json(['error' => 'Bookmark not found']);
        }
    }
}
?>
