<?php
class ReaderApiController extends ApiController {
    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance()->getConnection();
    }

    protected function getApiUser() {
        return $this->getSession('user');
    }

    public function progress($slug) {
        $user = $this->getApiUser();
        if (empty($user) || empty($user['id'])) {
            $this->json(['ok' => true, 'guest' => true]);
            return;
        }

        if (!$this->isPost()) {
            http_response_code(405);
            $this->json(['error' => 'Method not allowed']);
        }

        $csrf = new CSRF();
        if (!$csrf->validateToken($this->getPost(CSRF_TOKEN_NAME))) {
            http_response_code(400);
            $this->json(['error' => 'Invalid CSRF token']);
        }

        $page = (int)$this->getPost('page');
        if ($page < 1) {
            $page = 1;
        }

        $bookStmt = $this->db->prepare("SELECT id FROM books WHERE slug = ? AND status = 'published' LIMIT 1");
        $bookStmt->execute([$slug]);
        $book = $bookStmt->fetch();
        if (!$book) {
            http_response_code(404);
            $this->json(['error' => 'Book not found']);
        }

        $sql = "
            INSERT INTO reading_history (user_id, book_id, page_number)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE page_number = VALUES(page_number), last_read_at = CURRENT_TIMESTAMP
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([(int)$user['id'], (int)$book['id'], $page]);

        $this->json(['ok' => true]);
    }

    public function bookmark($slug) {
        $user = $this->getApiUser();
        if (empty($user) || empty($user['id'])) {
            $this->json(['error' => 'Must be logged in to bookmark']);
            return;
        }

        if (!$this->isPost()) {
            http_response_code(405);
            $this->json(['error' => 'Method not allowed']);
        }

        $csrf = new CSRF();
        if (!$csrf->validateToken($this->getPost(CSRF_TOKEN_NAME))) {
            http_response_code(400);
            $this->json(['error' => 'Invalid CSRF token']);
        }

        $page = (int)$this->getPost('page');
        if ($page < 1) {
            $page = 1;
        }

        $bookStmt = $this->db->prepare("SELECT id FROM books WHERE slug = ? AND status = 'published' LIMIT 1");
        $bookStmt->execute([$slug]);
        $book = $bookStmt->fetch();
        if (!$book) {
            http_response_code(404);
            $this->json(['error' => 'Book not found']);
        }

        $sql = "
            INSERT INTO bookmarks (user_id, book_id, page_number)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE page_number = VALUES(page_number)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([(int)$user['id'], (int)$book['id'], $page]);

        $this->json(['ok' => true]);
    }
}
?>
