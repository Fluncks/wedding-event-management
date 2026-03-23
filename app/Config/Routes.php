<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/login', 'LoginController::index');
$routes->post('/login', 'LoginController::login');
$routes->get('/logout', 'LoginController::logout', ['filter' => 'auth']);

$routes->get('/auth/login', 'LoginController::index');
$routes->get('/auth/register', function () {
    return redirect()->to('/login')->with('error', 'Accounts are created by an administrator only.');
});

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'Home::dashboard');
    $routes->get('event-list', 'SearchController::events');
    $routes->get('location-list', 'SearchController::locations');

    $routes->get('create-event', 'Home::createEvent');
    $routes->get('create-event/(:num)', 'Home::createEvent/$1');

    $routes->get('create-location', 'Home::createLocation');
    $routes->get('create-location/(:num)', 'Home::createLocation/$1');

    $routes->post('crud/event/save', 'CrudController::saveEvent');
    $routes->post('crud/event/delete/(:num)', 'CrudController::deleteEvent/$1');
    $routes->post('crud/location/save', 'CrudController::saveLocation');
    $routes->post('crud/location/delete/(:num)', 'CrudController::deleteLocation/$1');
});

$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('create-account', 'Home::createAccount');
    $routes->post('create-user', 'CrudController::createUser');
    $routes->post('delete-user/(:num)', 'CrudController::deleteUser/$1');
});
