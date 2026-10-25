<h2>Edit Mahasiswa</h2>

<form method="POST" action="/BkpmWebServer/acara13/public/mahasiswa/<?= (int) $mahasiswa['id'] ?>">
    <div class="mb-3">
        <label for="nim" class="form-label">NIM</label>
        <input id="nim" type="text" name="nim" class="form-control" value="<?= htmlspecialchars($mahasiswa['nim']) ?>" required pattern="[0-9]+">
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input id="nama" type="text" name="nama" class="form-control" value="<?= htmlspecialchars($mahasiswa['nama']) ?>" required maxlength="100">
    </div>
    <div class="mb-3">
        <label for="prodi" class="form-label">Program Studi</label>
        <select id="prodi" name="prodi" class="form-select" required>
            <option value="">-- Pilih Program Studi --</option>
            <?php foreach ($prodiList as $p): ?>
                <option value="<?= htmlspecialchars($p['nama']) ?>" <?= $mahasiswa['prodi'] === $p['nama'] ? 'selected' : '' ?>><?= htmlspecialchars($p['nama']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select" required>
            <?php foreach (['aktif' => 'Aktif', 'cuti' => 'Cuti', 'lulus' => 'Lulus'] as $value => $label): ?>
                <option value="<?= $value ?>" <?= $mahasiswa['status'] === $value ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="dosen_id" class="form-label">Dosen ID (opsional)</label>
        <input id="dosen_id" type="number" min="1" name="dosen_id" class="form-control" value="<?= htmlspecialchars((string) ($mahasiswa['dosen_id'] ?? '')) ?>">
    </div>
    <button type="submit" class="btn btn-success">Update</button>
    <a href="/BkpmWebServer/acara13/public/mahasiswa" class="btn btn-secondary">Kembali</a>
</form>
