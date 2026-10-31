# Paket Acara 21 — Dokumentasi Endpoint

Folder `acara20` adalah salinan project Acara 20 dari ZIP yang diberikan, dengan penambahan:
- `docs/API_DOCUMENTATION.md`
- Penyesuaian `expires_in` pada AuthController menjadi 300 detik agar sesuai dengan masa berlaku JWT di `Auth.php`.

Base URL lokal: http://localhost/BkpmWebServer/acara20/public

Catatan:
- Sesuaikan base URL dengan nama folder tempat project diekstrak.
- Pastikan Apache, MySQL, dan database `db_workshop` aktif.
- Endpoint buku tidak dimasukkan karena Router Acara 20 tidak mendaftarkan resource `buku`.
- Lakukan pengujian ulang di Postman sebelum mengumpulkan; contoh response dalam dokumentasi adalah ilustrasi format.
