<?php
class CategoryController extends Controller {
    private $categoryModel;

    public function __construct() {
        parent::__construct();
        $this->categoryModel = new Category();
    }

    public function index() {
        $categories = $this->categoryModel->getAllWithCounts();
        $this->render('categories/index', [
            'title' => 'Categories - ' . SITE_NAME,
            'categories' => $categories,
        ]);
    }

    public function show($slug) {
        $category = $this->categoryModel->findBySlugWithCounts($slug);
        if (!$category) {
            http_response_code(404);
            echo 'Category not found';
            exit;
        }

        $sql = "SELECT * FROM books WHERE status = 'published' AND category_id = ? ORDER BY created_at DESC";
        $stmt = Database::getInstance()->getConnection()->prepare($sql);
        $stmt->execute([$category['id']]);
        $books = $stmt->fetchAll();

        $this->render('categories/show', [
            'title' => $category['seo_title'] ?: ($category['name'] . ' - ' . SITE_NAME),
            'category' => $category,
            'books' => $books,
        ]);
    }
}
?>
