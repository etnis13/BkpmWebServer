
<?php

class AuthController {
    private User $user;

    public function __construct() {
        $this->user = new User();
    }

    public function register(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            Response::json(
                400,
                false,
                'Format JSON tidak valid'
            );
        }

        $username = trim($data['username'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if ($username === '' || $email === '' || $password === '') {
            Response::json(
                400,
                false,
                'Username, email, dan password wajib diisi'
            );
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::json(
                422,
                false,
                'Format email tidak valid'
            );
        }

        if (strlen($password) < 8) {
            Response::json(
                422,
                false,
                'Password minimal 8 karakter'
            );
        }

        if ($this->user->findByUsername($username)) {
            Response::json(
                409,
                false,
                'Username sudah digunakan'
            );
        }

        $dataUser = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash(
                $password,
                PASSWORD_DEFAULT
            )
        ];

        try {
            $id = $this->user->create($dataUser);

            Response::json(
                201,
                true,
                'Registrasi berhasil',
                ['id' => $id]
            );
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                Response::json(
                    409,
                    false,
                    'Username atau email sudah digunakan'
                );
            }

            Response::json(
                500,
                false,
                'Terjadi kesalahan pada database'
            );
        }
    }
}
