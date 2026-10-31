# Dokumentasi API — Sistem Informasi Akademik

**Versi:** Acara 21  
**Teknologi:** PHP Native, PDO, MySQL, REST API, JSON, JWT  
**Base URL Lokal:** `http://localhost/BkpmWebServer/acara21/public`

## 1. Pendahuluan

Dokumentasi ini disusun untuk memenuhi kegiatan praktikum Acara 21 pada mata kuliah Workshop Web Server. Dokumen ini menjelaskan endpoint REST API pada project Sistem Informasi Akademik, meliputi autentikasi pengguna dan pengelolaan data mahasiswa.

Dokumentasi mengacu pada struktur project Acara 21, khususnya `Router`, `AuthController`, `MahasiswaController`, dan `Auth`. Nama endpoint dan contoh request di bawah perlu dicocokkan kembali dengan implementasi aktual apabila terdapat perbedaan pada kode project.

## 2. Format Response

API menggunakan JSON. Struktur umum response:
{
  "success": true,
  "message": "Pesan response",
  "data": {},
  "timestamp": "2026-10-31T10:00:00+07:00"
}


| Field | Tipe | Keterangan |
|---|---|---|
| `success` | Boolean | Status keberhasilan request |
| `message` | String | Pesan hasil proses |
| `data` | Object/Array/Null | Data yang dikembalikan API |
| `timestamp` | String | Waktu response |

## 3. Ringkasan Endpoint

| No. | Method | Endpoint | Controller/Method | Fungsi | Auth |
|---|---|---|---|---|---|
| 1 | POST | `/register` | `AuthController::register()` | Registrasi pengguna | Tidak |
| 2 | POST | `/login` | `AuthController::login()` | Login pengguna dan memperoleh JWT | Tidak |
| 3 | GET | `/mahasiswa` | `MahasiswaController::index()` | Menampilkan seluruh mahasiswa | Tidak |
| 4 | GET | `/mahasiswa?id={id}` | `MahasiswaController::index()` | Menampilkan mahasiswa berdasarkan ID | Tidak |
| 5 | POST | `/mahasiswa` | `MahasiswaController::store()` | Menambahkan mahasiswa | JWT |
| 6 | PUT | `/mahasiswa?id={id}` | `MahasiswaController::update()` | Mengubah data mahasiswa | JWT |
| 7 | DELETE | `/mahasiswa?id={id}` | `MahasiswaController::destroy()` | Menghapus data mahasiswa | JWT |

> Catatan: Tabel ini mengikuti pemetaan endpoint project yang menjadi dasar pengerjaan. Endpoint yang tidak terdaftar pada Router Acara 21 tidak dicantumkan.

## 4. Autentikasi

### 4.1 Register

- **Method:** `POST`
- **URL:** `/register`
- **Controller:** `AuthController::register()`
- **Autentikasi:** Tidak diperlukan.

Endpoint ini digunakan untuk mendaftarkan akun pengguna.

**Contoh request body** (sesuaikan nama field dengan validasi pada `AuthController`):
{
  "username": "etnis21",
  "email": "etnis@example.com",
  "password": "password123"
}

**Contoh response ilustratif:**
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {},
  "timestamp": "2026-10-31T10:00:00+07:00"
}
```

### 4.2 Login

- **Method:** `POST`
- **URL:** `/login`
- **Controller:** `AuthController::login()`
- **Autentikasi:** Tidak diperlukan.

Endpoint ini memeriksa kredensial pengguna. Jika berhasil, API mengembalikan token JWT.

**Contoh request body:**
{
  "username": "etnis21",
  "password": "password123"
}


**Contoh response ilustratif:**
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "token": "TOKEN_JWT",
    "token_type": "Bearer",
    "expires_in": 300
  },
  "timestamp": "2026-10-31T10:00:00+07:00"
}


Token pada contoh di atas hanya placeholder. Gunakan token asli yang diperoleh dari response login saat melakukan pengujian. Berdasarkan konfigurasi `Auth` yang menjadi acuan, masa berlaku token adalah 300 detik (5 menit).

## 5. Pengelolaan Data Mahasiswa

Untuk endpoint yang membutuhkan autentikasi, kirim token melalui header:

Authorization: Bearer TOKEN_JWT
Content-Type: application/json


### 5.1 Menampilkan Seluruh Mahasiswa

- **Method:** `GET`
- **URL:** `/mahasiswa`
- **Controller:** `MahasiswaController::index()`
- **Autentikasi:** Tidak diperlukan.

Endpoint ini digunakan untuk mengambil daftar mahasiswa.

**Request body:** Tidak diperlukan.

**Contoh response ilustratif:**


{
  "success": true,
  "message": "Data mahasiswa berhasil diambil",
  "data": [
    {
      "id": 1,
      "nim": "2201001",
      "nama": "Andi Saputra",
      "prodi": "Teknik Informatika"
    }
  ],
  "timestamp": "2026-10-31T10:00:00+07:00"
}
```

