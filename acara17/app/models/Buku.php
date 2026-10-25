
<?php

class Buku
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        $query = $this->db->query(
            "SELECT * FROM buku ORDER BY id DESC"
        );

        return $query->fetchAll();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO buku (judul, penulis)
                VALUES (:judul, :penulis)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':judul' => $data['judul'],
            ':penulis' => $data['penulis']
        ]);
    }
}
