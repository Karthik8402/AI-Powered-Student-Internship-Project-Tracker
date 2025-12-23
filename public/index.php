<?php
session_start();

// Define root path
define('ROOT_PATH', dirname(__DIR__));

// Autoloader (Simple psr-4 style for manual implementation without composer if needed, but we will use one)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = ROOT_PATH . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Simple Router (Can be expanded)
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];

// Remove script path from URI to get the internal route
$path = str_replace(dirname($script_name), '', $request_uri);
$path = parse_url($path, PHP_URL_PATH);

// Route Handling
$auth = new \App\Controllers\AuthController();

switch ($path) {
    case '/':
    case '/index.php':
    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->handleLogin();
        } else {
            $auth->login();
        }
        break;

    case '/register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->handleRegister();
        } else {
            $auth->register();
        }
        break;

    case '/logout':
        $auth->logout();
        break;

    case '/dashboard':
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
        $dashboard = new \App\Controllers\DashboardController();
        $dashboard->index();
        break;

    default:
        http_response_code(404);
        echo "<h1 style='color:white;text-align:center;'>404 Not Found</h1>";
        break;
}
