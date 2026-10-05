<?php

session_start();

require_once __DIR__ . '/../routes/web.php';

require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

require_once __DIR__ . '/../app/Core/Middleware/AuthMiddleware.php';


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/BkpmWebServer/acara7/public';

if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'];

$route = $routes[$method][$uri] ?? null;


// Route biasa
if ($route) {

    // Jalankan middleware jika ada
    if (!empty($route['middleware'])) {

        foreach ($route['middleware'] as $middleware) {

            $middlewareInstance = new $middleware();

            $middlewareInstance->handle();
        }
    }

    $controllerName = $route['controller'];
    $action = $route['action'];

    $controller = new $controllerName();

    $controller->$action();


// Route dinamis /mahasiswa/{id}
} elseif (
    $method === 'GET' &&
    preg_match('#^/mahasiswa/([0-9]+)$#', $uri, $matches)
) {

    $middleware = new AuthMiddleware();
    $middleware->handle();

    $controller = new MahasiswaController();

    $controller->show($matches[1]);


// Jika route tidak ditemukan
} else {

    http_response_code(404);

    echo '404 - Halaman tidak ditemukan';
}