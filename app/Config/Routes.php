<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Dataview::index');

$routes->get('/view/(:any)', 'Dataview::view/$1');
$routes->get('/sample', 'Dataview::sample');

$routes->get('/imageproxy', 'ImageProxy::index');
