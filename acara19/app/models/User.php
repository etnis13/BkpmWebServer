
<?php

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(array $data): string {
        // Validasi format email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                'Format email tidak valid'
            );
        }

        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password)
             VALUES (:u, :e, :p)'
        );

        $stmt->execute([
            ':u' => $data['username'],
            ':e' => $data['email'],
            ':p' => $data['password']
        ]);

        return $this->db->lastInsertId();
    }

    public function findByUsername(string $username) {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE username = :u'
        );

        $stmt->execute([':u' => $username]);

        return $stmt->fetch();
    }
}
