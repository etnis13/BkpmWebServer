<?php

class AuthController
{
    public function loginForm()
    {
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        session_start();
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        if ($username === 'admin' && $password === '12345') {
            $_SESSION['user_id'] = 1;
            $_SESSION['user_name'] = 'Admin';
            $_SESSION['logged_in'] = true;
            $_SESSION['flash'] = 'Selamat datang, Admin';
            header('Location: /BkpmWebServer/acara13/public/dashboard');
            exit();
        } else {
            $_SESSION['flash'] = 'Username atau password salah';
            header('Location: /BkpmWebServer/acara13/public/login');
            exit();
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        session_start();
        $_SESSION['flash'] = 'Anda telah logout';
        header('Location: /BkpmWebServer/acara13/public/login');
        exit();
    }
}
