<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Logger.php';
require_once __DIR__ . '/../app/Models/BaseModel.php';
require_once __DIR__ . '/../app/Models/Mahasiswa.php';
require_once __DIR__ . '/../app/Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../app/Repositories/ProdiRepository.php';
require_once __DIR__ . '/../app/Services/MahasiswaService.php';
require_once __DIR__ . '/../app/Controllers/BaseController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Middleware/AuthMiddleware.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$base = '/BkpmWebServer/acara14/public';
if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}
if ($uri === '') $uri = '/';

try {
    if ($method === 'GET' && preg_match('#^/mahasiswa/([0-9]+)/edit$#', $uri, $m)) {
        (new AuthMiddleware())->handle();
        $controller = new MahasiswaController(new MahasiswaService(
            new MahasiswaRepository(Database::getInstance()), new ProdiRepository(Database::getInstance())
        ));
        $controller->edit((int) $m[1]);
        exit;
    }
    if ($method === 'POST' && preg_match('#^/mahasiswa/([0-9]+)$#', $uri, $m)) {
        (new AuthMiddleware())->handle();
        $controller = new MahasiswaController(new MahasiswaService(
            new MahasiswaRepository(Database::getInstance()), new ProdiRepository(Database::getInstance())
        ));
        $controller->update((int) $m[1]);
        exit;
    }
    if ($method === 'POST' && preg_match('#^/mahasiswa/([0-9]+)/delete$#', $uri, $m)) {
        (new AuthMiddleware())->handle();
        $controller = new MahasiswaController(new MahasiswaService(
            new MahasiswaRepository(Database::getInstance()), new ProdiRepository(Database::getInstance())
        ));
        $controller->destroy((int) $m[1]);
        exit;
    }

    $route = $routes[$method][$uri] ?? null;
    if (!$route) {
        http_response_code(404);
        echo '<h1>404 - Halaman Tidak Ditemukan</h1>';
        exit;
    }
    foreach (($route['middleware'] ?? []) as $middlewareName) {
        if ($middlewareName === 'AuthMiddleware') (new AuthMiddleware())->handle();
    }

    $controllerName = $route['controller'];
    $action = $route['action'];
    if ($controllerName === 'MahasiswaController') {
        $controller = new MahasiswaController(new MahasiswaService(
            new MahasiswaRepository(Database::getInstance()), new ProdiRepository(Database::getInstance())
        ));
    } elseif ($controllerName === 'AuthController') {
        $controller = new AuthController();
    } elseif ($controllerName === 'HomeController') {
        $controller = new HomeController();
    } else {
        http_response_code(404); echo '<h1>Controller tidak ditemukan</h1>'; exit;
    }
    if (!method_exists($controller, $action)) {
        http_response_code(500); echo '<h1>Terjadi kesalahan pada aplikasi.</h1>'; exit;
    }
    $controller->$action();
} catch (Throwable $e) {
    Logger::error($e);
    http_response_code(500);
    echo '<h1>Terjadi kesalahan.</h1><p>Aplikasi tidak dapat memproses permintaan saat ini.</p>';
}
