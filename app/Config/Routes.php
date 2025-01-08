<?php

namespace Config;

$routes->options('(:any)', function($any) {
    return '';
});

$routes->group('api', ['namespace' => 'App\Controllers'], function ($routes) {
    // Rutas públicas
    $routes->post('login', 'AuthController::login');
    
    // Rutas protegidas (requieren token válido)
    $routes->group('', ['filter' => 'jwt'], function($routes) {
        $routes->post('logout', 'AuthController::logout');
        // Aquí irán otras rutas protegidas
    });
});