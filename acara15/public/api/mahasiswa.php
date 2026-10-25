<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function jsonResponse(int $statusCode, array $body): void
{
    http_response_code($statusCode);
    echo json_encode(
        $body,
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    );
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if (!in_array($method, ['GET', 'POST'], true)) {
    header('Allow: GET, POST, OPTIONS');
    jsonResponse(405, [
        'status' => 'error',
        'message' => 'Method tidak diizinkan.'
    ]);
}

try {
    require_once __DIR__ . '/../../app/Core/Database.php';
    require_once __DIR__ . '/../../app/Models/BaseModel.php';
    require_once __DIR__ . '/../../app/Models/Mahasiswa.php';
    require_once __DIR__ . '/../../app/Repositories/MahasiswaRepository.php';

    $repository = new MahasiswaRepository(Database::getInstance());

    // POST: menambahkan mahasiswa dari JSON.
    if ($method === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            jsonResponse(400, [
                'status' => 'error',
                'message' => 'Format JSON tidak valid.'
            ]);
        }

        $nim = trim((string) ($data['nim'] ?? ''));
        $nama = trim((string) ($data['nama'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));

        if ($nim === '' || $nama === '' || $email === '') {
            jsonResponse(422, [
                'status' => 'error',
                'message' => 'NIM, nama, dan email wajib diisi.'
            ]);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            jsonResponse(422, [
                'status' => 'error',
                'message' => 'Format email tidak valid.'
            ]);
        }

        // Memeriksa NIM duplikat .
        if (
            method_exists($repository, 'existsByNim')
            && $repository->existsByNim($nim)
        ) {
            jsonResponse(409, [
                'status' => 'error',
                'message' => 'NIM sudah terdaftar.'
            ]);
        }

        // Menyesuaikan data dengan kolom mahasiswa pada database.
        $mahasiswaBaru = new Mahasiswa(
        $nim,
        $nama,
        $email,
        (int) date('Y'),
        'Teknik Informatika',
        'aktif',
        1
    );

        $berhasil = $repository->create($mahasiswaBaru);

        if (!$berhasil) {
            jsonResponse(500, [
                'status' => 'error',
                'message' => 'Data mahasiswa gagal ditambahkan.'
            ]);
}

        jsonResponse(201, [
            'status' => 'success',
            'message' => 'Mahasiswa berhasil ditambahkan.'
        ]);

        if ($berhasil === false) {
            jsonResponse(500, [
                'status' => 'error',
                'message' => 'Data mahasiswa gagal ditambahkan.'
            ]);
        }

        jsonResponse(201, [
            'status' => 'success',
            'message' => 'Mahasiswa berhasil ditambahkan.',
            'data' => [
                'nim' => $nim,
                'nama' => $nama,
                'email' => $email,
                'angkatan' => date('Y')
            ]
        ]);
    }

    // GET: mengambil mahasiswa berdasarkan ID.
    if (isset($_GET['id'])) {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id === false || $id === null || $id < 1) {
            jsonResponse(400, [
                'status' => 'error',
                'message' => 'Parameter id harus berupa bilangan bulat positif.'
            ]);
        }

        $mahasiswa = $repository->find($id);

        if ($mahasiswa === null) {
            jsonResponse(404, [
                'status' => 'error',
                'message' => 'Data mahasiswa tidak ditemukan.'
            ]);
        }

        jsonResponse(200, [
            'status' => 'success',
            'message' => 'Data mahasiswa berhasil diambil.',
            'data' => $mahasiswa
        ]);
    }

    // GET: mengambil semua mahasiswa.
    $mahasiswa = $repository->all();

    jsonResponse(200, [
        'status' => 'success',
        'message' => 'Daftar mahasiswa berhasil diambil.',
        'total' => count($mahasiswa),
        'data' => $mahasiswa
    ]);
} catch (Throwable $e) {
    error_log('[Acara15 API] ' . $e->getMessage());

    jsonResponse(500, [
        'status' => 'error',
        'message' => 'Terjadi kesalahan pada server.'
    ]);
}