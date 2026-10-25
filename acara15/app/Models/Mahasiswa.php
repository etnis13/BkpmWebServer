<?php

require_once __DIR__ . '/BaseModel.php';

class Mahasiswa extends BaseModel
{
    private string $nim;
    private string $nama;
    private string $email;
    private int $angkatan;
    private string $prodi;
    private string $status;
    private ?int $dosen_id;

    public function __construct(
        string $nim,
        string $nama,
        string $email,
        int $angkatan,
        string $prodi,
        string $status = 'aktif',
        ?int $dosen_id = null
    ) {
        parent::__construct();
        $this->nim = $nim;
        $this->nama = $nama;
        $this->email = $email;
        $this->angkatan = $angkatan;
        $this->prodi = $prodi;
        $this->status = $status;
        $this->dosen_id = $dosen_id;
    }

    public function getNim(): string { return $this->nim; }
    public function getNama(): string { return $this->nama; }
    public function getEmail(): string { return $this->email; }
    public function getAngkatan(): int { return $this->angkatan; }
    public function getProdi(): string { return $this->prodi; }
    public function getStatus(): string { return $this->status; }
    public function getDosenId(): ?int { return $this->dosen_id; }
}
