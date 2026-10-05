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
        header("Location: {$url}");
        exit;
    }
}