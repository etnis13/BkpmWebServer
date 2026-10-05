<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SI Akademik</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow">
                <div class="card-body">

                    <h3 class="text-center mb-4">
                        Login Si Akademik
                    </h3>

                    <?php if (!empty($_SESSION['flash'])): ?>

                        <div class="alert alert-info">
                            <?= htmlspecialchars($_SESSION['flash']) ?>
                        </div>

                        <?php unset($_SESSION['flash']); ?>

                    <?php endif; ?>

                    <form
                        method="POST"
                        action="/BkpmWebServer/acara6/public/login"
                    >

                        <div class="mb-3">
                            <label class="form-label">
                                Username
                            </label>

                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required
                            >
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Login
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
