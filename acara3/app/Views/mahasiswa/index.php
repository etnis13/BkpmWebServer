<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">

        <h1 class="mb-4">Daftar Mahasiswa</h1>

        <a href="/mahasiswa/create" class="btn btn-primary mb-3">
            Tambah Mahasiswa
        </a>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Prodi</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>23001</td>
                    <td>Andi</td>
                    <td>Teknik Informatika</td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">Detail</a>
                        <a href="#" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>

                <tr>
                    <td>23002</td>
                    <td>Budi</td>
                    <td>Teknik Informatika</td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">Detail</a>
                        <a href="#" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>

                <tr>
                    <td>23003</td>
                    <td>Citra</td>
                    <td>Teknik Informatika</td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">Detail</a>
                        <a href="#" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</body>
</html>