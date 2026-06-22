<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../private/includes/funcoes.php';

$conteudos = [];
try {
    $ligacao = liga_bd();
    $stmt = $ligacao->query("SELECT chave, valor FROM Conteudos ORDER BY ordem");
    foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $row) {
        $conteudos[$row->chave] = $row->valor;
    }
    $ligacao = null;
} catch (PDOException $err) {
    // falha silenciosa — página continua com valores vazios
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>

    <!-- favicon -->
    <link rel="shortcut icon" href="../assets/img/sihem2.png" type="image/png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/1240928.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/fontawesome/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

</head>

<body>
    <!-- Navegação -->
    <nav class="navbar">

        <!-- Logotipo -->
        <div class="logo-container">
            <img src="../assets/img/sihem1.png" alt="Logo SIHEM">
        </div>

        <!-- Links -->
        <div class="nav-links">
            <a href="#servicos">Serviços</a>
            <a href="#faq">FAQ</a>
            <a href="#contactos">Contactos</a>
        </div>

        <!-- Área Reservada-->
        <div class="nav-login">
            <a href="../private/login.php">
                Área Privada
            </a>
        </div>

    </nav>

    <!-- Hero Section-->
    <section class="hero">

        <div class="hero-text">
            <h1>
                <?= conteudo($conteudos, 'hero_titulo') ?>
            </h1>

            <p>
                <?= conteudo($conteudos, 'hero_subtitulo') ?>
            </p>

            <a href="<?= conteudo($conteudos, 'hero_link') ?>" class="hero-button">
                <?= conteudo($conteudos, 'hero_botao') ?>
            </a>
        </div>

        <div class="hero-image">
            <img src="../assets/img/<?= conteudo($conteudos, 'hero_imagem') ?>" alt="Hospital">
        </div>

    </section>

    <!-- Estatísticas -->
    <section class="estatisticas">

        <div class="estatistica-card">
            <i class="<?= conteudo($conteudos, 'estat1_icone') ?>"></i>
            <h2><?= conteudo($conteudos, 'estat1_valor') ?></h2>
            <p><?= conteudo($conteudos, 'estat1_label') ?></p>
        </div>

        <div class="estatistica-card">
            <i class="<?= conteudo($conteudos, 'estat2_icone') ?>"></i>
            <h2><?= conteudo($conteudos, 'estat2_valor') ?></h2>
            <p><?= conteudo($conteudos, 'estat2_label') ?></p>
        </div>

        <div class="estatistica-card">
            <i class="<?= conteudo($conteudos, 'estat3_icone') ?>"></i>
            <h2><?= conteudo($conteudos, 'estat3_valor') ?></h2>
            <p><?= conteudo($conteudos, 'estat3_label') ?></p>
        </div>

        <div class="estatistica-card">
            <i class="<?= conteudo($conteudos, 'estat4_icone') ?>"></i>
            <h2><?= conteudo($conteudos, 'estat4_valor') ?></h2>
            <p><?= conteudo($conteudos, 'estat4_label') ?></p>
        </div>

    </section>

    <!-- Serviços -->

    <!-- Serviços -->
    <section class="servicos" id="servicos">

        <div class="servicos-titulo">
            <h2><?= conteudo($conteudos, 'servicos_titulo') ?></h2>
            <p><?= conteudo($conteudos, 'servicos_subtitulo') ?></p>
        </div>

        <div class="servicos-cards">

            <div class="servico-card">

                <h3><?= conteudo($conteudos, 'serv1_titulo') ?></h3>
                <p><?= conteudo($conteudos, 'serv1_texto') ?></p>
            </div>

            <div class="servico-card">

                <h3><?= conteudo($conteudos, 'serv2_titulo') ?></h3>
                <p><?= conteudo($conteudos, 'serv2_texto') ?></p>
            </div>

            <div class="servico-card">

                <h3><?= conteudo($conteudos, 'serv3_titulo') ?></h3>
                <p><?= conteudo($conteudos, 'serv3_texto') ?></p>
            </div>

            <div class="servico-card">

                <h3><?= conteudo($conteudos, 'serv4_titulo') ?></h3>
                <p><?= conteudo($conteudos, 'serv4_texto') ?></p>
            </div>

        </div>

    </section>
    <!-- FAQ -->

    <section class="faq-section" id="faq">

        <div class="faq-title">
            <h2><?= conteudo($conteudos, 'faq_titulo') ?></h2>
            <p><?= conteudo($conteudos, 'faq_subtitulo') ?></p>
        </div>

        <div class="accordion faq-container" id="accordionFAQ">

            <!-- FAQ 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        <?= conteudo($conteudos, 'faq1_pergunta') ?>
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        <?= conteudo($conteudos, 'faq1_resposta') ?>
                    </div>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq2">
                        <?= conteudo($conteudos, 'faq2_pergunta') ?>
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        <?= conteudo($conteudos, 'faq2_resposta') ?>
                    </div>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq3">
                        <?= conteudo($conteudos, 'faq3_pergunta') ?>
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        <?= conteudo($conteudos, 'faq3_resposta') ?>
                    </div>
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq4">
                        <?= conteudo($conteudos, 'faq4_pergunta') ?>
                    </button>
                </h2>
                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        <?= conteudo($conteudos, 'faq4_resposta') ?>
                    </div>
                </div>

            </div>

            <!-- FAQ 5 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq5">
                        <?= conteudo($conteudos, 'faq5_pergunta') ?>
                    </button>
                </h2>
                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        <?= conteudo($conteudos, 'faq5_resposta') ?>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- Footer -->
    <footer class="footer" id="contactos">

        <!-- contactos -->
        <div class="footer-section">

            <h3><?= conteudo($conteudos, 'footer_titulo1') ?></h3>
            <p>
                <i class="fa-solid fa-envelope"></i>
                <?= conteudo($conteudos, 'contacto_email') ?>
            </p>
            <p>
                <i class="fa-solid fa-phone"></i>
                <?= conteudo($conteudos, 'contacto_telefone') ?>
            </p>
            <p>
                <i class="fa-solid fa-location-dot"></i>
                <?= conteudo($conteudos, 'contacto_local') ?>
            </p>

        </div>

        <div class="footer-section">

            <h3><?= conteudo($conteudos, 'footer_titulo2') ?></h3>
            <p>
                Versão da plataforma: <?= conteudo($conteudos, 'plataforma_versao') ?>
            </p>
            <p>
                Última atualização: <?= conteudo($conteudos, 'plataforma_atualizacao') ?>
            </p>
            <p class="estado-sistema">
                <span class="status-online"></span>
                <?= conteudo($conteudos, 'plataforma_estado') ?>
            </p>

        </div>

    </footer>

    <!-- Rodapé Final -->

    <div class="footer-bottom">

        <p>
            <?php echo APP_COPYRIGHT; ?>
        </p>

    </div>

    <!-- Bootstrap JS -->
    <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>

</html>