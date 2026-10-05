<?php

require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/BkpmWebServer/acara5/public';

if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'];

// Route dinamis: /mahasiswa/{id}
if ($method === 'GET' && preg_match('#^/mahasiswa/([0-9]+)$#', $uri, $matches)) {
    $id = $matches[1];

    $controller = new MahasiswaController();
    $controller->show($id);
    exit();
}

// Route biasa
if (isset($routes[$method][$uri])) {
    [$controllerName, $action] = $routes[$method][$uri];

    $controller = new $controllerName();

    $controller->$action();
    exit();
}

// 404
http_response_code(404);
echo '404 - Halaman tidak ditemukan';
