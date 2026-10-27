<?php
class AdminController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
    }

    protected function requireAdmin() {
        $user = $this->getSession('user');
        if (empty($user) || empty($user['role']) || $user['role'] !== 'admin') {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }
}
?>
