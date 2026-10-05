<?php

require_once __DIR__ . '/../Models/Mahasiswa.php';
class MahasiswaController
{
    public function index()
    {
        $model = new Mahasiswa();
        $mahasiswa = $model->all();
        $content = __DIR__ . '/../Views/mahasiswa/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function create()
    {
        echo '<h1>Tambah Mahasiswa</h1>';
    }

    public function show($id)
    {
        echo '<h1>Detail Mahasiswa</h1>';
        echo '<p>ID Mahasiswa: ' . htmlspecialchars($id) . '</p>';
    }
}
