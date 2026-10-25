<?php

class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    private function readJson(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data) || json_last_error() !== JSON_ERROR_NONE) {
            Response::json(400, false, 'Format JSON tidak valid');
        }

        return $data;
    }

    private function validate(array $data): void
    {
        foreach (['nim', 'nama', 'prodi'] as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                Response::json(400, false, "Field {$field} wajib diisi");
            }
        }

        
        if (strlen(trim((string) $data['nim'])) !== 9 || !ctype_digit(trim((string) $data['nim']))) {
            Response::json(
                422,
                false,
                'Validasi data gagal',
                null,
                [
                    'nim' => [
                        'NIM harus terdiri dari 9 digit angka'
                    ]
                ]
            );
        }

    }

    public function index(): void
    {
        $id = $_GET['id'] ?? null;

        if ($id !== null && $id !== '') {
            if (!ctype_digit((string) $id) || (int) $id < 1) {
                Response::json(400, false, 'Parameter id harus berupa bilangan bulat positif');
            }

            $data = $this->model->find((int) $id);
            if (!$data) {
                Response::json(404, false, 'Data tidak ditemukan');
            }

            Response::json(200, true, 'Data mahasiswa berhasil ditemukan', $data);
        }

        Response::json(200, true, 'Data mahasiswa berhasil diambil', $this->model->getAll());
    }

    public function store(): void
    {
        $data = $this->readJson();
        $this->validate($data);

        try {
            $id = $this->model->create($data);
            Response::json(201, true, 'Data mahasiswa berhasil ditambahkan', ['id' => $id]);
        } catch (DomainException $e) {
            Response::json(409, false, $e->getMessage());
        }
    }

    public function update(int $id): void
    {
        $data = $this->readJson();
        $this->validate($data);

        try {
            if (!$this->model->update($id, $data)) {
                Response::json(404, false, 'Data tidak ditemukan');
            }

            Response::json(200, true, 'Data mahasiswa berhasil diperbarui');
        } catch (DomainException $e) {
            Response::json(409, false, $e->getMessage());
        }
    }

    public function destroy(int $id): void
    {
        if (!$this->model->delete($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        Response::json(200, true, 'Data mahasiswa berhasil dihapus');
    }
}
