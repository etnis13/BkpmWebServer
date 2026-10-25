<?php

require_once __DIR__ . '/../Models/Mahasiswa.php';
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';

class MahasiswaService
{
    private MahasiswaRepository $mahasiswaRepository;
    private ProdiRepository $prodiRepository;

    // Dependency injection: kedua repository diberikan melalui constructor.
    public function __construct(
        MahasiswaRepository $mahasiswaRepository,
        ProdiRepository $prodiRepository
    ) {
        $this->mahasiswaRepository = $mahasiswaRepository;
        $this->prodiRepository = $prodiRepository;
    }

    public function getAll(): array
    {
        return $this->mahasiswaRepository->all();
    }

    public function getById(int $id): ?array
    {
        return $this->mahasiswaRepository->find($id);
    }

    public function getAllProdi(): array
    {
        return $this->prodiRepository->all();
    }

    public function create(array $input): bool
    {
        $data = $this->validate($input);

        if ($this->mahasiswaRepository->existsByNim($data['nim'])) {
            throw new DomainException('NIM sudah terdaftar. Gunakan NIM lain.');
        }

        $mahasiswa = new Mahasiswa(
            $data['nim'], $data['nama'], $data['prodi'], $data['status'], $data['dosen_id']
        );

        try {
            return $this->mahasiswaRepository->create($mahasiswa);
        } catch (PDOException $e) {
            // Menangani juga kemungkinan NIM dibuat bersamaan oleh proses lain.
            if ($e->getCode() === '23000') {
                throw new DomainException('NIM sudah terdaftar. Gunakan NIM lain.');
            }
            throw new RuntimeException('Data mahasiswa gagal disimpan.');
        }
    }

    public function update(int $id, array $input): bool
    {
        if (!$this->mahasiswaRepository->find($id)) {
            throw new DomainException('Data mahasiswa tidak ditemukan.');
        }

        $data = $this->validate($input);

        if ($this->mahasiswaRepository->existsByNim($data['nim'], $id)) {
            throw new DomainException('NIM sudah digunakan mahasiswa lain.');
        }

        $mahasiswa = new Mahasiswa(
            $data['nim'], $data['nama'], $data['prodi'], $data['status'], $data['dosen_id']
        );

        try {
            return $this->mahasiswaRepository->update($id, $mahasiswa);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new DomainException('NIM sudah digunakan mahasiswa lain.');
            }
            throw new RuntimeException('Perubahan data gagal disimpan.');
        }
    }

    public function delete(int $id): bool
    {
        if (!$this->mahasiswaRepository->find($id)) {
            throw new DomainException('Data mahasiswa tidak ditemukan.');
        }

        return $this->mahasiswaRepository->delete($id);
    }

    private function validate(array $input): array
    {
        $nim = trim((string) ($input['nim'] ?? ''));
        $nama = trim((string) ($input['nama'] ?? ''));
        $prodi = trim((string) ($input['prodi'] ?? ''));
        $status = trim((string) ($input['status'] ?? ''));
        $dosenInput = trim((string) ($input['dosen_id'] ?? ''));

        if ($nim === '' || !ctype_digit($nim)) {
            throw new DomainException('NIM wajib diisi dan harus berupa angka.');
        }
        if ($nama === '') {
            throw new DomainException('Nama mahasiswa wajib diisi.');
        }
        if (strlen($nama) > 100) {
            throw new DomainException('Nama mahasiswa maksimal 100 karakter.');
        }
        if ($prodi === '' || !$this->prodiRepository->existsByName($prodi)) {
            throw new DomainException('Pilih program studi yang tersedia.');
        }
        if (!in_array($status, ['aktif', 'cuti', 'lulus'], true)) {
            throw new DomainException('Status mahasiswa tidak valid.');
        }
        if ($dosenInput !== '' && !ctype_digit($dosenInput)) {
            throw new DomainException('Dosen ID harus berupa angka atau dikosongkan.');
        }

        return [
            'nim' => $nim,
            'nama' => $nama,
            'prodi' => $prodi,
            'status' => $status,
            'dosen_id' => $dosenInput === '' ? null : (int) $dosenInput,
        ];
    }
}
