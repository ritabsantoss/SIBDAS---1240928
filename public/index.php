<?php require_once __DIR__ . '/../config/config.php'; ?>

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
            <a href="../private/login.html">
                Área Privada
            </a>
        </div>

    </nav>

    <!-- Hero Section-->
    <section class="hero">

        <div class="hero-text">
            <h1>
                Apoio ao Inventário Hospitalar de Equipamentos Médicos
            </h1>

            <p>
                A SIHEM permite organizar, monitorizar e gerir
                o inventário hospitalar de equipamentos médicos
                de forma centralizada, segura e eficiente.
            </p>

            <a href="#servicos" class="hero-button">
                Explore a nossa Plataforma
            </a>
        </div>

        <div class="hero-image">
            <img src="../assets/img/sihem3.jpg" alt="Hospital">
        </div>

    </section>

    <!-- Estatísticas -->

    <section class="estatisticas">

        <div class="estatistica-card">
            <i class="fa-solid fa-laptop-medical"></i>
            <h2>1500+</h2>
            <p>Equipamentos Registados</p>
        </div>

        <div class="estatistica-card">
            <i class="fa-solid fa-hospital"></i>
            <h2>45</h2>
            <p>Hospitais Associados</p>
        </div>

        <div class="estatistica-card">
            <i class="fa-solid fa-user-doctor"></i>
            <h2>120</h2>
            <p>Técnicos Especializados</p>
        </div>

        <div class="estatistica-card">
            <i class="fa-solid fa-clock"></i>
            <h2>24/7</h2>
            <p>Monitorização Contínua</p>
        </div>

    </section>

    <!-- Serviços -->

    <section class="servicos" id="servicos">

        <div class="servicos-titulo">
            <h2>Serviços da Plataforma</h2>

            <p>
                Funcionalidades desenvolvidas para apoiar
                a gestão hospitalar de equipamentos médicos.
            </p>

        </div>

        <div class="servicos-cards">

            <div class="servico-card">

                <i class="fa-solid fa-laptop-file"></i>
                <h3>Gestão de Equipamentos</h3>
                <p>
                    Registo e consulta de equipamentos médicos
                    com informação técnica detalhada.
                </p>

            </div>

            <div class="servico-card">

                <i class="fa-solid fa-file-medical"></i>
                <h3>Documentação Técnica</h3>
                <p>
                    Armazenamento de manuais, garantias,
                    relatórios e documentação associada.
                </p>

            </div>

            <div class="servico-card">

                <i class="fa-solid fa-location-dot"></i>
                <h3>Localização Hospitalar</h3>
                <p>
                    Monitorização da localização física
                    dos equipamentos hospitalares.
                </p>

            </div>

            <div class="servico-card">

                <i class="fa-solid fa-chart-pie"></i>

                <h3>Dashboard Estatístico</h3>

                <p>
                    Visualização rápida de indicadores
                    e estatísticas do inventário hospitalar.
                </p>

            </div>

        </div>

    </section>

    <!-- FAQ -->

    <section class="faq-section" id="faq">

        <div class="faq-title">

            <h2>Perguntas Frequentes</h2>
            <p>
                Esclareça as principais dúvidas sobre a plataforma SIHEM.
            </p>

        </div>

        <div class="accordion faq-container" id="accordionFAQ">

            <!-- FAQ 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        O que é a plataforma SIHEM?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        A SIHEM é uma plataforma de gestão de inventário hospitalar
                        desenvolvida para organizar equipamentos médicos e documentação técnica.
                    </div>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq2">
                        É possível localizar equipamentos hospitalares?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        Sim. O sistema permite acompanhar a localização dos equipamentos
                        dentro das diferentes áreas hospitalares.
                    </div>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq3">
                        A plataforma armazena documentação técnica?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        A SIHEM permite guardar manuais, relatórios,
                        garantias e histórico técnico dos equipamentos.
                    </div>
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq4">
                        O sistema apresenta estatísticas e dashboards?
                    </button>
                </h2>
                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        Sim. A plataforma inclui dashboards com indicadores
                        estatísticos relacionados com os equipamentos registados.
                    </div>
                </div>

            </div>

            <!-- FAQ 5 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq5">
                        Quem pode utilizar a plataforma?
                    </button>
                </h2>
                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        A plataforma destina-se a hospitais,
                        clínicas e técnicos responsáveis pela gestão de equipamentos médicos.
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- Footer -->

    <footer class="footer" id="contactos">

        <!-- contactos -->
        <div class="footer-section">

            <h3>APOIO TÉCNICO</h3>
            <p>
                <i class="fa-solid fa-envelope"></i>
                geral@sihem.pt
            </p>
            <p>
                <i class="fa-solid fa-phone"></i>
                +351 912 222 222
            </p>
            <p>
                <i class="fa-solid fa-location-dot"></i>
                Porto, Portugal
            </p>

        </div>


        <div class="footer-section">

            <h3>PLATAFORMA</h3>
            <p>
                Versão da plataforma: v<?php echo APP_VERSION; ?>
            </p>
            <p>
                Última atualização: Junho 2026
            </p>
            <p class="estado-sistema">
                <span class="status-online"></span>
                Sistema Online
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