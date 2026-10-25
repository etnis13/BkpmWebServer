<?php

class Logger
{
    public static function error(Throwable $exception): void
    {
        $directory = __DIR__ . '/../../storage/logs';
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $message = sprintf(
            "[%s] %s: %s in %s:%d%s",
            date('Y-m-d H:i:s'),
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            PHP_EOL
        );

        error_log($message, 3, $directory . '/app.log');
    }
}
