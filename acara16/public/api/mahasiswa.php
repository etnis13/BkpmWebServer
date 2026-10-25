<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../app/Core/Database.php';

function sendJson(int $code, string $status, string $message, $data = null): void
{
    http_response_code($code);
    $response = [
        'status'  => $status,
        'message' => $message,
    ];

    if ($data !== null) {
        $response['data'] = $data;
    }

    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

function readJsonBody(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        sendJson(400, 'error', 'Body JSON tidak boleh kosong.');
    }

    $decoded = json_decode($raw, true);
    if (!is_array($decoded) || json_last_error() !== JSON_ERROR_NONE) {
        sendJson(400, 'error', 'Format JSON tidak valid.');
    }

    return $decoded;
}

function validateStudent(array $input, bool $partial = false): array
{
    $allowed = ['nim', 'nama', 'email', 'angkatan', 'prodi', 'status', 'dosen_id'];
    foreach ($input as $key => $value) {
        if (!in_array($key, $allowed, true)) {
            sendJson(400, 'error', "Field '{$key}' tidak dikenal.");
        }
    }

    if (!$partial) {
        foreach (['nim', 'nama', 'prodi'] as $required) {
            if (!isset($input[$required]) || trim((string)$input[$required]) === '') {
                sendJson(400, 'error', "Field '{$required}' wajib diisi.");
            }
        }
    } else {
        if (count($input) === 0) {
            sendJson(400, 'error', 'Minimal satu field harus dikirim untuk PATCH.');
        }
        foreach (['nim', 'nama', 'prodi'] as $required) {
            if (array_key_exists($required, $input) && trim((string)$input[$required]) === '') {
                sendJson(400, 'error', "Field '{$required}' tidak boleh kosong.");
            }
        }
    }

    if (array_key_exists('email', $input) && $input['email'] !== null && trim((string)$input['email']) !== '') {
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            sendJson(400, 'error', 'Format email tidak valid.');
        }
    } elseif (array_key_exists('email', $input)) {
        $input['email'] = null;
    }

    if (array_key_exists('status', $input) && !in_array($input['status'], ['aktif', 'cuti', 'lulus'], true)) {
        sendJson(400, 'error', 'Status harus aktif, cuti, atau lulus.');
    }

    if (array_key_exists('angkatan', $input) && $input['angkatan'] !== null && $input['angkatan'] !== '') {
        if (!filter_var($input['angkatan'], FILTER_VALIDATE_INT)) {
            sendJson(400, 'error', 'Angkatan harus berupa bilangan bulat.');
        }
        $input['angkatan'] = (int)$input['angkatan'];
    } elseif (array_key_exists('angkatan', $input)) {
        $input['angkatan'] = null;
    }

    if (array_key_exists('dosen_id', $input) && $input['dosen_id'] !== null && $input['dosen_id'] !== '') {
        if (!filter_var($input['dosen_id'], FILTER_VALIDATE_INT) || (int)$input['dosen_id'] < 1) {
            sendJson(400, 'error', 'dosen_id harus berupa bilangan bulat positif atau null.');
        }
        $input['dosen_id'] = (int)$input['dosen_id'];
    } elseif (array_key_exists('dosen_id', $input)) {
        $input['dosen_id'] = null;
    }

    return $input;
}

