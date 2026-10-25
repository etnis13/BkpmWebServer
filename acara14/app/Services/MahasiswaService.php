<?php

require_once __DIR__ . '/../Models/Mahasiswa.php';
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';

class MahasiswaService
{
    public function __construct(
        private MahasiswaRepository $mahasiswaRepository,
        private ProdiRepository $prodiRepository
    ) {}

    public function getAll(): array { return $this->mahasiswaRepository->all(); }
    public function getById(int $id): ?array { return $this->mahasiswaRepository->find($id); }
    public function getAllProdi(): array { return $this->prodiRepository->all(); }

    public function create(array $input): bool
    {
        $data = $this->validate($input);
        if ($this->mahasiswaRepository->existsByNim($data['nim'])) {
            throw new DomainException('NIM sudah terdaftar. Gunakan NIM lain.');
        }
        try {
            return $this->mahasiswaRepository->create($this->makeModel($data));
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') throw new DomainException('NIM sudah terdaftar. Gunakan NIM lain.');
            throw $e;
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
        try {
            return $this->mahasiswaRepository->update($id, $this->makeModel($data));
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') throw new DomainException('NIM sudah digunakan mahasiswa lain.');
            throw $e;
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
        $email = trim((string) ($input['email'] ?? ''));
        $angkatan = trim((string) ($input['angkatan'] ?? ''));
        $prodi = trim((string) ($input['prodi'] ?? ''));
        $status = trim((string) ($input['status'] ?? 'aktif'));
        $dosen = trim((string) ($input['dosen_id'] ?? ''));

        if ($nim === '' || !ctype_digit($nim) || strlen($nim) > 20) {
            throw new DomainException('NIM wajib diisi dengan angka, maksimal 20 digit.');
        }
        if ($nama === '' || strlen($nama) > 100) {
            throw new DomainException('Nama wajib diisi dan maksimal 100 karakter.');
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            throw new DomainException('Masukkan alamat email yang valid.');
        }
        $year = (int) date('Y');
        if ($angkatan === '' || !ctype_digit($angkatan) || (int) $angkatan < 2000 || (int) $angkatan > $year) {
            throw new DomainException('Angkatan harus berupa tahun antara 2000 dan ' . $year . '.');
        }
        if ($prodi === '' || !$this->prodiRepository->existsByName($prodi)) {
            throw new DomainException('Pilih program studi yang tersedia.');
        }
        if (!in_array($status, ['aktif', 'cuti', 'lulus'], true)) {
            throw new DomainException('Status mahasiswa tidak valid.');
        }
        if ($dosen !== '' && !ctype_digit($dosen)) {
            throw new DomainException('Dosen ID harus berupa angka atau dikosongkan.');
        }

        return ['nim'=>$nim, 'nama'=>$nama, 'email'=>$email, 'angkatan'=>(int)$angkatan,
            'prodi'=>$prodi, 'status'=>$status, 'dosen_id'=>$dosen === '' ? null : (int)$dosen];
    }

    private function makeModel(array $data): Mahasiswa
    {
        return new Mahasiswa($data['nim'], $data['nama'], $data['email'], $data['angkatan'],
            $data['prodi'], $data['status'], $data['dosen_id']);
    }
}
