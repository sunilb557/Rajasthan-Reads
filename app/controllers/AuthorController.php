<?php
class AuthorController extends Controller {
    private $authorModel;
    private $bookModel;

    public function __construct() {
        parent::__construct();
        $this->authorModel = new Author();
        $this->bookModel = new Book();
    }

    public function index() {
        $authors = $this->authorModel->getAllWithCounts();
        $this->render('authors/index', [
            'title' => 'Authors - ' . SITE_NAME,
            'authors' => $authors,
        ]);
    }

    public function show($slug) {
        $author = $this->authorModel->findBySlugWithCounts($slug);
        if (!$author) {
            http_response_code(404);
            echo 'Author not found';
            exit;
        }

        $sql = "SELECT * FROM books WHERE status = 'published' AND author_id = ? ORDER BY created_at DESC";
        $stmt = Database::getInstance()->getConnection()->prepare($sql);
        $stmt->execute([$author['id']]);
        $books = $stmt->fetchAll();

        $this->render('authors/show', [
            'title' => $author['seo_title'] ?: ($author['name'] . ' - ' . SITE_NAME),
            'author' => $author,
            'books' => $books,
        ]);
    }
}
?>
