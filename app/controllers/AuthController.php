<?php
class AuthController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->loadModel('User');
    }

    public function showLogin() {
        $this->render('auth/login', [
            'title' => 'Login - ' . SITE_NAME,
        ]);
    }

    public function login() {
        if (!$this->isPost()) {
            $this->redirect(BASE_URL . '/login');
        }

        $csrf = new CSRF();
        if (!$csrf->validateToken($this->getPost('csrf_token'))) {
            $this->flash('error', 'Invalid CSRF token. Please try again.');
            $this->redirect(BASE_URL . '/login');
        }

        $email = trim((string)$this->getPost('email'));
        $password = (string)$this->getPost('password');

        // Rate limiting check
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        if (!Security::checkRateLimit($ip, 'login')) {
            Security::logEvent('rate_limit', 'Login rate limit exceeded', ['email' => $email, 'ip' => $ip]);
            $this->flash('error', 'Too many login attempts. Please try again in 5 minutes.');
            $this->redirect(BASE_URL . '/login');
        }

        if ($email === '' || $password === '') {
            $this->flash('error', 'Email and password are required.');
            $this->redirect(BASE_URL . '/login');
        }

        $user = $this->model->findByEmail($email);
        
        // Clear rate limit on successful login
        if ($user && $this->model->verifyPassword($password, $user['password'])) {
            Security::clearRateLimit($ip, 'login');
        }

        if (!$user || !$this->model->verifyPassword($password, $user['password'])) {
            $this->flash('error', 'Invalid credentials.');
            $this->redirect(BASE_URL . '/login');
        }

        $this->setSession('user', [
            'id' => $user['id'],
            'name' => $user['full_name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ]);

        $this->flash('success', 'Welcome back, ' . $user['full_name'] . '!');
        $this->redirect(BASE_URL . '/');
    }

    public function showRegister() {
        $this->render('auth/register', [
            'title' => 'Register - ' . SITE_NAME,
        ]);
    }

    public function register() {
        if (!$this->isPost()) {
            $this->redirect(BASE_URL . '/register');
        }

        $csrf = new CSRF();
        if (!$csrf->validateToken($this->getPost('csrf_token'))) {
            $this->flash('error', 'Invalid CSRF token. Please try again.');
            $this->redirect(BASE_URL . '/register');
        }

        $name = trim((string)$this->getPost('name'));
        $email = trim((string)$this->getPost('email'));
        $password = (string)$this->getPost('password');
        $passwordConfirm = (string)$this->getPost('password_confirm');

        if ($name === '' || $email === '' || $password === '') {
            $this->flash('error', 'All fields are required.');
            $this->redirect(BASE_URL . '/register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flash('error', 'Please provide a valid email address.');
            $this->redirect(BASE_URL . '/register');
        }

        if ($password !== $passwordConfirm) {
            $this->flash('error', 'Passwords do not match.');
            $this->redirect(BASE_URL . '/register');
        }

        if ($this->model->findByEmail($email)) {
            $this->flash('error', 'Email already in use.');
            $this->redirect(BASE_URL . '/register');
        }

        $userId = $this->model->createUser([
            'username' => strstr($email, '@', true), // Generate a username from email if not provided
            'full_name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $this->flash('success', 'Account created. Please log in.');
        $this->redirect(BASE_URL . '/login');
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['user']);
        $this->flash('success', 'Logged out successfully.');
        $this->redirect(BASE_URL . '/');
    }
}
?>
