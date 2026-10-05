<h2>Edit Mahasiswa</h2>

<form
    method="POST"
    action="/BkpmWebServer/acara8/public/mahasiswa/<?= $mahasiswa['id'] ?>"
>

    <div class="mb-3">
        <label>NIM</label>

        <input
            type="text"
            name="nim"
            class="form-control"
            value="<?= htmlspecialchars($mahasiswa['nim']) ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label>Nama</label>

        <input
            type="text"
            name="nama"
            class="form-control"
            value="<?= htmlspecialchars($mahasiswa['nama']) ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label>Prodi</label>

        <input
            type="text"
            name="prodi"
            class="form-control"
            value="<?= htmlspecialchars($mahasiswa['prodi']) ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label>Status</label>

        <select name="status" class="form-select">

            <option
                value="aktif"
                <?= $mahasiswa['status'] == 'aktif' ? 'selected' : '' ?>
            >
                Aktif
            </option>

            <option
                value="cuti"
                <?= $mahasiswa['status'] == 'cuti' ? 'selected' : '' ?>
            >
                Cuti
            </option>

            <option
                value="lulus"
                <?= $mahasiswa['status'] == 'lulus' ? 'selected' : '' ?>
            >
                Lulus
            </option>

        </select>
    </div>

    <div class="mb-3">
        <label>Dosen ID</label>

        <input
            type="number"
            name="dosen_id"
            class="form-control"
            value="<?= htmlspecialchars($mahasiswa['dosen_id'] ?? '') ?>"
        >
    </div>

    <button class="btn btn-success">
        Update
    </button>

    <a
        href="/BkpmWebServer/acara8/public/mahasiswa"
        class="btn btn-secondary"
    >
        Kembali
    </a>

</form>