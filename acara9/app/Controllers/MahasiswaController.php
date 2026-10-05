<?php

require_once __DIR__ . '/../Models/Mahasiswa.php';
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';

class MahasiswaController
{
    private MahasiswaRepository $repo;

    public function __construct(MahasiswaRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(): void
    {
        $mahasiswa = $this->repo->all();

        $content = __DIR__ . '/../Views/mahasiswa/index.php';

        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function create(): void
    {
        $content = __DIR__ . '/../Views/mahasiswa/create.php';

        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function store(): void
    {
        try {

            $mahasiswa = new Mahasiswa(
                trim($_POST['nim']),
                trim($_POST['nama']),
                trim($_POST['prodi']),
                $_POST['status'],
                !empty($_POST['dosen_id'])
                    ? (int) $_POST['dosen_id']
                    : null
            );

            $this->repo->create($mahasiswa);

            header(
                'Location: /BkpmWebServer/acara9/public/mahasiswa'
            );

            exit;

        } catch (InvalidArgumentException $e) {

            echo $e->getMessage();
        }
    }

    public function edit(int $id): void
    {
        $mahasiswa = $this->repo->find($id);

        if (!$mahasiswa) {
            http_response_code(404);
            echo 'Data mahasiswa tidak ditemukan.';
            return;
        }

        $content = __DIR__ . '/../Views/mahasiswa/edit.php';

        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function update(int $id): void
    {
        try {

            $mahasiswa = new Mahasiswa(
                trim($_POST['nim']),
                trim($_POST['nama']),
                trim($_POST['prodi']),
                $_POST['status'],
                !empty($_POST['dosen_id'])
                    ? (int) $_POST['dosen_id']
                    : null
            );

            $this->repo->update($id, $mahasiswa);

            header(
                'Location: /BkpmWebServer/acara9/public/mahasiswa'
            );

            exit;

        } catch (InvalidArgumentException $e) {

            echo $e->getMessage();
        }
    }

    public function destroy(int $id): void
    {
        $this->repo->delete($id);

        header(
            'Location: /BkpmWebServer/acara9/public/mahasiswa'
        );

        exit;
    }
}