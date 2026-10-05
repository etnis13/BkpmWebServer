<h1>Daftar Mahasiswa</h1>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Prodi</th>
            <th>Dosen Pembimbing</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        <?php if (empty($mahasiswa)): ?>
            <tr>
                <td colspan="6" class="text-center">
                    Belum ada data mahasiswa.
                </td>
            </tr>
        <?php else: ?>
            <?php $no = 1; ?>

            <?php foreach ($mahasiswa as $mhs): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($mhs['nim']) ?></td>
                    <td><?= htmlspecialchars($mhs['nama']) ?></td>
                    <td><?= htmlspecialchars($mhs['prodi']) ?></td>
                    <td><?= htmlspecialchars($mhs['nama_dosen'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($mhs['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>