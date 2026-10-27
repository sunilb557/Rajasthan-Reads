<?php
class AdminCategoryController extends AdminController {
    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAllWithCounts();

        $this->render('admin/categories/index', [
            'title' => 'Manage Categories - ' . SITE_NAME,
            'categories' => $categories,
        ], 'admin');
    }

    public function create() {
        $this->render('admin/categories/create', [
            'title' => 'Add Category - ' . SITE_NAME,
        ], 'admin');
    }

    public function store() {
        if (!$this->isPost()) {
            $this->redirect(BASE_URL . '/admin/categories/create');
        }

        $csrf = new CSRF();
        if (!$csrf->validateToken($this->getPost(CSRF_TOKEN_NAME))) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect(BASE_URL . '/admin/categories/create');
        }

        $name = trim((string)$this->getPost('name'));
        $name_hindi = trim((string)$this->getPost('name_hindi'));
        $slug = trim((string)$this->getPost('slug')) ?: Slug::generate($name, null, 'categories');
        $description = trim((string)$this->getPost('description'));

        if ($name === '') {
            $this->flash('error', 'Name is required.');
            $this->redirect(BASE_URL . '/admin/categories/create');
        }

        $sql = "INSERT INTO categories (name, name_hindi, slug, description, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $name_hindi, $slug, $description]);

        $this->flash('success', 'Category added successfully.');
        $this->redirect(BASE_URL . '/admin/categories');
    }

    public function edit($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([(int)$id]);
        $category = $stmt->fetch();

        if (!$category) {
            $this->flash('error', 'Category not found.');
            $this->redirect(BASE_URL . '/admin/categories');
        }

        $this->render('admin/categories/edit', [
            'title' => 'Edit Category - ' . SITE_NAME,
            'category' => $category,
        ], 'admin');
    }

    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect(BASE_URL . '/admin/categories');
        }

        $name = trim((string)$this->getPost('name'));
        $name_hindi = trim((string)$this->getPost('name_hindi'));
        $slug = trim((string)$this->getPost('slug'));
        $description = trim((string)$this->getPost('description'));

        $sql = "UPDATE categories SET name = ?, name_hindi = ?, slug = ?, description = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $name_hindi, $slug, $description, (int)$id]);

        $this->flash('success', 'Category updated successfully.');
        $this->redirect(BASE_URL . '/admin/categories');
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([(int)$id]);
        $this->flash('success', 'Category deleted successfully.');
        $this->redirect(BASE_URL . '/admin/categories');
    }
}
