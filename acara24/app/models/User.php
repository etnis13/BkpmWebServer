<?php
class User {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }
    public function findByUsername(string $username): array|false {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :u LIMIT 1');
        $stmt->execute([':u' => $username]);
        return $stmt->fetch();
    }
    public function create(array $data): string {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Format email tidak valid');
        }
        $stmt = $this->db->prepare('INSERT INTO users (username, email, password, role) VALUES (:u, :e, :p, :r)');
        $stmt->execute([':u'=>$data['username'], ':e'=>$data['email'], ':p'=>$data['password'], ':r'=>$data['role'] ?? 'user']);
        return $this->db->lastInsertId();
    }
}
