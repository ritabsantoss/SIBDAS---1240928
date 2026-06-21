<?php
require_once __DIR__ . '/includes/funcoes.php';
redirect_if_not_logged();

$pagina_ativa = 'inicio';

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>

<div class="private-container">

    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <!-- Conteúdo -->
    <main class="private-main">

        <section class="welcome-card">

            <div>

                <h1>
                    <?= ($_SESSION['genero'] ?? '') === 'F' ? 'Bem-vinda' : 'Bem-vindo' ?>, <?= htmlspecialchars($_SESSION['nome'] ?? '') ?>!
                </h1>

                <?php if ($_SESSION['perfil'] === 'administrador') : ?>
                    <p>
                        Esta área permite administrar todo o inventário hospitalar, gerir equipamentos,
                        fornecedores, localizações e documentação técnica. Permite ainda 
                        acompanhar indicadores e relatórios do sistema e gerir os conteúdos públicos.
                    </p>

                <?php elseif ($_SESSION['perfil'] === 'tecnico') : ?>
                    <p>
                        Esta área permite acompanhar o estado dos equipamentos médicos, fornecedores e 
                        localizações, consultar documentação técnica e apoiar as atividades
                        de manutenção e gestão do parque tecnológico.
                    </p>

                <?php else : ?>
                    <p>
                        Esta área permite consultar a ficha dos equipamentos médicos registados no sistema,
                        verificar a sua localização, documentação técnica e outras informações
                        relevantes associadas.
                    </p>
                <?php endif; ?>

                <?php if ($_SESSION['perfil'] === 'administrador' || $_SESSION['perfil'] === 'tecnico') : ?>
                    <a href="<?php echo BASE_URL; ?>/private/views/dashboard.php" class="btn welcome-btn">
                        Ir para o Dashboard
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                <?php else : ?>
                    <a href="<?php echo BASE_URL; ?>/private/views/equipamentos/lista.php" class="btn welcome-btn">
                        Ver Equipamentos
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                <?php endif; ?>

            </div>

            <div class="welcome-icon">

                <i class="fa-solid fa-laptop-medical"></i>

            </div>

        </section>

    </main>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>