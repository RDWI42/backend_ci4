<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('api', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('artikels', 'Artikels::index');

    $routes->get('artikels/(:any)', 'Artikels::show/$1');
    
    $routes->post('artikels', 'Artikels::create');
    
    $routes->post('artikels/(:any)', 'Artikels::update/$1');
    
    $routes->get('deleteArtikels/(:num)', 'Artikels::delete/$1');
    
    $routes->post('upload-image', 'Artikels::uploadImage');

});

