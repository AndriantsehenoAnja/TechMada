<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Route racine : rediriger vers login
$routes->get('/', 'Auth::login');

// Routes d'authentification
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::authenticate');
$routes->get('/logout', 'Auth::logout');

// Routes protégées - Back-office Admin
$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('/', 'Admin::dashboard');
    $routes->get('employes', 'Admin::employes');
    $routes->post('employes/store', 'Admin::storeEmploye');
    $routes->post('employes/update/(:num)', 'Admin::updateEmploye/$1');
    $routes->post('employes/toggle/(:num)', 'Admin::toggleEmploye/$1');

    $routes->get('departements', 'Admin::departements');
    $routes->post('departements/store', 'Admin::storeDepartement');
    $routes->post('departements/update/(:num)', 'Admin::updateDepartement/$1');
    $routes->post('departements/delete/(:num)', 'Admin::deleteDepartement/$1');

    $routes->get('types-conges', 'Admin::typesConges');
    $routes->post('types-conges/store', 'Admin::storeTypeConge');
    $routes->post('types-conges/update/(:num)', 'Admin::updateTypeConge/$1');
    $routes->post('types-conges/delete/(:num)', 'Admin::deleteTypeConge/$1');

    $routes->get('absences', 'Admin::absences');
    $routes->get('soldes', 'Admin::soldes');
    $routes->post('soldes/initialize', 'Admin::initializeSoldes');

    $routes->get('historique', 'Admin::historique');
});

// Routes protégées - Dashboard RH
$routes->get('/rh', 'RH::dashboard');
$routes->get('/rh/filter-departement/(:num)', 'RH::filterByDepartement/$1');
$routes->get('/rh/filter-statut/(:any)', 'RH::filterByStatut/$1');
$routes->post('/rh/approve/(:num)', 'RH::approveDemande/$1');
$routes->post('/rh/refuse/(:num)', 'RH::refuseDemande/$1');
$routes->get('/rh/soldes', 'RH::soldes');
$routes->get('/rh/employe/(:num)', 'RH::demandesEmploye/$1');

// Routes protégées - Dashboard Employé
$routes->get('/employe', 'EmployeController::dashboard');
$routes->get('/employe/demandes', 'EmployeController::demandes');
$routes->get('/employe/nouvelle-demande', 'EmployeController::nouvelleDemande');
$routes->post('/employe/demandes/store', 'EmployeController::storeDemande');
$routes->post('/employe/demandes/cancel/(:num)', 'EmployeController::cancelDemande/$1');
$routes->get('/employe/solde', 'EmployeController::solde');
$routes->get('/employe/profil', 'EmployeController::profil');
$routes->post('/employe/profil/update', 'EmployeController::updateProfil');
