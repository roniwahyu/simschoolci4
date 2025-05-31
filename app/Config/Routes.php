<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route - redirect to dashboard or login
$routes->get('/', 'Dashboard::index');

// Authentication Routes
$routes->group('auth', function($routes) {
    $routes->get('/', 'Auth::index');
    $routes->get('login', 'Auth::login');
    $routes->post('authenticate', 'Auth::authenticate');
    $routes->get('logout', 'Auth::logout');
    $routes->get('demo', 'Auth::demo');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::processRegister');
    $routes->get('forgot-password', 'Auth::forgotPassword');
    $routes->post('forgot-password', 'Auth::processForgotPassword');
    $routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
    $routes->post('reset-password', 'Auth::processResetPassword');
    $routes->get('google', 'Auth::googleAuth');
    $routes->get('google/callback', 'Auth::googleCallback');
    $routes->get('2fa', 'Auth::twoFactorAuth');
    $routes->post('2fa/generate', 'Auth::generateQRCode');
    $routes->post('2fa/verify', 'Auth::verify2FA');
    $routes->get('check-session', 'Auth::checkSession');
});

// Dashboard routes
$routes->get('dashboard', 'Dashboard::index');
$routes->get('teachers', 'Dashboard::teachers');
$routes->get('classes', 'Dashboard::classes');
$routes->get('subjects', 'Dashboard::subjects');
$routes->get('exams', 'Dashboard::exams');
$routes->get('library', 'Dashboard::library');
$routes->get('fees', 'Dashboard::fees');
$routes->get('transport', 'Dashboard::transport');
$routes->get('hostel', 'Dashboard::hostel');
$routes->get('reports', 'Dashboard::reports');
$routes->get('settings', 'Dashboard::settings');

// Student Management Routes
$routes->group('students', function($routes) {
    $routes->get('/', 'Student::index');
    $routes->get('create', 'Student::create');
    $routes->post('store', 'Student::store');
    $routes->get('(:num)', 'Student::show/$1');
    $routes->get('(:num)/edit', 'Student::edit/$1');
    $routes->put('(:num)/update', 'Student::update/$1');
    $routes->post('(:num)/update', 'Student::update/$1'); // For form compatibility
    $routes->delete('(:num)', 'Student::delete/$1');
    $routes->post('(:num)/toggle-status', 'Student::toggleStatus/$1');
    $routes->get('search', 'Student::search');
    $routes->get('export/excel', 'Student::exportExcel');
    $routes->get('export/pdf', 'Student::exportPdf');
    $routes->post('import', 'Student::import');
    $routes->get('(:num)/id-card', 'Student::generateIdCard/$1');
    $routes->get('(:num)/pdf', 'Student::generatePdf/$1');
    $routes->get('get-sections/(:num)', 'Student::getSections/$1');
});

// Student Management Routes
$routes->group('students', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Students::index');
    $routes->get('add', 'Students::add');
    $routes->post('store', 'Students::store');
    $routes->get('view/(:num)', 'Students::view/$1');
    $routes->get('edit/(:num)', 'Students::edit/$1');
    $routes->post('update/(:num)', 'Students::update/$1');
    $routes->delete('delete/(:num)', 'Students::delete/$1');
    $routes->get('export', 'Students::export');
    $routes->post('import', 'Students::import');
});

// Teacher Management Routes
$routes->group('teachers', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Teachers::index');
    $routes->get('add', 'Teachers::add');
    $routes->post('store', 'Teachers::store');
    $routes->get('view/(:num)', 'Teachers::view/$1');
    $routes->get('edit/(:num)', 'Teachers::edit/$1');
    $routes->post('update/(:num)', 'Teachers::update/$1');
    $routes->delete('delete/(:num)', 'Teachers::delete/$1');
    $routes->get('subjects/(:num)', 'Teachers::subjects/$1');
    $routes->get('export', 'Teachers::export');
});

// Class Management Routes
$routes->group('classes', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Classes::index');
    $routes->get('add', 'Classes::add');
    $routes->post('store', 'Classes::store');
    $routes->get('view/(:num)', 'Classes::view/$1');
    $routes->get('edit/(:num)', 'Classes::edit/$1');
    $routes->post('update/(:num)', 'Classes::update/$1');
    $routes->delete('delete/(:num)', 'Classes::delete/$1');
    $routes->get('students/(:num)', 'Classes::students/$1');
    $routes->get('subjects/(:num)', 'Classes::subjects/$1');
    $routes->get('export', 'Classes::export');
});

// API Routes for AJAX calls
$routes->group('api', ['filter' => 'auth'], function($routes) {
    $routes->get('students/search', 'Api\Students::search');
    $routes->get('teachers/search', 'Api\Teachers::search');
    $routes->get('classes/search', 'Api\Classes::search');
    $routes->get('dashboard/stats', 'Api\Dashboard::stats');
    $routes->post('upload/avatar', 'Api\Upload::avatar');
});

// Admin Routes
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('users', 'Admin::users');
    $routes->get('settings', 'Admin::settings');
    $routes->post('settings/update', 'Admin::updateSettings');
    $routes->get('backup', 'Admin::backup');
    $routes->get('logs', 'Admin::logs');
});
