<h2>Data Mahasiswa</h2>

<a
    href="/BkpmWebServer/acara10/public/mahasiswa/create"
    class="btn btn-primary mb-3"
>
    + Tambah Mahasiswa
</a>

<table class="table table-bordered">

    <thead class="table-dark">

        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Prodi</th>
            <th>Status</th>
            <th>Dosen ID</th>
            <th>Aksi</th>
        </tr>

    </thead>

    <tbody>

        <?php foreach ($mahasiswa as $no => $mhs): ?>

            <tr>

                <td><?= $no + 1 ?></td>

                <td>
                    <?= htmlspecialchars($mhs['nim']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($mhs['nama']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($mhs['prodi']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($mhs['status']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($mhs['dosen_id'] ?? '-') ?>
                </td>

                <td>

                    <a
                        href="/BkpmWebServer/acara10/public/mahasiswa/<?= $mhs['id'] ?>/edit"
                        class="btn btn-warning btn-sm"
                    >
                        Edit
                    </a>

                    <form
                        method="POST"
                        action="/BkpmWebServer/acara10/public/mahasiswa/<?= $mhs['id'] ?>/delete"
                        style="display:inline"
                        onsubmit="return confirm('Yakin ingin menghapus data?')"
                    >

                        <button class="btn btn-danger btn-sm">
                            Hapus
                        </button>

                    </form>

                </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>