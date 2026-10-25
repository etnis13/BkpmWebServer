<?php
class MahasiswaController {
    private Mahasiswa $model;

    public function __construct() {
        $this->model = new Mahasiswa();
    }

    private function respond(int $code, array $payload): void {
        http_response_code($code);
        echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function readJson(): array {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        if (!is_array($data) || json_last_error() !== JSON_ERROR_NONE) {
            $this->respond(400, ['success'=>false,'message'=>'Format JSON tidak valid']);
        }
        return $data;
    }

    private function validate(array $data): void {
        foreach (['nim', 'nama', 'prodi'] as $field) {
            if (!isset($data[$field]) || trim((string)$data[$field]) === '') {
                $this->respond(400, [
                    'success' => false,
                    'message' => "Field {$field} wajib diisi"
                ]);
            }
        }
    }

    public function index(): void {
        $id = $_GET['id'] ?? null;
        if ($id !== null && $id !== '') {
            $data = $this->model->find((int)$id);
            if (!$data) $this->respond(404, ['success'=>false,'message'=>'Data tidak ditemukan']);
            $this->respond(200, ['success'=>true,'data'=>$data]);
        }
        $this->respond(200, ['success'=>true,'data'=>$this->model->getAll()]);
    }

    public function store(): void {
        $data = $this->readJson();
        $this->validate($data);
        try {
            $id = $this->model->create($data);
            $this->respond(201, [
                'success'=>true,
                'message'=>'Data berhasil ditambahkan',
                'data'=>['id'=>$id]
            ]);
        } catch (DomainException $e) {
            $this->respond(409, ['success'=>false,'message'=>$e->getMessage()]);
        }
    }

    public function update(int $id): void {
        $data = $this->readJson();
        $this->validate($data);
        try {
            if (!$this->model->update($id, $data)) {
                $this->respond(404, ['success'=>false,'message'=>'Data tidak ditemukan']);
            }
            $this->respond(200, ['success'=>true,'message'=>'Data berhasil diperbarui']);
        } catch (DomainException $e) {
            $this->respond(409, ['success'=>false,'message'=>$e->getMessage()]);
        }
    }

    public function destroy(int $id): void {
        if (!$this->model->delete($id)) {
            $this->respond(404, ['success'=>false,'message'=>'Data tidak ditemukan']);
        }
        $this->respond(200, ['success'=>true,'message'=>'Data berhasil dihapus']);
    }
}
