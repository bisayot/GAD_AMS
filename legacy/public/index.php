<?php
// public/index.php

// 1. Define Root Directory
define('ROOT_DIR', dirname(__DIR__));

// 2. Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = ROOT_DIR . '/src/';

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

// 3. Load Config
require_once ROOT_DIR . '/config/config.php';

// 4. Initialize Router
use App\Core\Router;

$router = new Router();

// 5. Define Routes
$router->add('GET', '/login', ['AuthController', 'showLogin']);
$router->add('POST', '/login', ['AuthController', 'login']);
$router->add('GET', '/logout', ['AuthController', 'logout']);

// Default route (Home)
$router->add('GET', '/', ['HomeController', 'index']);

// 6. Dispatch
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