### 5.2 Menampilkan Mahasiswa Berdasarkan ID

- **Method:** `GET`
- **URL:** `/mahasiswa?id={id}`
- **Controller:** `MahasiswaController::index()`
- **Autentikasi:** Tidak diperlukan.

Ganti `{id}` dengan ID mahasiswa yang ingin ditampilkan.

**Contoh request:**
GET /mahasiswa?id=1


**Request body:** Tidak diperlukan.

### 5.3 Menambahkan Mahasiswa

- **Method:** `POST`
- **URL:** `/mahasiswa`
- **Controller:** `MahasiswaController::store()`
- **Autentikasi:** JWT wajib.

**Contoh request body:**
{
  "nim": "230010001",
  "nama": "Dewi Anggraini",
  "prodi": "Teknik Informatika"
}

| Field | Tipe | Keterangan |
|---|---|---|
| `nim` | String | Nomor induk mahasiswa |
| `nama` | String | Nama mahasiswa |
| `prodi` | String | Program studi |

### 5.4 Mengubah Data Mahasiswa

- **Method:** `PUT`
- **URL:** `/mahasiswa?id={id}`
- **Controller:** `MahasiswaController::update()`
- **Autentikasi:** JWT wajib.

**Contoh request:**


PUT /mahasiswa?id=1


**Contoh request body:**
{
  "nim": "230010001",
  "nama": "Dewi Anggraini Putri",
  "prodi": "Teknik Informatika"
}


### 5.5 Menghapus Mahasiswa

- **Method:** `DELETE`
- **URL:** `/mahasiswa?id={id}`
- **Controller:** `MahasiswaController::destroy()`
- **Autentikasi:** JWT wajib.

**Contoh request:**

DELETE /mahasiswa?id=1


Request body tidak diperlukan. Ganti `1` dengan ID mahasiswa yang akan dihapus.

## 6. Penanganan Kesalahan

Berikut beberapa kondisi yang perlu diperiksa ketika menguji API:

| Kondisi | Keterangan |
|---|---|
| JSON tidak valid | Format request body tidak dapat dibaca |
| Field wajib kosong | Data yang diperlukan belum dikirim |
| NIM tidak sesuai format | NIM tidak memenuhi aturan validasi |
| NIM duplikat | NIM sudah digunakan |
| Token tidak dikirim | Header Authorization tidak tersedia |
| Token tidak valid | Token gagal diverifikasi |
| Token kedaluwarsa | Masa berlaku token telah habis |
| Data tidak ditemukan | ID mahasiswa tidak tersedia |

Response aktual untuk setiap kondisi harus diambil dari hasil pengujian Postman. Jangan menganggap contoh pesan atau kode status yang belum diuji sebagai hasil pengujian sebenarnya.

## 7. Pengujian Menggunakan Postman

Pengujian dilakukan dengan langkah berikut:

1. Jalankan Apache dan MySQL melalui XAMPP.
2. Pastikan project dapat diakses melalui Base URL Acara 21.
3. Buat atau buka Collection Postman untuk project.
4. Uji `POST /register` untuk membuat akun.
5. Uji `POST /login` dan salin token JWT dari response.
6. Uji `GET /mahasiswa` dan `GET /mahasiswa?id={id}`.
7. Tambahkan token Bearer pada request POST, PUT, dan DELETE mahasiswa.
8. Uji penambahan, perubahan, dan penghapusan data mahasiswa.
9. Simpan response berhasil dan gagal pada bagian **Examples** di setiap request.
10. Isi **Description** setiap request dengan controller dan method yang menanganinya.
11. Buka **View Documentation** untuk melihat dokumentasi Collection.

## 8. Kesimpulan

API Sistem Informasi Akademik menyediakan endpoint untuk registrasi, login, serta pengelolaan data mahasiswa. Endpoint GET digunakan untuk membaca data, sedangkan POST, PUT, dan DELETE digunakan untuk menambah, mengubah, dan menghapus data. JWT diterapkan sebagai autentikasi pada operasi penambahan, perubahan, dan penghapusan data mahasiswa.

Dokumentasi ini membantu menjelaskan fungsi endpoint, kebutuhan autentikasi, format request, serta tahapan pengujian API menggunakan Postman.
