<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');

$routes->get('page/tos', 'Page::tos');

$routes->get('/artikel', 'Artikel::index');
$routes->get('/artikel/(:any)', 'Artikel::view/$1');

$routes->group('admin', function ($routes) {
    $routes->get('artikel', 'Artikel::admin_index');
    $routes->add('artikel/add', 'Artikel::add');
    $routes->add('artikel/edit/(:any)', 'Artikel::edit/$1');
    $routes->get('artikel/delete/(:any)', 'Artikel::delete/$1');
});

$routes->add('user/login', 'User::login');
$routes->get('user/logout', 'User::logout');

// Ajax routes
$routes->get('ajax', 'AjaxController::index');
$routes->get('ajax/getData', 'AjaxController::getData');
$routes->delete('ajax/delete/(:num)', 'AjaxController::delete/$1');
$routes->post('ajax/add', 'AjaxController::add');
$routes->post('ajax/update/(:num)', 'AjaxController::update/$1');
$routes->get('ajax/getDetail/(:num)', 'AjaxController::getDetail/$1');

// REST API routes - GET tidak butuh token (read only)
$routes->get('post', 'Api\Post::index');
$routes->get('post/(:segment)', 'Api\Post::show/$1');

// REST API routes - POST, PUT, DELETE dilindungi filter apiauth
$routes->post('post', 'Api\Post::create', ['filter' => 'apiauth']);
$routes->put('post/(:segment)', 'Api\Post::update/$1', ['filter' => 'apiauth']);
$routes->delete('post/(:segment)', 'Api\Post::delete/$1', ['filter' => 'apiauth']);

// API Login (tidak perlu token)
$routes->post('api/login', 'Api\Auth::login');