
<?php

class BukuController
{
    private Buku $model;

    public function __construct()
    {
        $this->model = new Buku();
    }

    public function index(): void
    {
        try {
            $data = $this->model->getAll();

            echo json_encode([
                'success' => true,
                'data' => $data
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => 'Gagal mengambil data buku'
            ]);
        }
    }

    public function store(): void
    {
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (
            !is_array($data) ||
            empty($data['judul']) ||
            empty($data['penulis'])
        ) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Judul dan penulis wajib diisi'
            ]);
            return;
        }

        try {
            $this->model->create($data);

            http_response_code(201);

            echo json_encode([
                'success' => true,
                'message' => 'Data buku berhasil ditambahkan'
            ]);
        } catch (PDOException $e) {
            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => 'Gagal menambahkan data buku'
            ]);
        }
    }
}
