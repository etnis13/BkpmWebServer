<h2>Data Mahasiswa</h2>

<a
    href="/BkpmWebServer/acara8/public/mahasiswa/create"
    class="btn btn-primary mb-3"
>
    + Tambah Mahasiswa
</a>

<form
    method="GET"
    action="/BkpmWebServer/acara8/public/mahasiswa"
    class="mb-3"
>

    <div class="input-group">

        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Cari nama atau NIM..."
            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
        >

        <button class="btn btn-secondary">
            Cari
        </button>

    </div>

</form>

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

    <?php if (empty($mahasiswa)): ?>

        <tr>
            <td colspan="7" class="text-center">
                Data tidak ditemukan.
            </td>
        </tr>

    <?php else: ?>

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
                        href="/BkpmWebServer/acara8/public/mahasiswa/<?= $mhs['id'] ?>/edit"
                        class="btn btn-warning btn-sm"
                    >
                        Edit
                    </a>

                    <form
                        method="POST"
                        action="/BkpmWebServer/acara8/public/mahasiswa/<?= $mhs['id'] ?>/delete"
                        style="display:inline"
                        onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                    >

                        <button
                            type="submit"
                            class="btn btn-danger btn-sm"
                        >
                            Hapus
                        </button>

                    </form>

                </td>

            </tr>

        <?php endforeach; ?>

    <?php endif; ?>

    </tbody>

</table>