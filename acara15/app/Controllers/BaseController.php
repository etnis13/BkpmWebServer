<?php

class BaseController
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $content = __DIR__ . '/../Views/' . $view . '.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url, true, 303);
        exit;
    }

    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrf(): void
    {
        $submitted = (string) ($_POST['csrf_token'] ?? '');
        $stored = (string) ($_SESSION['csrf_token'] ?? '');
        if ($stored === '' || !hash_equals($stored, $submitted)) {
            throw new DomainException('Permintaan tidak valid. Muat ulang halaman lalu coba lagi.');
        }
    }

    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
}
