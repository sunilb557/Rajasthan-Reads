<?php
require_once 'config.php';

echo "=== DEBUG INFO ===\n\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "BASE_PATH: " . BASE_PATH . "\n";
echo "Processed URI: " . str_replace(BASE_PATH, '', $_SERVER['REQUEST_URI']) . "\n\n";

// Check if controllers exist
$controllers = ['HomeController', 'BookController', 'SearchController', 'AuthController'];
foreach ($controllers as $ctrl) {
    $file = __DIR__ . '/app/controllers/' . $ctrl . '.php';
    echo "$ctrl: " . (file_exists($file) ? 'EXISTS' : 'MISSING') . "\n";
}

echo "\n=== Testing Autoloader ===\n";
spl_autoload_register(function ($class) {
    $baseDir = __DIR__;
    $paths = [
        $baseDir . '/app/core/' . $class . '.php',
        $baseDir . '/app/helpers/' . $class . '.php',
        $baseDir . '/app/models/' . $class . '.php',
        $baseDir . '/app/controllers/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            echo "Loaded: $class from $path\n";
            return;
        }
    }
    echo "FAILED to load: $class\n";
});

// Try to load Router
echo "\n";
$router = new Router();
echo "Router created successfully\n";

// Check database
$db = Database::getInstance()->getConnection();
echo "Database connected: " . ($db ? 'YES' : 'NO') . "\n";

$stmt = $db->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo "Tables: " . count($tables) . "\n";
