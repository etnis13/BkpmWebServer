<?php

session_start();

require_once __DIR__ . '/../routes/web.php';

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Models/Mahasiswa.php';
require_once __DIR__ . '/../app/Repositories/MahasiswaRepository.php';

require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';

require_once __DIR__ . '/../app/Middleware/AuthMiddleware.php';


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$base = '/BkpmWebServer/acara9/public';

if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}

if ($uri === '') {
    $uri = '/';
}


/*
|--------------------------------------------------------------------------
| Route dengan parameter Mahasiswa
|--------------------------------------------------------------------------
*/

if (
    $method === 'GET' &&
    preg_match('#^/mahasiswa/([0-9]+)/edit$#', $uri, $matches)
) {

    $middleware = new AuthMiddleware();
    $middleware->handle();

    $controller = new MahasiswaController(
        new MahasiswaRepository(Database::getInstance())
    );

    $controller->edit((int) $matches[1]);
    exit;
}


if (
    $method === 'POST' &&
    preg_match('#^/mahasiswa/([0-9]+)$#', $uri, $matches)
) {

    $middleware = new AuthMiddleware();
    $middleware->handle();

    $controller = new MahasiswaController(
        new MahasiswaRepository(Database::getInstance())
    );

    $controller->update((int) $matches[1]);
    exit;
}


if (
    $method === 'POST' &&
    preg_match('#^/mahasiswa/([0-9]+)/delete$#', $uri, $matches)
) {

    $middleware = new AuthMiddleware();
    $middleware->handle();

    $controller = new MahasiswaController(
        new MahasiswaRepository(Database::getInstance())
    );

    $controller->destroy((int) $matches[1]);
    exit;
}


/*
|--------------------------------------------------------------------------
| Cari Route
|--------------------------------------------------------------------------
*/

$route = $routes[$method][$uri] ?? null;


/*
|--------------------------------------------------------------------------
| Route Tidak Ditemukan
|--------------------------------------------------------------------------
*/

if ($route === null) {

    http_response_code(404);

    echo '<h1>404 - Halaman Tidak Ditemukan</h1>';

    exit;
}


/*
|--------------------------------------------------------------------------
| Middleware
|--------------------------------------------------------------------------
*/

if (isset($route['middleware'])) {

    foreach ($route['middleware'] as $middlewareName) {

        if ($middlewareName === 'AuthMiddleware') {

            $middleware = new AuthMiddleware();

            $middleware->handle();
        }
    }
}


/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
*/

$controllerName = $route['controller'];
$action = $route['action'];


if ($controllerName === 'MahasiswaController') {

    $controller = new MahasiswaController(
        new MahasiswaRepository(Database::getInstance())
    );

} elseif ($controllerName === 'AuthController') {

    $controller = new AuthController();

} elseif ($controllerName === 'HomeController') {

    $controller = new HomeController();

} else {

    http_response_code(404);

    echo '<h1>Controller Tidak Ditemukan</h1>';

    exit;
}


/*
|--------------------------------------------------------------------------
| Jalankan Action
|--------------------------------------------------------------------------
*/

if (!method_exists($controller, $action)) {

    http_response_code(500);

    echo "Method {$action} tidak ditemukan pada {$controllerName}.";

    exit;
}

$controller->$action();