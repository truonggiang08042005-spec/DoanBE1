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
