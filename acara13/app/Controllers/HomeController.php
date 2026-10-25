<?php

class HomeController
{
    public function index()
    {
        echo '<h1>Selamat Datang di SI Akademik</h1>';
        echo '<p>Ini adalah halaman utama.</p>';
    }

    public function dashboard()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        echo '<h1>Dashboard</h1>';

        // Menampilkan flash message setelah login
        if (!empty($_SESSION['flash'])) {
            echo "<div style='padding:10px; background:#d1e7dd; margin-bottom:15px;'>";
            echo htmlspecialchars($_SESSION['flash']);
            echo '</div>';

            unset($_SESSION['flash']);
        }

        echo "<a href='/BkpmWebServer/acara13/public/mahasiswa'>";
        echo 'Daftar Mahasiswa';
        echo '</a>';

        echo '<br><br>';

        echo "<a href='/BkpmWebServer/acara13/public/logout'>";
        echo 'Logout';
        echo '</a>';
    }
}