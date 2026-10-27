<?php
/**
 * Search Controller
 * Handles book search with FULLTEXT and filters
 */
class SearchController extends Controller {
    private $db;
    
    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Main search page
     */
    public function index() {
        $query = trim((string)$this->getGet('q'));
        $category = (int)$this->getGet('category');
        $author = (int)$this->getGet('author');
        $district = (int)$this->getGet('district');
        $language = (string)$this->getGet('language');
        $page = max(1, (int)$this->getGet('page'));
        
        $limit = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        
        $results = [];
        $total = 0;
        
        if ($query !== '' || $category || $author || $district || $language) {
            // Build search query
            $searchResults = $this->performSearch([
                'query' => $query,
                'category' => $category,
                'author' => $author,
                'district' => $district,
                'language' => $language,
                'limit' => $limit,
                'offset' => $offset,
            ]);
            
            $results = $searchResults['results'];
            $total = $searchResults['total'];
        }
        
        // Get filter options
        $categories = $this->db->query("SELECT id, name FROM categories ORDER BY name ASC")->fetchAll();
        $authors = $this->db->query("SELECT id, name FROM authors ORDER BY name ASC")->fetchAll();
        $districts = $this->db->query("SELECT id, name FROM districts ORDER BY name ASC")->fetchAll();
        
        $totalPages = (int)ceil($total / $limit);
        
        $this->render('search/index', [
            'title' => ($query ? "Search: $query - " : 'Search - ') . SITE_NAME,
            'query' => $query,
            'results' => $results,
            'total' => $total,
            'page' => $page,
            'totalPages' => $totalPages,
            'categories' => $categories,
            'authors' => $authors,
            'districts' => $districts,
            'selectedCategory' => $category,
            'selectedAuthor' => $author,
            'selectedDistrict' => $district,
            'selectedLanguage' => $language,
            'description' => "Search results for: $query",
        ]);
    }
    
    /**
     * AJAX autocomplete endpoint
     */
    public function autocomplete() {
        if (!$this->isAjax()) {
            $this->json(['error' => 'Invalid request']);
        }
        
        $query = trim((string)$this->getGet('q'));
        
        if (strlen($query) < 2) {
            $this->json(['suggestions' => []]);
        }
        
        // Search books
        $books = $this->db->prepare("
            SELECT title, slug, 'book' as type
            FROM books 
            WHERE status = 'published' 
            AND (title LIKE ? OR title_hindi LIKE ?)
            LIMIT 5
        ");
        $likeQuery = '%' . $query . '%';
        $books->execute([$likeQuery, $likeQuery]);
        
        // Search authors
        $authors = $this->db->prepare("
            SELECT name as title, slug, 'author' as type
            FROM authors 
            WHERE name LIKE ? OR name_hindi LIKE ?
            LIMIT 3
        ");
        $authors->execute([$likeQuery, $likeQuery]);
        
        $suggestions = array_merge(
            $books->fetchAll(),
            $authors->fetchAll()
        );
        
        $this->json(['suggestions' => $suggestions]);
    }
    
    /**
     * Perform search with filters
     */
    private function performSearch($params) {
        $query = $params['query'];
        $category = $params['category'];
        $author = $params['author'];
        $district = $params['district'];
        $language = $params['language'];
        $limit = $params['limit'];
        $offset = $params['offset'];
        
        $where = ["b.status = 'published'"];
        $bindings = [];
        
        // FULLTEXT search
        if ($query !== '') {
            $where[] = "(MATCH(b.title, b.description) AGAINST(? IN NATURAL LANGUAGE MODE) OR b.title LIKE ? OR b.description LIKE ?)";
            $bindings[] = $query;
            $bindings[] = '%' . $query . '%';
            $bindings[] = '%' . $query . '%';
        }
        
        // Filters
        if ($category) {
            $where[] = "b.category_id = ?";
            $bindings[] = $category;
        }
        
        if ($author) {
            $where[] = "b.author_id = ?";
            $bindings[] = $author;
        }
        
        if ($district) {
            $where[] = "b.district_id = ?";
            $bindings[] = $district;
        }
        
        if ($language) {
            $where[] = "b.language = ?";
            $bindings[] = $language;
        }
        
        $whereClause = implode(' AND ', $where);
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM books b WHERE $whereClause";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($bindings);
        $total = (int)$countStmt->fetch()['total'];
        
        // Get results
        $sql = "
            SELECT b.*, a.name as author_name, a.slug as author_slug,
                   c.name as category_name, c.slug as category_slug,
                   d.name as district_name
            FROM books b
            LEFT JOIN authors a ON b.author_id = a.id
            LEFT JOIN categories c ON b.category_id = c.id
            LEFT JOIN districts d ON b.district_id = d.id
            WHERE $whereClause
            ORDER BY b.featured DESC, b.created_at DESC
            LIMIT ? OFFSET ?
        ";
        
        $stmt = $this->db->prepare($sql);
        $i = 1;
        foreach ($bindings as $binding) {
            $stmt->bindValue($i++, $binding);
        }
        $stmt->bindValue($i++, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue($i++, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        return [
            'results' => $results,
            'total' => $total,
        ];
    }
    
    /**
     * OCR Search - Search inside PDF content
     */
    public function ocrSearch() {
        $query = trim((string)$this->getGet('q'));
        $bookId = (int)$this->getGet('book');
        
        if (strlen($query) < 3) {
            $this->render('search/ocr', [
                'title' => 'Search Inside Books - ' . SITE_NAME,
                'query' => $query,
                'results' => [],
                'bookId' => $bookId,
            ]);
            return;
        }
        
        $results = OCR::search($query, $bookId ?: null);
        
        // Get book info for results
        if (!empty($results)) {
            $bookIds = array_unique(array_column($results, 'book_id'));
            $placeholders = implode(',', array_fill(0, count($bookIds), '?'));
            
            $stmt = $this->db->prepare("
                SELECT id, title, slug, cover_image FROM books
                WHERE id IN ($placeholders)
            ");
            $stmt->execute($bookIds);
            $books = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            foreach ($results as &$result) {
                $result['book'] = $books[$result['book_id']] ?? null;
            }
        }
        
        $this->render('search/ocr', [
            'title' => 'Search: ' . htmlspecialchars($query) . ' - ' . SITE_NAME,
            'query' => $query,
            'results' => $results,
            'bookId' => $bookId,
            'total' => count($results),
        ]);
    }
}
?>
