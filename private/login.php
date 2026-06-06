<?php
require_once '../config/config.php';
session_start();

// Recupera erros de validação guardados na sessão (e limpa-os)
$validation_errors = [];
if (!empty($_SESSION['validation_errors'])) {
    $validation_errors = $_SESSION['validation_errors'];
    unset($_SESSION['validation_errors']);
}

// Recupera erro de servidor guardado na sessão (e limpa-o)
$server_error = '';
if (!empty($_SESSION['server_error'])) {
    $server_error = $_SESSION['server_error'];
    unset($_SESSION['server_error']);
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> | Login</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/assets/img/sihem2.png" type="image/png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/1240928.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/fontawesome/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>

<body class="login-body">

    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-5 col-sm-7 col-10">

                <div class="card p-4 login-card">

                    <div class="login-header">
                        <img src="<?php echo BASE_URL; ?>/assets/img/sihem1.png" class="login-logo">
                    </div>

                    <form action="index.php" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Utilizador</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="exemplo@isep.ipp.pt">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••">
                        </div>

                        <div class="mb-3 text-center">
                            <button type="submit" class="btn login-btn px-4">
                                Entrar <i class="fa-solid fa-right-to-bracket ms-2"></i>
                            </button>
                        </div>
                    </form>
                    <!-- Mensagens de erro (validação e servidor) -->
                    <?php if (!empty($validation_errors)) : ?>
                        <div class="alert alert-danger p-2 text-center mt-3">
                            <?php foreach ($validation_errors as $error) : ?>
                                <div><?= htmlspecialchars($error) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($server_error)) : ?>
                        <div class="alert alert-danger p-2 text-center mt-3">
                            <div><?= htmlspecialchars($server_error) ?></div>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mt-3">
                        <a href="../public/index.php" class="login-voltar">
                            <i class="fa-solid fa-arrow-left"></i> Voltar ao site
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>

</html>
