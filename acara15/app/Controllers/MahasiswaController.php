<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Services/MahasiswaService.php';
require_once __DIR__ . '/../Core/Logger.php';

class MahasiswaController extends BaseController
{
    public function __construct(private MahasiswaService $service) {}

    public function index(): void
    {
        try {
            $this->view('mahasiswa/index', ['mahasiswa' => $this->service->getAll(), 'csrfToken' => $this->csrfToken()]);
        } catch (Throwable $e) {
            Logger::error($e);
            $this->flash('danger', 'Data mahasiswa tidak dapat ditampilkan saat ini.');
            $this->view('mahasiswa/index', ['mahasiswa' => [], 'csrfToken' => $this->csrfToken()]);
        }
    }

    public function create(): void
    {
        $this->view('mahasiswa/create', [
            'prodiList' => $this->service->getAllProdi(),
            'csrfToken' => $this->csrfToken()
        ]);
    }

    public function store(): void
    {
        try {
            $this->verifyCsrf();
            if (!$this->service->create($_POST)) {
                throw new RuntimeException('Repository mengembalikan hasil gagal saat create.');
            }
            $this->flash('success', 'Data mahasiswa berhasil ditambahkan.');
        } catch (DomainException $e) {
            $this->flash('danger', $e->getMessage());
        } catch (Throwable $e) {
            Logger::error($e);
            $this->flash('danger', 'Data gagal disimpan. Periksa data lalu coba kembali.');
        }
        $this->redirect('/BkpmWebServer/acara14/public/mahasiswa');
    }

    public function edit(int $id): void
    {
        try {
            $mahasiswa = $this->service->getById($id);
            if (!$mahasiswa) {
                http_response_code(404);
                $this->flash('danger', 'Data mahasiswa tidak ditemukan.');
                $this->redirect('/BkpmWebServer/acara14/public/mahasiswa');
            }
            $this->view('mahasiswa/edit', [
                'mahasiswa' => $mahasiswa,
                'prodiList' => $this->service->getAllProdi(),
                'csrfToken' => $this->csrfToken()
            ]);
        } catch (Throwable $e) {
            Logger::error($e);
            $this->flash('danger', 'Data mahasiswa tidak dapat dibuka saat ini.');
            $this->redirect('/BkpmWebServer/acara14/public/mahasiswa');
        }
    }

    public function update(int $id): void
    {
        try {
            $this->verifyCsrf();
            if (!$this->service->update($id, $_POST)) {
                throw new RuntimeException('Repository mengembalikan hasil gagal saat update.');
            }
            $this->flash('success', 'Data mahasiswa berhasil diubah.');
        } catch (DomainException $e) {
            $this->flash('danger', $e->getMessage());
        } catch (Throwable $e) {
            Logger::error($e);
            $this->flash('danger', 'Data gagal disimpan. Periksa data lalu coba kembali.');
        }
        $this->redirect('/BkpmWebServer/acara14/public/mahasiswa');
    }

    public function destroy(int $id): void
    {
        try {
            $this->verifyCsrf();
            if (!$this->service->delete($id)) {
                throw new RuntimeException('Repository mengembalikan hasil gagal saat delete.');
            }
            $this->flash('success', 'Data mahasiswa berhasil dihapus.');
        } catch (DomainException $e) {
            $this->flash('danger', $e->getMessage());
        } catch (Throwable $e) {
            Logger::error($e);
            $this->flash('danger', 'Data gagal dihapus. Silakan coba kembali.');
        }
        $this->redirect('/BkpmWebServer/acara14/public/mahasiswa');
    }
}
