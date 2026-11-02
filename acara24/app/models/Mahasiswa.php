<?php
class Mahasiswa {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(): array {
        return $this->db->query('SELECT * FROM mahasiswa ORDER BY id DESC')->fetchAll();
    }

    public function find(int $id): array|false {
        $stmt = $this->db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function existsByNim(string $nim, ?int $excludeId = null): bool {
        $sql = 'SELECT id FROM mahasiswa WHERE nim = :nim';
        $params = [':nim' => $nim];
        if ($excludeId !== null) {
            $sql .= ' AND id <> :id';
            $params[':id'] = $excludeId;
        }
        $stmt = $this->db->prepare($sql . ' LIMIT 1');
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }

    public function create(array $data): string {
        if ($this->existsByNim(trim($data['nim']))) {
            throw new DomainException('NIM sudah digunakan');
        }
        $stmt = $this->db->prepare(
            'INSERT INTO mahasiswa (nim, nama, prodi) VALUES (:nim, :nama, :prodi)'
        );
        $stmt->execute([
            ':nim' => trim($data['nim']),
            ':nama' => trim($data['nama']),
            ':prodi' => trim($data['prodi'])
        ]);
        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        if (!$this->find($id)) return false;
        if ($this->existsByNim(trim($data['nim']), $id)) {
            throw new DomainException('NIM sudah digunakan');
        }
        $stmt = $this->db->prepare(
            'UPDATE mahasiswa SET nim = :nim, nama = :nama, prodi = :prodi WHERE id = :id'
        );
        return $stmt->execute([
            ':nim' => trim($data['nim']),
            ':nama' => trim($data['nama']),
            ':prodi' => trim($data['prodi']),
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
