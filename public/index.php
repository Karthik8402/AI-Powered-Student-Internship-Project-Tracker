<?php
// Session configuration for shared hosting
$sessionPath = __DIR__ . '/sessions';
if (!file_exists($sessionPath)) {
    mkdir($sessionPath, 0777, true);
}
ini_set('session.save_path', $sessionPath);
ini_set('session.cookie_path', '/KK/');
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();

// Define root path
// Check if 'src' exists in the current directory (Shared Hosting / Flat Structure)
if (is_dir(__DIR__ . '/src')) {
    define('ROOT_PATH', __DIR__);
} else {
    // Default structure (public/ inside root)
    define('ROOT_PATH', dirname(__DIR__));
}

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

// Define BASE_URL for consistent absolute paths
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_URL', '/KK');

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
        // Redirect to dashboard if already logged in
        if (isset($_SESSION['user_id'])) {
            header("Location: ./dashboard");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->handleLogin();
        } else {
            $auth->login();
        }
        break;

    case '/register':
        // Redirect to dashboard if already logged in
        if (isset($_SESSION['user_id'])) {
            header("Location: ./dashboard");
            exit;
        }
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
            header("Location: ./login");
            exit;
        }
        $dashboard = new \App\Controllers\DashboardController();
        $dashboard->index();
        break;

    case '/change-password':
        if (!isset($_SESSION['user_id'])) {
            header("Location: ./login");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->handleChangePassword();
        } else {
            $auth->changePassword();
        }
        break;

    // ===== PROJECT ROUTES =====
    case '/projects':
        if (!isset($_SESSION['user_id'])) {
            header("Location: ./login");
            exit;
        }
        $projectController = new \App\Controllers\ProjectController();
        $projectController->index();
        break;

    case '/projects/create':
        if (!isset($_SESSION['user_id'])) {
            header("Location: ./login");
            exit;
        }
        $projectController = new \App\Controllers\ProjectController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projectController->handleCreate();
        } else {
            $projectController->create();
        }
        break;

    case '/projects/update-status':
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            exit;
        }
        $projectController = new \App\Controllers\ProjectController();
        $projectController->updateStatus();
        break;

    // ===== TASK ROUTES =====
    case '/tasks/create':
        if (!isset($_SESSION['user_id'])) {
            header("Location: ./login");
            exit;
        }
        $taskController = new \App\Controllers\TaskController();
        $taskController->handleCreate();
        break;

    case '/tasks/update-status':
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            exit;
        }
        $taskController = new \App\Controllers\TaskController();
        $taskController->updateStatus();
        break;

    default:
        // Handle dynamic routes with IDs
        if (!isset($_SESSION['user_id'])) {
            header("Location: ./login");
            exit;
        }

        // Project view: /projects/view/{id}
        if (preg_match('#^/projects/view/(\d+)$#', $path, $matches)) {
            $projectController = new \App\Controllers\ProjectController();
            $projectController->view($matches[1]);
            break;
        }

        // Project edit: /projects/edit/{id}
        if (preg_match('#^/projects/edit/(\d+)$#', $path, $matches)) {
            $projectController = new \App\Controllers\ProjectController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $projectController->handleEdit($matches[1]);
            } else {
                $projectController->edit($matches[1]);
            }
            break;
        }

        // Project delete: /projects/delete/{id}
        if (preg_match('#^/projects/delete/(\d+)$#', $path, $matches)) {
            $projectController = new \App\Controllers\ProjectController();
            $projectController->delete($matches[1]);
            break;
        }

        // Task create for project: /tasks/create/{projectId}
        if (preg_match('#^/tasks/create/(\d+)$#', $path, $matches)) {
            $taskController = new \App\Controllers\TaskController();
            $taskController->create($matches[1]);
            break;
        }

        // Task delete: /tasks/delete/{id}
        if (preg_match('#^/tasks/delete/(\d+)$#', $path, $matches)) {
            $taskController = new \App\Controllers\TaskController();
            $taskController->delete($matches[1]);
            break;
        }

        // ===== TEAM ROUTES =====
        
        // Teams list: /teams
        if ($path === '/teams') {
            $teamController = new \App\Controllers\TeamController();
            $teamController->index();
            break;
        }

        // Team create: /teams/create
        if ($path === '/teams/create') {
            $teamController = new \App\Controllers\TeamController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $teamController->handleCreate();
            } else {
                $teamController->create();
            }
            break;
        }

        // Team view: /teams/view/{id}
        if (preg_match('#^/teams/view/(\d+)$#', $path, $matches)) {
            $teamController = new \App\Controllers\TeamController();
            $teamController->view($matches[1]);
            break;
        }

        // Team edit: /teams/edit/{id}
        if (preg_match('#^/teams/edit/(\d+)$#', $path, $matches)) {
            $teamController = new \App\Controllers\TeamController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $teamController->handleEdit($matches[1]);
            } else {
                $teamController->edit($matches[1]);
            }
            break;
        }

        // Team delete: /teams/delete/{id}
        if (preg_match('#^/teams/delete/(\d+)$#', $path, $matches)) {
            $teamController = new \App\Controllers\TeamController();
            $teamController->delete($matches[1]);
            break;
        }

        http_response_code(404);
        echo "<h1 style='color:white;text-align:center;'>404 Not Found</h1>";
        break;
}

