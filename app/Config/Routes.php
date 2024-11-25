<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/login', 'Login::create');
$routes->post('/masuk', 'Login::index');
$routes->get('/getUserData', 'Login::getUserData');
$routes->get('/getKelas', 'Siswa::kelas');
