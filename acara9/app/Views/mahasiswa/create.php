<h2>Tambah Mahasiswa</h2>

<form
    method="POST"
    action="/BkpmWebServer/acara9/public/mahasiswa"
>

    <div class="mb-3">

        <label>NIM</label>

        <input
            type="text"
            name="nim"
            class="form-control"
            required
        >

    </div>

    <div class="mb-3">

        <label>Nama</label>

        <input
            type="text"
            name="nama"
            class="form-control"
            required
        >

    </div>

    <div class="mb-3">

        <label>Prodi</label>

        <input
            type="text"
            name="prodi"
            class="form-control"
            required
        >

    </div>

    <div class="mb-3">

        <label>Status</label>

        <select name="status" class="form-select">

            <option value="aktif">
                Aktif
            </option>

            <option value="cuti">
                Cuti
            </option>

            <option value="lulus">
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
        >

    </div>

    <button class="btn btn-primary">
        Simpan
    </button>

    <a
        href="/BkpmWebServer/acara9/public/mahasiswa"
        class="btn btn-secondary"
    >
        Kembali
    </a>

</form>