<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');
$routes->get('register', 'Auth::registerForm');
$routes->post('register', 'Auth::register');

$routes->group('customer', ['filter' => 'auth:customer'], static function ($routes) {
    $routes->get('/', 'Customer::index');
    $routes->post('action', 'Customer::action');
});

$routes->group('employee', ['filter' => 'auth:employee'], static function ($routes) {
    $routes->get('/', 'Employee::index');
    $routes->post('action', 'Employee::action');
    $routes->get('register', 'Employee::registerForm');
    $routes->post('register', 'Employee::register');
});
