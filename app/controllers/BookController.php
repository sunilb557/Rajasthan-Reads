<?php
class BookController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->loadModel('Book');
    }

    public function index() {
        $page = (int)($this->getGet('page') ?? 1);
        if ($page < 1) {
            $page = 1;
        }

        $limit = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;

        $books = $this->model->getPublished($limit, $offset);
        $total = $this->model->countPublished();
        $totalPages = (int)ceil($total / $limit);

        $this->render('books/index', [
            'title' => 'Books - ' . SITE_NAME,
            'books' => $books,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    public function show($slug) {
        $book = $this->model->findPublishedBySlug($slug);
        if (!$book) {
            http_response_code(404);
            echo 'Book not found';
            exit;
        }

        $title = $book['seo_title'] ?: $book['title'] . ' - ' . SITE_NAME;
        $description = $book['seo_description'] ?: (substr($book['description'], 0, 155) ?: DEFAULT_DESCRIPTION);
        $canonical = BASE_URL . '/books/' . $book['slug'];

        $schemaJson = [
            '@context' => 'https://schema.org',
            '@type' => 'Book',
            'name' => $book['title'],
            'description' => $book['description'] ?: '',
            'url' => $canonical,
            'author' => $book['author_name'] ? [
                '@type' => 'Person',
                'name' => $book['author_name'],
            ] : null,
            'inLanguage' => $book['language'] ?? 'hindi',
            'datePublished' => $book['publication_year'] ? $book['publication_year'] . '-01-01' : $book['created_at'],
            'numberOfPages' => $book['pages'] ?: null,
            'isbn' => $book['isbn'] ?: null,
            'publisher' => [
                '@type' => 'Organization',
                'name' => SITE_NAME,
            ],
        ];

        // Get related books (same category or author)
        $relatedBooks = [];
        if (!empty($book['category_id']) || !empty($book['author_id'])) {
            $db = Database::getInstance()->getConnection();
            $sql = "
                SELECT b.*, a.name as author_name, a.slug as author_slug
                FROM books b
                LEFT JOIN authors a ON b.author_id = a.id
                WHERE b.status = 'published'
                AND b.id != ?
                AND (b.category_id = ? OR b.author_id = ?)
                ORDER BY b.featured DESC, b.created_at DESC
                LIMIT 4
            ";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $book['id'],
                $book['category_id'] ?: 0,
                $book['author_id'] ?: 0
            ]);
            $relatedBooks = $stmt->fetchAll();
        }

        // Increment view count
        $db->prepare("UPDATE books SET view_count = view_count + 1 WHERE id = ?")
           ->execute([$book['id']]);

        $this->render('books/show', [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'schemaJson' => $schemaJson,
            'book' => $book,
            'relatedBooks' => $relatedBooks,
        ]);
    }

    public function reader($slug) {
        $book = $this->model->findPublishedBySlug($slug);
        if (!$book) {
            http_response_code(404);
            echo 'Book not found';
            exit;
        }

        if (empty($book['file_path'])) {
            http_response_code(404);
            echo 'PDF file not available';
            exit;
        }

        $title = ($book['seo_title'] ?: $book['title']) . ' - Reader - ' . SITE_NAME;
        $this->render('books/reader', [
            'title' => $title,
            'book' => $book,
        ]);
    }
}
?>
