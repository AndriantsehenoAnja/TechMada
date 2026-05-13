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

$routes->get('demandes', 'DemandeController::index');
$routes->post('demandes/create', 'DemandeController::create');
$routes->get('employe/dashboard', 'AdminController::dashboard');
// // $routes->group('admin', ['filter' => 'auth'], function($routes) {
// // });

// $routes->get('dashboard', 'RhController::dashboard');
// $routes->group('rh', ['filter' => 'auth'], function($routes) {
// });

// $routes->get('dashboard', 'EmployeController::dashboard');
// $routes->group('employe', ['filter' => 'auth'], function($routes) {
// });


