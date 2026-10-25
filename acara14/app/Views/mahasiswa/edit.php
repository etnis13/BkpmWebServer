<h2 class="mb-4">Edit Mahasiswa</h2>
<form method="POST" action="/BkpmWebServer/acara14/public/mahasiswa/<?= (int)$mahasiswa['id'] ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
    <div class="mb-3"><label for="nim" class="form-label">NIM</label><input id="nim" type="text" name="nim" class="form-control" value="<?= htmlspecialchars((string)$mahasiswa['nim'], ENT_QUOTES, 'UTF-8') ?>" required maxlength="20" pattern="[0-9]+"></div>
    <div class="mb-3"><label for="nama" class="form-label">Nama</label><input id="nama" type="text" name="nama" class="form-control" value="<?= htmlspecialchars((string)$mahasiswa['nama'], ENT_QUOTES, 'UTF-8') ?>" required maxlength="100"></div>
    <div class="mb-3"><label for="email" class="form-label">Email</label><input id="email" type="email" name="email" class="form-control" value="<?= htmlspecialchars((string)($mahasiswa['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required maxlength="150"></div>
    <div class="mb-3"><label for="angkatan" class="form-label">Angkatan</label><input id="angkatan" type="number" name="angkatan" class="form-control" value="<?= htmlspecialchars((string)($mahasiswa['angkatan'] ?? date('Y')), ENT_QUOTES, 'UTF-8') ?>" min="2000" max="<?= (int)date('Y') ?>" required></div>
    <div class="mb-3"><label for="prodi" class="form-label">Program Studi</label><select id="prodi" name="prodi" class="form-select" required><option value="">-- Pilih Program Studi --</option><?php foreach ($prodiList as $p): ?><option value="<?= htmlspecialchars($p['nama'], ENT_QUOTES, 'UTF-8') ?>" <?= $mahasiswa['prodi'] === $p['nama'] ? 'selected' : '' ?>><?= htmlspecialchars($p['nama'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></div>
    <div class="mb-3"><label for="status" class="form-label">Status</label><select id="status" name="status" class="form-select" required><?php foreach (['aktif'=>'Aktif','cuti'=>'Cuti','lulus'=>'Lulus'] as $value=>$label): ?><option value="<?= $value ?>" <?= $mahasiswa['status'] === $value ? 'selected' : '' ?>><?= $label ?></option><?php endforeach; ?></select></div>
    <div class="mb-3"><label for="dosen_id" class="form-label">Dosen ID (opsional)</label><input id="dosen_id" type="number" min="1" name="dosen_id" class="form-control" value="<?= htmlspecialchars((string)($mahasiswa['dosen_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></div>
    <button type="submit" class="btn btn-success">Update</button>
    <a href="/BkpmWebServer/acara14/public/mahasiswa" class="btn btn-secondary">Kembali</a>
</form>
