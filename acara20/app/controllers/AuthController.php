<?php
class AuthController {
    private User $user;
    public function __construct() { $this->user = new User(); }

    public function login(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data) || json_last_error() !== JSON_ERROR_NONE) Response::json(400, false, 'Format JSON tidak valid');
        $username = trim((string)($data['username'] ?? ''));
        $password = (string)($data['password'] ?? '');
        if ($username === '' || $password === '') Response::json(400, false, 'Username dan password wajib diisi');
        $user = $this->user->findByUsername($username);
        if (!$user || !password_verify($password, $user['password'])) Response::json(401, false, 'Username atau password salah');
        $token = Auth::createToken(['sub' => (int)$user['id'], 'username' => $user['username'], 'role' => $user['role']]);
        Response::json(200, true, 'Login berhasil', ['token' => $token, 'token_type' => 'Bearer', 'expires_in' => 3600]);
    }

    public function register(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data) || json_last_error() !== JSON_ERROR_NONE) Response::json(400, false, 'Format JSON tidak valid');
        $username = trim((string)($data['username'] ?? ''));
        $email = trim((string)($data['email'] ?? ''));
        $password = (string)($data['password'] ?? '');
        if ($username === '' || $email === '' || $password === '') Response::json(400, false, 'Username, email, dan password wajib diisi');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) Response::json(422, false, 'Format email tidak valid');
        if (strlen($password) < 8) Response::json(422, false, 'Password minimal 8 karakter');
        if ($this->user->findByUsername($username)) Response::json(409, false, 'Username sudah digunakan');
        try {
            $id = $this->user->create(['username'=>$username, 'email'=>$email, 'password'=>password_hash($password, PASSWORD_DEFAULT)]);
            Response::json(201, true, 'Registrasi berhasil', ['id'=>$id]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') Response::json(409, false, 'Username atau email sudah digunakan');
            Response::json(500, false, 'Terjadi kesalahan pada database');
        }
    }
}
