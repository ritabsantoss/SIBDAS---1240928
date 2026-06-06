<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../includes/funcoes.php';
redirect_if_not_logged();
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> | Alterar Password</title>

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

                    <form action="#" method="post">

                        <div class="mb-3">
                            <label for="password_atual" class="form-label">
                                Password atual
                            </label>
                            <input type="password"
                                   id="password_atual"
                                   name="password_atual"
                                   class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="nova_password" class="form-label">
                                Nova password
                            </label>
                            <input type="password"
                                   id="nova_password"
                                   name="nova_password"
                                   class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="confirmar_password" class="form-label">
                                Confirmar nova password
                            </label>
                            <input type="password"
                                   id="confirmar_password"
                                   name="confirmar_password"
                                   class="form-control">
                        </div>

                        <div class="mb-3 text-center">
                            <button type="submit" class="btn login-btn px-4">
                                Guardar
                                <i class="fa-regular fa-floppy-disk ms-2"></i>
                            </button>
                        </div>

                    </form>

                    <div class="text-center mt-3">
                        <a href="../index.php" class="login-voltar">
                            <i class="fa-solid fa-arrow-left"></i>
                            Voltar à área privada
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>