<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Services/MahasiswaService.php';

class MahasiswaController extends BaseController
{
    private MahasiswaService $service;

    public function __construct(MahasiswaService $service)
    {
        $this->service = $service;
    }

    public function index(): void
    {
        $this->view('mahasiswa/index', [
            'mahasiswa' => $this->service->getAll()
        ]);
    }

    public function create(): void
    {
        $this->view('mahasiswa/create', [
            'prodiList' => $this->service->getAllProdi()
        ]);
    }

    public function store(): void
    {
        try {
            if ($this->service->create($_POST)) {
                $this->setFlash('success', 'Data mahasiswa berhasil ditambahkan.');
            } else {
                $this->setFlash('error', 'Data mahasiswa gagal disimpan.');
            }
        } catch (DomainException $e) {
            $this->setFlash('error', $e->getMessage());
        } catch (Throwable $e) {
            $this->setFlash('error', 'Terjadi kesalahan. Data mahasiswa gagal disimpan.');
        }

        $this->redirect('/BkpmWebServer/acara13/public/mahasiswa');
    }

    public function edit(int $id): void
    {
        $mahasiswa = $this->service->getById($id);
        if (!$mahasiswa) {
            http_response_code(404);
            echo 'Data mahasiswa tidak ditemukan.';
            return;
        }

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa,
            'prodiList' => $this->service->getAllProdi()
        ]);
    }

    public function update(int $id): void
    {
        try {
            if ($this->service->update($id, $_POST)) {
                $this->setFlash('success', 'Data mahasiswa berhasil diperbarui.');
            } else {
                $this->setFlash('error', 'Perubahan data gagal disimpan.');
            }
        } catch (DomainException $e) {
            $this->setFlash('error', $e->getMessage());
        } catch (Throwable $e) {
            $this->setFlash('error', 'Terjadi kesalahan. Perubahan data gagal disimpan.');
        }

        $this->redirect('/BkpmWebServer/acara13/public/mahasiswa');
    }

    public function destroy(int $id): void
    {
        try {
            $this->service->delete($id);
            $this->setFlash('success', 'Data mahasiswa berhasil dihapus.');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Data mahasiswa gagal dihapus.');
        }
        $this->redirect('/BkpmWebServer/acara13/public/mahasiswa');
    }

    private function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
}
