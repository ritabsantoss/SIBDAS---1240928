<?php
session_start();

// Segurança: só por POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: login.php');
    return;
}

// Recolha dos dados
$email    = isset($_POST['email'])    ? $_POST['email']    : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validação
$validation_errors = [];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $validation_errors[] = 'O utilizador tem de ser um email válido.';
}
if (strlen($email) > 100) { // ajusta este 100 ao tamanho da coluna "email" na tua BD
    $validation_errors[] = 'O email é demasiado longo.';
}
if (strlen($password) < 8) {
    $validation_errors[] = 'A palavra-passe deve ter pelo menos 8 caracteres.';
}

// Se houver erros, guarda na sessão e volta ao login
if (!empty($validation_errors)) {
    $_SESSION['validation_errors'] = $validation_errors;
    header('Location: login.php');
    return;
}

// (temporário) mostra os dados se passou na validação
echo "Utilizador: " . $email . "<br>";
echo "Password: " . $password;
?>
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