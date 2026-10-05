<h2 class="mb-4">Daftar Mahasiswa</h2>
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Prodi</th>
            <th>Angkatan</th>
        </tr>
    </thead>
    <tbody> 
        <?php foreach ($mahasiswa as $mhs): ?>
            <tr>
                <td><?= $mhs->getNim(); ?></td>
                <td><?= $mhs->getNama(); ?></td>
                <td><?= $mhs->getProdi(); ?></td>
                <td><?= $mhs->getAngkatan(); ?></td>
            </tr> 
            <?php endforeach; ?>
    </tbody>
</table>