<?php

$routes = [
    'GET' => [
        '/' => [
            'controller' => 'HomeController',
            'action' => 'index',
        ],

        '/login' => [
            'controller' => 'AuthController',
            'action' => 'loginForm',
        ],

        '/dashboard' => [
            'controller' => 'HomeController',
            'action' => 'dashboard',
            'middleware' => ['AuthMiddleware'],
        ],

        '/mahasiswa' => [
            'controller' => 'MahasiswaController',
            'action' => 'index',
            'middleware' => ['AuthMiddleware'],
        ],

        '/mahasiswa/create' => [
            'controller' => 'MahasiswaController',
            'action' => 'create',
            'middleware' => ['AuthMiddleware'],
        ],

        '/logout' => [
            'controller' => 'AuthController',
            'action' => 'logout',
        ],
    ],

    'POST' => [
        '/login' => [
            'controller' => 'AuthController',
            'action' => 'login',
        ],

        '/mahasiswa' => [
            'controller' => 'MahasiswaController',
            'action' => 'store',
            'middleware' => ['AuthMiddleware'],
        ],
    ],
];
