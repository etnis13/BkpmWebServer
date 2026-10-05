<?php

require_once __DIR__ . '/../Core/Database.php';

class Mahasiswa
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // READ
    public function all()
    {
        $sql = '
            SELECT 
                mahasiswa.*,
                prodi.kode AS kode_prodi,
                prodi.nama AS nama_prodi
            FROM mahasiswa
            LEFT JOIN prodi ON mahasiswa.prodi = prodi.id
            ORDER BY mahasiswa.id DESC
        ';

        return $this->db->query($sql)->fetchAll();
    }

    // SEARCH
    public function search($keyword)
    {
        $sql = '
            SELECT 
                mahasiswa.*,
                prodi.kode AS kode_prodi,
                prodi.nama AS nama_prodi
            FROM mahasiswa
            LEFT JOIN prodi ON mahasiswa.prodi = prodi.id
            WHERE mahasiswa.nama LIKE :nama
               OR mahasiswa.nim LIKE :nim
            ORDER BY mahasiswa.id DESC
        ';

        $stmt = $this->db->prepare($sql);

        $keyword = '%' . $keyword . '%';

        $stmt->execute([
            'nama' => $keyword,
            'nim' => $keyword
        ]);

        return $stmt->fetchAll();
    }

    // FIND
    public function find($id)
    {
        $sql = '
            SELECT 
                mahasiswa.*,
                prodi.kode AS kode_prodi,
                prodi.nama AS nama_prodi
            FROM mahasiswa
            LEFT JOIN prodi ON mahasiswa.prodi = prodi.id
            WHERE mahasiswa.id = :id
        ';

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch();
    }

    // GET PRODI
    public function getProdi()
    {
        $sql = 'SELECT * FROM prodi ORDER BY nama ASC';

        return $this->db->query($sql)->fetchAll();
    }

    // CREATE
    public function create($data)
{
    $sql = '
        INSERT INTO mahasiswa
        (nim, nama, prodi, status, dosen_id)
        VALUES
        (:nim, :nama, :prodi, :status, :dosen_id)
    ';

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        'nim' => $data['nim'],
        'nama' => $data['nama'],
        'prodi' => $data['prodi'],
        'status' => $data['status'],
        'dosen_id' => $data['dosen_id']
    ]);
}

    // UPDATE
    public function update($id, $data)
{
    $sql = '
        UPDATE mahasiswa
        SET
            nim = :nim,
            nama = :nama,
            prodi = :prodi,
            status = :status,
            dosen_id = :dosen_id
        WHERE id = :id
    ';

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        'id' => $id,
        'nim' => $data['nim'],
        'nama' => $data['nama'],
        'prodi' => $data['prodi'],
        'status' => $data['status'],
        'dosen_id' => $data['dosen_id']
    ]);
}

    // DELETE
    public function delete($id)
    {
        $sql = 'DELETE FROM mahasiswa WHERE id = :id';

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id
        ]);
    }
}