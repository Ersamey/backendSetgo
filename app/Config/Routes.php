<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/login', 'Login::create');
$routes->get('/login', 'Login::cobalogni');
$routes->post('/masuk', 'Login::index');
$routes->get('/getUserData', 'Login::getUserData');

// Siswa
$routes->get('/getKelas', 'Siswa::kelas');
$routes->get('/join', 'Siswa::join');
$routes->post('/addClass', 'Siswa::addClass');

// Guru
$routes->get('/kelasGuru', 'Guru::kelas');
$routes->get('/siswaKu', 'Guru::siswaKu');
$routes->get('/buatKelas', 'Guru::buatKelas');
