<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Mahasiswa.php';
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';

class MahasiswaController extends BaseController
{
    private MahasiswaRepository $repo;

    public function __construct(MahasiswaRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(): void
    {
        $mahasiswa = $this->repo->all();

        $this->view('mahasiswa/index', [
            'mahasiswa' => $mahasiswa
        ]);
    }

    public function create(): void
    {
        $this->view('mahasiswa/create');
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

            $this->redirect('/BkpmWebServer/acara10/public/mahasiswa');

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

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa
        ]);
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

            $this->redirect('/BkpmWebServer/acara10/public/mahasiswa');

        } catch (InvalidArgumentException $e) {
            echo $e->getMessage();
        }
    }

    public function destroy(int $id): void
    {
        $this->repo->delete($id);

        $this->redirect('/BkpmWebServer/acara10/public/mahasiswa');
    }
}