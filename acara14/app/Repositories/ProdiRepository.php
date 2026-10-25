<?php

require_once __DIR__ . '/../Core/Database.php';

class ProdiRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT id, kode, nama FROM prodi ORDER BY nama ASC");
        return $stmt->fetchAll();
    }

    public function existsByName(string $nama): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM prodi WHERE nama = :nama");
        $stmt->execute(['nama' => $nama]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
