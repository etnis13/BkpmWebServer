
<?php
class Router {
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'register' => 'AuthController',
        'login' => 'AuthController'
    ];

    public function dispatch(): void {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = '/BkpmWebServer/acara21/public';

        $path = substr($uri, strlen($basePath));
        $path = trim($path, '/');

        $resource = $path !== '' ? explode('/', $path)[0] : 'mahasiswa';

        $controllerName = $this->map[$resource] ?? null;

        if (!$controllerName) {
            Response::json(404, false, 'Endpoint tidak ditemukan');
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $controller = new $controllerName();

        if ($resource === 'register' || $resource === 'login') {
            if ($method !== 'POST') {
                header('Allow: POST');
                Response::json(405, false, 'Method tidak diizinkan');
                return;
            }

            $resource === 'register'
                ? $controller->register()
                : $controller->login();

            return;
        }

        if (in_array($method, ['POST', 'PUT', 'DELETE'], true)) {
            Auth::requireToken();
        }

        $id = $_GET['id'] ?? null;

        switch ($method) {
            case 'GET':
                $controller->index();
                break;

            case 'POST':
                $controller->store();
                break;

            case 'PUT':
                if ($id === null || $id === '') {
                    Response::json(400, false, 'Parameter id wajib diisi');
                    return;
                }

                if (!ctype_digit((string)$id) || (int)$id < 1) {
                    Response::json(400, false, 'Parameter id harus berupa bilangan bulat positif');
                    return;
                }

                $controller->update((int)$id);
                break;

            case 'DELETE':
                if ($id === null || $id === '') {
                    Response::json(400, false, 'Parameter id wajib diisi');
                    return;
                }

                if (!ctype_digit((string)$id) || (int)$id < 1) {
                    Response::json(400, false, 'Parameter id harus berupa bilangan bulat positif');
                    return;
                }

                $controller->destroy((int)$id);
                break;

            default:
                header('Allow: GET, POST, PUT, DELETE');
                Response::json(405, false, 'Method tidak diizinkan');
                return;
        }
    }
}
