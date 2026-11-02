
const BASE_URL = 'http://localhost/BkpmWebServer/acara24/public';

async function muatDataMahasiswa() {
    const tbody = document.querySelector('#tabel-mahasiswa tbody');

    try {
        // Menampilkan indikator loading
        tbody.innerHTML = `
            <tr>
                <td colspan="3">Memuat data...</td>
            </tr>
        `;

        const response = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await response.json();

        tbody.innerHTML = '';

        if (!hasil.success || hasil.data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="3">Belum ada data</td>
                </tr>
            `;
            return;
        }

        hasil.data.forEach((mhs) => {
            tbody.innerHTML += `
                <tr>
                    <td>${mhs.nim}</td>
                    <td>${mhs.nama}</td>
                    <td>${mhs.prodi}</td>
                </tr>
            `;
        });

    } catch (error) {
        tbody.innerHTML = `
            <tr>
                <td colspan="3">Gagal memuat data</td>
            </tr>
        `;
        console.error('Gagal memuat data:', error);
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);
