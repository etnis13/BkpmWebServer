<?php

class Router
{
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
    ];

    public function dispatch(): void
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $uri);
        $resource = end($segments) ?: 'mahasiswa';
        $controllerName = $this->map[$resource] ?? null;

        if (!$controllerName) {
            Response::json(404, false, 'Endpoint tidak ditemukan');
        }

        $controller = new $controllerName();
        $id = $_GET['id'] ?? null;

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $controller->index();
                break;
            case 'POST':
                $controller->store();
                break;
            case 'PUT':
                if ($id === null || $id === '') {
                    Response::json(400, false, 'Parameter id wajib diisi');
                }
                if (!ctype_digit((string) $id) || (int) $id < 1) {
                    Response::json(400, false, 'Parameter id harus berupa bilangan bulat positif');
                }
                $controller->update((int) $id);
                break;
            case 'DELETE':
                if ($id === null || $id === '') {
                    Response::json(400, false, 'Parameter id wajib diisi');
                }
                if (!ctype_digit((string) $id) || (int) $id < 1) {
                    Response::json(400, false, 'Parameter id harus berupa bilangan bulat positif');
                }
                $controller->destroy((int) $id);
                break;
            default:
                header('Allow: GET, POST, PUT, DELETE');
                Response::json(405, false, 'Method tidak diizinkan');
        }
    }
}
