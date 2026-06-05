<?php $pagina_ativa = 'inicio'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="private-container">

    <?php include 'includes/sidebar.php'; ?>

    <!-- Conteúdo -->
    <main class="private-main">

        <section class="welcome-card">

            <div>

                <h1>
                    Bem-vindo à Área Reservada
                </h1>

                <p>
                    Esta área permite gerir o inventário hospitalar de equipamentos médicos,
                    consultar fornecedores, acompanhar localizações, visualizar documentação técnica
                    e monitorizar garantias e contratos associados aos equipamentos.
                </p>

                <a href="<?php echo BASE_URL; ?>/private/views/dashboard.php" class="btn welcome-btn">
                    Ir para o Dashboard
                    <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>

            </div>

            <div class="welcome-icon">

                <i class="fa-solid fa-laptop-medical"></i>

            </div>

        </section>

    </main>

</div>

<?php include 'includes/footer.php'; ?>