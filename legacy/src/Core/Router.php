<?php
// src/Core/Router.php

namespace App\Core;

class Router {
    protected $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($method, $uri) {
        // Simple routing logic: find matching path and method
        // For production, use regex or a library like FastRoute
        
        // Remove base path from URI if necessary
        $basePath = '/GAD_v12';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        // Remove query string
        $uri = explode('?', $uri)[0];
        if ($uri === '') $uri = '/';

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $uri) {
                list($controllerName, $action) = $route['handler'];
                $controllerClass = "App\\Controllers\\" . $controllerName;
                
                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    if (method_exists($controller, $action)) {
                        return $controller->$action();
                    }
                }
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 Not Found";
    }
}
