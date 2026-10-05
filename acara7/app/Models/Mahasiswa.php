<?php

require_once __DIR__ . '/Model.php';
class Mahasiswa extends Model
{
    public function all()
    {
        $sql = ' SELECT 
        mahasiswa.id, 
        mahasiswa.nim, 
        mahasiswa.nama, 
        mahasiswa.prodi, 
        mahasiswa.dosen_id, 
        mahasiswa.status, 
        dosen.nama AS nama_dosen 
        FROM mahasiswa LEFT JOIN dosen ON mahasiswa.dosen_id = dosen.id ORDER BY mahasiswa.id ASC ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
