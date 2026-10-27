<?php
class View {
    private $layout = 'default';
    private $data = [];

    public function setLayout($layout) {
        $this->layout = $layout;
    }

    public function render($view, $data = []) {
        $this->data = array_merge($this->data, $data);
        extract($this->data);

        $viewFile = '../app/views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception("View file $viewFile not found");
        }

        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        if ($this->layout) {
            $layoutFile = '../app/views/layouts/' . $this->layout . '.php';
            if (!file_exists($layoutFile)) {
                throw new Exception("Layout file $layoutFile not found");
            }
            include $layoutFile;
        } else {
            echo $content;
        }
    }

    public function partial($partial, $data = []) {
        $partialData = array_merge($this->data, $data);
        extract($partialData);

        $partialFile = '../app/views/partials/' . $partial . '.php';
        if (!file_exists($partialFile)) {
            throw new Exception("Partial file $partialFile not found");
        }

        include $partialFile;
    }

    public function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    public function url($path) {
        return BASE_URL . '/' . ltrim($path, '/');
    }

    public function asset($path) {
        return BASE_URL . '/assets/' . ltrim($path, '/');
    }

    public function vite_asset($entry) {
        $manifestPath = ROOT_PATH . 'public/build/.vite/manifest.json';
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            if (isset($manifest[$entry])) {
                return BASE_URL . '/public/build/' . $manifest[$entry]['file'];
            }
        }
        // Fallback to build assets directly if manifest fails
        return BASE_URL . '/public/build/assets/' . $entry;
    }

    public function csrf() {
        $csrf = new CSRF();
        return $csrf->generateToken();
    }

    public function highlight($text, $query) {
        return SearchHelper::highlight($text, $query);
    }
}
?>
