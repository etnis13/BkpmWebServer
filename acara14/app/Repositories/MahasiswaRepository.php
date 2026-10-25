<?php

require_once __DIR__ . '/../Models/Mahasiswa.php';

class MahasiswaRepository
{
    private PDO $db;

    public function __construct(PDO $db) { $this->db = $db; }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM mahasiswa ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function existsByNim(string $nim, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim';
        $params = ['nim' => $nim];
        if ($excludeId !== null) {
            $sql .= ' AND id <> :exclude_id';
            $params['exclude_id'] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(Mahasiswa $m): bool
    {
        $sql = 'INSERT INTO mahasiswa (nim, nama, email, angkatan, prodi, status, dosen_id)
                VALUES (:nim, :nama, :email, :angkatan, :prodi, :status, :dosen_id)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nim' => $m->getNim(), 'nama' => $m->getNama(), 'email' => $m->getEmail(),
            'angkatan' => $m->getAngkatan(), 'prodi' => $m->getProdi(),
            'status' => $m->getStatus(), 'dosen_id' => $m->getDosenId()
        ]);
    }

    public function update(int $id, Mahasiswa $m): bool
    {
        $sql = 'UPDATE mahasiswa SET nim = :nim, nama = :nama, email = :email,
                angkatan = :angkatan, prodi = :prodi, status = :status, dosen_id = :dosen_id
                WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id, 'nim' => $m->getNim(), 'nama' => $m->getNama(),
            'email' => $m->getEmail(), 'angkatan' => $m->getAngkatan(),
            'prodi' => $m->getProdi(), 'status' => $m->getStatus(),
            'dosen_id' => $m->getDosenId()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
