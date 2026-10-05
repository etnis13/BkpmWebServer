<?php

require_once __DIR__ . '/../Models/Mahasiswa.php';

class MahasiswaController
{
    // READ + SEARCH
    public function index()
    {
        $model = new Mahasiswa();

        $keyword = $_GET['search'] ?? '';

        if ($keyword !== '') {
            $mahasiswa = $model->search($keyword);
        } else {
            $mahasiswa = $model->all();
        }

        $content = __DIR__ . '/../Views/mahasiswa/index.php';

        require __DIR__ . '/../Views/layouts/main.php';
    }

    // FORM CREATE
    public function create()
    {
        $content = __DIR__ . '/../Views/mahasiswa/create.php';

        require __DIR__ . '/../Views/layouts/main.php';
    }

    // CREATE
    public function store()
    {
        $model = new Mahasiswa();

        $model->create([
            'nim' => trim($_POST['nim']),
            'nama' => trim($_POST['nama']),
            'prodi' => trim($_POST['prodi']),
            'status' => $_POST['status'],
            'dosen_id' => !empty($_POST['dosen_id'])
                ? $_POST['dosen_id']
                : null
        ]);

        header('Location: /BkpmWebServer/acara8/public/mahasiswa');
        exit;
    }

    // FORM EDIT
    public function edit($id)
    {
        $model = new Mahasiswa();

        $mahasiswa = $model->find($id);

        if (!$mahasiswa) {
            http_response_code(404);
            echo "Data mahasiswa tidak ditemukan.";
            return;
        }

        $content = __DIR__ . '/../Views/mahasiswa/edit.php';

        require __DIR__ . '/../Views/layouts/main.php';
    }

    // UPDATE
    public function update($id)
    {
        $model = new Mahasiswa();

        $model->update($id, [
            'nim' => trim($_POST['nim']),
            'nama' => trim($_POST['nama']),
            'prodi' => trim($_POST['prodi']),
            'status' => $_POST['status'],
            'dosen_id' => !empty($_POST['dosen_id'])
                ? $_POST['dosen_id']
                : null
        ]);

        header('Location: /BkpmWebServer/acara8/public/mahasiswa');
        exit;
    }

    // DELETE
    public function destroy($id)
    {
        $model = new Mahasiswa();

        $model->delete($id);

        header('Location: /BkpmWebServer/acara8/public/mahasiswa');
        exit;
    }
}