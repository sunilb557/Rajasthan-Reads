<?php
class Router {
    private $routes = [];

    public function add($method, $route, $action) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'route' => $route,
            'action' => $action
        ];
    }

    public function get($route, $action) {
        $this->add('GET', $route, $action);
    }

    public function post($route, $action) {
        $this->add('POST', $route, $action);
    }

    public function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Handle subdirectory installs - only strip BASE_PATH if URI starts with it
        if (strpos($uri, BASE_PATH) === 0) {
            $uri = substr($uri, strlen(BASE_PATH));
        }
        $uri = $uri ?: '/';
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchRoute($route['route'], $uri, $params)) {
                return $this->callAction($route['action'], $params);
            }
        }

        // If no route matches, show 404
        $this->notFound();
    }

    private function matchRoute($route, $uri, &$params) {
        $route = preg_replace('/\{([^}]+)\}/', '(?P<$1>[^/]+)', $route);
        $route = '#^' . $route . '$#';

        if (preg_match($route, $uri, $matches)) {
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }
        return false;
    }

    private function callAction($action, $params) {
        if (is_callable($action)) {
            call_user_func_array($action, $params);
        } elseif (is_string($action)) {
            list($controller, $method) = explode('@', $action);
            // Controller class name without namespace
            if (class_exists($controller)) {
                $instance = new $controller();
                if (method_exists($instance, $method)) {
                    call_user_func_array([$instance, $method], $params);
                } else {
                    throw new Exception("Method $method not found in $controller");
                }
            } else {
                throw new Exception("Controller $controller not found");
            }
        }
    }

    private function notFound() {
        http_response_code(404);
        echo "404 - Page not found";
        exit;
    }
}
?>
