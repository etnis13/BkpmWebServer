
const BASE_URL = 'http://localhost/BkpmWebServer/acara25/public';

const token = localStorage.getItem('token');
const role = localStorage.getItem('role');

const tabel = document.getElementById('tabel-mahasiswa');
const totalMahasiswa = document.getElementById('total-mahasiswa');
const infoUser = document.getElementById('info-user');
const tombolLogout = document.getElementById('btn-logout');
const aksiAdmin = document.getElementById('aksi-admin');
const tombolTambah = document.getElementById('btn-tambah');

if (!token) {
    window.location.href = 'login.html';
    throw new Error('Pengguna belum login');
}

infoUser.textContent = `Login sebagai: ${role}`;

// Sembunyikan fitur admin jika role user
if (role !== 'admin') {
    aksiAdmin.style.display = 'none';
}

async function muatDataMahasiswa() {
    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const hasil = await response.json();

        if (!response.ok || !hasil.success) {
            tabel.innerHTML = '<tr><td colspan="4">Gagal memuat data</td></tr>';
            return;
        }

        const data = hasil.data;

        totalMahasiswa.textContent = data.length;
        tabel.innerHTML = '';

        if (data.length === 0) {
            tabel.innerHTML = '<tr><td colspan="4">Belum ada data mahasiswa</td></tr>';
            return;
        }

        data.forEach((mhs) => {
            const aksi = role === 'admin'
                ? `
                    <button onclick="editMahasiswa(${mhs.id}, '${mhs.nim}', '${mhs.nama}', '${mhs.prodi}')">Edit</button>
                    <button onclick="hapusMahasiswa(${mhs.id})">Hapus</button>
                  `
                : 'Tidak ada aksi';

            tabel.innerHTML += `
                <tr>
                    <td>${mhs.nim}</td>
                    <td>${mhs.nama}</td>
                    <td>${mhs.prodi}</td>
                    <td>${aksi}</td>
                </tr>
            `;
        });

    } catch (error) {
        tabel.innerHTML = '<tr><td colspan="4">Gagal terhubung ke server</td></tr>';
        console.error(error);
    }
}

// Tambah mahasiswa
async function tambahMahasiswa() {
    const nim = prompt('Masukkan NIM:');
    if (nim === null) return;

    const nama = prompt('Masukkan Nama:');
    if (nama === null) return;

    const prodi = prompt('Masukkan Program Studi:');
    if (prodi === null) return;

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ nim, nama, prodi })
        });

        const hasil = await response.json();
        alert(hasil.message);

        if (response.ok && hasil.success) {
            muatDataMahasiswa();
        }
    } catch (error) {
        alert('Gagal menambahkan mahasiswa');
    }
}

// Edit mahasiswa
async function editMahasiswa(id, nimLama, namaLama, prodiLama) {
    const nim = prompt('Masukkan NIM baru:', nimLama);
    if (nim === null) return;

    const nama = prompt('Masukkan Nama baru:', namaLama);
    if (nama === null) return;

    const prodi = prompt('Masukkan Program Studi baru:', prodiLama);
    if (prodi === null) return;

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa?id=${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ nim, nama, prodi })
        });

        const hasil = await response.json();
        alert(hasil.message);

        if (response.ok && hasil.success) {
            muatDataMahasiswa();
        }
    } catch (error) {
        alert('Gagal mengubah mahasiswa');
    }
}

// Hapus mahasiswa
async function hapusMahasiswa(id) {
    if (!confirm('Yakin ingin menghapus data mahasiswa ini?')) return;

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa?id=${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const hasil = await response.json();
        alert(hasil.message);

        if (response.ok && hasil.success) {
            muatDataMahasiswa();
        }
    } catch (error) {
        alert('Gagal menghapus mahasiswa');
    }
}

// Tombol tambah
if (role === 'admin') {
    tombolTambah.addEventListener('click', tambahMahasiswa);
}

// Logout
tombolLogout.addEventListener('click', function() {
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    window.location.href = 'login.html';
});

// Tampilkan data
muatDataMahasiswa();
