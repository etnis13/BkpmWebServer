<?php 
require_once __DIR__ . '/../app/Models/Mahasiswa.php';
$mhs1 = new Mahasiswa(
    "2401001",
    "Budi Santoso",
    "Teknik Informatika"
);
$mhs2 = new Mahasiswa(
    "2301002",
    "Andi",
    "Teknik Informatika"
);
$mahasiswa = [$mhs1, $mhs2];
$content = __DIR__ . '/../app/Views/mahasiswa/index.php';
require __DIR__ . '/../app/Views/layouts/main.php';