<?php class Mahasiswa
{
    private string $nim;
    private string $nama;
    private string $prodi;
    public function __construct(string $nim, string $nama, string $prodi)
    {
        $this->nim = $nim;
        $this->nama = $nama;
        $this->prodi = $prodi;
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
    
}