try {
    $db = Database::connect();
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $id = isset($_GET['id']) && $_GET['id'] !== '' ? (int)$_GET['id'] : null;

    if ($id !== null && $id < 1) {
        sendJson(400, 'error', 'ID harus berupa bilangan bulat positif.');
    }

    // GET /api/mahasiswa dan GET /api/mahasiswa/{id}
    if ($method === 'GET') {
        if ($id !== null) {
            $stmt = $db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();

            if (!$row) {
                sendJson(404, 'error', 'Data mahasiswa tidak ditemukan.');
            }
            sendJson(200, 'success', 'Data mahasiswa berhasil diambil.', $row);
        }

        $stmt = $db->query('SELECT * FROM mahasiswa ORDER BY id DESC');
        $rows = $stmt->fetchAll();
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Daftar mahasiswa berhasil diambil.',
            'total' => count($rows),
            'data' => $rows,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    // POST /api/mahasiswa
    if ($method === 'POST') {
        if ($id !== null) {
            sendJson(400, 'error', 'POST untuk menambah data gunakan endpoint /api/mahasiswa tanpa ID.');
        }
        $input = validateStudent(readJsonBody());

        $check = $db->prepare('SELECT id FROM mahasiswa WHERE nim = :nim LIMIT 1');
        $check->execute(['nim' => $input['nim']]);
        if ($check->fetch()) {
            sendJson(409, 'error', 'NIM sudah digunakan.');
        }

        $columns = ['nim', 'nama', 'prodi'];
        $values = [':nim', ':nama', ':prodi'];
        $params = [
            'nim' => trim($input['nim']),
            'nama' => trim($input['nama']),
            'prodi' => trim($input['prodi']),
        ];

        foreach (['email', 'angkatan', 'status', 'dosen_id'] as $field) {
            if (array_key_exists($field, $input)) {
                $columns[] = $field;
                $values[] = ':' . $field;
                $params[$field] = $field === 'status' ? $input[$field] : $input[$field];
            }
        }
        if (!array_key_exists('status', $input)) {
            $columns[] = 'status';
            $values[] = ':status';
            $params['status'] = 'aktif';
        }

        $sql = 'INSERT INTO mahasiswa (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ')';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        $newId = (int)$db->lastInsertId();
        $get = $db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
        $get->execute(['id' => $newId]);
        sendJson(201, 'success', 'Mahasiswa berhasil ditambahkan.', $get->fetch());
    }

    // PUT /api/mahasiswa/{id}: mengganti data yang dikirim secara lengkap
    if ($method === 'PUT') {
        if ($id === null) {
            sendJson(400, 'error', 'ID wajib disertakan untuk PUT.');
        }
        $input = validateStudent(readJsonBody());
        $find = $db->prepare('SELECT id FROM mahasiswa WHERE id = :id');
        $find->execute(['id' => $id]);
        if (!$find->fetch()) {
            sendJson(404, 'error', 'Data mahasiswa tidak ditemukan.');
        }

        $check = $db->prepare('SELECT id FROM mahasiswa WHERE nim = :nim AND id <> :id LIMIT 1');
        $check->execute(['nim' => $input['nim'], 'id' => $id]);
        if ($check->fetch()) {
            sendJson(409, 'error', 'NIM sudah digunakan mahasiswa lain.');
        }

        $sql = 'UPDATE mahasiswa SET nim=:nim, nama=:nama, email=:email, angkatan=:angkatan, prodi=:prodi, status=:status, dosen_id=:dosen_id WHERE id=:id';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'nim' => trim($input['nim']),
            'nama' => trim($input['nama']),
            'email' => $input['email'] ?? null,
            'angkatan' => $input['angkatan'] ?? null,
            'prodi' => trim($input['prodi']),
            'status' => $input['status'] ?? 'aktif',
            'dosen_id' => $input['dosen_id'] ?? null,
            'id' => $id,
        ]);

        $get = $db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
        $get->execute(['id' => $id]);
        sendJson(200, 'success', 'Data mahasiswa berhasil diperbarui.', $get->fetch());
    }

    // PATCH /api/mahasiswa/{id}: mengubah sebagian field
    if ($method === 'PATCH') {
        if ($id === null) {
            sendJson(400, 'error', 'ID wajib disertakan untuk PATCH.');
        }
        $input = validateStudent(readJsonBody(), true);
        $find = $db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
        $find->execute(['id' => $id]);
        $current = $find->fetch();
        if (!$current) {
            sendJson(404, 'error', 'Data mahasiswa tidak ditemukan.');
        }

       $merged = array_merge($current, $input);

        // Ambil hanya field yang boleh digunakan untuk validasi.
        $validated = [
            'nim'      => $merged['nim'],
            'nama'     => $merged['nama'],
            'email'    => $merged['email'] ?? null,
            'angkatan' => $merged['angkatan'] ?? null,
            'prodi'    => $merged['prodi'],
            'status'   => $merged['status'] ?? 'aktif',
            'dosen_id' => $merged['dosen_id'] ?? null,
        ];

        $merged = validateStudent($validated);
        if ($merged['nim'] !== $current['nim']) {
            $check = $db->prepare('SELECT id FROM mahasiswa WHERE nim = :nim AND id <> :id LIMIT 1');
            $check->execute(['nim' => $merged['nim'], 'id' => $id]);
            if ($check->fetch()) {
                sendJson(409, 'error', 'NIM sudah digunakan mahasiswa lain.');
            }
        }

        $stmt = $db->prepare('UPDATE mahasiswa SET nim=:nim, nama=:nama, email=:email, angkatan=:angkatan, prodi=:prodi, status=:status, dosen_id=:dosen_id WHERE id=:id');
        $stmt->execute([
            'nim' => $merged['nim'],
            'nama' => $merged['nama'],
            'email' => $merged['email'] ?? null,
            'angkatan' => $merged['angkatan'] ?? null,
            'prodi' => $merged['prodi'],
            'status' => $merged['status'] ?? 'aktif',
            'dosen_id' => $merged['dosen_id'] ?? null,
            'id' => $id,
        ]);
        $get = $db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
        $get->execute(['id' => $id]);
        sendJson(200, 'success', 'Sebagian data mahasiswa berhasil diperbarui.', $get->fetch());
    }

    // DELETE /api/mahasiswa/{id}
    if ($method === 'DELETE') {
        if ($id === null) {
            sendJson(400, 'error', 'ID wajib disertakan untuk DELETE.');
        }
        $find = $db->prepare('SELECT id FROM mahasiswa WHERE id = :id');
        $find->execute(['id' => $id]);
        if (!$find->fetch()) {
            sendJson(404, 'error', 'Data mahasiswa tidak ditemukan.');
        }
        $stmt = $db->prepare('DELETE FROM mahasiswa WHERE id = :id');
        $stmt->execute(['id' => $id]);
        sendJson(200, 'success', 'Data mahasiswa berhasil dihapus.');
    }

    header('Allow: GET, POST, PUT, PATCH, DELETE');
    sendJson(405, 'error', 'Metode HTTP tidak diizinkan untuk endpoint ini.');
} catch (PDOException $e) {
    // Detail database sengaja tidak dikirim ke client.
    sendJson(500, 'error', 'Terjadi kesalahan pada database. Periksa konfigurasi dan struktur tabel.');
} catch (Throwable $e) {
    sendJson(500, 'error', 'Terjadi kesalahan pada server.');
}
