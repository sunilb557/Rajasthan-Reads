<?php
class ApiController extends Controller {
    protected function requireLogin() {
        $user = $this->getSession('user');
        if (empty($user) || empty($user['id'])) {
            http_response_code(401);
            $this->json(['error' => 'Unauthorized']);
        }
        return $user;
    }
}
?>
