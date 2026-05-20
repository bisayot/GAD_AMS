<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', function($routes) {
    $routes->post('login', 'AuthController::login');
    $routes->post('register', 'AuthController::register');
    $routes->get('logout', 'AuthController::logout');
    
    // CORS preflight routes
    $routes->options('login', 'AuthController::handleOptions');
    $routes->options('register', 'AuthController::handleOptions');
    $routes->options('logout', 'AuthController::handleOptions');
});
