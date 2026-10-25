
<?php

class Router
{
   private array $map = [
    'mahasiswa' => 'MahasiswaController',
    'buku' => 'BukuController',
];

    public function dispatch(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        // Mengambil alamat URL yang sedang diakses
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Menghapus folder proyek dari URL
        $basePath = '/BkpmWebServer/acara17/public';

        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Membersihkan URL
        $uri = trim($uri, '/');
        $segments = $uri === '' ? [] : explode('/', $uri);

        // Menentukan endpoint
        $resource = $segments[0] ?? 'mahasiswa';

        // URL index.php/mahasiswa
        if ($resource === 'index.php') {
            $resource = $segments[1] ?? 'mahasiswa';
        }

        // Memeriksa apakah endpoint tersedia
        $controllerName = $this->map[$resource] ?? null;

        if ($controllerName === null) {
            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Endpoint tidak ditemukan',
                'endpoint' => $resource
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            return;
        }

        // Membuat objek controller
        $controller = new $controllerName();

        // Mengambil ID dari parameter URL
        $id = $_GET['id'] ?? null;

        // Menjalankan fungsi sesuai HTTP method
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $controller->index();
                break;

            case 'POST':
                $controller->store();
                break;

            case 'PUT':
                if ($id === null || $id === '' || !ctype_digit((string) $id) || (int) $id < 1) {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Parameter id wajib berupa bilangan bulat positif'
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    return;
                }

                $controller->update((int) $id);
                break;

            case 'DELETE':
                if ($id === null || $id === '' || !ctype_digit((string) $id) || (int) $id < 1) {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Parameter id wajib berupa bilangan bulat positif'
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    return;
                }

                $controller->destroy((int) $id);
                break;

            default:
                http_response_code(405);
                header('Allow: GET, POST, PUT, DELETE');

                echo json_encode([
                    'success' => false,
                    'message' => 'Method tidak diizinkan'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                break;
        }
    }
}