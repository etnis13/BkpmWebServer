<?php

require_once __DIR__ . '/../Core/Logger.php';

class AuthController
{
    public function loginForm(): void
    {
        if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        $token = (string) ($_POST['csrf_token'] ?? '');
        if (empty($_SESSION['csrf_token']) || !hash_equals((string)$_SESSION['csrf_token'], $token)) {
            $_SESSION['flash'] = 'Permintaan tidak valid. Muat ulang halaman login.';
            header('Location: /BkpmWebServer/acara14/public/login', true, 303);
            exit;
        }

        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        if ($username === 'admin' && $password === '12345') {
            session_regenerate_id(true);
            $_SESSION['user_id'] = 1;
            $_SESSION['user_name'] = 'Admin';
            $_SESSION['logged_in'] = true;
            $_SESSION['flash'] = 'Selamat datang, Admin';
            header('Location: /BkpmWebServer/acara14/public/dashboard', true, 303);
            exit;
        }
        $_SESSION['flash'] = 'Username atau password salah.';
        header('Location: /BkpmWebServer/acara14/public/login', true, 303);
        exit;
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        header('Location: /BkpmWebServer/acara14/public/login', true, 303);
        exit;
    }
}
