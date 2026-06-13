<?php

require_once dirname(__DIR__) . '/config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    $params = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => $params['lifetime'],
        'path' => '/',
        'domain' => $params['domain'],
        'secure' => $params['secure'],
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        header("HTTP/1.1 403 Forbidden");
        die("Lỗi: Token CSRF không hợp lệ hoặc đã hết hạn. Vui lòng quay lại và tải lại trang.");
    }
}

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        dirname(__DIR__) . '/app/controllers/',
        dirname(__DIR__) . '/app/models/',
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Check locked status for logged-in users on every request
if (isset($_SESSION['user']['id']) && !isset($_GET['ignore_lock'])) {
    $userModelForCheck = new User();
    $currentUserCheck = $userModelForCheck->findById($_SESSION['user']['id']);
    if ($currentUserCheck && $currentUserCheck['status'] === 'locked') {
        // Destroy session and redirect
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(), '', 0, '/');
        session_regenerate_id(true);
        
        header("Location: " . BASE_URL . "index.php?controller=auth&action=login&error=locked");
        exit();
    }
}

$controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'HomeController';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

// Basic routing
if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $actionName)) {
        $controller->$actionName();
    } else {
        // Handle 404 - Action not found
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found - Action " . $actionName . " not found in " . $controllerName . ".";
    }
} else {
    // Handle 404 - Controller not found
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found - Controller " . $controllerName . " not found.";
}
