<?php
require_once __DIR__ . '/../config.php';

spl_autoload_register(function ($class) {
    $baseDir = dirname(__DIR__);

    $paths = [
        $baseDir . '/app/core/' . $class . '.php',
        $baseDir . '/app/helpers/' . $class . '.php',
        $baseDir . '/app/models/' . $class . '.php',
        $baseDir . '/app/controllers/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

$router = new Router();

$routesFile = dirname(__DIR__) . '/routes/web.php';
if (file_exists($routesFile)) {
    require_once $routesFile;
}

// Set security headers
Security::setSecurityHeaders();

$router->dispatch();
