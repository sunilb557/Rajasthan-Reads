<?php
class AdminAuthorController extends AdminController {
    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        $authorModel = new Author();
        $authors = $authorModel->getAllWithCounts();

        $this->render('admin/authors/index', [
            'title' => 'Manage Authors - ' . SITE_NAME,
            'authors' => $authors,
        ], 'admin');
    }

    public function create() {
        $districts = $this->db->query("SELECT id, name FROM districts ORDER BY name ASC")->fetchAll();
        $this->render('admin/authors/create', [
            'title' => 'Add Author - ' . SITE_NAME,
            'districts' => $districts,
        ], 'admin');
    }

    public function store() {
        if (!$this->isPost()) {
            $this->redirect(BASE_URL . '/admin/authors/create');
        }

        $csrf = new CSRF();
        if (!$csrf->validateToken($this->getPost(CSRF_TOKEN_NAME))) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect(BASE_URL . '/admin/authors/create');
        }

        $name = trim((string)$this->getPost('name'));
        $name_hindi = trim((string)$this->getPost('name_hindi'));
        $slug = trim((string)$this->getPost('slug')) ?: Slug::generate($name, null, 'authors');
        $bio = trim((string)$this->getPost('bio'));
        $district_id = (int)$this->getPost('district_id');

        if ($name === '') {
            $this->flash('error', 'Name is required.');
            $this->redirect(BASE_URL . '/admin/authors/create');
        }

        $sql = "INSERT INTO authors (name, name_hindi, slug, bio, district_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $name_hindi, $slug, $bio, $district_id ?: null]);

        $this->flash('success', 'Author added successfully.');
        $this->redirect(BASE_URL . '/admin/authors');
    }

    public function edit($id) {
        $stmt = $this->db->prepare("SELECT * FROM authors WHERE id = ?");
        $stmt->execute([(int)$id]);
        $author = $stmt->fetch();

        if (!$author) {
            $this->flash('error', 'Author not found.');
            $this->redirect(BASE_URL . '/admin/authors');
        }

        $districts = $this->db->query("SELECT id, name FROM districts ORDER BY name ASC")->fetchAll();
        $this->render('admin/authors/edit', [
            'title' => 'Edit Author - ' . SITE_NAME,
            'author' => $author,
            'districts' => $districts,
        ], 'admin');
    }

    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect(BASE_URL . '/admin/authors');
        }

        $name = trim((string)$this->getPost('name'));
        $name_hindi = trim((string)$this->getPost('name_hindi'));
        $slug = trim((string)$this->getPost('slug'));
        $bio = trim((string)$this->getPost('bio'));
        $district_id = (int)$this->getPost('district_id');

        $sql = "UPDATE authors SET name = ?, name_hindi = ?, slug = ?, bio = ?, district_id = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $name_hindi, $slug, $bio, $district_id ?: null, (int)$id]);

        $this->flash('success', 'Author updated successfully.');
        $this->redirect(BASE_URL . '/admin/authors');
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM authors WHERE id = ?");
        $stmt->execute([(int)$id]);
        $this->flash('success', 'Author deleted successfully.');
        $this->redirect(BASE_URL . '/admin/authors');
    }
}
