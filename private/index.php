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

                <p>
                    Esta área permite gerir o inventário hospitalar de equipamentos médicos,
                    consultar fornecedores, acompanhar localizações, visualizar documentação técnica
                    e monitorizar garantias e contratos associados aos equipamentos.
                </p>

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