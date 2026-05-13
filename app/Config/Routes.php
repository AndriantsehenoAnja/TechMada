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
$routes->get('formconge', 'EmpController::formconge');
$routes->get('demandeconge', 'EmpController::demadeconge');

$routes->group('emp', ['filter' => 'role:employe'], function($routes) {
    // $routes->get('profil', 'EmpController::profil');
});

