<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form GET dan POST</title>
</head>
<body>
    <h1>Selamat Datang di SI Akademik</h1>
    <h2>Form GET-Cari Mahasiswa</h2>

    <form action="proses.php" method="GET">
        <label>Cari Mahasiswa:</label>
        <input type="text" name="keyword">
        <button type="submit">Cari</button>
    </form>

    <h2>Form POST-Login</h2>
    <form action="login.php" method="POST">
        <label>Username:</label>
        <input type="text" name="username">
        <label>Password:</label>
        <input type="password" name="password">
        <button type="submit">Login</button>
    </form>
</body>
</html>