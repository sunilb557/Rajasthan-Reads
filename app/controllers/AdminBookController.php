<?php
class AdminBookController extends AdminController {
    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        $stmt = $this->db->prepare("SELECT id, title, slug, status, created_at FROM books ORDER BY created_at DESC");
        $stmt->execute();
        $books = $stmt->fetchAll();

        $this->render('admin/books/index', [
            'title' => 'Manage Books - ' . SITE_NAME,
            'books' => $books,
        ], 'admin');
    }

    public function create() {
        $authors = $this->db->query("SELECT id, name FROM authors ORDER BY name ASC")->fetchAll();
        $categories = $this->db->query("SELECT id, name FROM categories ORDER BY name ASC")->fetchAll();
        $districts = $this->db->query("SELECT id, name FROM districts ORDER BY name ASC")->fetchAll();

        $this->render('admin/books/create', [
            'title' => 'Add Book - ' . SITE_NAME,
            'authors' => $authors,
            'categories' => $categories,
            'districts' => $districts,
        ], 'admin');
    }

    public function store() {
        if (!$this->isPost()) {
            $this->redirect(BASE_URL . '/admin/books/create');
        }

        $csrf = new CSRF();
        if (!$csrf->validateToken($this->getPost(CSRF_TOKEN_NAME))) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect(BASE_URL . '/admin/books/create');
        }

        $title = trim((string)$this->getPost('title'));
        $slugInput = trim((string)$this->getPost('slug'));
        $authorId = (int)$this->getPost('author_id');
        $categoryId = (int)$this->getPost('category_id');
        $districtId = (int)$this->getPost('district_id');
        $description = trim((string)$this->getPost('description'));
        $status = (string)$this->getPost('status');

        if ($title === '') {
            $this->flash('error', 'Title is required.');
            $this->redirect(BASE_URL . '/admin/books/create');
        }

        // Auto-generate slug if not provided
        $slug = $slugInput ?: Slug::generate($title, null, 'books');

        if (!in_array($status, ['draft', 'published', 'archived'], true)) {
            $status = 'published';
        }

        $filePath = null;
        if (!empty($_FILES['pdf_file']['name'])) {
            if (!is_dir(UPLOAD_PATH)) {
                @mkdir(UPLOAD_PATH, 0777, true);
            }

            $ext = strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));
            if ($ext !== 'pdf') {
                $this->flash('error', 'Only PDF files are allowed.');
                $this->redirect(BASE_URL . '/admin/books/create');
            }

            $safeName = preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $_FILES['pdf_file']['name']);
            $filename = time() . '_' . $safeName;
            $destination = rtrim(UPLOAD_PATH, '/\\') . DIRECTORY_SEPARATOR . $filename;

            if (!move_uploaded_file($_FILES['pdf_file']['tmp_name'], $destination)) {
                $this->flash('error', 'Failed to upload PDF.');
                $this->redirect(BASE_URL . '/admin/books/create');
            }

            $filePath = 'public/assets/uploads/' . $filename;
        }

        // Handle cover image upload
        $coverImagePath = null;
        if (!empty($_FILES['cover_image']['name'])) {
            $coverImagePath = Image::uploadCover($_FILES['cover_image']);
        }

        $sql = "
            INSERT INTO books (title, slug, description, author_id, category_id, district_id, file_path, cover_image, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $title,
            $slug,
            $description,
            $authorId ?: null,
            $categoryId ?: null,
            $districtId ?: null,
            $filePath,
            $coverImagePath,
            $status,
        ]);

        $this->flash('success', 'Book created successfully.');
        $this->redirect(BASE_URL . '/admin/books');
    }

    public function edit($id) {
        $id = (int)$id;
        $stmt = $this->db->prepare("SELECT * FROM books WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $book = $stmt->fetch();

        if (!$book) {
            http_response_code(404);
            echo 'Book not found';
            exit;
        }

        $authors = $this->db->query("SELECT id, name FROM authors ORDER BY name ASC")->fetchAll();
        $categories = $this->db->query("SELECT id, name FROM categories ORDER BY name ASC")->fetchAll();
        $districts = $this->db->query("SELECT id, name FROM districts ORDER BY name ASC")->fetchAll();

        $this->render('admin/books/edit', [
            'title' => 'Edit Book - ' . SITE_NAME,
            'book' => $book,
            'authors' => $authors,
            'categories' => $categories,
            'districts' => $districts,
        ], 'admin');
    }

    public function update($id) {
        $id = (int)$id;

        if (!$this->isPost()) {
            $this->redirect(BASE_URL . '/admin/books/' . $id . '/edit');
        }

        $csrf = new CSRF();
        if (!$csrf->validateToken($this->getPost(CSRF_TOKEN_NAME))) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect(BASE_URL . '/admin/books/' . $id . '/edit');
        }

        $stmt = $this->db->prepare("SELECT * FROM books WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $book = $stmt->fetch();

        if (!$book) {
            http_response_code(404);
            echo 'Book not found';
            exit;
        }

        $title = trim((string)$this->getPost('title'));
        $slug = trim((string)$this->getPost('slug'));
        $authorId = (int)$this->getPost('author_id');
        $categoryId = (int)$this->getPost('category_id');
        $districtId = (int)$this->getPost('district_id');
        $description = trim((string)$this->getPost('description'));
        $status = (string)$this->getPost('status');

        if ($title === '' || $slug === '') {
            $this->flash('error', 'Title and slug are required.');
            $this->redirect(BASE_URL . '/admin/books/' . $id . '/edit');
        }

        if (!in_array($status, ['draft', 'published', 'archived'], true)) {
            $status = 'published';
        }

        $filePath = $book['file_path'];
        if (!empty($_FILES['pdf_file']['name'])) {
            if (!is_dir(UPLOAD_PATH)) {
                @mkdir(UPLOAD_PATH, 0777, true);
            }

            $ext = strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));
            if ($ext !== 'pdf') {
                $this->flash('error', 'Only PDF files are allowed.');
                $this->redirect(BASE_URL . '/admin/books/' . $id . '/edit');
            }

            $safeName = preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $_FILES['pdf_file']['name']);
            $filename = time() . '_' . $safeName;
            $destination = rtrim(UPLOAD_PATH, '/\\') . DIRECTORY_SEPARATOR . $filename;

            if (!move_uploaded_file($_FILES['pdf_file']['tmp_name'], $destination)) {
                $this->flash('error', 'Failed to upload PDF.');
                $this->redirect(BASE_URL . '/admin/books/' . $id . '/edit');
            }

            $filePath = 'public/assets/uploads/' . $filename;
        }

        $sql = "
            UPDATE books SET
                title = ?,
                slug = ?,
                description = ?,
                author_id = ?,
                category_id = ?,
                district_id = ?,
                file_path = ?,
                status = ?,
                updated_at = NOW()
            WHERE id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $title,
            $slug,
            $description,
            $authorId ?: null,
            $categoryId ?: null,
            $districtId ?: null,
            $filePath,
            $status,
            $id,
        ]);

        $this->flash('success', 'Book updated successfully.');
        $this->redirect(BASE_URL . '/admin/books');
    }

    public function delete($id) {
        $id = (int)$id;

        $csrf = new CSRF();
        if (!$csrf->validateToken($this->getPost(CSRF_TOKEN_NAME))) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect(BASE_URL . '/admin/books');
        }

        $stmt = $this->db->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$id]);

        $this->flash('success', 'Book deleted successfully.');
        $this->redirect(BASE_URL . '/admin/books');
    }
}
?>
