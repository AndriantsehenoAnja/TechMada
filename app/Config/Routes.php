<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('auth', function($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::authenticate');
    $routes->get('logout', 'AuthController::logout');
});

$routes->get('emp/index', 'EmpController::index');
$routes->get('demandeconge', 'EmpController::demadeconge');

$routes->group('emp', ['filter' => 'role:employe'], function($routes) {
    // $routes->get('profil', 'EmpController::profil');
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


