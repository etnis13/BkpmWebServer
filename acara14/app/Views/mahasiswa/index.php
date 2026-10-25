<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Data Mahasiswa</h2>
    <a href="/BkpmWebServer/acara14/public/mahasiswa/create" class="btn btn-primary">+ Tambah Mahasiswa</a>
</div>
<div class="table-responsive">
<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark"><tr><th>No</th><th>NIM</th><th>Nama</th><th>Email</th><th>Angkatan</th><th>Prodi</th><th>Status</th><th>Dosen ID</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if (empty($mahasiswa)): ?>
        <tr><td colspan="9" class="text-center">Belum ada data mahasiswa.</td></tr>
    <?php else: foreach ($mahasiswa as $no => $mhs): ?>
        <tr>
            <td><?= $no + 1 ?></td>
            <td><?= htmlspecialchars((string)$mhs['nim'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string)$mhs['nama'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string)($mhs['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string)($mhs['angkatan'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string)$mhs['prodi'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string)$mhs['status'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string)($mhs['dosen_id'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
            <td class="text-nowrap">
                <a href="/BkpmWebServer/acara14/public/mahasiswa/<?= (int)$mhs['id'] ?>/edit" class="btn btn-warning btn-sm">Edit</a>
                <form method="POST" action="/BkpmWebServer/acara14/public/mahasiswa/<?= (int)$mhs['id'] ?>/delete" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
</div>
