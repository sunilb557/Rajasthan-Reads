<?php
class Controller {
    protected $view;
    protected $model;
    protected $data = [];

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->view = new View();

        // Fetch site settings
        try {
            $db = Database::getInstance();
            $settingsRows = $db->fetchAll("SELECT setting_key, setting_value FROM settings");
            $siteSettings = [];
            foreach ($settingsRows as $row) {
                $siteSettings[$row['setting_key']] = $row['setting_value'];
            }
            $this->set('site_settings', $siteSettings);
        } catch (Exception $e) {
            // Silently fail if DB not ready or table doesn't exist
            $this->set('site_settings', []);
        }
    }

    protected function loadModel($modelName) {
        $modelPath = '../app/models/' . $modelName . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
            $this->model = new $modelName();
            return $this->model;
        }
        return null;
    }

    protected function set($key, $value) {
        $this->data[$key] = $value;
    }

    protected function render($view, $data = [], $layout = null) {
        $this->data = array_merge($this->data, $data);
        if ($layout) {
            $this->view->setLayout($layout);
        }
        $this->view->render($view, $this->data);
    }

    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    protected function getPost($key = null) {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }

    protected function getGet($key = null) {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }

    protected function getSession($key = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($key === null) {
            return $_SESSION;
        }
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    protected function setSession($key, $value) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION[$key] = $value;
    }

    protected function unsetSession($key) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION[$key]);
    }

    protected function flash($key, $value = null) {
        if ($value !== null) {
            $_SESSION['flash'][$key] = $value;
        } else {
            $flash = isset($_SESSION['flash'][$key]) ? $_SESSION['flash'][$key] : null;
            unset($_SESSION['flash'][$key]);
            return $flash;
        }
    }
}
?>
