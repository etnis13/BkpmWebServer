<h2 class="mb-4">Tambah Mahasiswa</h2>
<form method="POST" action="/BkpmWebServer/acara14/public/mahasiswa">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
    <div class="mb-3"><label for="nim" class="form-label">NIM</label><input id="nim" type="text" name="nim" class="form-control" required maxlength="20" pattern="[0-9]+"></div>
    <div class="mb-3"><label for="nama" class="form-label">Nama</label><input id="nama" type="text" name="nama" class="form-control" required maxlength="100"></div>
    <div class="mb-3"><label for="email" class="form-label">Email</label><input id="email" type="email" name="email" class="form-control" required maxlength="150"></div>
    <div class="mb-3"><label for="angkatan" class="form-label">Angkatan</label><input id="angkatan" type="number" name="angkatan" class="form-control" min="2000" max="<?= (int)date('Y') ?>" required></div>
    <div class="mb-3"><label for="prodi" class="form-label">Program Studi</label><select id="prodi" name="prodi" class="form-select" required><option value="">-- Pilih Program Studi --</option><?php foreach ($prodiList as $p): ?><option value="<?= htmlspecialchars($p['nama'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($p['nama'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></div>
    <div class="mb-3"><label for="status" class="form-label">Status</label><select id="status" name="status" class="form-select" required><option value="aktif">Aktif</option><option value="cuti">Cuti</option><option value="lulus">Lulus</option></select></div>
    <div class="mb-3"><label for="dosen_id" class="form-label">Dosen ID (opsional)</label><input id="dosen_id" type="number" min="1" name="dosen_id" class="form-control"></div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="/BkpmWebServer/acara14/public/mahasiswa" class="btn btn-secondary">Kembali</a>
</form>
