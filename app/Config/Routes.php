<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('auth', function($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::authenticate');
    $routes->get('logout', 'AuthController::logout');
});


