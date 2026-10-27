<?php
/**
 * Collection Controller
 * Manages curated book collections/lists
 */
class CollectionController extends Controller {
    
    private $db;
    
    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * List all collections
     */
    public function index() {
        $stmt = $this->db->query("
            SELECT c.*, COUNT(cb.book_id) as book_count
            FROM collections c
            LEFT JOIN collection_books cb ON c.id = cb.collection_id
            WHERE c.featured = 1 OR c.sort_order > 0
            GROUP BY c.id
            ORDER BY c.sort_order ASC, c.created_at DESC
        ");
        $collections = $stmt->fetchAll();
        
        $this->render('collections/index', [
            'title' => 'Collections - ' . SITE_NAME,
            'collections' => $collections,
        ]);
    }
    
    /**
     * Show single collection with books
     */
    public function show($slug) {
        $stmt = $this->db->prepare("
            SELECT c.*, COUNT(cb.book_id) as book_count
            FROM collections c
            LEFT JOIN collection_books cb ON c.id = cb.collection_id
            WHERE c.slug = ?
            GROUP BY c.id
        ");
        $stmt->execute([$slug]);
        $collection = $stmt->fetch();
        
        if (!$collection) {
            http_response_code(404);
            echo 'Collection not found';
            exit;
        }
        
        // Get books in collection
        $stmt = $this->db->prepare("
            SELECT b.*, a.name as author_name, a.slug as author_slug
            FROM books b
            JOIN collection_books cb ON b.id = cb.book_id
            LEFT JOIN authors a ON b.author_id = a.id
            WHERE cb.collection_id = ? AND b.status = 'published'
            ORDER BY cb.sort_order ASC, b.title ASC
        ");
        $stmt->execute([$collection['id']]);
        $books = $stmt->fetchAll();
        
        $this->render('collections/show', [
            'title' => htmlspecialchars($collection['title']) . ' - ' . SITE_NAME,
            'description' => $collection['description'],
            'collection' => $collection,
            'books' => $books,
        ]);
    }
    
    /**
     * Admin: Create collection
     */
    public function adminCreate() {
        $this->checkAdmin();
        
        $this->render('admin/collections/create', [
            'title' => 'Create Collection - Admin',
        ], 'admin');
    }
    
    /**
     * Admin: Store collection
     */
    public function adminStore() {
        $this->checkAdmin();
        
        if (!$this->isPost()) {
            $this->redirect(BASE_URL . '/admin/collections/create');
        }
        
        $csrf = new CSRF();
        if (!$csrf->validateToken($this->getPost('csrf_token'))) {
            $this->flash('error', 'Invalid CSRF token');
            $this->redirect(BASE_URL . '/admin/collections/create');
        }
        
        $title = trim((string)$this->getPost('title'));
        $description = trim((string)$this->getPost('description'));
        $featured = $this->getPost('featured') ? 1 : 0;
        
        if (empty($title)) {
            $this->flash('error', 'Title is required');
            $this->redirect(BASE_URL . '/admin/collections/create');
        }
        
        $slug = Slug::generate($title, 'collections', 'slug');
        
        $stmt = $this->db->prepare("
            INSERT INTO collections (title, slug, description, featured)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$title, $slug, $description, $featured]);
        
        $this->flash('success', 'Collection created successfully');
        $this->redirect(BASE_URL . '/admin/collections');
    }
    
    /**
     * Admin: List collections
     */
    public function adminIndex() {
        $this->checkAdmin();
        
        $collections = $this->db->query("
            SELECT c.*, COUNT(cb.book_id) as book_count
            FROM collections c
            LEFT JOIN collection_books cb ON c.id = cb.collection_id
            GROUP BY c.id
            ORDER BY c.sort_order ASC, c.created_at DESC
        ")->fetchAll();
        
        $this->render('admin/collections/index', [
            'title' => 'Collections - Admin',
            'collections' => $collections,
        ], 'admin');
    }
    
    /**
     * Admin: Add book to collection
     */
    public function adminAddBook($collectionId) {
        $this->checkAdmin();
        
        if (!$this->isPost()) {
            $this->json(['error' => 'Invalid request']);
        }
        
        $bookId = (int)$this->getPost('book_id');
        
        try {
            $stmt = $this->db->prepare("
                INSERT INTO collection_books (collection_id, book_id)
                VALUES (?, ?)
            ");
            $stmt->execute([$collectionId, $bookId]);
            
            $this->json(['success' => true]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $this->json(['error' => 'Book already in collection']);
            } else {
                $this->json(['error' => 'Failed to add book']);
            }
        }
    }
    
    /**
     * Admin: Remove book from collection
     */
    public function adminRemoveBook($collectionId, $bookId) {
        $this->checkAdmin();
        
        $stmt = $this->db->prepare("
            DELETE FROM collection_books 
            WHERE collection_id = ? AND book_id = ?
        ");
        $stmt->execute([$collectionId, $bookId]);
        
        $this->flash('success', 'Book removed from collection');
        $this->redirect(BASE_URL . '/admin/collections/' . $collectionId . '/edit');
    }
    
    private function checkAdmin() {
        $user = $this->getSession('user');
        if (!$user || $user['role'] !== 'admin') {
            $this->redirect(BASE_URL . '/');
        }
    }
}
?>
