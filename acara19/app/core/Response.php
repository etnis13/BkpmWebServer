<?php

class Response
{
    public static function json(
        int $httpCode,
        bool $success,
        string $message,
        $data = null,
        array $errors = []
    ): void {
        http_response_code($httpCode);
        header('Content-Type: application/json');

        // Format JSON mengikuti mode aplikasi
        $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

        if (defined('APP_ENV') && APP_ENV === 'development') {
            $options |= JSON_PRETTY_PRINT;
        }

        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'errors' => $errors,
            'timestamp' => date('c'),
        ], $options);

        exit;
    }
}
