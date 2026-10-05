<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/Mahasiswa.php';

class MahasiswaRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM mahasiswa ORDER BY id DESC"
        );

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM mahasiswa WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    public function create(Mahasiswa $mahasiswa): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO mahasiswa
            (nim, nama, prodi, status, dosen_id)
            VALUES
            (:nim, :nama, :prodi, :status, :dosen_id)
        ");

        return $stmt->execute([
            'nim' => $mahasiswa->getNim(),
            'nama' => $mahasiswa->getNama(),
            'prodi' => $mahasiswa->getProdi(),
            'status' => $mahasiswa->getStatus(),
            'dosen_id' => $mahasiswa->getDosenId()
        ]);
    }

    public function update(int $id, Mahasiswa $mahasiswa): bool
    {
        $stmt = $this->db->prepare("
            UPDATE mahasiswa SET
                nim = :nim,
                nama = :nama,
                prodi = :prodi,
                status = :status,
                dosen_id = :dosen_id
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'nim' => $mahasiswa->getNim(),
            'nama' => $mahasiswa->getNama(),
            'prodi' => $mahasiswa->getProdi(),
            'status' => $mahasiswa->getStatus(),
            'dosen_id' => $mahasiswa->getDosenId()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM mahasiswa WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}