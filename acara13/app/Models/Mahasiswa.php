<?php

require_once __DIR__ . '/BaseModel.php';

class Mahasiswa extends BaseModel
{
    private int $id;
    private string $nim;
    private string $nama;
    private string $prodi;
    private string $status;
    private ?int $dosen_id;

    public function __construct(
        string $nim = '',
        string $nama = '',
        string $prodi = '',
        string $status = 'aktif',
        ?int $dosen_id = null
    ) {
        parent::__construct();

        $this->setNim($nim);
        $this->setNama($nama);
        $this->setProdi($prodi);
        $this->setStatus($status);
        $this->setDosenId($dosen_id);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getProdi(): string
    {
        return $this->prodi;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getDosenId(): ?int
    {
        return $this->dosen_id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNim(string $nim): void
    {
        if ($nim === '' || !ctype_digit($nim)) {
            throw new InvalidArgumentException('NIM harus berupa angka.');
        }

        $this->nim = $nim;
    }

    public function setNama(string $nama): void
    {
        $nama = trim($nama);

        if ($nama === '') {
            throw new InvalidArgumentException('Nama tidak boleh kosong.');
        }

        $this->nama = $nama;
    }

    public function setProdi(string $prodi): void
    {
        $prodi = trim($prodi);

        if ($prodi === '') {
            throw new InvalidArgumentException('Prodi tidak boleh kosong.');
        }

        $this->prodi = $prodi;
    }

    public function setStatus(string $status): void
    {
        if (!in_array($status, ['aktif', 'cuti', 'lulus'])) {
            throw new InvalidArgumentException('Status tidak valid.');
        }

        $this->status = $status;
    }

    public function setDosenId(?int $dosen_id): void
    {
        $this->dosen_id = $dosen_id;
    }
}