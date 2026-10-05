<?php

require_once __DIR__ . '/../routes/web.php';

require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';

$uri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$method = $_SERVER['REQUEST_METHOD'];


// Base URL Acara 8
$base = '/BkpmWebServer/acara8/public';


// Hilangkan base URL
if (str_starts_with($uri, $base)) {

    $uri = substr($uri, strlen($base));

}


// Jika kosong
if ($uri === '') {
    $uri = '/';
}


$controller = new MahasiswaController();


// ==============================
// GET: EDIT
// /mahasiswa/5/edit
// ==============================

if (
    $method === 'GET' &&
    preg_match(
        '#^/mahasiswa/([0-9]+)/edit$#',
        $uri,
        $matches
    )
) {

    $controller->edit($matches[1]);

    exit;
}


// ==============================
// POST: UPDATE
// /mahasiswa/5
// ==============================

if (
    $method === 'POST' &&
    preg_match(
        '#^/mahasiswa/([0-9]+)$#',
        $uri,
        $matches
    )
) {

    $controller->update($matches[1]);

    exit;
}


// ==============================
// POST: DELETE
// /mahasiswa/5/delete
// ==============================

if (
    $method === 'POST' &&
    preg_match(
        '#^/mahasiswa/([0-9]+)/delete$#',
        $uri,
        $matches
    )
) {

    $controller->destroy($matches[1]);

    exit;
}


// ==============================
// ROUTE BIASA
// ==============================

if (isset($routes[$method][$uri])) {

    $route = $routes[$method][$uri];

    $controllerName = $route['controller'];

    $action = $route['action'];

    $controller = new $controllerName();

    $controller->$action();

    exit;
}


// ==============================
// 404
// ==============================

http_response_code(404);

echo "<h1>404 - Halaman Tidak Ditemukan</h1>";